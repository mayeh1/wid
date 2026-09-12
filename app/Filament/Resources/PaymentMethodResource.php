<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\AuthorizesViaPermissions;
use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PaymentMethodResource extends Resource
{
    use AuthorizesViaPermissions;

    protected static string $permissionGroup = 'payment_methods';

    protected static ?string $model = PaymentMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Donations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('logo')->collection('logo')->image()->columnSpanFull(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $state, Forms\Set $set, Forms\Get $get) => $get('slug') === null && $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->options(['manual' => 'Manual / Instructional', 'gateway' => 'Gateway (API-based)'])
                            ->required()
                            ->live()
                            ->helperText('Manual methods just show instructions to the donor (e.g. Bank Transfer). Gateway methods process payment automatically.'),
                        Forms\Components\Select::make('driver')
                            ->options(PaymentMethod::DRIVERS)
                            ->live()
                            ->visible(fn (Forms\Get $get) => $get('type') === 'gateway')
                            ->required(fn (Forms\Get $get) => $get('type') === 'gateway')
                            ->helperText(fn (Forms\Get $get) => in_array($get('driver'), ['square', 'authorize_net'])
                                ? 'Not yet available on this site — needs additional development before it can accept real payments. Use Stripe, PayPal, Paystack, or Flutterwave instead.'
                                : null),
                        Forms\Components\TextInput::make('order')->numeric()->default(0),
                        Forms\Components\Toggle::make('is_enabled')->default(true),
                    ]),

                Forms\Components\Section::make('Manual Payment Instructions')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'manual')
                    ->schema([
                        Forms\Components\Textarea::make('instructions')
                            ->rows(4)
                            ->helperText('Shown to donors, e.g. account name/number for Bank Transfer, or a $Cashtag for CashApp.'),
                    ]),

                Forms\Components\Section::make('Stripe Credentials')
                    ->visible(fn (Forms\Get $get) => $get('driver') === 'stripe')
                    ->description('From your Stripe Dashboard → Developers → API keys.')
                    ->schema([
                        Forms\Components\TextInput::make('stripe_secret_key')
                            ->label('Secret Key')
                            ->password()
                            ->revealable()
                            ->placeholder('sk_live_... or sk_test_...')
                            ->helperText('Starts with sk_test_ while testing, sk_live_ once you\'re ready for real donations.'),
                    ]),

                Forms\Components\Section::make('PayPal Credentials')
                    ->visible(fn (Forms\Get $get) => $get('driver') === 'paypal')
                    ->description('From the PayPal Developer Dashboard → Apps & Credentials.')
                    ->schema([
                        Forms\Components\TextInput::make('paypal_client_id')
                            ->label('Client ID'),
                        Forms\Components\TextInput::make('paypal_secret')
                            ->label('Secret')
                            ->password()
                            ->revealable(),
                        Forms\Components\Toggle::make('paypal_sandbox')
                            ->label('Sandbox / Test Mode')
                            ->default(true)
                            ->helperText('Turn this OFF only once you\'ve tested a real small donation and are ready to accept live payments.'),
                    ]),

                Forms\Components\Section::make('Paystack Credentials')
                    ->visible(fn (Forms\Get $get) => $get('driver') === 'paystack')
                    ->description('From your Paystack Dashboard → Settings → API Keys & Webhooks.')
                    ->schema([
                        Forms\Components\TextInput::make('paystack_secret_key')
                            ->label('Secret Key')
                            ->password()
                            ->revealable()
                            ->placeholder('sk_live_... or sk_test_...'),
                    ]),

                Forms\Components\Section::make('Flutterwave Credentials')
                    ->visible(fn (Forms\Get $get) => $get('driver') === 'flutterwave')
                    ->description('From your Flutterwave Dashboard → Settings → API.')
                    ->schema([
                        Forms\Components\TextInput::make('flutterwave_secret_key')
                            ->label('Secret Key')
                            ->password()
                            ->revealable()
                            ->placeholder('FLWSECK_TEST-... or FLWSECK-...'),
                    ]),

                Forms\Components\Section::make('Credentials')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'gateway' && in_array($get('driver'), ['square', 'authorize_net']))
                    ->schema([
                        Forms\Components\KeyValue::make('config')
                            ->keyLabel('Setting')
                            ->valueLabel('Value')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * The Stripe/PayPal/Paystack/Flutterwave fields above are flat (not
     * dot-notated into `config`) because Filament doesn't dehydrate
     * dot-notated fields nested under conditionally-visible sibling
     * Sections reliably. Instead each driver's fields are assembled into
     * the real `config` array here, and stripped from $data so they never
     * hit the database as their own columns.
     */
    private const DRIVER_FIELD_MAP = [
        'stripe' => ['stripe_secret_key' => 'secret_key'],
        'paystack' => ['paystack_secret_key' => 'secret_key'],
        'flutterwave' => ['flutterwave_secret_key' => 'secret_key'],
        'paypal' => [
            'paypal_client_id' => 'client_id',
            'paypal_secret' => 'secret',
            'paypal_sandbox' => 'sandbox',
        ],
    ];

    public static function assembleConfigFromFlatFields(array $data): array
    {
        $allFlatKeys = collect(self::DRIVER_FIELD_MAP)->flatMap(fn (array $map) => array_keys($map))->all();

        if (isset(self::DRIVER_FIELD_MAP[$data['driver'] ?? null])) {
            $config = [];

            foreach (self::DRIVER_FIELD_MAP[$data['driver']] as $field => $configKey) {
                if (array_key_exists($field, $data)) {
                    $config[$configKey] = $data[$field];
                }
            }

            $data['config'] = $config;
        }

        foreach ($allFlatKeys as $field) {
            unset($data[$field]);
        }

        return $data;
    }

    /**
     * Reverse of assembleConfigFromFlatFields(), used to populate the flat
     * fields when opening the edit form for an existing record.
     */
    public static function spreadConfigIntoFlatFields(array $data): array
    {
        if (isset(self::DRIVER_FIELD_MAP[$data['driver'] ?? null])) {
            foreach (self::DRIVER_FIELD_MAP[$data['driver']] as $field => $configKey) {
                $data[$field] = $data['config'][$configKey] ?? null;
            }
        }

        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')->collection('logo'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('type')->badge()->color(fn (string $state) => $state === 'gateway' ? 'warning' : 'gray'),
                Tables\Columns\TextColumn::make('driver')->formatStateUsing(fn (?string $state) => $state ? PaymentMethod::DRIVERS[$state] ?? $state : '—'),
                Tables\Columns\IconColumn::make('is_enabled')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

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
                            ->afterStateUpdated(fn (string $state, Forms\Set $set, ?string $slug) => $slug === null && $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->options(['manual' => 'Manual / Instructional', 'gateway' => 'Gateway (API-based)'])
                            ->required()
                            ->live()
                            ->helperText('Manual methods just show instructions to the donor (e.g. Bank Transfer). Gateway methods process payment automatically.'),
                        Forms\Components\Select::make('driver')
                            ->options(PaymentMethod::DRIVERS)
                            ->visible(fn (Forms\Get $get) => $get('type') === 'gateway')
                            ->required(fn (Forms\Get $get) => $get('type') === 'gateway'),
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

                Forms\Components\Section::make('Gateway Credentials')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'gateway')
                    ->schema([
                        Forms\Components\KeyValue::make('config')
                            ->keyLabel('Setting')
                            ->valueLabel('Value')
                            ->helperText('e.g. secret_key, client_id — stored encrypted. See each driver\'s class docblock for the exact keys it reads.')
                            ->columnSpanFull(),
                    ]),
            ]);
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

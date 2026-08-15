<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationGroup = 'Donations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('receipt_number')->disabled(),
                        Forms\Components\TextInput::make('amount')->numeric()->prefix('$')->required(),
                        Forms\Components\TextInput::make('donor_name')->required()->maxLength(255),
                        Forms\Components\TextInput::make('donor_email')->email()->required()->maxLength(255),
                        Forms\Components\Select::make('frequency')->options([
                            'one_time' => 'One-Time', 'monthly' => 'Monthly', 'annual' => 'Annual',
                        ])->required(),
                        Forms\Components\Select::make('status')->options([
                            'pending' => 'Pending', 'completed' => 'Completed', 'failed' => 'Failed', 'refunded' => 'Refunded',
                        ])->required(),
                        Forms\Components\Select::make('payment_method_id')->relationship('paymentMethod', 'name'),
                        Forms\Components\Select::make('campaign_id')->relationship('campaign', 'title'),
                        Forms\Components\Select::make('project_id')->relationship('project', 'title'),
                        Forms\Components\Toggle::make('is_anonymous'),
                        Forms\Components\Textarea::make('notes')->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('receipt_number')->searchable()->weight(FontWeight::SemiBold),
                Tables\Columns\TextColumn::make('donor_name')->searchable()->formatStateUsing(fn (Donation $record) => $record->displayName()),
                Tables\Columns\TextColumn::make('amount')->money('usd')->sortable(),
                Tables\Columns\TextColumn::make('frequency')->badge(),
                Tables\Columns\TextColumn::make('paymentMethod.name')->label('Method'),
                Tables\Columns\TextColumn::make('campaign.title')->label('Campaign')->toggleable(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state) => match ($state) {
                    'completed' => 'success',
                    'failed', 'refunded' => 'danger',
                    default => 'warning',
                }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending', 'completed' => 'Completed', 'failed' => 'Failed', 'refunded' => 'Refunded',
                ]),
                Tables\Filters\SelectFilter::make('frequency')->options([
                    'one_time' => 'One-Time', 'monthly' => 'Monthly', 'annual' => 'Annual',
                ]),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(\App\Filament\Exports\DonationExporter::class),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->visible(fn (Donation $record) => $record->status === 'pending')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Donation $record) {
                        $record->update(['status' => 'completed', 'approved_at' => now(), 'approved_by' => auth()->id()]);
                        Notification::make()->title('Donation approved')->success()->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->visible(fn (Donation $record) => $record->status === 'pending')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Donation $record) {
                        $record->update(['status' => 'failed']);
                        Notification::make()->title('Donation rejected')->danger()->send();
                    }),
                Tables\Actions\Action::make('receipt')
                    ->visible(fn (Donation $record) => $record->status === 'completed')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (Donation $record) => route('donations.receipt', $record->receipt_number))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListDonations::route('/'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

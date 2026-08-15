<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentDonationsTable extends BaseWidget
{
    protected static ?string $heading = 'Recent Donations';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Donation::query()->latest())
            ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\TextColumn::make('receipt_number'),
                Tables\Columns\TextColumn::make('donor_name')->formatStateUsing(fn (Donation $record) => $record->displayName()),
                Tables\Columns\TextColumn::make('amount')->money('usd'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state) => match ($state) {
                    'completed' => 'success',
                    'failed', 'refunded' => 'danger',
                    default => 'warning',
                }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->since(),
            ]);
    }
}

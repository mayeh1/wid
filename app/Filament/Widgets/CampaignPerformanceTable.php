<?php

namespace App\Filament\Widgets;

use App\Models\Campaign;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CampaignPerformanceTable extends BaseWidget
{
    protected static ?string $heading = 'Campaign Performance';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Campaign::query()->published())
            ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('target_amount')->money('usd')->label('Goal'),
                Tables\Columns\TextColumn::make('raised')->state(fn (Campaign $record) => '$'.number_format($record->raisedAmount(), 2))->label('Raised'),
                Tables\Columns\TextColumn::make('progress')->state(fn (Campaign $record) => $record->progressPercent().'%')->label('Progress'),
            ]);
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use Filament\Widgets\ChartWidget;

class DonationsChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Donations (Last 12 Months)';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $months = collect(range(11, 0))->map(fn ($i) => now()->subMonths($i));

        $totals = $months->map(function ($month) {
            return Donation::completed()
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('amount');
        });

        return [
            'datasets' => [
                [
                    'label' => 'Donations ($)',
                    'data' => $totals->values()->all(),
                    'borderColor' => '#D4AF37',
                    'backgroundColor' => 'rgba(212, 175, 55, 0.15)',
                    'fill' => true,
                ],
            ],
            'labels' => $months->map(fn ($m) => $m->format('M Y'))->values()->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

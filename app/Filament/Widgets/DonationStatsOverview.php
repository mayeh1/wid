<?php

namespace App\Filament\Widgets;

use App\Models\Campaign;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\Volunteer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DonationStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $raisedThisMonth = Donation::completed()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount');
        $raisedLastMonth = Donation::completed()->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->sum('amount');
        $trend = $raisedLastMonth > 0 ? round((($raisedThisMonth - $raisedLastMonth) / $raisedLastMonth) * 100) : 0;

        return [
            Stat::make('Raised This Month', '$'.number_format($raisedThisMonth, 2))
                ->description(($trend >= 0 ? '+' : '').$trend.'% vs last month')
                ->descriptionIcon($trend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($trend >= 0 ? 'success' : 'danger'),

            Stat::make('Pending Donations', Donation::pending()->count())
                ->description('Awaiting approval or verification')
                ->color('warning'),

            Stat::make('Active Campaigns', Campaign::published()->count())
                ->description(Campaign::published()->where('is_featured', true)->count().' featured'),

            Stat::make('Pending Volunteers', Volunteer::where('status', 'pending')->count())
                ->description(Volunteer::approved()->count().' approved total'),

            Stat::make('Unread Messages', ContactMessage::where('is_read', false)->count())
                ->description('Contact form submissions'),
        ];
    }
}

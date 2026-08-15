<?php

namespace App\Filament\Pages;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Volunteer;
use Filament\Pages\Page;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Donations';

    protected static string $view = 'filament.pages.reports';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('donations.view') ?? false;
    }

    public function getDonationTotals(): array
    {
        return [
            'total_raised' => Donation::completed()->sum('amount'),
            'total_donations' => Donation::completed()->count(),
            'pending' => Donation::pending()->count(),
            'this_month' => Donation::completed()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount'),
        ];
    }

    public function getCampaigns()
    {
        return Campaign::published()->get();
    }

    public function getVolunteerTotals(): array
    {
        return [
            'approved' => Volunteer::approved()->count(),
            'pending' => Volunteer::where('status', 'pending')->count(),
            'total_hours' => Volunteer::approved()->get()->sum(fn (Volunteer $v) => $v->totalHours()),
        ];
    }
}

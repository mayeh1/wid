<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Volunteer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function summaryPdf(Request $request)
    {
        abort_unless($request->user()->hasAnyRole(['Super Admin', 'Admin', 'Finance Manager']), 403);

        $data = [
            'generatedAt' => now(),
            'totalRaised' => Donation::completed()->sum('amount'),
            'totalDonations' => Donation::completed()->count(),
            'pendingDonations' => Donation::pending()->count(),
            'campaigns' => Campaign::published()->get(),
            'volunteerApproved' => Volunteer::approved()->count(),
            'volunteerPending' => Volunteer::where('status', 'pending')->count(),
            'volunteerHours' => Volunteer::approved()->get()->sum(fn (Volunteer $v) => $v->totalHours()),
        ];

        $pdf = Pdf::loadView('admin.reports.summary-pdf', $data);

        return $pdf->download('WID-Report-'.now()->format('Y-m-d').'.pdf');
    }
}

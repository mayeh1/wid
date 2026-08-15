<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    public function create()
    {
        $existing = auth()->user()->volunteer;

        if ($existing) {
            return redirect()->route('volunteer.dashboard');
        }

        return view('volunteer.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'phone' => ['nullable', 'string', 'max:255'],
            'skills' => ['nullable', 'string', 'max:1000'],
            'availability' => ['nullable', 'string', 'max:1000'],
            'bio' => ['nullable', 'string', 'max:2000'],
        ]);

        auth()->user()->volunteer()->create([
            'phone' => $data['phone'] ?? null,
            'skills' => $data['skills'] ? array_map('trim', explode(',', $data['skills'])) : [],
            'availability' => $data['availability'] ?? null,
            'bio' => $data['bio'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('volunteer.dashboard')->with('status', 'Thanks for applying! Our Volunteer Manager will review your application soon.');
    }

    public function dashboard()
    {
        $volunteer = auth()->user()->volunteer;

        if (! $volunteer) {
            return redirect()->route('volunteer.create');
        }

        return view('volunteer.dashboard', [
            'volunteer' => $volunteer->load('hourLogs'),
        ]);
    }

    public function certificate()
    {
        $volunteer = auth()->user()->volunteer;

        abort_unless($volunteer && $volunteer->status === 'approved' && $volunteer->totalHours() > 0, 404);

        $pdf = Pdf::loadView('volunteer.certificate-pdf', [
            'volunteer' => $volunteer->load('user'),
        ]);

        return $pdf->download('WID-Volunteer-Certificate.pdf');
    }
}

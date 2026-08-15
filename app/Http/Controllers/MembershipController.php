<?php

namespace App\Http\Controllers;

use App\Models\MembershipLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function create()
    {
        return view('membership.create', [
            'levels' => MembershipLevel::active()->orderBy('order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'membership_level_id' => ['required', 'exists:membership_levels,id'],
        ]);

        auth()->user()->memberships()->create([
            'membership_level_id' => $data['membership_level_id'],
            'status' => 'active',
            'started_at' => now(),
            'expires_at' => now()->addYear(),
        ]);

        return redirect()->route('membership.dashboard')->with('status', 'Welcome to Women in Development! Your membership is now active.');
    }

    public function dashboard()
    {
        $membership = auth()->user()->membership()->with('level')->first();

        if (! $membership) {
            return redirect()->route('membership.create');
        }

        return view('membership.dashboard', ['membership' => $membership]);
    }

    public function renew(): RedirectResponse
    {
        $membership = auth()->user()->membership;

        abort_unless($membership, 404);

        $membership->update([
            'status' => 'active',
            'expires_at' => now()->addYear(),
        ]);

        return back()->with('status', 'Your membership has been renewed for another year.');
    }
}

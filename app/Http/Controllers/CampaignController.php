<?php

namespace App\Http\Controllers;

use App\Models\Campaign;

class CampaignController extends Controller
{
    public function index()
    {
        return view('campaigns.index', [
            'campaigns' => Campaign::published()->latest()->get(),
        ]);
    }

    public function show(string $slug)
    {
        $campaign = Campaign::published()->where('slug', $slug)->firstOrFail();

        return view('campaigns.show', [
            'campaign' => $campaign,
            'donorWall' => $campaign->donorWall(),
        ]);
    }
}

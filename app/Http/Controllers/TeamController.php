<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\TeamMember;

class TeamController extends Controller
{
    public function index()
    {
        return view('team', [
            'settings' => SiteSetting::current(),
            'staff' => TeamMember::active()->ofCategory('staff')->orderBy('order')->get(),
            'advisors' => TeamMember::active()->ofCategory('advisor')->orderBy('order')->get(),
        ]);
    }
}

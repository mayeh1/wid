<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\TeamMember;

class AboutController extends Controller
{
    public function index()
    {
        return view('about', [
            'settings' => SiteSetting::current(),
            'page' => Page::where('slug', 'about')->where('is_published', true)->first(),
            'founder' => TeamMember::active()->ofCategory('founder')->orderBy('order')->first(),
            'boardMembers' => TeamMember::active()->ofCategory('board_member')->orderBy('order')->get(),
            'staff' => TeamMember::active()->ofCategory('staff')->orderBy('order')->get(),
        ]);
    }
}

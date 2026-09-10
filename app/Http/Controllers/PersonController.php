<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\TeamMember;

class PersonController extends Controller
{
    public function show(string $slug)
    {
        $person = TeamMember::active()->where('slug', $slug)->firstOrFail();

        return view('people.show', [
            'settings' => SiteSetting::current(),
            'person' => $person,
        ]);
    }
}

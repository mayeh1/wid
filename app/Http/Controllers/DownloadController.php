<?php

namespace App\Http\Controllers;

use App\Models\Download;

class DownloadController extends Controller
{
    public function index()
    {
        return view('resources.index', [
            'downloads' => Download::published()->orderByDesc('year')->get()->groupBy('category'),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $projects = Project::published()
            ->when($status, fn ($query) => $query->status($status))
            ->latest('start_date')
            ->get();

        return view('projects.index', [
            'projects' => $projects,
            'status' => $status,
        ]);
    }

    public function show(string $slug)
    {
        $project = Project::published()->where('slug', $slug)->firstOrFail();

        return view('projects.show', ['project' => $project]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Program;

class ProgramController extends Controller
{
    public function index()
    {
        return view('programs.index', [
            'programs' => Program::published()->orderBy('order')->get(),
        ]);
    }

    public function show(string $slug)
    {
        $program = Program::published()->where('slug', $slug)->firstOrFail();

        return view('programs.show', [
            'program' => $program,
            'related' => Program::published()->where('id', '!=', $program->id)->where('category', $program->category)->limit(3)->get(),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SuccessStory;

class SuccessStoryController extends Controller
{
    public function index()
    {
        return view('success-stories.index', [
            'stories' => SuccessStory::published()->latest()->get(),
        ]);
    }

    public function show(string $slug)
    {
        $story = SuccessStory::published()->where('slug', $slug)->firstOrFail();

        return view('success-stories.show', [
            'story' => $story,
            'related' => SuccessStory::published()->where('id', '!=', $story->id)->limit(3)->get(),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;

class NewsController extends Controller
{
    public function index()
    {
        return view('news.index', [
            'posts' => NewsPost::published()->latest('published_at')->paginate(9),
        ]);
    }

    public function show(string $slug)
    {
        $post = NewsPost::published()->where('slug', $slug)->firstOrFail();

        return view('news.show', ['post' => $post]);
    }
}

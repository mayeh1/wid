<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->query('category');
        $search = $request->query('q');

        $posts = BlogPost::published()
            ->when($categorySlug, fn ($query) => $query->whereHas('category', fn ($c) => $c->where('slug', $categorySlug)))
            ->when($search, fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('blog.index', [
            'posts' => $posts,
            'categories' => BlogCategory::withCount('posts')->get(),
            'categorySlug' => $categorySlug,
            'search' => $search,
        ]);
    }

    public function show(string $slug)
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();

        return view('blog.show', [
            'post' => $post,
            'related' => BlogPost::published()->where('id', '!=', $post->id)->where('blog_category_id', $post->blog_category_id)->limit(3)->get(),
            'comments' => $post->approvedComments()->latest()->get(),
        ]);
    }

    public function comment(Request $request, string $slug): RedirectResponse
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $post->comments()->create($data + ['is_approved' => false]);

        return back()->with('status', 'Thanks for your comment! It will appear once approved.');
    }
}

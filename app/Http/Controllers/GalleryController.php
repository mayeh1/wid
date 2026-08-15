<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;

class GalleryController extends Controller
{
    public function index()
    {
        return view('gallery.index', [
            'albums' => GalleryAlbum::published()->orderBy('order')->get(),
        ]);
    }

    public function show(string $slug)
    {
        $album = GalleryAlbum::published()->where('slug', $slug)->firstOrFail();

        return view('gallery.show', ['album' => $album]);
    }
}

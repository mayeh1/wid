<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        return view('faqs.index', [
            'faqs' => Faq::published()->orderBy('order')->get()->groupBy(fn ($faq) => $faq->category ?: 'General'),
        ]);
    }
}

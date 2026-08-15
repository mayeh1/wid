<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Models\Partner;
use App\Models\Program;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'settings' => SiteSetting::current(),
            'heroSlides' => HeroSlide::published()->orderBy('order')->get(),
            'featuredPrograms' => Program::published()->featured()->orderBy('order')->limit(4)->get(),
            'featuredProjects' => Project::published()->where('is_featured', true)->limit(3)->get(),
            'testimonials' => Testimonial::published()->featured()->orderBy('order')->limit(6)->get(),
            'partners' => Partner::published()->orderBy('order')->get(),
        ]);
    }
}

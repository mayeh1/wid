<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

$comingSoonPages = [
    'about' => 'About Us',
    'programs' => 'Programs',
    'projects' => 'Projects',
    'events' => 'Events',
    'blog' => 'Blog',
    'contact' => 'Contact',
    'donate' => 'Donate',
    'volunteer' => 'Become a Volunteer',
    'membership' => 'Membership',
    'resources' => 'Resources & Reports',
    'success-stories' => 'Success Stories',
];

foreach ($comingSoonPages as $slug => $pageTitle) {
    Route::view("/{$slug}", 'coming-soon', ['pageTitle' => $pageTitle])->name($slug);
}

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

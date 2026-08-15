<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return view('events.index', [
            'upcoming' => Event::published()->upcoming()->get(),
            'past' => Event::published()->past()->limit(9)->get(),
        ]);
    }

    public function show(string $slug)
    {
        $event = Event::published()->where('slug', $slug)->firstOrFail();

        return view('events.show', ['event' => $event]);
    }

    public function register(Request $request, string $slug): RedirectResponse
    {
        $event = Event::published()->where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'guests' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $event->registrations()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'guests' => $data['guests'] ?? 1,
        ]);

        return back()->with('status', 'You are registered for this event. We look forward to seeing you!');
    }
}

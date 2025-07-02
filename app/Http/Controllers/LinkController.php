<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    public function index()
    {
        $links = auth()->user()->links()->withCount('visits')->get();
        return view('links.index', compact('links'));
    }

    public function create()
    {
        return view('links.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url'
        ]);

        $link = auth()->user()->links()->create([
            'original_url' => $request->original_url,
            'short_code' => Str::random(6),
        ]);

        return redirect()->route('links.index')->with('success', 'Link created successfully!');
    }

    public function show(Link $link)
    {
        if ($link->user_id !== auth()->id()) {
            abort(403);
        }

        $visits = $link->visits()->latest()->paginate(10);
        return view('links.show', compact('link', 'visits'));
    }

    public function destroy(Link $link)
    {
        if ($link->user_id !== auth()->id()) {
            abort(403);
        }

        $link->delete();
        return redirect()->route('links.index')->with('success', 'Link deleted successfully!');
    }
}

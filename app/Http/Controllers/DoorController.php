<?php

namespace App\Http\Controllers;

use App\Models\Door;
use Illuminate\Http\Request;

class DoorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doors = Door::latest()->get();
        return view('doors.index', compact('doors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        ]);

        $imagePath = $request->file('image')->store('doors', 'public');

        Door::create([
            'name' => $validated['name'],
            'image_path' => $imagePath,
        ]);

        return redirect()->route('home')->with('success', 'Door uploaded successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Door $door)
    {
        $door->delete();
        return redirect()->route('home')->with('success', 'Door deleted successfully!');
    }

    // Not needed anymore
    public function create() {}
    public function show(Door $door) {}
    public function edit(Door $door) {}
    public function update(Request $request, Door $door) {}
}

<?php

namespace App\Http\Controllers;

use App\Models\Door;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function store(Request $request)
    {
        // Check if voting is enabled
        if (!config('voting.enabled')) {
            return redirect()->route('home')->with('error', 'Voting is currently disabled.');
        }

        $validated = $request->validate([
            'voter_name' => 'required|string|max:255',
            'votes' => 'required|array|min:1|max:3',
            'votes.*' => 'required|exists:doors,id',
        ]);

        // Delete existing votes for this voter
        Vote::where('voter_name', $validated['voter_name'])->delete();

        // Store new ranked votes
        foreach ($validated['votes'] as $rank => $doorId) {
            Vote::create([
                'voter_name' => $validated['voter_name'],
                'door_id' => $doorId,
                'rank' => $rank + 1, // rank starts at 1
            ]);
        }

        return redirect()->route('home')->with('success', 'Thanks for voting, ' . $validated['voter_name'] . '!');
    }
}

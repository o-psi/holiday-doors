<?php

use App\Http\Controllers\DoorController;
use App\Http\Controllers\VoteController;
use App\Models\Door;
use App\Models\Vote;
use Illuminate\Support\Facades\Route;

// Main page - shows everything
Route::get('/', function () {
    $doors = Door::withCount('votes')->latest()->get();
    
    // Calculate results
    $results = [];
    foreach ($doors as $door) {
        $votes = $door->votes;
        $firstChoiceVotes = $votes->where('rank', 1)->count();
        $secondChoiceVotes = $votes->where('rank', 2)->count();
        $thirdChoiceVotes = $votes->where('rank', 3)->count();
        $totalPoints = ($firstChoiceVotes * 3) + ($secondChoiceVotes * 2) + ($thirdChoiceVotes * 1);
        
        $results[] = [
            'door' => $door,
            'first_choice' => $firstChoiceVotes,
            'second_choice' => $secondChoiceVotes,
            'third_choice' => $thirdChoiceVotes,
            'total_points' => $totalPoints,
            'total_votes' => $votes->count(),
        ];
    }
    
    // Sort by total points
    usort($results, function ($a, $b) {
        return $b['total_points'] <=> $a['total_points'];
    });
    
    return view('home', compact('doors', 'results'));
})->name('home');

// Door actions
Route::post('/doors', [DoorController::class, 'store'])->name('doors.store');
Route::delete('/doors/{door}', [DoorController::class, 'destroy'])->name('doors.destroy');

// Voting
Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');

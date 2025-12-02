<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Door extends Model
{
    protected $fillable = [
        'name',
        'image_path',
    ];

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function getVoteCount()
    {
        return $this->votes()->count();
    }

    public function getTotalPoints()
    {
        return $this->votes->sum(function($vote) {
            return match($vote->rank) {
                1 => 3,
                2 => 2,
                3 => 1,
                default => 0
            };
        });
    }
}

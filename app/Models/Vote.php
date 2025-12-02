<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'voter_name',
        'door_id',
        'rank',
    ];

    public function door()
    {
        return $this->belongsTo(Door::class);
    }

    public function getPoints()
    {
        return match($this->rank) {
            1 => 3,
            2 => 2,
            3 => 1,
            default => 0
        };
    }
}

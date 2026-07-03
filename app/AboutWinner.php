<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AboutWinner extends Model
{
    protected $guarded = [];

    public const PLACEMENTS = [
        'winner' => 'Winner',
        'first_runner_up' => 'First Runner Up',
        'second_runner_up' => 'Second Runner Up',
    ];

    public function placementLabel()
    {
        return self::PLACEMENTS[$this->placement] ?? $this->placement;
    }
}

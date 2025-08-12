<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $guarded = [];

    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id', 'id');
    }

    public function contestant()
    {
        return $this->belongsTo(Employee::class, 'candidate_id', 'id');
    }

    public function calculateTotal()
    {
        $sum = $this->depth + $this->diction + $this->accuracy + $this->interpretation + $this->technique +
            $this->stage_presence + $this->song_choice + $this->overall_presentation + $this->adaptability + $this->audience_interaction;
        $this->total = $sum;
        return $this->total;
    }
}

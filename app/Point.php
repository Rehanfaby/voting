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
        $sum = (float) $this->depth + (float) $this->diction + (float) $this->accuracy
            + (float) $this->interpretation + (float) $this->technique
            + (float) $this->stage_presence + (float) $this->song_choice
            + (float) $this->overall_presentation + (float) $this->adaptability
            + (float) $this->audience_interaction;
        $this->total = round($sum, 2);
        return $this->total;
    }
}

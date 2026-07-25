<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HallRow extends Model
{
    protected $guarded = [];

    public function section()
    {
        return $this->belongsTo(HallSection::class, 'hall_section_id');
    }

    public function templateSeats()
    {
        return $this->hasMany(HallTemplateSeat::class)->orderBy('seat_index');
    }
}

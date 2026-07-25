<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HallLevel extends Model
{
    protected $guarded = [];

    public function layoutVersion()
    {
        return $this->belongsTo(HallLayoutVersion::class, 'hall_layout_version_id');
    }

    public function sections()
    {
        return $this->hasMany(HallSection::class)->orderBy('sort_order');
    }

    public function templateSeats()
    {
        return $this->hasMany(HallTemplateSeat::class);
    }
}

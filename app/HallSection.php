<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HallSection extends Model
{
    protected $guarded = [];

    public function layoutVersion()
    {
        return $this->belongsTo(HallLayoutVersion::class, 'hall_layout_version_id');
    }

    public function level()
    {
        return $this->belongsTo(HallLevel::class, 'hall_level_id');
    }

    public function rows()
    {
        return $this->hasMany(HallRow::class)->orderBy('sort_order');
    }

    public function templateSeats()
    {
        return $this->hasMany(HallTemplateSeat::class);
    }

    public function isSellable()
    {
        return in_array($this->type, ['seating', 'standing'], true);
    }
}

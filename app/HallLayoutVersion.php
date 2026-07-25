<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HallLayoutVersion extends Model
{
    protected $guarded = [];

    protected $dates = ['published_at'];

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function levels()
    {
        return $this->hasMany(HallLevel::class)->orderBy('sort_order');
    }

    public function sections()
    {
        return $this->hasMany(HallSection::class)->orderBy('sort_order');
    }

    public function templateSeats()
    {
        return $this->hasMany(HallTemplateSeat::class);
    }

    public function eventSeatMaps()
    {
        return $this->hasMany(EventSeatMap::class);
    }

    public function isPublished()
    {
        return $this->status === 'published';
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }
}

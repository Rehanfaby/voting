<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventSeatMap extends Model
{
    protected $guarded = [];

    protected $dates = ['published_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function layoutVersion()
    {
        return $this->belongsTo(HallLayoutVersion::class, 'hall_layout_version_id');
    }

    public function categories()
    {
        return $this->hasMany(EventSeatCategory::class)->orderBy('sort_order');
    }

    public function inventory()
    {
        return $this->hasMany(EventSeatInventory::class);
    }

    public function isLocked()
    {
        return $this->status === 'locked'
            || $this->inventory()->where('status', 'sold')->exists();
    }
}

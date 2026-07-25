<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventSeatCategory extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_vip' => 'boolean',
        'price' => 'float',
    ];

    public function map()
    {
        return $this->belongsTo(EventSeatMap::class, 'event_seat_map_id');
    }

    public function inventory()
    {
        return $this->hasMany(EventSeatInventory::class);
    }
}

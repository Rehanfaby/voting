<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventSeatInventory extends Model
{
    protected $table = 'event_seat_inventory';

    protected $guarded = [];

    protected $dates = ['held_until'];

    protected $casts = [
        'is_accessible' => 'boolean',
        'restricted_view' => 'boolean',
        'price' => 'float',
    ];

    public function map()
    {
        return $this->belongsTo(EventSeatMap::class, 'event_seat_map_id');
    }

    public function category()
    {
        return $this->belongsTo(EventSeatCategory::class, 'event_seat_category_id');
    }

    public function templateSeat()
    {
        return $this->belongsTo(HallTemplateSeat::class, 'hall_template_seat_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function isAvailable()
    {
        if ($this->status === 'available') {
            return true;
        }
        if ($this->status === 'held' && $this->held_until && $this->held_until->isPast()) {
            return true;
        }
        return false;
    }

    public function locationLabel()
    {
        $parts = array_filter([
            $this->level_name ?: $this->level_code,
            $this->section_name ?: $this->section_code,
            $this->row_label ? ('Row ' . $this->row_label) : null,
            $this->label,
        ]);

        return implode(' · ', $parts);
    }
}

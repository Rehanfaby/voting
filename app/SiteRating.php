<?php

namespace App;

use App\Helpers\CountryFlag;
use Illuminate\Database\Eloquent\Model;

class SiteRating extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_visible' => 'boolean',
        'stars' => 'integer',
    ];

    public function vote()
    {
        return $this->belongsTo(vote::class, 'vote_id');
    }

    public function musician()
    {
        return $this->belongsTo(Employee::class, 'musician_id');
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function countryLabel()
    {
        return CountryFlag::label($this->country) ?: (string) $this->country;
    }

    public function countryFlagUrl($width = 24)
    {
        return CountryFlag::url($this->country, $width);
    }

    public static function averageStars($visibleOnly = true)
    {
        $q = static::query();
        if ($visibleOnly) {
            $q->visible();
        }
        $avg = (float) $q->avg('stars');

        return $avg > 0 ? round($avg, 1) : 0.0;
    }

    public static function countStars($visibleOnly = true)
    {
        $q = static::query();
        if ($visibleOnly) {
            $q->visible();
        }

        return (int) $q->count();
    }
}

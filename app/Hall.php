<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Hall extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($hall) {
            if (empty($hall->slug)) {
                $hall->slug = Str::slug($hall->name);
                $base = $hall->slug;
                $i = 1;
                while (static::where('slug', $hall->slug)->exists()) {
                    $hall->slug = $base . '-' . $i++;
                }
            }
        });
    }

    public function layoutVersions()
    {
        return $this->hasMany(HallLayoutVersion::class)->orderByDesc('version');
    }

    public function latestPublishedLayout()
    {
        return $this->hasOne(HallLayoutVersion::class)
            ->where('status', 'published')
            ->orderByDesc('version');
    }

    public function draftLayout()
    {
        return $this->hasOne(HallLayoutVersion::class)
            ->where('status', 'draft')
            ->orderByDesc('version');
    }
}

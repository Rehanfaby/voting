<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Judge extends Model
{
    protected $guarded = [];

    /** Judges for display/ordering — excludes ambassador records wrongly stored in judges. */
    public static function orderedForDisplay()
    {
        $excludeNames = Ambassador::pluck('name')->filter()->all();

        return static::orderBy('sort_order')->orderBy('id')
            ->when(!empty($excludeNames), function ($q) use ($excludeNames) {
                $q->whereNotIn('name', $excludeNames);
            })
            ->where('name', 'not like', 'Ambassador %')
            ->get();
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }
}

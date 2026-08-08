<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Judge extends Model
{
    protected $guarded = [];

    /**
     * Base query for real judges only — excludes ambassador rows wrongly stored
     * in the judges table (name prefix or matching ambassadors.name).
     */
    public static function realJudgesQuery()
    {
        $excludeNames = Ambassador::pluck('name')->filter()->all();

        return static::query()
            ->where('name', 'not like', 'Ambassador %')
            ->when(!empty($excludeNames), function ($q) use ($excludeNames) {
                $q->whereNotIn('name', $excludeNames);
            });
    }

    /** Judges for display/ordering — excludes ambassador records wrongly stored in judges. */
    public static function orderedForDisplay()
    {
        return static::realJudgesQuery()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }
}

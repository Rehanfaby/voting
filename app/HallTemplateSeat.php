<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HallTemplateSeat extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_accessible' => 'boolean',
        'restricted_view' => 'boolean',
    ];

    public function layoutVersion()
    {
        return $this->belongsTo(HallLayoutVersion::class, 'hall_layout_version_id');
    }

    public function level()
    {
        return $this->belongsTo(HallLevel::class, 'hall_level_id');
    }

    public function section()
    {
        return $this->belongsTo(HallSection::class, 'hall_section_id');
    }

    public function row()
    {
        return $this->belongsTo(HallRow::class, 'hall_row_id');
    }
}

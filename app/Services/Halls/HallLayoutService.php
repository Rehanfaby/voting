<?php

namespace App\Services\Halls;

use App\Hall;
use App\HallLayoutVersion;
use App\HallLevel;
use App\HallRow;
use App\HallSection;
use App\HallTemplateSeat;
use Illuminate\Support\Facades\DB;

class HallLayoutService
{
    public function createDraft(Hall $hall, array $attrs = [])
    {
        $next = ((int) $hall->layoutVersions()->max('version')) + 1;

        return HallLayoutVersion::create(array_merge([
            'hall_id' => $hall->id,
            'version' => max(1, $next),
            'status' => 'draft',
            'label' => 'Draft v' . max(1, $next),
            'canvas_width' => 1000,
            'canvas_height' => 700,
        ], $attrs));
    }

    public function ensureDefaultLevel(HallLayoutVersion $layout)
    {
        $level = $layout->levels()->first();
        if ($level) {
            return $level;
        }

        return HallLevel::create([
            'hall_layout_version_id' => $layout->id,
            'code' => 'GND',
            'name' => 'Ground',
            'sort_order' => 0,
        ]);
    }

    /**
     * Bulk-generate straight rows of seats inside a section.
     */
    public function generateStraightRows(
        HallLayoutVersion $layout,
        HallSection $section,
        $rowCount,
        $seatsPerRow,
        $startX,
        $startY,
        $seatW = 28,
        $seatH = 28,
        $gapX = 6,
        $gapY = 10,
        $rowPrefix = null
    ) {
        return $this->generateRows($layout, $section, [
            'row_count' => $rowCount,
            'seats_per_row' => $seatsPerRow,
            'origin_x' => $startX,
            'origin_y' => $startY,
            'seat_w' => $seatW,
            'seat_h' => $seatH,
            'gap_x' => $gapX,
            'gap_y' => $gapY,
            'label_prefix' => $rowPrefix,
            'curve' => 0,
        ]);
    }

    /**
     * Generate straight or gently curved / fan rows (Phase 1–2).
     */
    public function generateRows(HallLayoutVersion $layout, HallSection $section, array $options)
    {
        $level = $section->level ?: $this->ensureDefaultLevel($layout);
        $created = [];

        $seatsPerRow = max(1, min(80, (int) ($options['seats_per_row'] ?? 10)));
        $startNumber = max(1, (int) ($options['start_number'] ?? 1));
        $startX = (int) ($options['origin_x'] ?? 40);
        $startY = (int) ($options['origin_y'] ?? 80);
        $seatW = max(12, (int) ($options['seat_w'] ?? 28));
        $seatH = max(12, (int) ($options['seat_h'] ?? 28));
        $gapX = (int) ($options['gap_x'] ?? 6);
        $gapY = (int) ($options['gap_y'] ?? 10);
        $curve = (float) ($options['curve'] ?? 0);
        $seatType = $options['seat_type'] ?? ($section->type === 'standing' ? 'standing' : 'seat');
        $restricted = !empty($options['restricted_view']);
        $labelPrefix = $options['label_prefix'] ?? null;

        $rowLabels = $this->resolveRowLabels($options);
        if (empty($rowLabels)) {
            throw new \InvalidArgumentException('Provide row_from/row_to or row_count.');
        }

        if (in_array($section->type, ['stage', 'aisle'], true)) {
            throw new \InvalidArgumentException('Cannot generate sellable seats on stage/aisle sections.');
        }

        DB::transaction(function () use (
            $layout, $section, $level, $rowLabels, $seatsPerRow, $startNumber,
            $startX, $startY, $seatW, $seatH, $gapX, $gapY, $curve,
            $seatType, $restricted, $labelPrefix, &$created
        ) {
            foreach ($rowLabels as $r => $rowLabel) {
                $row = HallRow::create([
                    'hall_section_id' => $section->id,
                    'label' => $rowLabel,
                    'curve_type' => abs($curve) > 0.01 ? 'curved' : 'straight',
                    'curve_params_json' => abs($curve) > 0.01 ? json_encode(['amount' => $curve]) : null,
                    'sort_order' => $r,
                ]);

                $mid = ($seatsPerRow - 1) / 2;
                for ($i = 0; $i < $seatsPerRow; $i++) {
                    $seatIndex = $startNumber + $i;
                    $label = $labelPrefix
                        ? strtoupper(trim($labelPrefix)) . '-' . str_pad((string) $seatIndex, 2, '0', STR_PAD_LEFT)
                        : SeatLabelGenerator::make($level->code, $rowLabel, $seatIndex, $section->code);

                    $base = $label;
                    $n = 1;
                    while (HallTemplateSeat::where('hall_layout_version_id', $layout->id)->where('label', $label)->exists()) {
                        $label = $base . '-' . $n++;
                    }

                    $offset = $i - $mid;
                    $curveY = (int) round($curve * ($offset * $offset));

                    $created[] = HallTemplateSeat::create([
                        'hall_layout_version_id' => $layout->id,
                        'hall_level_id' => $level->id,
                        'hall_section_id' => $section->id,
                        'hall_row_id' => $row->id,
                        'seat_index' => $seatIndex,
                        'label' => $label,
                        'pos_x' => (int) $startX + $i * ($seatW + $gapX),
                        'pos_y' => (int) $startY + $r * ($seatH + $gapY) + $curveY,
                        'width' => $seatW,
                        'height' => $seatH,
                        'seat_type' => $seatType,
                        'restricted_view' => $restricted || $section->type === 'restricted',
                    ]);
                }
            }
        });

        return $created;
    }

    protected function resolveRowLabels(array $options)
    {
        $from = strtoupper(trim((string) ($options['row_from'] ?? '')));
        $to = strtoupper(trim((string) ($options['row_to'] ?? '')));
        if ($from !== '' && $to !== '') {
            $labels = [];
            $a = $this->letterIndex($from);
            $b = $this->letterIndex($to);
            if ($a > $b) {
                [$a, $b] = [$b, $a];
            }
            for ($i = $a; $i <= $b; $i++) {
                $labels[] = SeatLabelGenerator::rowLetter($i);
            }
            return $labels;
        }

        $count = max(0, min(40, (int) ($options['row_count'] ?? 0)));
        $labels = [];
        for ($r = 0; $r < $count; $r++) {
            $labels[] = SeatLabelGenerator::rowLetter($r);
        }
        return $labels;
    }

    protected function letterIndex($letters)
    {
        $letters = strtoupper(preg_replace('/[^A-Z]/', '', $letters));
        if ($letters === '') {
            return 0;
        }
        $n = 0;
        for ($i = 0; $i < strlen($letters); $i++) {
            $n = $n * 26 + (ord($letters[$i]) - 64);
        }
        return max(0, $n - 1);
    }

    /**
     * Upsert template seat geometry for a draft layout from a JSON payload.
     */
    public function saveTemplateSeats(HallLayoutVersion $layout, array $seats)
    {
        if ($layout->isPublished()) {
            throw new \RuntimeException('Published layouts are immutable. Create a new draft version.');
        }

        DB::transaction(function () use ($layout, $seats) {
            $keepIds = [];
            foreach ($seats as $row) {
                $id = isset($row['id']) ? (int) $row['id'] : 0;
                $data = [
                    'hall_layout_version_id' => $layout->id,
                    'hall_level_id' => $row['hall_level_id'] ?? null,
                    'hall_section_id' => $row['hall_section_id'] ?? null,
                    'hall_row_id' => $row['hall_row_id'] ?? null,
                    'seat_index' => (int) ($row['seat_index'] ?? 1),
                    'label' => substr(trim($row['label'] ?? 'S-01'), 0, 64),
                    'pos_x' => max(0, (int) ($row['pos_x'] ?? 0)),
                    'pos_y' => max(0, (int) ($row['pos_y'] ?? 0)),
                    'width' => max(12, (int) ($row['width'] ?? 28)),
                    'height' => max(12, (int) ($row['height'] ?? 28)),
                    'seat_type' => $row['seat_type'] ?? 'seat',
                    'is_accessible' => !empty($row['is_accessible']),
                    'restricted_view' => !empty($row['restricted_view']),
                ];

                if ($id > 0) {
                    $existing = HallTemplateSeat::where('hall_layout_version_id', $layout->id)->find($id);
                    if ($existing) {
                        $existing->update($data);
                        $keepIds[] = $existing->id;
                        continue;
                    }
                }

                $created = HallTemplateSeat::create($data);
                $keepIds[] = $created->id;
            }

            HallTemplateSeat::where('hall_layout_version_id', $layout->id)
                ->whereNotIn('id', $keepIds ?: [0])
                ->delete();
        });
    }

    public function publish(HallLayoutVersion $layout)
    {
        if ($layout->templateSeats()->count() < 1) {
            throw new \InvalidArgumentException('Add at least one seat before publishing.');
        }

        $layout->status = 'published';
        $layout->published_at = now();
        $layout->save();

        return $layout;
    }

    /** Clone a published layout into a new draft for redesign. */
    public function forkDraft(HallLayoutVersion $source)
    {
        return DB::transaction(function () use ($source) {
            $draft = $this->createDraft($source->hall, [
                'label' => 'Draft from v' . $source->version,
                'canvas_width' => $source->canvas_width,
                'canvas_height' => $source->canvas_height,
                'background_image' => $source->background_image,
            ]);

            $levelMap = [];
            foreach ($source->levels as $level) {
                $copy = $level->replicate();
                $copy->hall_layout_version_id = $draft->id;
                $copy->save();
                $levelMap[$level->id] = $copy->id;
            }

            $sectionMap = [];
            foreach ($source->sections as $section) {
                $copy = $section->replicate();
                $copy->hall_layout_version_id = $draft->id;
                $copy->hall_level_id = $levelMap[$section->hall_level_id] ?? null;
                $copy->save();
                $sectionMap[$section->id] = $copy->id;
            }

            $rowMap = [];
            foreach ($source->sections as $section) {
                foreach ($section->rows as $row) {
                    $copy = $row->replicate();
                    $copy->hall_section_id = $sectionMap[$section->id];
                    $copy->save();
                    $rowMap[$row->id] = $copy->id;
                }
            }

            foreach ($source->templateSeats as $seat) {
                $copy = $seat->replicate();
                $copy->hall_layout_version_id = $draft->id;
                $copy->hall_level_id = $levelMap[$seat->hall_level_id] ?? null;
                $copy->hall_section_id = $sectionMap[$seat->hall_section_id] ?? null;
                $copy->hall_row_id = $rowMap[$seat->hall_row_id] ?? null;
                $copy->save();
            }

            return $draft->fresh(['levels', 'sections', 'templateSeats']);
        });
    }
}

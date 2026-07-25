<?php

namespace App\Http\Controllers;

use App\Hall;
use App\HallLayoutVersion;
use App\HallLevel;
use App\HallSection;
use App\Services\Halls\HallLayoutService;
use Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class HallLayoutController extends Controller
{
    protected function can($perm)
    {
        $role = Role::find(Auth::user()->role_id);
        return $role && $role->hasPermissionTo($perm);
    }

    public function edit($hallId, $layoutId)
    {
        if (!$this->can('halls-edit')) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        $hall = Hall::findOrFail($hallId);
        $layout = HallLayoutVersion::where('hall_id', $hall->id)
            ->with(['levels', 'sections.level', 'sections.rows', 'templateSeats'])
            ->findOrFail($layoutId);

        $readonly = $layout->isPublished();
        $levels = $layout->levels;
        $sections = $layout->sections->map(function ($s) {
            return [
                'id' => $s->id,
                'level_id' => $s->hall_level_id,
                'hall_level_id' => $s->hall_level_id,
                'code' => $s->code,
                'name' => $s->name,
                'type' => $s->type,
                'pos_x' => $s->pos_x,
                'pos_y' => $s->pos_y,
                'width' => $s->width,
                'height' => $s->height,
                'color' => $s->color,
                'sort_order' => $s->sort_order,
            ];
        })->values();
        $seatsFlat = $layout->templateSeats->values();

        return view('halls.layout_edit', compact('hall', 'layout', 'readonly', 'levels', 'sections', 'seatsFlat'));
    }

    public function saveSettings(Request $request, $hallId, $layoutId)
    {
        if (!$this->can('halls-edit')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $layout = HallLayoutVersion::where('hall_id', $hallId)->findOrFail($layoutId);
        if ($layout->isPublished()) {
            return response()->json(['error' => 'Published layouts are immutable'], 422);
        }

        $layout->canvas_width = max(400, min(2000, (int) $request->input('canvas_width', 1000)));
        $layout->canvas_height = max(300, min(1600, (int) $request->input('canvas_height', 700)));
        $layout->label = $request->input('label', $layout->label);
        $layout->save();

        return response()->json(['ok' => true, 'layout' => $layout]);
    }

    public function saveLevel(Request $request, $hallId, $layoutId)
    {
        if (!$this->can('halls-edit')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $layout = HallLayoutVersion::where('hall_id', $hallId)->findOrFail($layoutId);
        if ($layout->isPublished()) {
            return response()->json(['error' => 'Published layouts are immutable'], 422);
        }

        $payload = [
            'hall_layout_version_id' => $layout->id,
            'code' => strtoupper(substr($request->input('code', 'GND'), 0, 32)),
            'name' => $request->input('name', 'Ground'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];

        if ($request->filled('id')) {
            $level = HallLevel::where('hall_layout_version_id', $layout->id)->findOrFail((int) $request->input('id'));
            $level->update($payload);
        } else {
            $level = HallLevel::create($payload);
        }

        return response()->json([
            'ok' => true,
            'level' => $level,
            'levels' => $layout->levels()->orderBy('sort_order')->get(),
        ]);
    }

    public function saveSection(Request $request, $hallId, $layoutId)
    {
        if (!$this->can('halls-edit')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $layout = HallLayoutVersion::where('hall_id', $hallId)->findOrFail($layoutId);
        if ($layout->isPublished()) {
            return response()->json(['error' => 'Published layouts are immutable'], 422);
        }

        $levelId = (int) ($request->input('hall_level_id') ?: $request->input('level_id'));
        if (!$levelId) {
            $levelId = app(HallLayoutService::class)->ensureDefaultLevel($layout)->id;
        }

        $payload = [
            'hall_layout_version_id' => $layout->id,
            'hall_level_id' => $levelId,
            'code' => strtoupper(substr($request->input('code', 'SEC'), 0, 32)),
            'name' => $request->input('name', 'Section'),
            'type' => $request->input('type', 'seating'),
            'pos_x' => (int) $request->input('pos_x', 40),
            'pos_y' => (int) $request->input('pos_y', 40),
            'width' => (int) $request->input('width', 240),
            'height' => (int) $request->input('height', 160),
            'color' => $request->input('color'),
            'polygon_json' => $request->input('polygon_json'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];

        if ($request->filled('id')) {
            $section = HallSection::where('hall_layout_version_id', $layout->id)->findOrFail((int) $request->input('id'));
            $section->update($payload);
        } else {
            $section = HallSection::create($payload);
        }

        return response()->json([
            'ok' => true,
            'section' => $section,
            'sections' => $layout->sections()->orderBy('sort_order')->get()->map(function ($s) {
                return [
                    'id' => $s->id,
                    'level_id' => $s->hall_level_id,
                    'hall_level_id' => $s->hall_level_id,
                    'code' => $s->code,
                    'name' => $s->name,
                    'type' => $s->type,
                    'sort_order' => $s->sort_order,
                ];
            }),
        ]);
    }

    public function saveSeats(Request $request, $hallId, $layoutId, HallLayoutService $service)
    {
        if (!$this->can('halls-edit')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $layout = HallLayoutVersion::where('hall_id', $hallId)->findOrFail($layoutId);
        try {
            $service->saveTemplateSeats($layout, $request->input('seats', []));
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json([
            'ok' => true,
            'seats' => $layout->templateSeats()->get(),
        ]);
    }

    public function generateRows(Request $request, $hallId, $layoutId, HallLayoutService $service)
    {
        if (!$this->can('halls-edit')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $layout = HallLayoutVersion::where('hall_id', $hallId)->findOrFail($layoutId);
        if ($layout->isPublished()) {
            return response()->json(['error' => 'Published layouts are immutable'], 422);
        }

        $section = HallSection::where('hall_layout_version_id', $layout->id)
            ->findOrFail((int) $request->input('section_id'));

        $curve = (float) $request->input('curve', 0);
        $options = [
            'row_from' => $request->input('row_from'),
            'row_to' => $request->input('row_to'),
            'row_count' => (int) $request->input('row_count', 0),
            'seats_per_row' => max(1, min(80, (int) $request->input('seats_per_row', 10))),
            'start_number' => max(1, (int) $request->input('start_number', 1)),
            'origin_x' => (int) ($request->input('origin_x', $request->input('start_x', $section->pos_x + 10))),
            'origin_y' => (int) ($request->input('origin_y', $request->input('start_y', $section->pos_y + 10))),
            'seat_w' => (int) $request->input('seat_w', 28),
            'seat_h' => (int) $request->input('seat_h', 28),
            'gap_x' => (int) $request->input('gap_x', 6),
            'gap_y' => (int) $request->input('gap_y', 10),
            'label_prefix' => $request->input('label_prefix', $request->input('row_prefix')),
            'seat_type' => $request->input('seat_type', 'seat'),
            'curve' => $curve,
            'restricted_view' => $request->boolean('restricted_view'),
        ];

        try {
            $created = $service->generateRows($layout, $section, $options);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json([
            'ok' => true,
            'created' => count($created),
            'seats' => $layout->templateSeats()->get(),
        ]);
    }

    public function publish($hallId, $layoutId, HallLayoutService $service)
    {
        if (!$this->can('hall-layouts-publish') && !$this->can('halls-edit')) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        $layout = HallLayoutVersion::where('hall_id', $hallId)->findOrFail($layoutId);
        try {
            $service->publish($layout);
        } catch (\Throwable $e) {
            return redirect()->back()->with('not_permitted', $e->getMessage());
        }

        return redirect()->route('halls.edit', $hallId)->with('message', 'Layout v' . $layout->version . ' published.');
    }

    public function fork($hallId, $layoutId, HallLayoutService $service)
    {
        if (!$this->can('halls-edit')) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        $source = HallLayoutVersion::where('hall_id', $hallId)->findOrFail($layoutId);
        $draft = $service->forkDraft($source);

        return redirect()->route('halls.layouts.edit', [$hallId, $draft->id])
            ->with('message', 'New draft created from v' . $source->version);
    }

    /** Phase 2: upload background image for draft layout. */
    public function uploadBackground(Request $request, $hallId, $layoutId)
    {
        if (!$this->can('halls-edit')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $layout = HallLayoutVersion::where('hall_id', $hallId)->findOrFail($layoutId);
        if ($layout->isPublished()) {
            return response()->json(['error' => 'Published layouts are immutable'], 422);
        }

        $request->validate(['background' => 'required|image|max:4096']);
        if (!is_dir(public_path('images/halls'))) {
            mkdir(public_path('images/halls'), 0755, true);
        }
        $file = $request->file('background');
        $name = 'hall-bg-' . $layout->id . '-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/halls'), $name);
        $layout->background_image = 'images/halls/' . $name;
        $layout->save();

        return response()->json(['ok' => true, 'background_image' => asset($layout->background_image)]);
    }
}

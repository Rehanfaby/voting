<?php

namespace App\Services\Halls;

use App\EventSeatCategory;
use App\EventSeatInventory;
use App\EventSeatMap;
use App\HallLayoutVersion;
use App\Product;
use Illuminate\Support\Facades\DB;

class EventSeatMapService
{
    /**
     * Snapshot a published hall layout onto a product (event ticket type).
     */
    public function attachLayout(Product $product, HallLayoutVersion $layout, array $defaultCategories = null)
    {
        if (!$layout->isPublished()) {
            throw new \InvalidArgumentException('Only published layouts can be attached to an event.');
        }

        $existing = EventSeatMap::where('product_id', $product->id)->first();
        if ($existing && $existing->isLocked()) {
            throw new \RuntimeException('This event already has sold seats. Create a new product or unlock after refunds.');
        }

        return DB::transaction(function () use ($product, $layout, $existing, $defaultCategories) {
            if ($existing) {
                EventSeatInventory::where('event_seat_map_id', $existing->id)->delete();
                EventSeatCategory::where('event_seat_map_id', $existing->id)->delete();
                $map = $existing;
                $map->hall_layout_version_id = $layout->id;
                $map->status = 'published';
                $map->canvas_width = $layout->canvas_width;
                $map->canvas_height = $layout->canvas_height;
                $map->background_image = $layout->background_image;
                $map->published_at = now();
                $map->save();
            } else {
                $map = EventSeatMap::create([
                    'product_id' => $product->id,
                    'hall_layout_version_id' => $layout->id,
                    'status' => 'published',
                    'canvas_width' => $layout->canvas_width,
                    'canvas_height' => $layout->canvas_height,
                    'background_image' => $layout->background_image,
                    'published_at' => now(),
                ]);
            }

            $categories = $defaultCategories ?: [
                ['code' => 'REGULAR', 'name' => 'Regular', 'price' => (float) $product->price, 'color' => '#3b82f6', 'is_vip' => false, 'sort_order' => 0],
                ['code' => 'VIP', 'name' => 'VIP', 'price' => (float) $product->price * 2, 'color' => '#f59e0b', 'is_vip' => true, 'sort_order' => 1],
                ['code' => 'PREMIUM', 'name' => 'Premium', 'price' => (float) $product->price * 1.5, 'color' => '#a855f7', 'is_vip' => false, 'sort_order' => 2],
                ['code' => 'EARLY', 'name' => 'Early Bird', 'price' => max(0, (float) $product->price * 0.8), 'color' => '#16a34a', 'is_vip' => false, 'sort_order' => 3],
            ];

            $catMap = [];
            foreach ($categories as $cat) {
                $row = EventSeatCategory::create([
                    'event_seat_map_id' => $map->id,
                    'code' => $cat['code'],
                    'name' => $cat['name'],
                    'price' => $cat['price'],
                    'color' => $cat['color'] ?? '#e87722',
                    'is_vip' => !empty($cat['is_vip']),
                    'sort_order' => (int) ($cat['sort_order'] ?? 0),
                ]);
                $catMap[$row->code] = $row;
            }

            $defaultCat = $catMap['REGULAR'] ?? reset($catMap);

            $seats = $layout->templateSeats()
                ->with(['level', 'section', 'row'])
                ->get();

            foreach ($seats as $seat) {
                $sectionCode = optional($seat->section)->code;
                $category = $defaultCat;
                if ($sectionCode && isset($catMap[strtoupper($sectionCode)])) {
                    $category = $catMap[strtoupper($sectionCode)];
                } elseif ($sectionCode && stripos($sectionCode, 'VIP') !== false && isset($catMap['VIP'])) {
                    $category = $catMap['VIP'];
                }

                EventSeatInventory::create([
                    'event_seat_map_id' => $map->id,
                    'hall_template_seat_id' => $seat->id,
                    'event_seat_category_id' => $category ? $category->id : null,
                    'label' => $seat->label,
                    'level_code' => optional($seat->level)->code,
                    'level_name' => optional($seat->level)->name,
                    'section_code' => optional($seat->section)->code,
                    'section_name' => optional($seat->section)->name,
                    'row_label' => optional($seat->row)->label,
                    'seat_index' => $seat->seat_index,
                    'pos_x' => $seat->pos_x,
                    'pos_y' => $seat->pos_y,
                    'width' => $seat->width,
                    'height' => $seat->height,
                    'seat_type' => $seat->seat_type,
                    'is_accessible' => $seat->is_accessible,
                    'restricted_view' => $seat->restricted_view,
                    'price' => $category ? $category->price : (float) $product->price,
                    'status' => 'available',
                ]);
            }

            $product->seat_selection_enabled = true;
            $product->seat_map_width = $layout->canvas_width;
            $product->seat_map_height = $layout->canvas_height;
            $product->save();

            return $map->fresh(['categories', 'inventory']);
        });
    }
}

<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductSeat;
use App\ProductSeatZone;
use Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ProductSeatMapController extends Controller
{
    public function edit($id)
    {
        if (!$this->canEdit()) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        $product = Product::findOrFail($id);
        $zones = ProductSeatZone::where('product_id', $product->id)->orderBy('sort_order')->get();
        $seats = ProductSeat::where('product_id', $product->id)->with('zone')->get();

        return view('product.seat_map', compact('product', 'zones', 'seats'));
    }

    public function saveSettings(Request $request, $id)
    {
        if (!$this->canEdit()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $product = Product::findOrFail($id);
        $product->seat_selection_enabled = $request->boolean('seat_selection_enabled');
        $product->seat_map_width = max(400, min(1400, (int) $request->input('seat_map_width', 900)));
        $product->seat_map_height = max(300, min(1200, (int) $request->input('seat_map_height', 650)));
        $product->save();

        return response()->json(['ok' => true]);
    }

    public function saveZone(Request $request, $id)
    {
        if (!$this->canEdit()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        Product::findOrFail($id);

        $zone = ProductSeatZone::updateOrCreate(
            ['id' => $request->input('id')],
            [
                'product_id' => $id,
                'name' => $request->input('name', 'Zone'),
                'price' => $request->input('price', 0),
                'color' => $request->input('color', '#e87722'),
                'is_vip' => $request->boolean('is_vip'),
                'sort_order' => (int) $request->input('sort_order', 0),
            ]
        );

        return response()->json(['ok' => true, 'zone' => $zone]);
    }

    public function deleteZone($zoneId)
    {
        if (!$this->canEdit()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $zone = ProductSeatZone::findOrFail($zoneId);
        ProductSeat::where('zone_id', $zone->id)->delete();
        $zone->delete();

        return response()->json(['ok' => true]);
    }

    public function saveSeats(Request $request, $id)
    {
        if (!$this->canEdit()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        Product::findOrFail($id);
        $seats = $request->input('seats', []);

        $keepIds = [];
        foreach ($seats as $row) {
            $seatId = $row['id'] ?? null;
            $existing = $seatId ? ProductSeat::where('product_id', $id)->where('id', $seatId)->first() : null;
            if ($existing && $existing->status === 'sold') {
                $keepIds[] = $existing->id;
                continue;
            }

            $data = [
                'product_id' => $id,
                'zone_id' => (int) $row['zone_id'],
                'label' => substr(trim($row['label'] ?? ''), 0, 32),
                'pos_x' => max(0, (int) ($row['pos_x'] ?? 0)),
                'pos_y' => max(0, (int) ($row['pos_y'] ?? 0)),
                'width' => max(20, min(200, (int) ($row['width'] ?? 44))),
                'height' => max(20, min(200, (int) ($row['height'] ?? 36))),
            ];

            if ($data['label'] === '' || $data['zone_id'] <= 0) {
                continue;
            }

            if ($existing) {
                $existing->update($data);
                $keepIds[] = $existing->id;
            } else {
                $data['status'] = 'available';
                $created = ProductSeat::create($data);
                $keepIds[] = $created->id;
            }
        }

        ProductSeat::where('product_id', $id)
            ->where('status', 'available')
            ->whereNotIn('id', $keepIds)
            ->delete();

        $fresh = ProductSeat::where('product_id', $id)->with('zone')->get();

        return response()->json(['ok' => true, 'seats' => $fresh]);
    }

    public function publicSeats($id)
    {
        $product = Product::where('id', $id)->where('is_active', true)->firstOrFail();
        if (!$product->seat_selection_enabled) {
            return response()->json(['enabled' => false]);
        }

        $zones = ProductSeatZone::where('product_id', $product->id)->orderBy('sort_order')->get();
        $seats = ProductSeat::where('product_id', $product->id)->get(['id', 'zone_id', 'label', 'pos_x', 'pos_y', 'width', 'height', 'status']);

        return response()->json([
            'enabled' => true,
            'width' => $product->seat_map_width,
            'height' => $product->seat_map_height,
            'zones' => $zones,
            'seats' => $seats,
        ]);
    }

    private function canEdit()
    {
        $role = Role::find(Auth::user()->role_id);
        return $role && $role->hasPermissionTo('products-edit');
    }
}

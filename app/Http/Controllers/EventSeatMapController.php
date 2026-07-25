<?php

namespace App\Http\Controllers;

use App\EventSeatCategory;
use App\EventSeatInventory;
use App\EventSeatMap;
use App\HallLayoutVersion;
use App\Product;
use App\Services\Halls\EventSeatMapService;
use App\Services\Halls\EventSeatOpsService;
use App\Services\Halls\SeatHoldService;
use App\Ticket;
use App\TicketSeat;
use Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class EventSeatMapController extends Controller
{
    protected function can($perm)
    {
        $role = Role::find(Auth::user()->role_id);
        return $role && $role->hasPermissionTo($perm);
    }

    public function adminAttachForm($productId)
    {
        if (!$this->can('event-seat-maps-manage') && !$this->can('halls-edit')) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        $product = Product::findOrFail($productId);
        $layouts = HallLayoutVersion::with('hall')
            ->where('status', 'published')
            ->orderByDesc('id')
            ->get();
        $map = EventSeatMap::with(['categories', 'layoutVersion.hall'])
            ->where('product_id', $product->id)
            ->first();

        return view('halls.attach_layout', compact('product', 'layouts', 'map'));
    }

    public function adminAttach(Request $request, $productId, EventSeatMapService $service)
    {
        if (!$this->can('event-seat-maps-manage') && !$this->can('halls-edit')) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        $product = Product::findOrFail($productId);
        $layout = HallLayoutVersion::where('status', 'published')->findOrFail((int) $request->input('layout_id'));

        try {
            $service->attachLayout($product, $layout);
        } catch (\Throwable $e) {
            return redirect()->back()->with('not_permitted', $e->getMessage());
        }

        return redirect()->route('products.event_seat_map', $product->id)
            ->with('message', 'Hall layout attached. Inventory ready for sale.');
    }

    public function adminInventory($productId)
    {
        if (!$this->can('event-seat-maps-manage') && !$this->can('halls-edit')) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        $product = Product::findOrFail($productId);
        $map = EventSeatMap::with(['categories', 'layoutVersion.hall', 'inventory.category'])
            ->where('product_id', $product->id)
            ->firstOrFail();

        $stats = [
            'available' => $map->inventory()->where('status', 'available')->count(),
            'held' => $map->inventory()->where('status', 'held')->count(),
            'sold' => $map->inventory()->where('status', 'sold')->count(),
            'blocked' => $map->inventory()->where('status', 'blocked')->count(),
        ];

        return view('halls.inventory', compact('product', 'map', 'stats'));
    }

    public function saveCategory(Request $request, $productId)
    {
        if (!$this->can('event-seat-maps-manage') && !$this->can('halls-edit')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $map = EventSeatMap::where('product_id', $productId)->firstOrFail();
        $cat = EventSeatCategory::updateOrCreate(
            ['id' => $request->input('id'), 'event_seat_map_id' => $map->id],
            [
                'event_seat_map_id' => $map->id,
                'code' => strtoupper(substr($request->input('code', 'CAT'), 0, 32)),
                'name' => $request->input('name', 'Category'),
                'price' => (float) $request->input('price', 0),
                'color' => $request->input('color', '#e87722'),
                'is_vip' => $request->boolean('is_vip'),
                'sort_order' => (int) $request->input('sort_order', 0),
            ]
        );

        if ($request->boolean('apply_price')) {
            EventSeatInventory::where('event_seat_category_id', $cat->id)
                ->update(['price' => $cat->price]);
        }

        return response()->json(['ok' => true, 'category' => $cat]);
    }

    public function assignCategory(Request $request, $productId)
    {
        if (!$this->can('event-seat-maps-manage') && !$this->can('halls-edit')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $map = EventSeatMap::where('product_id', $productId)->firstOrFail();
        $cat = EventSeatCategory::where('event_seat_map_id', $map->id)->findOrFail((int) $request->input('category_id'));
        $ids = array_filter(array_map('intval', (array) $request->input('inventory_ids', [])));

        EventSeatInventory::where('event_seat_map_id', $map->id)
            ->whereIn('id', $ids)
            ->where('status', '!=', 'sold')
            ->update([
                'event_seat_category_id' => $cat->id,
                'price' => $cat->price,
            ]);

        return response()->json(['ok' => true]);
    }

    /** Public inventory for booking UI. */
    public function publicSeats($productId, SeatHoldService $holds)
    {
        $holds->releaseExpired();

        $product = Product::where('is_active', true)->findOrFail($productId);
        $map = EventSeatMap::with(['categories', 'layoutVersion.hall'])
            ->where('product_id', $product->id)
            ->first();

        if (!$map) {
            // Legacy product_seats fallback handled by ProductSeatMapController@publicSeats
            return app(ProductSeatMapController::class)->publicSeats($productId);
        }

        $inventory = EventSeatInventory::where('event_seat_map_id', $map->id)
            ->with('category')
            ->get()
            ->map(function ($seat) {
                $status = $seat->status;
                if ($status === 'held' && $seat->held_until && $seat->held_until->isPast()) {
                    $status = 'available';
                }
                return [
                    'id' => $seat->id,
                    'label' => $seat->label,
                    'pos_x' => $seat->pos_x,
                    'pos_y' => $seat->pos_y,
                    'width' => $seat->width,
                    'height' => $seat->height,
                    'status' => $status,
                    'price' => (float) $seat->price,
                    'category_id' => $seat->event_seat_category_id,
                    'category' => optional($seat->category)->name,
                    'category_code' => optional($seat->category)->code,
                    'color' => optional($seat->category)->color ?: '#e87722',
                    'level' => $seat->level_name ?: $seat->level_code,
                    'section' => $seat->section_name ?: $seat->section_code,
                    'row' => $seat->row_label,
                    'restricted_view' => (bool) $seat->restricted_view,
                    'is_accessible' => (bool) $seat->is_accessible,
                    'seat_type' => $seat->seat_type,
                ];
            });

        $categories = $map->categories->map(function ($c) {
            return [
                'id' => $c->id,
                'code' => $c->code,
                'name' => $c->name,
                'price' => (float) $c->price,
                'color' => $c->color,
                'is_vip' => (bool) $c->is_vip,
                'sort_order' => (int) $c->sort_order,
            ];
        });

        return response()->json([
            'enabled' => true,
            'mode' => 'event_map',
            'hall' => optional(optional($map->layoutVersion)->hall)->name,
            'width' => $map->canvas_width,
            'height' => $map->canvas_height,
            'background_image' => $map->background_image,
            'categories' => $categories,
            'zones' => $categories, // legacy alias for older frontends
            'seats' => $inventory,
            'hold_minutes' => SeatHoldService::HOLD_MINUTES,
        ]);
    }

    public function refund(Request $request, $productId, EventSeatOpsService $ops)
    {
        if (!$this->can('event-seat-maps-manage') && !$this->can('halls-edit')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $ticket = Ticket::where('product_id', $productId)->findOrFail((int) $request->input('ticket_id'));
        try {
            $ops->refundTicket($ticket);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json(['ok' => true]);
    }

    public function reissue(Request $request, $productId, EventSeatOpsService $ops)
    {
        if (!$this->can('event-seat-maps-manage') && !$this->can('halls-edit')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $seat = TicketSeat::where('product_id', $productId)->findOrFail((int) $request->input('ticket_seat_id'));
        $seat = $ops->reissueTicketSeat($seat);

        return response()->json(['ok' => true, 'token' => $seat->token]);
    }

    public function relocate(Request $request, $productId, EventSeatOpsService $ops)
    {
        if (!$this->can('event-seat-maps-manage') && !$this->can('halls-edit')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $map = EventSeatMap::where('product_id', $productId)->firstOrFail();
        $from = EventSeatInventory::where('event_seat_map_id', $map->id)->findOrFail((int) $request->input('from_inventory_id'));
        $to = EventSeatInventory::where('event_seat_map_id', $map->id)->findOrFail((int) $request->input('to_inventory_id'));

        try {
            $ops->relocate($from, $to);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json(['ok' => true]);
    }

    public function createHold(Request $request, $productId, SeatHoldService $holds)
    {
        $product = Product::where('is_active', true)->findOrFail($productId);
        $ids = $request->input('seat_ids', []);
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

        try {
            $hold = $holds->createHold($product, $ids);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json([
            'ok' => true,
            'hold_token' => $hold['hold_token'],
            'held_until' => $hold['held_until'],
            'expires_in' => $hold['expires_in'],
            'total' => $hold['total'],
            'labels' => $hold['labels'],
            'ids' => $hold['ids'],
        ]);
    }

    public function releaseHold($productId, $token, SeatHoldService $holds)
    {
        $holds->releaseHold($token);
        return response()->json(['ok' => true]);
    }
}

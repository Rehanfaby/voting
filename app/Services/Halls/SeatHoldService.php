<?php

namespace App\Services\Halls;

use App\EventSeatInventory;
use App\EventSeatMap;
use App\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SeatHoldService
{
    const HOLD_MINUTES = 10;

    public function releaseExpired()
    {
        return EventSeatInventory::where('status', 'held')
            ->whereNotNull('held_until')
            ->where('held_until', '<', now())
            ->update([
                'status' => 'available',
                'held_until' => null,
                'hold_token' => null,
                'ticket_id' => null,
            ]);
    }

    public function createHold(Product $product, array $inventoryIds, $ttlMinutes = null)
    {
        $this->releaseExpired();

        $map = EventSeatMap::where('product_id', $product->id)->first();
        if (!$map) {
            throw new \InvalidArgumentException('No event seat map for this product.');
        }

        $ids = array_values(array_unique(array_filter(array_map('intval', $inventoryIds))));
        if (empty($ids)) {
            throw new \InvalidArgumentException('Select at least one seat.');
        }

        $ttl = max(1, (int) ($ttlMinutes ?: self::HOLD_MINUTES));
        $token = (string) Str::uuid();
        $until = now()->addMinutes($ttl);

        return DB::transaction(function () use ($map, $ids, $token, $until) {
            $locked = EventSeatInventory::where('event_seat_map_id', $map->id)
                ->whereIn('id', $ids)
                ->lockForUpdate()
                ->get();

            if ($locked->count() !== count($ids)) {
                throw new \RuntimeException('One or more seats are invalid.');
            }

            foreach ($locked as $seat) {
                if (!$seat->isAvailable() && !($seat->status === 'held' && $seat->held_until && $seat->held_until->isPast())) {
                    throw new \RuntimeException('Seat ' . $seat->label . ' is no longer available.');
                }
            }

            EventSeatInventory::where('event_seat_map_id', $map->id)
                ->whereIn('id', $ids)
                ->where(function ($q) {
                    $q->where('status', 'available')
                        ->orWhere(function ($q2) {
                            $q2->where('status', 'held')->where('held_until', '<', now());
                        });
                })
                ->update([
                    'status' => 'held',
                    'hold_token' => $token,
                    'held_until' => $until,
                    'ticket_id' => null,
                ]);

            $held = EventSeatInventory::where('hold_token', $token)->get();
            if ($held->count() !== count($ids)) {
                throw new \RuntimeException('Could not hold all selected seats. Please try again.');
            }

            return [
                'hold_token' => $token,
                'held_until' => $until->toIso8601String(),
                'expires_in' => $until->diffInSeconds(now()),
                'seats' => $held,
                'total' => (float) $held->sum('price'),
                'labels' => $held->pluck('label')->all(),
                'ids' => $held->pluck('id')->all(),
            ];
        });
    }

    public function releaseHold($token)
    {
        if (!$token) {
            return 0;
        }

        return EventSeatInventory::where('hold_token', $token)
            ->where('status', 'held')
            ->update([
                'status' => 'available',
                'held_until' => null,
                'hold_token' => null,
                'ticket_id' => null,
            ]);
    }

    public function attachHoldToTicket($token, $ticketId)
    {
        return EventSeatInventory::where('hold_token', $token)
            ->where('status', 'held')
            ->update(['ticket_id' => $ticketId]);
    }

    public function markSold($token, $ticketId)
    {
        return EventSeatInventory::where('hold_token', $token)
            ->whereIn('status', ['held', 'available'])
            ->update([
                'status' => 'sold',
                'ticket_id' => $ticketId,
                'held_until' => null,
                // keep hold_token for audit until cleared
            ]);
    }

    public function releaseByTicket($ticketId)
    {
        // Only release holds tied to a pending ticket — never free sold seats here.
        return EventSeatInventory::where('ticket_id', $ticketId)
            ->where('status', 'held')
            ->update([
                'status' => 'available',
                'held_until' => null,
                'hold_token' => null,
                'ticket_id' => null,
            ]);
    }

    public function getHoldSeats($token)
    {
        $this->releaseExpired();

        return EventSeatInventory::where('hold_token', $token)
            ->where('status', 'held')
            ->where('held_until', '>', now())
            ->with('category')
            ->get();
    }
}

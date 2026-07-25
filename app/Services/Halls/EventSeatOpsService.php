<?php

namespace App\Services\Halls;

use App\EventSeatInventory;
use App\Ticket;
use App\TicketSeat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventSeatOpsService
{
    /**
     * Refund a paid ticket: free inventory seats and mark ticket inactive.
     */
    public function refundTicket(Ticket $ticket)
    {
        return DB::transaction(function () use ($ticket) {
            EventSeatInventory::where('ticket_id', $ticket->id)
                ->where('status', 'sold')
                ->update([
                    'status' => 'available',
                    'held_until' => null,
                    'hold_token' => null,
                    'ticket_id' => null,
                ]);

            TicketSeat::where('ticket_id', $ticket->id)->delete();

            $ticket->status = false;
            $ticket->is_used = false;
            $ticket->save();

            return true;
        });
    }

    /**
     * Invalidate old QR token and issue a new one.
     */
    public function reissueTicketSeat(TicketSeat $ticketSeat)
    {
        $ticketSeat->token = Str::random(6);
        $ticketSeat->is_used = false;
        $ticketSeat->used_at = null;
        $ticketSeat->save();

        return $ticketSeat;
    }

    /**
     * Move a sold seat reservation to another available inventory seat under the same ticket.
     */
    public function relocate(EventSeatInventory $from, EventSeatInventory $to)
    {
        if ($from->event_seat_map_id !== $to->event_seat_map_id) {
            throw new \InvalidArgumentException('Seats must belong to the same event map.');
        }
        if ($from->status !== 'sold' || !$from->ticket_id) {
            throw new \InvalidArgumentException('Source seat is not a sold seat.');
        }
        if (!$to->isAvailable()) {
            throw new \InvalidArgumentException('Target seat is not available.');
        }

        return DB::transaction(function () use ($from, $to) {
            $ticketId = $from->ticket_id;
            $ticketSeat = TicketSeat::where('ticket_id', $ticketId)
                ->where(function ($q) use ($from) {
                    $q->where('event_seat_inventory_id', $from->id)
                        ->orWhere('seat_label', $from->label);
                })
                ->first();

            $to->status = 'sold';
            $to->ticket_id = $ticketId;
            $to->held_until = null;
            $to->save();

            $from->status = 'available';
            $from->ticket_id = null;
            $from->hold_token = null;
            $from->held_until = null;
            $from->save();

            if ($ticketSeat) {
                $ticketSeat->event_seat_inventory_id = $to->id;
                $ticketSeat->seat_label = $to->label;
                $ticketSeat->seat_location = $to->locationLabel();
                $ticketSeat->seat_number = $to->id;
                $ticketSeat->save();
            }

            $ticket = Ticket::find($ticketId);
            if ($ticket) {
                $labels = EventSeatInventory::where('ticket_id', $ticketId)
                    ->where('status', 'sold')
                    ->pluck('label')
                    ->all();
                $ticket->seat_numbers = json_encode($labels);
                $ticket->selected_seat_ids = json_encode(
                    EventSeatInventory::where('ticket_id', $ticketId)->where('status', 'sold')->pluck('id')->all()
                );
                $ticket->save();
            }

            return ['from' => $from->fresh(), 'to' => $to->fresh()];
        });
    }
}

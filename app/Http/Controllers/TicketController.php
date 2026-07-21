<?php

namespace App\Http\Controllers;

use App\Product;
use App\Ticket;
use App\TicketSeat;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $activeProductIds = Product::where('is_active', true)->pluck('id');

        // Prefer seat rows for currently active events only.
        $ticketSeat = TicketSeat::with('product', 'ticket')
            ->whereHas('ticket', function ($query) use ($activeProductIds) {
                $query->where('status', 1)
                    ->whereIn('product_id', $activeProductIds);
            })
            ->join('tickets', 'ticket_seats.ticket_id', '=', 'tickets.id')
            ->orderBy('tickets.created_at', 'desc')
            ->select('ticket_seats.*')
            ->get();

        return view('ticket.index', compact('ticketSeat'));
    }
}

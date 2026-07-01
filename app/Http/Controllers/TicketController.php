<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\TicketSeat;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index() {
        $ticketSeat = TicketSeat::with('product', 'ticket')
            ->whereHas('ticket', function ($query) {
                $query->where('status', 1);
            })
            ->join('tickets', 'ticket_seats.ticket_id', '=', 'tickets.id')
            ->orderBy('tickets.created_at', 'desc')
            ->select('ticket_seats.*') // avoid column conflict from join
            ->get();
        return view('ticket.index', compact('ticketSeat'));
    }
}

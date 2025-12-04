<?php

namespace App\Http\Controllers;

use App\Product;
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

    public function deleteBySelection(Request $request)
    {
        $ticketIds = $request['expenseIdArray'];

        if (!is_array($ticketIds)) {
            return "Invalid selection.";
        }

        foreach ($ticketIds as $id) {

            if ($id === null) {
                continue;
            }

            $ticket = Ticket::find($id);

            if (!$ticket) {
                continue;
            }

            // 1️⃣ Restore product quantity
            $product = Product::find($ticket->product_id);
            if ($product) {
                $product->qty = $product->qty + (int)$ticket->qty;
                $product->save();
            }

            // 2️⃣ Delete all related ticket seats
            TicketSeat::where("ticket_id", $ticket->id)->delete();

            // 3️⃣ Delete the ticket itself
            $ticket->delete();
        }

        return 'Tickets deleted successfully!';
    }
}

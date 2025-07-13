<?php

namespace App\Http\Controllers;

use App\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index() {
        $tickets = Ticket::with('product')->where('status', 1)->orderBy('created_at', 'desc')->get();
        return view('ticket.index', compact('tickets'));
    }
}

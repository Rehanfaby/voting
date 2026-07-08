<?php

namespace App\Http\Controllers;

use App\AboutMember;
use App\AboutWinner;
use App\Ambassador;
use App\Category;
use App\Coin;
use App\Employee;
use App\Gallery;
use App\GeneralSetting;
use App\Judge;
use App\Helpers\SiteContent;
use App\Helpers\PhoneHelper;
use App\Helpers\WhatsAppFormatter;
use App\ProductSeat;
use App\ProductSeatZone;
use App\TicketSeat;
use App\User;
use App\vote;
use Illuminate\Http\Request;
use App\Sale;
use App\Returns;
use App\ReturnPurchase;
use App\ProductPurchase;
use App\Purchase;
use App\Expense;
use App\Payroll;
use App\Quotation;
use App\Payment;
use App\Account;
use App\Product_Sale;
use App\Customer;
use App\Product;
use App\Ticket;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Printing;
use Rawilk\Printing\Contracts\Printer;
use Spatie\Permission\Models\Role;
use Stripe\EphemeralKey;
use Stripe\Stripe;
use Twilio\Rest\Client;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;

/*use vendor\autoload;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;*/

class HomeController extends Controller
{
    public function dashboard()
    {
        if(Auth::user()) {
            $role = Auth::user()->role_id;
            if($role == 3) {
                return $this->index();
            }
        }

        return view('home');
    }

    public function about()
    {
        $team = AboutMember::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $winnersYear = \App\Helpers\SiteContent::aboutWinnersYear();
        $winners = AboutWinner::where('year', $winnersYear)->get()->keyBy('placement');

        return view('frontend.about', compact('team', 'winners', 'winnersYear'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

     public function contactMessage(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:40',
            'email' => 'nullable|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $adminNumber = getenv('ADMIN_NUMBER') ?: '237675321739';
        $msg = WhatsAppFormatter::brandLine();
        $msg .= WhatsAppFormatter::bilingualHeading('📩', 'NOUVEAU MESSAGE', 'NEW CONTACT MESSAGE');
        $msg .= WhatsAppFormatter::bilingualGreeting($request->name);
        $msg .= WhatsAppFormatter::bilingualBody(
            'Vous avez reçu un message depuis le site.',
            'You have received a message from the website.'
        );
        $msg .= WhatsAppFormatter::bilingualLine('Nom', 'Name', $request->name);
        $msg .= WhatsAppFormatter::bilingualLine('Téléphone', 'Phone', $request->number);
        if ($request->filled('email')) {
            $msg .= WhatsAppFormatter::bilingualLine('E-mail', 'Email', $request->email);
        }
        $msg .= "\n■ *Message:*\n" . $request->message . "\n";
        $msg .= WhatsAppFormatter::footer('Répondez via WhatsApp.', 'Reply via WhatsApp.');

         try{
             $this->wpMessage($adminNumber, $msg);
         }
         catch(\Exception $e){

         }
         return back()->with('message', trans('file.Your message has been sent'));
     }

    public function admin() {

        if(Auth::user()) {
            $role = Auth::user()->role_id;
            if($role == 3) {
                return $this->index();
            }
        }

        return view('index');
    }

    public function index()
    {

//        $this->checkVotePayment();

        // Note: '/' always renders the public website, even for logged-in admins.
        // Admins reach the dashboard via '/admin' (see HomeController@admin).

        $musicians = Employee::where('is_active', true)->where('is_approve', true)->get();
        $judges = Judge::where('is_active', true)->get();
        $ambassadors = Ambassador::where('is_active', true)->get();

//        $judges = User::where('is_active', true)->where('role_id', $judge_role_id)->get();
//        $ambassadors = User::where('is_active', true)->where('role_id', $ambassador_role_id)->get();


//        $start_date = date('Y-m-d', strtotime('last monday'));
//        $end_date = date('Y-m-d');

        $mostVotedLimit = SiteContent::mostVotedCount();

        $weekly_top_rows = DB::table('votes')
            ->select('votes.musician_id', DB::raw('SUM(votes.vote) as total_vote'))
            ->join('employees', 'employees.id', '=', 'votes.musician_id')
            ->where('employees.is_active', true)
            ->where('employees.is_approve', true)
            ->where('votes.status', true)
            ->orderBy('total_vote', 'desc')
            ->groupBy('votes.musician_id')
            ->limit($mostVotedLimit)
            ->get();

        $weekly_top = [];
        foreach ($weekly_top_rows as $row) {
            $emp = Employee::find($row->musician_id);
            if ($emp) {
                $weekly_top[] = (object) [
                    'employee'   => $emp,
                    'total_vote' => (int) $row->total_vote,
                ];
            }
        }

        $best_musician_data = !empty($weekly_top) ? (object) [
            'musician_id' => $weekly_top[0]->employee->id,
            'total_vote'  => $weekly_top[0]->total_vote,
        ] : null;

        $best_musicians = DB::table('votes')
            ->select('votes.musician_id', DB::raw('SUM(votes.vote) as total_vote'))
            ->join('employees', 'employees.id', '=', 'votes.musician_id')
//            ->whereDate('votes.created_at', '>=', $start_date)
//            ->whereDate('votes.created_at', '<=', $end_date)
            ->where('employees.is_active', true)
            ->where('employees.is_approve', true)
            ->where('votes.status', true)
            ->orderBy('total_vote', 'desc')
            ->groupBy('votes.musician_id')
            ->limit(5)
            ->get();

        $best_musician = null;

        if ($best_musician_data != null) {
            $best_musician = Employee::find($best_musician_data->musician_id);
        }

        $see_votes = \App\Helpers\VoteSettings::showPublicCounts();

        SiteContent::expirePastEvents();

        // Total valid (paid) votes per contestant, keyed by musician id, for the
        // top carousel. Without this the view falls back to 0 for everyone.
        $vote_counts = DB::table('votes')
            ->select('musician_id', DB::raw('SUM(vote) as total_vote'))
            ->where('status', true)
            ->groupBy('musician_id')
            ->pluck('total_vote', 'musician_id')
            ->toArray();

        return view('frontend.home', compact('musicians', 'judges', 'best_musician', 'see_votes', 'ambassadors', 'best_musicians', 'best_musician_data', 'vote_counts', 'weekly_top'));
    }

    public function signup()
    {
        return view('frontend.signup');
    }

    public function login()
    {
        return view('frontend.login');
    }

    public function team(){
        return view('frontend.team', $this->contestantTeamPageData());
    }

    public function employee($id) {
        $musician = Employee::find($id);
        $images = Gallery::where('employee_id', $id)->where('type', 'image')->get();
        $audios = Gallery::where('employee_id', $id)->where('type', 'audio')->get();
        $videos = Gallery::where('employee_id', $id)->where('type', 'video')->get();
        $shorts = Gallery::where('employee_id', $id)->where('type', 'short')->get();
        $youtubes = Gallery::where('employee_id', $id)->where('type', 'link')->get();
        $contentants = Employee::where('is_active', true)->where('is_approve', true)->get();
        $teamData = $this->contestantTeamPageData($contentants);

        return view('frontend.employee', array_merge(
            compact('musician', 'contentants', 'images', 'audios', 'videos', 'shorts', 'youtubes'),
            $teamData
        ));
    }

    public function events() {
        $events = Category::where('is_active', true)->paginate(12);
        return view('frontend.events', compact('events'));
    }

    public function tickets($id) {
        $event = Category::where('id', $id)->where('is_active', true)->firstOrFail();
        $tickets = Product::where('category_id', $id)->where('is_active', true)
            ->select('id', 'name', 'qty', 'image', 'price', 'seat_selection_enabled')
            ->paginate(12);
        return view('frontend.tickets', compact('tickets', 'event'));
    }

    public function ticket($id) {
        $ticket = Product::with('category')->findOrFail($id);
        return view('frontend.ticket', compact('ticket'));
    }

    public function purchaseTicket(Request $request) {
        $data = $request->all();
        $ticket = Product::with('category')->findOrFail($data['ticket_id']);
        $seatSelection = $this->resolveTicketSeatSelection($ticket, $request);

        if ($seatSelection === false) {
            return back()->with('not_permitted', trans('file.One or more seats are no longer available'));
        }

        if (is_array($seatSelection)) {
            $data['seat_ids'] = implode(',', $seatSelection['ids']);
            $data['seat_labels'] = implode(', ', $seatSelection['labels']);
            $data['vote'] = count($seatSelection['ids']);
            $data['amount'] = $seatSelection['total'];
        } else {
            $qty = max(1, (int) ($data['vote'] ?? 1));
            $data['vote'] = $qty;
            $data['amount'] = $qty * $ticket->price;
        }

        return view('frontend.ticket-payment', compact('data', 'ticket', 'seatSelection'));
    }

    public function employeeFind(Request $request) {
        $musicians = Employee::where('name', 'LIKE', '%' . $request->search . '%')
            ->where('is_active', true)
            ->where('is_approve', true)
            ->get();

        return view('frontend.team', $this->contestantTeamPageData($musicians));
    }

    public function employeeVote(Request $request) {
        $data = $request->all();
        $musician = Employee::find($data['musician_id']);
        return view('frontend.payment', compact('data', 'musician'));
    }

    public function userContentant() {
        $votes = vote::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        return view('frontend.votes', compact('votes'));
    }

    public function userEvents() {
        $tickets = Ticket::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(5);
        return view('frontend.ticket-purchased', compact('tickets'));
    }

    public function musicianVotePayment(Request $request) {

        if ($request->input('payment_method') === 'card') {
            return $this->musicianVotePaymentStripe($request);
        }

        $phone = PhoneHelper::fromLocalDigits($request->input('phone_local'))
            ?? PhoneHelper::cameroon($request->phone);
        $whatsapp = PhoneHelper::fromLocalDigits($request->input('whatsapp_local'))
            ?? PhoneHelper::cameroon($request->whatsapp_number ?? $phone);

        if (!$phone) {
            return back()->with('not_permitted', 'Please enter a valid mobile money number.');
        }
        if (!$whatsapp) {
            return back()->with('not_permitted', 'Please enter a valid WhatsApp number for your confirmation.');
        }

        $voterName = $this->resolveVoterName($request, $phone);
        if (!$voterName) {
            return back()->withInput()->with('not_permitted', trans('file.Please enter your name'));
        }

        $user = $this->findOrCreateVoterUser($phone, $whatsapp, $voterName);

        $general_setting = GeneralSetting::pluck('vote_price')->first();
        $vote = vote::create([
                    'user_id' => $user->id,
                    'musician_id' => $request->musician_id,
                    'vote' => $request->vote,
                    'status' => false,
                    'reference' => 'pending',
                    'price' => $general_setting,
                    'grand_total' => $general_setting * $request->vote,
                    'whatsapp_number' => $whatsapp
                ]);

        $token = getenv("MOMO_TOKEN");
        if($token && $vote) {
            $campayNumber = ltrim($phone, '+');
            $amount = $request->amount;
            $reference = $this->mobileMoneyCollect($token, $campayNumber, $amount, $vote->id, 'Mulema Gospel Vote');
            if ($reference == false) {
                $vote->delete();
                $message = 'Phone Number is incorrect or There is any other issue in payment method';
                return redirect()->route('home')->with('not_permitted', $message);
            }

            $vote->reference = $reference;
            $vote->save();

            $this->sendWhatsappMsgVoteMomo($user, $vote->vote, $vote->musician_id, $whatsapp, $amount);

            return redirect()->route('musician.vote.payment.pending', $vote->id);
        }

        if (PhoneHelper::paymentSimulate() && $vote) {
            $vote->status = true;
            $vote->reference = 'SIM-' . $vote->id;
            $vote->save();
            $this->sendWhatsappMsgVoteMomoSuccess($user, $vote->vote, $vote->musician_id, $vote);

            return redirect()->route('home')->with('message', trans('file.Thank you for your voting'));
        }

        $vote->delete();
        $message = 'There is any other issue in payment method, please contact the system administrator';
        return back()->with('not_permitted', $message);
    }

    public function musicianVotePaymentStripe(Request $request) {

        $phone = PhoneHelper::fromLocalDigits($request->input('phone_local'))
            ?? PhoneHelper::fromLocalDigits($request->input('whatsapp_local'))
            ?? PhoneHelper::cameroon($request->phone);
        $whatsapp = PhoneHelper::fromLocalDigits($request->input('whatsapp_local'))
            ?? PhoneHelper::cameroon($request->whatsapp_number ?? $phone);

        if (!$phone) {
            return back()->with('not_permitted', 'Please enter a valid phone number.');
        }
        if (!$whatsapp) {
            return back()->with('not_permitted', 'Please enter a valid WhatsApp number for your confirmation.');
        }

        $voterName = $this->resolveVoterName($request, $phone);
        if (!$voterName) {
            return back()->withInput()->with('not_permitted', trans('file.Please enter your name'));
        }

        $user = $this->findOrCreateVoterUser($phone, $whatsapp, $voterName);

        $general_setting = GeneralSetting::pluck('vote_price')->first();
        $vote = vote::create([
            'user_id' => $user->id,
            'musician_id' => $request->musician_id,
            'vote' => $request->vote,
            'status' => false,
            'reference' => 'abc',
            'price' => $general_setting,
            'grand_total' => $general_setting * $request->vote,
            'whatsapp_number' => $whatsapp

        ]);

        if($vote) {
            $route = route('musician.vote.payment.check.stripe');
            $amount = $request->amount;

            $link = $this->createCheckoutSession($amount, $route, $vote->id, $vote);
            if ($link == false) {
                $message = 'There is any other issue in payment method';
                return back()->with('not_permitted', $message);
            }

            return redirect($link);
            die();
        }
        $message = 'There is any other issue in payment method, please contact the system administrator';
        return back()->with('not_permitted', $message);
    }


    public function musicianVotePaymentCheckStripe(Request $request)
    {

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = \Stripe\Checkout\Session::retrieve($request->session_id);

//        dd($session);

        if ($session->payment_status !== 'paid') {
            Vote::where('id', $session->metadata->vote_id)->delete();
            return redirect()->route('home')->with('not_permitted', 'payment failed.');
        }

        $vote = Vote::where('id', $session->metadata->vote_id)->first();
        $vote->status = true;
        $vote->reference = $session->payment_intent;
        $vote->save();

        if($vote) {
            $this->sendWhatsappMsgVoteMomoSuccess($vote->voters, $vote->vote, $vote->musician_id, $vote);
            $message = 'Thank you for your voting';
            return redirect()->route('home')->with('message', $message);
        }

        $message = 'There is any issue, please contact the system administrator';
        return redirect()->route('home')->with('not_permitted', $message);

    }

    public function ticketPaymentStripe(Request $request) {

        $user = Auth::user() ?? null;
        $password = rand(1, 999999);
        $data['is_active'] = true;
        $data['is_deleted'] = false;
        $data['password'] = bcrypt($password);
        $data['name'] = $request->phone;
        $data['phone'] = $request->phone;
        $data['email'] = 'user@gmail.com';
        $data['role_id'] = 3;

        if($data['phone'] == null) {
            return 'Phone cannot be null';
        }

        if ($user_check = User::where('phone', $request->phone)->first()) {
            $user = $user_check;
        }

        if($user == null) {
            $user = User::create($data);
            $this->sendWhatsappMsg($user, $password);
        }

        $product = Product::findOrFail($request->ticket_id);
        $seatSelection = $this->resolveTicketSeatSelection($product, $request);
        if ($product->seat_selection_enabled && $seatSelection === false) {
            return back()->with('not_permitted', trans('file.One or more seats are no longer available'));
        }

        $qty = is_array($seatSelection) ? count($seatSelection['ids']) : (int) $request->qty;
        $amount = is_array($seatSelection) ? $seatSelection['total'] : (float) $request->amount;

        $ticket = Ticket::create([
            'user_id' => $user->id,
            'product_id' => $request->ticket_id,
            'qty' => $qty,
            'status' => false,
            'reference' => 'abc',
            'identity_type' => $request->identity_type ?? 1,
            'cnic' => $request->identity_number,
            'student_card' => $request->student_number,
            'passport' => $request->passport_number,
            'phone' => $request->phone,
            'name' => $request->name,
            'email' => $request->email,
            'token' => Str::random(6),
            'price' => $product->price,
            'total_amount' => $amount,
            'payment_method' => 1,
            'selected_seat_ids' => is_array($seatSelection) ? json_encode($seatSelection['ids']) : null,
        ]);

        if (is_array($seatSelection)) {
            $this->reserveTicketSeats($ticket, $seatSelection['ids']);
        }

        if($ticket) {
            $route = route('ticket.payment.check.stripe');
            $mtn_number = $data['phone'];
            $amount = $request->amount;

            $link = $this->createCheckoutSessionForTicket($amount, $route, $ticket->id);
            if ($link == false) {
                $message = 'There is any other issue in payment method';
                return back()->with('not_permitted', $message);
            }

            return redirect($link);
            die();
        }
        $message = 'There is any other issue in payment method, please contact the system administrator';
        return back()->with('not_permitted', $message);
    }


    public function ticketPaymentCheckStripe(Request $request)
    {

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = \Stripe\Checkout\Session::retrieve($request->session_id);

        if ($session->payment_status !== 'paid') {
            $ticket = Ticket::where('id', $session->metadata->ticket_id)->first();
            if ($ticket) {
                $this->releaseTicketSeats($ticket);
                $ticket->delete();
            }
            return redirect()->route('home')->with('not_permitted', 'payment failed.');
        }

        $ticket = Ticket::where('id', $session->metadata->ticket_id)->first();

        if($ticket) {
            $this->processTicketSuccessfulPayment($ticket, $session->payment_intent);

            $message = 'Thank you for your Purchasing Ticket';
            return redirect()->route('home')->with('message', $message);
        }

        $message = 'There is any issue, please contact the system administrator';
        return redirect()->route('home')->with('not_permitted', $message);

    }

    public function ticketPayment(Request $request) {

        $user = Auth::user() ?? null;
        $password = rand(1, 999999);
        $data['is_active'] = true;
        $data['is_deleted'] = false;
        $data['password'] = bcrypt($password);
        $data['name'] = $request->name;
        $data['phone'] = PhoneHelper::fromLocalDigits($request->input('phone_local'))
            ?? PhoneHelper::cameroon($request->phone);
        $data['whatsapp_number'] = PhoneHelper::fromLocalDigits($request->input('whatsapp_local'))
            ?? PhoneHelper::cameroon($request->whatsapp_number ?? $data['phone']);
        $data['email'] = $request->email ?? 'user@gmail.com';
        $data['role_id'] = 3;


        if($data['phone'] == null) {
            return back()->with('not_permitted', trans('file.Please enter a valid mobile money number.'));
        }

        if ($user_check = User::where('phone', $request->phone)->first()) {
            if($user_check->whatsapp_number !== $request->whatsapp_number) {
                $user_check->whatsapp_number = $request->whatsapp_number;
                $user_check->save();
            }
            $user = $user_check;
        }

        if($user == null) {
            $user = User::create($data);
            $this->sendWhatsappMsg($user, $password);
        }
        $product = Product::findOrFail($request->ticket_id);
        $seatSelection = $this->resolveTicketSeatSelection($product, $request);
        if ($product->seat_selection_enabled && $seatSelection === false) {
            return back()->with('not_permitted', trans('file.One or more seats are no longer available'));
        }

        $qty = is_array($seatSelection) ? count($seatSelection['ids']) : (int) $request->qty;
        $amount = is_array($seatSelection) ? $seatSelection['total'] : (float) $request->amount;

        $ticket = Ticket::create([
                    'user_id' => $user->id,
                    'product_id' => $request->ticket_id,
                    'qty' => $qty,
                    'status' => false,
                    'reference' => 'abc',
                    'identity_type' => $request->identity_type ?? 1,
                    'cnic' => $request->identity_number,
                    'student_card' => $request->student_number,
                    'passport' => $request->passport_number,
                    'phone' => $request->phone,
                    'name' => $request->name,
                    'email' => $request->email,
                    'token' => Str::random(6),
                    'price' => $product->price,
                    'total_amount' => $amount,
                    'payment_method' => 0,
                    'selected_seat_ids' => is_array($seatSelection) ? json_encode($seatSelection['ids']) : null,
                ]);

        if (is_array($seatSelection)) {
            $this->reserveTicketSeats($ticket, $seatSelection['ids']);
        }
        $token = getenv("MOMO_TOKEN");
        if($token && $ticket) {
            $route = route('ticket.payment.check');
            $mtn_number = $data['phone'];
            $amount = $request->amount;
            $link = $this->mobileMoneyRequestLink($token, $amount, $route, $ticket->id, $mtn_number);
            if ($link == false) {
                $this->releaseTicketSeats($ticket);
                $ticket->delete();
                $message = 'Phone Number is incorrect or There is any other issue in payment method';
                return redirect()->route('home')->with('not_permitted', $message);
            }
            header("Location: $link");
            die();
        }

        if (PhoneHelper::paymentSimulate() && $ticket) {
            $this->processTicketSuccessfulPayment($ticket, 'SIM-' . $ticket->id);

            return redirect()->route('home')->with('message', trans('file.Thank you for your Purchasing Ticket'));
        }

        $this->releaseTicketSeats($ticket);
        $ticket->delete();
        $message = 'There is any other issue in payment method, please contact the system administrator';
        return back()->with('not_permitted', $message);
    }

    public function ticketPaymentCheck(Request $request)
    {
        if($request->status != 'SUCCESSFUL'){
            $ticket = Ticket::where('id', $request->external_reference)->first();
            if ($ticket) {
                $this->releaseTicketSeats($ticket);
                $ticket->delete();
            }
            return redirect()->route('home')->with('not_permitted', 'payment failed.');
        }

        $ticket = Ticket::where('id', $request->external_reference)->first();
        if($ticket) {
            $this->processTicketSuccessfulPayment($ticket, $request->reference);

            $message = 'Thank you for your Purchasing Ticket';
            return redirect()->route('home')->with('message', $message);
        }

        $message = 'There is any issue, please contact the system administrator';
        return redirect()->route('home')->with('not_permitted', $message);

    }

    private function processTicketSuccessfulPayment($ticket, $reference)
    {
        $ticket->status = true;
        $ticket->reference = $reference;

        $seatIds = json_decode($ticket->selected_seat_ids, true);
        if (is_array($seatIds) && count($seatIds)) {
            $seats = ProductSeat::where('product_id', $ticket->product_id)
                ->whereIn('id', $seatIds)
                ->get();
            $labels = [];
            foreach ($seats as $seat) {
                $seat->status = 'sold';
                $seat->ticket_id = $ticket->id;
                $seat->save();
                $labels[] = $seat->label;
                TicketSeat::create([
                    'ticket_id' => $ticket->id,
                    'product_id' => $ticket->product_id,
                    'seat_number' => $seat->id,
                    'seat_label' => $seat->label,
                    'product_seat_id' => $seat->id,
                    'token' => Str::random(6),
                ]);
            }
            $ticket->seat_numbers = json_encode($labels);
            $ticket->save();
        } else {
            $lastSeat = TicketSeat::where('product_id', $ticket->product_id)->orderBy('id', 'desc')->first()->seat_number ?? 0;
            $qty = (int) $ticket->qty;
            $seatNumbers = range($lastSeat + 1, $lastSeat + $qty);
            $ticket->seat_numbers = json_encode($seatNumbers);
            $ticket->save();

            for ($i = 1; $i <= $qty; $i++) {
                $lastSeat++;
                TicketSeat::create([
                    'ticket_id' => $ticket->id,
                    'product_id' => $ticket->product_id,
                    'seat_number' => $lastSeat,
                    'token' => Str::random(6),
                ]);
            }
        }

        $this->sendWhatsappMsgTicketMomoSuccess($ticket);
        return true;
    }

    /** Shared data for the public contestants / vote page. */
    private function contestantTeamPageData($musicians = null)
    {
        if ($musicians === null) {
            $musicians = Employee::where('is_active', true)->where('is_approve', true)->get();
        }

        $see_votes = \App\Helpers\VoteSettings::showPublicCounts();
        $vote_counts = DB::table('votes')
            ->select('musician_id', DB::raw('SUM(vote) as total_vote'))
            ->where('status', true)
            ->groupBy('musician_id')
            ->pluck('total_vote', 'musician_id')
            ->toArray();

        return compact('musicians', 'see_votes', 'vote_counts');
    }

    /**
     * @return array|false|null  seat bundle, false if invalid, null if seat selection off
     */
    private function resolveTicketSeatSelection(Product $product, Request $request)
    {
        if (!$product->seat_selection_enabled) {
            return null;
        }

        $raw = $request->input('seat_ids', '');
        $ids = array_values(array_unique(array_filter(array_map('intval', explode(',', (string) $raw)))));
        if (empty($ids)) {
            return false;
        }

        $seats = ProductSeat::where('product_id', $product->id)
            ->whereIn('id', $ids)
            ->where('status', 'available')
            ->with('zone')
            ->get();

        if ($seats->count() !== count($ids)) {
            return false;
        }

        $total = $seats->sum(function ($seat) use ($product) {
            return $seat->zone ? (float) $seat->zone->price : (float) $product->price;
        });

        return [
            'ids' => $seats->pluck('id')->all(),
            'labels' => $seats->pluck('label')->all(),
            'total' => $total,
        ];
    }

    private function reserveTicketSeats(Ticket $ticket, array $seatIds)
    {
        ProductSeat::where('product_id', $ticket->product_id)
            ->whereIn('id', $seatIds)
            ->where('status', 'available')
            ->update(['status' => 'reserved', 'ticket_id' => $ticket->id]);
    }

    private function releaseTicketSeats(Ticket $ticket)
    {
        ProductSeat::where('ticket_id', $ticket->id)
            ->whereIn('status', ['reserved'])
            ->update(['status' => 'available', 'ticket_id' => null]);
    }

    public function musicianVotePaymentPending($id)
    {
        $vote = vote::findOrFail($id);
        if ($vote->status) {
            return redirect()->route('home')->with('message', 'Thank you for your voting');
        }
        $musician = Employee::findOrFail($vote->musician_id);

        return view('frontend.payment-pending', compact('vote', 'musician'));
    }

    public function musicianVotePaymentPoll(Request $request)
    {
        $vote = vote::find($request->vote_id);
        if (!$vote) {
            return response()->json(['status' => 'UNKNOWN']);
        }
        if ($vote->status) {
            return response()->json(['status' => 'SUCCESSFUL']);
        }

        $token = getenv("MOMO_TOKEN");
        if (!$token || !$vote->reference || $vote->reference === 'pending') {
            return response()->json(['status' => 'PENDING']);
        }

        $result = $this->mobileMoneyStatus($token, $vote->reference);
        if ($result === 1) {
            $vote->status = true;
            $vote->save();
            $this->sendWhatsappMsgVoteMomoSuccess($vote->voters, $vote->vote, $vote->musician_id, $vote);

            return response()->json(['status' => 'SUCCESSFUL']);
        }
        if ($result === 2) {
            $vote->delete();

            return response()->json(['status' => 'FAILED']);
        }

        return response()->json(['status' => 'PENDING']);
    }

    public function musicianVotePaymentCheck(Request $request)
    {
        if($request->status != 'SUCCESSFUL'){
            Vote::where('id', $request->external_reference)->delete();
            return redirect()->route('home')->with('not_permitted', 'payment failed.');
        }

        $vote = Vote::where('id', $request->external_reference)->first();
        $vote->status = true;
        $vote->reference = $request->reference;
        $vote->save();

        if($vote) {
            $this->sendWhatsappMsgVoteMomoSuccess($vote->voters, $vote->vote, $vote->musician_id, $vote);
            $message = 'Thank you for your voting';
            return redirect()->route('home')->with('message', $message);
        }

        $message = 'There is any issue, please contact the system administrator';
        return redirect()->route('home')->with('not_permitted', $message);

    }

    public function handleCampayWebhook(Request $request)
    {
        $data = $request->all();

        Log::info('Campay Webhook Received', $data);

        if (($data['status'] ?? '') === 'SUCCESSFUL' && isset($data['external_reference'])) {
            $vote = Vote::where('id', $data['external_reference'])->first();

            if ($vote && !$vote->status) {
                $vote->status = true;
                $vote->reference = $data['reference'] ?? 'from_webhook';
                $vote->save();
                Log::info('Campay Webhook Completed', $data);
                $this->sendWhatsappMsgVoteMomoSuccess($vote->voters, $vote->vote, $vote->musician_id, $vote);
            }
        }

        return response()->json(['status' => 'ok'], 200);
    }

    public function musicianVotePaymentCoin(Request $request) {

        $user = Auth::user() ?? null;

        if ($request->phone_number == '+237' || $request->phone_number == null) {
            return "Phone number is incorrect";
        }

        if ($request->code == null) {
            return "Code is incorrect";
        }

        if($request->phone_number[0] == '+') {
            $request->phone_number = substr($request->phone_number, 1);
        }

        $coin_check = Coin::where('phone', $request->phone_number)->where('is_active', true)->where('code', $request->code)->first();
        if (!$coin_check) {
            return "You have entered incorrect phone number and code";
        }

        if ($user == null) {
            $user = User::where('phone', $request->phone_number)->first();
        }

        if ($user == null) {
            $password = rand(1, 999999);
            $data['is_active'] = true;
            $data['is_deleted'] = false;
            $data['password'] = bcrypt($password);
            $data['name'] = $request->phone_number;
            $data['phone'] = $request->phone_number;
            $data['email'] = 'user@gmail.com';
            $data['role_id'] = 3;
            $user = User::create($data);
            $this->sendWhatsappMsg($user, $password);
        }

        $general_setting = GeneralSetting::pluck('vote_coin')->first();
        if($request->amount <= $coin_check->coin) {
            vote::create([
                'user_id' => $user->id,
                'musician_id' => $request->musician_id,
                'vote' => $request->vote,
                'status' => true,
                'reference' => rand(1, 999999),
                'price' => $general_setting,
                'grand_total' => $request->amount,
                'whatsapp_number' => $data['whatsapp_number']
            ]);
            $remaining_coin = $coin_check->coin - $request->amount;

            $coin_check->update(['coin' => $remaining_coin]);
            $this->sendWhatsappMsgVote($user, $request->vote, $request->musician_id, $remaining_coin);
            return 'Thank you for your vote';
        }

        return "You don't have enough Coins";
    }

    public function logout() {

        $user = Auth::user();
        $user->update(['otp_verify' => '0']);
        Auth::logout();
        return redirect()->route('home');
    }

    public function otpCheck(){
        $user = Auth::user();
        $sent = $this->sendOTP($user);
        $user->refresh();

        return view('otp_screen', [
            'otp_sent_at' => $user->otp_time,
            'otp_send_failed' => $sent === false && !$user->otp_time,
        ]);
    }

    public function otpResend()
    {
        $user = Auth::user();
        if ($this->sendOTP($user)) {
            return redirect()->route('check.otp')->with('message', trans('file.OTP resent successfully'));
        }

        return redirect()->route('check.otp')->with('not_permitted', trans('file.OTP resend failed'));
    }

    public function otpCancel()
    {
        Auth::logout();

        return redirect()->route('login');
    }

    public function otpCheckStore(Request $request) {
        $user = Auth::user();

        if ($request->otp == $user->otp && $user->otp_time > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {
            $user->update(['otp' => null, 'otp_time' => null, 'otp_verify' => '1']);
            if ((int) $user->role_id !== 3) {
                return redirect('/admin');
            }
            return redirect()->route('home');
        } else {
            return redirect()->back()->with('not_permitted', trans('file.Invalid OTP'));
        }
    }

    public function sendOTP($user) {
        if ($user->otp_time != null && $user->otp_time >= date('Y-m-d H:i:s', strtotime('-1 minutes'))) {
            return null;
        }

        $recipient = $this->loginOtpRecipient($user);
        if (!$recipient) {
            \Log::warning('Login OTP: user has no phone', ['user_id' => $user->id]);
            return false;
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $msg = WhatsAppFormatter::otpMessage($user->name ?? 'User', $otp, 3);

        if (!$this->wpMessage($recipient, $msg)) {
            \Log::warning('Login OTP WhatsApp delivery failed', [
                'user_id' => $user->id,
                'to' => \App\Helpers\PhoneHelper::forUltraMsg($recipient),
            ]);
            return false;
        }

        $user->update(['otp' => $otp, 'otp_time' => date('Y-m-d H:i:s')]);

        return true;
    }

    /** Prefer a valid Cameroon number for login OTP delivery. */
    private function loginOtpRecipient($user)
    {
        $candidates = array_filter([$user->phone, $user->whatsapp_number]);
        foreach ($candidates as $candidate) {
            $e164 = \App\Helpers\PhoneHelper::forUltraMsg($candidate);
            if ($e164 && strpos($e164, '+237') === 0) {
                return $candidate;
            }
        }

        return $user->phone ?: $user->whatsapp_number;
    }

    public function whatsapp()
    {
        $sid = getenv("ACCOUNT_SID");
        $token = getenv("AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        $message = $twilio->messages->create(
            'whatsapp:+923410060960', // +23775321739
            array(
                'from' => getenv("TWILIO_FROM"),
                'body' => 'hi twilio here'
            )
        );

        print($message->sid);
    }


    public function mobileMoneyToken(){
        $curl = curl_init();


        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://demo.campay.net/api/token/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                                "username": "tzTGYFo9eF9d4E8VdQ6G_WnCmOtTQSlZKbY5bJaoeSZpUhD5Z6hMTzaZ8of39L0-FvHBS6YMyZyDxtclpsEcnw",
                                "password": "EpER_6cr-YQjIDqlee4-6yVSG1KpB2zL4VTy1tROoE_f6YNYxCl_llU-h43QqBrfI9JwkKx-XT5RUXx2AnNOOw"
                                }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $response_decode = json_decode($response, true);

        curl_close($curl);

        if($response_decode && $response_decode['token']) {
            return $response_decode['token'];
        }
        return false;
    }

    public function mobileMoneyCollect($token, $number, $amount, $externalReference, $description = 'Mulema Gospel Vote'){

        $payload = json_encode([
            'amount' => (string) $amount,
            'from' => (string) $number,
            'description' => $description,
            'external_reference' => (string) $externalReference,
            'currency' => 'XAF',
        ]);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.campay.net/api/collect/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Token ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $response_decode = json_decode($response, true);

        curl_close($curl);

        if($response_decode && isset($response_decode['reference'])) {
            return $response_decode['reference'];
        }

        return false;
    }

    public function mobileMoneyRequest($token, $number, $amount){

        return $this->mobileMoneyCollect($token, $number, $amount, '', 'Test');
    }

    public function mobileMoneyStatus($token, $reference){


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.campay.net/api/transaction/'.$reference.'/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Token ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $response_decode = json_decode($response, true);

        curl_close($curl);

        if($response_decode && isset($response_decode['status'])) {
            if($response_decode['status'] == 'SUCCESSFUL') {
                return 1;
            }
            if($response_decode['status'] == 'FAILED') {
                return 2;
            }
        }

        return 0;

    }

//    private function checkVotePayment(){
//        $votes = vote::where('created_at', '>' , date('Y-m-d H:i:s', strtotime('-1440 minutes')))->where('status', 0)->get();
//        if($votes->isEmpty()) {
//            return true;
//        }
//
//        $token = getenv("MOMO_TOKEN");
//        foreach ($votes as $vote) {
//            $status = $this->mobileMoneyStatus($token, $vote->reference);
//            if($status == 1) {
//                $vote->update(['status' => 1]);
//                $this->sendWhatsappMsgVoteMomoSuccess($vote->voters, $vote->vote, $vote->musician_id);
//            }
//            if($status == 2) {
//                $vote->update(['status' => 2]);
//            }
//        }
//    }

    public function sendWhatsappMsg($user, $password){

        $msg = WhatsAppFormatter::compose(
            '🎉',
            'COMPTE CRÉÉ',
            'ACCOUNT CREATED',
            $user->name ?? 'User',
            'Félicitations ! Votre compte a été créé avec succès.',
            'Congratulations! Your account has been created successfully.',
            [
                ['Nom d\'utilisateur', 'Username', $user->name ?? '—'],
                ['Téléphone', 'Phone', $user->phone ?? '—'],
                ['Mot de passe', 'Password', (string) $password],
            ],
            'Bienvenue à bord !',
            'Welcome aboard!'
        );

        try{
            $this->wpMessage($user->whatsapp_number ?? $user->phone, $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }


    public function sendWhatsappMsgVote($user, $vote, $musician_id, $remaining_coin)
    {
        $musician = Employee::select('name', 'id')->find($musician_id);
        $total_votes = vote::where('musician_id', $musician_id)->where('status', true)->sum('vote');

//        $msg = '*Congrats:* You have casted ' . $vote;
//        if ($vote == 1) {
//            $msg .= ' vote ';
//        } else {
//            $msg .= ' votes ';
//        }
//        $msg .= 'for ' .$musician->name . '\n\n';
//        $msg .= $musician->name . '`s total votes are  '.$total_votes.'\n\n';
//
//        $msg .= 'Your remaining coins are  '.$remaining_coin.'\n\n';


        $voteLabel = $vote > 1 ? "{$vote} votes" : '1 vote';

        $msg = WhatsAppFormatter::compose(
            '🗳️',
            'VOTE ENREGISTRÉ',
            'VOTE RECORDED',
            $user->name ?? 'Voter',
            "Vous avez voté {$vote} fois pour {$musician->name}.",
            "You successfully cast {$voteLabel} for {$musician->name}.",
            [
                ['Candidat', 'Contestant', $musician->name ?? '—'],
                ['Votes', 'Votes cast', (string) $vote],
                ['Total des votes', 'Total votes', (string) $total_votes],
                ['Coins restants', 'Remaining coins', (string) $remaining_coin],
            ],
            'Continuez à soutenir votre candidat !',
            'Keep supporting your favourite contestant!'
        );

        try{
            $this->wpMessage($user->phone, $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }


    public function sendWhatsappMsgVoteMomo($user, $vote, $musician_id, $whatsapp = null, $amount = null)
    {
        $musician = Employee::select('name', 'id')->find($musician_id);
        $total_votes = vote::where('musician_id', $musician_id)->where('status', true)->sum('vote');
        $recipient = $whatsapp ?? $user->whatsapp_number ?? $user->phone;
        $amountLine = $amount ? number_format((float) $amount) . ' CFA' : null;
        $lines = [
            ['Candidat', 'Contestant', $musician->name ?? '—'],
            ['Votes', 'Votes', (string) $vote],
            ['Total actuel', 'Current total', (string) $total_votes],
            ['Statut', 'Status', 'En attente / Pending'],
        ];
        if ($amountLine) {
            array_splice($lines, 2, 0, [['Montant', 'Amount', $amountLine]]);
        }

        $msg = WhatsAppFormatter::compose(
            '💳',
            'PAIEMENT DE VOTE EN ATTENTE',
            'VOTE PAYMENT PENDING',
            $user->name ?? 'Voter',
            'Veuillez finaliser votre paiement Mobile Money dans les 30 minutes.',
            'Please complete your Mobile Money payment within 30 minutes.',
            $lines,
            'Validez la demande MoMo sur votre téléphone.',
            'Approve the MoMo prompt on your phone.'
        );

        try{
            $this->wpMessage($recipient, $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }

    public function sendWhatsappMsgTicketMomoSuccess($ticket)
    {

        $ticketSeats = TicketSeat::where('ticket_id', $ticket->id)->get();

        $path = public_path('public/images/customer/docs/');
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        foreach ($ticketSeats as $ticketSeat) {
            $user = User::find($ticket->user_id);
            $eventName = $ticket->product->name ?? 'Event';

            $msg = WhatsAppFormatter::compose(
                '🎫',
                'BILLET CONFIRMÉ',
                'TICKET CONFIRMED',
                $user->name ?? 'Guest',
                'Merci pour votre achat de billet.',
                'Thank you for your ticket purchase.',
                [
                    ['Événement', 'Event', $eventName],
                    ['N° billet', 'Ticket no.', (string) $ticketSeat->token],
                    ['Siège', 'Seat', (string) $ticketSeat->seat_label ?? (string) $ticketSeat->seat_number],
                    ['Quantité', 'Quantity', '1'],
                ],
                'Présentez le QR code à l\'entrée.',
                'Present your QR code at the entrance.'
            );

            try{
                $this->wpMessage($user->whatsapp_number ?? $user->phone, $msg);
            }
            catch(\Exception $e){
            }

            // send QR code
            $filename = 'qr_code_' . $ticketSeat->token . '.png';
            $url = url("/ticket/scan/$ticketSeat->token");
            QrCode::format('png')->size(300)->generate($url, $path . $filename);
            try {
                $this->wpAttachMessage($path.$filename, $user->whatsapp_number ?? $user->phone, $filename);
            } catch (\Exception $e) {
            }
            // Delete the QR code file after sending
            if (file_exists($path . $filename)) {
                unlink($path . $filename);
            }
        }
        return true;
    }

    public function sendWhatsappMsgVoteMomoSuccess($user, $vote, $musician_id, $vote_data)
    {
        $musician = Employee::select('name', 'id')->find($musician_id);
        $total_votes = vote::where('musician_id', $musician_id)->where('status', true)->sum('vote');

//        $msg = '*Thank you for your vote,*  \n\n';
//
//        $msg .= 'You have casted ' . $vote;
//        if ($vote == 1) {
//            $msg .= ' vote ';
//        } else {
//            $msg .= ' votes ';
//        }
//        $msg .= 'for ' .$musician->name . '\n\n';
//        $msg .= $musician->name . '`s total votes are  '.$total_votes.'\n\n';


        $msg = WhatsAppFormatter::compose(
            '✅',
            'VOTE CONFIRMÉ',
            'VOTE CONFIRMED',
            $user->name ?? 'Voter',
            'Merci ! Votre vote a été enregistré avec succès.',
            'Thank you! Your vote has been recorded successfully.',
            [
                ['Candidat', 'Contestant', $musician->name ?? '—'],
                ['Votes', 'Votes cast', (string) $vote],
                ['Nouveau total', 'New total votes', (string) $total_votes],
                ['Statut', 'Status', 'Confirmé ✓ / Confirmed ✓'],
            ],
            'Chaque vote compte — merci pour votre soutien !',
            'Every vote counts — thank you for your support!'
        );

        try{
            $this->wpMessage($vote_data->whatsapp_number ?? $user->phone, $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }



    public function ticketScan($token) {
        $ticketSeat = TicketSeat::where('token', $token)->first();
        $error = false;
        if($ticketSeat) {
            $ticket = $ticketSeat->ticket;
            return view('frontend.ticket-scan-permission', compact('ticket', 'ticketSeat'));
        } else {
            return view('frontend.ticket-scan-permission', compact('error'));
        }
    }

    public function ticketScanUsed($token) {
        $ticketSeat = TicketSeat::where('token', $token)->first();

        if($ticketSeat) {
            $ticket = Ticket::where('id', $ticketSeat->ticket_id)->first();
            if($ticketSeat->is_used == true) {
                $error = 'This ticket has already been scanned, Ticket was scanned at: ' . $ticketSeat->used_at;
                return view('frontend.ticket-scan', compact('error'));
            }
            if($ticket->status == false) {
                $error = 'This ticket is not paid yet';
                return view('frontend.ticket-scan', compact('error'));
            }
            if($ticket->product->is_active == false) {
                $error = 'This ticket is not valid';
                return view('frontend.ticket-scan', compact('error'));
            }
            if($ticket->product->event_day && $ticket->product->event_day < date('Y-m-d')) {
                $error = 'This ticket is not valid, event date has been passed, Event date: ' . $ticket->product->event_day;
                return view('frontend.ticket-scan', compact('error'));
            }
            $user = User::find($ticket->user_id);
            if($user) {
                $msg = WhatsAppFormatter::compose(
                    '🎟️',
                    'BILLET SCANNÉ',
                    'TICKET SCANNED',
                    $user->name ?? 'Guest',
                    'Votre billet a été scanné avec succès à l\'entrée.',
                    'Your ticket has been scanned successfully at the entrance.',
                    [
                        ['N° billet', 'Ticket no.', (string) $token],
                        ['Siège', 'Seat', (string) $ticketSeat->seat_number],
                        ['Événement', 'Event', $ticket->product->name ?? '—'],
                        ['Date', 'Event date', (string) ($ticket->product->event_day ?? '—')],
                    ]
                );
                try{
                    $this->wpMessage($user->whatsapp_number ?? $user->phone, $msg);
                }
                catch(\Exception $e){

                }
            }
            $ticketSeat->is_used = true;
            $ticketSeat->used_at = date('Y-m-d H:i:s');
            $ticketSeat->save();
            $unUsedSeats = TicketSeat::where('ticket_id', $ticket->id)->where('is_used', false)->count();
            if($unUsedSeats == 0) {
                $ticket->is_used = true;
                $ticket->used_at = date('Y-m-d H:i:s');
                $ticket->save();
            }

            $success = 'Ticket has been scanned successfully';
            return view('frontend.ticket-scan', compact('success', 'ticket', 'ticketSeat'));
        } else {
            $error = 'This ticket is not valid';
            return view('frontend.ticket-scan', compact('error'));
        }
    }


    public function sendWhatsappMsgForUpdatePassword($user, $password){

        $msg = WhatsAppFormatter::compose(
            '🔑',
            'MOT DE PASSE MIS À JOUR',
            'PASSWORD UPDATED',
            $user->name ?? 'User',
            'Votre mot de passe a été mis à jour.',
            'Your password has been updated.',
            [
                ['Nom d\'utilisateur', 'Username', $user->name ?? '—'],
                ['Téléphone', 'Phone', $user->phone ?? '—'],
                ['Nouveau mot de passe', 'New password', (string) $password],
            ],
            'Connectez-vous avec vos nouveaux identifiants.',
            'Sign in with your new credentials.'
        );

        try{
            $this->wpMessage($user->whatsapp_number ?? $user->phone, $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }

    public function forgotPassword()
    {
        return view('frontend.forgot-password');
    }

    public function forgotPasswordStore(Request $request)
    {
        $user = User::where('phone', $request->phone)->where('is_active', true)->first();
        if ($user) {
            $otp = $this->sendOTP($user);
            Session::put('otp', $otp);
            Session::put('user', $user);
            return view('frontend.otp_screen_forgot_password');
        }

        return back()->with('not_permitted', 'Your phone number is incorrect...!');
    }

    public function forgotPasswordCheck(Request $request)
    {

        if ($request->otp == Session::get('otp')) {
            Session::forget('otp');
            return view('frontend.password_change');
        }
        return back()->with('not_permitted', 'OTP is incorrect...!');
    }

    public function forgotPasswordCheckStore(Request $request) {
        $data = $request->all();
        $password = $data['password'];

        if($data['password'] != $data['confirm_password']) {
            $not_permitted = 'Password and confirm password does not match';
            return view('frontend.password_change', compact('not_permitted'));
        }

        $user = Session::get('user');
        User::where('id', $user->id)->update([
            'password' => bcrypt($password)
        ]);
        Session::forget('user');

        $msg = '*Dear* :'. $user->name .' \n\n';
        $msg .= '*Your new password is:* '. $password . '\n\n';

        try{
            $this->wpMessage($user->phone, $msg);
        }
        catch(\Exception $e){
        }

        return redirect()->route('user.login')->with('message', 'Congratulaton: Your password has been updated');
    }

    /* ------------------------------------------------------------------
     | Admin / staff password reset via WhatsApp OTP
     | ----------------------------------------------------------------- */
    public function adminForgotPassword()
    {
        return view("auth.forgot-password");
    }

    public function adminForgotPasswordStore(Request $request)
    {
        $request->validate(['phone' => 'required']);

        $user = User::where("phone", $request->phone)
            ->where("is_active", true)
            ->first();

        if (!$user) {
            return back()->with("not_permitted", "No active account found with that WhatsApp number.");
        }

        $otp = $this->sendOTP($user);
        Session::put("reset_otp", $otp);
        Session::put("reset_user_id", $user->id);

        return redirect()->route('admin.password.verify')
            ->with("message", "An OTP has been sent to your WhatsApp number.");
    }

    public function adminForgotPasswordVerifyForm()
    {
        if (!Session::has("reset_user_id")) {
            return redirect()->route('admin.password.request');
        }
        return view("auth.verify-otp");
    }

    public function adminForgotPasswordVerify(Request $request)
    {
        $request->validate(['otp' => 'required']);

        if ($request->otp == Session::get("reset_otp")) {
            Session::put("reset_otp_verified", true);
            return redirect()->route('admin.password.reset.form');
        }

        return back()->with("not_permitted", "The OTP you entered is incorrect or has expired.");
    }

    public function adminForgotPasswordResetForm()
    {
        if (!Session::get("reset_otp_verified")) {
            return redirect()->route('admin.password.request');
        }
        return view("auth.reset-password");
    }

    public function adminForgotPasswordReset(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6',
            'confirm_password' => 'required',
        ]);

        if (!Session::get("reset_otp_verified") || !Session::has("reset_user_id")) {
            return redirect()->route('admin.password.request')
                ->with("not_permitted", "Your reset session expired. Please try again.");
        }

        if ($request->password !== $request->confirm_password) {
            return back()->with("not_permitted", "Password and confirm password do not match.");
        }

        $user = User::find(Session::get("reset_user_id"));
        if (!$user) {
            return redirect()->route('admin.password.request')
                ->with("not_permitted", "Account not found.");
        }

        $user->update(["password" => bcrypt($request->password)]);

        Session::forget(["reset_otp", "reset_user_id", "reset_otp_verified"]);

        $msg = "*Dear* " . $user->name . " \n\n";
        $msg .= "Your dashboard password has just been reset successfully. \n\n";
        $msg .= "If this wasn't you, please contact the administrator immediately. \n\n";
        $msg .= request()->getHost();
        try {
            $this->wpMessage($user->phone, $msg);
        } catch (\Exception $e) {
        }

        return redirect()->route("login")->with("message", "Your password has been reset. Please sign in.");
    }

    private function resolveVoterName(Request $request, $phone)
    {
        $name = trim((string) $request->input('voter_name', ''));
        if ($name !== '') {
            return $name;
        }

        $authUser = Auth::user();
        if ($authUser && !PhoneHelper::looksLikePhone($authUser->name)) {
            return $authUser->name;
        }

        $existing = User::where('phone', $phone)->first();
        if ($existing && !PhoneHelper::looksLikePhone($existing->name)) {
            return $existing->name;
        }

        return null;
    }

    private function findOrCreateVoterUser($phone, $whatsapp, $voterName)
    {
        $user = User::where('phone', $phone)->first();

        if ($user) {
            $dirty = false;
            if ($user->whatsapp_number !== $whatsapp) {
                $user->whatsapp_number = $whatsapp;
                $dirty = true;
            }
            if (PhoneHelper::looksLikePhone($user->name) && $voterName) {
                $user->name = $voterName;
                $dirty = true;
            }
            if ($dirty) {
                $user->save();
            }

            return $user;
        }

        $password = rand(1, 999999);
        $user = User::create([
            'is_active' => true,
            'is_deleted' => false,
            'password' => bcrypt($password),
            'name' => $voterName,
            'phone' => $phone,
            'whatsapp_number' => $whatsapp,
            'email' => 'user@gmail.com',
            'role_id' => 3,
        ]);
        $this->sendWhatsappMsg($user, $password);

        return $user;
    }

}


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
        $winners = AboutWinner::where('year', $winnersYear)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('frontend.about', compact('team', 'winners', 'winnersYear'));
    }

    public function gallery()
    {
        $images = \App\Helpers\SiteContent::galleryItems();

        return view('frontend.gallery', compact('images'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

     public function contactMessage(Request $request){
        // Honeypot: bots fill hidden fields humans never see. Silently drop.
        if ($request->filled('website') || $request->filled('company_url')) {
            return back()->with('message', trans('file.Your message has been sent'));
        }

        $request->validate([
            'name' => 'required|string|max:120',
            'number' => 'required|string|max:40',
            'email' => 'nullable|email|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Spam heuristics: block link-laden / bot promotional submissions.
        if ($this->looksLikeSpam($request->name, $request->message, $request->number)) {
            \Log::info('Blocked spam contact submission', [
                'ip' => $request->ip(),
                'name' => $request->name,
            ]);
            // Pretend success so bots don't learn they were blocked.
            return back()->with('message', trans('file.Your message has been sent'));
        }

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

    /**
     * Lightweight spam detection for the public contact form.
     * The abuse we see is bot-generated promotional text stuffed with links.
     */
    private function looksLikeSpam($name, $message, $number)
    {
        $name = (string) $name;
        $message = (string) $message;
        $haystack = strtolower($name . ' ' . $message);

        // Any URL / link markup is the strongest signal for this form,
        // which is meant for short "get in touch" notes, not links.
        if (preg_match('~(https?://|www\.|\bt\.me/|\[url|\bhref=|</?a\b)~i', $haystack)) {
            return true;
        }

        // Multiple domain-like tokens (e.g. site.com, mail.ru) indicate spam.
        if (preg_match_all('~[a-z0-9-]+\.(com|net|ru|org|info|xyz|top|online|site|shop|biz)\b~i', $haystack) >= 1) {
            return true;
        }

        // Common spam keywords across the messages we received.
        $keywords = ['seo', 'backlink', 'crypto', 'casino', 'viagra', 'loan', 'bitcoin', 'traffic to your', 'rank your', 'guest post'];
        foreach ($keywords as $kw) {
            if (strpos($haystack, $kw) !== false) {
                return true;
            }
        }

        // Phone must contain digits; bots sometimes stuff text here.
        if (!preg_match('~\d~', (string) $number)) {
            return true;
        }

        return false;
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
        $judges = Judge::where('is_active', true)
            ->whereNotIn('name', Ambassador::pluck('name'))
            ->where('name', 'not like', 'Ambassador %')
            ->orderBy('sort_order')->orderBy('id')->get();
        $ambassadors = Ambassador::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        $partners = \App\Partner::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

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

        return view('frontend.home', compact('musicians', 'judges', 'best_musician', 'see_votes', 'ambassadors', 'partners', 'best_musicians', 'best_musician_data', 'vote_counts', 'weekly_top'));
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
        $musician = Employee::where('is_active', true)->where('is_approve', true)->findOrFail($id);
        $vote_count = vote::where('status', true)->where('musician_id', $id)->sum('vote');
        $images = Gallery::where('employee_id', $id)->where('type', 'image')->get();
        $audios = Gallery::where('employee_id', $id)->where('type', 'audio')->get();
        $videos = Gallery::where('employee_id', $id)->where('type', 'video')->get();
        $socialLinks = Gallery::where('employee_id', $id)
            ->whereIn('type', \App\Helpers\SocialEmbed::linkTypes())
            ->get();
        $contentants = Employee::where('is_active', true)->where('is_approve', true)->get();
        $teamData = $this->contestantTeamPageData($contentants);

        return view('frontend.employee', array_merge(
            compact('musician', 'contentants', 'images', 'audios', 'videos', 'socialLinks', 'vote_count'),
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
        $search = substr((string) $request->input('search', ''), 0, 100);
        $musicians = Employee::where('name', 'LIKE', '%' . $search . '%')
            ->where('is_active', true)
            ->where('is_approve', true)
            ->get();

        return view('frontend.team', $this->contestantTeamPageData($musicians));
    }

    public function employeeVote(Request $request) {
        $request->validate([
            'musician_id' => 'required|integer',
            'vote' => 'required|integer|min:1|max:1000',
        ]);

        $musician = Employee::where('is_active', true)->where('is_approve', true)
            ->findOrFail($request->musician_id);

        $data = $request->all();
        $data['vote'] = (int) $request->vote;

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
        $whatsapp = PhoneHelper::e164($request->input('whatsapp_intl'))
            ?? PhoneHelper::fromLocalDigits($request->input('whatsapp_local'))
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

        $voteCount = $this->validatedVoteCount($request);
        $general_setting = GeneralSetting::pluck('vote_price')->first();
        $amount = $general_setting * $voteCount;
        $vote = vote::create([
                    'user_id' => $user->id,
                    'musician_id' => $request->musician_id,
                    'vote' => $voteCount,
                    'status' => false,
                    'reference' => 'pending',
                    'price' => $general_setting,
                    'grand_total' => $amount,
                    'whatsapp_number' => $whatsapp,
                    'locale' => WhatsAppFormatter::currentLocale(),
                ]);

        if (PhoneHelper::paymentSimulate() && $vote) {
            $vote->status = true;
            $vote->reference = 'SIM-' . $vote->id;
            $vote->save();
            $this->sendWhatsappMsgVoteMomoSuccess($user, $vote->vote, $vote->musician_id, $vote);

            return redirect()->route('home')->with('message', trans('file.Thank you for your voting'));
        }

        try {
            $voteMobileMoneyService = app(\App\Services\Payments\VoteMobileMoneyService::class);
            $initiation = $voteMobileMoneyService->initiate(
                $vote,
                $phone,
                $request->input('payment_method', 'momo')
            );

            if (!empty($initiation['result']) && !$initiation['result']->success) {
                $this->markVoteFailed($vote->id);
                return redirect()->route('home')->with('not_permitted', $initiation['result']->message);
            }

            // Extra safety: watcher is also started inside VoteMobileMoneyService::initiate.
            $voteMobileMoneyService->dispatchVoteWatcher($vote->id);

            $this->sendWhatsappMsgVoteMomo(
                $user,
                $vote->vote,
                $vote->musician_id,
                $whatsapp,
                $amount,
                $vote,
                $request->input('payment_method', 'momo')
            );

            return redirect()->route('musician.vote.payment.pending', $vote->id);
        } catch (\App\Services\Payments\MobileMoneyGatewayConfigurationException $e) {
            \Log::error('Mobile money configuration error: ' . $e->getMessage());
            $this->markVoteFailed($vote->id);
            return redirect()->route('home')->with(
                'not_permitted',
                trans('file.Mobile Money payment is temporarily unavailable. Please try again later or contact support.')
            );
        } catch (\Throwable $e) {
            \Log::error('Vote mobile money initiation failed: ' . $e->getMessage(), ['vote_id' => $vote->id]);
            $this->markVoteFailed($vote->id);
            return redirect()->route('home')->with('not_permitted', $this->momoFailureMessage());
        }
    }

    public function musicianVotePaymentStripe(Request $request) {

        $phone = PhoneHelper::fromLocalDigits($request->input('phone_local'))
            ?? PhoneHelper::e164($request->input('whatsapp_intl'))
            ?? PhoneHelper::fromLocalDigits($request->input('whatsapp_local'))
            ?? PhoneHelper::cameroon($request->phone);
        $whatsapp = PhoneHelper::e164($request->input('whatsapp_intl'))
            ?? PhoneHelper::fromLocalDigits($request->input('whatsapp_local'))
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

        $voteCount = $this->validatedVoteCount($request);
        $general_setting = GeneralSetting::pluck('vote_price')->first();
        $amount = $general_setting * $voteCount;
        $vote = vote::create([
            'user_id' => $user->id,
            'musician_id' => $request->musician_id,
            'vote' => $voteCount,
            'status' => false,
            'reference' => 'abc',
            'price' => $general_setting,
            'grand_total' => $amount,
            'whatsapp_number' => $whatsapp,
            'locale' => WhatsAppFormatter::currentLocale(),
        ]);

        if($vote) {
            $route = route('musician.vote.payment.check.stripe');
            $link = $this->createCheckoutSession($amount, $route, $vote->id, $vote);
            if ($link == false) {
                $this->markVoteFailed($vote->id);
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

        Stripe::setApiKey(config('services.stripe.secret') ?: env('STRIPE_SECRET'));

        try {
            $session = \Stripe\Checkout\Session::retrieve($request->session_id);
        } catch (\Throwable $e) {
            // We could not confirm with Stripe — do NOT delete; leave pending for review.
            \Log::error('Stripe vote check failed: ' . $e->getMessage(), ['session' => $request->session_id]);
            return redirect()->route('home')->with('not_permitted', trans('file.Payment failed please try again'));
        }

        $voteId = $session->metadata->vote_id ?? null;
        $vote = $voteId ? vote::find($voteId) : null;
        if (!$vote) {
            return redirect()->route('home')->with('not_permitted', trans('file.Payment record not found'));
        }

        if ($session->payment_status !== 'paid') {
            $this->markVoteFailed($vote->id);
            return redirect()->route('home')->with('not_permitted', trans('file.Payment failed please try again'));
        }

        $this->markVoteSuccessful($vote->id, $session->payment_intent);

        return redirect()->route('home')->with('message', trans('file.Thank you for your voting'));
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
            'locale' => WhatsAppFormatter::currentLocale(),
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

        Stripe::setApiKey(config('services.stripe.secret') ?: env('STRIPE_SECRET'));

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
        $data['whatsapp_number'] = PhoneHelper::e164($request->input('whatsapp_intl'))
            ?? PhoneHelper::fromLocalDigits($request->input('whatsapp_local'))
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
                    'locale' => WhatsAppFormatter::currentLocale(),
                    'selected_seat_ids' => is_array($seatSelection) ? json_encode($seatSelection['ids']) : null,
                ]);

        if (is_array($seatSelection)) {
            $this->reserveTicketSeats($ticket, $seatSelection['ids']);
        }
        $token = PhoneHelper::momoToken();
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

    /** Clamp vote quantity from user input to a safe server-side range. */
    private function validatedVoteCount(Request $request, int $max = 1000): int
    {
        return max(1, min($max, (int) $request->input('vote', 1)));
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
        if ((int) $vote->status === self::VOTE_SUCCESS) {
            return redirect()->route('home')->with('message', 'Thank you for your voting');
        }
        $musician = Employee::findOrFail($vote->musician_id);
        $paymentMethod = 'momo';
        $payment = \App\MobileMoneyPayment::where('payable_type', vote::class)
            ->where('payable_id', $vote->id)
            ->orderByDesc('id')
            ->first();
        if ($payment && $payment->request_payload) {
            $payload = json_decode($payment->request_payload, true);
            if (!empty($payload['payment_method'])) {
                $paymentMethod = $payload['payment_method'];
            }
        } elseif ($payment && $payment->mobile_network && stripos($payment->mobile_network, 'ORANGE') !== false) {
            $paymentMethod = 'om';
        }
        $lastAttemptAt = $payment && $payment->created_at ? $payment->created_at : $vote->created_at;
        $retryAfterSeconds = 240;
        $secondsUntilRetry = max(0, $retryAfterSeconds - now()->diffInSeconds($lastAttemptAt));
        $canRetry = (int) $vote->status === self::VOTE_PENDING && $secondsUntilRetry === 0;
        $pendingUrl = route('musician.vote.payment.pending', $vote->id);

        return view('frontend.payment-pending', compact(
            'vote',
            'musician',
            'paymentMethod',
            'lastAttemptAt',
            'retryAfterSeconds',
            'secondsUntilRetry',
            'canRetry',
            'pendingUrl'
        ));
    }

    public function musicianVotePaymentRetry($id)
    {
        $vote = vote::findOrFail($id);
        if ((int) $vote->status === self::VOTE_SUCCESS) {
            return redirect()->route('home')->with('message', trans('file.Thank you for your voting'));
        }
        if ((int) $vote->status === self::VOTE_FAILED) {
            return redirect()->route('musician.data', $vote->musician_id)
                ->with('not_permitted', trans('file.This vote can no longer be retried Please start a new vote'));
        }

        try {
            $result = app(\App\Services\Payments\VoteMobileMoneyService::class)->reinitiate($vote, 240);
            if (empty($result['ok'])) {
                $msg = $result['message'] ?? trans('file.We could not restart the Mobile Money payment');
                if (($result['code'] ?? '') === 'too_soon' && !empty($result['wait_seconds'])) {
                    $mins = max(1, (int) ceil($result['wait_seconds'] / 60));
                    $msg = trans('file.Please wait minutes before retrying', ['minutes' => $mins]);
                }
                return redirect()->route('musician.vote.payment.pending', $vote->id)->with('not_permitted', $msg);
            }

            $user = User::find($vote->user_id);
            if ($user) {
                $this->sendWhatsappMsgVoteMomo(
                    $user,
                    $vote->vote,
                    $vote->musician_id,
                    $vote->whatsapp_number,
                    $vote->grand_total,
                    $vote,
                    $result['payment_method'] ?? 'momo'
                );
            }

            return redirect()->route('musician.vote.payment.pending', $vote->id)
                ->with('message', trans('file.New payment prompt sent Approve on your phone'));
        } catch (\Throwable $e) {
            \Log::error('Vote payment retry failed: ' . $e->getMessage(), ['vote_id' => $vote->id]);
            return redirect()->route('musician.vote.payment.pending', $vote->id)
                ->with('not_permitted', trans('file.We could not restart the Mobile Money payment'));
        }
    }

    public function musicianVotePaymentPoll(Request $request)
    {
        $vote = vote::find($request->vote_id);
        if (!$vote) {
            return response()->json(['status' => 'UNKNOWN']);
        }
        if ((int) $vote->status === self::VOTE_SUCCESS) {
            return response()->json(['status' => 'SUCCESSFUL']);
        }
        if ((int) $vote->status === self::VOTE_FAILED) {
            return response()->json(['status' => 'FAILED']);
        }

        try {
            $voteMobileMoneyService = app(\App\Services\Payments\VoteMobileMoneyService::class);
            $status = $voteMobileMoneyService->refreshVoteStatus($vote);

            if ($status === 'SUCCESSFUL') {
                $this->markVoteSuccessful($vote->id, $vote->reference);

                return response()->json(['status' => 'SUCCESSFUL']);
            }
            if ($status === 'FAILED') {
                $this->markVoteFailed($vote->id);

                return response()->json(['status' => 'FAILED']);
            }
        } catch (\Throwable $e) {
            \Log::warning('Vote payment poll failed: ' . $e->getMessage(), ['vote_id' => $vote->id]);
        }

        return response()->json(['status' => 'PENDING']);
    }

    /**
     * AJAX: return the account holder name registered on a mobile money number
     * so the payment form can auto-fill the voter name. Never throws to the UI.
     */
    public function musicianVotePaymentHolder(Request $request)
    {
        $phone = PhoneHelper::fromLocalDigits($request->input('phone'))
            ?? PhoneHelper::cameroon($request->input('phone'));
        if (!$phone) {
            return response()->json(['name' => null]);
        }

        try {
            $voteMobileMoneyService = app(\App\Services\Payments\VoteMobileMoneyService::class);
            $name = $voteMobileMoneyService->getHolderName(ltrim($phone, '+'));
        } catch (\Throwable $e) {
            \Log::warning('Holder info lookup failed: ' . $e->getMessage());
            $name = null;
        }

        if ($name && PhoneHelper::looksLikePhone($name)) {
            $name = null;
        }

        return response()->json(['name' => $name]);
    }

    public function musicianVotePaymentCheck(Request $request)
    {
        $vote = vote::where('id', $request->external_reference)->first();
        if (!$vote) {
            return redirect()->route('home')->with('not_permitted', trans('file.Payment record not found'));
        }
        if ((int) $vote->status === self::VOTE_SUCCESS) {
            return redirect()->route('home')->with('message', trans('file.Thank you for your voting'));
        }

        try {
            $voteMobileMoneyService = app(\App\Services\Payments\VoteMobileMoneyService::class);
            $status = $voteMobileMoneyService->refreshVoteStatus($vote);

            if ($status === 'SUCCESSFUL') {
                $this->markVoteSuccessful($vote->id, $request->reference ?: $vote->reference);
                return redirect()->route('home')->with('message', trans('file.Thank you for your voting'));
            }
            if ($status === 'FAILED') {
                $this->markVoteFailed($vote->id);
                return redirect()->route('home')->with('not_permitted', trans('file.Payment failed please try again'));
            }
        } catch (\Throwable $e) {
            \Log::warning('Vote payment check failed: ' . $e->getMessage(), ['vote_id' => $vote->id]);
        }

        return redirect()->route('musician.vote.payment.pending', $vote->id);
    }

    public function handleCampayWebhook(Request $request)
    {
        $data = $request->all();

        Log::info('Campay Webhook Received', $data);

        $gateway = new \App\Services\Payments\Gateways\CampayGateway();
        if (!$gateway->validateWebhookSecret($request->headers->all(), $data)) {
            Log::warning('Campay webhook rejected: invalid secret');
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $voteMobileMoneyService = app(\App\Services\Payments\VoteMobileMoneyService::class);
        $result = $voteMobileMoneyService->handleProviderCallback('campay', $data, $request->headers->all());

        if ($result && !empty($result['vote_id'])) {
            if (($data['status'] ?? '') === 'SUCCESSFUL' || ($result['status'] ?? null) === 'completed') {
                $reference = $data['reference']
                    ?? $data['operator_reference']
                    ?? $result['reference']
                    ?? 'from_webhook';
                if ($this->markVoteSuccessful($result['vote_id'], $reference)) {
                    Log::info('Campay Webhook Completed', $data);
                }
            } elseif (($data['status'] ?? '') === 'FAILED' || ($result['status'] ?? null) === 'failed') {
                $this->markVoteFailed($result['vote_id']);
            }
        }

        return response()->json(['status' => 'ok'], 200);
    }

    /**
     * Vote payment status codes stored in votes.status:
     *   0 = pending (awaiting confirmation)   1 = successful / counted   2 = failed
     * Votes are NEVER deleted once a payment prompt has been sent, so a debited
     * customer can always be reconciled and counted even after a dropped connection.
     */
    const VOTE_PENDING = 0;
    const VOTE_SUCCESS = 1;
    const VOTE_FAILED = 2;

    /** Details of the most recent failed Campay collect (for accurate messaging). */
    private $lastMomoError = null;

    /**
     * Atomically mark a vote as paid/counted. Row-locked and idempotent so the
     * poller, the Campay webhook and the reconciliation cron can never
     * double-count or double-notify. Returns the vote only when it was newly
     * confirmed by this call (so the WhatsApp confirmation is sent exactly once).
     */
    public function markVoteSuccessfulPublic($voteId, $reference = null)
    {
        return $this->markVoteSuccessful($voteId, $reference);
    }

    public function markVoteFailedPublic($voteId)
    {
        return $this->markVoteFailed($voteId);
    }

    private function markVoteSuccessful($voteId, $reference = null)
    {
        try {
            $vote = \DB::transaction(function () use ($voteId, $reference) {
                $v = vote::lockForUpdate()->find($voteId);
                if (!$v || (int) $v->status === self::VOTE_SUCCESS) {
                    return null;
                }
                $v->status = self::VOTE_SUCCESS;
                if (!empty($reference)) {
                    $v->reference = $reference;
                }
                $v->save();
                return $v;
            });
        } catch (\Throwable $e) {
            \Log::error('markVoteSuccessful failed: ' . $e->getMessage(), ['vote_id' => $voteId]);
            return null;
        }

        if ($vote) {
            // Use the account holder name registered on the paying MoMo number as
            // the authoritative voter name once the payment is confirmed.
            try {
                $this->applyHolderNameToVote($vote);
            } catch (\Throwable $e) {
                \Log::warning('Applying holder name failed: ' . $e->getMessage(), ['vote_id' => $voteId]);
            }

            try {
                $this->sendWhatsappMsgVoteMomoSuccess($vote->voters, $vote->vote, $vote->musician_id, $vote);
            } catch (\Throwable $e) {
                \Log::error('Vote success WhatsApp failed: ' . $e->getMessage(), ['vote_id' => $voteId]);
            }
        }

        return $vote;
    }

    /**
     * After a confirmed payment, look up the MoMo account holder name for the
     * paying number and store it as the voter's name (Campay is authoritative).
     */
    private function applyHolderNameToVote($vote)
    {
        $user = $vote->voters;
        if (!$user || empty($user->phone)) {
            return;
        }

        $token = PhoneHelper::momoToken();
        if (!$token) {
            return;
        }

        $holderName = $this->mobileMoneyHolderInfo($token, ltrim($user->phone, '+'));
        if (!$holderName || PhoneHelper::looksLikePhone($holderName)) {
            return;
        }

        if (trim((string) $user->name) !== $holderName) {
            $user->name = $holderName;
            $user->save();
        }
    }

    /** Mark a vote as failed — but never override an already-counted (paid) vote. */
    private function markVoteFailed($voteId)
    {
        try {
            $vote = vote::find($voteId);
            if ($vote && (int) $vote->status !== self::VOTE_SUCCESS) {
                $vote->status = self::VOTE_FAILED;
                $vote->save();
            }
            return $vote;
        } catch (\Throwable $e) {
            \Log::error('markVoteFailed failed: ' . $e->getMessage(), ['vote_id' => $voteId]);
            return null;
        }
    }

    /**
     * Safety net for dropped connections / missed webhooks: re-query Campay for
     * every still-pending vote that already has a real transaction reference and
     * settle it against Campay's authoritative status. Run on a schedule.
     */
    public function reconcilePendingVotes($days = 3, $phone = null)
    {
        $voteMobileMoneyService = app(\App\Services\Payments\VoteMobileMoneyService::class);
        $providerStats = $voteMobileMoneyService->reconcilePendingPayments($days);

        $since = \Carbon\Carbon::now()->subDays((int) $days);
        $query = vote::where('status', self::VOTE_PENDING)
            ->where('created_at', '>=', $since)
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->whereNotNull('reference')
                        ->whereNotIn('reference', ['pending', 'abc', ''])
                        ->where('reference', 'not like', 'SIM-%');
                })->orWhereNotNull('mobile_money_payment_id');
            });

        if ($phone) {
            $digits = preg_replace('/\D+/', '', (string) $phone);
            $local = ltrim(preg_replace('/^237/', '', $digits), '0');
            $query->where(function ($q) use ($digits, $local) {
                $q->where('whatsapp_number', 'like', '%' . $local . '%');
                if ($digits !== '') {
                    $q->orWhere('whatsapp_number', 'like', '%' . $digits . '%');
                }
                $q->orWhereHas('voters', function ($uq) use ($digits, $local) {
                    $uq->where('phone', 'like', '%' . $local . '%')
                        ->orWhere('whatsapp_number', 'like', '%' . $local . '%');
                    if ($digits !== '') {
                        $uq->orWhere('phone', 'like', '%' . $digits . '%')
                            ->orWhere('whatsapp_number', 'like', '%' . $digits . '%');
                    }
                });
            });
        }

        $pending = $query->orderBy('id')->get();

        $confirmed = 0;
        $failed = 0;
        foreach ($pending as $vote) {
            try {
                $status = $voteMobileMoneyService->refreshVoteStatus($vote);
            } catch (\Throwable $e) {
                \Log::warning('Vote reconcile status check failed: ' . $e->getMessage(), ['vote_id' => $vote->id]);
                continue;
            }
            if ($status === 'SUCCESSFUL') {
                if ($this->markVoteSuccessful($vote->id, $vote->reference)) {
                    $confirmed++;
                    \Log::info('Vote reconciled to SUCCESSFUL', ['vote_id' => $vote->id, 'reference' => $vote->reference]);
                }
            } elseif ($status === 'FAILED') {
                $this->markVoteFailed($vote->id);
                $failed++;
            }
        }

        return [
            'checked' => $pending->count() + ($providerStats['checked'] ?? 0),
            'confirmed' => $confirmed + ($providerStats['confirmed'] ?? 0),
            'failed' => $failed + ($providerStats['failed'] ?? 0),
        ];
    }

    /**
     * Hostinger-friendly HTTP cron: hit every minute with ?token=CRON_SECRET
     * so pending MoMo votes are settled without needing crontab CLI.
     */
    public function cronReconcileVotes(Request $request)
    {
        $secret = (string) env('CRON_SECRET', '');
        $token = (string) $request->query('token', '');
        if ($secret === '' || !hash_equals($secret, $token)) {
            return response()->json(['ok' => false, 'message' => 'unauthorized'], 401);
        }

        @set_time_limit(0);
        $days = max(1, (int) $request->query('days', 14));
        $phone = $request->query('phone');
        $result = $this->reconcilePendingVotes($days, $phone);

        @file_put_contents(storage_path('app/cron-reconcile.heartbeat'), json_encode([
            'at' => date('Y-m-d H:i:s'),
            'result' => $result,
        ]) . PHP_EOL, FILE_APPEND);

        return response()->json(['ok' => true] + $result);
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
                'whatsapp_number' => $user->whatsapp_number ?? $user->phone,
                'locale' => WhatsAppFormatter::currentLocale(),
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

        return redirect()->route('home');
    }

    public function otpCheckStore(Request $request) {
        $user = Auth::user();

        if (!$user->otp || !$user->otp_time || $user->otp_time <= date('Y-m-d H:i:s', strtotime('-3 minutes'))) {
            return redirect()->back()->with('not_permitted', trans('file.Invalid OTP'));
        }

        if (hash_equals((string) $user->otp, (string) $request->otp)) {
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
        $msg = WhatsAppFormatter::otpMessage(
            $user->name ?? 'User',
            $otp,
            3,
            WhatsAppFormatter::currentLocale()
        );

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
        if (!config('app.debug')) {
            abort(404);
        }

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
        $curlError = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $response_decode = json_decode($response, true);

        curl_close($curl);

        if($response_decode && isset($response_decode['reference'])) {
            $this->lastMomoError = null;
            return $response_decode['reference'];
        }

        // Remember the real reason so the caller can show an accurate message
        // instead of always blaming the phone number.
        $this->lastMomoError = [
            'http_code' => $httpCode,
            'message' => is_array($response_decode) ? ($response_decode['message'] ?? '') : '',
        ];

        \Log::warning('Campay collect failed', [
            'http_code' => $httpCode,
            'curl_error' => $curlError,
            'from' => (string) $number,
            'amount' => (string) $amount,
            'response' => $response,
        ]);

        return false;
    }

    /**
     * Build a voter-facing message from the last MoMo collect failure. Only a
     * genuinely malformed number is blamed on the phone; account/service issues
     * (e.g. Campay "Unauthorized. Inactive") get a neutral "try again" message.
     */
    private function momoFailureMessage()
    {
        $http = $this->lastMomoError['http_code'] ?? null;
        $msg = strtolower((string) ($this->lastMomoError['message'] ?? ''));

        if (strpos($msg, 'inactive') !== false || strpos($msg, 'unauthorized') !== false || (int) $http === 401 || (int) $http === 403) {
            return trans('file.Mobile Money payment is temporarily unavailable. Please try again later or contact support.');
        }

        return trans('file.We could not start the Mobile Money payment. Please check your number and try again.');
    }

    /**
     * Look up the registered account holder name for a mobile money number via
     * Campay's holder_info endpoint. Returns the full name string or null.
     */
    public function mobileMoneyHolderInfo($token, $number)
    {
        if (!$token) {
            return null;
        }
        $number = preg_replace('/\D/', '', (string) $number);
        if ($number === '') {
            return null;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.campay.net/api/holder_info/?phone_number=' . $number,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Token ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $decoded = json_decode($response, true);
        if (is_array($decoded) && !empty($decoded['full_name'])) {
            return trim($decoded['full_name']);
        }

        return null;
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
//        $token = PhoneHelper::momoToken();
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
            'Welcome aboard!',
            WhatsAppFormatter::currentLocale()
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
        $locale = WhatsAppFormatter::currentLocale();

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
            'Keep supporting your favourite contestant!',
            $locale
        );

        try{
            $this->wpMessage($user->phone, $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }


    public function sendWhatsappMsgVoteMomo($user, $vote, $musician_id, $whatsapp = null, $amount = null, $voteModel = null, $paymentMethod = 'momo')
    {
        $musician = Employee::select('name', 'id')->find($musician_id);
        $total_votes = vote::where('musician_id', $musician_id)->where('status', true)->sum('vote');
        $recipient = $whatsapp ?? $user->whatsapp_number ?? $user->phone;
        $amountLine = $amount ? number_format((float) $amount) . ' CFA' : null;
        $pendingUrl = $voteModel ? route('musician.vote.payment.pending', $voteModel->id) : null;
        $isOrange = strtolower((string) $paymentMethod) === 'om';
        $ussdCode = $isOrange ? '#150*47#' : '*126#';
        $networkLabel = $isOrange ? 'Orange Money' : 'MTN Mobile Money';
        $locale = WhatsAppFormatter::normalizeLocale(optional($voteModel)->locale)
            ?: WhatsAppFormatter::currentLocale();
        $ussdValue = $locale === 'fr'
            ? "Composez {$ussdCode}"
            : "Dial {$ussdCode}";
        $statusValue = $locale === 'fr' ? 'En attente' : 'Pending';
        $lines = [
            ['Candidat', 'Contestant', $musician->name ?? '—'],
            ['Votes', 'Votes', (string) $vote],
            ['Total actuel', 'Current total', (string) $total_votes],
            ['Réseau', 'Network', $networkLabel],
            ['Statut', 'Status', $statusValue],
            ['Code USSD', 'USSD code', $ussdValue],
        ];
        if ($amountLine) {
            array_splice($lines, 2, 0, [['Montant', 'Amount', $amountLine]]);
        }
        if ($pendingUrl) {
            $lines[] = ['Lien', 'Link', $pendingUrl];
        }

        $msg = WhatsAppFormatter::compose(
            '💳',
            'PAIEMENT DE VOTE EN ATTENTE',
            'VOTE PAYMENT PENDING',
            $user->name ?? 'Voter',
            "Validez sur votre téléphone avec {$networkLabel}. Composez {$ussdCode} pour approuver. Si rien n’arrive, rouvrez le lien après 4 minutes pour renvoyer la demande.",
            "Approve on your phone with {$networkLabel}. Dial {$ussdCode} to approve. If no prompt, reopen the link after 4 minutes to resend.",
            $lines,
            'Ne fermez pas avant confirmation.',
            'Do not close until payment is confirmed.',
            $locale
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

        $locale = WhatsAppFormatter::normalizeLocale(optional($ticket)->locale)
            ?: WhatsAppFormatter::currentLocale();

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
                'Present your QR code at the entrance.',
                $locale
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


        $locale = WhatsAppFormatter::normalizeLocale(optional($vote_data)->locale)
            ?: WhatsAppFormatter::currentLocale();
        $statusValue = $locale === 'fr' ? 'Confirmé ✓' : 'Confirmed ✓';

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
                ['Statut', 'Status', $statusValue],
            ],
            'Chaque vote compte — merci pour votre soutien !',
            'Every vote counts — thank you for your support!',
            $locale
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
                $locale = WhatsAppFormatter::normalizeLocale(optional($ticket)->locale)
                    ?: WhatsAppFormatter::currentLocale();
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
                    ],
                    '',
                    '',
                    $locale
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
            'Sign in with your new credentials.',
            WhatsAppFormatter::currentLocale()
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
            if (!$this->sendOTP($user)) {
                return back()->with('not_permitted', trans('file.OTP delivery failed'));
            }
            $user->refresh();
            Session::put('otp', $user->otp);
            Session::put('user', $user);
            return view('frontend.otp_screen_forgot_password');
        }

        return back()->with('not_permitted', 'Your phone number is incorrect...!');
    }

    public function forgotPasswordCheck(Request $request)
    {

        $sessionOtp = (string) Session::get('otp');
        if ($sessionOtp !== '' && hash_equals($sessionOtp, (string) $request->otp)) {
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

        $this->sendWhatsappMsgForUpdatePassword($user, $password);

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

        // Match the number in any stored format (raw, +237…, or last 9 local digits)
        // against both the phone and whatsapp_number columns.
        $raw = trim($request->phone);
        $e164 = \App\Helpers\PhoneHelper::forUltraMsg($raw);
        $local = ltrim(preg_replace('/^237/', '', preg_replace('/\D/', '', $raw)), '0');

        $user = User::where('is_active', true)
            ->where(function ($q) use ($raw, $e164, $local) {
                $q->where('phone', $raw)->orWhere('whatsapp_number', $raw);
                if ($e164) {
                    $q->orWhere('phone', $e164)->orWhere('whatsapp_number', $e164);
                }
                if ($local !== '') {
                    $q->orWhere('phone', 'like', '%' . $local)
                      ->orWhere('whatsapp_number', 'like', '%' . $local);
                }
            })
            ->first();

        if (!$user) {
            return back()->with("not_permitted", "No active account found with that WhatsApp number.");
        }

        $otp = $this->sendOTP($user);
        // false = delivery failed; null = within 1-min cooldown (reuse existing code).
        if ($otp === false) {
            return back()->with("not_permitted", trans('file.OTP delivery failed'));
        }
        $user->refresh();
        if (empty($user->otp)) {
            return back()->with("not_permitted", trans('file.OTP delivery failed'));
        }
        Session::put("reset_otp", $user->otp);
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

        $sessionOtp = (string) Session::get("reset_otp");
        if ($sessionOtp !== '' && hash_equals($sessionOtp, (string) $request->otp)) {
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

        $locale = WhatsAppFormatter::currentLocale();
        $msg = WhatsAppFormatter::compose(
            '🔑',
            'MOT DE PASSE RÉINITIALISÉ',
            'PASSWORD RESET',
            $user->name ?? 'User',
            'Votre mot de passe du tableau de bord a été réinitialisé avec succès.',
            'Your dashboard password has been reset successfully.',
            [
                ['Site', 'Site', (string) request()->getHost()],
            ],
            'Si ce n\'était pas vous, contactez immédiatement l\'administrateur.',
            'If this wasn\'t you, contact the administrator immediately.',
            $locale
        );
        try {
            $this->wpMessage($user->whatsapp_number ?? $user->phone, $msg);
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


<?php

namespace App\Http\Controllers;

use App\Ambassador;
use App\Category;
use App\Coin;
use App\Employee;
use App\Gallery;
use App\Judge;
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
        return view('frontend.about');
    }

    public function contact()
    {
        return view('frontend.contact');
    }

     public function contactMessage(Request $request){
        $query = $request->message;
         $msg = "*Message:* ".$query . "\n\n";
         $msg .= '*Thanks for contacting ' . getenv("APP_NAME") . '.* ' . '\n\n';
         $msg .= 'We will contact you seen. \n\n';

         try{
             $this->wpMessage($request->number, $msg);
         }
         catch(\Exception $e){

         }
         return back()->with('message', 'Your message has been delivered, We will contact you ASAP..!');
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
        if(Auth::user()) {
            $role = Auth::user()->role_id;
            if($role == 1 || $role == 2) {
                return $this->admin();
            }
        }
        $musicians = Employee::where('is_active', true)->where('is_approve', true)->get();
        $judges = Judge::where('is_active', true)->get();
        $ambassadors = Ambassador::where('is_active', true)->get();


//        $start_date = date('Y-m-d', strtotime('last monday'));
//        $end_date = date('Y-m-d');

        $best_musician = DB::table('votes')
            ->select('votes.musician_id', DB::raw('SUM(votes.vote) as total_vote'))
            ->join('employees', 'employees.id', '=', 'votes.musician_id')
//            ->whereDate('votes.created_at', '>=', $start_date)
//            ->whereDate('votes.created_at', '<=', $end_date)
            ->where('employees.is_active', true)
            ->where('employees.is_approve', true)
            ->where('votes.status', true)
            ->orderBy('total_vote', 'desc')
            ->groupBy('votes.musician_id')
            ->first();

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

        if($best_musician != null) {
            $best_musician  = Employee::find($best_musician->musician_id);
        } else {
            $best_musician = DB::table('votes')
                ->select('votes.musician_id', DB::raw('SUM(votes.vote) as total_vote'))
                ->join('employees', 'employees.id', '=', 'votes.musician_id')
                ->where('employees.is_active', true)
                ->where('votes.status', true)
                ->orderBy('total_vote', 'desc')
                ->groupBy('votes.musician_id')
                ->first();

            if($best_musician != null) {
                $best_musician  = Employee::find($best_musician->musician_id);
            }
        }

        $see_votes = false;
        $role = Role::first();
        if($role->hasPermissionTo('see-votes')) {
            $see_votes = true;
        }


        return view('frontend.home', compact('musicians', 'judges', 'best_musician', 'see_votes', 'ambassadors', 'best_musicians'));
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
        $musicians = Employee::where('is_active', true)->where('is_approve', true)->get();
        return view('frontend.team', compact('musicians'));
    }

    public function employee($id) {
        $musician = Employee::find($id);
        $images = Gallery::where('employee_id', $id)->where('type', 'image')->get();
        $audios = Gallery::where('employee_id', $id)->where('type', 'audio')->get();
        $videos = Gallery::where('employee_id', $id)->where('type', 'video')->get();
        $shorts = Gallery::where('employee_id', $id)->where('type', 'short')->get();
        $youtubes = Gallery::where('employee_id', $id)->where('type', 'link')->get();
        $contentants = Employee::where('is_active', true)->where('is_approve', true)->get();


        $see_votes = false;
        $role = Role::first();
        if($role->hasPermissionTo('see-votes')) {
            $see_votes = true;
        }

        return view('frontend.employee', compact('musician', 'contentants', 'images', 'audios', 'videos', 'shorts', 'youtubes', 'see_votes'));
    }

    public function events() {
        $events = Category::where('is_active', true)->paginate(12);
        return view('frontend.events', compact('events'));
    }

    public function tickets($id) {
        $tickets = Product::where('category_id', $id)->where('is_active', true)->paginate(12);
        return view('frontend.tickets', compact('tickets'));
    }

    public function ticket($id) {
        $ticket = Product::find($id);
        return view('frontend.ticket', compact('ticket'));
    }

    public function purchaseTicket(Request $request) {
        $data = $request->all();
        $ticket = Product::find($data['ticket_id']);
        return view('frontend.ticket-payment', compact('data', 'ticket'));
    }

    public function employeeFind(Request $request) {
        $musicians = Employee::where('name', 'LIKE', '%' . $request->search . '%')->where('is_active', true)->where('is_approve', true)->get();
        return view('frontend.team', compact('musicians'));
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

        $vote = vote::create([
                    'user_id' => $user->id,
                    'musician_id' => $request->musician_id,
                    'vote' => $request->vote,
                    'status' => false,
                    'reference' => 'abc'
                ]);
        $token = getenv("MOMO_TOKEN");
        if($token && $vote) {
            $route = route('musician.vote.payment.check');
            $mtn_number = $data['phone'];
            $amount = $request->amount;
            $link = $this->mobileMoneyRequestLink($token, $amount, $route, $vote->id, $mtn_number);
            if ($link == false) {
                $message = 'Phone Number is incorrect or There is any other issue in payment method';
                return redirect()->route('home')->with('not_permitted', $message);
            }
            header("Location: $link");
            die();
        }
        $message = 'There is any other issue in payment method, please contact the system administrator';
        return back()->with('not_permitted', $message);
    }

    public function musicianVotePaymentStripe(Request $request) {

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

        $vote = vote::create([
            'user_id' => $user->id,
            'musician_id' => $request->musician_id,
            'vote' => $request->vote,
            'status' => false,
            'reference' => 'abc'
        ]);

        if($vote) {
            $route = route('musician.vote.payment.check.stripe');
            $mtn_number = $data['phone'];
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
            return redirect()->back()->with('not_permitted', 'payment failed.');
        }

        $vote = Vote::where('id', $session->metadata->vote_id)->first();
        $vote->status = true;
        $vote->reference = $session->payment_intent;
        $vote->save();

        if($vote) {
            $this->sendWhatsappMsgVoteMomoSuccess($vote->voters, $vote->vote, $vote->musician_id);
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

        $product = Product::where('id', $request->ticket_id)->select('price')->first();
        if($product == null) {
            return 'Ticket not found';
        }
        $ticket = Ticket::create([
            'user_id' => $user->id,
            'product_id' => $request->ticket_id,
            'qty' => $request->qty,
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
            'total_amount' => $request->amount,
            'payment_method' => 1
        ]);

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
            Ticket::where('id', $session->metadata->ticket_id)->delete();
            return redirect()->back()->with('not_permitted', 'payment failed.');
        }

        $ticket = Ticket::where('id', $session->metadata->ticket_id)->first();

        if($ticket) {
            $lastSeat = Ticket::where('product_id', $ticket->product_id)
                    ->where('status', 1)
                    ->get()
                    ->pluck('seat_numbers')    // Get all seat_numbers arrays
                    ->flatten()                // Flatten to a single array of seat numbers
                    ->max();
            $lastSeat = $lastSeat ? max(json_decode($lastSeat, true)) : 0;
            $qty = (int) $ticket->qty;
            $seatNumbers = range($lastSeat + 1, $lastSeat + $qty);

            $ticket->status = true;
            $ticket->reference = $session->payment_intent;
            $ticket->seat_numbers = json_encode($seatNumbers);
            $ticket->save();

            $this->sendWhatsappMsgTicketMomoSuccess($ticket);
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
        $data['phone'] = $request->phone;
        $data['email'] = $request->email ?? 'user@gmail.com';
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
        $product = Product::where('id', $request->ticket_id)->select('price')->first();
        if($product == null) {
            return 'Ticket not found';
        }
        $ticket = Ticket::create([
                    'user_id' => $user->id,
                    'product_id' => $request->ticket_id,
                    'qty' => $request->qty,
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
                    'total_amount' => $request->amount,
                    'payment_method' => 0
                ]);
        $token = getenv("MOMO_TOKEN");
        if($token && $ticket) {
            $route = route('ticket.payment.check');
            $mtn_number = $data['phone'];
            $amount = $request->amount;
            $link = $this->mobileMoneyRequestLink($token, $amount, $route, $ticket->id, $mtn_number);
            if ($link == false) {
                $message = 'Phone Number is incorrect or There is any other issue in payment method';
                return redirect()->route('tickets')->with('not_permitted', $message);
            }
            header("Location: $link");
            die();
        }
        $message = 'There is any other issue in payment method, please contact the system administrator';
        return back()->with('not_permitted', $message);
    }

    public function ticketPaymentCheck(Request $request)
    {
        if($request->status != 'SUCCESSFUL'){
            Ticket::where('id', $request->external_reference)->delete();
            return redirect()->route('tickets')->with('not_permitted', 'payment failed.');
        }

        $ticket = Ticket::where('id', $request->external_reference)->first();

        if($ticket) {
            $lastSeat = Ticket::where('product_id', $ticket->product_id)
                                ->where('status', 1)
                                ->get()
                                ->pluck('seat_numbers')    // Get all seat_numbers arrays
                                ->flatten()                // Flatten to a single array of seat numbers
                                ->max();
            $lastSeat = $lastSeat ? max(json_decode($lastSeat, true)) : 0;
            $qty = (int) $ticket->qty;
            $seatNumbers = range($lastSeat + 1, $lastSeat + $qty);

            $ticket->status = true;
            $ticket->reference = $request->reference;
            $ticket->seat_numbers = json_encode($seatNumbers);
            $ticket->save();

            $this->sendWhatsappMsgTicketMomoSuccess($ticket);
            $message = 'Thank you for your Purchasing Ticket';
            return redirect()->route('home')->with('message', $message);
        }

        $message = 'There is any issue, please contact the system administrator';
        return redirect()->back()->with('not_permitted', $message);

    }

    public function musicianVotePaymentCheck(Request $request)
    {
        if($request->status != 'SUCCESSFUL'){
            Vote::where('id', $request->external_reference)->delete();
            return redirect()->back()->with('not_permitted', 'payment failed.');
        }

        $vote = Vote::where('id', $request->external_reference)->first();
        $vote->status = true;
        $vote->reference = $request->reference;
        $vote->save();

        if($vote) {
            $this->sendWhatsappMsgVoteMomoSuccess($vote->voters, $vote->vote, $vote->musician_id);
            $message = 'Thank you for your voting';
            return redirect()->back()->with('message', $message);
        }

        $message = 'There is any issue, please contact the system administrator';
        return redirect()->back()->with('not_permitted', $message);

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
                $this->sendWhatsappMsgVoteMomoSuccess($vote->voters, $vote->vote, $vote->musician_id);
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

        if($request->amount <= $coin_check->coin) {
            vote::create([
                'user_id' => $user->id,
                'musician_id' => $request->musician_id,
                'vote' => $request->vote,
                'status' => true,
                'reference' => rand(1, 999999)
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
        $this->sendOTP($user);
        return view('otp_screen');
    }

    public function otpCheckStore(Request $request) {
        $user = Auth::user();

        if ($request->otp == $user->otp && $user->otp_time > date('Y-m-d H:i:s', strtotime('-3 minutes'))) {
            $user->update(['otp' => null, 'otp_time' => null, 'otp_verify' => '1']);
            return redirect()->route('home');
        } else {
            return redirect()->back()->with('not_permitted', 'Invalid OTP');
        }
    }

    public function sendOTP($user) {
        if ($user->otp_time == null || $user->otp_time < date('Y-m-d H:i:s', strtotime('-1 minutes'))) {
            $otp = rand(1, 999999);
            $msg = "Your OTP is: " . $otp . "\n That will be expired after 2 minutes";
            try {
                $this->wpMessage($user->phone, $msg);
                $user->update(['otp' => $otp, 'otp_time' => date('Y-m-d H:i:s')]);
            } catch (\Exception $e) {
                return $otp;
            }
            return $otp;
        }
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

    public function mobileMoneyRequest($token, $number, $amount){

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
            CURLOPT_POSTFIELDS =>'{"amount":"'.$amount.'","from":"'.$number.'","description":"Test","external_reference": ""}',
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

//        $msg = '*Congrats:* Your account has been created' . '\n\n';
//        $msg .= '*User name:* '. $user->name . '\n\n';
//        $msg .= '*Phone number:* '. $user->phone . '\n\n';
//        $msg .= '*Password:* '. $password . '\n\n';

        $msg = "Account Creation\n\n";
        $msg .= "🎉 *Félicitations : Votre compte a été créé ! / Congrats: Your Account has been Created!* 🎉\n";
        $msg .= "👤 *Nom d'utilisateur / Username:* " . $user->name . "\n";
        $msg .= "📱 *Numéro de téléphone / Phone Number:* " . $user->phone . "\n";
        $msg .= "🔐 *Mot de passe / Password:* " . $password . "\n\n";
        $msg .= "✅ *Bienvenue à bord ! / Welcome aboard!* 🙌\n\n";
        $msg .= "🌐". getenv("APP_NAME");


        try{
            $this->wpMessage($user->phone, $msg);
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


        $msg = "🗳️ Merci pour votre vote ! 🙏 / Thank You for Your Vote! 🙏\n\n";
        $msg .= "✅ Vous avez voté ".$vote." fois pour 🌟 {$musician->name} !\n";
        $msg .= "✅ You have successfully cast ".$vote." vote";
        $msg .= $vote > 1 ? "s" : "";
        $msg .= " for 🌟 {$musician->name}!\n\n";

        $msg .= "📊 {$musician->name} a maintenant un total de ".$total_votes." votes 🎉👏\n";
        $msg .= "📊 {$musician->name} now has a total of ".$total_votes." votes 🎉👏\n\n";

        $msg .= "🪙 Coins restants : ".$remaining_coin."\n";
        $msg .= "🪙 Remaining Coins: ".$remaining_coin."\n\n";

        $msg .= "🙌 Continuez à soutenir ! Chaque vote compte ! 💪🔥\n";
        $msg .= "🙌 Keep the support coming! Every vote counts! 💪🔥\n\n";

        $msg .= "🌐". getenv("APP_NAME");


        try{
            $this->wpMessage($user->phone, $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }


    public function sendWhatsappMsgVoteMomo($user, $vote, $musician_id)
    {
        $musician = Employee::select('name', 'id')->find($musician_id);
        $total_votes = vote::where('musician_id', $musician_id)->where('status', true)->sum('vote');

        $msg = '*Thank you for your vote,* Vote status is pending, please pay your payment in 30 minutes \n\n';

        $msg .= 'You have casted ' . $vote;
        if ($vote == 1) {
            $msg .= ' vote ';
        } else {
            $msg .= ' votes ';
        }
        $msg .= 'for ' .$musician->name . '\n\n';
        $msg .= $musician->name . '`s total votes are  '.$total_votes.'\n\n';

        try{
            $this->wpMessage($user->phone, $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }

    public function sendWhatsappMsgTicketMomoSuccess($ticket)
    {

        $msg = '*Thank you for your Purchasing Ticket,*  \n\n';

        $msg .= 'You have purchased ' . $ticket->qty . ' ticket(s) for ' . $ticket->product->name . '\n\n';
        $msg .= 'Your ticket number is: ' . $ticket->token . '\n\n';
        $msg .= 'Your Seat number is: ' . $ticket->seat_numbers . '\n\n';
        $msg .= 'QR code has been sent to your whatsapp number please scan it at the entrance of the event' . '\n\n';

        $user = User::find($ticket->user_id);

        try{
            $this->wpMessage($user->phone, $msg);
        }
        catch(\Exception $e){

        }

        // send QR code
        $filename = 'qr_code_' . $ticket->token . '.png';
        $path = public_path('public/images/customer/docs/');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        $url = url("/ticket/scan/$ticket->token");

        QrCode::format('png')->size(300)->generate($url, $path . $filename);

        try {
            $this->wpAttachMessage($path.$filename, $user->phone, $filename);
        } catch (\Exception $e) {

        }

        return true;
    }

    public function sendWhatsappMsgVoteMomoSuccess($user, $vote, $musician_id)
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


        $msg = "🗳️ Merci pour votre vote ! 🙏 / Thank You for Your Vote! 🙏\n\n";
        $msg .= "✅ Vous avez voté ".$vote." fois pour 🌟 {$musician->name} !\n";
        $msg .= "✅ You have successfully cast ".$vote." vote";
        $msg .= $vote > 1 ? "s" : "";
        $msg .= " for 🌟 {$musician->name}!\n\n";

        $msg .= "📊 {$musician->name} a maintenant un total de ".$total_votes." votes 🎉👏\n";
        $msg .= "📊 {$musician->name} now has a total of ".$total_votes." votes 🎉👏\n\n";

        $msg .= "🙌 Continuez à soutenir ! Chaque vote compte ! 💪🔥\n";
        $msg .= "🙌 Keep the support coming! Every vote counts! 💪🔥\n\n";

        $msg .= "🌐". getenv("APP_NAME");

        try{
            $this->wpMessage($user->phone, $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }



    public function ticketScan($token) {
        $ticket = Ticket::where('token', $token)->first();
        $error = false;
        if($ticket) {
            return view('frontend.ticket-scan-permission', compact('ticket'));
        } else {
            return view('frontend.ticket-scan-permission', compact('error'));
        }
    }

    public function ticketScanUsed($token) {
        $ticket = Ticket::where('token', $token)->first();

        if($ticket) {
            if($ticket->is_used == true) {
                $error = 'This ticket has already been scanned, Ticket was scanned at: ' . $ticket->used_at;
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
                $msg = '*Ticket Scan Alert:* Your ticket has been scanned successfully' . '\n\n';
                $msg .= '*Ticket number:* '. $ticket->token . '\n\n';
                $msg .= '*Event name:* '. $ticket->product->name . '\n\n';
                $msg .= '*Event date:* '. $ticket->product->event_day . '\n\n';
                try{
                    $this->wpMessage($user->phone, $msg);
                }
                catch(\Exception $e){

                }
            }
            Ticket::where('token', $token)->update(['is_used' => true, 'used_at' => date('Y-m-d H:i:s')]);

            $success = 'Ticket has been scanned successfully';
            return view('frontend.ticket-scan', compact('success', 'ticket'));
        } else {
            $error = 'This ticket is not valid';
            return view('frontend.ticket-scan', compact('ticket'));
        }
    }


    public function sendWhatsappMsgForUpdatePassword($user, $password){

        $msg = '*Update Alert:* Your password has been updated, you new credentials are' . '\n\n';
        $msg .= '*User name:* '. $user->name . '\n\n';
        $msg .= '*Phone number:* '. $user->phone . '\n\n';
        $msg .= '*Password:* '. $password . '\n\n';

        try{
            $this->wpMessage($user->phone, $msg);
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



}

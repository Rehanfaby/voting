<?php

namespace App\Http\Controllers;

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
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Printing;
use Rawilk\Printing\Contracts\Printer;
use Spatie\Permission\Models\Role;
use Stripe\EphemeralKey;
use Twilio\Rest\Client;

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
         $msg = '*Thanks for contacting ' . getenv("APP_NAME") . '.* ' . '\n\n';
         $msg .= 'How might we be of help? \n\n';

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

        $this->checkVotePayment();
        if(Auth::user()) {
            $role = Auth::user()->role_id;
            if($role == 1 || $role == 2) {
                return $this->admin();
            }
        }
        $musicians = Employee::where('is_active', true)->get();
        $judges = Judge::where('is_active', true)->get();


//        $start_date = date('Y-m-d', strtotime('last monday'));
//        $end_date = date('Y-m-d');

        $best_musician = DB::table('votes')
            ->select('votes.musician_id', DB::raw('SUM(votes.vote) as total_vote'))
            ->join('employees', 'employees.id', '=', 'votes.musician_id')
//            ->whereDate('votes.created_at', '>=', $start_date)
//            ->whereDate('votes.created_at', '<=', $end_date)
            ->where('employees.is_active', true)
            ->where('votes.status', true)
            ->orderBy('total_vote', 'desc')
            ->groupBy('votes.musician_id')
            ->first();

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


        return view('frontend.home', compact('musicians', 'judges', 'best_musician', 'see_votes'));
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
        $musicians = Employee::where('is_active', true)->get();
        return view('frontend.team', compact('musicians'));
    }

    public function employee($id) {
        $musician = Employee::find($id);
        $images = Gallery::where('employee_id', $id)->where('type', 'image')->get();
        $audios = Gallery::where('employee_id', $id)->where('type', 'audio')->get();
        $videos = Gallery::where('employee_id', $id)->where('type', 'video')->get();
        $shorts = Gallery::where('employee_id', $id)->where('type', 'short')->get();
        $youtubes = Gallery::where('employee_id', $id)->where('type', 'link')->get();
        $contentants = Employee::where('is_active', true)->get();


        $see_votes = false;
        $role = Role::first();
        if($role->hasPermissionTo('see-votes')) {
            $see_votes = true;
        }

        return view('frontend.employee', compact('musician', 'contentants', 'images', 'audios', 'videos', 'shorts', 'youtubes', 'see_votes'));
    }

    public function employeeFind(Request $request) {
        $musicians = Employee::where('name', 'LIKE', '%' . $request->search . '%')->where('is_active', true)->get();
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

//        $token = $this->mobileMoneyToken();
        $token = getenv("MOMO_TOKEN");
        if($token) {
            $refernece = $this->mobileMoneyRequest($token, $request->phone, $request->amount);
            if($refernece) {
                vote::create([
                    'user_id' => $user->id,
                    'musician_id' => $request->musician_id,
                    'vote' => $request->vote,
                    'status' => false,
                    'reference' => $refernece
                ]);

//                $this->sendWhatsappMsgVoteMomo($user, $request->vote, $request->musician_id);
                return 'Thank you for your vote, Vote status is pending, please pay your payment in 30 minutes';
            }
        }
        return 'Some thing went wrong, please check your phone number';
    }

    public function musicianVotePaymentCoin(Request $request) {

        $user = Auth::user() ?? null;

        if ($request->phone_number == '+237' || $request->phone_number == null) {
            return "Phone number is incorrect";
        }

        if ($request->code == null) {
            return "Code is incorrect";
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

    private function sendOTP($user) {
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

    private function checkVotePayment(){
        $votes = vote::where('created_at', '>' , date('Y-m-d H:i:s', strtotime('-1440 minutes')))->where('status', 0)->get();
        if($votes->isEmpty()) {
            return true;
        }

        $token = getenv("MOMO_TOKEN");
        foreach ($votes as $vote) {
            $status = $this->mobileMoneyStatus($token, $vote->reference);
            if($status == 1) {
                $vote->update(['status' => 1]);
                $this->sendWhatsappMsgVoteMomoSuccess($vote->voters, $vote->vote, $vote->musician_id);
            }
            if($status == 2) {
                $vote->update(['status' => 2]);
            }
        }
    }

    public function sendWhatsappMsg($user, $password){

        $msg = '*Congrats:* Your account has been created' . '\n\n';
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


    public function sendWhatsappMsgVote($user, $vote, $musician_id, $remaining_coin)
    {
        $musician = Employee::select('name', 'id')->find($musician_id);
        $total_votes = vote::where('musician_id', $musician_id)->where('status', true)->sum('vote');

        $msg = '*Congrats:* You have casted ' . $vote;
        if ($vote == 1) {
            $msg .= ' vote ';
        } else {
            $msg .= ' votes ';
        }
        $msg .= 'for ' .$musician->name . '\n\n';
        $msg .= $musician->name . '`s total votes are  '.$total_votes.'\n\n';

        $msg .= 'Your remaining coins are  '.$remaining_coin.'\n\n';

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

    public function sendWhatsappMsgVoteMomoSuccess($user, $vote, $musician_id)
    {
        $musician = Employee::select('name', 'id')->find($musician_id);
        $total_votes = vote::where('musician_id', $musician_id)->where('status', true)->sum('vote');

        $msg = '*Thank you for your vote,*  \n\n';

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

}

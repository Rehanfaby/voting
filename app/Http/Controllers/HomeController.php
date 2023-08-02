<?php

namespace App\Http\Controllers;

use App\Employee;
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
        return view('home');
    }

    public function admin() {
        return view('index');
    }

    public function index()
    {
        if(Auth::user()) {
            $role = Auth::user()->role_id;
            if($role == 1) {
                return $this->admin();
            }
        }
        $this->checkVotePayment();
        $musicians = Employee::where('is_active', true)->get();
        return view('frontend.home', compact('musicians'));
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
        $contentants = Employee::where('is_active', true)->get();
        return view('frontend.employee', compact('musician', 'contentants'));
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
        $data['is_active'] = true;
        $data['is_deleted'] = false;
        $data['password'] = bcrypt($request->password);
        $data['name'] = $request->name;
        $data['phone'] = $request->phone;
        $data['email'] = 'user@gmail.com';
        $data['role_id'] = 3;

        if($user == null) {
            if($data['name'] == null) {
                return 'Name cannot be null';
            }
            if ($user_check = User::where('name', $request->name)->first()) {
                $pass = Hash::check($request->password, $user_check->password);
                if ($pass) {
                    $user = $user_check;
                } else {
                    $user_check->update(['password' => $data['password']]);
                    $user = $user_check;
                    $this->sendWhatsappMsgForUpdatePassword($user, $request->password);
                }
            }
        }

        if($user == null) {
            $user = User::create($data);
            $this->sendWhatsappMsg($user, $request->password);
        }

        $token = $this->mobileMoneyToken();
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
                return 'Thank you for your vote, Vote status is pending, please pay your payment in 30 minutes';
            }
        }
        return 'Some thing went wrong, please check your phone number';
    }

    public function musicianVotePaymentCoin(Request $request) {

        $user = Auth::user() ?? null;

        if($user == null) {
            return 'Please login first';
        }

        if($request->amount <= $user->beyond_coin) {
            vote::create([
                'user_id' => $user->id,
                'musician_id' => $request->musician_id,
                'vote' => $request->vote,
                'status' => true,
                'reference' => rand(1, 999999)
            ]);
            $remaining_coin = $user->beyond_coin - $request->amount;
            $user->update(['beyond_coin' => $remaining_coin]);
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
            CURLOPT_URL => 'https://demo.campay.net/api/collect/',
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
            CURLOPT_URL => 'https://demo.campay.net/api/transaction/'.$reference.'/',
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
            if($response_decode['status'] == 'SUCCESS') {
                return true;
            }
        }

        return false;

    }

    private function checkVotePayment(){
        $votes = vote::where('created_at', '>' , date('Y-m-d H:i:s', strtotime('-120 minutes')))->where('status', false)->get();

        if($votes->isEmpty()) {
            return true;
        }

        $token = $this->mobileMoneyToken();
        foreach ($votes as $vote) {
            $status = $this->mobileMoneyStatus($token, $vote->reference);
            if($status == true) {
                $vote->update(['status' => 1]);
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

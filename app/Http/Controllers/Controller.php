<?php

namespace App\Http\Controllers;

use App\BookingProduct;
use App\Customer;
use App\GeneralSetting;
use App\StockDuration;
use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Role;

class Controller extends BaseController
{


    private $user;

    public function __construct() {


        $this->middleware(function ($request, $next) {
            $this->user = \Illuminate\Support\Facades\Auth::user();
            if ($this->user && $this->user->role_id != 5) {
                $role = Role::find($this->user->role_id);
                $permissions = Role::findByName($role->name)->permissions;

                foreach ($permissions as $permission) {
                    $all_permission[] = $permission->name;
                }
                View::share ('all_permission', $all_permission);
            }
            return $next($request);
        });
    }

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function mobileMoneyRequestLink($token, $amount, $route, $patient_id, $number){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.campay.net/api/get_payment_link/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "amount": "'.$amount.'",
                "from": "'.$number.'",
                "currency": "XAF",
                "external_reference": "'.$patient_id.'",
                "redirect_url": "'.$route.'",
                "payment_options":"MOMO,CARD",
                "failure_redirect_url": "'.$route.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Token ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $response_decode = json_decode($response, true);

        curl_close($curl);


        if($response_decode && isset($response_decode['link'])) {
            return $response_decode['link'];
        }
        return false;
    }

    public function mobileMoneyOrderRequestLink($token, $amount, $route, $orders, $failure_route, $number){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.campay.net/api/get_payment_link/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "amount": "'.$amount.'",
                "from": "'.$number.'",
                "currency": "XAF",
                "external_reference": "'.$failure_route.',' .$orders.'",
                "redirect_url": "'.$route.'",
                "payment_options":"MOMO,CARD",
                "failure_redirect_url": "'.$route.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Token ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $response_decode = json_decode($response, true);

        curl_close($curl);

        if($response_decode && isset($response_decode['link'])) {
            return $response_decode['link'];
        }
        return false;
    }

    public function wpMessage($number, $msg){
        $params= [
            'token' => getenv('ULTRAMSG_TOKEN'),
            'to' => $number,
            'body' => $msg
        ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ultramsg.com/".getenv('ULTRAMSG_INSTANCE')."/messages/chat",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
    }


    public function wpAttachMessage($path, $number, $filename='compile_result.pdf'){
        $instance=getenv('ULTRAMSG_INSTANCE');
        $token=getenv('ULTRAMSG_TOKEN');
        $to=$number;

        $data = file_get_contents($path);

        $img_base64 =  base64_encode($data);
        $img_base64 =urlencode($img_base64);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ultramsg.com/$instance/messages/document",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_SSL_VERIFYHOST =>0,
            CURLOPT_SSL_VERIFYPEER =>0,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "token=$token&to=$to&document=$img_base64&filename=$filename",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return true;
    }


    public function wpPDFMessage($path, $lims_customer_data, $filename='invoice.pdf'){
        $instance=getenv('ULTRAMSG_INSTANCE');
        $token=getenv('ULTRAMSG_TOKEN');
        $to=$lims_customer_data->phone_number;

        $data = file_get_contents($path);

        $img_base64 =  base64_encode($data);
        $img_base64 =urlencode($img_base64);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ultramsg.com/$instance/messages/document",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_SSL_VERIFYHOST =>0,
            CURLOPT_SSL_VERIFYPEER =>0,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "token=$token&to=$to&document=$img_base64&filename=$filename",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return true;
    }

    public function sendWhatsappMsgForPlacingOrderToBuyer($order){

        $general_setting = GeneralSetting::first();

        $msg = '*Subject:* Order Confirmation for '. $order->name . '\n\n';
        $msg .= 'Dear '. $order->name . '\n\n';
        $msg .= 'Thank you for choosing '.$general_setting->site_title.' as your preferred supplier. We are pleased to confirm the availability of the products requested.\n\n\n';

        $msg .= '*Order Details:*\n';
        $msg .= 'Order Number: '.$order->id.'\n';
        $msg .=  'Order Date: '.$order->created_at.'\n\n';

        if ($order->payment_method == 'COD') {
            $msg .= '*Note:* Your payment status is cash on delivery. Admin will approve order.\n\n';
        }

        $msg .= '*Product Detail:*\n';
        foreach ($order->orderProducts as $key => $product) {
            $msg .= $key+1 .') ['. $product->product->name . '] [' . $product->quantity . '] x [ '. number_format($product->price, 2) .'] = ['. number_format($product->sub_total, 2) .']\n';
        }

        $msg .= 'Total Amount: ' . number_format($order->grand_total, 2) . '\n\n';

        $msg .= '*Payment Information:*\n';
        $msg .= 'Payment Method: ' . $order->payment_method . '\n';
        $msg .= 'Delivery Information: ' . $order->address . '\n';

        $msg .= 'Once again, we appreciate your business and trust in '. $general_setting->site_title .'. We strive to provide exceptional products and services, and we are confident that you will be satisfied with our products.\n';
        $msg .= 'Thank you for choosing ' . $general_setting->site_title . '.\n\n';

        $msg .= 'Best regards,\n';
        $msg .= @$general_setting->develoled_by. '\n';
        $msg .= $general_setting->site_title. '\n\n';
        $msg .= request()->getHost();

        try{
            $this->wpMessage($order->phone, $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }

    public function sendWhatsappMsgForPlacingServiceToBuyer($order){

        $general_setting = GeneralSetting::first();

        $msg = '*Subject:* Service order Confirmation for '. $order->name . '\n\n';
        $msg .= 'Dear '. $order->name . '\n\n';
        $msg .= 'Thank you for choosing '.$general_setting->site_title.' as your preferred supplier. We are pleased to confirm the availability of the service requested.\n\n\n';

        $msg .= '*Service Order Details:*\n';
        $msg .= 'Order Number: '.$order->id.'\n';
        $msg .=  'Order Date: '.$order->created_at.'\n\n';

        if ($order->payment_method == 'COD') {
            $msg .= '*Note:* Your payment status is cash on delivery. Admin will approve order.\n\n';
        }

        $msg .= '*Service Detail:*\n';
        foreach ($order->orderProducts as $key => $product) {
            $msg .= 'Name: '. $product->product->name . '\n';
            $msg .= 'Subject: '. $order->subject . '\n';
            $msg .= 'Project Title: '. $order->project_title . '\n';

            $msg .= 'project_guide_lines: '. $order->project_guide_lines . '\n';
            $msg .= 'Citation Sytle: '. $order->citation_style . '\n';
            $msg .= 'Font Style: '. $order->font_style . '\n';
            $msg .= 'Language: '. $order->language . '\n';
            $msg .= 'References: '. $order->references . '\n';
            $msg .= 'Academic Level: '. $order->academic_year . '\n';
            $msg .= 'DeadLine: '. $order->variant_id . '\n';
            $msg .= 'Number Of Pages: '. $order->number_of_pages . '\n';
            $msg .= 'Word Count: '. $order->word_count . '\n';
            $msg .= 'Line Spacing: '. $order->spacing . '\n\n';

            $msg .= '*Addons* \n';
            if($order->quality_double_checker){$msg .= '-- Quality Double Checker \n';}
            if($order->abstract_page){$msg .= '-- Abstract Page \n';}
            if($order->one_page_summary){$msg .= '-- One Page Summary \n';}
            if($order->grammar_checker){$msg .= '-- Grammar Checker \n';}
            if($order->preferred_expert){$msg .= '-- Preferred Expert \n';}

        }
        $msg .= '\n*Grand Total:* ';
        $msg .= number_format($order->grand_total, 2) . '\n\n';

        $msg .= '*Payment Information:*\n';
        $msg .= 'Payment Method: ' . $order->payment_method . '\n';
        $msg .= 'Delivery Information: ' . $order->address . '\n';

        $msg .= 'Once again, we appreciate your business and trust in '. $general_setting->site_title .'. We strive to provide exceptional products and services, and we are confident that you will be satisfied with our products.\n';
        $msg .= 'Thank you for choosing ' . $general_setting->site_title . '.\n\n';

        $msg .= 'Best regards,\n';
        $msg .= @$general_setting->develoled_by. '\n';
        $msg .= $general_setting->site_title. '\n\n';
        $msg .= request()->getHost();

        try{
            $this->wpMessage($order->phone, $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }

    public function sendWhatsappMsgForPlacingServiceToSaller($order){

        $general_setting = GeneralSetting::first();

        $msg = '*Subject:* Service order Confirmation for '. $order->name . '\n\n';
        $msg .= 'A new Service order is placed. \n\n';

        $msg .= '*Service Order Details:*\n';
        $msg .= 'Order Number: '.$order->id.'\n';
        $msg .=  'Order Date: '.$order->created_at.'\n\n';

        if ($order->payment_method == 'COD') {
            $msg .= '*Note:* This service order payment status is cash on delivery. Please have look and approve service order.\n\n';
        }

        $msg .= '*Service Detail:*\n';
        foreach ($order->orderProducts as $key => $product) {
            $msg .= 'Name: '. $product->product->name . '\n';
            $msg .= 'Subject: '. $order->subject . '\n';
            $msg .= 'Project Title: '. $order->project_title . '\n';

            $msg .= 'project_guide_lines: '. $order->project_guide_lines . '\n';
            $msg .= 'Citation Sytle: '. $order->citation_style . '\n';
            $msg .= 'Font Style: '. $order->font_style . '\n';
            $msg .= 'Language: '. $order->language . '\n';
            $msg .= 'References: '. $order->references . '\n';
            $msg .= 'Academic Level: '. $order->academic_year . '\n';
            $msg .= 'DeadLine: '. $order->variant_id . '\n';
            $msg .= 'Number Of Pages: '. $order->number_of_pages . '\n';
            $msg .= 'Word Count: '. $order->word_count . '\n';
            $msg .= 'Line Spacing: '. $order->spacing . '\n\n';

            $msg .= '*Addons* \n';
            if($order->quality_double_checker){$msg .= '-- Quality Double Checker \n';}
            if($order->abstract_page){$msg .= '-- Abstract Page \n';}
            if($order->one_page_summary){$msg .= '-- One Page Summary \n';}
            if($order->grammar_checker){$msg .= '-- Grammar Checker \n';}
            if($order->preferred_expert){$msg .= '-- Preferred Expert \n';}

        }
        $msg .= '\n*Grand Total:* ';
        $msg .= number_format($order->grand_total, 2) . '\n\n';

        $msg .= '*Payment Information:*\n';
        $msg .= 'Payment Method: ' . $order->payment_method . '\n';
        $msg .= 'Delivery Information: ' . $order->address . '\n';

        $msg .= 'Once again, we appreciate your business and trust in '. $general_setting->site_title .'. We strive to provide exceptional products and services, and we are confident that you will be satisfied with our products.\n';
        $msg .= 'Thank you for choosing ' . $general_setting->site_title . '.\n\n';

        $msg .= 'Best regards,\n';
        $msg .= @$general_setting->develoled_by. '\n';
        $msg .= $general_setting->site_title. '\n\n';
        $msg .= request()->getHost();

        try{
            $this->wpMessage(getenv('ADMIN_NUMBER'), $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }


    public function sendWhatsappMsgMomoPaymentSuccess($number, $total)
    {
        $msg = '*Thank you for your Order,*  \n\n';
        $msg .= 'You have payed ' . $total;

        try{
            $this->wpMessage($number, $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }

    public function sendWhatsappMsgForPlacingOrderToBuyerBooking($order){

        $general_setting = GeneralSetting::first();
        $customer = User::where('id', $order->user_id)->first();

        $msg = '*Subject:* Order Confirmation for '. $customer->name . '\n\n';
        $msg .= 'Dear '. $customer->name . '\n\n';
        $msg .= 'Thank you for choosing '.$general_setting->site_title.' as your preferred supplier. We are pleased to confirm the availability of the products requested.\n\n\n';

        $msg .= '*Order Details:*\n';
        $msg .= 'Order Number: '.$order->id.'\n';
        $msg .=  'Order Date: '.$order->created_at.'\n\n';

        if ($order->payment_method == 'COD') {
            $msg .= '*Note:* Your payment status is cash on delivery. Admin will approve order.\n\n';
        }

        $msg .= '*Product Detail:*\n';
        $bookingProducts = BookingProduct::with('product')->where('booking_id', $order->id)->get();
        foreach ($bookingProducts as $key => $product) {
            $msg .= $key+1 .') ['. $product->product->name . '] [' . $product->qty . '] x [' . $product->number_duration . '] x [ '. number_format($product->net_unit_price, 2) .'] = ['. number_format($product->qty * $product->number_duration * $product->net_unit_price, 2) .']\n';
            $msg .= "*Start* : " . $product->start . " *Return* : " . $product->end . '\n';
        }

        $msg .= 'Total Amount: ' . number_format($order->grand_total, 2) . '\n\n';

        $msg .= '*Payment Information:*\n';
        $msg .= 'Payment Method: ' . $order->payment_method . '\n';
        $msg .= 'Delivery Information: ' . $order->address . '\n';

        $msg .= 'Once again, we appreciate your business and trust in '. $general_setting->site_title .'. We strive to provide exceptional products and services, and we are confident that you will be satisfied with our products.\n';
        $msg .= 'Thank you for choosing ' . $general_setting->site_title . '.\n\n';

        $msg .= 'Best regards,\n';
        $msg .= @$general_setting->develoled_by. '\n';
        $msg .= $general_setting->site_title. '\n\n';
        $msg .= request()->getHost();

        try{
            $this->wpMessage($customer->phone, $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }

    public function sendWhatsappMsgForPlacingOrderToAdminBooking($order){

        $general_setting = GeneralSetting::first();
        $customer = Customer::where('id', $order->customer_id)->first();

        $msg = '*Subject:* Order Confirmation for '. $customer->name . '\n\n';
        $msg .= 'Dear Admin \n\n';
        $msg .= 'You have received a booking order.\n\n\n';

        $msg .= '*Order Details:*\n';
        $msg .= 'Order Number: '.$order->id.'\n';
        $msg .=  'Order Date: '.$order->created_at.'\n\n';

        if ($order->payment_method == 'COD') {
            $msg .= '*Note:* Your payment status is cash on delivery. Admin will approve order.\n\n';
        }

        $msg .= '*Product Detail:*\n';
        $bookingProducts = BookingProduct::with('product')->where('booking_id', $order->id)->get();
        foreach ($bookingProducts as $key => $product) {
            $msg .= $key+1 .') ['. $product->product->name . '] [' . $product->qty . '] x [' . $product->number_duration . '] x [ '. number_format($product->net_unit_price, 2) .'] = ['. number_format($product->qty * $product->number_duration * $product->net_unit_price, 2) .']\n';
            $msg .= "*Start* : " . $product->start . " *Return* : " . $product->end . '\n';
        }

        $msg .= 'Total Amount: ' . number_format($order->grand_total, 2) . '\n\n';

        $msg .= '*Payment Information:*\n';
        $msg .= 'Payment Method: ' . $order->payment_method . '\n';
        $msg .= 'Delivery Information: ' . $order->address . '\n';

        $msg .= 'Once again, we appreciate your business and trust in '. $general_setting->site_title .'. We strive to provide exceptional products and services, and we are confident that you will be satisfied with our products.\n';
        $msg .= 'Thank you for choosing ' . $general_setting->site_title . '.\n\n';

        $msg .= 'Best regards,\n';
        $msg .= @$general_setting->develoled_by. '\n';
        $msg .= $general_setting->site_title. '\n\n';
        $msg .= request()->getHost();

        try{
            $this->wpMessage(getenv('ADMIN_NUMBER'), $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }

    public function sendWhatsappMsgForPlacingOrderToSaller($order){

        $general_setting = GeneralSetting::first();
        $vendor = User::where('id', $order->vendor_id)->first();

        $msg = '*Subject:* Order Confirmation for '. $order->name . '\n\n';
        $msg .= 'Dear Seller \n\n';
        $msg .= '*Congrats* You have received an order from '. $order->name .'('.$order->phone.') of '. $order->grand_total . ' CFA' .'\n\n';

        $msg .= '*Order Details:*\n';
        $msg .= 'Order Number: '.$order->id.'\n';
        $msg .=  'Order Date: '.$order->created_at.'\n\n';

        if ($order->payment_method == 'COD') {
            $msg .= '*Note:* Payment status is cash on delivery. Please check and verify and approve order.\n\n';
        }

        $msg .= '*Product Detail:*\n';
        foreach ($order->orderProducts as $key => $product) {
            $msg .= $key+1 .') ['. $product->product->name . '] [' . $product->quantity . '] x [ '. number_format($product->price, 2) .'] = ['. number_format($product->sub_total, 2) .']\n';
        }

        $msg .= 'Total Amount: ' . number_format($order->grand_total, 2) . '\n\n';

        $msg .= '*Payment Information:*\n';
        $msg .= 'Payment Method: ' . $order->payment_method . '\n';
        $msg .= 'Delivery Information: ' . $order->address . '\n';

        $msg .= 'Best regards,\n';
        $msg .= @$general_setting->develoled_by. '\n';
        $msg .= $general_setting->site_title. '\n\n';
        $msg .= request()->getHost();

        try{
            $this->wpMessage($vendor->phone, $msg);
            $this->wpMessage(getenv('ADMIN_NUMBER'), $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }

    public function sendWhatsappMsgMomoPaymentSuccessDonation($general_setting, $order, $total)
    {
        $user = User::select('name', 'id', 'phone')->find($order->user_id);

        $msg = '*Subject:* Donation Confirmation for '. $order->name . '\n\n';
        $msg .= '*Thank you for your Donation,*  \n\n';
        $msg .= 'You have payed *' . $total . '* CFA' .'\n\n';

        $msg .= 'Best regards,\n';
        $msg .= @$general_setting->develoled_by. '\n';
        $msg .= @$general_setting->site_title. '\n\n';

        $msg .= request()->getHost();

        try{
            $this->wpMessage($user->phone, $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }

    public function sendWhatsappMsgMomoPaymentSuccessDonationSeller($general_setting, $order)
    {
        $user = User::select('name', 'id', 'phone')->find($order->vendor_id);

        $msg = '*Subject:* Donation Confirmation for '. $order->name . '\n\n';

        $msg .= 'Dear '. $user->name .' \n\n';
        $msg .= '*Congrats* You have received a donation from '. $order->name .'('.$order->phone.') of *'. $order->grand_total . '* CFA' .'\n\n';

        $msg .= 'Best regards,\n';
        $msg .= @$general_setting->develoled_by. '\n';
        $msg .= @$general_setting->site_title. '\n\n';

        $msg .= request()->getHost();

        try{
            $this->wpMessage($user->phone, $msg);
        }
        catch(\Exception $e){

        }

        $msg = '*Subject:* Donation Confirmation for '. $order->name . '\n\n';

        $msg .= 'Dear Admin \n\n';
        $msg .= '*Congrats* Your Vendor ('. $user->name .') have received a donation from '. $order->name .'('.$order->phone.') of *'. $order->grand_total . '* CFA' .'\n\n';

        $msg .= 'Best regards,\n';
        $msg .= @$general_setting->develoled_by. '\n';
        $msg .= @$general_setting->site_title. '\n\n';

        $msg .= request()->getHost();

        try{
            $this->wpMessage(getenv('ADMIN_NUMBER'), $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }



    public function sendOTP($phone) {
        $otp = rand(1, 999999);
        $msg = "Your OTP is: " . $otp . "\n That will be expired after 5 minutes";
        try {
            $this->wpMessage($phone, $msg);
        } catch (\Exception $e) {
            return $otp;
        }
        return $otp;
    }


    public function sendWhatsappMsgForAccount($user, $password){

        $msg = '*Congrats:* Your account has been created' . '\n\n';
        $msg .= '*User name:* '. $user->name . '\n\n';
        $msg .= '*Phone number:* '. $user->phone . '\n\n';
        $msg .= '*Password:* '. $password . '\n\n';
        $msg .= request()->getHost() . '\n\n';


        try{
            $this->wpMessage($user->phone, $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }

    public function sendWhatsappMsgForVendorAccount($user, $password){

        $general_setting = GeneralSetting::first();

        $msg = '*Congrats:* Your account has been created' . '\n\n';
        $msg .= '*Vendor name:* '. $user->name . '\n\n';
        $msg .= '*Phone number:* '. $user->phone . '\n\n';
        $msg .= '*Password:* '. $password . '\n\n';
        $msg .= '\n\n';
        $msg .= '*Note:* Your Account is under review, Admin will review and approve your account soon. After approval you can sale your products. On every sale you will charged ' .$user->commission. '% \n\n';
        $msg .= request()->getHost() . '\n\n';


        try{
            $this->wpMessage($user->phone, $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }

    public function sendWhatsappMsgForVendorAccountToAdmin($user, $password){

        $general_setting = GeneralSetting::first();

        $msg = '*Congrats:* A new account has been created' . '\n\n';
        $msg .= '*Vendor name:* '. $user->name . '\n\n';
        $msg .= '*Phone number:* '. $user->phone . '\n\n';
        $msg .= '*Password:* '. $password . '\n\n';
        $msg .= '\n\n';
        $msg .= '*Note:* Please review and active this shop, so vendor can sale his products. \n\n';
        $msg .= request()->getHost() . '\n\n';


        try{
            $this->wpMessage(getenv('ADMIN_NUMBER'), $msg);
        }
        catch(\Exception $e){

        }

        return true;
    }

    public function stockDurationSave($id, $qty) {
        $stockDuration = StockDuration::where([
            'product_id' => $id,
            'restock' => null
        ])->first();
        if ($qty == 0.0) {
            if(!$stockDuration) {
                StockDuration::create([
                    'product_id' => $id,
                    'out_of_stock' => date('Y-m-d')
                ]);
            }
        } else {
            if ($stockDuration) {
                $stockDuration->update(['restock' => date('Y-m-d')]);
            }
        }
    }

}

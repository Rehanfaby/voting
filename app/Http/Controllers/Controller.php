<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function mobileMoneyRequestLink($token, $amount, $route, $musician_id, $number){

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
                "external_reference": "'.$musician_id.'",
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
}

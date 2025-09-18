<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WhatsAppService;

class WhatsAppController extends Controller
{
    protected $whatsappService;

    public function __construct()
    {
        $this->whatsappService = new WhatsAppService();
    }

    public function send()
    {
        $to = "+923410060960";
        $message= "test message";
        $response = $this->whatsappService->sendMessage($to, $message);
        return response()->json(['message' => $response]);
    }
}

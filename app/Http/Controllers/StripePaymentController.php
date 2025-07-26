<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripePaymentController extends Controller
{
    public function paymentCancel(Request $request)
    {
        return redirect()->back()->with('not_permitted', 'Something went wrong, please try again');
    }
}

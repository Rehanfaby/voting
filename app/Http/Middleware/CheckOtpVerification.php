<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOtpVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!config('app.login_otp_enabled', true)) {
            return $next($request);
        }

        if ($request->routeIs('check.otp', 'check.otp.store', 'logout')) {
            return $next($request);
        }

        $user = Auth::user();
        if ($user && (int) $user->otp_verify === 0) {
            return redirect()->route('check.otp');
        }

        return $next($request);
    }
}

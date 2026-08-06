<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckOtpVerification
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->routeIs('check.otp', 'check.otp.store', 'check.otp.resend', 'check.otp.cancel', 'logout')) {
            return $next($request);
        }

        $user = Auth::user();
        if ($user && $this->userRequiresLoginOtp($user) && (int) $user->otp_verify === 0) {
            return redirect()->route('check.otp');
        }

        return $next($request);
    }

    /** Match LoginController: global flag, or always for Ambassador / Judge. */
    protected function userRequiresLoginOtp($user): bool
    {
        if (config('app.login_otp_enabled', true)) {
            return true;
        }

        $role = strtolower((string) (
            optional($user->role)->name
            ?: optional(DB::table('roles')->find($user->role_id))->name
            ?: ''
        ));

        return in_array($role, ['ambassador', 'judge'], true);
    }
}

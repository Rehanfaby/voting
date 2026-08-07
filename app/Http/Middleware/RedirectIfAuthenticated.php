<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user = Auth::guard($guard)->user();

            // Never send authenticated users to /home — that path does not exist (404).
            if ($this->userRequiresLoginOtp($user) && (int) $user->otp_verify === 0) {
                return redirect()->route('check.otp');
            }

            if ((int) $user->role_id === 3) {
                return redirect('/');
            }

            return redirect('/admin');
        }

        return $next($request);
    }

    /** Match LoginController / CheckOtpVerification. */
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

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\JudgeAmbassadorAccountService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('throttle:10,1')->only('login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'password' => 'required',
        ]);

        $login = trim((string) $request->name);
        $password = $request->password;
        $authenticated = false;

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $authenticated = auth()->attempt(['email' => $login, 'password' => $password]);
        } else {
            $authenticated = auth()->attempt(['name' => $login, 'password' => $password]);
            if (!$authenticated) {
                $user = app(JudgeAmbassadorAccountService::class)->findUserByPhone($login);
                if ($user && (int) $user->is_active === 1 && \Hash::check($password, $user->password)) {
                    Auth::login($user);
                    $authenticated = true;
                }
            }
        }

        if ($authenticated) {
            $user = Auth::user();

            if ($user && $this->userRequiresLoginOtp($user)) {
                $user->update(['otp_verify' => 0]);
                return redirect()->route('check.otp');
            }

            if ($user && $user->role_id != 3) {
                return redirect('/admin');
            }

            return redirect('/');
        }

        return redirect()->route('user.login')
            ->with('not_permitted', 'Name, email, phone or password is incorrect. Use Forgot Password if you need a new password.');
    }

    /**
     * OTP after password login when globally enabled, or always for Ambassadors / Judges.
     */
    protected function userRequiresLoginOtp($user): bool
    {
        if (config('app.login_otp_enabled', true)) {
            return true;
        }

        $role = strtolower((string) (
            optional($user->role)->name
            ?: optional(\DB::table('roles')->find($user->role_id))->name
            ?: ''
        ));

        return in_array($role, ['ambassador', 'judge'], true);
    }
}

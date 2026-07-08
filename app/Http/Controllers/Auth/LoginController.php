<?php



namespace App\Http\Controllers\Auth;



use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;


class LoginController extends Controller

{



    use AuthenticatesUsers;



    protected $redirectTo = '/';

    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

        $this->middleware('guest')->except('logout');
        $this->middleware('throttle:10,1')->only('login');

    }



    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function login(Request $request)

    {

        $input = $request->all();

        $this->validate($request, [

            'name' => 'required',

            'password' => 'required',

        ]);



        $fieldType = filter_var($request->name, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if(auth()->attempt(array($fieldType => $input['name'], 'password' => $input['password'])))

        {

            $user = Auth::user();

            if ($user && config('app.login_otp_enabled', true)) {
                $user->update(['otp_verify' => 0]);
                return redirect()->route('check.otp');
            }

            if($user && $user->role_id != 3) {
                return redirect('/admin');
            }
            return redirect('/');

        }else{

            return redirect()->route('user.login')

                ->with('not_permitted','Email-Address | Name And Password Are Wrong.');

        }



    }

}

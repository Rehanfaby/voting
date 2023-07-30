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
            $role = Role::find(Auth::user()->role_id);
            if($role->hasPermissionTo('one_time_otp')){
                Auth::user()->update(['otp_verify' => 0]);
                return redirect()->route('check.otp');
            }

            return redirect('/');

        }else{

            return redirect()->route('login')

                ->with('error','Email-Address And Password Are Wrong.');

        }



    }

}

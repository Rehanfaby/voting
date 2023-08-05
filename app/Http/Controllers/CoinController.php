<?php

namespace App\Http\Controllers;

use App\Coin;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class CoinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('coins-index')) {
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;

            $coins = Coin::orderBy('id', 'desc')->get();
            return view('coins.index', compact('coins', 'all_permission'));
        } else {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');
        $msg = '';

        if(!$request->is_active) {
            $data['is_active'] = 0;
        }
        Coin::create($data);

        $user = User::where('phone', $request->phone)->where('is_active', true)->first();

        if ($user == null) {
            unset($data['is_active']);
            $password = rand(1, 999999);
            $data['is_active'] = true;
            $data['is_deleted'] = false;
            $data['password'] = bcrypt($password);
            $data['name'] = $request->phone;
            $data['phone'] = $request->phone;
            $data['email'] = 'user@gmail.com';
            $data['role_id'] = 3;
            $user = User::create($data);

            $msg .= '*Congrats:* Your account has been created \n\n';
            $msg .= '*User name:* '. $user->name . '\n\n';
            $msg .= '*Phone number:* '. $user->phone . '\n\n';
            $msg .= '*Password:* '. $password . '\n\n';

        }


        $msg .= $data['coin'] . ' beyond coins have been added in your account \n\n';
        $msg .= 'Coin code is: ' .$data['code']. '\n\n';

        $message = $this->sendWhatsappMsg($user, $msg);

        return redirect()->route('coins.index')->with('message', $message);
    }

    public function sendWhatsappMsg($user, $msg){

        $message = 'Coin has been created';

        try{
            $this->wpMessage($user->phone, $msg);
        }
        catch(\Exception $e){
            $message = 'Coin has been created, but message has not sent';
            return $message;
        }

        return $message;
    }

    public function edit($id)
    {
        $role = Role::firstOrCreate(['id' => Auth::user()->role_id]);
        if ($role->hasPermissionTo('coins-edit')) {
            $coin = Coin::find($id);
            return $coin;
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coin  $coin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $vote = Coin::find($data['id']);
        if($request->is_active != null) {
            $data['is_active'] = 1;
        } else {
            $data['is_active'] = 0;
        }
        $vote->update($data);

        return redirect('coins')->with('message', 'Data updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lims_expense_data = Coin::find($id);
        $lims_expense_data->delete();
        return back()->with('not_permitted', 'Data deleted successfully');
    }

    public function deleteBySelection(Request $request)
    {
        $vote_id = $request['expenseIdArray'];
        foreach ($vote_id as $id) {
            if($id == null) {
                continue;
            }
            Coin::find($id)->delete();
        }
        return 'Coins deleted successfully!';
    }
}

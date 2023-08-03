<?php

namespace App\Http\Controllers;

use App\Employee;
use App\User;
use App\vote;
use Illuminate\Http\Request;
use Auth;
use Spatie\Permission\Models\Role;

class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('votes-index')) {
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;

            if($request->start_date) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
            }
            else {
                $start_date = date('Y-m-01', strtotime('-1 year', strtotime(date('Y-m-d'))));
                $end_date = date("Y-m-d");
            }

            $votes = Vote::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->orderBy('id', 'desc')->get();
            return view('votes.index', compact('votes', 'start_date', 'end_date', 'all_permission'));
        } else {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }
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
        if(!$request->status) {
            $data['status'] = 0;
        }
        vote::create($data);
        return redirect()->route('votes.index')->with('message', 'Vote has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::firstOrCreate(['id' => Auth::user()->role_id]);
        if ($role->hasPermissionTo('votes-edit')) {
            $vote = vote::find($id);
            return $vote;
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $vote = vote::find($data['id']);
        if($request->status != null) {
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }
        $vote->update($data);
        return redirect('votes')->with('message', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lims_expense_data = vote::find($id);
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
            vote::find($id)->delete();
        }
        return 'Votes deleted successfully!';
    }
}

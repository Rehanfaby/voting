<?php

namespace App\Http\Controllers;

use App\Employee;
use App\User;
use App\vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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

            $statusFilter = strtolower((string) $request->input('status', 'all'));
            if (!in_array($statusFilter, ['all', 'success', 'pending', 'failed'], true)) {
                $statusFilter = 'all';
            }

            $dated = function () use ($start_date, $end_date) {
                return vote::whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date);
            };

            $statusCounts = [
                'all' => $dated()->count(),
                'success' => $dated()->where('status', 1)->count(),
                'pending' => $dated()->where('status', 0)->count(),
                'failed' => $dated()->where('status', 2)->count(),
            ];

            $votesQuery = $dated();
            if ($statusFilter === 'success') {
                $votesQuery->where('status', 1);
            } elseif ($statusFilter === 'pending') {
                $votesQuery->where('status', 0);
            } elseif ($statusFilter === 'failed') {
                $votesQuery->where('status', 2);
            }

            $votes = $votesQuery->orderBy('id', 'desc')->get();
            $lastClearedAt = null;
            if (Schema::hasColumn('votes', 'cleared_at')) {
                $lastClearedAt = vote::whereNotNull('cleared_at')->max('cleared_at');
            }
            return view('votes.index', compact(
                'votes',
                'start_date',
                'end_date',
                'all_permission',
                'lastClearedAt',
                'statusFilter',
                'statusCounts'
            ));
        } else {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }
    }

    /**
     * Zero completed vote counts without deleting rows or voters.
     * Public tallies restart from this moment; payment/voter history is kept.
     */
    public function clearVotes(Request $request)
    {
        $role = Role::find(Auth::user()->role_id);
        if (!$role || !$role->hasPermissionTo('votes-delete')) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        if (!Schema::hasColumn('votes', 'cleared_at') || !Schema::hasColumn('votes', 'cleared_vote')) {
            return redirect()->back()->with('not_permitted', 'Clear Votes is not available yet. Please run migrations.');
        }

        $cleared = DB::table('votes')
            ->where('status', 1)
            ->where('vote', '>', 0)
            ->whereNull('cleared_at')
            ->update([
                'cleared_vote' => DB::raw('vote'),
                'vote' => 0,
                'cleared_at' => now(),
                'updated_at' => now(),
            ]);

        return redirect()->route('votes.index')->with(
            'message',
            trans('file.Votes cleared successfully. Voters kept. New votes will count from now.', ['count' => (int) $cleared])
        );
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
        $role = Role::find(Auth::user()->role_id);
        if (!$role || !$role->hasPermissionTo('votes-delete')) {
            return response('Not permitted', 403);
        }

        // Delete all rows matching the current date + status filter.
        if ($request->boolean('delete_all')) {
            $start = $request->input('start_date', date('Y-m-01', strtotime('-1 year')));
            $end = $request->input('end_date', date('Y-m-d'));
            $statusFilter = strtolower((string) $request->input('status', 'all'));

            $query = vote::whereDate('created_at', '>=', $start)
                ->whereDate('created_at', '<=', $end);

            if ($statusFilter === 'success') {
                $query->where('status', 1);
            } elseif ($statusFilter === 'pending') {
                $query->where('status', 0);
            } elseif ($statusFilter === 'failed') {
                $query->where('status', 2);
            }

            $count = $query->count();
            $query->delete();

            return $count . ' vote(s) deleted successfully!';
        }

        $vote_id = $request['expenseIdArray'] ?? $request['voteIdArray'] ?? $request['ids'] ?? [];
        if (!is_array($vote_id)) {
            $vote_id = [$vote_id];
        }
        $vote_id = array_values(array_unique(array_filter(array_map(function ($id) {
            return (is_numeric($id) && (int) $id > 0) ? (int) $id : null;
        }, $vote_id))));

        if (count($vote_id) === 0) {
            return 'No vote was selected.';
        }

        $count = 0;
        foreach ($vote_id as $id) {
            $vote = vote::find($id);
            if ($vote) {
                $vote->delete();
                $count++;
            }
        }

        return $count . ' vote(s) deleted successfully!';
    }
}

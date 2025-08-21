<?php

namespace App\Http\Controllers;

use App\Booking;
use App\BookingProduct;
use App\Category;
use App\Employee;
use App\GeneralSetting;
use App\Judge;
use App\Ticket;
use Illuminate\Http\Request;
use App\Product;
use App\ProductPurchase;
use App\Product_Sale;
use App\ProductQuotation;
use App\Sale;
use App\Purchase;
use App\Quotation;
use App\Transfer;
use App\Returns;
use App\ProductReturn;
use App\ReturnPurchase;
use App\ProductTransfer;
use App\PurchaseProductReturn;
use App\Payment;
use App\Warehouse;
use App\Product_Warehouse;
use App\Expense;
use App\Payroll;
use App\User;
use App\Customer;
use App\Supplier;
use App\Variant;
use App\ProductVariant;
use DB;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ReportController extends Controller
{

    public function votingReport(Request $request)
    {
        $start_date = null;
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('vote-report')){
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if($request->start_date) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
            }
            else {
                $start_date = '2023-07-01';
                $end_date = date('Y-m-d');
            }


            $votes = DB::table('votes')
                ->select('votes.musician_id', DB::raw('SUM(votes.vote) as total_vote'))
                ->join('employees', 'employees.id', '=', 'votes.musician_id')
                ->whereDate('votes.created_at', '>=', $start_date)
                ->whereDate('votes.created_at', '<=', $end_date)
                ->where('employees.is_active', true)
                ->where('votes.status', true)
                ->orderBy('total_vote', 'desc')
                ->groupBy('votes.musician_id')
                ->get();

            return view('report.voting', compact('start_date', 'end_date', 'votes', 'all_permission'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function ticketPurchaseReport(Request $request)
    {
        $start_date = null;
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('vote-report')){
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if($request->start_date) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
            }
            else {
                $start_date = '2023-07-01';
                $end_date = date('Y-m-d');
            }
            $status = $request->status ?? 2;

            $tickets = Ticket::with('product')->where('status', 1)
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date);

            if ($status == 0) {
                $tickets = $tickets->where('is_used', 0);
            } elseif ($status == 1) {
                $tickets = $tickets->where('is_used', 1);
            }
            $tickets =  $tickets->orderBy('created_at', 'desc')->get();

            return view('report.ticket-purchase', compact('start_date', 'end_date', 'tickets', 'all_permission', 'status'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function contestantRanking() {

        $general_setting = GeneralSetting::latest()->first();
        $judges_percentage = $general_setting->judge_percentage;
        $ambassadors_percentage = $general_setting->ambassador_percentage;
        $vote_percentage = $general_setting->vote_percentage;

        $maxVotes = DB::table('votes')
            ->where('status', 1)
            ->selectRaw('SUM(vote) as total_votes, musician_id')
            ->groupBy('musician_id')
            ->orderByDesc('total_votes')
            ->limit(1)
            ->value('total_votes');

        $judges_count = Judge::where('is_active', true)->count();

        $contestants = DB::table('employees')->where('is_active', true)->where('is_approve', true)
            ->leftJoin('votes', function($join) {
                $join->on('votes.musician_id', '=', 'employees.id')
                    ->where('votes.status', 1);
            })
            ->leftJoin(DB::raw('(SELECT candidate_id, SUM(total) as total_points FROM points GROUP BY candidate_id) as p'), 'employees.id', '=', 'p.candidate_id')
            ->leftJoin(DB::raw('(SELECT candidate_id, SUM(points) as total_ambassador_points FROM ambassador_points GROUP BY candidate_id) as ap'), 'employees.id', '=', 'ap.candidate_id')
            ->select(
                'employees.id',
                'employees.name',
                DB::raw('COALESCE(p.total_points, 0) as total_points'),
                DB::raw('COALESCE(ap.total_ambassador_points, 0) as total_ambassador_points'),
                DB::raw('SUM(votes.vote) as total_votes')
            )
            ->groupBy('employees.id', 'employees.name', 'p.total_points', 'ap.total_ambassador_points')
            ->get()
            ->map(function ($row) use ($maxVotes, $judges_count, $judges_percentage, $vote_percentage) {
                $score_points = ($row->total_points/$judges_count * $judges_percentage/100);
                $score_ambassador = $row->total_ambassador_points;
                $score_votes = $maxVotes > 0 ? (($row->total_votes / $maxVotes) * $vote_percentage) : 0;

                $row->final_score = $score_points + $score_ambassador + $score_votes;
                return $row;
            })
            ->sortByDesc('final_score')
            ->values();

        return view('report.ranking', compact('contestants'));
    }

    public function qualifiedContestantRanking() {

        $general_setting = GeneralSetting::latest()->first();
        $judges_percentage = $general_setting->judge_percentage;
        $ambassadors_percentage = $general_setting->ambassador_percentage;
        $vote_percentage = $general_setting->vote_percentage;

        $maxVotes = DB::table('votes')
            ->where('status', 1)
            ->selectRaw('SUM(vote) as total_votes, musician_id')
            ->groupBy('musician_id')
            ->orderByDesc('total_votes')
            ->limit(1)
            ->value('total_votes');

        $judges_count = Judge::where('is_active', true)->count();

        $contestants = DB::table('employees')->where('is_active', true)->where('is_approve', true)->where('is_eliminate', false)
            ->leftJoin('votes', function($join) {
                $join->on('votes.musician_id', '=', 'employees.id')
                    ->where('votes.status', 1);
            })
            ->leftJoin(DB::raw('(SELECT candidate_id, SUM(total) as total_points FROM points GROUP BY candidate_id) as p'), 'employees.id', '=', 'p.candidate_id')
            ->leftJoin(DB::raw('(SELECT candidate_id, SUM(points) as total_ambassador_points FROM ambassador_points GROUP BY candidate_id) as ap'), 'employees.id', '=', 'ap.candidate_id')
            ->select(
                'employees.id',
                'employees.name',
                DB::raw('COALESCE(p.total_points, 0) as total_points'),
                DB::raw('COALESCE(ap.total_ambassador_points, 0) as total_ambassador_points'),
                DB::raw('SUM(votes.vote) as total_votes')
            )
            ->groupBy('employees.id', 'employees.name', 'p.total_points', 'ap.total_ambassador_points')
            ->get()
            ->map(function ($row) use ($maxVotes, $judges_count, $judges_percentage, $vote_percentage) {
                $score_points = ($row->total_points/$judges_count * $judges_percentage/100);
                $score_ambassador = $row->total_ambassador_points;
                $score_votes = $maxVotes > 0 ? (($row->total_votes / $maxVotes) * $vote_percentage) : 0;

                $row->final_score = $score_points + $score_ambassador + $score_votes;
                return $row;
            })
            ->sortByDesc('final_score')
            ->values();

//        $contestants = $contestants->slice(0, $contestants->count() - $general_setting->number_of_elimination);

        return view('report.qualified', compact('contestants'));
    }

    public function eliminatedContestantRanking() {

        $general_setting = GeneralSetting::latest()->first();
        $judges_percentage = $general_setting->judge_percentage;
        $ambassadors_percentage = $general_setting->ambassador_percentage;
        $vote_percentage = $general_setting->vote_percentage;

        $maxVotes = DB::table('votes')
            ->where('status', 1)
            ->selectRaw('SUM(vote) as total_votes, musician_id')
            ->groupBy('musician_id')
            ->orderByDesc('total_votes')
            ->limit(1)
            ->value('total_votes');

        $judges_count = Judge::where('is_active', true)->count();

        $contestants = DB::table('employees')->where('is_active', true)->where('is_approve', true)->where('is_eliminate', true)
            ->leftJoin('votes', function($join) {
                $join->on('votes.musician_id', '=', 'employees.id')
                    ->where('votes.status', 1);
            })
            ->leftJoin(DB::raw('(SELECT candidate_id, SUM(total) as total_points FROM points GROUP BY candidate_id) as p'), 'employees.id', '=', 'p.candidate_id')
            ->leftJoin(DB::raw('(SELECT candidate_id, SUM(points) as total_ambassador_points FROM ambassador_points GROUP BY candidate_id) as ap'), 'employees.id', '=', 'ap.candidate_id')
            ->select(
                'employees.id',
                'employees.name',
                DB::raw('COALESCE(p.total_points, 0) as total_points'),
                DB::raw('COALESCE(ap.total_ambassador_points, 0) as total_ambassador_points'),
                DB::raw('SUM(votes.vote) as total_votes')
            )
            ->groupBy('employees.id', 'employees.name', 'p.total_points', 'ap.total_ambassador_points')
            ->get()
            ->map(function ($row) use ($maxVotes, $judges_count, $judges_percentage, $vote_percentage) {
                $score_points = ($row->total_points/$judges_count * $judges_percentage/100);
                $score_ambassador = $row->total_ambassador_points;
                $score_votes = $maxVotes > 0 ? (($row->total_votes / $maxVotes) * $vote_percentage) : 0;

                $row->final_score = $score_points + $score_ambassador + $score_votes;
                return $row;
            })
            ->sortByDesc('final_score')
            ->values();

//        $contestants = $contestants->slice(-$general_setting->number_of_elimination);

        return view('report.eliminated', compact('contestants'));
    }

    public function eliminateContestants()
    {
        $general_setting = GeneralSetting::latest()->first();
        $judges_percentage = $general_setting->judge_percentage;
        $ambassadors_percentage = $general_setting->ambassador_percentage;
        $vote_percentage = $general_setting->vote_percentage;

        $maxVotes = DB::table('votes')
            ->where('status', 1)
            ->selectRaw('SUM(vote) as total_votes, musician_id')
            ->groupBy('musician_id')
            ->orderByDesc('total_votes')
            ->limit(1)
            ->value('total_votes');

        $judges_count = Judge::where('is_active', true)->count();

        $contestants = DB::table('employees')->where('is_active', true)->where('is_approve', true)
            ->leftJoin('votes', function($join) {
                $join->on('votes.musician_id', '=', 'employees.id')
                    ->where('votes.status', 1);
            })
            ->leftJoin(DB::raw('(SELECT candidate_id, SUM(total) as total_points FROM points GROUP BY candidate_id) as p'), 'employees.id', '=', 'p.candidate_id')
            ->leftJoin(DB::raw('(SELECT candidate_id, SUM(points) as total_ambassador_points FROM ambassador_points GROUP BY candidate_id) as ap'), 'employees.id', '=', 'ap.candidate_id')
            ->select(
                'employees.id',
                'employees.name',
                DB::raw('COALESCE(p.total_points, 0) as total_points'),
                DB::raw('COALESCE(ap.total_ambassador_points, 0) as total_ambassador_points'),
                DB::raw('SUM(votes.vote) as total_votes')
            )
            ->groupBy('employees.id', 'employees.name', 'p.total_points', 'ap.total_ambassador_points')
            ->get()
            ->map(function ($row) use ($maxVotes, $judges_count, $judges_percentage, $vote_percentage) {
                $score_points = ($row->total_points/$judges_count * $judges_percentage/100);
                $score_ambassador = $row->total_ambassador_points;
                $score_votes = $maxVotes > 0 ? (($row->total_votes / $maxVotes) * $vote_percentage) : 0;

                $row->final_score = $score_points + $score_ambassador + $score_votes;
                return $row;
            })
            ->sortByDesc('final_score')
            ->values();

        $contestants = $contestants->slice(-$general_setting->number_of_elimination);
        Employee::where('id', '>', 0)->update(['is_eliminate' => false]);
        if (!$contestants->isEmpty()) {
            foreach ($contestants as $contestant) {
                Employee::where('id', $contestant->id)->update(['is_eliminate' => true]);
            }
        };

        return redirect()->route('report.contestant.eliminated')->with('message', 'Eliminated contestant list is ready');
    }

}

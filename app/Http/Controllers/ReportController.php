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
use App\Department;
use App\Helpers\ReportPeriod;
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

    public function reportCentre()
    {
        if (!$this->canAccessReports()) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        return view('report.centre');
    }

    public function votesByRegionReport(Request $request)
    {
        if (!$this->canAccessReports()) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        list($start_date, $end_date, $period) = ReportPeriod::resolve($request);
        $department_id = $request->input('department_id');

        $query = DB::table('votes')
            ->join('employees', 'employees.id', '=', 'votes.musician_id')
            ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
            ->where('votes.status', true)
            ->whereDate('votes.created_at', '>=', $start_date)
            ->whereDate('votes.created_at', '<=', $end_date);

        if ($department_id) {
            $query->where('employees.department_id', $department_id);
        }

        $rows = $query
            ->select(
                DB::raw('COALESCE(departments.name, "Unassigned") as region'),
                DB::raw('SUM(votes.vote) as total_votes'),
                DB::raw('COUNT(DISTINCT votes.musician_id) as contestants'),
                DB::raw('COALESCE(SUM(votes.grand_total), 0) as revenue')
            )
            ->groupBy('region')
            ->orderByDesc('total_votes')
            ->get();

        $contestantRows = DB::table('votes')
            ->join('employees', 'employees.id', '=', 'votes.musician_id')
            ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
            ->where('votes.status', true)
            ->whereDate('votes.created_at', '>=', $start_date)
            ->whereDate('votes.created_at', '<=', $end_date)
            ->when($department_id, function ($q) use ($department_id) {
                $q->where('employees.department_id', $department_id);
            })
            ->select(
                'employees.name as contestant',
                DB::raw('COALESCE(departments.name, "Unassigned") as region'),
                DB::raw('SUM(votes.vote) as total_votes')
            )
            ->groupBy('employees.id', 'employees.name', 'region')
            ->orderByDesc('total_votes')
            ->get();

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        if ($request->input('export') === 'csv') {
            return $this->csvDownload('votes-by-region.csv', ['Region', 'Total Votes', 'Contestants', 'Revenue'], $rows->map(function ($r) {
                return [$r->region, $r->total_votes, $r->contestants, $r->revenue];
            }));
        }
        if ($request->input('export') === 'pdf') {
            return $this->pdfDownload('report.exports.table', [
                'title' => 'Votes by Region',
                'headers' => ['Region', 'Total Votes', 'Contestants', 'Revenue'],
                'rows' => $rows,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ], 'votes-by-region.pdf');
        }

        return view('report.votes-by-region', compact('rows', 'contestantRows', 'start_date', 'end_date', 'period', 'departments', 'department_id'));
    }

    public function ticketSalesSummaryReport(Request $request)
    {
        if (!$this->canAccessReports()) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        list($start_date, $end_date, $period) = ReportPeriod::resolve($request);
        $category_id = $request->input('category_id');

        $query = DB::table('tickets')
            ->join('products', 'products.id', '=', 'tickets.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->where('tickets.status', 1)
            ->whereDate('tickets.created_at', '>=', $start_date)
            ->whereDate('tickets.created_at', '<=', $end_date);

        if ($category_id) {
            $query->where('products.category_id', $category_id);
        }

        $rows = (clone $query)
            ->select(
                DB::raw('COALESCE(categories.name, "General") as event'),
                DB::raw('SUM(tickets.qty) as tickets_sold'),
                DB::raw('COALESCE(SUM(tickets.total_amount), SUM(tickets.qty * COALESCE(tickets.price, products.price, 0)), 0) as revenue')
            )
            ->groupBy('event')
            ->orderByDesc('tickets_sold')
            ->get();

        $totals = (clone $query)->selectRaw('SUM(tickets.qty) as tickets_sold, COALESCE(SUM(tickets.total_amount), 0) as revenue')->first();
        $events = Category::where('is_active', true)->orderBy('name')->get();

        if ($request->input('export') === 'csv') {
            return $this->csvDownload('ticket-sales.csv', ['Event', 'Tickets Sold', 'Revenue'], $rows->map(function ($r) {
                return [$r->event, $r->tickets_sold, $r->revenue];
            }));
        }
        if ($request->input('export') === 'pdf') {
            return $this->pdfDownload('report.exports.table', [
                'title' => 'Ticket Sales',
                'headers' => ['Event', 'Tickets Sold', 'Revenue'],
                'rows' => $rows,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ], 'ticket-sales.pdf');
        }

        return view('report.ticket-sales-summary', compact('rows', 'totals', 'start_date', 'end_date', 'period', 'events', 'category_id'));
    }

    public function contestantsListReport(Request $request)
    {
        if (!$this->canAccessReports()) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        $department_id = $request->input('department_id');
        $rows = Employee::with('departments')
            ->where('is_active', true)
            ->when($department_id, function ($q) use ($department_id) {
                $q->where('department_id', $department_id);
            })
            ->orderBy('name')
            ->get()
            ->map(function ($e) {
                $votes = DB::table('votes')->where('musician_id', $e->id)->where('status', true)->sum('vote');
                return (object) [
                    'name' => $e->name,
                    'region' => optional($e->departments)->name ?? 'Unassigned',
                    'email' => $e->email,
                    'phone' => $e->phone_number,
                    'approved' => $e->is_approve ? 'Yes' : 'No',
                    'total_votes' => (int) $votes,
                ];
            });

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        if ($request->input('export') === 'csv') {
            return $this->csvDownload('contestants.csv', ['Name', 'Region', 'Email', 'Phone', 'Approved', 'Total Votes'], $rows->map(function ($r) {
                return [$r->name, $r->region, $r->email, $r->phone, $r->approved, $r->total_votes];
            }));
        }
        if ($request->input('export') === 'pdf') {
            return $this->pdfDownload('report.exports.table', [
                'title' => 'Contestants List',
                'headers' => ['Name', 'Region', 'Email', 'Phone', 'Approved', 'Votes'],
                'rows' => $rows,
            ], 'contestants.pdf');
        }

        return view('report.contestants-list', compact('rows', 'departments', 'department_id'));
    }

    public function incomeExpenseReport(Request $request)
    {
        if (!$this->canAccessReports()) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        list($start_date, $end_date, $period) = ReportPeriod::resolve($request);
        $department_id = $request->input('department_id');

        $voteIncomeQuery = DB::table('votes')
            ->join('employees', 'employees.id', '=', 'votes.musician_id')
            ->where('votes.status', true)
            ->whereDate('votes.created_at', '>=', $start_date)
            ->whereDate('votes.created_at', '<=', $end_date);
        if ($department_id) {
            $voteIncomeQuery->where('employees.department_id', $department_id);
        }
        $voteIncome = (float) $voteIncomeQuery->sum('grand_total');

        $ticketIncome = (float) DB::table('tickets')
            ->where('status', 1)
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->sum('total_amount');

        $expenses = (float) Expense::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->sum('amount');

        $incomeRows = collect([
            (object) ['source' => 'Vote payments', 'amount' => $voteIncome],
            (object) ['source' => 'Ticket sales', 'amount' => $ticketIncome],
        ]);

        $expenseRows = Expense::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->orderByDesc('created_at')
            ->get(['reference_no', 'amount', 'note', 'created_at']);

        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $totalIncome = $voteIncome + $ticketIncome;
        $net = $totalIncome - $expenses;

        if ($request->input('export') === 'csv') {
            $csvRows = $incomeRows->map(function ($r) {
                return ['Income: ' . $r->source, $r->amount];
            })->merge($expenseRows->map(function ($r) {
                return ['Expense: ' . ($r->reference_no ?: $r->note), $r->amount];
            }));
            return $this->csvDownload('income-expense.csv', ['Item', 'Amount'], $csvRows);
        }
        if ($request->input('export') === 'pdf') {
            return $this->pdfDownload('report.income-expense-pdf', compact(
                'incomeRows', 'expenseRows', 'totalIncome', 'expenses', 'net', 'start_date', 'end_date'
            ), 'income-expense.pdf');
        }

        return view('report.income-expense', compact(
            'incomeRows', 'expenseRows', 'totalIncome', 'expenses', 'net',
            'start_date', 'end_date', 'period', 'departments', 'department_id'
        ));
    }

    private function canAccessReports()
    {
        $role = Role::find(Auth::user()->role_id);
        return $role && $role->hasPermissionTo('vote-report');
    }

    private function csvDownload($filename, array $headers, $rows)
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($rows as $row) {
                fputcsv($out, is_array($row) ? $row : (array) $row);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function pdfDownload($view, array $data, $filename)
    {
        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return redirect()->back()->with('not_permitted', 'PDF export is not available.');
        }
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, $data)->setPaper('a4', 'landscape');
        return $pdf->download($filename);
    }

}

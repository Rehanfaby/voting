<?php

namespace App\Http\Controllers;

use App\Booking;
use App\BookingProduct;
use App\Category;
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

}

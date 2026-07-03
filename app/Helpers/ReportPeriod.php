<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportPeriod
{
    /** @return array{0: string, 1: string, 2: string} [start_date, end_date, label] */
    public static function resolve(Request $request, $defaultPeriod = 'month')
    {
        $period = $request->input('period', $defaultPeriod);

        if ($period === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->input('start_date'))->startOfDay();
            $end = Carbon::parse($request->input('end_date'))->endOfDay();

            return [$start->toDateString(), $end->toDateString(), 'custom'];
        }

        switch ($period) {
            case 'week':
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
                break;
            case 'year':
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
                break;
            case 'month':
            default:
                $period = 'month';
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
        }

        return [$start->toDateString(), $end->toDateString(), $period];
    }

    public static function periodOptions()
    {
        return [
            'week' => 'This Week',
            'month' => 'This Month',
            'year' => 'This Year',
            'custom' => 'Custom Range',
        ];
    }
}

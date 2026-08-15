@extends('layout.main')
@section('content')

@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif

@php
    /* Defensive stat collection — the dashboard must never break the page. */
    $__safe = function (callable $cb, $fallback = 0) {
        try { $v = $cb(); return is_null($v) ? $fallback : $v; }
        catch (\Throwable $e) { return $fallback; }
    };

    $roleNameLower = strtolower((string) (
        optional(Auth::user()->role)->name
        ?: optional(\DB::table('roles')->find(Auth::user()->role_id))->name
        ?: ''
    ));
    $isGraderDashboard = in_array($roleNameLower, ['judge', 'ambassador'], true);

    $contestantCounts = \Cache::remember('dash_contestant_counts_v1', 120, function () use ($__safe) {
        return [
            'total' => (int) $__safe(function () { return \App\Employee::publiclyListed()->count(); }),
            'pending' => (int) $__safe(function () { return \App\Employee::where('is_active', true)->where('is_approve', false)->count(); }),
        ];
    });
    $totalContestants   = (int) ($contestantCounts['total'] ?? 0);
    $pendingContestants = (int) ($contestantCounts['pending'] ?? 0);

    $gradedByMe = 0;
    $pendingGrading = 0;
    if ($isGraderDashboard) {
        $uid = (int) Auth::id();
        $awaitingRoute = $roleNameLower === 'ambassador'
            ? route('ambassador_points.awaiting_candidates')
            : route('points.awaiting_candidates');
        $listingRoute = $roleNameLower === 'ambassador'
            ? route('ambassador_points.index')
            : route('points.index');

        $graderStats = \Cache::remember('grader_dash_stats_' . $roleNameLower . '_' . $uid, 60, function () use ($__safe, $uid, $roleNameLower, $totalContestants) {
            if ($roleNameLower === 'ambassador') {
                $graded = (int) $__safe(function () use ($uid) {
                    return \App\Employee::where('is_active', true)
                        ->where('is_approve', true)
                        ->whereIn('id', function ($q) use ($uid) {
                            $q->select('candidate_id')
                                ->from('ambassador_points')
                                ->where('ambassador_id', $uid);
                        })
                        ->count();
                });
            } else {
                $graded = (int) $__safe(function () use ($uid) {
                    return \App\Employee::where('is_active', true)
                        ->where('is_approve', true)
                        ->whereIn('id', function ($q) use ($uid) {
                            $q->select('candidate_id')
                                ->from('points')
                                ->where('judge_id', $uid);
                        })
                        ->count();
                });
            }

            return [
                'graded' => $graded,
                'pending' => max(0, (int) $totalContestants - $graded),
            ];
        });
        $gradedByMe = (int) ($graderStats['graded'] ?? 0);
        $pendingGrading = (int) ($graderStats['pending'] ?? 0);
    }
@endphp

@if($isGraderDashboard)
{{-- Judge / Ambassador: grading-only dashboard (no voting stats) --}}
<div class="row">
  <div class="container-fluid">
    <div class="ms-dash-head">
      <h2>{{trans('file.welcome')}}, {{ ucfirst(Auth::user()->name) }}</h2>
      <p>{{ \Carbon\Carbon::now()->format('l, F j, Y') }} — {{ $roleNameLower === 'ambassador' ? 'Ambassador' : 'Judge' }} grading overview</p>
    </div>

    <div class="ms-stat-grid mg-grader-stats">
      <a href="{{ $awaitingRoute }}" class="ms-stat" style="--ms-accent:#f59e0b;">
        <div class="ms-stat-icon"><i class="fa fa-hourglass-half"></i></div>
        <div class="ms-stat-body">
          <div class="ms-stat-value">{{ number_format($pendingGrading) }}</div>
          <div class="ms-stat-label">Pending Grading</div>
        </div>
      </a>
      <a href="{{ $listingRoute }}" class="ms-stat" style="--ms-accent:#16a34a;">
        <div class="ms-stat-icon"><i class="fa fa-check-circle"></i></div>
        <div class="ms-stat-body">
          <div class="ms-stat-value">{{ number_format($gradedByMe) }}</div>
          <div class="ms-stat-label">Number Graded</div>
        </div>
      </a>
      <div class="ms-stat" style="--ms-accent:#1d4ed8;">
        <div class="ms-stat-icon"><i class="fa fa-microphone"></i></div>
        <div class="ms-stat-body">
          <div class="ms-stat-value">{{ number_format($totalContestants) }}</div>
          <div class="ms-stat-label">Number of Contestants</div>
        </div>
      </div>
    </div>

  </div>
</div>
<style>
.mg-grader-stats { grid-template-columns: 1fr; max-width: 920px; }
@media (min-width: 768px) { .mg-grader-stats { grid-template-columns: repeat(3, 1fr); } }
</style>
@else
@php
    /* Admin / staff dashboard — cached ~2 min; v2 uses SQL aggregates (no full vote dump). */
    $__dash = \Cache::remember('admin_dashboard_stats_v2', 120, function () use ($__safe, $totalContestants) {
        $byStatus = $__safe(function () {
            return \DB::table('votes')
                ->select('status', \DB::raw('COUNT(*) as txns'), \DB::raw('COALESCE(SUM(vote),0) as units'))
                ->groupBy('status')
                ->get()
                ->keyBy('status');
        }, collect());
        $voteTxns = (int) optional($byStatus->get(1))->txns;
        $voteUnitsSuccess = (int) optional($byStatus->get(1))->units;
        $failedTxns = (int) optional($byStatus->get(2))->txns;
        $voteUnitsFailed = (int) optional($byStatus->get(2))->units;
        $pendingTxns = (int) optional($byStatus->get(0))->txns;
        $voteUnitsPending = (int) optional($byStatus->get(0))->units;
        $totalVoters = (int) $__safe(function () {
            return \DB::table('votes')->where('status', 1)->distinct('user_id')->count('user_id');
        });

        $totalJudges = (int) $__safe(function () { return \App\Judge::where('is_active', true)->count(); });
        $totalAmbassadors = (int) $__safe(function () {
            $q = \App\Ambassador::query();
            if (\Schema::hasColumn('ambassadors', 'is_active')) {
                $q->where('is_active', true);
            }
            return $q->count();
        });

        $trendRaw = $__safe(function () {
            return \DB::table('votes')
                ->where('created_at', '>=', \Carbon\Carbon::now()->subDays(13)->startOfDay())
                ->select('status', \DB::raw('DATE(created_at) as d'), \DB::raw('SUM(vote) as t'))
                ->groupBy('status', 'd')
                ->get();
        }, collect());
        $trendMap = [];
        foreach ($trendRaw as $row) {
            $trendMap[(int) $row->status][$row->d] = (int) $row->t;
        }
        $trendLabels = []; $trendSuccess = []; $trendFailed = []; $trendPending = [];
        for ($i = 13; $i >= 0; $i--) {
            $day = \Carbon\Carbon::now()->subDays($i);
            $key = $day->format('Y-m-d');
            $trendLabels[] = $day->format('M j');
            $trendSuccess[] = (int) ($trendMap[1][$key] ?? 0);
            $trendFailed[] = (int) ($trendMap[2][$key] ?? 0);
            $trendPending[] = (int) ($trendMap[0][$key] ?? 0);
        }

        $topRows = $__safe(function () {
            return \DB::table('votes')
                ->join('employees', 'employees.id', '=', 'votes.musician_id')
                ->where('votes.status', 1)
                ->where('employees.is_active', true)
                ->select('employees.name', \DB::raw('SUM(votes.vote) as t'))
                ->groupBy('employees.name')->orderByDesc('t')->limit(5)->get();
        }, collect());

        $regionRows = $__safe(function () {
            return \DB::table('employees')
                ->where('is_active', true)->where('is_approve', true)
                ->select(\DB::raw("COALESCE(NULLIF(TRIM(city), ''), 'Unassigned') as name"), \DB::raw('COUNT(*) as c'))
                ->groupBy(\DB::raw("COALESCE(NULLIF(TRIM(city), ''), 'Unassigned')"))
                ->orderByDesc('c')
                ->get();
        }, collect());

        $votesByRegion = $__safe(function () {
            return \DB::table('votes')
                ->join('employees', 'employees.id', '=', 'votes.musician_id')
                ->where('votes.status', 1)
                ->where('employees.is_active', true)
                ->select(\DB::raw("COALESCE(NULLIF(TRIM(employees.city), ''), 'Unassigned') as name"), \DB::raw('SUM(votes.vote) as t'))
                ->groupBy(\DB::raw("COALESCE(NULLIF(TRIM(employees.city), ''), 'Unassigned')"))
                ->orderByDesc('t')
                ->get();
        }, collect());

        $totalTicketsSold = (int) $__safe(function () {
            return \DB::table('tickets')
                ->join('products', 'products.id', '=', 'tickets.product_id')
                ->where('tickets.status', 1)
                ->where('products.is_active', 1)
                ->sum('tickets.qty');
        });
        $ticketsByEvent = $__safe(function () {
            return \DB::table('tickets')
                ->join('products', 'products.id', '=', 'tickets.product_id')
                ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
                ->where('tickets.status', 1)
                ->where('products.is_active', 1)
                ->select(\DB::raw('COALESCE(NULLIF(products.name, ""), COALESCE(categories.name, "General")) as name'), \DB::raw('SUM(tickets.qty) as c'))
                ->groupBy(\DB::raw('COALESCE(NULLIF(products.name, ""), COALESCE(categories.name, "General"))'))
                ->orderByDesc('c')
                ->limit(8)
                ->get();
        }, collect());

        $paymentMethodStats = $__safe(function () {
            $counts = ['momo' => 0, 'om' => 0, 'card' => 0];
            $units = ['momo' => 0, 'om' => 0, 'card' => 0];
            $hasMethodCol = \Schema::hasColumn('votes', 'payment_method');
            if ($hasMethodCol) {
                $rows = \DB::table('votes')
                    ->where('status', 1)
                    ->select(\DB::raw('LOWER(TRIM(COALESCE(payment_method, ""))) as method'), \DB::raw('COUNT(*) as txns'), \DB::raw('COALESCE(SUM(vote),0) as units'))
                    ->groupBy(\DB::raw('LOWER(TRIM(COALESCE(payment_method, "")))'))
                    ->get();
                foreach ($rows as $row) {
                    $method = (string) $row->method;
                    if (!in_array($method, ['momo', 'om', 'card'], true)) {
                        continue;
                    }
                    $counts[$method] += (int) $row->txns;
                    $units[$method] += (int) $row->units;
                }
            } else {
                // Fallback without loading every row: classify via payment_provider / reference prefixes in SQL.
                $hasProviderCol = \Schema::hasColumn('votes', 'payment_provider');
                $cardExpr = $hasProviderCol
                    ? "LOWER(COALESCE(payment_provider,'')) = 'stripe' OR reference LIKE 'pi_%'"
                    : "reference LIKE 'pi_%'";
                $counts['card'] = (int) \DB::table('votes')->where('status', 1)->whereRaw("($cardExpr)")->count();
                $units['card'] = (int) \DB::table('votes')->where('status', 1)->whereRaw("($cardExpr)")->sum('vote');
                $rest = (int) \DB::table('votes')->where('status', 1)->whereRaw("NOT ($cardExpr)")->count();
                $restUnits = (int) \DB::table('votes')->where('status', 1)->whereRaw("NOT ($cardExpr)")->sum('vote');
                // Without method column, attribute remaining successful votes to MoMo (dominant channel).
                $counts['momo'] = $rest;
                $units['momo'] = $restUnits;
            }

            return [
                'labels' => ['MTN MoMo', 'Orange Money', 'Visa / Mastercard'],
                'keys' => ['momo', 'om', 'card'],
                'txns' => [(int) $counts['momo'], (int) $counts['om'], (int) $counts['card']],
                'units' => [(int) $units['momo'], (int) $units['om'], (int) $units['card']],
            ];
        }, [
            'labels' => ['MTN MoMo', 'Orange Money', 'Visa / Mastercard'],
            'keys' => ['momo', 'om', 'card'],
            'txns' => [0, 0, 0],
            'units' => [0, 0, 0],
        ]);

        return [
            'voteTxns' => $voteTxns,
            'voteUnitsSuccess' => $voteUnitsSuccess,
            'totalVoters' => $totalVoters,
            'failedTxns' => $failedTxns,
            'voteUnitsFailed' => $voteUnitsFailed,
            'pendingTxns' => $pendingTxns,
            'voteUnitsPending' => $voteUnitsPending,
            'totalVoteTxns' => $voteTxns + $pendingTxns + $failedTxns,
            'totalVoteUnits' => $voteUnitsSuccess + $voteUnitsPending + $voteUnitsFailed,
            'totalVotes' => $voteUnitsSuccess,
            'failedVotes' => $failedTxns,
            'pendingVotes' => $pendingTxns,
            'totalJudges' => $totalJudges,
            'totalAmbassadors' => $totalAmbassadors,
            'trendLabels' => $trendLabels,
            'trendSuccess' => $trendSuccess,
            'trendFailed' => $trendFailed,
            'trendPending' => $trendPending,
            'topRows' => $topRows,
            'regionContestantLabels' => $regionRows->pluck('name')->values()->all(),
            'regionContestantData' => $regionRows->pluck('c')->map(function ($v) { return (int) $v; })->values()->all(),
            'regionVoteLabels' => $votesByRegion->pluck('name')->values()->all(),
            'regionVoteData' => $votesByRegion->pluck('t')->map(function ($v) { return (int) $v; })->values()->all(),
            'voteStatusLabels' => ['Succeeded', 'Failed', 'Pending'],
            'voteStatusData' => [$voteTxns, $failedTxns, $pendingTxns],
            'totalTicketsSold' => $totalTicketsSold,
            'ticketsByEvent' => $ticketsByEvent,
            'payMethodLabels' => $paymentMethodStats['labels'],
            'payMethodTxns' => $paymentMethodStats['txns'],
            'payMethodUnits' => $paymentMethodStats['units'],
            'payMethodTotalTxns' => array_sum($paymentMethodStats['txns']),
            'totalContestantsCached' => $totalContestants,
        ];
    });

    $voteTxns = $__dash['voteTxns'];
    $voteUnitsSuccess = $__dash['voteUnitsSuccess'];
    $totalVoters = $__dash['totalVoters'];
    $failedTxns = $__dash['failedTxns'];
    $voteUnitsFailed = $__dash['voteUnitsFailed'];
    $pendingTxns = $__dash['pendingTxns'];
    $voteUnitsPending = $__dash['voteUnitsPending'];
    $totalVoteTxns = $__dash['totalVoteTxns'];
    $totalVoteUnits = $__dash['totalVoteUnits'];
    $totalVotes = $__dash['totalVotes'];
    $failedVotes = $__dash['failedVotes'];
    $pendingVotes = $__dash['pendingVotes'];
    $totalJudges = $__dash['totalJudges'];
    $totalAmbassadors = $__dash['totalAmbassadors'];
    $trendLabels = $__dash['trendLabels'];
    $trendSuccess = $__dash['trendSuccess'];
    $trendFailed = $__dash['trendFailed'];
    $trendPending = $__dash['trendPending'];
    $topRows = $__dash['topRows'];
    $regionContestantLabels = $__dash['regionContestantLabels'];
    $regionContestantData = $__dash['regionContestantData'];
    $regionVoteLabels = $__dash['regionVoteLabels'];
    $regionVoteData = $__dash['regionVoteData'];
    $voteStatusLabels = $__dash['voteStatusLabels'];
    $voteStatusData = $__dash['voteStatusData'];
    $totalTicketsSold = $__dash['totalTicketsSold'];
    $ticketsByEvent = $__dash['ticketsByEvent'];
    $payMethodLabels = $__dash['payMethodLabels'];
    $payMethodTxns = $__dash['payMethodTxns'];
    $payMethodUnits = $__dash['payMethodUnits'];
    $payMethodTotalTxns = $__dash['payMethodTotalTxns'];
@endphp

<div class="row">
  <div class="container-fluid">
    <div class="ms-dash-head">
      <h2>{{trans('file.welcome')}}, {{ ucfirst(Auth::user()->name) }}</h2>
      <p>{{ \Carbon\Carbon::now()->format('l, F j, Y') }} — {{ $general_setting->site_title ?? 'Mulema' }} overview</p>
      @if(Auth::user()->role_id == 2 && \App\Employee::where('user_id', Auth::user()->id)->value('is_approve') == false)
        <span class="alert alert-danger d-inline-block mt-2">{{ trans('file.your account is not approved yet, please contact to administrator to approve your account') }}</span>
      @endif
    </div>

    <div class="ms-stat-grid">
      <a href="{{ route('musician.index') }}" class="ms-stat" style="--ms-accent:#1d4ed8;">
        <div class="ms-stat-icon"><i class="fa fa-microphone"></i></div>
        <div class="ms-stat-body">
          <div class="ms-stat-value">{{ number_format($totalContestants) }}</div>
          <div class="ms-stat-label">Total Contestants</div>
        </div>
      </a>
      <a href="{{ route('votes.index', ['status' => 'all']) }}" class="ms-stat" style="--ms-accent:#16a34a;">
        <div class="ms-stat-icon"><i class="fa fa-check-square-o"></i></div>
        <div class="ms-stat-body">
          <div class="ms-stat-value">{{ number_format($totalVoteTxns) }}</div>
          <div class="ms-stat-label">Total Votes</div>
        </div>
      </a>
      <a href="{{ route('votes.index', ['status' => 'success']) }}" class="ms-stat" style="--ms-accent:#15803d;">
        <div class="ms-stat-icon"><i class="fa fa-thumbs-up"></i></div>
        <div class="ms-stat-body">
          <div class="ms-stat-value">{{ number_format($voteTxns) }}</div>
          <div class="ms-stat-label">Successful Votes</div>
        </div>
      </a>
      <a href="{{ route('votes.index', ['status' => 'failed']) }}" class="ms-stat" style="--ms-accent:#ef4444;">
        <div class="ms-stat-icon"><i class="fa fa-times-circle"></i></div>
        <div class="ms-stat-body">
          <div class="ms-stat-value">{{ number_format($failedTxns) }}</div>
          <div class="ms-stat-label">Votes Failed</div>
        </div>
      </a>
      <a href="{{ route('votes.index', ['status' => 'pending']) }}" class="ms-stat" style="--ms-accent:#f59e0b;">
        <div class="ms-stat-icon"><i class="fa fa-clock-o"></i></div>
        <div class="ms-stat-body">
          <div class="ms-stat-value">{{ number_format($pendingTxns) }}</div>
          <div class="ms-stat-label">Votes Pending</div>
        </div>
      </a>
      <a href="{{ route('voter.index') }}" class="ms-stat" style="--ms-accent:#f59e0b;">
        <div class="ms-stat-icon"><i class="fa fa-users"></i></div>
        <div class="ms-stat-body">
          <div class="ms-stat-value">{{ number_format($totalVoters) }}</div>
          <div class="ms-stat-label">Unique Voters</div>
        </div>
      </a>
      <a href="{{ route('judge.index') }}" class="ms-stat" style="--ms-accent:#a855f7;">
        <div class="ms-stat-icon"><i class="fa fa-podcast"></i></div>
        <div class="ms-stat-body">
          <div class="ms-stat-value">{{ number_format($totalJudges) }}</div>
          <div class="ms-stat-label">Judges</div>
        </div>
      </a>
      <a href="{{ route('ambassador.index') }}" class="ms-stat" style="--ms-accent:#0ea5e9;">
        <div class="ms-stat-icon"><i class="fa fa-bullhorn"></i></div>
        <div class="ms-stat-body">
          <div class="ms-stat-value">{{ number_format($totalAmbassadors) }}</div>
          <div class="ms-stat-label">Ambassadors</div>
        </div>
      </a>
      <a href="{{ route('musician.pending.index') }}" class="ms-stat" style="--ms-accent:#ef4444;">
        <div class="ms-stat-icon"><i class="fa fa-hourglass-half"></i></div>
        <div class="ms-stat-body">
          <div class="ms-stat-value">{{ number_format($pendingContestants) }}</div>
          <div class="ms-stat-label">Pending Approval</div>
        </div>
      </a>
      <a href="{{ route('report.ticket.sales') }}" class="ms-stat" style="--ms-accent:#0d9488;">
        <div class="ms-stat-icon"><i class="fa fa-ticket"></i></div>
        <div class="ms-stat-body">
          <div class="ms-stat-value">{{ number_format($totalTicketsSold) }}</div>
          <div class="ms-stat-label">Tickets Sold</div>
        </div>
      </a>
    </div>

    <div class="ms-chart-grid">
      <div class="ms-panel">
        <h3><i class="fa fa-line-chart"></i> Successful Votes — Last 14 Days <small class="text-muted" style="font-weight:600;font-size:13px;">({{ number_format($voteTxns) }} transactions)</small></h3>
        <canvas id="msVotesTrend" height="120"></canvas>
      </div>
      <div class="ms-panel">
        <h3><i class="fa fa-pie-chart"></i> Votes by Status</h3>
        <canvas id="msVoteStatusChart" height="200"></canvas>
      </div>
    </div>

    <div class="ms-chart-grid">
      <div class="ms-panel">
        <h3><i class="fa fa-line-chart"></i> Failed Votes — Last 14 Days <small class="text-muted" style="font-weight:600;font-size:13px;">({{ number_format($failedTxns) }} transactions)</small></h3>
        <canvas id="msVotesFailedTrend" height="120"></canvas>
      </div>
      <div class="ms-panel">
        <h3><i class="fa fa-line-chart"></i> Pending Votes — Last 14 Days <small class="text-muted" style="font-weight:600;font-size:13px;">({{ number_format($pendingTxns) }} transactions)</small></h3>
        <canvas id="msVotesPendingTrend" height="120"></canvas>
      </div>
    </div>

    <div class="ms-chart-grid">
      <div class="ms-panel">
        <h3><i class="fa fa-pie-chart"></i> Contestants by Region <small class="text-muted" style="font-weight:600;font-size:13px;">({{ number_format($totalContestants) }} total)</small></h3>
        @if(count($regionContestantLabels))
          <canvas id="msRegionChart" height="220"></canvas>
        @else
          <p class="text-muted mb-0">No region data yet. Set each contestant’s city/region.</p>
        @endif
      </div>
      <div class="ms-panel">
        <h3><i class="fa fa-bar-chart"></i> Contestants per Region</h3>
        @if(count($regionContestantLabels))
          <canvas id="msRegionBarChart" height="220"></canvas>
        @else
          <p class="text-muted mb-0">No contestants found.</p>
        @endif
      </div>
    </div>

    <div class="ms-chart-grid">
      <div class="ms-panel">
        <h3><i class="fa fa-bar-chart"></i> Votes per Region <small class="text-muted" style="font-weight:600;font-size:13px;">(successful votes for contestants in each region)</small></h3>
        @if(count($regionVoteLabels))
          <canvas id="msVotesRegionChart" height="220"></canvas>
        @else
          <p class="text-muted mb-0">No successful votes with region data yet.</p>
        @endif
      </div>
      <div class="ms-panel">
        <h3><i class="fa fa-pie-chart"></i> Votes Share by Region</h3>
        @if(count($regionVoteLabels))
          <canvas id="msVotesRegionPie" height="220"></canvas>
        @else
          <p class="text-muted mb-0">No successful votes with region data yet.</p>
        @endif
      </div>
    </div>

    <div class="ms-chart-grid">
      <div class="ms-panel">
        <h3><i class="fa fa-bar-chart"></i> Top Contestants by Votes</h3>
        <canvas id="msTopChart" height="130"></canvas>
      </div>
      <div class="ms-panel">
        <h3><i class="fa fa-trophy"></i> Leaderboard</h3>
        <ul class="ms-rank-list">
          @forelse($topRows as $i => $row)
            <li>
              <span class="ms-rank-pos">{{ $i + 1 }}</span>
              <span class="ms-rank-name">{{ $row->name }}</span>
              <span class="ms-rank-votes">{{ number_format($row->t) }}</span>
            </li>
          @empty
            <li><span class="ms-rank-name text-muted">No votes recorded yet.</span></li>
          @endforelse
        </ul>
      </div>
    </div>

    <div class="ms-chart-grid">
      <div class="ms-panel">
        <h3><i class="fa fa-pie-chart"></i> Votes by Payment Method <small class="text-muted" style="font-weight:600;font-size:13px;">({{ number_format($payMethodTotalTxns) }} successful transactions)</small></h3>
        @if($payMethodTotalTxns > 0)
          <canvas id="msPayMethodPie" height="220"></canvas>
        @else
          <p class="text-muted mb-0">No successful payments yet.</p>
        @endif
      </div>
      <div class="ms-panel">
        <h3><i class="fa fa-bar-chart"></i> Payment Methods <small class="text-muted" style="font-weight:600;font-size:13px;">(successful vote units)</small></h3>
        @if($payMethodTotalTxns > 0)
          <canvas id="msPayMethodBar" height="220"></canvas>
        @else
          <p class="text-muted mb-0">No successful payments yet.</p>
        @endif
      </div>
    </div>

    <div class="ms-chart-grid">
      <div class="ms-panel">
        <h3><i class="fa fa-ticket"></i> Tickets Sold by Event</h3>
        <canvas id="msTicketsEventChart" height="130"></canvas>
      </div>
    </div>

  </div>
</div>
@endif
@endsection

@section('scripts')
@if(empty($isGraderDashboard))
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script type="text/javascript">
(function () {
    if (typeof Chart === 'undefined') return;
    if (typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
    }
    Chart.defaults.font.family = "'Nunito','Segoe UI',system-ui,sans-serif";
    Chart.defaults.color = '#64748b';
    Chart.defaults.plugins = Chart.defaults.plugins || {};
    Chart.defaults.plugins.datalabels = { display: false };

    var BLUE = '#1d4ed8', YELLOW = '#f5c518', GREEN = '#16a34a', RED = '#ef4444', AMBER = '#f59e0b';
    var palette = ['#1d4ed8', '#f5c518', '#16a34a', '#a855f7', '#0ea5e9', '#ef4444', '#f59e0b', '#14b8a6', '#6366f1', '#db2777'];

    function fmtNum(v) {
        return Number(v || 0).toLocaleString();
    }
    function pieLabels(minPct) {
        minPct = minPct || 0;
        return {
            display: true,
            color: '#0f172a',
            font: { weight: '700', size: 11 },
            formatter: function (value, ctx) {
                var total = ctx.chart.data.datasets[0].data.reduce(function (a, b) { return a + b; }, 0);
                var pct = total ? (value / total) * 100 : 0;
                if (value <= 0 || pct < minPct) return '';
                return fmtNum(value);
            }
        };
    }
    function barTopLabels() {
        return {
            display: true,
            anchor: 'end',
            align: 'top',
            offset: 2,
            color: '#0f172a',
            font: { weight: '700', size: 11 },
            formatter: function (v) { return v > 0 ? fmtNum(v) : ''; }
        };
    }
    function barEndLabels() {
        return {
            display: true,
            anchor: 'end',
            align: 'right',
            offset: 6,
            color: '#0f172a',
            font: { weight: '700', size: 12 },
            formatter: function (v) { return v > 0 ? fmtNum(v) : ''; }
        };
    }

    function hexAlpha(h, a) {
        var c = h.replace('#','');
        if (c.length === 3) c = c[0]+c[0]+c[1]+c[1]+c[2]+c[2];
        var r = parseInt(c.slice(0,2),16), g = parseInt(c.slice(2,4),16), b = parseInt(c.slice(4,6),16);
        return 'rgba('+r+','+g+','+b+','+a+')';
    }
    function lineChart(elId, labels, data, color, label) {
        var el = document.getElementById(elId);
        if (!el) return;
        var ctx = el.getContext('2d');
        var grad = ctx.createLinearGradient(0, 0, 0, 260);
        grad.addColorStop(0, hexAlpha(color, 0.28));
        grad.addColorStop(1, hexAlpha(color, 0));
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    borderColor: color,
                    backgroundColor: grad,
                    borderWidth: 3,
                    fill: true,
                    tension: .38,
                    pointRadius: 3,
                    pointBackgroundColor: YELLOW,
                    pointBorderColor: color
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: true,
                plugins: { legend: { display: false }, datalabels: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#eef2f7' }, ticks: { precision: 0 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    var labels = @json($trendLabels);
    lineChart('msVotesTrend', labels, @json($trendSuccess), GREEN, 'Succeeded');
    lineChart('msVotesFailedTrend', labels, @json($trendFailed), RED, 'Failed');
    lineChart('msVotesPendingTrend', labels, @json($trendPending), AMBER, 'Pending');

    var statusEl = document.getElementById('msVoteStatusChart');
    if (statusEl) {
        new Chart(statusEl.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($voteStatusLabels),
                datasets: [{
                    data: @json($voteStatusData),
                    backgroundColor: [GREEN, RED, AMBER],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: true, cutout: '62%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } },
                    datalabels: pieLabels(0)
                }
            }
        });
    }

    function pctTooltip() {
        return {
            callbacks: {
                label: function (ctx) {
                    var total = ctx.dataset.data.reduce(function (a, b) { return a + b; }, 0);
                    var pct = total ? Math.round((ctx.raw / total) * 100) : 0;
                    return ctx.label + ': ' + fmtNum(ctx.raw) + ' (' + pct + '%)';
                }
            }
        };
    }

    var regionLabels = @json($regionContestantLabels);
    var regionCounts = @json($regionContestantData);
    var voteRegionLabels = @json($regionVoteLabels);
    var voteRegionCounts = @json($regionVoteData);

    var regionEl = document.getElementById('msRegionChart');
    if (regionEl && regionLabels.length) {
        new Chart(regionEl.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: regionLabels,
                datasets: [{ data: regionCounts, backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }]
            },
            options: {
                responsive: true, maintainAspectRatio: true, cutout: '55%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10 } },
                    tooltip: pctTooltip(),
                    datalabels: pieLabels(3)
                }
            }
        });
    }

    var regionBarEl = document.getElementById('msRegionBarChart');
    if (regionBarEl && regionLabels.length) {
        new Chart(regionBarEl.getContext('2d'), {
            type: 'bar',
            data: {
                labels: regionLabels,
                datasets: [{ label: 'Contestants', data: regionCounts, backgroundColor: BLUE, borderRadius: 8 }]
            },
            options: {
                responsive: true, maintainAspectRatio: true,
                layout: { padding: { top: 18 } },
                plugins: { legend: { display: false }, datalabels: barTopLabels() },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } }, x: { grid: { display: false } } }
            }
        });
    }

    var votesRegionEl = document.getElementById('msVotesRegionChart');
    if (votesRegionEl && voteRegionLabels.length) {
        new Chart(votesRegionEl.getContext('2d'), {
            type: 'bar',
            data: {
                labels: voteRegionLabels,
                datasets: [{ label: 'Successful votes', data: voteRegionCounts, backgroundColor: palette, borderRadius: 8 }]
            },
            options: {
                responsive: true, maintainAspectRatio: true,
                layout: { padding: { top: 22 } },
                plugins: { legend: { display: false }, datalabels: barTopLabels() },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } }, x: { grid: { display: false } } }
            }
        });
    }

    var votesRegionPieEl = document.getElementById('msVotesRegionPie');
    if (votesRegionPieEl && voteRegionLabels.length) {
        new Chart(votesRegionPieEl.getContext('2d'), {
            type: 'pie',
            data: {
                labels: voteRegionLabels,
                datasets: [{ data: voteRegionCounts, backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }]
            },
            options: {
                responsive: true, maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10 } },
                    tooltip: pctTooltip(),
                    datalabels: pieLabels(2)
                }
            }
        });
    }

    var topEl = document.getElementById('msTopChart');
    if (topEl) {
        new Chart(topEl.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($topRows->pluck('name')),
                datasets: [{ label: 'Votes', data: @json($topRows->pluck('t')), backgroundColor: BLUE, borderRadius: 8, maxBarThickness: 46 }]
            },
            options: {
                indexAxis: 'y',
                responsive: true, maintainAspectRatio: true,
                layout: { padding: { right: 36 } },
                plugins: { legend: { display: false }, datalabels: barEndLabels() },
                scales: {
                    x: { beginAtZero: true, grid: { color: '#eef2f7' }, ticks: { precision: 0 } },
                    y: { grid: { display: false } }
                }
            }
        });
    }

    var payLabels = @json($payMethodLabels);
    var payTxns = @json($payMethodTxns);
    var payUnits = @json($payMethodUnits);
    var payColors = ['#ffcc00', '#ff6600', '#1a1f71'];

    var payPieEl = document.getElementById('msPayMethodPie');
    if (payPieEl && payTxns.reduce(function (a, b) { return a + b; }, 0) > 0) {
        new Chart(payPieEl.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: payLabels,
                datasets: [{ data: payTxns, backgroundColor: payColors, borderWidth: 2, borderColor: '#fff' }]
            },
            options: {
                responsive: true, maintainAspectRatio: true, cutout: '55%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10 } },
                    tooltip: pctTooltip(),
                    datalabels: pieLabels(0)
                }
            }
        });
    }

    var payBarEl = document.getElementById('msPayMethodBar');
    if (payBarEl && payUnits.reduce(function (a, b) { return a + b; }, 0) > 0) {
        new Chart(payBarEl.getContext('2d'), {
            type: 'bar',
            data: {
                labels: payLabels,
                datasets: [{ label: 'Vote units', data: payUnits, backgroundColor: payColors, borderRadius: 8 }]
            },
            options: {
                responsive: true, maintainAspectRatio: true,
                layout: { padding: { top: 22 } },
                plugins: { legend: { display: false }, datalabels: barTopLabels() },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } }, x: { grid: { display: false } } }
            }
        });
    }

    var ticketsEventEl = document.getElementById('msTicketsEventChart');
    if (ticketsEventEl) {
        new Chart(ticketsEventEl.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($ticketsByEvent->pluck('name')),
                datasets: [{ label: 'Tickets', data: @json($ticketsByEvent->pluck('c')), backgroundColor: '#0d9488', borderRadius: 8 }]
            },
            options: {
                indexAxis: 'y',
                responsive: true, maintainAspectRatio: true,
                layout: { padding: { right: 28 } },
                plugins: { legend: { display: false }, datalabels: barEndLabels() },
                scales: { x: { beginAtZero: true, ticks: { precision: 0 } }, y: { grid: { display: false } } }
            }
        });
    }
})();
</script>
@endif
@endsection

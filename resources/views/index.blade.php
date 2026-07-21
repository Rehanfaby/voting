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

    $totalContestants   = (int) $__safe(function () { return \App\Employee::where('is_active', true)->where('is_approve', true)->count(); });
    $pendingContestants = (int) $__safe(function () { return \App\Employee::where('is_active', true)->where('is_approve', false)->count(); });

    // Votes on the dashboard = successful only (status = 1 / true).
    $totalVotes         = (int) $__safe(function () { return \DB::table('votes')->where('status', 1)->sum('vote'); });
    $voteTxns           = (int) $__safe(function () { return \DB::table('votes')->where('status', 1)->count(); });
    $totalVoters        = (int) $__safe(function () { return \DB::table('votes')->where('status', 1)->distinct('user_id')->count('user_id'); });
    $failedVotes        = (int) $__safe(function () { return \DB::table('votes')->where('status', 2)->sum('vote'); });
    $failedTxns         = (int) $__safe(function () { return \DB::table('votes')->where('status', 2)->count(); });
    $pendingVotes       = (int) $__safe(function () { return \DB::table('votes')->where('status', 0)->sum('vote'); });
    $pendingTxns        = (int) $__safe(function () { return \DB::table('votes')->where('status', 0)->count(); });

    // Active judges only (exclude soft-deleted ambassador rows still in judges table).
    $totalJudges        = (int) $__safe(function () { return \App\Judge::where('is_active', true)->count(); });
    $totalAmbassadors   = (int) $__safe(function () {
        $q = \App\Ambassador::query();
        if (\Schema::hasColumn('ambassadors', 'is_active')) {
            $q->where('is_active', true);
        }
        return $q->count();
    });

    $buildTrend = function ($status) use ($__safe) {
        $raw = $__safe(function () use ($status) {
            return \DB::table('votes')->where('status', $status)
                ->where('created_at', '>=', \Carbon\Carbon::now()->subDays(13)->startOfDay())
                ->select(\DB::raw('DATE(created_at) as d'), \DB::raw('SUM(vote) as t'))
                ->groupBy('d')->pluck('t', 'd');
        }, collect());
        $labels = []; $data = [];
        for ($i = 13; $i >= 0; $i--) {
            $day = \Carbon\Carbon::now()->subDays($i);
            $labels[] = $day->format('M j');
            $data[]   = (int) ($raw[$day->format('Y-m-d')] ?? 0);
        }
        return [$labels, $data];
    };

    list($trendLabels, $trendSuccess) = $buildTrend(1);
    list(, $trendFailed) = $buildTrend(2);
    list(, $trendPending) = $buildTrend(0);

    /* Top 5 contestants by successful votes */
    $topRows = $__safe(function () {
        return \DB::table('votes')
            ->join('employees', 'employees.id', '=', 'votes.musician_id')
            ->where('votes.status', 1)
            ->where('employees.is_active', true)
            ->select('employees.name', \DB::raw('SUM(votes.vote) as t'))
            ->groupBy('employees.name')->orderByDesc('t')->limit(5)->get();
    }, collect());

    /* Contestants per Cameroon region (stored in employees.city) */
    $regionRows = $__safe(function () {
        return \DB::table('employees')
            ->where('is_active', true)->where('is_approve', true)
            ->select(\DB::raw('COALESCE(NULLIF(TRIM(city), ""), "Unassigned") as name'), \DB::raw('COUNT(*) as c'))
            ->groupBy('name')->orderByDesc('c')->get();
    }, collect());

    /* Successful votes per region */
    $votesByRegion = $__safe(function () {
        return \DB::table('votes')
            ->join('employees', 'employees.id', '=', 'votes.musician_id')
            ->where('votes.status', 1)
            ->where('employees.is_active', true)
            ->select(\DB::raw('COALESCE(NULLIF(TRIM(employees.city), ""), "Unassigned") as name'), \DB::raw('SUM(votes.vote) as t'))
            ->groupBy('name')->orderByDesc('t')->get();
    }, collect());

    /* Contestant names per region (for elimination tracking) */
    $contestantsByRegionList = $__safe(function () {
        $rows = \DB::table('employees')
            ->where('is_active', true)->where('is_approve', true)
            ->orderBy('city')->orderBy('name')
            ->get(['id', 'name', 'city']);
        $grouped = [];
        foreach ($rows as $row) {
            $region = trim((string) $row->city) !== '' ? $row->city : 'Unassigned';
            $grouped[$region][] = $row->name;
        }
        ksort($grouped);
        return $grouped;
    }, []);

    $totalTicketsSold = (int) $__safe(function () {
        return \DB::table('tickets')->where('status', 1)->sum('qty');
    });
    $ticketsByEvent = $__safe(function () {
        return \DB::table('tickets')
            ->join('products', 'products.id', '=', 'tickets.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->where('tickets.status', 1)
            ->select(\DB::raw('COALESCE(categories.name, "General") as name'), \DB::raw('SUM(tickets.qty) as c'))
            ->groupBy('name')->orderByDesc('c')->limit(8)->get();
    }, collect());

    $voteStatusLabels = ['Succeeded', 'Failed', 'Pending'];
    $voteStatusData = [$totalVotes, $failedVotes, $pendingVotes];
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
      <a href="{{ route('votes.index') }}" class="ms-stat" style="--ms-accent:#16a34a;">
        <div class="ms-stat-icon"><i class="fa fa-check-square-o"></i></div>
        <div class="ms-stat-body">
          <div class="ms-stat-value">{{ number_format($totalVotes) }}</div>
          <div class="ms-stat-label">Total Votes Succeeded</div>
        </div>
      </a>
      <a href="{{ route('votes.index') }}" class="ms-stat" style="--ms-accent:#ef4444;">
        <div class="ms-stat-icon"><i class="fa fa-times-circle"></i></div>
        <div class="ms-stat-body">
          <div class="ms-stat-value">{{ number_format($failedVotes) }}</div>
          <div class="ms-stat-label">Votes Failed <small>({{ number_format($failedTxns) }} txns)</small></div>
        </div>
      </a>
      <a href="{{ route('votes.index') }}" class="ms-stat" style="--ms-accent:#f59e0b;">
        <div class="ms-stat-icon"><i class="fa fa-clock-o"></i></div>
        <div class="ms-stat-body">
          <div class="ms-stat-value">{{ number_format($pendingVotes) }}</div>
          <div class="ms-stat-label">Votes Pending <small>({{ number_format($pendingTxns) }} txns)</small></div>
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
        <canvas id="msRegionChart" height="200"></canvas>
      </div>
      <div class="ms-panel">
        <h3><i class="fa fa-bar-chart"></i> Successful Votes by Region</h3>
        <canvas id="msVotesRegionChart" height="200"></canvas>
      </div>
    </div>

    <div class="ms-panel" style="margin-bottom:20px;">
      <h3><i class="fa fa-map-marker"></i> Contestants per Region <small class="text-muted" style="font-weight:600;font-size:13px;">(for eliminations)</small></h3>
      <div class="table-responsive">
        <table class="table table-sm table-striped mb-0">
          <thead>
            <tr>
              <th>Region</th>
              <th>Count</th>
              <th>Contestants</th>
            </tr>
          </thead>
          <tbody>
            @forelse($contestantsByRegionList as $region => $names)
              <tr>
                <td><strong>{{ $region }}</strong></td>
                <td>{{ count($names) }}</td>
                <td>{{ implode(', ', $names) }}</td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-muted">No contestants found.</td></tr>
            @endforelse
          </tbody>
        </table>
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
        <h3><i class="fa fa-ticket"></i> Tickets Sold by Event</h3>
        <canvas id="msTicketsEventChart" height="130"></canvas>
      </div>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script type="text/javascript">
(function () {
    if (typeof Chart === 'undefined') return;
    Chart.defaults.font.family = "'Nunito','Segoe UI',system-ui,sans-serif";
    Chart.defaults.color = '#64748b';

    var BLUE = '#1d4ed8', YELLOW = '#f5c518', GREEN = '#16a34a', RED = '#ef4444', AMBER = '#f59e0b';
    var palette = ['#1d4ed8', '#f5c518', '#16a34a', '#a855f7', '#0ea5e9', '#ef4444', '#f59e0b', '#14b8a6', '#6366f1', '#db2777'];

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
                plugins: { legend: { display: false } },
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
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } } }
            }
        });
    }

    var regionEl = document.getElementById('msRegionChart');
    if (regionEl) {
        new Chart(regionEl.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($regionRows->pluck('name')),
                datasets: [{ data: @json($regionRows->pluck('c')), backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }]
            },
            options: {
                responsive: true, maintainAspectRatio: true, cutout: '55%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10 } },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                var total = ctx.dataset.data.reduce(function (a, b) { return a + b; }, 0);
                                var pct = total ? Math.round((ctx.raw / total) * 100) : 0;
                                return ctx.label + ': ' + ctx.raw + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    var votesRegionEl = document.getElementById('msVotesRegionChart');
    if (votesRegionEl) {
        new Chart(votesRegionEl.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($votesByRegion->pluck('name')),
                datasets: [{ label: 'Successful votes', data: @json($votesByRegion->pluck('t')), backgroundColor: GREEN, borderRadius: 8 }]
            },
            options: {
                responsive: true, maintainAspectRatio: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } }, x: { grid: { display: false } } }
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
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, grid: { color: '#eef2f7' }, ticks: { precision: 0 } },
                    y: { grid: { display: false } }
                }
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
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ticks: { precision: 0 } }, y: { grid: { display: false } } }
            }
        });
    }
})();
</script>
@endsection

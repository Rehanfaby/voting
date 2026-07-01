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
    $pendingContestants = (int) $__safe(function () { return \App\Employee::where('is_approve', false)->count(); });
    $totalVotes         = (int) $__safe(function () { return \DB::table('votes')->where('status', true)->sum('vote'); });
    $voteTxns           = (int) $__safe(function () { return \DB::table('votes')->where('status', true)->count(); });
    $totalVoters        = (int) $__safe(function () { return \DB::table('votes')->where('status', true)->distinct('user_id')->count('user_id'); });
    $totalJudges        = (int) $__safe(function () { return \App\Judge::count(); });
    $totalAmbassadors   = (int) $__safe(function () { return \App\Ambassador::count(); });

    /* Votes trend — last 14 days */
    $rawTrend = $__safe(function () {
        return \DB::table('votes')->where('status', true)
            ->where('created_at', '>=', \Carbon\Carbon::now()->subDays(13)->startOfDay())
            ->select(\DB::raw('DATE(created_at) as d'), \DB::raw('SUM(vote) as t'))
            ->groupBy('d')->pluck('t', 'd');
    }, collect());
    $trendLabels = []; $trendData = [];
    for ($i = 13; $i >= 0; $i--) {
        $day = \Carbon\Carbon::now()->subDays($i);
        $trendLabels[] = $day->format('M j');
        $trendData[]   = (int) ($rawTrend[$day->format('Y-m-d')] ?? 0);
    }

    /* Top 5 contestants by votes */
    $topRows = $__safe(function () {
        return \DB::table('votes')
            ->join('employees', 'employees.id', '=', 'votes.musician_id')
            ->where('votes.status', true)
            ->select('employees.name', \DB::raw('SUM(votes.vote) as t'))
            ->groupBy('employees.name')->orderByDesc('t')->limit(5)->get();
    }, collect());

    /* Contestants per department */
    $deptRows = $__safe(function () {
        return \DB::table('employees')
            ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
            ->where('employees.is_active', true)->where('employees.is_approve', true)
            ->select(\DB::raw('COALESCE(departments.name, "Unassigned") as name'), \DB::raw('COUNT(*) as c'))
            ->groupBy('name')->orderByDesc('c')->limit(8)->get();
    }, collect());
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
          <div class="ms-stat-label">Total Votes</div>
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
    </div>

    <div class="ms-chart-grid">
      <div class="ms-panel">
        <h3><i class="fa fa-line-chart"></i> Votes — Last 14 Days <small class="text-muted" style="font-weight:600;font-size:13px;">({{ number_format($voteTxns) }} transactions)</small></h3>
        <canvas id="msVotesTrend" height="120"></canvas>
      </div>
      <div class="ms-panel">
        <h3><i class="fa fa-pie-chart"></i> Contestants by Department</h3>
        <canvas id="msDeptChart" height="200"></canvas>
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

    var BLUE = '#1d4ed8', YELLOW = '#f5c518';
    var palette = ['#1d4ed8', '#f5c518', '#16a34a', '#a855f7', '#0ea5e9', '#ef4444', '#f59e0b', '#14b8a6'];

    var trendEl = document.getElementById('msVotesTrend');
    if (trendEl) {
        var ctx = trendEl.getContext('2d');
        var grad = ctx.createLinearGradient(0, 0, 0, 260);
        grad.addColorStop(0, 'rgba(29,78,216,.28)');
        grad.addColorStop(1, 'rgba(29,78,216,0)');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [{
                    label: 'Votes',
                    data: @json($trendData),
                    borderColor: BLUE,
                    backgroundColor: grad,
                    borderWidth: 3,
                    fill: true,
                    tension: .38,
                    pointRadius: 3,
                    pointBackgroundColor: YELLOW,
                    pointBorderColor: BLUE
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

    var deptEl = document.getElementById('msDeptChart');
    if (deptEl) {
        new Chart(deptEl.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($deptRows->pluck('name')),
                datasets: [{ data: @json($deptRows->pluck('c')), backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }]
            },
            options: {
                responsive: true, maintainAspectRatio: true, cutout: '62%',
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } } }
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
})();
</script>
@endsection

@extends('layout.main')
@section('content')
@php
    use App\Helpers\ImageOptimizer;
@endphp
<section class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3" style="gap:12px;">
        <div>
            <h3 class="mb-1">Detailed Grading Report</h3>
            <p class="text-muted mb-0">{{ $siteTitle }} · Generated {{ $generatedAt }} · {{ count($reports) }} contestant(s)</p>
        </div>
        <div>
            <a href="{{ route('report.detailed.grading') }}" class="btn btn-outline-secondary btn-sm mr-1">
                <i class="fa fa-arrow-left"></i> Select again
            </a>
            <button type="button" class="btn btn-danger btn-sm" onclick="window.print()">
                <i class="fa fa-print"></i> Print
            </button>
        </div>
    </div>

    @foreach($reports as $report)
        <div class="mg-dgr-block mb-4" style="page-break-after:always;">
            <div class="card mb-3" style="border:0;border-radius:16px;overflow:hidden;box-shadow:0 8px 24px rgba(15,23,42,.08);">
                <div style="background:linear-gradient(135deg,#0a2350,#1d4ed8);color:#fff;padding:18px 20px;">
                    <div class="d-flex align-items-center justify-content-between" style="gap:14px;flex-wrap:wrap;">
                        <div class="d-flex align-items-center" style="gap:14px;min-width:0;">
                            <div style="width:64px;height:64px;border-radius:50%;overflow:hidden;background:rgba(255,255,255,.15);flex-shrink:0;">
                                @if(!empty($report['contestant_image']))
                                    <img src="{{ ImageOptimizer::employeeImageUrl($report['contestant_image']) }}" alt="" style="width:64px;height:64px;object-fit:cover;">
                                @endif
                            </div>
                            <div style="min-width:0;">
                                <div style="font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:#fbbf24;font-weight:700;">Contestant Grading Report</div>
                                <h2 style="margin:4px 0 0;font-size:1.45rem;font-weight:800;">{{ $report['contestant_name'] }}</h2>
                                <div style="opacity:.9;font-size:13px;">Anonymized judge &amp; ambassador results</div>
                            </div>
                        </div>
                        <div style="text-align:right;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.28);border-radius:14px;padding:10px 16px;min-width:120px;">
                            <div style="font-size:10px;letter-spacing:.06em;text-transform:uppercase;color:#fbbf24;font-weight:700;">Overall score</div>
                            <div style="font-size:1.75rem;font-weight:800;line-height:1.1;">
                                @if($report['overall_score'] !== null)
                                    {{ number_format($report['overall_score'], 2) }}<span style="font-size:1rem;opacity:.85;"> / 100</span>
                                @else
                                    —
                                @endif
                            </div>
                            <div style="font-size:11px;opacity:.85;">Avg of {{ (int) $report['judge_count'] }} judge(s)</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px;">
                                <div style="font-size:11px;color:#64748b;font-weight:700;text-transform:uppercase;">Judge total</div>
                                <div style="font-size:1.7rem;font-weight:800;color:#0a2350;">{{ number_format($report['judge_total'], 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px;">
                                <div style="font-size:11px;color:#64748b;font-weight:700;text-transform:uppercase;">Ambassador total</div>
                                <div style="font-size:1.7rem;font-weight:800;color:#0a2350;">{{ number_format($report['ambassador_total'], 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px;">
                                <div style="font-size:11px;color:#64748b;font-weight:700;text-transform:uppercase;">Votes</div>
                                <div style="font-size:1.7rem;font-weight:800;color:#0a2350;">{{ number_format($report['votes_total']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-5 mb-3">
                    <div class="card h-100" style="border:0;border-radius:14px;box-shadow:0 6px 18px rgba(15,23,42,.06);">
                        <div class="card-header" style="background:#0a2350;color:#fff;border:0;">
                            <strong>Ambassadors</strong>
                            <span class="badge badge-light ml-1">{{ count($report['ambassadors']) }}</span>
                        </div>
                        <div class="card-body p-0">
                            @if(empty($report['ambassadors']))
                                <p class="text-muted p-3 mb-0">No ambassador grades.</p>
                            @else
                                <table class="table mb-0">
                                    <thead><tr><th>Ambassador</th><th>Points</th></tr></thead>
                                    <tbody>
                                        @foreach($report['ambassadors'] as $amb)
                                            <tr>
                                                <td>{{ $amb['label'] }}</td>
                                                <td><strong>{{ number_format($amb['points'], 2) }}</strong> / 5</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 mb-3">
                    <div class="card h-100" style="border:0;border-radius:14px;box-shadow:0 6px 18px rgba(15,23,42,.06);">
                        <div class="card-header" style="background:#1d4ed8;color:#fff;border:0;">
                            <strong>Judges (summary)</strong>
                            <span class="badge badge-light ml-1">{{ count($report['judges']) }}</span>
                        </div>
                        <div class="card-body p-0">
                            @if(empty($report['judges']))
                                <p class="text-muted p-3 mb-0">No judge grades.</p>
                            @else
                                <table class="table mb-0">
                                    <thead><tr><th>Judge</th><th>Total</th></tr></thead>
                                    <tbody>
                                        @foreach($report['judges'] as $judge)
                                            <tr>
                                                <td>{{ $judge['label'] }}</td>
                                                <td><strong>{{ number_format($judge['total'], 2) }}</strong> / 100</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="mt-2 mb-3" style="color:#0a2350;font-weight:800;">Judge grading details</h4>
            <p class="text-muted">Breakdown by criteria so the candidate can see Accuracy and other scores. Judge names are not shown.</p>

            @forelse($report['judges'] as $judge)
                <div class="card mb-3" style="border:0;border-radius:14px;box-shadow:0 6px 18px rgba(15,23,42,.06);">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background:#f1f5f9;border:0;">
                        <strong style="color:#0a2350;">{{ $judge['label'] }}</strong>
                        <span style="font-weight:800;color:#0a2350;">Total: {{ number_format($judge['total'], 2) }} / 100</span>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0" style="padding-left:18px;">
                            @foreach($judge['criteria'] as $c)
                                <li style="margin-bottom:8px;{{ !empty($c['highlight']) ? 'background:#eff6ff;padding:6px 10px;border-radius:8px;border-left:3px solid #1d4ed8;list-style-position:inside;' : '' }}">
                                    <span style="color:#334155;">{{ $c['label'] }}:</span>
                                    <strong style="color:#0a2350;">{{ number_format($c['score'], 2) }}</strong>
                                    @if(!empty($c['highlight']))
                                        <span class="badge badge-primary ml-1">Accuracy</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @empty
                <div class="alert alert-light border">No judge grading details available for this contestant.</div>
            @endforelse

            @if(!empty($report['rubric_chart']) && (int) $report['judge_count'] > 0)
            <div class="card mt-3" style="border:0;border-radius:14px;box-shadow:0 6px 18px rgba(15,23,42,.06);">
                <div class="card-header" style="background:#0a2350;color:#fff;border:0;">
                    <strong>Performance by grading rubric</strong>
                    <span class="d-block" style="font-size:12px;opacity:.85;font-weight:400;">Average score across judges · bars show % of each criterion maximum</span>
                </div>
                <div class="card-body">
                    <div class="mg-rubric-bars">
                        @foreach($report['rubric_chart'] as $bar)
                            <div class="mg-rubric-row {{ !empty($bar['highlight']) ? 'is-accuracy' : '' }}">
                                <div class="mg-rubric-label">
                                    <strong>{{ $bar['short'] }}</strong>
                                    <span>{{ number_format($bar['average'], 2) }} / {{ $bar['max'] }}</span>
                                </div>
                                <div class="mg-rubric-track">
                                    <div class="mg-rubric-fill" style="width: {{ $bar['percent'] }}%;"></div>
                                </div>
                                <div class="mg-rubric-pct">{{ $bar['percent'] }}%</div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mg-rubric-columns mt-4">
                        @foreach($report['rubric_chart'] as $bar)
                            <div class="mg-rubric-col {{ !empty($bar['highlight']) ? 'is-accuracy' : '' }}">
                                <div class="mg-rubric-col-track">
                                    <div class="mg-rubric-col-fill" style="height: {{ max(4, $bar['percent']) }}%;"></div>
                                </div>
                                <div class="mg-rubric-col-value">{{ number_format($bar['average'], 1) }}</div>
                                <div class="mg-rubric-col-name">{{ $bar['short'] }}</div>
                                <div class="mg-rubric-col-max">/{{ $bar['max'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    @endforeach
</section>

<style>
.mg-rubric-row { display:grid; grid-template-columns: 160px 1fr 52px; gap:10px; align-items:center; margin-bottom:12px; }
.mg-rubric-label { display:flex; flex-direction:column; font-size:12px; color:#334155; }
.mg-rubric-label strong { color:#0a2350; font-size:13px; }
.mg-rubric-track { background:#e2e8f0; border-radius:999px; height:14px; overflow:hidden; }
.mg-rubric-fill { height:100%; background:linear-gradient(90deg,#1d4ed8,#38bdf8); border-radius:999px; }
.mg-rubric-row.is-accuracy .mg-rubric-fill { background:linear-gradient(90deg,#b45309,#f59e0b); }
.mg-rubric-pct { font-weight:800; color:#0a2350; text-align:right; }
.mg-rubric-columns { display:flex; align-items:flex-end; justify-content:space-between; gap:10px; min-height:180px; padding:8px 4px 0; border-top:1px solid #e2e8f0; }
.mg-rubric-col { flex:1; text-align:center; }
.mg-rubric-col-track { height:140px; background:#eef2ff; border-radius:10px 10px 4px 4px; display:flex; align-items:flex-end; overflow:hidden; border:1px solid #dbeafe; }
.mg-rubric-col-fill { width:100%; background:linear-gradient(180deg,#38bdf8,#1d4ed8); border-radius:8px 8px 0 0; min-height:4px; }
.mg-rubric-col.is-accuracy .mg-rubric-col-track { background:#fff7ed; border-color:#fed7aa; }
.mg-rubric-col.is-accuracy .mg-rubric-col-fill { background:linear-gradient(180deg,#fbbf24,#b45309); }
.mg-rubric-col-value { margin-top:6px; font-weight:800; color:#0a2350; font-size:13px; }
.mg-rubric-col-name { font-size:11px; font-weight:700; color:#334155; }
.mg-rubric-col-max { font-size:10px; color:#94a3b8; }
@media (max-width: 767px) {
    .mg-rubric-row { grid-template-columns: 1fr; gap:4px; }
    .mg-rubric-pct { text-align:left; }
    .mg-rubric-columns { min-height:140px; }
    .mg-rubric-col-track { height:100px; }
}
@media print {
    .btn, .sidebar, nav, .navbar, aside { display: none !important; }
    .mg-dgr-block { page-break-after: always; }
}
</style>
<script>
    $("ul#report").siblings('a').attr('aria-expanded','true');
    $("ul#report").addClass("show");
    $("ul#report #detailed-grading-report-menu").addClass("active");
</script>
@endsection

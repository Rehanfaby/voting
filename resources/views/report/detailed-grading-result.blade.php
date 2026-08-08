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
                    <div class="d-flex align-items-center" style="gap:14px;">
                        <div style="width:64px;height:64px;border-radius:50%;overflow:hidden;background:rgba(255,255,255,.15);flex-shrink:0;">
                            @if(!empty($report['contestant_image']))
                                <img src="{{ ImageOptimizer::employeeImageUrl($report['contestant_image']) }}" alt="" style="width:64px;height:64px;object-fit:cover;">
                            @endif
                        </div>
                        <div>
                            <div style="font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:#fbbf24;font-weight:700;">Contestant Grading Report</div>
                            <h2 style="margin:4px 0 0;font-size:1.45rem;font-weight:800;">{{ $report['contestant_name'] }}</h2>
                            <div style="opacity:.9;font-size:13px;">Anonymized judge &amp; ambassador results</div>
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
        </div>
    @endforeach
</section>

<style>
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

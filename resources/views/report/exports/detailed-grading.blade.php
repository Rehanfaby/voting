<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detailed Grading Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        h1 { font-size: 16px; margin: 0 0 4px; color: #0a2350; }
        h2 { font-size: 14px; margin: 0 0 6px; color: #0a2350; }
        h3 { font-size: 12px; margin: 14px 0 6px; color: #1d4ed8; }
        .brand { text-align: center; margin-bottom: 12px; }
        .brand .site { font-size: 15px; font-weight: bold; color: #0a2350; text-transform: uppercase; }
        .brand .sub { font-size: 10px; color: #64748b; }
        .hero {
            background: #0a2350; color: #fff; padding: 12px 14px; margin-bottom: 10px;
        }
        .hero-table { width: 100%; border-collapse: collapse; }
        .hero-table td { vertical-align: middle; color: #fff; border: 0; padding: 0; }
        .hero .eyebrow { font-size: 9px; color: #fbbf24; text-transform: uppercase; letter-spacing: 1px; }
        .hero h2 { color: #fff; margin: 2px 0 0; font-size: 16px; }
        .hero-score {
            text-align: right; background: #12306a; border: 1px solid #33508a;
            border-radius: 8px; padding: 8px 10px; min-width: 110px;
        }
        .hero-score .lbl { font-size: 8px; color: #fbbf24; text-transform: uppercase; font-weight: bold; }
        .hero-score .val { font-size: 18px; font-weight: bold; color: #fff; }
        .hero-score .sub { font-size: 8px; color: #cbd5e1; }
        .stats { width: 100%; margin-bottom: 12px; border-collapse: collapse; }
        .stats td {
            width: 33%; background: #f8fafc; border: 1px solid #e2e8f0;
            padding: 8px; text-align: center;
        }
        .stats .label { font-size: 9px; color: #64748b; text-transform: uppercase; font-weight: bold; }
        .stats .value { font-size: 16px; font-weight: bold; color: #0a2350; margin-top: 2px; }
        table.grid { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.grid th, table.grid td { border: 1px solid #cbd5e1; padding: 5px 7px; text-align: left; }
        table.grid th { background: #0a2350; color: #fff; font-size: 10px; }
        .judge-box { border: 1px solid #cbd5e1; margin-bottom: 10px; page-break-inside: avoid; }
        .judge-head { background: #e2e8f0; padding: 6px 8px; font-weight: bold; color: #0a2350; }
        .judge-body { padding: 8px; }
        ul.criteria { margin: 0; padding-left: 16px; }
        ul.criteria li { margin-bottom: 4px; }
        .accuracy { background: #dbeafe; padding: 3px 5px; }
        .page-break { page-break-after: always; }
        .footer-note { font-size: 9px; color: #64748b; margin-top: 8px; }
        .chart-wrap { margin-top: 12px; page-break-inside: avoid; }
        .chart-title { font-size: 12px; color: #1d4ed8; font-weight: bold; margin: 12px 0 4px; }
        .bar-row { margin-bottom: 8px; }
        .bar-meta { font-size: 9px; margin-bottom: 2px; }
        .bar-meta strong { color: #0a2350; }
        .bar-track { background: #e2e8f0; height: 12px; border: 1px solid #cbd5e1; }
        .bar-fill { height: 12px; background: #1d4ed8; }
        .bar-fill.accuracy { background: #d97706; }
        .col-chart { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .col-chart td { vertical-align: bottom; text-align: center; border: 0; padding: 0 4px; width: 20%; }
        .col-track { height: 90px; background: #eef2ff; border: 1px solid #dbeafe; position: relative; }
        .col-fill { background: #1d4ed8; width: 100%; }
        .col-track.accuracy { background: #fff7ed; border-color: #fed7aa; }
        .col-fill.accuracy { background: #d97706; }
        .col-val { font-size: 9px; font-weight: bold; color: #0a2350; margin-top: 3px; }
        .col-name { font-size: 8px; color: #334155; font-weight: bold; }
    </style>
</head>
<body>
    <div class="brand">
        <div class="site">{{ $siteTitle }}</div>
        <div class="sub">Detailed Contestant Grading Report · Generated {{ $generatedAt }}</div>
        <div class="sub">Judge and ambassador names are anonymized</div>
    </div>

    @foreach($reports as $index => $report)
        <div class="{{ $index < count($reports) - 1 ? 'page-break' : '' }}">
            <div class="hero">
                <table class="hero-table">
                    <tr>
                        <td style="width:70%;">
                            <div class="eyebrow">Contestant Grading Report</div>
                            <h2>{{ $report['contestant_name'] }}</h2>
                        </td>
                        <td style="width:30%;">
                            <div class="hero-score">
                                <div class="lbl">Overall score</div>
                                <div class="val">
                                    @if($report['overall_score'] !== null)
                                        {{ number_format($report['overall_score'], 2) }} / 100
                                    @else
                                        —
                                    @endif
                                </div>
                                <div class="sub">Avg of {{ (int) $report['judge_count'] }} judge(s)</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <table class="stats">
                <tr>
                    <td>
                        <div class="label">Judge total</div>
                        <div class="value">{{ number_format($report['judge_total'], 2) }}</div>
                    </td>
                    <td>
                        <div class="label">Ambassador total</div>
                        <div class="value">{{ number_format($report['ambassador_total'], 2) }}</div>
                    </td>
                    <td>
                        <div class="label">Votes</div>
                        <div class="value">{{ number_format($report['votes_total']) }}</div>
                    </td>
                </tr>
            </table>

            <h3>Ambassadors</h3>
            @if(empty($report['ambassadors']))
                <p>No ambassador grades.</p>
            @else
                <table class="grid">
                    <thead><tr><th>Ambassador</th><th>Points</th></tr></thead>
                    <tbody>
                        @foreach($report['ambassadors'] as $amb)
                            <tr>
                                <td>{{ $amb['label'] }}</td>
                                <td>{{ number_format($amb['points'], 2) }} / 5</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <h3>Judges (summary)</h3>
            @if(empty($report['judges']))
                <p>No judge grades.</p>
            @else
                <table class="grid">
                    <thead><tr><th>Judge</th><th>Total</th></tr></thead>
                    <tbody>
                        @foreach($report['judges'] as $judge)
                            <tr>
                                <td>{{ $judge['label'] }}</td>
                                <td>{{ number_format($judge['total'], 2) }} / 100</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <h3>Judge grading details</h3>
            <p class="footer-note">Criteria breakdown for the candidate (Accuracy highlighted). Real judge names are not shown.</p>

            @forelse($report['judges'] as $judge)
                <div class="judge-box">
                    <div class="judge-head">
                        {{ $judge['label'] }} — Total: {{ number_format($judge['total'], 2) }} / 100
                    </div>
                    <div class="judge-body">
                        <ul class="criteria">
                            @foreach($judge['criteria'] as $c)
                                <li class="{{ !empty($c['highlight']) ? 'accuracy' : '' }}">
                                    {{ $c['label'] }}: <strong>{{ number_format($c['score'], 2) }}</strong>
                                    @if(!empty($c['highlight'])) — Accuracy @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @empty
                <p>No judge grading details available.</p>
            @endforelse

            @if(!empty($report['rubric_chart']) && (int) $report['judge_count'] > 0)
            <div class="chart-wrap">
                <div class="chart-title">Performance by grading rubric</div>
                <p class="footer-note">Average across judges. Bar length = % of each criterion maximum (Accuracy highlighted).</p>

                @foreach($report['rubric_chart'] as $bar)
                    <div class="bar-row">
                        <div class="bar-meta">
                            <strong>{{ $bar['short'] }}</strong>
                            — {{ number_format($bar['average'], 2) }} / {{ $bar['max'] }}
                            ({{ $bar['percent'] }}%)
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill {{ !empty($bar['highlight']) ? 'accuracy' : '' }}" style="width: {{ $bar['percent'] }}%;"></div>
                        </div>
                    </div>
                @endforeach

                <table class="col-chart">
                    <tr>
                        @foreach($report['rubric_chart'] as $bar)
                            @php
                                $pct = max(4, (float) $bar['percent']);
                                $empty = max(0, 100 - $pct);
                                $fillColor = !empty($bar['highlight']) ? '#d97706' : '#1d4ed8';
                                $trackBg = !empty($bar['highlight']) ? '#fff7ed' : '#eef2ff';
                            @endphp
                            <td>
                                <table style="width:100%;height:90px;border-collapse:collapse;background:{{ $trackBg }};border:1px solid #cbd5e1;">
                                    @if($empty > 0)
                                    <tr style="height:{{ $empty }}%;"><td style="padding:0;border:0;"></td></tr>
                                    @endif
                                    <tr style="height:{{ $pct }}%;">
                                        <td style="padding:0;border:0;background:{{ $fillColor }};"></td>
                                    </tr>
                                </table>
                                <div class="col-val">{{ number_format($bar['average'], 1) }}</div>
                                <div class="col-name">{{ $bar['short'] }}</div>
                            </td>
                        @endforeach
                    </tr>
                </table>
            </div>
            @endif
        </div>
    @endforeach
</body>
</html>

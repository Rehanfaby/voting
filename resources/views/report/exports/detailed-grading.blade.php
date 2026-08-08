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
        .hero .eyebrow { font-size: 9px; color: #fbbf24; text-transform: uppercase; letter-spacing: 1px; }
        .hero h2 { color: #fff; margin: 2px 0 0; }
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
                <div class="eyebrow">Contestant Grading Report</div>
                <h2>{{ $report['contestant_name'] }}</h2>
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
        </div>
    @endforeach
</body>
</html>

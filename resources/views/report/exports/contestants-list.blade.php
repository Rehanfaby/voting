<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $listLabel ?? 'CONTESTANT LIST' }}</title>
    <style>
        @page { margin: 28px 24px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
            margin: 0;
        }
        .banner {
            background: {{ $headerColor ?? '#0a2350' }};
            color: #ffffff;
            text-align: center;
            padding: 14px 12px 12px;
            margin-bottom: 14px;
        }
        .banner .brand {
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin: 0 0 4px;
        }
        .banner .list-label {
            font-size: 12px;
            font-weight: bold;
            margin: 0 0 2px;
        }
        .banner .subtitle {
            font-size: 9px;
            color: {{ $bannerSoft ?? '#dbeafe' }};
            margin: 0;
        }
        .banner .generated {
            font-size: 8px;
            margin: 6px 0 0;
            color: #ffffff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: {{ $headerColor ?? '#0a2350' }};
            color: #ffffff;
            font-weight: bold;
            font-size: 9px;
            text-align: left;
            padding: 6px 5px;
            border: 0.4px solid {{ $lineColor ?? '#cbd5e1' }};
        }
        td {
            padding: 5px;
            border: 0.3px solid {{ $lineColor ?? '#e2e8f0' }};
            font-size: 9px;
            vertical-align: top;
        }
        tr.even td { background: {{ $altRow ?? '#eff6ff' }}; }
        tr.odd td { background: {{ $lightRow ?? '#f8fafc' }}; }
        td.num { text-align: center; width: 28px; }
        td.votes { text-align: right; font-weight: bold; color: {{ $headerColor ?? '#0a2350' }}; }
        .footer-note {
            margin-top: 12px;
            font-size: 8px;
            color: #64748b;
            text-align: center;
        }
        @if(!empty($namesOnly))
        td.name { font-size: 11px; font-weight: bold; }
        @endif
    </style>
</head>
<body>
@php
    $brand = strtoupper((string) ($siteTitle ?? 'MULEMA GOSPEL'));
    $label = strtoupper((string) ($listLabel ?? 'CONTESTANT LIST'));
    $namesOnly = !empty($namesOnly);
    $colspan = $namesOnly ? 2 : 7;
@endphp
<div class="banner">
    <p class="brand">{{ $brand }}</p>
    <p class="list-label">{{ $label }}</p>
    @if(!empty($subtitle))
        <p class="subtitle">{{ $subtitle }}</p>
    @endif
    <p class="generated">Generated {{ $generatedAt ?? now()->format('d M Y H:i') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th class="num">#</th>
            <th>Name</th>
            @unless($namesOnly)
                <th>Region</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Approved</th>
                <th style="text-align:right;">Votes</th>
            @endunless
        </tr>
    </thead>
    <tbody>
    @forelse($rows as $i => $r)
        <tr class="{{ $i % 2 === 0 ? 'even' : 'odd' }}">
            <td class="num">{{ $i + 1 }}</td>
            <td class="name">{{ $r->name }}</td>
            @unless($namesOnly)
                <td>{{ $r->region }}</td>
                <td>{{ $r->phone }}</td>
                <td>{{ $r->email }}</td>
                <td>{{ $r->approved }}</td>
                <td class="votes">{{ number_format((int) $r->total_votes) }}</td>
            @endunless
        </tr>
    @empty
        <tr><td colspan="{{ $colspan }}" style="text-align:center;">No contestants found.</td></tr>
    @endforelse
    </tbody>
</table>

<p class="footer-note">{{ $brand }} · {{ $label }}{{ $namesOnly ? ' · Names only' : '' }} · {{ count($rows) }} contestant(s)</p>
</body>
</html>

<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>{{ $title ?? 'Report' }}</title>
<style>body{font-family:DejaVu Sans,sans-serif;font-size:12px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ccc;padding:6px;text-align:left}th{background:#eee}</style>
</head><body>
<h2>{{ $title ?? 'Report' }}</h2>
@if(!empty($start_date) && !empty($end_date))
<p>{{ $start_date }} — {{ $end_date }}</p>
@endif
<table>
<thead><tr>@foreach($headers as $h)<th>{{ $h }}</th>@endforeach</tr></thead>
<tbody>
@foreach($rows as $row)
<tr>
@foreach($headers as $i => $h)
@php
    $vals = array_values((array) $row);
@endphp
<td>{{ $vals[$i] ?? '' }}</td>
@endforeach
</tr>
@endforeach
</tbody>
</table>
</body></html>

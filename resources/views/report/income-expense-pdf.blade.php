<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Income &amp; Expenses</title>
<style>body{font-family:DejaVu Sans,sans-serif;font-size:12px}table{width:100%;border-collapse:collapse;margin-bottom:16px}th,td{border:1px solid #ccc;padding:6px}</style>
</head><body>
<h2>Income &amp; Expenses</h2>
<p>{{ $start_date }} — {{ $end_date }}</p>
<p><strong>Total income:</strong> {{ number_format($totalIncome) }} | <strong>Expenses:</strong> {{ number_format($expenses) }} | <strong>Net:</strong> {{ number_format($net) }}</p>
<h3>Income</h3>
<table><tr><th>Source</th><th>Amount</th></tr>
@foreach($incomeRows as $r)<tr><td>{{ $r->source }}</td><td>{{ number_format($r->amount) }}</td></tr>@endforeach
</table>
<h3>Expenses</h3>
<table><tr><th>Reference</th><th>Amount</th><th>Date</th></tr>
@foreach($expenseRows as $r)<tr><td>{{ $r->reference_no ?: $r->note }}</td><td>{{ number_format($r->amount) }}</td><td>{{ $r->created_at }}</td></tr>@endforeach
</table>
</body></html>

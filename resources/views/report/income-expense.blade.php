@extends('layout.main')
@section('content')
<section class="container-fluid">
    <h3>Income &amp; Expenses</h3>
    @include('report.partials.filters', ['action' => route('report.income.expense')])

    <div class="row mb-3">
        <div class="col-md-3"><div class="card"><div class="card-body"><small>Total income</small><div class="h4 text-success mb-0">{{ number_format($totalIncome) }}</div></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><small>Expenses</small><div class="h4 text-danger mb-0">{{ number_format($expenses) }}</div></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><small>Net</small><div class="h4 mb-0">{{ number_format($net) }}</div></div></div></div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4"><div class="card-header"><strong>Income</strong></div>
                <table class="table mb-0"><tbody>
                    @foreach($incomeRows as $r)<tr><td>{{ $r->source }}</td><td class="text-right">{{ number_format($r->amount) }}</td></tr>@endforeach
                </tbody></table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4"><div class="card-header"><strong>Expenses</strong></div>
                <div class="table-responsive"><table class="table table-striped mb-0">
                    <thead><tr><th>Reference</th><th>Amount</th><th>Date</th></tr></thead>
                    <tbody>@forelse($expenseRows as $r)
                        <tr><td>{{ $r->reference_no ?: $r->note }}</td><td>{{ number_format($r->amount) }}</td><td>{{ $r->created_at }}</td></tr>
                    @empty<tr><td colspan="3">No expenses in this period.</td></tr>@endforelse</tbody>
                </table></div>
            </div>
        </div>
    </div>
</section>
@endsection

@extends('layout.main')
@section('content')
<section class="container-fluid">
    <h3>Total Ticket Sales</h3>
    @include('report.partials.filters', ['action' => route('report.ticket.sales'), 'showRegion' => false, 'showEvent' => true])

    <div class="row mb-3">
        <div class="col-md-4"><div class="card"><div class="card-body"><strong>Tickets sold</strong><div class="h4 mb-0">{{ number_format($totals->tickets_sold ?? 0) }}</div></div></div></div>
        <div class="col-md-4"><div class="card"><div class="card-body"><strong>Revenue</strong><div class="h4 mb-0">{{ number_format($totals->revenue ?? 0) }}</div></div></div></div>
    </div>

    <div class="card"><div class="table-responsive"><table class="table table-striped mb-0">
        <thead><tr><th>Event</th><th>Tickets Sold</th><th>Revenue</th></tr></thead>
        <tbody>@forelse($rows as $r)
            <tr><td>{{ $r->event }}</td><td>{{ number_format($r->tickets_sold) }}</td><td>{{ number_format($r->revenue) }}</td></tr>
        @empty<tr><td colspan="3">No ticket sales in this period.</td></tr>@endforelse</tbody>
    </table></div></div>
</section>
@endsection

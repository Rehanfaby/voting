@extends('layout.main')
@section('content')
<section class="container-fluid">
    <h3>Contestants List</h3>
    @include('report.partials.filters', ['action' => route('report.contestants.list'), 'period' => 'month', 'showRegion' => true, 'showPeriod' => false])

    <div class="card"><div class="table-responsive"><table class="table table-striped mb-0">
        <thead><tr><th>Name</th><th>Region</th><th>Email</th><th>Phone</th><th>Approved</th><th>Votes</th></tr></thead>
        <tbody>@forelse($rows as $r)
            <tr><td>{{ $r->name }}</td><td>{{ $r->region }}</td><td>{{ $r->email }}</td><td>{{ $r->phone }}</td><td>{{ $r->approved }}</td><td>{{ number_format($r->total_votes) }}</td></tr>
        @empty<tr><td colspan="6">No contestants found.</td></tr>@endforelse</tbody>
    </table></div></div>
</section>
@endsection

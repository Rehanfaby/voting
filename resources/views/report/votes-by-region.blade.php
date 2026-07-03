@extends('layout.main')
@section('content')
<section class="container-fluid">
    <h3>Votes by Region</h3>
    @include('report.partials.filters', ['action' => route('report.votes.by.region'), 'showEvent' => false])

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4"><div class="card-header"><strong>Summary by region</strong></div>
                <div class="table-responsive"><table class="table table-striped mb-0">
                    <thead><tr><th>Region</th><th>Votes</th><th>Contestants</th><th>Revenue</th></tr></thead>
                    <tbody>@forelse($rows as $r)
                        <tr><td>{{ $r->region }}</td><td>{{ number_format($r->total_votes) }}</td><td>{{ $r->contestants }}</td><td>{{ number_format($r->revenue) }}</td></tr>
                    @empty<tr><td colspan="4">No data for this period.</td></tr>@endforelse</tbody>
                </table></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4"><div class="card-header"><strong>Top contestants by region</strong></div>
                <div class="table-responsive"><table class="table table-striped mb-0">
                    <thead><tr><th>Contestant</th><th>Region</th><th>Votes</th></tr></thead>
                    <tbody>@forelse($contestantRows as $r)
                        <tr><td>{{ $r->contestant }}</td><td>{{ $r->region }}</td><td>{{ number_format($r->total_votes) }}</td></tr>
                    @empty<tr><td colspan="3">No data.</td></tr>@endforelse</tbody>
                </table></div>
            </div>
        </div>
    </div>
</section>
@endsection

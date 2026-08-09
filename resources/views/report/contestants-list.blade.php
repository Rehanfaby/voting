@extends('layout.main')
@section('content')
<section class="container-fluid">
    <div class="d-flex flex-wrap align-items-start justify-content-between mb-3" style="gap:12px;">
        <div>
            <h3 class="mb-1">{{ trans('file.Contestants List') }}</h3>
            <p class="text-muted mb-0">Filter by region, then download a branded PDF with the system title (same style as Elimination / Qualified lists).</p>
        </div>
        <a class="btn btn-danger font-weight-bold" href="{{ route('report.contestants.generate_pdf', request()->only('department_id')) }}">
            <i class="fa fa-file-pdf-o"></i> {{ trans('file.Generate Contestant List') }}
        </a>
    </div>

    @include('report.partials.filters', ['action' => route('report.contestants.list'), 'period' => 'month', 'showRegion' => true, 'showPeriod' => false])

    <div class="card"><div class="table-responsive"><table class="table table-striped mb-0">
        <thead><tr><th>#</th><th>Name</th><th>Region</th><th>Email</th><th>Phone</th><th>Approved</th><th>Votes</th></tr></thead>
        <tbody>@forelse($rows as $i => $r)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $r->name }}</td>
                <td>{{ $r->region }}</td>
                <td>{{ $r->email }}</td>
                <td>{{ $r->phone }}</td>
                <td>{{ $r->approved }}</td>
                <td>{{ number_format($r->total_votes) }}</td>
            </tr>
        @empty<tr><td colspan="7">No contestants found.</td></tr>@endforelse</tbody>
    </table></div></div>
</section>
@endsection

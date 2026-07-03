@extends('layout.main')
@section('content')
<section class="container-fluid">
    <h3 class="mb-4">Reports Centre</h3>
    <div class="row">
        <div class="col-md-4 mb-3">
            <a href="{{ route('report.votes.by.region') }}" class="card h-100 text-decoration-none">
                <div class="card-body">
                    <h5><i class="fa fa-map-marker text-primary"></i> Votes by Region</h5>
                    <p class="text-muted mb-0">Total votes and revenue grouped by contestant region.</p>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('report.ticket.sales') }}" class="card h-100 text-decoration-none">
                <div class="card-body">
                    <h5><i class="fa fa-ticket text-success"></i> Total Ticket Sales</h5>
                    <p class="text-muted mb-0">Tickets sold and revenue by event.</p>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('voting.report') }}" class="card h-100 text-decoration-none">
                <div class="card-body">
                    <h5><i class="fa fa-check-square-o text-info"></i> Total Votes</h5>
                    <p class="text-muted mb-0">Contestant vote ranking for a date range.</p>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('report.contestants.list') }}" class="card h-100 text-decoration-none">
                <div class="card-body">
                    <h5><i class="fa fa-users text-warning"></i> Contestants List</h5>
                    <p class="text-muted mb-0">All contestants with region and vote totals.</p>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('report.income.expense') }}" class="card h-100 text-decoration-none">
                <div class="card-body">
                    <h5><i class="fa fa-money text-danger"></i> Income &amp; Expenses</h5>
                    <p class="text-muted mb-0">Vote + ticket income vs recorded expenses.</p>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('report.contestant.ranking') }}" class="card h-100 text-decoration-none">
                <div class="card-body">
                    <h5><i class="fa fa-trophy text-purple"></i> Contestant Grading</h5>
                    <p class="text-muted mb-0">Combined judge, ambassador and vote scores.</p>
                </div>
            </a>
        </div>
    </div>
</section>
@endsection

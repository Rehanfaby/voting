@extends('layout.main')
@section('content')

@php
    use App\Helpers\ImageOptimizer;
    $img = ImageOptimizer::employeeImageUrl($contestant->image ?? '');
@endphp

<section class="mg-awaiting">
    <div class="container-fluid">
        <div class="mg-awaiting__hero">
            <div class="d-flex align-items-center" style="gap:14px;">
                <div class="mg-awaiting-card__photo" style="width:72px;height:72px;">
                    @if(!empty($contestant->image))
                        <img src="{{ $img }}" alt="{{ $contestant->name }}" width="72" height="72">
                    @else
                        <span class="mg-awaiting-card__initial">{{ strtoupper(substr($contestant->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div>
                    <p class="mg-awaiting__eyebrow">Contestant Grading</p>
                    <h1 class="mg-awaiting__title">{{ $contestant->name }}</h1>
                    <p class="mg-awaiting__sub">Judge and Ambassador scores for this contestant.</p>
                </div>
            </div>
            <a href="{{ route('report.contestant.ranking') }}" class="mg-awaiting__help-link mg-awaiting__help-link--ghost" style="align-self:center;">
                <i class="fa fa-arrow-left"></i> Back to ranking
            </a>
        </div>

        <div class="mg-stat-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:18px;">
            <div class="mg-detail-stat" style="background:#fff;border:1px solid #e7edf5;border-radius:14px;padding:14px;">
                <div style="font-size:12px;color:#64748b;font-weight:700;text-transform:uppercase;">Judge total</div>
                <div style="font-size:1.6rem;font-weight:800;color:#0a2350;">{{ rtrim(rtrim(number_format($judgeTotal, 2, '.', ''), '0'), '.') }}</div>
            </div>
            <div class="mg-detail-stat" style="background:#fff;border:1px solid #e7edf5;border-radius:14px;padding:14px;">
                <div style="font-size:12px;color:#64748b;font-weight:700;text-transform:uppercase;">Ambassador total</div>
                <div style="font-size:1.6rem;font-weight:800;color:#0a2350;">{{ rtrim(rtrim(number_format($ambassadorTotal, 2, '.', ''), '0'), '.') }}</div>
            </div>
            <div class="mg-detail-stat" style="background:#fff;border:1px solid #e7edf5;border-radius:14px;padding:14px;">
                <div style="font-size:12px;color:#64748b;font-weight:700;text-transform:uppercase;">Votes</div>
                <div style="font-size:1.6rem;font-weight:800;color:#0a2350;">{{ number_format($votesTotal) }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-3">
                <div class="card" style="border:0;border-radius:16px;box-shadow:0 8px 24px rgba(15,23,42,.06);">
                    <div class="card-header" style="background:linear-gradient(135deg,#0a2350,#1d4ed8);color:#fff;border:0;border-radius:16px 16px 0 0;">
                        <strong>Judges</strong>
                        <span class="badge badge-light ml-2">{{ $judgeScores->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if($judgeScores->isEmpty())
                            <p class="text-muted p-3 mb-0">No judge grades yet.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Judge</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($judgeScores as $row)
                                            <tr>
                                                <td>{{ optional($row->judge)->name ?? '—' }}</td>
                                                <td><strong>{{ rtrim(rtrim(number_format((float) $row->total, 2, '.', ''), '0'), '.') }}</strong> / 100</td>
                                                <td class="text-right">
                                                    <a href="{{ route('points.show', $row->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="card" style="border:0;border-radius:16px;box-shadow:0 8px 24px rgba(15,23,42,.06);">
                    <div class="card-header" style="background:linear-gradient(135deg,#0a2350,#f59e0b);color:#fff;border:0;border-radius:16px 16px 0 0;">
                        <strong>Ambassadors</strong>
                        <span class="badge badge-light ml-2">{{ $ambassadorScores->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if($ambassadorScores->isEmpty())
                            <p class="text-muted p-3 mb-0">No ambassador grades yet.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Ambassador</th>
                                            <th>Points</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ambassadorScores as $row)
                                            <tr>
                                                <td>{{ optional($row->ambassador)->name ?? '—' }}</td>
                                                <td><strong>{{ rtrim(rtrim(number_format((float) $row->points, 2, '.', ''), '0'), '.') }}</strong> / 5</td>
                                                <td class="text-right">
                                                    <a href="{{ route('ambassador_points.edit', $row->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.grading-list-styles')

<script type="text/javascript">
    $("ul#grading-setting").siblings('a').attr('aria-expanded','true');
    $("ul#grading-setting").addClass("show");
    $("ul#grading-setting #contestant-ranking").addClass("active");
</script>
@endsection

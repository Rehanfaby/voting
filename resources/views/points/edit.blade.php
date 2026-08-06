@extends('layout.main') @section('content')
<section class="forms mg-grade-page">
    <div class="container-fluid">
        <div class="mg-grade-page__hero">
            <div>
                <p class="mg-grade-page__eyebrow">Judge Grading</p>
                <h1 class="mg-grade-page__title">Edit Points</h1>
                @if(isset($point) && $point->contestant)
                    <p class="mg-grade-page__candidate">{{ $point->contestant->name }}</p>
                @endif
            </div>
            <div class="mg-grade-page__badge">Max 100</div>
        </div>

        <div class="card mg-grade-page__card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-validation" role="alert">
                        <strong>Could not save.</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('points.update', $point) }}" method="POST">
                    @method('PUT')
                    @include('points._form')
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.mg-grade-page { padding-bottom: 28px; }
.mg-grade-page__hero {
    display: flex; align-items: flex-start; justify-content: space-between; gap: 16px;
    background: linear-gradient(135deg, #0a2350 0%, #1d4ed8 100%);
    color: #fff; border-radius: 18px; padding: 20px 18px; margin-bottom: 16px;
    box-shadow: 0 12px 30px rgba(10,35,80,.22);
}
.mg-grade-page__eyebrow {
    margin: 0 0 4px; font-size: 12px; letter-spacing: .06em; text-transform: uppercase;
    color: #f5c518; font-weight: 700;
}
.mg-grade-page__title { margin: 0; font-size: 1.45rem; font-weight: 800; line-height: 1.2; }
.mg-grade-page__candidate {
    margin: 8px 0 0; font-size: 1.05rem; font-weight: 700; color: #f5c518;
}
.mg-grade-page__badge {
    flex-shrink: 0; align-self: center; padding: 10px 14px; border-radius: 14px;
    background: rgba(255,255,255,.12); border: 1px solid rgba(245,197,24,.45);
    color: #f5c518; font-weight: 800; font-size: 13px;
}
.mg-grade-page__card {
    border: 0; border-radius: 18px; box-shadow: 0 10px 28px rgba(15,23,42,.06);
    overflow: hidden;
}
.mg-grade-page__card .card-body { padding: 18px; }
@media (min-width: 768px) {
    .mg-grade-page__title { font-size: 1.75rem; }
    .mg-grade-page__card .card-body { padding: 24px; }
}
</style>

<script type="text/javascript">
    $("ul#point").siblings('a').attr('aria-expanded','true');
    $("ul#point").addClass("show");
    $("ul#point #point-menu-list").addClass("active");

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.mgGradeRefreshScores === 'function') {
            window.mgGradeRefreshScores();
        }
    });
</script>
@endsection

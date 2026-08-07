@extends('layout.main')
@section('content')

@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif
@if(session('success'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session('success') }}</div>
@endif

@php
    use App\Helpers\ImageOptimizer;
    $awaiting = collect($awaiting_candidates ?? []);
    $count = $awaiting->count();
@endphp

<section class="mg-awaiting">
    <div class="container-fluid">
        <div class="mg-awaiting__hero">
            <div>
                <p class="mg-awaiting__eyebrow">Ambassador Grading</p>
                <h1 class="mg-awaiting__title">{{ trans('file.Awaiting Candidate') }}</h1>
                <p class="mg-awaiting__sub">
                    @if(!empty($adminView))
                        All approved contestants. Tap a card to grade as an ambassador (max 5).
                    @else
                        Contestants you have not graded yet. Tap a card to give points (max 5).
                    @endif
                </p>
            </div>
            <div class="mg-awaiting__count" aria-label="{{ $count }} remaining">
                <span class="mg-awaiting__count-num">{{ $count }}</span>
                <span class="mg-awaiting__count-label">{{ !empty($adminView) ? 'contestants' : 'left to grade' }}</span>
            </div>
        </div>

        <div class="mg-awaiting__toolbar">
            <div class="mg-awaiting__search">
                <i class="fa fa-search"></i>
                <input type="search" id="mg-awaiting-search" placeholder="Search candidate…" autocomplete="off">
            </div>
            <a class="mg-awaiting__help-link" href="#module-help" data-module-help="1">
                <i class="dripicons-question"></i> {{ trans('file.Help') }}
            </a>
        </div>

        @if(!empty($grading_disabled))
            <div class="alert alert-warning">{{ trans('file.Grading is not enabled yet') }}</div>
        @elseif($count === 0)
            <div class="mg-awaiting__empty">
                <i class="fa fa-check-circle"></i>
                <h3>{{ !empty($adminView) ? 'No contestants' : 'All caught up' }}</h3>
                <p>
                    @if(!empty($adminView))
                        There are no approved contestants to grade right now.
                    @else
                        You have graded every contestant, or grading is not available right now.
                    @endif
                </p>
                <a href="{{ route('ambassador_points.index') }}" class="btn btn-primary">{{ trans('file.Grade Listing') }}</a>
            </div>
        @else
            <div class="mg-awaiting__grid" id="mg-awaiting-grid">
                @foreach($awaiting->sortBy('name') as $candidate)
                    @php
                        $href = route('ambassador_points.create', ['candidate_id' => $candidate->id]);
                        $img = ImageOptimizer::employeeImageUrl($candidate->image ?? '');
                    @endphp
                    <a class="mg-awaiting-card" href="{{ $href }}" data-name="{{ strtolower($candidate->name) }}">
                        <div class="mg-awaiting-card__photo">
                            @if(!empty($candidate->image))
                                <img src="{{ $img }}" alt="{{ $candidate->name }}" loading="lazy" width="88" height="88">
                            @else
                                <span class="mg-awaiting-card__initial">{{ strtoupper(substr($candidate->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="mg-awaiting-card__body">
                            <h3 class="mg-awaiting-card__name">{{ $candidate->name }}</h3>
                            <p class="mg-awaiting-card__hint">Tap to grade · max 5 points</p>
                        </div>
                        <span class="mg-awaiting-card__cta">
                            <i class="fa fa-pencil"></i>
                            <span>Give Point</span>
                        </span>
                    </a>
                @endforeach
            </div>
            <p class="mg-awaiting__none-match" id="mg-awaiting-none" style="display:none;">No candidate matches your search.</p>
        @endif
    </div>
</section>

<style>
.mg-awaiting { padding: 8px 0 28px; }
.mg-awaiting__hero {
    display: flex; align-items: flex-start; justify-content: space-between; gap: 16px;
    background: linear-gradient(135deg, #0a2350 0%, #1d4ed8 100%);
    color: #fff; border-radius: 18px; padding: 20px 18px; margin-bottom: 16px;
    box-shadow: 0 12px 30px rgba(10,35,80,.22);
}
.mg-awaiting__eyebrow { margin: 0 0 4px; font-size: 12px; letter-spacing: .06em; text-transform: uppercase; color: #f5c518; font-weight: 700; }
.mg-awaiting__title { margin: 0; font-size: 1.45rem; font-weight: 800; line-height: 1.2; }
.mg-awaiting__sub { margin: 8px 0 0; font-size: 13px; color: rgba(255,255,255,.82); max-width: 36rem; }
.mg-awaiting__count {
    flex-shrink: 0; min-width: 88px; text-align: center; background: rgba(255,255,255,.12);
    border: 1px solid rgba(245,197,24,.45); border-radius: 14px; padding: 10px 12px;
}
.mg-awaiting__count-num { display: block; font-size: 1.8rem; font-weight: 800; color: #f5c518; line-height: 1; }
.mg-awaiting__count-label { display: block; font-size: 11px; margin-top: 4px; color: rgba(255,255,255,.85); }
.mg-awaiting__toolbar { display: flex; gap: 10px; align-items: center; margin-bottom: 14px; flex-wrap: wrap; }
.mg-awaiting__search {
    flex: 1 1 220px; display: flex; align-items: center; gap: 10px;
    background: #fff; border: 1px solid #e2e8f0; border-radius: 999px; padding: 10px 14px;
    box-shadow: 0 4px 14px rgba(15,23,42,.05);
}
.mg-awaiting__search i { color: #64748b; }
.mg-awaiting__search input {
    border: 0; outline: 0; width: 100%; font-size: 15px; background: transparent; color: #0a2350;
}
.mg-awaiting__help-link {
    display: inline-flex; align-items: center; gap: 6px; padding: 10px 14px; border-radius: 999px;
    background: #0a2350; color: #fff !important; font-weight: 700; font-size: 13px; text-decoration: none !important;
}
.mg-awaiting__grid { display: grid; grid-template-columns: 1fr; gap: 10px; }
.mg-awaiting-card {
    display: flex; align-items: center; gap: 12px; padding: 12px;
    background: #fff; border: 1px solid #e7edf5; border-radius: 16px; text-decoration: none !important;
    color: inherit; box-shadow: 0 6px 18px rgba(15,23,42,.05);
    transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
}
.mg-awaiting-card:hover, .mg-awaiting-card:focus {
    transform: translateY(-1px); border-color: #f5c518;
    box-shadow: 0 10px 24px rgba(10,35,80,.12); color: inherit;
}
.mg-awaiting-card__photo {
    width: 64px; height: 64px; border-radius: 50%; overflow: hidden; flex-shrink: 0;
    background: #0a2350; border: 3px solid #f5c518;
    display: flex; align-items: center; justify-content: center;
}
.mg-awaiting-card__photo img { width: 100%; height: 100%; object-fit: cover; }
.mg-awaiting-card__initial { color: #f5c518; font-weight: 800; font-size: 1.4rem; }
.mg-awaiting-card__body { flex: 1; min-width: 0; }
.mg-awaiting-card__name {
    margin: 0; font-size: 15px; font-weight: 800; color: #0a2350;
    line-height: 1.25; overflow-wrap: anywhere;
}
.mg-awaiting-card__hint { margin: 4px 0 0; font-size: 12px; color: #64748b; }
.mg-awaiting-card__cta {
    flex-shrink: 0; display: inline-flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 2px; min-width: 72px; padding: 8px 10px; border-radius: 12px;
    background: linear-gradient(135deg, #1d4ed8, #0a2350); color: #fff; font-size: 11px; font-weight: 700;
}
.mg-awaiting-card__cta i { font-size: 16px; }
.mg-awaiting__empty {
    text-align: center; background: #fff; border-radius: 18px; padding: 36px 18px;
    border: 1px solid #e7edf5;
}
.mg-awaiting__empty i { font-size: 42px; color: #22c55e; }
.mg-awaiting__empty h3 { margin: 12px 0 6px; color: #0a2350; font-weight: 800; }
.mg-awaiting__empty p { color: #64748b; margin-bottom: 16px; }
.mg-awaiting__none-match { text-align: center; color: #64748b; margin-top: 18px; }
@media (min-width: 768px) {
    .mg-awaiting__grid { grid-template-columns: 1fr 1fr; }
    .mg-awaiting__title { font-size: 1.75rem; }
    .mg-awaiting-card__photo { width: 72px; height: 72px; }
}
@media (min-width: 1200px) {
    .mg-awaiting__grid { grid-template-columns: 1fr 1fr 1fr; }
}
@media (max-width: 575.98px) {
    .mg-awaiting__hero { flex-direction: row; padding: 16px; }
    .mg-awaiting__sub { font-size: 12px; }
    .mg-awaiting-card__cta span { display: none; }
    .mg-awaiting-card__cta { min-width: 44px; min-height: 44px; border-radius: 50%; }
}
</style>

<script type="text/javascript">
    $("ul#ambassador-point").siblings('a').attr('aria-expanded','true');
    $("ul#ambassador-point").addClass("show");
    $("ul#ambassador-point #ambassador-point-awaiting-list").addClass("active");

    (function () {
        var input = document.getElementById('mg-awaiting-search');
        var grid = document.getElementById('mg-awaiting-grid');
        var none = document.getElementById('mg-awaiting-none');
        if (!input || !grid) return;
        input.addEventListener('input', function () {
            var q = (input.value || '').toLowerCase().trim();
            var visible = 0;
            grid.querySelectorAll('.mg-awaiting-card').forEach(function (card) {
                var match = !q || (card.getAttribute('data-name') || '').indexOf(q) !== -1;
                card.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            if (none) none.style.display = visible ? 'none' : 'block';
        });

        var helpLink = document.querySelector('.mg-awaiting__help-link');
        if (helpLink) {
            helpLink.addEventListener('click', function (e) {
                e.preventDefault();
                if (typeof window.msShowModuleHelp === 'function') {
                    window.msShowModuleHelp('ambassador-point');
                } else {
                    window.location.hash = 'module-help';
                }
            });
        }
    })();
</script>
@endsection

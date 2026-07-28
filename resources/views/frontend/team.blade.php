@extends('frontend.layout.main')
@section('content')

    @if($errors->has('name'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('name') }}</div>
    @endif
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif
    @if(session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif

    <main>
        <section class="mg-contestants-page pt-40 pb-80">
            <div class="container">
                <div class="mg-contestants-page__head text-center">
                    <p class="mg-contestants-page__eyebrow">{{ trans('file.Vote your Candidate') }}</p>
                    <h1 class="mg-contestants-page__title">{{ trans('file.Contestants') }}</h1>
                    <div class="mg-search mg-contestants-page__search">
                        <i class="fa fa-search"></i>
                        <input type="text" id="contestant-search" placeholder="{{ trans('file.Search Your Contestant') }}">
                    </div>
                </div>

                @php
                    $ranked = $musicians->sortByDesc(function ($m) use ($vote_counts) {
                        return $vote_counts[$m->id] ?? 0;
                    })->values();
                    $elimCount = \App\Helpers\SiteContent::eliminationsCount();
                    $totalRanked = $ranked->count();
                    $elimLineIndex = ($elimCount > 0 && $elimCount < $totalRanked)
                        ? ($totalRanked - $elimCount)
                        : null;
                    $qualCount = $elimLineIndex !== null ? $elimLineIndex : 0;
                @endphp

                <div class="row mg-contestant-grid justify-content-center" id="contestant-grid">
                    @if($elimLineIndex !== null)
                    <div class="col-12 mg-zone-divider mg-zone-divider--qual js-qual-divider" role="separator" aria-label="{{ trans('file.Qualified zone') }}">
                        <div class="mg-zone-divider__line"></div>
                        <p class="mg-zone-divider__label">
                            <i class="fa fa-check-circle"></i>
                            {{ trans('file.Qualified zone top contestants', ['count' => $qualCount]) }}
                        </p>
                        <div class="mg-zone-divider__line"></div>
                    </div>
                    @endif

                    @foreach($ranked as $key => $musician)
                    @if($elimLineIndex !== null && $key === $elimLineIndex)
                    <div class="col-12 mg-zone-divider mg-zone-divider--elim js-elim-divider" role="separator" aria-label="{{ trans('file.Elimination zone') }}">
                        <div class="mg-zone-divider__line"></div>
                        <p class="mg-zone-divider__label">
                            <i class="fa fa-exclamation-triangle"></i>
                            {{ trans('file.Below this line contestants are in the elimination zone', ['count' => $elimCount]) }}
                        </p>
                        <div class="mg-zone-divider__line"></div>
                    </div>
                    @endif
                    @php
                        $inElim = $elimLineIndex !== null && $key >= $elimLineIndex;
                        $inQual = $elimLineIndex !== null && $key < $elimLineIndex;
                        $zoneClass = $inElim ? 'is-elim-zone' : ($inQual ? 'is-qual-zone' : '');
                    @endphp
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 contestant-list js-contestant-item {{ $zoneClass }}" data-name="{{ strtolower($musician->name) }}" data-zone="{{ $inElim ? 'elim' : ($inQual ? 'qual' : '') }}">
                        <div class="mg-contestant-card">
                            <div class="mg-contestant-card__avatar">
                                <span class="mg-contestant-card__badge {{ $inElim ? 'is-danger' : ($inQual ? 'is-safe' : '') }}">{{ $key + 1 }}</span>
                                <a href="{{ route('musician.data', $musician->id) }}">
                                    <img src="{{ \App\Helpers\ImageOptimizer::employeeImageUrl($musician->image) }}" alt="{{ $musician->name }}" width="160" height="160" loading="lazy" decoding="async">
                                </a>
                            </div>
                            <h3 class="mg-contestant-card__name">
                                <a href="{{ route('musician.data', $musician->id) }}">{{ $musician->name }}</a>
                            </h3>
                            @if($see_votes)
                            <span class="mg-contestant-card__votes">
                                <i class="fa fa-vote-yea"></i>
                                {{ number_format($vote_counts[$musician->id] ?? 0) }} {{ trans('file.Votes') }}
                            </span>
                            @else
                            <a href="{{ route('musician.data', $musician->id) }}" class="mg-contestant-card__cta">{{ trans('file.Vote For Me') }}</a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($ranked->isEmpty())
                <p class="mg-contestants-page__empty text-center">{{ trans('file.No contestants found') }}</p>
                @endif
            </div>
        </section>
    </main>

@endsection

@section('styles')
<style>
.mg-zone-divider {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    margin: 10px 0 18px; padding: 4px 8px; width: 100%;
}
.mg-zone-divider__line {
    width: 100%; height: 3px; border-radius: 999px;
}
.mg-zone-divider__label {
    margin: 0; text-align: center; font-weight: 800;
    font-size: 13px; letter-spacing: .3px; text-transform: uppercase;
    border-radius: 999px; padding: 8px 14px; line-height: 1.3;
}
.mg-zone-divider__label i { margin-right: 6px; }

/* Qualified (green) */
.mg-zone-divider--qual .mg-zone-divider__line {
    background: linear-gradient(90deg, transparent, #22c55e 12%, #22c55e 88%, transparent);
    box-shadow: 0 0 18px rgba(34, 197, 94, .45);
}
.mg-zone-divider--qual .mg-zone-divider__label {
    color: #bbf7d0;
    background: rgba(20, 83, 45, .55);
    border: 1px solid rgba(34, 197, 94, .55);
}
.mg-zone-divider--qual .mg-zone-divider__label i { color: #4ade80; }

/* Elimination (red) */
.mg-zone-divider--elim .mg-zone-divider__line {
    background: linear-gradient(90deg, transparent, #ef4444 12%, #ef4444 88%, transparent);
    box-shadow: 0 0 18px rgba(239, 68, 68, .45);
}
.mg-zone-divider--elim .mg-zone-divider__label {
    color: #fecaca;
    background: rgba(127, 29, 29, .55);
    border: 1px solid rgba(239, 68, 68, .55);
}
.mg-zone-divider--elim .mg-zone-divider__label i { color: #f87171; }

/* Blinking frame — qualified (green) */
.contestant-list.is-qual-zone .mg-contestant-card__avatar {
    background: linear-gradient(145deg, #16a34a, #4ade80) !important;
    animation: mg-blink-green 1.35s ease-in-out infinite;
}
.contestant-list.is-qual-zone.is-search-hit .mg-contestant-card__avatar {
    animation: mg-blink-green 0.7s ease-in-out infinite;
}
.mg-contestant-card__badge.is-safe {
    background: #166534 !important;
    color: #bbf7d0 !important;
    border-color: #4ade80 !important;
}

/* Blinking frame — elimination (red) */
.contestant-list.is-elim-zone .mg-contestant-card__avatar {
    background: linear-gradient(145deg, #dc2626, #f87171) !important;
    animation: mg-blink-red 1.35s ease-in-out infinite;
}
.contestant-list.is-elim-zone.is-search-hit .mg-contestant-card__avatar {
    animation: mg-blink-red 0.7s ease-in-out infinite;
}
.mg-contestant-card__badge.is-danger {
    background: #dc2626 !important;
    color: #fff !important;
    border-color: #fca5a5 !important;
}

@keyframes mg-blink-red {
    0%, 100% {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, .25), 0 0 10px rgba(239, 68, 68, .25);
        filter: brightness(1);
    }
    50% {
        box-shadow: 0 0 0 6px rgba(239, 68, 68, .95), 0 0 28px rgba(239, 68, 68, .85);
        filter: brightness(1.12);
    }
}
@keyframes mg-blink-green {
    0%, 100% {
        box-shadow: 0 0 0 3px rgba(34, 197, 94, .25), 0 0 10px rgba(34, 197, 94, .25);
        filter: brightness(1);
    }
    50% {
        box-shadow: 0 0 0 6px rgba(34, 197, 94, .95), 0 0 28px rgba(34, 197, 94, .85);
        filter: brightness(1.12);
    }
}

@media (max-width: 575.98px) {
    .mg-zone-divider__label { font-size: 11px; padding: 7px 12px; max-width: 96%; }
}
@media (prefers-reduced-motion: reduce) {
    .contestant-list.is-qual-zone .mg-contestant-card__avatar,
    .contestant-list.is-elim-zone .mg-contestant-card__avatar,
    .contestant-list.is-search-hit .mg-contestant-card__avatar {
        animation: none !important;
    }
    .contestant-list.is-qual-zone .mg-contestant-card__avatar {
        box-shadow: 0 0 0 4px rgba(34, 197, 94, .85), 0 0 18px rgba(34, 197, 94, .55);
    }
    .contestant-list.is-elim-zone .mg-contestant-card__avatar {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, .85), 0 0 18px rgba(239, 68, 68, .55);
    }
}
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        var $grid = $('#contestant-grid');
        var originalNodes = $grid.children().toArray();
        var originalCards = $grid.children('.contestant-list').toArray();
        var $qualDivider = $grid.children('.js-qual-divider');
        var $elimDivider = $grid.children('.js-elim-divider');

        $('#contestant-search').on('keyup input', function () {
            var value = $(this).val().toLowerCase().trim();
            $('.js-contestant-item').removeClass('is-search-hit');

            if (!value) {
                $grid.append(originalNodes);
                $(originalNodes).show();
                return;
            }

            var matched = [], rest = [];
            originalCards.forEach(function (el) {
                var $el = $(el);
                var name = ($el.data('name') || $el.text()).toString().toLowerCase();
                if (name.indexOf(value) > -1) {
                    $el.show().addClass('is-search-hit');
                    matched.push(el);
                } else {
                    $el.hide().removeClass('is-search-hit');
                    rest.push(el);
                }
            });
            if ($qualDivider.length) { $qualDivider.hide(); }
            if ($elimDivider.length) { $elimDivider.hide(); }
            $grid.append(matched.concat(rest));
        });

        // Prefill from the header search (?q=) and apply the filter immediately.
        var params = new URLSearchParams(window.location.search);
        var q = params.get('q');
        if (q) {
            $('#contestant-search').val(q).trigger('keyup');
        }
    });
</script>
@endsection

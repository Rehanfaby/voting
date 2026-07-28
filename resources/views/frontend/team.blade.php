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
                @endphp

                <div class="row mg-contestant-grid justify-content-center" id="contestant-grid">
                    @foreach($ranked as $key => $musician)
                    @if($elimLineIndex !== null && $key === $elimLineIndex)
                    <div class="col-12 mg-elim-divider js-elim-divider" role="separator" aria-label="{{ trans('file.Elimination zone') }}">
                        <div class="mg-elim-divider__line"></div>
                        <p class="mg-elim-divider__label">
                            <i class="fa fa-exclamation-triangle"></i>
                            {{ trans('file.Below this line contestants are in the elimination zone', ['count' => $elimCount]) }}
                        </p>
                        <div class="mg-elim-divider__line"></div>
                    </div>
                    @endif
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 contestant-list js-contestant-item {{ ($elimLineIndex !== null && $key >= $elimLineIndex) ? 'is-elim-zone' : '' }}" data-name="{{ strtolower($musician->name) }}">
                        <div class="mg-contestant-card">
                            <div class="mg-contestant-card__avatar">
                                <span class="mg-contestant-card__badge {{ ($elimLineIndex !== null && $key >= $elimLineIndex) ? 'is-danger' : '' }}">{{ $key + 1 }}</span>
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
.mg-elim-divider {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    margin: 10px 0 18px; padding: 4px 8px; width: 100%;
}
.mg-elim-divider__line {
    width: 100%; height: 3px; border-radius: 999px;
    background: linear-gradient(90deg, transparent, #ef4444 12%, #ef4444 88%, transparent);
    box-shadow: 0 0 18px rgba(239, 68, 68, .45);
}
.mg-elim-divider__label {
    margin: 0; text-align: center; color: #fecaca; font-weight: 800;
    font-size: 13px; letter-spacing: .3px; text-transform: uppercase;
    background: rgba(127, 29, 29, .55); border: 1px solid rgba(239, 68, 68, .55);
    border-radius: 999px; padding: 8px 14px; line-height: 1.3;
}
.mg-elim-divider__label i { margin-right: 6px; color: #f87171; }
.contestant-list.is-elim-zone .mg-contestant-card__avatar {
    box-shadow: 0 0 0 2px rgba(239, 68, 68, .55);
}
.mg-contestant-card__badge.is-danger { background: #dc2626 !important; }
@media (max-width: 575.98px) {
    .mg-elim-divider__label { font-size: 11px; padding: 7px 12px; max-width: 96%; }
}
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        var $grid = $('#contestant-grid');
        var originalNodes = $grid.children().toArray();
        var originalCards = $grid.children('.contestant-list').toArray();
        var $divider = $grid.children('.js-elim-divider');

        $('#contestant-search').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            if (!value) {
                $grid.append(originalNodes);
                $(originalNodes).show();
                return;
            }
            var matched = [], rest = [];
            originalCards.forEach(function (el) {
                var $el = $(el);
                var name = ($el.data('name') || $el.text()).toString().toLowerCase();
                if (name.indexOf(value) > -1) { $el.show(); matched.push(el); }
                else { $el.hide(); rest.push(el); }
            });
            if ($divider.length) { $divider.hide(); }
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

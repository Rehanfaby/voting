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
                @endphp

                <div class="row mg-contestant-grid justify-content-center" id="contestant-grid">
                    @foreach($ranked as $key => $musician)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 contestant-list js-contestant-item" data-name="{{ strtolower($musician->name) }}">
                        <div class="mg-contestant-card">
                            <div class="mg-contestant-card__avatar">
                                <span class="mg-contestant-card__badge">{{ $key + 1 }}</span>
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

@section('scripts')
<script>
    $(document).ready(function () {
        var $grid = $('#contestant-grid');
        var originalOrder = $grid.children('.contestant-list').toArray();

        $('#contestant-search').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            var matched = [], rest = [];
            originalOrder.forEach(function (el) {
                var $el = $(el);
                var name = ($el.data('name') || $el.text()).toString().toLowerCase();
                if (!value || name.indexOf(value) > -1) { $el.show(); matched.push(el); }
                else { $el.hide(); rest.push(el); }
            });
            // Move matches to the top so the exact contestant is easy to find.
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

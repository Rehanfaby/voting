@extends('frontend.layout.main')
@section('content')

    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center mg-rate-flash"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif
    @if(session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center mg-rate-flash"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible text-center mg-rate-flash"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first() }}</div>
    @endif

    <main>
        <section class="mg-rate-page">
            <div class="container">
                <div class="mg-rate-page__head text-center">
                    <p class="mg-rate-page__eyebrow">{{ trans('file.Rate Us') }}</p>
                    <h1 class="mg-rate-page__title">{{ trans('file.How was your experience') }}</h1>
                    <p class="mg-rate-page__lead">{{ trans('file.Share your rating and help us improve') }}</p>

                    <div class="mg-rate-avg" aria-label="{{ trans('file.Overall rating') }}">
                        <div class="mg-rate-avg__stars" aria-hidden="true">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= round($average) ? 'is-on' : '' }}">★</span>
                            @endfor
                        </div>
                        <div class="mg-rate-avg__score">{{ number_format($average, 1) }} <span>/ 5</span></div>
                        <div class="mg-rate-avg__count">{{ trans('file.Based on ratings', ['count' => $ratingCount]) }}</div>
                    </div>
                </div>

                <div class="mg-rate-layout">
                    <div class="mg-rate-form-wrap">
                        <h2 class="mg-rate-section-title">{{ trans('file.Leave a rating') }}</h2>
                        <form method="post" action="{{ route('rate.us.store') }}" class="mg-rate-form" id="mg-rate-form">
                            @csrf
                            <input type="hidden" name="vote_id" value="{{ $vote ? $vote->id : '' }}">
                            <input type="hidden" name="musician_id" value="{{ $musicianId ?? '' }}">
                            <input type="hidden" name="stars" id="mg-rate-stars-input" value="{{ old('stars', 5) }}">

                            <div class="mg-rate-stars-picker" role="radiogroup" aria-label="{{ trans('file.Select stars') }}">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" class="mg-rate-star-btn {{ (int) old('stars', 5) >= $i ? 'is-active' : '' }}" data-stars="{{ $i }}" aria-label="{{ $i }} {{ trans('file.Stars') }}">★</button>
                                @endfor
                            </div>
                            <p class="mg-rate-stars-hint" id="mg-rate-stars-hint">{{ (int) old('stars', 5) }} / 5</p>

                            <div class="mg-rate-field">
                                <label>{{ trans('file.Show my name as') }}</label>
                                <div class="mg-rate-as">
                                    <label class="mg-rate-as__option">
                                        <input type="radio" name="display_as" value="voter" {{ old('display_as', 'voter') === 'voter' ? 'checked' : '' }}>
                                        <span>{{ trans('file.Voter') }}</span>
                                    </label>
                                    @if(!empty($contestantName))
                                    <label class="mg-rate-as__option">
                                        <input type="radio" name="display_as" value="contestant" {{ old('display_as') === 'contestant' ? 'checked' : '' }}>
                                        <span>{{ trans('file.Contestant') }} ({{ $contestantName }})</span>
                                    </label>
                                    @endif
                                </div>
                            </div>

                            <div class="mg-rate-field" id="mg-rate-name-field">
                                <label for="display_name">{{ trans('file.Your name') }} *</label>
                                <input type="text" name="display_name" id="display_name" class="mg-rate-input" value="{{ old('display_name', $voterName) }}" maxlength="120" autocomplete="name" placeholder="{{ trans('file.Your name') }}">
                            </div>

                            <div class="mg-rate-field">
                                <label for="country">{{ trans('file.Country') }} *</label>
                                <select name="country" id="country" class="mg-rate-input" required>
                                    <option value="">{{ trans('file.Select country') }}</option>
                                    @foreach($countries as $code => $label)
                                        <option value="{{ $code }}" {{ old('country', 'CM') === $code ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mg-rate-field">
                                <label for="comment">{{ trans('file.Comment') }}</label>
                                <textarea name="comment" id="comment" class="mg-rate-input mg-rate-textarea" rows="4" maxlength="1000" placeholder="{{ trans('file.Tell us what you think') }}">{{ old('comment') }}</textarea>
                            </div>

                            <button type="submit" class="mg-btn mg-rate-submit">
                                <i class="fa fa-star"></i> {{ trans('file.Submit rating') }}
                            </button>
                            <p class="mg-rate-note">{{ trans('file.Ratings appear after admin approval') }}</p>
                        </form>
                    </div>

                    <div class="mg-rate-list-wrap">
                        <h2 class="mg-rate-section-title">{{ trans('file.What people say') }}</h2>
                        @forelse($ratings as $rating)
                            <article class="mg-rate-card">
                                <div class="mg-rate-card__top">
                                    <div class="mg-rate-card__stars" aria-label="{{ $rating->stars }} / 5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="{{ $i <= $rating->stars ? 'is-on' : '' }}">★</span>
                                        @endfor
                                    </div>
                                    <time datetime="{{ optional($rating->created_at)->toIso8601String() }}">{{ optional($rating->created_at)->diffForHumans() }}</time>
                                </div>
                                <h3 class="mg-rate-card__name">
                                    {{ $rating->display_name }}
                                    @if($rating->display_as === 'contestant')
                                        <span class="mg-rate-card__tag">{{ trans('file.Contestant') }}</span>
                                    @endif
                                </h3>
                                <p class="mg-rate-card__country">
                                    @if($rating->countryFlagUrl())
                                        <img src="{{ $rating->countryFlagUrl(20) }}" alt="" width="20" height="14">
                                    @endif
                                    {{ $rating->countryLabel() }}
                                </p>
                                @if($rating->comment)
                                    <p class="mg-rate-card__comment">{{ $rating->comment }}</p>
                                @endif
                            </article>
                        @empty
                            <p class="mg-rate-empty">{{ trans('file.No public ratings yet Be the first') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('styles')
<style>
.mg-rate-flash { margin: 0; border-radius: 0; }
.mg-rate-page {
    padding: 28px 0 72px;
    background:
        radial-gradient(700px 320px at 50% -10%, rgba(232,119,34,.16), transparent 60%),
        linear-gradient(180deg, #07172f 0%, #0a2350 55%, #07172f 100%);
    min-height: 70vh;
}
.mg-rate-page__eyebrow {
    color: #e87722; font-weight: 800; letter-spacing: 2px; text-transform: uppercase;
    font-size: 12px; margin: 0 0 8px;
}
.mg-rate-page__title {
    color: #fff; font-size: clamp(28px, 7vw, 42px); font-weight: 800; margin: 0 0 8px; line-height: 1.15;
}
.mg-rate-page__lead { color: rgba(255,255,255,.72); margin: 0 auto 22px; max-width: 420px; font-size: 15px; }
.mg-rate-avg {
    display: inline-flex; flex-direction: column; align-items: center; gap: 4px;
    padding: 14px 22px; border-radius: 18px;
    background: rgba(255,255,255,.06); border: 1px solid rgba(232,119,34,.28);
    margin-bottom: 28px;
}
.mg-rate-avg__stars span, .mg-rate-card__stars span { color: rgba(255,255,255,.25); font-size: 22px; line-height: 1; }
.mg-rate-avg__stars span.is-on, .mg-rate-card__stars span.is-on { color: #e87722; }
.mg-rate-avg__score { color: #fff; font-size: 28px; font-weight: 800; }
.mg-rate-avg__score span { font-size: 16px; color: rgba(255,255,255,.55); font-weight: 600; }
.mg-rate-avg__count { color: rgba(255,255,255,.55); font-size: 13px; }

.mg-rate-layout { display: grid; gap: 22px; }
@media (min-width: 992px) {
    .mg-rate-layout { grid-template-columns: 1.05fr .95fr; align-items: start; gap: 28px; }
}
.mg-rate-form-wrap, .mg-rate-list-wrap {
    background: #fff; border-radius: 20px; padding: 18px 16px 20px;
    box-shadow: 0 18px 50px rgba(3,12,28,.35);
}
@media (min-width: 576px) {
    .mg-rate-form-wrap, .mg-rate-list-wrap { padding: 24px 22px; }
}
.mg-rate-section-title {
    font-size: 18px; font-weight: 800; color: #0a2350; margin: 0 0 16px;
}
.mg-rate-stars-picker {
    display: flex; justify-content: center; gap: 8px; margin-bottom: 6px;
}
.mg-rate-star-btn {
    width: 52px; height: 52px; border: none; border-radius: 14px;
    background: #f1f5f9; color: #cbd5e1; font-size: 28px; line-height: 1;
    cursor: pointer; touch-action: manipulation;
    transition: transform .12s ease, background .15s ease, color .15s ease;
}
.mg-rate-star-btn.is-active { background: rgba(232,119,34,.14); color: #e87722; }
.mg-rate-star-btn:active { transform: scale(.94); }
.mg-rate-stars-hint { text-align: center; color: #64748b; font-weight: 700; margin: 0 0 16px; }
.mg-rate-field { margin-bottom: 14px; }
.mg-rate-field > label { display: block; font-weight: 700; color: #334155; margin-bottom: 6px; font-size: 14px; }
.mg-rate-input {
    width: 100%; border: 1.5px solid #e2e8f0; border-radius: 14px;
    padding: 14px 14px; font-size: 16px; color: #0f172a; background: #fff;
    -webkit-appearance: none; appearance: none;
}
.mg-rate-input:focus { outline: none; border-color: #e87722; box-shadow: 0 0 0 3px rgba(232,119,34,.18); }
.mg-rate-textarea { min-height: 110px; resize: vertical; }
.mg-rate-as { display: flex; flex-direction: column; gap: 8px; }
.mg-rate-as__option {
    display: flex; align-items: center; gap: 10px; padding: 12px 14px;
    border: 1.5px solid #e2e8f0; border-radius: 14px; margin: 0; cursor: pointer;
    font-weight: 600; color: #334155;
}
.mg-rate-as__option:has(input:checked) { border-color: #e87722; background: rgba(232,119,34,.06); }
.mg-rate-submit {
    width: 100%; justify-content: center; margin-top: 6px;
    min-height: 52px; font-size: 16px !important; border-radius: 16px !important;
}
.mg-rate-note { text-align: center; color: #94a3b8; font-size: 12px; margin: 12px 0 0; }
.mg-rate-card {
    padding: 14px 0; border-bottom: 1px solid #eef2f7;
}
.mg-rate-card:last-child { border-bottom: none; }
.mg-rate-card__top { display: flex; justify-content: space-between; align-items: center; gap: 8px; margin-bottom: 4px; }
.mg-rate-card__top time { color: #94a3b8; font-size: 12px; white-space: nowrap; }
.mg-rate-card__stars span { font-size: 16px; }
.mg-rate-card__name { font-size: 16px; font-weight: 800; color: #0a2350; margin: 0 0 4px; }
.mg-rate-card__tag {
    display: inline-block; margin-left: 6px; font-size: 11px; font-weight: 700;
    color: #0a2350; background: #e0e7ff; border-radius: 999px; padding: 2px 8px; vertical-align: middle;
}
.mg-rate-card__country { color: #64748b; font-size: 13px; margin: 0 0 6px; display: flex; align-items: center; gap: 6px; }
.mg-rate-card__comment { color: #334155; font-size: 14px; margin: 0; line-height: 1.45; }
.mg-rate-empty { color: #94a3b8; margin: 0; }
@media (max-width: 575.98px) {
    .mg-rate-page { padding-top: 18px; }
    .mg-rate-star-btn { width: 48px; height: 48px; font-size: 26px; }
}
</style>
@endsection

@section('scripts')
<script>
(function () {
    var input = document.getElementById('mg-rate-stars-input');
    var hint = document.getElementById('mg-rate-stars-hint');
    var buttons = document.querySelectorAll('.mg-rate-star-btn');
    var nameField = document.getElementById('mg-rate-name-field');
    var nameInput = document.getElementById('display_name');
    var asRadios = document.querySelectorAll('input[name="display_as"]');

    function paint(n) {
        buttons.forEach(function (btn) {
            var v = parseInt(btn.getAttribute('data-stars'), 10);
            btn.classList.toggle('is-active', v <= n);
        });
        if (input) input.value = String(n);
        if (hint) hint.textContent = n + ' / 5';
    }

    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            paint(parseInt(btn.getAttribute('data-stars'), 10));
        });
    });

    function syncNameField() {
        var as = 'voter';
        asRadios.forEach(function (r) { if (r.checked) as = r.value; });
        if (nameField) {
            nameField.style.display = as === 'contestant' ? 'none' : '';
        }
        if (nameInput) {
            nameInput.required = as !== 'contestant';
        }
    }
    asRadios.forEach(function (r) { r.addEventListener('change', syncNameField); });
    syncNameField();
})();
</script>
@endsection

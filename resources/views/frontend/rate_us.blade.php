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

                    <button type="button" class="mg-btn mg-rate-open-btn" id="mg-rate-open" aria-haspopup="dialog" aria-controls="mg-rate-modal">
                        <i class="fa fa-star"></i> {{ trans('file.Rate Us') }}
                    </button>
                </div>

                <div class="mg-rate-list-wrap">
                    <h2 class="mg-rate-section-title">{{ trans('file.What people say') }}</h2>
                    <div class="mg-rate-list">
                        @forelse($ratings as $rating)
                            <article class="mg-rate-card {{ $rating->vote_id ? 'is-from-vote' : '' }}">
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
                                    @elseif($rating->vote_id)
                                        <span class="mg-rate-card__tag mg-rate-card__tag--vote">{{ trans('file.Voter') }}</span>
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

    {{-- Rate Us modal --}}
    <div class="mg-rate-modal {{ !empty($openRateModal) ? 'is-open' : '' }}" id="mg-rate-modal" role="dialog" aria-modal="true" aria-labelledby="mg-rate-modal-title" {{ empty($openRateModal) ? 'hidden' : '' }}>
        <div class="mg-rate-modal__backdrop" data-rate-close></div>
        <div class="mg-rate-modal__sheet">
            <div class="mg-rate-modal__head">
                <h2 id="mg-rate-modal-title">{{ trans('file.Leave a rating') }}</h2>
                <button type="button" class="mg-rate-modal__close" data-rate-close aria-label="{{ trans('file.Close') }}">&times;</button>
            </div>
            <form method="post" action="{{ route('rate.us.store') }}" class="mg-rate-form" id="mg-rate-form">
                @csrf
                <input type="hidden" name="vote_id" value="{{ $vote ? $vote->id : '' }}">
                <input type="hidden" name="musician_id" value="{{ $musicianId ?? '' }}">
                <input type="hidden" name="stars" id="mg-rate-stars-input" value="{{ old('stars', 5) }}">
                <input type="hidden" name="country" id="mg-rate-country-value" value="{{ old('country', 'CM') }}" required>

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
                    <label for="mg-country-search">{{ trans('file.Country') }} *</label>
                    <div class="mg-country" id="mg-country">
                        <input type="text" id="mg-country-search" class="mg-rate-input" autocomplete="off" placeholder="{{ trans('file.Search country') }}" value="{{ \App\Helpers\CountryFlag::label(old('country', 'CM')) }}" aria-autocomplete="list" aria-controls="mg-country-list" aria-expanded="false">
                        <ul class="mg-country__list" id="mg-country-list" role="listbox" hidden>
                            @foreach($countries as $code => $label)
                                <li role="option" data-code="{{ $code }}" data-label="{{ $label }}" class="{{ old('country', 'CM') === $code ? 'is-selected' : '' }}">{{ $label }}</li>
                            @endforeach
                        </ul>
                    </div>
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
    </div>
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
.mg-rate-page__lead { color: rgba(255,255,255,.72); margin: 0 auto 18px; max-width: 420px; font-size: 15px; }
.mg-rate-avg {
    display: inline-flex; flex-direction: column; align-items: center; gap: 4px;
    padding: 14px 22px; border-radius: 18px;
    background: rgba(255,255,255,.06); border: 1px solid rgba(232,119,34,.28);
    margin-bottom: 18px;
}
.mg-rate-avg__stars span, .mg-rate-card__stars span { color: rgba(255,255,255,.25); font-size: 22px; line-height: 1; }
.mg-rate-avg__stars span.is-on, .mg-rate-card__stars span.is-on { color: #e87722; }
.mg-rate-avg__score { color: #fff; font-size: 28px; font-weight: 800; }
.mg-rate-avg__score span { font-size: 16px; color: rgba(255,255,255,.55); font-weight: 600; }
.mg-rate-avg__count { color: rgba(255,255,255,.55); font-size: 13px; }
.mg-rate-open-btn {
    min-height: 52px; padding: 14px 28px; font-size: 16px !important;
    border-radius: 16px !important; margin-bottom: 28px;
}

.mg-rate-list-wrap {
    background: #fff; border-radius: 20px; padding: 18px 16px 20px;
    box-shadow: 0 18px 50px rgba(3,12,28,.35); max-width: 720px; margin: 0 auto;
}
@media (min-width: 576px) {
    .mg-rate-list-wrap { padding: 24px 22px; }
}
.mg-rate-section-title {
    font-size: 18px; font-weight: 800; color: #0a2350; margin: 0 0 16px;
}
.mg-rate-card {
    padding: 14px 0; border-bottom: 1px solid #eef2f7;
}
.mg-rate-card:last-child { border-bottom: none; }
.mg-rate-card.is-from-vote { background: linear-gradient(90deg, rgba(232,119,34,.06), transparent); margin: 0 -8px; padding-left: 8px; padding-right: 8px; border-radius: 10px; }
.mg-rate-card__top { display: flex; justify-content: space-between; align-items: center; gap: 8px; margin-bottom: 4px; }
.mg-rate-card__top time { color: #94a3b8; font-size: 12px; white-space: nowrap; }
.mg-rate-card__stars span { font-size: 16px; color: #cbd5e1; }
.mg-rate-card__stars span.is-on { color: #e87722; }
.mg-rate-card__name { font-size: 16px; font-weight: 800; color: #0a2350; margin: 0 0 4px; }
.mg-rate-card__tag {
    display: inline-block; margin-left: 6px; font-size: 11px; font-weight: 700;
    color: #0a2350; background: #e0e7ff; border-radius: 999px; padding: 2px 8px; vertical-align: middle;
}
.mg-rate-card__tag--vote { background: rgba(232,119,34,.16); color: #c65d0a; }
.mg-rate-card__country { color: #64748b; font-size: 13px; margin: 0 0 6px; display: flex; align-items: center; gap: 6px; }
.mg-rate-card__comment { color: #334155; font-size: 14px; margin: 0; line-height: 1.45; }
.mg-rate-empty { color: #94a3b8; margin: 0; }

/* Modal */
.mg-rate-modal {
    position: fixed; inset: 0; z-index: 10060; display: none;
    align-items: flex-end; justify-content: center;
}
.mg-rate-modal.is-open { display: flex; }
.mg-rate-modal__backdrop {
    position: absolute; inset: 0; background: rgba(3,12,28,.72);
}
.mg-rate-modal__sheet {
    position: relative; z-index: 1; width: 100%; max-width: 480px;
    max-height: min(92vh, 720px); overflow: auto; -webkit-overflow-scrolling: touch;
    background: #fff; border-radius: 22px 22px 0 0;
    padding: 16px 16px calc(18px + env(safe-area-inset-bottom, 0px));
    box-shadow: 0 -12px 40px rgba(0,0,0,.35);
    animation: mg-rate-sheet .22s ease;
}
@keyframes mg-rate-sheet {
    from { transform: translateY(24px); opacity: .6; }
    to { transform: translateY(0); opacity: 1; }
}
@media (min-width: 576px) {
    .mg-rate-modal { align-items: center; padding: 16px; }
    .mg-rate-modal__sheet {
        border-radius: 20px; padding: 22px 22px 20px;
        max-height: min(88vh, 720px);
    }
}
.mg-rate-modal__head {
    display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px;
}
.mg-rate-modal__head h2 { margin: 0; font-size: 20px; font-weight: 800; color: #0a2350; }
.mg-rate-modal__close {
    width: 40px; height: 40px; border: 0; border-radius: 12px; background: #f1f5f9;
    color: #334155; font-size: 26px; line-height: 1; cursor: pointer;
}
.mg-rate-stars-picker { display: flex; justify-content: center; gap: 8px; margin-bottom: 6px; }
.mg-rate-star-btn {
    width: 52px; height: 52px; border: none; border-radius: 14px;
    background: #f1f5f9; color: #cbd5e1; font-size: 28px; line-height: 1;
    cursor: pointer; touch-action: manipulation;
}
.mg-rate-star-btn.is-active { background: rgba(232,119,34,.14); color: #e87722; }
.mg-rate-star-btn:active { transform: scale(.94); }
.mg-rate-stars-hint { text-align: center; color: #64748b; font-weight: 700; margin: 0 0 16px; }
.mg-rate-field { margin-bottom: 14px; }
.mg-rate-field > label { display: block; font-weight: 700; color: #334155; margin-bottom: 6px; font-size: 14px; }
.mg-rate-input {
    width: 100%; border: 1.5px solid #e2e8f0; border-radius: 14px;
    padding: 14px; font-size: 16px; color: #0f172a; background: #fff;
    -webkit-appearance: none; appearance: none;
}
.mg-rate-input:focus { outline: none; border-color: #e87722; box-shadow: 0 0 0 3px rgba(232,119,34,.18); }
.mg-rate-textarea { min-height: 100px; resize: vertical; }
.mg-rate-as { display: flex; flex-direction: column; gap: 8px; }
.mg-rate-as__option {
    display: flex; align-items: center; gap: 10px; padding: 12px 14px;
    border: 1.5px solid #e2e8f0; border-radius: 14px; margin: 0; cursor: pointer;
    font-weight: 600; color: #334155;
}
.mg-rate-as__option:has(input:checked),
.mg-rate-as__option.is-checked { border-color: #e87722; background: rgba(232,119,34,.06); }
.mg-rate-submit {
    width: 100%; justify-content: center; margin-top: 6px;
    min-height: 52px; font-size: 16px !important; border-radius: 16px !important;
}
.mg-rate-note { text-align: center; color: #94a3b8; font-size: 12px; margin: 12px 0 0; }

/* Searchable country */
.mg-country { position: relative; }
.mg-country__list {
    position: absolute; left: 0; right: 0; top: calc(100% + 4px); z-index: 5;
    max-height: 220px; overflow: auto; -webkit-overflow-scrolling: touch;
    margin: 0; padding: 6px 0; list-style: none;
    background: #fff; border: 1.5px solid #e2e8f0; border-radius: 14px;
    box-shadow: 0 14px 30px rgba(15,23,42,.18);
}
.mg-country__list li {
    padding: 12px 14px; cursor: pointer; font-weight: 600; color: #334155; font-size: 15px;
}
.mg-country__list li:hover,
.mg-country__list li.is-active { background: rgba(232,119,34,.10); color: #0a2350; }
.mg-country__list li.is-selected { color: #e87722; }
.mg-country__list li[hidden] { display: none !important; }
body.mg-rate-modal-open { overflow: hidden; }
@media (max-width: 575.98px) {
    .mg-rate-page { padding-top: 18px; }
    .mg-rate-star-btn { width: 48px; height: 48px; font-size: 26px; }
}
</style>
@endsection

@section('scripts')
<script>
(function () {
    var modal = document.getElementById('mg-rate-modal');
    var openBtn = document.getElementById('mg-rate-open');
    var input = document.getElementById('mg-rate-stars-input');
    var hint = document.getElementById('mg-rate-stars-hint');
    var buttons = document.querySelectorAll('.mg-rate-star-btn');
    var nameField = document.getElementById('mg-rate-name-field');
    var nameInput = document.getElementById('display_name');
    var asRadios = document.querySelectorAll('input[name="display_as"]');
    var countrySearch = document.getElementById('mg-country-search');
    var countryValue = document.getElementById('mg-rate-country-value');
    var countryList = document.getElementById('mg-country-list');
    var countryItems = countryList ? Array.prototype.slice.call(countryList.querySelectorAll('li')) : [];

    function openModal() {
        if (!modal) return;
        modal.hidden = false;
        modal.classList.add('is-open');
        document.body.classList.add('mg-rate-modal-open');
        setTimeout(function () {
            if (nameInput && nameField && nameField.style.display !== 'none') {
                nameInput.focus();
            }
        }, 180);
    }

    function closeModal() {
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.hidden = true;
        document.body.classList.remove('mg-rate-modal-open');
        closeCountryList();
    }

    if (openBtn) openBtn.addEventListener('click', openModal);
    document.querySelectorAll('[data-rate-close]').forEach(function (el) {
        el.addEventListener('click', closeModal);
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal && modal.classList.contains('is-open')) {
            closeModal();
        }
    });

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
        asRadios.forEach(function (r) {
            if (r.checked) as = r.value;
            r.closest('.mg-rate-as__option').classList.toggle('is-checked', r.checked);
        });
        if (nameField) nameField.style.display = as === 'contestant' ? 'none' : '';
        if (nameInput) nameInput.required = as !== 'contestant';
    }
    asRadios.forEach(function (r) { r.addEventListener('change', syncNameField); });
    syncNameField();

    function openCountryList() {
        if (!countryList || !countrySearch) return;
        countryList.hidden = false;
        countrySearch.setAttribute('aria-expanded', 'true');
    }
    function closeCountryList() {
        if (!countryList || !countrySearch) return;
        countryList.hidden = true;
        countrySearch.setAttribute('aria-expanded', 'false');
    }
    function filterCountries(q) {
        q = (q || '').toLowerCase().trim();
        var firstVisible = null;
        countryItems.forEach(function (li) {
            var label = (li.getAttribute('data-label') || '').toLowerCase();
            var code = (li.getAttribute('data-code') || '').toLowerCase();
            var show = !q || label.indexOf(q) > -1 || code.indexOf(q) > -1;
            li.hidden = !show;
            li.classList.toggle('is-active', false);
            if (show && !firstVisible) firstVisible = li;
        });
        if (firstVisible) firstVisible.classList.add('is-active');
    }
    function pickCountry(li) {
        if (!li) return;
        countryItems.forEach(function (item) { item.classList.remove('is-selected'); });
        li.classList.add('is-selected');
        if (countryValue) countryValue.value = li.getAttribute('data-code') || '';
        if (countrySearch) countrySearch.value = li.getAttribute('data-label') || '';
        closeCountryList();
    }

    if (countrySearch) {
        countrySearch.addEventListener('focus', function () {
            openCountryList();
            filterCountries(countrySearch.value);
            countrySearch.select();
        });
        countrySearch.addEventListener('input', function () {
            openCountryList();
            filterCountries(countrySearch.value);
            // Clear hidden value until a real option is chosen
            if (countryValue) countryValue.value = '';
        });
        countrySearch.addEventListener('keydown', function (e) {
            var visible = countryItems.filter(function (li) { return !li.hidden; });
            var active = visible.find(function (li) { return li.classList.contains('is-active'); });
            var idx = active ? visible.indexOf(active) : -1;
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                openCountryList();
                if (active) active.classList.remove('is-active');
                var next = visible[Math.min(visible.length - 1, idx + 1)] || visible[0];
                if (next) { next.classList.add('is-active'); next.scrollIntoView({ block: 'nearest' }); }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (active) active.classList.remove('is-active');
                var prev = visible[Math.max(0, idx - 1)] || visible[0];
                if (prev) { prev.classList.add('is-active'); prev.scrollIntoView({ block: 'nearest' }); }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                pickCountry(active || visible[0]);
            } else if (e.key === 'Escape') {
                closeCountryList();
            }
        });
    }
    countryItems.forEach(function (li) {
        li.addEventListener('mousedown', function (e) {
            e.preventDefault();
            pickCountry(li);
        });
    });
    document.addEventListener('click', function (e) {
        var wrap = document.getElementById('mg-country');
        if (wrap && !wrap.contains(e.target)) closeCountryList();
    });

    document.getElementById('mg-rate-form').addEventListener('submit', function (e) {
        if (!countryValue || !countryValue.value) {
            e.preventDefault();
            openCountryList();
            if (countrySearch) countrySearch.focus();
            alert(@json(trans('file.Please select your country')));
        }
    });

    @if(!empty($openRateModal))
    openModal();
    @endif
})();
</script>
@endsection

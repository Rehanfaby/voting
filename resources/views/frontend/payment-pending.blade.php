@extends('frontend.layout.main')
@section('content')

    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif
    @if(session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif

    @php $amount = $vote->grand_total; @endphp

    <main>
        <section class="mg-pay-hero pt-130 pb-24">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <span class="mg-pay-kicker">{{ trans('file.Vote payment') }}</span>
                        <h1 class="mg-pay-title">{{ trans('file.Approve on your phone') }}</h1>
                        <p class="mg-pay-lead">{{ trans('file.Check your phone for the MoMo prompt and enter your PIN') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mg-pay-body pb-80">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-7">
                        <div class="mg-pay-card mg-pay-card--compact">
                            <div class="mg-pay-summary mg-pay-summary--compact">
                                <div class="mg-pay-summary__avatar mg-pay-summary__avatar--sm">
                                    <img src="{{ \App\Helpers\ImageOptimizer::employeeImageUrl($musician->image) }}" alt="{{ $musician->name }}" loading="lazy" decoding="async">
                                </div>
                                <div class="mg-pay-summary__info">
                                    <span class="mg-pay-summary__label">{{ trans('file.Contestant') }}</span>
                                    <h2 class="mg-pay-summary__name">{{ $musician->name }}</h2>
                                    <div class="mg-pay-summary__meta">
                                        <span><i class="fa fa-vote-yea"></i> {{ $vote->vote }} {{ trans('file.Votes') }}</span>
                                        <span class="mg-pay-summary__amount">{{ number_format($amount) }} {{ $currency->code }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mg-pay-ussd">
                                <p class="mg-pay-ussd__title"><i class="fa fa-mobile-screen"></i> {{ trans('file.If you did not get a PIN prompt') }}</p>
                                <ul class="mg-pay-ussd__list">
                                    <li class="{{ ($paymentMethod ?? 'momo') === 'momo' ? 'is-active' : '' }}">
                                        <strong>MTN</strong>
                                        <span>{{ trans('file.Dial') }} <code>*126#</code> {{ trans('file.to approve your payment') }}</span>
                                    </li>
                                    <li class="{{ ($paymentMethod ?? '') === 'om' ? 'is-active' : '' }}">
                                        <strong>Orange</strong>
                                        <span>{{ trans('file.Dial') }} <code>#150*50#</code> {{ trans('file.to approve your payment') }}</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="mg-pay-pending">
                                <div class="mg-pay-pending__spinner" aria-hidden="true"></div>
                                <p class="mg-pay-pending__status" id="mg-pay-status">{{ trans('file.Waiting for payment confirmation') }}…</p>
                                <p class="mg-pay-pending__hint">{{ trans('file.You can leave this page Your vote will still count once payment is confirmed') }}</p>
                            </div>

                            <div class="mg-pay-retry" id="mg-pay-retry" style="{{ !empty($canRetry) ? '' : 'display:none;' }}">
                                <p class="mg-pay-retry__text">{{ trans('file.Still waiting You can resend the payment prompt') }}</p>
                                <form method="post" action="{{ route('musician.vote.payment.retry', $vote->id) }}" class="mb-3">
                                    @csrf
                                    <button type="submit" class="mg-btn mg-pay-retry__btn">
                                        <i class="fa fa-rotate"></i> {{ trans('file.Resend payment prompt') }}
                                    </button>
                                </form>
                                <p class="mg-pay-retry__link-label">{{ trans('file.Or open this link later') }}</p>
                                <div class="mg-pay-retry__link-row">
                                    <input type="text" readonly class="mg-pay-retry__link" id="mg-pay-link" value="{{ $pendingUrl }}">
                                    <button type="button" class="mg-pay-retry__copy" id="mg-pay-copy">{{ trans('file.Copy') }}</button>
                                </div>
                            </div>
                            <p class="mg-pay-retry-wait" id="mg-pay-retry-wait" style="{{ empty($canRetry) && (int) $vote->status === 0 ? '' : 'display:none;' }}">
                                {{ trans('file.Resend available in') }}
                                <strong id="mg-pay-countdown">{{ (int) ($secondsUntilRetry ?? 240) }}</strong>s
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('styles')
<style>
    .mg-pay-hero { background: radial-gradient(900px 420px at 50% -20%, rgba(232,119,34,.12), transparent 60%); }
    .mg-pay-kicker { display:inline-block; color:#e87722; font-weight:800; letter-spacing:2px; text-transform:uppercase; font-size:12px; margin-bottom:8px; }
    .mg-pay-title { color:#fff; font-size:32px; font-weight:800; margin:0 0 8px; }
    .mg-pay-lead { color:rgba(255,255,255,.75); font-size:15px; margin:0; }
    .mg-pay-card { background:#fff; border-radius:20px; border:1px solid rgba(232,119,34,.22); box-shadow:0 24px 60px rgba(3,12,28,.4); overflow:hidden; }
    .mg-pay-summary { display:flex; gap:14px; align-items:center; padding:18px 20px 16px; background:linear-gradient(135deg,#0c2f6b,#0a2350); color:#fff; }
    .mg-pay-summary__avatar--sm { width:64px; height:64px; flex:0 0 64px; border-radius:50%; overflow:hidden; border:2px solid #e87722; }
    .mg-pay-summary__avatar--sm img { width:100%; height:100%; object-fit:cover; display:block; }
    .mg-pay-summary__label { display:block; font-size:11px; letter-spacing:1px; text-transform:uppercase; color:rgba(255,255,255,.65); margin-bottom:2px; }
    .mg-pay-summary__name { font-size:18px; font-weight:800; margin:0 0 6px; line-height:1.25; }
    .mg-pay-summary__meta { display:flex; flex-wrap:wrap; gap:10px; align-items:center; font-size:13px; }
    .mg-pay-summary__meta i { color:#e87722; margin-right:4px; }
    .mg-pay-summary__amount { background:rgba(232,119,34,.18); border:1px solid rgba(232,119,34,.45); color:#ff9533; font-weight:800; padding:3px 10px; border-radius:20px; font-size:12px; }
    .mg-pay-ussd { padding:16px 20px 0; }
    .mg-pay-ussd__title { margin:0 0 10px; font-weight:800; color:#0a2350; font-size:14px; }
    .mg-pay-ussd__title i { color:#e87722; margin-right:6px; }
    .mg-pay-ussd__list { list-style:none; margin:0; padding:0; display:grid; gap:8px; }
    .mg-pay-ussd__list li { display:flex; flex-direction:column; gap:2px; padding:10px 12px; border-radius:12px; background:#f7f9fd; border:1px solid #dbe4f3; font-size:13px; color:#23324d; }
    .mg-pay-ussd__list li.is-active { background:#fff7f0; border-color:rgba(232,119,34,.45); box-shadow:0 0 0 2px rgba(232,119,34,.12); }
    .mg-pay-ussd__list code { font-weight:800; color:#e87722; background:rgba(232,119,34,.1); padding:1px 6px; border-radius:6px; }
    .mg-pay-pending { padding:24px 24px 20px; text-align:center; }
    .mg-pay-pending__spinner { width:44px; height:44px; margin:0 auto 16px; border:3px solid #dbe4f3; border-top-color:#e87722; border-radius:50%; animation:mg-spin .8s linear infinite; }
    @keyframes mg-spin { to { transform: rotate(360deg); } }
    .mg-pay-pending__status { font-weight:700; color:#0a2350; margin:0 0 8px; font-size:15px; }
    .mg-pay-pending__hint { color:#6b7a93; font-size:13px; margin:0; }
    .mg-pay-pending.is-success .mg-pay-pending__spinner { border-color:#28a745; border-top-color:#28a745; animation:none; }
    .mg-pay-pending.is-failed .mg-pay-pending__spinner { border-color:#dc3545; border-top-color:#dc3545; animation:none; }
    .mg-pay-retry { padding:0 20px 22px; text-align:center; border-top:1px solid #eef2f8; }
    .mg-pay-retry__text { color:#0a2350; font-weight:700; font-size:14px; margin:16px 0 12px; }
    .mg-pay-retry__btn { width:100%; justify-content:center; background:#e87722; color:#fff; border:0; border-radius:12px; padding:12px 16px; font-weight:800; }
    .mg-pay-retry__link-label { color:#6b7a93; font-size:12px; margin:0 0 8px; }
    .mg-pay-retry__link-row { display:flex; gap:8px; }
    .mg-pay-retry__link { flex:1; min-width:0; border:1px solid #dbe4f3; border-radius:10px; padding:10px 12px; font-size:12px; color:#14223f; background:#f7f9fd; }
    .mg-pay-retry__copy { border:0; border-radius:10px; background:#0a2350; color:#fff; font-weight:700; padding:0 14px; }
    .mg-pay-retry-wait { text-align:center; color:#6b7a93; font-size:13px; padding:0 20px 20px; margin:0; }
</style>
@endsection

@section('scripts')
<script>
(function () {
    var voteId = {{ (int) $vote->id }};
    var pollUrl = @json(route('musician.vote.payment.poll'));
    var homeUrl = @json(route('home'));
    var statusEl = document.getElementById('mg-pay-status');
    var pending = document.querySelector('.mg-pay-pending');
    var retryBox = document.getElementById('mg-pay-retry');
    var waitEl = document.getElementById('mg-pay-retry-wait');
    var countdownEl = document.getElementById('mg-pay-countdown');
    var secondsLeft = {{ (int) ($secondsUntilRetry ?? 0) }};
    var attempts = 0;
    var maxAttempts = 160; // ~8 minutes of polling
    var stopped = false;

    function showRetry() {
        if (retryBox) { retryBox.style.display = ''; }
        if (waitEl) { waitEl.style.display = 'none'; }
    }

    function tickCountdown() {
        if (secondsLeft <= 0) {
            showRetry();
            return;
        }
        if (countdownEl) { countdownEl.textContent = String(secondsLeft); }
        secondsLeft -= 1;
        setTimeout(tickCountdown, 1000);
    }

    if (secondsLeft > 0) {
        tickCountdown();
    } else {
        showRetry();
    }

    var copyBtn = document.getElementById('mg-pay-copy');
    var linkInput = document.getElementById('mg-pay-link');
    if (copyBtn && linkInput) {
        copyBtn.addEventListener('click', function () {
            linkInput.select();
            try {
                document.execCommand('copy');
                copyBtn.textContent = @json(trans('file.Copied'));
            } catch (e) {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(linkInput.value).then(function () {
                        copyBtn.textContent = @json(trans('file.Copied'));
                    });
                }
            }
        });
    }

    function poll() {
        if (stopped) { return; }
        if (attempts++ >= maxAttempts) {
            statusEl.textContent = @json(trans('file.Payment timed out please try again'));
            pending.classList.add('is-failed');
            showRetry();
            return;
        }
        fetch(pollUrl + '?vote_id=' + voteId, { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.status === 'SUCCESSFUL') {
                    stopped = true;
                    pending.classList.add('is-success');
                    statusEl.textContent = @json(trans('file.Thank you for your voting'));
                    if (retryBox) { retryBox.style.display = 'none'; }
                    if (waitEl) { waitEl.style.display = 'none'; }
                    setTimeout(function () { window.location.href = homeUrl; }, 1200);
                    return;
                }
                if (data.status === 'FAILED') {
                    stopped = true;
                    pending.classList.add('is-failed');
                    statusEl.textContent = data.message || @json(trans('file.Payment failed please try again'));
                    if (retryBox) { retryBox.style.display = 'none'; }
                    if (waitEl) { waitEl.style.display = 'none'; }
                    setTimeout(function () { window.location.href = homeUrl; }, 5000);
                    return;
                }
                setTimeout(poll, 3000);
            })
            .catch(function () { setTimeout(poll, 4000); });
    }
    setTimeout(poll, 2000);
})();
</script>
@endsection

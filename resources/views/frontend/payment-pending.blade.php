@extends('frontend.layout.main')
@section('content')

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

                            <div class="mg-pay-pending">
                                <div class="mg-pay-pending__spinner" aria-hidden="true"></div>
                                <p class="mg-pay-pending__status" id="mg-pay-status">{{ trans('file.Waiting for payment confirmation') }}…</p>
                                <p class="mg-pay-pending__hint">{{ trans('file.Do not close this page until payment is confirmed') }}</p>
                            </div>
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
    .mg-pay-pending { padding:28px 24px 32px; text-align:center; }
    .mg-pay-pending__spinner { width:44px; height:44px; margin:0 auto 16px; border:3px solid #dbe4f3; border-top-color:#e87722; border-radius:50%; animation:mg-spin .8s linear infinite; }
    @keyframes mg-spin { to { transform: rotate(360deg); } }
    .mg-pay-pending__status { font-weight:700; color:#0a2350; margin:0 0 8px; font-size:15px; }
    .mg-pay-pending__hint { color:#6b7a93; font-size:13px; margin:0; }
    .mg-pay-pending.is-success .mg-pay-pending__spinner { border-color:#28a745; border-top-color:#28a745; animation:none; }
    .mg-pay-pending.is-failed .mg-pay-pending__spinner { border-color:#dc3545; border-top-color:#dc3545; animation:none; }
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
    var attempts = 0;
    var maxAttempts = 120;

    function poll() {
        if (attempts++ >= maxAttempts) {
            statusEl.textContent = @json(trans('file.Payment timed out please try again'));
            pending.classList.add('is-failed');
            return;
        }
        fetch(pollUrl + '?vote_id=' + voteId, { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.status === 'SUCCESSFUL') {
                    pending.classList.add('is-success');
                    statusEl.textContent = 'Thank you for your voting';
                    setTimeout(function () { window.location.href = homeUrl; }, 1200);
                    return;
                }
                if (data.status === 'FAILED') {
                    pending.classList.add('is-failed');
                    statusEl.textContent = @json(trans('file.Payment failed please try again'));
                    setTimeout(function () { window.location.href = homeUrl; }, 2500);
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

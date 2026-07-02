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

    @php
        $user = Auth::user();
        $amount = $data['vote'] * $general_setting->vote_price;
        $stripeMin = (int) env('STRIPE_MINIMUM_AMOUNT', 300);
        $localPhone = '';
        $localWhatsapp = '';
        if ($user && $user->phone) {
            $localPhone = preg_replace('/^\+?237/', '', preg_replace('/\D/', '', $user->phone));
        }
        if ($user && $user->whatsapp_number) {
            $localWhatsapp = preg_replace('/^\+?237/', '', preg_replace('/\D/', '', $user->whatsapp_number));
        } elseif ($localPhone) {
            $localWhatsapp = $localPhone;
        }
    @endphp

    <main>
        <section class="mg-pay-hero pt-130 pb-40">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <span class="mg-pay-kicker">{{ trans('file.Vote payment') }}</span>
                        <h1 class="mg-pay-title">{{ trans('file.Complete Your Vote') }}</h1>
                        <p class="mg-pay-lead">{{ trans('file.Choose how you want to pay and confirm on WhatsApp') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mg-pay-body pb-130">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-8">
                        <div class="mg-pay-card">
                            <div class="mg-pay-summary">
                                <div class="mg-pay-summary__avatar">
                                    <img src="{{ \App\Helpers\ImageOptimizer::employeeImageUrl($musician->image) }}" alt="{{ $musician->name }}" loading="lazy" decoding="async">
                                </div>
                                <div class="mg-pay-summary__info">
                                    <span class="mg-pay-summary__label">{{ trans('file.Contestant') }}</span>
                                    <h2 class="mg-pay-summary__name">{{ $musician->name }}</h2>
                                    <div class="mg-pay-summary__meta">
                                        <span><i class="fa fa-vote-yea"></i> {{ $data['vote'] }} {{ trans('file.Votes') }}</span>
                                        <span class="mg-pay-summary__amount">{{ number_format($amount) }} {{ $currency->code }}</span>
                                    </div>
                                </div>
                            </div>

                            <form method="post" action="{{ route('musician.vote.payment') }}" id="vote-payment-form" class="mg-pay-form">
                                @csrf
                                <input type="hidden" name="musician_id" value="{{ $musician->id }}">
                                <input type="hidden" name="vote" value="{{ $data['vote'] }}">
                                <input type="hidden" name="amount" value="{{ $amount }}">

                                <fieldset class="mg-pay-methods">
                                    <legend>{{ trans('file.Payment method') }}</legend>

                                    <label class="mg-pay-method">
                                        <input type="radio" name="payment_method" value="momo" checked>
                                        <span class="mg-pay-method__box">
                                            <i class="fa-solid fa-mobile-screen"></i>
                                            <span class="mg-pay-method__title">{{ trans('file.MTN Mobile Money') }}</span>
                                            <span class="mg-pay-method__sub">{{ trans('file.Pay with MTN MoMo') }}</span>
                                        </span>
                                    </label>

                                    <label class="mg-pay-method">
                                        <input type="radio" name="payment_method" value="om">
                                        <span class="mg-pay-method__box">
                                            <i class="fa-solid fa-signal"></i>
                                            <span class="mg-pay-method__title">{{ trans('file.Orange Money') }}</span>
                                            <span class="mg-pay-method__sub">{{ trans('file.Pay with Orange Money') }}</span>
                                        </span>
                                    </label>

                                    <label class="mg-pay-method {{ $amount < $stripeMin ? 'is-disabled' : '' }}">
                                        <input type="radio" name="payment_method" value="card" {{ $amount < $stripeMin ? 'disabled' : '' }}>
                                        <span class="mg-pay-method__box">
                                            <i class="fa-brands fa-cc-visa"></i>
                                            <span class="mg-pay-method__title">{{ trans('file.Visa / Mastercard') }}</span>
                                            <span class="mg-pay-method__sub">
                                                @if($amount < $stripeMin)
                                                    {{ trans('file.Minimum amount for stripe is') }} {{ $stripeMin }} {{ $currency->code }}
                                                @else
                                                    {{ trans('file.Secure card payment via Stripe') }}
                                                @endif
                                            </span>
                                        </span>
                                    </label>
                                </fieldset>

                                <div id="mg-pay-mobile-fields">
                                    <div class="mg-field">
                                        <label for="phone_local">{{ trans('file.Momo Number') }}</label>
                                        <div class="mg-phone-input">
                                            <span class="mg-phone-prefix">🇨🇲 +237</span>
                                            <input id="phone_local" type="tel" name="phone_local" inputmode="numeric" maxlength="9" placeholder="6XX XXX XXX" value="{{ old('phone_local', $localPhone) }}" required>
                                        </div>
                                        <small class="mg-field-hint">{{ trans('file.Enter your number without the country code') }}</small>
                                    </div>
                                </div>

                                <div class="mg-field">
                                    <label for="whatsapp_local"><i class="fab fa-whatsapp"></i> {{ trans('file.Whatsapp number') }}</label>
                                    <div class="mg-phone-input">
                                        <span class="mg-phone-prefix">🇨🇲 +237</span>
                                        <input id="whatsapp_local" type="tel" name="whatsapp_local" inputmode="numeric" maxlength="9" placeholder="6XX XXX XXX" value="{{ old('whatsapp_local', $localWhatsapp) }}" required>
                                    </div>
                                    <small class="mg-field-hint">{{ trans('file.Confirmation will be sent to this WhatsApp number') }}</small>
                                </div>

                                <button type="submit" class="mg-btn mg-pay-submit" id="mg-pay-submit-btn">
                                    <i class="fa-solid fa-lock"></i>
                                    {{ trans('file.Pay') }} {{ number_format($amount) }} {{ $currency->code }}
                                </button>
                            </form>
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
    .mg-pay-kicker { display:inline-block; color:#e87722; font-weight:800; letter-spacing:2px; text-transform:uppercase; font-size:13px; margin-bottom:12px; }
    .mg-pay-title { color:#fff; font-size:38px; font-weight:800; margin:0 0 10px; }
    .mg-pay-lead { color:rgba(255,255,255,.75); font-size:16px; margin:0; }
    .mg-pay-card { background:#fff; border-radius:24px; border:1px solid rgba(232,119,34,.22); box-shadow:0 30px 80px rgba(3,12,28,.45); overflow:hidden; }
    .mg-pay-summary { display:flex; gap:18px; align-items:center; padding:28px 28px 22px; background:linear-gradient(135deg,#0c2f6b,#0a2350); color:#fff; }
    .mg-pay-summary__avatar { width:88px; height:88px; flex:0 0 88px; border-radius:50%; overflow:hidden; border:3px solid #e87722; box-shadow:0 0 0 5px rgba(232,119,34,.25); }
    .mg-pay-summary__avatar img { width:100%; height:100%; object-fit:cover; display:block; }
    .mg-pay-summary__label { display:block; font-size:12px; letter-spacing:1px; text-transform:uppercase; color:rgba(255,255,255,.65); margin-bottom:4px; }
    .mg-pay-summary__name { font-size:22px; font-weight:800; margin:0 0 10px; line-height:1.25; }
    .mg-pay-summary__meta { display:flex; flex-wrap:wrap; gap:12px; align-items:center; font-size:14px; }
    .mg-pay-summary__meta i { color:#e87722; margin-right:4px; }
    .mg-pay-summary__amount { background:rgba(232,119,34,.18); border:1px solid rgba(232,119,34,.45); color:#ff9533; font-weight:800; padding:4px 12px; border-radius:20px; }
    .mg-pay-form { padding:28px; }
    .mg-pay-methods { border:0; margin:0 0 24px; padding:0; }
    .mg-pay-methods legend { font-size:15px; font-weight:800; color:#0a2350; margin-bottom:14px; }
    .mg-pay-method { display:block; margin-bottom:12px; cursor:pointer; }
    .mg-pay-method input { position:absolute; opacity:0; pointer-events:none; }
    .mg-pay-method__box { display:flex; flex-direction:column; gap:3px; padding:16px 18px; border:2px solid #dbe4f3; border-radius:14px; background:#f7f9fd; transition:border-color .2s, box-shadow .2s, background .2s; }
    .mg-pay-method__box i { color:#e87722; font-size:20px; margin-bottom:4px; }
    .mg-pay-method__title { font-weight:800; color:#0a2350; font-size:15px; }
    .mg-pay-method__sub { font-size:13px; color:#5b6b86; }
    .mg-pay-method input:checked + .mg-pay-method__box { border-color:#e87722; background:#fff7f0; box-shadow:0 0 0 3px rgba(232,119,34,.12); }
    .mg-pay-method.is-disabled { opacity:.55; cursor:not-allowed; }
    .mg-field { margin-bottom:20px; }
    .mg-field label { display:block; font-weight:700; color:#0a2350; margin-bottom:8px; font-size:14px; }
    .mg-field-hint { display:block; margin-top:6px; color:#6b7a93; font-size:12.5px; }
    .mg-phone-input { display:flex; align-items:stretch; border:1px solid #dbe4f3; border-radius:12px; overflow:hidden; background:#f7f9fd; }
    .mg-phone-prefix { display:inline-flex; align-items:center; padding:0 14px; background:#0a2350; color:#fff; font-weight:700; font-size:14px; white-space:nowrap; border-right:1px solid #dbe4f3; }
    .mg-phone-input input { flex:1; border:0; background:transparent; padding:14px 16px; font-size:16px; color:#14223f; outline:none; }
    .mg-phone-input input:focus { background:#fff; }
    .mg-pay-submit { width:100%; justify-content:center; margin-top:8px; font-size:16px; padding:15px 24px; }
    @media (max-width:575px) {
        .mg-pay-summary { flex-direction:column; text-align:center; }
        .mg-pay-title { font-size:30px; }
        .mg-pay-form { padding:20px 18px; }
    }
</style>
@endsection

@section('scripts')
<script>
(function () {
    var form = document.getElementById('vote-payment-form');
    if (!form) { return; }

    var mobileFields = document.getElementById('mg-pay-mobile-fields');
    var phoneInput = document.getElementById('phone_local');
    var radios = form.querySelectorAll('input[name="payment_method"]');

    function digitsOnly(el) {
        el.addEventListener('input', function () {
            el.value = el.value.replace(/\D/g, '').slice(0, 9);
        });
    }
    digitsOnly(phoneInput);
    digitsOnly(document.getElementById('whatsapp_local'));

    function syncMethod() {
        var method = form.querySelector('input[name="payment_method"]:checked');
        var isCard = method && method.value === 'card';
        mobileFields.style.display = isCard ? 'none' : 'block';
        phoneInput.required = !isCard;
    }

    radios.forEach(function (r) { r.addEventListener('change', syncMethod); });
    syncMethod();
})();
</script>
@endsection

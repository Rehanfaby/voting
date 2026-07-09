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
        $paymentSimulate = \App\Helpers\PhoneHelper::paymentSimulate();
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
        $localPhone = \App\Helpers\PhoneHelper::defaultPaymentLocal($localPhone);
        $localWhatsapp = \App\Helpers\PhoneHelper::defaultPaymentLocal($localWhatsapp ?: $localPhone);
        $voterName = '';
        if ($user && !\App\Helpers\PhoneHelper::looksLikePhone($user->name)) {
            $voterName = $user->name;
        }
    @endphp

    <main>
        <section class="mg-pay-hero pt-130 pb-24">
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

                                <fieldset class="mg-pay-methods mg-pay-methods--grid">
                                    <legend>{{ trans('file.Payment method') }}</legend>

                                    <label class="mg-pay-method">
                                        <input type="radio" name="payment_method" value="momo" checked>
                                        <span class="mg-pay-method__box">
                                            <i class="fa-solid fa-mobile-screen"></i>
                                            <span class="mg-pay-method__title">{{ trans('file.MTN Mobile Money') }}</span>
                                        </span>
                                    </label>

                                    <label class="mg-pay-method">
                                        <input type="radio" name="payment_method" value="om">
                                        <span class="mg-pay-method__box">
                                            <i class="fa-solid fa-signal"></i>
                                            <span class="mg-pay-method__title">{{ trans('file.Orange Money') }}</span>
                                        </span>
                                    </label>

                                    <label class="mg-pay-method {{ $amount < $stripeMin ? 'is-disabled' : '' }}">
                                        <input type="radio" name="payment_method" value="card" {{ $amount < $stripeMin ? 'disabled' : '' }}>
                                        <span class="mg-pay-method__box">
                                            <i class="fa-brands fa-cc-visa"></i>
                                            <span class="mg-pay-method__title">{{ trans('file.Visa / Mastercard') }}</span>
                                        </span>
                                    </label>
                                </fieldset>

                                <div class="mg-field">
                                    <label for="voter_name">{{ trans('file.Voter name') }}</label>
                                    <input id="voter_name" type="text" name="voter_name" class="mg-text-input" placeholder="{{ trans('file.Enter your full name') }}" value="{{ old('voter_name', $voterName) }}" required maxlength="120">
                                </div>

                                @if($paymentSimulate)
                                    <div class="alert alert-info py-2 px-3 mb-3" style="font-size:13px;border-radius:10px;">
                                        <i class="fa fa-flask"></i> {{ trans('file.Payment simulation mode') }}
                                    </div>
                                @endif

                                <div id="mg-pay-mobile-fields">
                                    @include('partials.cameroon-phone-field', [
                                        'id' => 'phone_local',
                                        'name' => 'phone_local',
                                        'label' => trans('file.Momo Number'),
                                        'value' => $localPhone,
                                    ])
                                </div>

                                @include('partials.cameroon-phone-field', [
                                    'id' => 'whatsapp_local',
                                    'name' => 'whatsapp_local',
                                    'label' => '<i class="fab fa-whatsapp"></i> ' . trans('file.Whatsapp number'),
                                    'value' => $localWhatsapp,
                                    'hint' => trans('file.Confirmation will be sent to this WhatsApp number'),
                                ])

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
    .mg-pay-kicker { display:inline-block; color:#e87722; font-weight:800; letter-spacing:2px; text-transform:uppercase; font-size:12px; margin-bottom:8px; }
    .mg-pay-title { color:#fff; font-size:32px; font-weight:800; margin:0 0 8px; }
    .mg-pay-lead { color:rgba(255,255,255,.75); font-size:15px; margin:0; }
    .mg-pay-card { background:#fff; border-radius:20px; border:1px solid rgba(232,119,34,.22); box-shadow:0 24px 60px rgba(3,12,28,.45); overflow:hidden; }
    .mg-pay-summary { display:flex; gap:14px; align-items:center; padding:18px 20px 14px; background:linear-gradient(135deg,#0c2f6b,#0a2350); color:#fff; }
    .mg-pay-summary__avatar--sm { width:64px; height:64px; flex:0 0 64px; border-radius:50%; overflow:hidden; border:2px solid #e87722; box-shadow:0 0 0 4px rgba(232,119,34,.2); }
    .mg-pay-summary__avatar--sm img { width:100%; height:100%; object-fit:cover; display:block; }
    .mg-pay-summary__label { display:block; font-size:11px; letter-spacing:1px; text-transform:uppercase; color:rgba(255,255,255,.65); margin-bottom:2px; }
    .mg-pay-summary__name { font-size:18px; font-weight:800; margin:0 0 6px; line-height:1.25; }
    .mg-pay-summary__meta { display:flex; flex-wrap:wrap; gap:10px; align-items:center; font-size:13px; }
    .mg-pay-summary__meta i { color:#e87722; margin-right:4px; }
    .mg-pay-summary__amount { background:rgba(232,119,34,.18); border:1px solid rgba(232,119,34,.45); color:#ff9533; font-weight:800; padding:3px 10px; border-radius:20px; font-size:12px; }
    .mg-pay-form { padding:18px 20px 22px; }
    .mg-pay-methods { border:0; margin:0 0 14px; padding:0; }
    .mg-pay-methods legend { font-size:14px; font-weight:800; color:#0a2350; margin-bottom:10px; }
    .mg-pay-methods--grid { display:grid; grid-template-columns:repeat(3, 1fr); gap:8px; }
    .mg-pay-method { display:block; margin:0; cursor:pointer; }
    .mg-pay-method input { position:absolute; opacity:0; pointer-events:none; }
    .mg-pay-method__box { display:flex; flex-direction:column; align-items:center; text-align:center; gap:4px; padding:10px 8px; border:2px solid #dbe4f3; border-radius:12px; background:#f7f9fd; transition:border-color .2s, box-shadow .2s, background .2s; min-height:72px; justify-content:center; }
    .mg-pay-method__box i { color:#e87722; font-size:18px; margin:0; }
    .mg-pay-method__title { font-weight:700; color:#0a2350; font-size:12px; line-height:1.2; }
    .mg-pay-method input:checked + .mg-pay-method__box { border-color:#e87722; background:#fff7f0; box-shadow:0 0 0 3px rgba(232,119,34,.12); }
    .mg-pay-method.is-disabled { opacity:.55; cursor:not-allowed; }
    .mg-field { margin-bottom:12px; }
    .mg-field label { display:block; font-weight:700; color:#0a2350; margin-bottom:6px; font-size:13px; }
    .mg-field-hint { display:block; margin-top:4px; color:#6b7a93; font-size:11.5px; }
    .mg-text-input { width:100%; border:1px solid #dbe4f3; border-radius:12px; background:#fff !important; padding:11px 14px; font-size:15px; color:#14223f !important; -webkit-text-fill-color:#14223f; outline:none; }
    .mg-text-input::placeholder { color:#94a3b8 !important; -webkit-text-fill-color:#94a3b8; }
    .mg-text-input:focus { background:#fff !important; border-color:#e87722; }
    .mg-pay-card .cm-phone-field__input { color:#14223f !important; -webkit-text-fill-color:#14223f; }
    .mg-pay-card .cm-phone-field__input::placeholder { color:#94a3b8 !important; -webkit-text-fill-color:#94a3b8; }
    .mg-pay-submit { width:100%; justify-content:center; margin-top:4px; font-size:15px; padding:13px 20px; }
    @media (max-width:575px) {
        .mg-pay-summary { flex-direction:column; text-align:center; }
        .mg-pay-title { font-size:26px; }
        .mg-pay-form { padding:16px 14px 18px; }
        .mg-pay-methods--grid { grid-template-columns:1fr; }
        .mg-pay-method__box { flex-direction:row; text-align:left; min-height:auto; padding:12px 14px; }
    }
</style>
@endsection

@section('scripts')
<script>
(function () {
    var form = document.getElementById('vote-payment-form');
    if (!form) { return; }

    var mobileFields = document.getElementById('mg-pay-mobile-fields');
    var phoneHidden = document.querySelector('#phone_local[data-cm-phone-hidden], input[name="phone_local"]');
    var radios = form.querySelectorAll('input[name="payment_method"]');

    function syncMethod() {
        var method = form.querySelector('input[name="payment_method"]:checked');
        var isCard = method && method.value === 'card';
        mobileFields.style.display = isCard ? 'none' : 'block';
        if (phoneHidden) {
            phoneHidden.required = !isCard;
        }
        var waHidden = form.querySelector('input[name="whatsapp_local"]');
        if (waHidden) {
            waHidden.required = !isCard;
        }
    }

    radios.forEach(function (r) { r.addEventListener('change', syncMethod); });
    syncMethod();
})();
</script>
@endsection

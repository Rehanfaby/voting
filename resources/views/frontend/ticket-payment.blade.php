@extends('frontend.layout.main')
@section('content')

    @php
        $user = \Illuminate\Support\Facades\Auth::user();
        $payAmount = $data['amount'] ?? ($data['vote'] * $ticket->price);
        $seatLabels = $data['seat_labels'] ?? null;
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
    @endphp

    <main class="mg-tickets mg-ticket-pay">
        <section class="mg-tickets__hero pt-130 pb-40">
            <div class="container text-center">
                <span class="mg-tickets__badge">{{ trans('file.Ticket payment') }}</span>
                <h1 class="mg-tickets__title">{{ trans('file.Complete your purchase') }}</h1>
                @if($ticket->category ?? null)
                    @include('partials.event_countdown', ['event' => $ticket->category, 'class' => 'mg-event-countdown--hero'])
                @endif
            </div>
        </section>

        <section class="pb-130">
            <div class="container">
                @if(session()->has('not_permitted'))
                    <div class="alert alert-danger text-center mb-4">{{ session()->get('not_permitted') }}</div>
                @endif

                <div class="mg-ticket-summary mb-5">
                    <div class="mg-ticket-summary__row">
                        <img src="{{ url('public/images/product', explode(',', $ticket->image)[0]) }}" alt="">
                        <div>
                            <strong>{{ $ticket->name }}</strong>
                            @if($seatLabels)
                                <p class="mb-0"><i class="fa-solid fa-chair"></i> {{ trans('file.Seats') }}: {{ $seatLabels }}</p>
                            @endif
                            <span>{{ trans('file.Qty') }}: {{ $data['vote'] }}</span>
                        </div>
                        <div class="mg-ticket-summary__amount">
                            {{ number_format($payAmount) }} {{ $currency->code }}
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="mg-pay-card">
                            <h3><i class="fa-solid fa-mobile-screen"></i> {{ trans('file.Pay By MOMO or OM') }}</h3>
                            <form method="post" action="{{ route('ticket.payment') }}">
                                @csrf
                                @if($paymentSimulate)
                                    <div class="alert alert-info py-2 px-3 mb-3" style="font-size:13px;border-radius:10px;">
                                        <i class="fa fa-flask"></i> {{ trans('file.Payment simulation mode') }}
                                    </div>
                                @endif
                                @if(!$user)
                                    <input type="text" name="name" required placeholder="{{ trans('file.Name') }}">
                                    <input type="text" name="email" required placeholder="email">
                                    <select class="mg-pay-select" name="identity_type" id="identity_type_momo" required>
                                        <option value="1">{{ __('NIC') }}</option>
                                        <option value="2">{{ __('Student ID') }}</option>
                                        <option value="3">{{ __('Passport') }}</option>
                                        <option value="4">{{ __('Others') }}</option>
                                    </select>
                                    <input type="text" name="identity_number" class="id-field id-nic" placeholder="{{ __('Identity Number') }}">
                                    <input type="text" name="student_number" class="id-field id-student" placeholder="{{ __('Student Number') }}" style="display:none;">
                                    <input type="text" name="passport_number" class="id-field id-passport" placeholder="{{ __('Passport Number') }}" style="display:none;">
                                @else
                                    <input type="text" name="name" required value="{{ $user->name }}" placeholder="{{ trans('file.Name') }}">
                                    <input type="text" name="email" required value="{{ $user->email }}" placeholder="email">
                                    <select class="mg-pay-select" name="identity_type" id="identity_type_momo" required>
                                        <option value="1">{{ __('NIC') }}</option>
                                        <option value="2">{{ __('Student ID') }}</option>
                                        <option value="3">{{ __('Passport') }}</option>
                                        <option value="4">{{ __('Others') }}</option>
                                    </select>
                                    <input type="text" name="identity_number" class="id-field id-nic" placeholder="{{ __('Identity Number') }}">
                                    <input type="text" name="student_number" class="id-field id-student" placeholder="{{ __('Student Number') }}" style="display:none;">
                                    <input type="text" name="passport_number" class="id-field id-passport" placeholder="{{ __('Passport Number') }}" style="display:none;">
                                @endif
                                @include('partials.cameroon-phone-field', [
                                    'id' => 'ticket_phone_local',
                                    'name' => 'phone_local',
                                    'label' => trans('file.Momo Number'),
                                    'value' => $localPhone,
                                ])
                                <p class="mg-field-hint" style="margin:-6px 0 12px;color:#9a3412;font-weight:600;">
                                    {{ trans('file.Personal MoMo only tip') }}
                                </p>
                                @include('partials.intl-phone-field', [
                                    'id' => 'ticket_whatsapp_intl',
                                    'name' => 'whatsapp_intl',
                                    'label' => '<i class="fab fa-whatsapp"></i> ' . trans('file.Whatsapp number'),
                                    'value' => ($user && $user->whatsapp_number) ? $user->whatsapp_number : $localWhatsapp,
                                    'defaultDial' => '237',
                                    'hint' => trans('file.Confirmation will be sent to this WhatsApp number'),
                                ])
                                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                <input type="hidden" name="qty" value="{{ $data['vote'] }}">
                                <input type="hidden" name="amount" value="{{ $payAmount }}">
                                @if(!empty($data['seat_ids']))
                                    <input type="hidden" name="seat_ids" value="{{ $data['seat_ids'] }}">
                                @endif
                                <button type="submit" class="mg-btn mg-ticket-buy w-100">
                                    {{ trans('file.Pay') }} {{ number_format($payAmount) }} {{ $currency->code }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mg-pay-card">
                            <h3><i class="fa-solid fa-credit-card"></i> {{ trans('file.Pay By Visa or Master Card') }}</h3>
                            <p class="mg-pay-note">{{ trans('file.Minimum amount for stripe is') }} {{ env('STRIPE_MINIMUM_AMOUNT') }}</p>
                            <form method="post" action="{{ route('ticket.payment.stripe') }}">
                                @csrf
                                @if(!$user)
                                    <input type="text" name="name" required placeholder="{{ trans('file.Name') }}">
                                    <input type="text" name="email" required placeholder="email">
                                    <select class="mg-pay-select" name="identity_type" id="identity_type_card" required>
                                        <option value="1">{{ __('ID') }}</option>
                                        <option value="2">{{ __('Student No.') }}</option>
                                        <option value="3">{{ __('Passport') }}</option>
                                        <option value="4">{{ __('Others') }}</option>
                                    </select>
                                    <input type="text" name="identity_number" placeholder="{{ __('Identity Number') }}">
                                    <input type="text" name="phone" required placeholder="{{ trans('file.Phone number') }}" value="+237">
                                @else
                                    <input type="text" name="name" required value="{{ $user->name }}" placeholder="{{ trans('file.Name') }}">
                                    <input type="text" name="email" required value="{{ $user->email }}" placeholder="email">
                                    <select class="mg-pay-select" name="identity_type" id="identity_type_card" required>
                                        <option value="1">{{ __('ID') }}</option>
                                        <option value="2">{{ __('Student No.') }}</option>
                                        <option value="3">{{ __('Passport') }}</option>
                                        <option value="4">{{ __('Others') }}</option>
                                    </select>
                                    <input type="text" name="identity_number" placeholder="{{ __('Identity Number') }}">
                                    <input type="text" name="phone" required value="{{ $user->phone }}" placeholder="{{ trans('file.Phone number') }}">
                                @endif
                                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                <input type="hidden" name="qty" value="{{ $data['vote'] }}">
                                <input type="hidden" name="amount" value="{{ $payAmount }}">
                                @if(!empty($data['seat_ids']))
                                    <input type="hidden" name="seat_ids" value="{{ $data['seat_ids'] }}">
                                @endif
                                <button type="submit" class="mg-btn mg-ticket-buy w-100" @if($payAmount < env('STRIPE_MINIMUM_AMOUNT')) disabled @endif>
                                    {{ trans('file.Pay') }} {{ number_format($payAmount) }} {{ $currency->code }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
<script src="{{ asset('public/js/event-countdown.js') }}"></script>
<script>
document.querySelectorAll('[id^="identity_type_"]').forEach(function (sel) {
    sel.addEventListener('change', function () {
        var form = sel.closest('form');
        form.querySelectorAll('.id-field').forEach(function (f) { f.style.display = 'none'; });
        var map = { '1': '.id-nic', '2': '.id-student', '3': '.id-passport' };
        var target = form.querySelector(map[sel.value]);
        if (target) target.style.display = '';
    });
    sel.dispatchEvent(new Event('change'));
});
</script>
@endsection

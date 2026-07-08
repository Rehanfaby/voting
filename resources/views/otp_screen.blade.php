<?php $general_setting = DB::table('general_settings')->find(1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $general_setting->site_title }} — {{ trans('file.Verify OTP') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ url('public/logo', $general_setting->site_logo) }}" />
    @include('auth.partials.auth-standalone-style')
</head>
<body>
    <div class="auth-wrap">
        <div class="auth-card">
            <div class="auth-card__head">
                @include('auth.partials.auth-standalone-logo')
                <h1 class="auth-title">{{ $general_setting->site_title }}</h1>
                <p class="auth-sub">{{ trans('file.OTP login instructions') }}</p>
            </div>

            <div class="auth-body">
                @if(!empty($otp_send_failed))
                    <div class="alert-box">{{ trans('file.OTP delivery failed') }}</div>
                @endif
                @if(session()->has('not_permitted'))
                    <div class="alert-box">{{ session()->get('not_permitted') }}</div>
                @endif
                @if(session()->has('message'))
                    <div class="alert-box success">{{ session()->get('message') }}</div>
                @endif

                @if(Auth::user()->is_active)
                <form action="{{ route('check.otp.store') }}" method="post">
                    @csrf
                    <div class="otp-field">
                        <input id="login-otp" type="text" name="otp" required inputmode="numeric" maxlength="6" placeholder="000000" autocomplete="one-time-code" autofocus>
                    </div>
                    <p class="otp-timer" id="otp-expiry">{{ trans('file.Code expires in') }} <span id="otp-expiry-time">3:00</span></p>
                    <button type="submit" class="btn-auth">{{ trans('file.Verify OTP') }}</button>
                </form>

                <div class="resend-row">
                    <span id="resend-label">{{ trans('file.Resend OTP in') }} <span id="resend-seconds">60</span>s</span>
                    <form id="resend-form" action="{{ route('check.otp.resend') }}" method="post" style="display:none;">
                        @csrf
                        <button type="submit" id="resend-btn">{{ trans('file.Resend OTP') }}</button>
                    </form>
                </div>

                <div class="back-row">
                    <a href="{{ route('home') }}"><i class="fa fa-home"></i> {{ trans('file.Back to Homepage') }}</a>
                </div>
                <div class="back-row" style="margin-top:10px;">
                    <form action="{{ route('check.otp.cancel') }}" method="post">
                        @csrf
                        <button type="submit"><i class="fa fa-arrow-left"></i> {{ trans('file.Back to Login') }}</button>
                    </form>
                </div>
                @else
                <div class="alert-box">{{ trans('file.Account not activated contact admin') }}</div>
                @endif
            </div>
        </div>
    </div>

    @include('auth.partials.auth-standalone-credit')

    <script>
        (function () {
            var sentAt = @json($otp_sent_at ? strtotime($otp_sent_at) * 1000 : null);
            var expiryEl = document.getElementById('otp-expiry');
            var expiryTimeEl = document.getElementById('otp-expiry-time');
            var resendLabel = document.getElementById('resend-label');
            var resendSecondsEl = document.getElementById('resend-seconds');
            var resendForm = document.getElementById('resend-form');
            var otpInput = document.getElementById('login-otp');

            if (otpInput) {
                otpInput.addEventListener('input', function () {
                    this.value = this.value.replace(/\D/g, '').slice(0, 6);
                });
            }

            function pad(n) { return n < 10 ? '0' + n : '' + n; }

            function tick() {
                var now = Date.now();
                if (sentAt) {
                    var expiryMs = sentAt + (3 * 60 * 1000);
                    var left = Math.max(0, Math.ceil((expiryMs - now) / 1000));
                    var m = Math.floor(left / 60);
                    var s = left % 60;
                    if (expiryTimeEl) {
                        expiryTimeEl.textContent = m + ':' + pad(s);
                    }
                    if (left === 0 && expiryEl) {
                        expiryEl.classList.add('expired');
                        expiryEl.textContent = @json(trans('file.OTP code expired'));
                    }

                    var resendLeft = Math.max(0, 60 - Math.floor((now - sentAt) / 1000));
                    if (resendLeft > 0) {
                        if (resendSecondsEl) resendSecondsEl.textContent = resendLeft;
                    } else if (resendForm && resendLabel) {
                        resendLabel.style.display = 'none';
                        resendForm.style.display = 'inline';
                    }
                }
            }

            tick();
            setInterval(tick, 1000);
        })();
    </script>
</body>
</html>

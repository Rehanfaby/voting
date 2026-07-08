<?php $general_setting = DB::table('general_settings')->find(1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $general_setting->site_title }} — {{ trans('file.Verify OTP') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ url('public/logo', $general_setting->site_logo) }}" />
    <link rel="stylesheet" href="<?php echo asset('public/vendor/font-awesome/css/font-awesome.min.css') ?>" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">
    <style>
        :root {
            --brand-blue: #1d4ed8;
            --brand-gold: #f5c518;
            --brand-navy: #0a2350;
            --brand-grad: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
            --ink: #14223f;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Poppins', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0c2f6b 0%, #0a2350 55%, #07172f 100%);
            color: var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .auth-wrap { position: relative; z-index: 1; width: 100%; max-width: 430px; padding: 24px; }
        .auth-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 24px 70px rgba(3, 12, 28, .45);
            overflow: hidden;
        }
        .auth-card__head {
            text-align: center;
            padding: 38px 30px 26px;
            background: radial-gradient(120% 120% at 50% 0%, rgba(29,78,216,.14), transparent 65%);
            border-bottom: 4px solid;
            border-image: var(--brand-grad) 1;
        }
        .auth-logo {
            width: 96px; height: 96px; margin: 0 auto 14px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(29,78,216,.12), rgba(245,197,24,.12));
            border: 2px solid rgba(245,197,24,.45);
        }
        .auth-logo img { max-width: 74px; max-height: 74px; border-radius: 50%; }
        .auth-title { margin: 4px 0 2px; font-size: 22px; font-weight: 700; color: var(--ink); }
        .auth-sub { margin: 0; font-size: 13.5px; color: #64748b; line-height: 1.5; }
        .auth-body { padding: 26px 32px 34px; }
        .otp-field input {
            width: 100%;
            padding: 16px;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 12px;
            text-align: center;
            color: var(--ink);
            background: #fff;
            border: 2px solid var(--brand-gold);
            border-radius: 14px;
            outline: none;
            font-family: inherit;
        }
        .otp-field input:focus { box-shadow: 0 0 0 3px rgba(245,197,24,.25); }
        .otp-timer {
            text-align: center;
            margin: 12px 0 18px;
            font-size: 13px;
            font-weight: 600;
            color: #dc2626;
        }
        .otp-timer.expired { color: #94a3b8; }
        .btn-verify {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            background: var(--brand-grad);
            cursor: pointer;
        }
        .btn-verify:hover { filter: brightness(1.05); }
        .resend-row { text-align: center; margin-top: 16px; font-size: 13px; color: #64748b; }
        .resend-row button {
            background: none; border: none; padding: 0;
            color: var(--brand-blue); font-weight: 600; cursor: pointer;
            font-family: inherit; font-size: inherit;
        }
        .resend-row button:disabled { color: #94a3b8; cursor: not-allowed; }
        .back-row { text-align: center; margin-top: 20px; }
        .back-row button {
            background: none; border: none; padding: 0;
            color: #64748b; font-size: 13.5px; cursor: pointer; font-family: inherit;
        }
        .back-row button:hover { color: var(--brand-blue); }
        .alert-box {
            background: #fdecec; border: 1px solid #f5c2c7; color: #b02a37;
            padding: 11px 14px; border-radius: 12px; font-size: 13px;
            margin-bottom: 18px; text-align: center;
        }
        .alert-box.success { background: #e7f6ec; border-color: #a8dab7; color: #1d7a3c; }
        .page-version {
            position: fixed; bottom: 12px; left: 0; right: 0;
            text-align: center; font-size: 11px; font-weight: 700;
            letter-spacing: .6px; color: rgba(255,255,255,.45);
        }
    </style>
</head>
<body>
    <div class="auth-wrap">
        <div class="auth-card">
            <div class="auth-card__head">
                <div class="auth-logo">
                    @if($general_setting->site_logo)
                        <img src="{{ url('public/logo', $general_setting->site_logo) }}" alt="logo">
                    @else
                        <i class="fa fa-shield" style="font-size:40px;color:#1d4ed8;"></i>
                    @endif
                </div>
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
                    <button type="submit" class="btn-verify">{{ trans('file.Verify OTP') }}</button>
                </form>

                <div class="resend-row">
                    <span id="resend-label">{{ trans('file.Resend OTP in') }} <span id="resend-seconds">60</span>s</span>
                    <form id="resend-form" action="{{ route('check.otp.resend') }}" method="post" style="display:none;">
                        @csrf
                        <button type="submit" id="resend-btn">{{ trans('file.Resend OTP') }}</button>
                    </form>
                </div>

                <div class="back-row">
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

    <div class="page-version">{{ strtoupper(substr($general_setting->site_title, 0, 3)) }} V.{{ config('app.version') }}</div>

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

<?php $general_setting = DB::table('general_settings')->find(1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $general_setting->site_title }} — Verify OTP</title>
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}" />
    @include('auth.partials.auth-style')
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-card__head">
            <div class="auth-logo"><i class="fa fa-shield step-icon"></i></div>
            <h1 class="auth-title">Verify OTP</h1>
            <p class="auth-sub">Enter the code we sent to your WhatsApp</p>
            <span class="version-badge">V.{{ config('app.version') }}</span>
        </div>

        <div class="auth-body">
            @if(session()->has('not_permitted'))
                <div class="alert-box">{{ session()->get('not_permitted') }}</div>
            @endif
            @if(session()->has('message'))
                <div class="alert-box success">{{ session()->get('message') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.password.verify.submit') }}">
                @csrf
                <div class="field otp-input">
                    <input type="text" name="otp" placeholder="------" inputmode="numeric" required autofocus>
                </div>
                @if ($errors->has('otp'))
                    <p class="field-error">{{ $errors->first('otp') }}</p>
                @endif

                <button type="submit" class="btn-login"><i class="fa fa-check"></i> Verify Code</button>
            </form>

            <div class="forgot-row">
                <a href="{{ route('admin.password.request') }}"><i class="fa fa-redo"></i> Resend / change number</a>
            </div>
        </div>

        <div class="auth-foot">
            {{ env('DEVELOPED_BY') ?? 'Mulema GC' }} &middot; <span class="external">V.{{ config('app.version') }}</span>
        </div>
    </div>
</div>
</body>
</html>

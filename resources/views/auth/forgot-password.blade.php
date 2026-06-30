<?php $general_setting = DB::table('general_settings')->find(1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $general_setting->site_title }} — Forgot Password</title>
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}" />
    @include('auth.partials.auth-style')
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-card__head">
            <div class="auth-logo">
                @if($general_setting->site_logo)
                    <img src="{{url('public/logo', $general_setting->site_logo)}}" alt="logo">
                @else
                    <i class="fa fa-whatsapp step-icon"></i>
                @endif
            </div>
            <h1 class="auth-title">Forgot Password</h1>
            <p class="auth-sub">Enter your WhatsApp number to receive a reset code</p>
            <span class="version-badge">V.{{ config('app.version') }}</span>
        </div>

        <div class="auth-body">
            @if(session()->has('not_permitted'))
                <div class="alert-box">{{ session()->get('not_permitted') }}</div>
            @endif
            @if(session()->has('message'))
                <div class="alert-box success">{{ session()->get('message') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.password.send') }}">
                @csrf
                <div class="field">
                    <i class="fa fa-whatsapp fld-icon" style="color:#25D366"></i>
                    <input type="text" name="phone" placeholder="WhatsApp number (with country code)" required autofocus value="{{ old('phone') }}">
                </div>
                @if ($errors->has('phone'))
                    <p class="field-error">{{ $errors->first('phone') }}</p>
                @endif

                <button type="submit" class="btn-login"><i class="fa fa-paper-plane"></i> Send OTP</button>
            </form>

            <div class="forgot-row">
                <a href="{{ route('login') }}"><i class="fa fa-arrow-left"></i> Back to Sign In</a>
            </div>
        </div>

        <div class="auth-foot">
            {{ env('DEVELOPED_BY') ?? 'Mulema GC' }} &middot; <span class="external">V.{{ config('app.version') }}</span>
        </div>
    </div>
</div>
</body>
</html>

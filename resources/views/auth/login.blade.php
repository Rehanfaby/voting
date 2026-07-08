<?php $general_setting = DB::table('general_settings')->find(1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $general_setting->site_title }} — Sign In</title>
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
                <p class="auth-sub">{{ trans('file.Sign in to dashboard') }}</p>
            </div>

            <div class="auth-body">
                @if(session()->has('delete_message'))
                    <div class="alert-box">{{ session()->get('delete_message') }}</div>
                @endif
                @if(session()->has('not_permitted'))
                    <div class="alert-box">{{ session()->get('not_permitted') }}</div>
                @endif
                @if(session()->has('message'))
                    <div class="alert-box success">{{ session()->get('message') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="login-form">
                    @csrf
                    <div class="field">
                        <i class="fa fa-user fld-icon"></i>
                        <input type="text" name="name" placeholder="Username" required autofocus value="{{ old('name') }}" autocomplete="username">
                    </div>
                    @if ($errors->has('name'))
                        <p class="field-error">{{ $errors->first('name') }}</p>
                    @endif

                    <div class="field">
                        <i class="fa fa-lock fld-icon"></i>
                        <input type="password" name="password" id="login-password" placeholder="Password" required autocomplete="current-password">
                        <i class="fa fa-eye toggle-pass" id="togglePass"></i>
                    </div>
                    @if ($errors->has('password'))
                        <p class="field-error">{{ $errors->first('password') }}</p>
                    @endif

                    <button type="submit" class="btn-auth">
                        <i class="fa fa-sign-in"></i> Sign In
                    </button>
                </form>

                <div class="forgot-row">
                    <a href="{{ route('admin.password.request') }}">
                        <i class="fa fa-whatsapp"></i> Forgot password? Reset via WhatsApp
                    </a>
                </div>

                <div class="back-row">
                    <a href="{{ route('home') }}"><i class="fa fa-arrow-left"></i> {{ trans('file.Back to Homepage') }}</a>
                </div>
            </div>
        </div>
    </div>

    @include('auth.partials.auth-standalone-credit')

    <script>
        (function () {
            var t = document.getElementById('togglePass');
            var p = document.getElementById('login-password');
            if (t && p) {
                t.addEventListener('click', function () {
                    var show = p.type === 'password';
                    p.type = show ? 'text' : 'password';
                    t.className = 'fa toggle-pass ' + (show ? 'fa-eye-slash' : 'fa-eye');
                });
            }
        })();
    </script>
</body>
</html>

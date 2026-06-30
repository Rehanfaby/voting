<?php $general_setting = DB::table('general_settings')->find(1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $general_setting->site_title }} — Reset Password</title>
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}" />
    @include('auth.partials.auth-style')
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-card__head">
            <div class="auth-logo"><i class="fa fa-key step-icon"></i></div>
            <h1 class="auth-title">Set New Password</h1>
            <p class="auth-sub">Choose a strong new password for your account</p>
            <span class="version-badge">V.{{ config('app.version') }}</span>
        </div>

        <div class="auth-body">
            @if(session()->has('not_permitted'))
                <div class="alert-box">{{ session()->get('not_permitted') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.password.reset') }}">
                @csrf
                <div class="field">
                    <i class="fa fa-lock fld-icon"></i>
                    <input type="password" name="password" id="np" placeholder="New password" required minlength="6">
                    <i class="fa fa-eye toggle-pass" id="t1"></i>
                </div>
                @if ($errors->has('password'))
                    <p class="field-error">{{ $errors->first('password') }}</p>
                @endif

                <div class="field">
                    <i class="fa fa-lock fld-icon"></i>
                    <input type="password" name="confirm_password" id="cp" placeholder="Confirm new password" required>
                    <i class="fa fa-eye toggle-pass" id="t2"></i>
                </div>

                <button type="submit" class="btn-login"><i class="fa fa-check-circle"></i> Update Password</button>
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
<script src="<?php echo asset('public/vendor/jquery/jquery.min.js') ?>"></script>
<script>
    function bindToggle(btnId, inputId) {
        var b = document.getElementById(btnId), i = document.getElementById(inputId);
        if (b && i) b.addEventListener('click', function () {
            var show = i.type === 'password';
            i.type = show ? 'text' : 'password';
            b.className = 'fa toggle-pass ' + (show ? 'fa-eye-slash' : 'fa-eye');
        });
    }
    bindToggle('t1', 'np'); bindToggle('t2', 'cp');
</script>
</body>
</html>

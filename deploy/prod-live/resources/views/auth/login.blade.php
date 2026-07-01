<?php $general_setting = DB::table('general_settings')->find(1); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$general_setting->site_title}} — Sign In</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <link rel="manifest" href="{{url('manifest.json')}}">
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/font-awesome/css/font-awesome.min.css') ?>" type="text/css">
    <!-- Google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">

    <style>
        :root {
            --brand-orange: #1d4ed8;   /* primary blue */
            --brand-gold:   #f5c518;   /* golden yellow */
            --brand-gold-2: #ffd84d;
            --brand-navy:   #0a2350;
            --brand-grad: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
            --ink: #14223f;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Poppins', system-ui, -apple-system, sans-serif;
            background:
                radial-gradient(900px 600px at 12% -8%, rgba(245,197,24,.18), transparent 55%),
                radial-gradient(900px 600px at 95% 0%, rgba(37,99,235,.35), transparent 55%),
                linear-gradient(135deg, #0c2f6b 0%, #0a2350 55%, #07172f 100%);
            color: var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        /* Soft glow accents */
        body::before, body::after {
            content: "";
            position: fixed;
            border-radius: 50%;
            filter: blur(110px);
            opacity: .35;
            z-index: 0;
        }
        body::before { width: 520px; height: 520px; background: #2563eb; top: -170px; left: -120px; }
        body::after  { width: 480px; height: 480px; background: #f5c518; bottom: -180px; right: -120px; opacity: .22; }

        .auth-wrap {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 430px;
            padding: 24px;
        }
        .auth-card {
            background: #ffffff;
            border: 1px solid rgba(29, 78, 216, .10);
            border-radius: 24px;
            box-shadow: 0 24px 70px rgba(3, 12, 28, .45);
            overflow: hidden;
        }
        .auth-card__head {
            position: relative;
            text-align: center;
            padding: 38px 30px 26px;
            background:
                radial-gradient(120% 120% at 50% 0%, rgba(29,78,216,.14), transparent 65%);
        }
        .auth-card__head::after {
            content: "";
            position: absolute; left: 0; right: 0; bottom: 0; height: 4px;
            background: var(--brand-grad);
        }
        .auth-logo {
            width: 96px; height: 96px; margin: 0 auto 14px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 22px;
            background: linear-gradient(135deg, rgba(29,78,216,.12), rgba(245,197,24,.12));
            border: 1px solid rgba(245,197,24,.45);
        }
        .auth-logo img { max-width: 74px; max-height: 74px; }
        .auth-title { margin: 4px 0 2px; font-size: 22px; font-weight: 700; color: var(--ink); }
        .auth-sub { margin: 0; font-size: 13.5px; color: #8a7866; }
        .version-badge {
            display: inline-block; margin-top: 12px;
            font-size: 11px; font-weight: 700; letter-spacing: .8px;
            color: #1d4ed8;
            background: rgba(29,78,216,.12);
            border: 1px solid rgba(29,78,216,.35);
            padding: 4px 12px; border-radius: 30px;
        }

        .auth-body { padding: 26px 32px 34px; }

        .field { position: relative; margin-bottom: 18px; }
        .field i {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            color: var(--brand-orange); font-size: 15px;
        }
        .field input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            font-size: 14.5px;
            color: var(--ink);
            background: #faf6f0;
            border: 1px solid #ece1d3;
            border-radius: 14px;
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
            font-family: inherit;
        }
        .field input::placeholder { color: #b3a796; }
        .field input:focus {
            border-color: var(--brand-orange);
            box-shadow: 0 0 0 3px rgba(29,78,216,.18);
            background: #fbfdff;
        }
        .field .toggle-pass {
            left: auto; right: 16px; cursor: pointer; color: #b3a796;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            background: var(--brand-grad);
            cursor: pointer;
            transition: transform .15s, box-shadow .2s, filter .2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-login:hover { filter: brightness(1.05); box-shadow: 0 12px 28px rgba(29,78,216,.4); transform: translateY(-1px); }
        .btn-login:active { transform: translateY(0); }

        .forgot-row { text-align: center; margin-top: 18px; }
        .forgot-row a {
            color: #7a6a58; text-decoration: none; font-size: 13.5px;
            transition: color .2s;
        }
        .forgot-row a:hover { color: var(--brand-orange); }
        .forgot-row a i { margin-right: 5px; color: #25D366; }

        .auth-foot {
            text-align: center; padding: 16px; font-size: 12px;
            color: #a8997f;
            border-top: 1px solid #f1e8db;
        }
        .auth-foot .external { color: #1d4ed8; }

        .alert-box {
            background: #fdecec;
            border: 1px solid #f5c2c7;
            color: #b02a37;
            padding: 11px 14px; border-radius: 12px; font-size: 13px;
            margin-bottom: 18px; text-align: center;
        }
        .alert-box.success { background: #e7f6ec; border-color: #a8dab7; color: #1d7a3c; }
        .field-error { color: #d63a45; font-size: 12px; margin: 6px 4px 0; }
    </style>
  </head>
  <body>
    <div class="auth-wrap">
      <div class="auth-card">
        <div class="auth-card__head">
          <div class="auth-logo">
            @if($general_setting->site_logo)
              <img src="{{url('public/logo', $general_setting->site_logo)}}" alt="logo">
            @else
              <span>{{$general_setting->site_title}}</span>
            @endif
          </div>
          <h1 class="auth-title">Welcome Back</h1>
          <p class="auth-sub">Sign in to the {{ $general_setting->site_title }} dashboard</p>
          <span class="version-badge">V.{{ config('app.version') }}</span>
        </div>

        <div class="auth-body">
          @if(session()->has('delete_message'))
            <div class="alert-box">{{ session()->get('delete_message') }}</div>
          @endif
          @if(session()->has('message'))
            <div class="alert-box success">{{ session()->get('message') }}</div>
          @endif

          <form method="POST" action="{{ route('login') }}" id="login-form">
            @csrf
            <div class="field">
              <i class="fa fa-user"></i>
              <input type="text" name="name" placeholder="Username" required autofocus value="{{ old('name') }}">
            </div>
            @if ($errors->has('name'))
              <p class="field-error">{{ $errors->first('name') }}</p>
            @endif

            <div class="field">
              <i class="fa fa-lock"></i>
              <input type="password" name="password" id="login-password" placeholder="Password" required>
              <i class="fa fa-eye toggle-pass" id="togglePass"></i>
            </div>
            @if ($errors->has('password'))
              <p class="field-error">{{ $errors->first('password') }}</p>
            @endif

            <button type="submit" class="btn-login">
              <i class="fa fa-sign-in"></i> Sign In
            </button>
          </form>

          <div class="forgot-row">
            <a href="{{ route('admin.password.request') }}">
              <i class="fa fa-whatsapp"></i> Forgot password? Reset via WhatsApp
            </a>
          </div>
        </div>

        <div class="auth-foot">
          {{ env('DEVELOPED_BY') ?? 'Mulema GC' }} &middot; <span class="external">V.{{ config('app.version') }}</span>
        </div>
      </div>
    </div>

    <script src="<?php echo asset('public/vendor/jquery/jquery.min.js') ?>"></script>
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

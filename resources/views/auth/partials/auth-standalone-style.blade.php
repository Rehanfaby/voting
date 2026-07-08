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
        animation: mg-logo-in .8s ease-out both, mg-logo-pulse 2.6s ease-in-out 1s infinite;
    }
    .auth-logo img {
        max-width: 74px; max-height: 74px; border-radius: 50%;
        animation: mg-logo-spin 6s linear infinite;
    }
    @keyframes mg-logo-in {
        0%   { transform: scale(.3) rotate(-25deg); opacity: 0; }
        60%  { transform: scale(1.1) rotate(6deg); opacity: 1; }
        100% { transform: scale(1) rotate(0deg); opacity: 1; }
    }
    @keyframes mg-logo-pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(245,197,24,.55); }
        50%      { box-shadow: 0 0 0 14px rgba(245,197,24,0); }
    }
    @keyframes mg-logo-spin {
        0%   { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @media (prefers-reduced-motion: reduce) {
        .auth-logo, .auth-logo img { animation: none; }
    }
    .auth-title { margin: 4px 0 2px; font-size: 22px; font-weight: 700; color: var(--ink); }
    .auth-sub { margin: 0; font-size: 13.5px; color: #64748b; line-height: 1.5; }
    .auth-body { padding: 26px 32px 34px; }
    .field { position: relative; margin-bottom: 18px; }
    .field i.fld-icon {
        position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
        color: var(--brand-blue); font-size: 15px;
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
        font-family: inherit;
        transition: border-color .2s, box-shadow .2s;
    }
    .field input:focus {
        border-color: var(--brand-gold);
        box-shadow: 0 0 0 3px rgba(245,197,24,.25);
        background: #fff;
    }
    .field .toggle-pass {
        position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
        cursor: pointer; color: #94a3b8;
    }
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
    .btn-auth {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 14px;
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        background: var(--brand-grad);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-auth:hover { filter: brightness(1.05); }
    .forgot-row, .resend-row, .back-row { text-align: center; margin-top: 18px; font-size: 13px; color: #64748b; }
    .forgot-row a, .back-row a, .back-row button {
        color: #64748b; text-decoration: none; font-size: 13.5px;
        background: none; border: none; padding: 0; cursor: pointer; font-family: inherit;
    }
    .forgot-row a:hover, .back-row a:hover, .back-row button:hover { color: var(--brand-blue); }
    .forgot-row a i { margin-right: 5px; color: #25D366; }
    .resend-row button {
        background: none; border: none; padding: 0;
        color: var(--brand-blue); font-weight: 600; cursor: pointer; font-family: inherit;
    }
    .alert-box {
        background: #fdecec; border: 1px solid #f5c2c7; color: #b02a37;
        padding: 11px 14px; border-radius: 12px; font-size: 13px;
        margin-bottom: 18px; text-align: center;
    }
    .alert-box.success { background: #e7f6ec; border-color: #a8dab7; color: #1d7a3c; }
    .field-error { color: #d63a45; font-size: 12px; margin: 6px 4px 0; }
    .page-credit {
        position: fixed; bottom: 14px; left: 0; right: 0;
        text-align: center; font-size: 11.5px; letter-spacing: .3px;
        color: rgba(255,255,255,.6); line-height: 1.4;
    }
    .page-credit a { color: #f5c518; text-decoration: none; }
    .page-credit a:hover { text-decoration: underline; }
    .page-credit .page-version {
        display: inline-block; margin-left: 8px; padding: 2px 8px;
        border-radius: 30px; font-weight: 700; font-size: 10.5px;
        letter-spacing: .8px; color: #f5c518;
        background: rgba(245,197,24,.12); border: 1px solid rgba(245,197,24,.35);
    }
</style>

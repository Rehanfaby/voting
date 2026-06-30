<link rel="stylesheet" href="<?php echo asset('public/vendor/font-awesome/css/font-awesome.min.css') ?>" type="text/css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">
<style>
    :root {
        --brand-orange: #e2562a;
        --brand-gold:   #f0a93b;
        --brand-gold-2: #f6c453;
        --brand-grad: linear-gradient(135deg, #e2562a 0%, #f0a93b 100%);
        --ink: #2b2018;
    }
    * { box-sizing: border-box; }
    body {
        margin: 0; min-height: 100vh;
        font-family: 'Poppins', system-ui, -apple-system, sans-serif;
        background: linear-gradient(135deg, #fff7ef 0%, #ffe7d3 45%, #ffd9bd 100%);
        color: var(--ink);
        display: flex; align-items: center; justify-content: center;
        overflow: hidden; position: relative;
    }
    body::before, body::after {
        content: ""; position: fixed; border-radius: 50%; filter: blur(110px); opacity: .35; z-index: 0;
    }
    body::before { width: 520px; height: 520px; background: #f0a93b; top: -170px; left: -120px; }
    body::after  { width: 480px; height: 480px; background: #e2562a; bottom: -180px; right: -120px; opacity: .25; }

    .auth-wrap { position: relative; z-index: 1; width: 100%; max-width: 430px; padding: 24px; }
    .auth-card {
        background: #ffffff; border: 1px solid rgba(226, 86, 42, .10); border-radius: 24px;
        box-shadow: 0 24px 70px rgba(146, 64, 14, .18); overflow: hidden;
    }
    .auth-card__head {
        position: relative; text-align: center; padding: 38px 30px 26px;
        background: radial-gradient(120% 120% at 50% 0%, rgba(240,169,59,.18), transparent 65%);
    }
    .auth-card__head::after { content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 4px; background: var(--brand-grad); }
    .auth-logo {
        width: 96px; height: 96px; margin: 0 auto 14px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 22px; background: linear-gradient(135deg, rgba(240,169,59,.12), rgba(226,86,42,.10)); border: 1px solid rgba(240,169,59,.35);
    }
    .auth-logo img { max-width: 74px; max-height: 74px; }
    .auth-logo .step-icon { font-size: 40px; color: var(--brand-orange); }
    .auth-title { margin: 4px 0 2px; font-size: 22px; font-weight: 700; color: var(--ink); }
    .auth-sub { margin: 0; font-size: 13.5px; color: #8a7866; }
    .version-badge {
        display: inline-block; margin-top: 12px; font-size: 11px; font-weight: 700; letter-spacing: .8px;
        color: #b45309; background: rgba(240,169,59,.16); border: 1px solid rgba(240,169,59,.45);
        padding: 4px 12px; border-radius: 30px;
    }
    .auth-body { padding: 26px 32px 34px; }
    .field { position: relative; margin-bottom: 18px; }
    .field > i.fld-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--brand-orange); font-size: 15px; }
    .field input {
        width: 100%; padding: 14px 16px 14px 44px; font-size: 14.5px; color: var(--ink);
        background: #faf6f0; border: 1px solid #ece1d3;
        border-radius: 14px; outline: none; font-family: inherit;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .field input::placeholder { color: #b3a796; }
    .field input:focus { border-color: var(--brand-gold); box-shadow: 0 0 0 3px rgba(240,169,59,.22); background: #fffdf9; }
    .field .toggle-pass { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #b3a796; }
    .otp-input input { text-align: center; letter-spacing: 10px; font-size: 22px; font-weight: 700; padding-left: 16px; }
    .btn-login {
        width: 100%; padding: 14px; border: none; border-radius: 14px;
        font-size: 15px; font-weight: 700; color: #fff; background: var(--brand-grad); cursor: pointer;
        transition: transform .15s, box-shadow .2s, filter .2s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-login:hover { filter: brightness(1.05); box-shadow: 0 12px 28px rgba(226,86,42,.35); transform: translateY(-1px); }
    .btn-login:active { transform: translateY(0); }
    .forgot-row { text-align: center; margin-top: 18px; }
    .forgot-row a { color: #7a6a58; text-decoration: none; font-size: 13.5px; transition: color .2s; }
    .forgot-row a:hover { color: var(--brand-orange); }
    .forgot-row a i { margin-right: 5px; }
    .auth-foot { text-align: center; padding: 16px; font-size: 12px; color: #a8997f; border-top: 1px solid #f1e8db; }
    .auth-foot .external { color: #b45309; }
    .alert-box { background: #fdecec; border: 1px solid #f5c2c7; color: #b02a37; padding: 11px 14px; border-radius: 12px; font-size: 13px; margin-bottom: 18px; text-align: center; }
    .alert-box.success { background: #e7f6ec; border-color: #a8dab7; color: #1d7a3c; }
    .field-error { color: #d63a45; font-size: 12px; margin: 6px 4px 0; }
</style>

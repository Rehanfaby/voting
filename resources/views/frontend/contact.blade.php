@extends('frontend.layout.main')
@section('content')

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first() }}</div>
    @endif
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif

    <main>
        <section class="mg-contact-hero pt-130 pb-50">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-9">
                        <span class="mg-contact-kicker">{{ trans('file.Get in Touch') }}</span>
                        <h1 class="mg-contact-title">{{ trans('file.Contact Us') }}</h1>
                        <p class="mg-contact-lead">{{ trans('file.Contact hero lead') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mg-contact-body pb-130">
            <div class="container">
                <div class="row g-4">
                    <div class="col-xl-5">
                        <div class="mg-contact-info-card">
                            <h3>{{ trans('file.Mulema Gospel Talent') }}</h3>
                            <p>{{ trans('file.Contact side intro') }}</p>
                            <ul class="mg-contact-info-list">
                                <li><i class="fas fa-envelope"></i> <a href="mailto:info@mulemagc.com">info@mulemagc.com</a></li>
                                <li><i class="fab fa-whatsapp"></i> <a href="https://wa.me/237675321739" target="_blank" rel="noopener">+237 675 321 739</a></li>
                                <li><i class="fas fa-map-marker-alt"></i> {{ trans('file.Yaounde Cameroon') }}</li>
                            </ul>
                            <div class="mg-contact-social">
                                <a href="https://www.facebook.com/share/1Ej9KPRMN4/?mibextid=LQQJ4d" target="_blank" rel="noopener"><i class="fab fa-facebook"></i> Facebook</a>
                                <a href="https://www.instagram.com/mulemagospeltalent?igsh=MWZ6MGJ1YXRuMnZ2Yw==" target="_blank" rel="noopener"><i class="fab fa-instagram"></i> Instagram</a>
                                <a href="https://www.tiktok.com/@mulemagospel?_t=ZN-8t8d1fyW4m6&_r=1" target="_blank" rel="noopener"><i class="fab fa-tiktok"></i> TikTok</a>
                            </div>
                            <div class="mg-contact-hours">
                                <strong><i class="far fa-clock"></i> {{ trans('file.Response time') }}</strong>
                                <p>{{ trans('file.Contact response note') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7">
                        <div class="mg-contact-form-card">
                            <div class="mg-contact-form-head">
                                <h4><i class="fas fa-paper-plane"></i> {{ trans('file.Send us a Direct Message') }}</h4>
                                <p>{{ trans('file.Contact whatsapp note') }}</p>
                            </div>
                            <form method="post" action="{{ route('contact.message') }}" class="mg-contact-form">
                                @csrf
                                {{-- Honeypot: hidden from real users, bots tend to fill these --}}
                                <div class="mg-hp" aria-hidden="true">
                                    <label>Website</label>
                                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                                    <label>Company URL</label>
                                    <input type="text" name="company_url" tabindex="-1" autocomplete="off">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mg-field">
                                            <label for="contact-name">{{ trans('file.Full Name') }} *</label>
                                            <input id="contact-name" type="text" name="name" value="{{ old('name') }}" required placeholder="{{ trans('file.Your name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mg-field">
                                            <label for="contact-number">{{ trans('file.Contact Number') }} *</label>
                                            <input id="contact-number" type="tel" name="number" placeholder="237 6XX XXX XXX" value="{{ old('number') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mg-field">
                                            <label for="contact-email">{{ trans('file.Email Address') }}</label>
                                            <input id="contact-email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mg-field">
                                            <label for="contact-message">{{ trans('file.Message') }} *</label>
                                            <textarea id="contact-message" name="message" rows="6" placeholder="{{ trans('file.Your message here...') }}" required>{{ old('message') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="mg-contact-actions">
                                    <button class="mg-btn mg-btn-primary" type="submit"><i class="fas fa-paper-plane"></i> {{ trans('file.Send Message') }}</button>
                                    <a class="mg-btn mg-btn-outline" href="https://wa.me/237675321739" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> {{ trans('file.Open WhatsApp') }}</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection

@section('styles')
<style>
    .mg-contact-hero { background: radial-gradient(900px 420px at 50% -20%, rgba(232,119,34,.14), transparent 60%); }
    .mg-contact-kicker { display:inline-block;color:#e87722;font-weight:800;letter-spacing:2px;text-transform:uppercase;font-size:13px;margin-bottom:12px; }
    .mg-contact-title { color:#fff;font-size:44px;font-weight:800;margin:0 0 12px; }
    .mg-contact-lead { color:rgba(255,255,255,.78);font-size:18px;margin:0;max-width:640px;margin-inline:auto;line-height:1.6; }
    .mg-contact-info-card { height:100%;padding:36px 32px;border-radius:24px;background:linear-gradient(185deg,#0c2f6b 0%,#0a2350 100%);color:#fff;border:1px solid rgba(232,119,34,.25);box-shadow:0 24px 60px rgba(0,0,0,.35); }
    .mg-contact-info-card h3 { font-size:26px;font-weight:800;margin:0 0 12px;color:#fff; }
    .mg-contact-info-card > p { color:rgba(255,255,255,.78);line-height:1.65;margin-bottom:24px; }
    .mg-contact-info-list { list-style:none;margin:0 0 24px;padding:0; }
    .mg-contact-info-list li { display:flex;align-items:center;gap:12px;margin-bottom:14px;font-weight:600; }
    .mg-contact-info-list a { color:#fff;text-decoration:none; }
    .mg-contact-info-list a:hover { color:#ff9533; }
    .mg-contact-info-list i { color:#e87722;width:18px; }
    .mg-contact-social { display:flex;flex-wrap:wrap;gap:10px;margin-bottom:24px; }
    .mg-contact-social a { display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:999px;background:rgba(255,255,255,.08);color:#fff;font-size:13px;font-weight:600;text-decoration:none; }
    .mg-contact-social a:hover { background:rgba(232,119,34,.25);color:#fff; }
    .mg-contact-hours { padding:16px;border-radius:14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08); }
    .mg-contact-hours p { margin:8px 0 0;color:rgba(255,255,255,.75);font-size:14px; }
    .mg-contact-form-card { background:#fff;border-radius:24px;overflow:hidden;box-shadow:0 30px 80px rgba(3,12,28,.45);border:1px solid rgba(232,119,34,.15); }
    .mg-contact-form-head { padding:28px 32px 0;background:linear-gradient(135deg,#0c2f6b,#1d4ed8);color:#fff; }
    .mg-contact-form-head h4 { margin:0 0 8px;font-weight:800;font-size:22px; }
    .mg-contact-form-head p { margin:0 0 24px;opacity:.85;font-size:14px; }
    .mg-contact-form { padding:28px 32px 32px; }
    .mg-hp { position:absolute !important; left:-9999px !important; top:auto; width:1px; height:1px; overflow:hidden; }
    .mg-contact-form .mg-field { margin-bottom:20px; }
    .mg-contact-form label { display:block;font-weight:700;color:#0a2350;margin-bottom:8px;font-size:14px; }
    .mg-contact-form input, .mg-contact-form textarea { width:100%;border:1px solid #dbe4f3;border-radius:12px;padding:14px 16px;font-size:15px;color:#14223f;background:#f7f9fd;transition:border-color .2s,box-shadow .2s; }
    .mg-contact-form input:focus, .mg-contact-form textarea:focus { outline:none;border-color:#e87722;box-shadow:0 0 0 3px rgba(232,119,34,.15);background:#fff; }
    .mg-contact-actions { display:flex;flex-wrap:wrap;gap:12px;margin-top:8px; }
    .mg-btn { display:inline-flex;align-items:center;gap:8px;padding:14px 22px;border-radius:12px;font-weight:700;border:none;cursor:pointer;text-decoration:none; }
    .mg-btn-primary { background:linear-gradient(135deg,#e87722,#f59e0b);color:#fff; }
    .mg-btn-outline { background:#fff;color:#16a34a;border:2px solid #16a34a; }
    .mg-dev-credit { margin:0;font-size:11px;line-height:1.4;opacity:.65;text-align:center; }
    .mg-dev-credit a { color:inherit;text-decoration:none; }
    .mg-dev-credit a:hover { text-decoration:underline;color:#e87722; }
    @media (max-width:991px){ .mg-contact-title{font-size:32px;} .mg-contact-form,.mg-contact-form-head,.mg-contact-info-card{padding-left:22px;padding-right:22px;} }
</style>
@endsection

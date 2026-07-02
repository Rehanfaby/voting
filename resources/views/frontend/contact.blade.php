@extends('frontend.layout.main')
@section('content')

    @if($errors->has('name'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('name') }}</div>
    @endif
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif
    @if(session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif

    <main>
        <section class="mg-contact-hero pt-130 pb-70">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <span class="mg-contact-kicker">{{ trans('file.Get in Touch') }}</span>
                        <h1 class="mg-contact-title">{{ trans('file.Contact Us') }}</h1>
                        <p class="mg-contact-lead">{{ trans('file.We would love to hear from you') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mg-contact-body pb-130">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <div class="mg-contact-card">
                            <div class="row g-0">
                                <div class="col-lg-5">
                                    <div class="mg-contact-side">
                                        <h3>{{ trans('file.Contact Us') }}</h3>
                                        <p>{{ trans('file.Send us a message and our team will respond as soon as possible.') }}</p>
                                        <ul class="mg-contact-links">
                                            <li><a href="mailto:info@mulemagc.com"><i class="fas fa-envelope"></i> info@mulemagc.com</a></li>
                                            <li><a href="https://www.facebook.com/share/1Ej9KPRMN4/?mibextid=LQQJ4d" target="_blank" rel="noopener"><i class="fab fa-facebook"></i> Facebook</a></li>
                                            <li><a href="https://www.instagram.com/mulemagospeltalent?igsh=MWZ6MGJ1YXRuMnZ2Yw==" target="_blank" rel="noopener"><i class="fab fa-instagram"></i> Instagram</a></li>
                                            <li><a href="https://www.tiktok.com/@mulemagospel?_t=ZN-8t8d1fyW4m6&_r=1" target="_blank" rel="noopener"><i class="fab fa-tiktok"></i> TikTok</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="mg-contact-form-wrap">
                                        <form method="post" action="{{ route('contact.message') }}" class="mg-contact-form">
                                            @csrf
                                            <div class="mg-field">
                                                <label for="contact-number">{{ trans('file.Contact Number') }}</label>
                                                <input id="contact-number" type="tel" name="number" placeholder="237 6XX XXX XXX" value="{{ old('number', '237') }}" required>
                                            </div>
                                            <div class="mg-field">
                                                <label for="contact-message">{{ trans('file.Message') }}</label>
                                                <textarea id="contact-message" name="message" rows="6" placeholder="{{ trans('file.Your message here...') }}" required>{{ old('message') }}</textarea>
                                            </div>
                                            <button class="mg-btn" type="submit"><i class="fas fa-paper-plane"></i> {{ trans('file.Contact Us') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection

@section('styles')
<style>
    .mg-contact-hero {
        background: radial-gradient(900px 420px at 50% -20%, rgba(232,119,34,.12), transparent 60%);
    }
    .mg-contact-kicker {
        display: inline-block;
        color: #e87722;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-size: 13px;
        margin-bottom: 12px;
    }
    .mg-contact-title {
        color: #fff;
        font-size: 42px;
        font-weight: 800;
        margin: 0 0 12px;
    }
    .mg-contact-lead {
        color: rgba(255,255,255,.75);
        font-size: 17px;
        margin: 0;
    }
    .mg-contact-card {
        border-radius: 24px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 30px 80px rgba(3, 12, 28, .45);
        border: 1px solid rgba(232,119,34,.2);
    }
    .mg-contact-side {
        height: 100%;
        padding: 42px 34px;
        background: linear-gradient(185deg, #0c2f6b 0%, #0a2350 100%);
        color: #fff;
    }
    .mg-contact-side h3 {
        font-size: 28px;
        font-weight: 800;
        margin: 0 0 14px;
    }
    .mg-contact-side p {
        color: rgba(255,255,255,.78);
        margin-bottom: 28px;
        line-height: 1.6;
    }
    .mg-contact-links {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .mg-contact-links li + li { margin-top: 14px; }
    .mg-contact-links a {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #fff;
        font-weight: 600;
        text-decoration: none;
        transition: color .2s ease;
    }
    .mg-contact-links a i { color: #e87722; width: 18px; }
    .mg-contact-links a:hover { color: #ff9533; }
    .mg-contact-form-wrap { padding: 42px 38px; background: #fff; }
    .mg-contact-form .mg-field { margin-bottom: 22px; }
    .mg-contact-form label {
        display: block;
        font-weight: 700;
        color: #0a2350;
        margin-bottom: 8px;
        font-size: 14px;
    }
    .mg-contact-form input,
    .mg-contact-form textarea {
        width: 100%;
        border: 1px solid #dbe4f3;
        border-radius: 12px;
        padding: 14px 16px;
        font-size: 15px;
        color: #14223f;
        background: #f7f9fd;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .mg-contact-form input:focus,
    .mg-contact-form textarea:focus {
        outline: none;
        border-color: #e87722;
        box-shadow: 0 0 0 3px rgba(232,119,34,.15);
        background: #fff;
    }
    .mg-contact-form textarea { resize: vertical; min-height: 150px; }
    @media (max-width: 991px) {
        .mg-contact-title { font-size: 32px; }
        .mg-contact-form-wrap, .mg-contact-side { padding: 28px 22px; }
    }
</style>
@endsection

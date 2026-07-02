@extends('frontend.layout.main')
@section('content')

    <main class="mg-about-page">
        <section class="mg-about-hero pt-130 pb-60">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-xl-9 col-lg-10">
                        <span class="mg-about-kicker">{{ trans('file.About Us') }}</span>
                        <h1 class="mg-about-title">{{ trans('file.Mulema Gospel Talent') }}</h1>
                        <p class="mg-about-lead">{{ trans('file.About hero subtitle') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mg-about-mission pb-80">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <div class="mg-about-mission-card">
                            <div class="row align-items-center g-4">
                                <div class="col-lg-5">
                                    <div class="mg-about-mission-media">
                                        <img src="{{ asset('public/frontend/images/bottom-banner-en.jpeg') }}" alt="{{ trans('file.Mulema Gospel Talent') }}" loading="lazy" decoding="async">
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <h2 class="mg-about-section-title">{{ trans('file.Our Mission') }}</h2>
                                    <div class="mg-about-mission-text">
                                        <p>{{ trans('file.About mission paragraph 1') }}</p>
                                        <p>{{ trans('file.About mission paragraph 2') }}</p>
                                        <p>{{ trans('file.About mission paragraph 3') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mg-about-intro pb-60">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <div class="mg-about-intro-card">
                            <h2 class="mg-about-section-title text-center">{{ trans('file.Hello Cameroon') }}</h2>
                            <p class="mg-about-intro-text">{{ trans('file.About intro footer text') }}</p>
                            <div class="mg-about-stats">
                                <div class="mg-about-stat"><strong>10</strong><span>{{ trans('file.Regions of Cameroon') }}</span></div>
                                <div class="mg-about-stat"><strong>2023</strong><span>{{ trans('file.Founded') }}</span></div>
                                <div class="mg-about-stat"><strong>Gospel</strong><span>{{ trans('file.Music & Worship') }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @if($team->isNotEmpty())
        <section class="mg-about-team ms-judges-section pb-130 pt-40">
            <div class="container">
                <div class="row justify-content-center text-center mb-40">
                    <div class="col-xl-8">
                        <span class="section__subtitle">{{ trans('file.Our Leadership') }}</span>
                        <h2 class="mg-about-section-title mg-about-section-title--light">{{ trans('file.The visionaries behind Mulema Gospel') }}</h2>
                    </div>
                </div>
                <div class="row justify-content-center g-4">
                    @foreach($team as $member)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <div class="ms-popular__item p-relative mg-about-team-card">
                            <div class="ms-popular__thumb ms-judge-glow">
                                <div class="ms-popular-overlay"></div>
                                @if($member->image)
                                    <img src="{{ \App\Helpers\ImageOptimizer::employeeImageUrl($member->image) }}" alt="{{ $member->name }}" loading="lazy" decoding="async">
                                @else
                                    <div class="mg-about-team-placeholder"><span>{{ strtoupper(substr($member->name, 0, 1)) }}</span></div>
                                @endif
                            </div>
                            <h4 class="ms-popular__title">
                                <span class="mg-about-team-label">
                                    <span class="mg-about-team-name">{{ $member->name }}</span>
                                    @include('partials.country_flag', ['country' => $member->country, 'size' => 20, 'class' => 'mg-judge-flag-inline'])
                                </span>
                            </h4>
                            @if($member->title)
                                <p class="mg-about-team-role">{{ $member->title }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    </main>
@endsection

@section('styles')
<style>
    .mg-about-page { background: #07172f; }
    .mg-about-hero { background: radial-gradient(900px 420px at 50% -20%, rgba(232,119,34,.14), transparent 60%); }
    .mg-about-kicker { display:inline-block; color:#e87722; font-weight:800; letter-spacing:2px; text-transform:uppercase; font-size:12px; margin-bottom:10px; }
    .mg-about-title { color:#fff; font-size:42px; font-weight:800; margin:0 0 12px; line-height:1.15; }
    .mg-about-lead { color:rgba(255,255,255,.78); font-size:18px; max-width:720px; margin:0 auto; }
    .mg-about-mission-card, .mg-about-intro-card {
        background:#fff; border-radius:24px; border:1px solid rgba(232,119,34,.22);
        box-shadow:0 24px 60px rgba(3,12,28,.35); padding:36px 32px;
    }
    .mg-about-mission-media img { width:100%; border-radius:20px; object-fit:cover; min-height:280px; max-height:360px; }
    .mg-about-section-title { color:#0a2350; font-size:30px; font-weight:800; margin:0 0 18px; }
    .mg-about-section-title--light { color:#fff; }
    .mg-about-mission-text p { color:#4a5872; font-size:16px; line-height:1.75; margin-bottom:14px; }
    .mg-about-intro-text { color:#4a5872; font-size:16px; line-height:1.75; text-align:center; max-width:860px; margin:0 auto 28px; }
    .mg-about-stats { display:flex; flex-wrap:wrap; gap:16px; justify-content:center; }
    .mg-about-stat { background:#f7f9fd; border:1px solid #e7edf5; border-radius:16px; padding:18px 24px; min-width:150px; text-align:center; }
    .mg-about-stat strong { display:block; color:#e87722; font-size:28px; font-weight:800; line-height:1.1; }
    .mg-about-stat span { display:block; margin-top:6px; color:#5b6b86; font-size:13px; font-weight:600; }
    .mg-about-team .section__subtitle { color:#e87722; }
    .mg-about-team-card { margin-bottom:0; }
    .mg-about-team-card .ms-popular__thumb { border-radius:50%; overflow:hidden; aspect-ratio:3/4; max-width:260px; margin:0 auto; }
    .mg-about-team-card .ms-popular__thumb img { width:100%; height:100%; object-fit:cover; display:block; }
    .mg-about-team-placeholder { width:100%; height:100%; min-height:280px; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#0c2f6b,#0a2350); color:#e87722; font-size:64px; font-weight:800; }
    .mg-about-team-card .ms-popular__title { position:static; text-align:center; margin-top:16px; }
    .mg-about-team-label {
        display:inline-flex; align-items:center; justify-content:center; flex-wrap:wrap; gap:6px;
        color:#f6c453; background:#12294d; border:1px solid rgba(246,196,83,.45);
        font-weight:700; font-size:15px; border-radius:30px; padding:10px 18px; line-height:1.3;
    }
    .mg-about-team-role { text-align:center; color:rgba(255,255,255,.72); font-size:14px; margin:8px 0 0; }
    .mg-about-team .ms-judge-glow {
        border:2px solid rgba(232,119,34,.55);
        box-shadow:0 0 18px rgba(232,119,34,.4), 0 0 36px rgba(232,119,34,.18);
    }
    .mg-country-flag { display:inline-flex; align-items:center; vertical-align:middle; }
    .mg-country-flag img { border-radius:2px; display:block; }
    .mg-country-flag__emoji { font-size:1.15em; line-height:1; }
    @media (max-width:767px) {
        .mg-about-title { font-size:30px; }
        .mg-about-mission-card, .mg-about-intro-card { padding:24px 18px; }
    }
</style>
@endsection

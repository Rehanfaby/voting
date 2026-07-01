@php use App\Employee; @endphp
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
    <style>
        .ms-banner__main-wrapper {
             padding-top: 0px;
            padding-bottom: 288px;
        }
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.75);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .popup-overlay.active {
            visibility: visible;
            opacity: 1;
        }

        .popup-content {
            position: relative;
            background: white;
            border-radius: 8px;
            max-width: 90%;
            max-height: 90%;
            overflow: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px;
        }


        .popup-image {
            max-width: 100%;
            max-height: 80vh; /* Make it responsive to screen height */
            height: auto;
            width: auto;
            object-fit: contain;
            display: block;
        }

        .close-btn {
            position: absolute;
            top: 5px;
            right: 10px;
            font-size: 24px;
            font-weight: bold;
            color: #000;
            cursor: pointer;
            background: #fff;
            border-radius: 50%;
            padding: 2px 8px;
        }
    </style>

    <main>

        <!-- Popup Overlay -->
        <div class="popup-overlay" id="popup">
            <div class="popup-content">
                <span class="close-btn" id="closeBtn">&times;</span>
                <img src="{{ asset('public/img/flayer.jpeg') }}" alt="Newscaster Image" class="popup-image" />
            </div>
        </div>
        <!-- Brand Song Area Start Here  -->
        
        <section class="ms-fun-brand ms-bg-2 pb-130 pt-125">
            <div class="container">
                <div class="row align-items-end mb-25 bdFadeUp">
                    <div class="col-xl-6 col-lg-6">
                        <div class="section__title-wrapper mb-40 bd-title-anim">
                            <span class="section__subtitle">{{ trans('file.Our Winners') }}</span>
                            <h2 class="section__title msg_title">
                                <span class="animated-underline active"></span><br>
                            </h2>
                        </div>
                    </div>
                </div>
        
                <div class="row bdFadeUp">
                    <div class="col-xxl-12">
                        <div class="swiper-container ms-popular-active fix">
                            <div class="swiper-wrapper">
                                @foreach($musicians as $key => $musician)
                                    <div class="swiper-slide">
                                        <div class="ms-popular__item p-relative mb-30">
                                            <div class="ms-popular__thumb">
                                                <div class="ms-popular-overlay"></div>
        
                                                <a href="{{ route('musician.data', $musician->id) }}">
                                                    <img src="{{ url('public/images/employee', $musician->image) }}"
                                                         alt="{{ $musician->name }}" loading="lazy" decoding="async">
                                                </a>
        
                                                <a href="{{ route('musician.data', $musician->id) }}" class="ms-popular__link">
                                                    <span class="ms-popular-icon">
                                                        <i class="fa-regular fa-arrow-right-long"></i>
                                                    </span>
                                                </a>
        
                                                @if($see_votes)
                                                    @php
                                                        $votes = \App\vote::where('status', true)
                                                            ->where('musician_id', $musician->id)
                                                            ->sum('vote');
                                                    @endphp
                                                    <span class="ms-song-num">{{ $votes }}</span>
                                                @endif
                                            </div>
        
                                            <h4 class="ms-popular__title">
                                                <a href="{{ route('musician.data', $musician->id) }}">
                                                    {{ $musician->name }}
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Brand Song Area End Here  -->

        <!-- Banner Area Start Here  -->
        <section class="ms-banner-area p-relative">
            <a class="ms-scroll-down" href="#">{{trans('file.SCROLL DOWN')}}</a>
            <div class="container-fluid ms-maw-1710">
                @if(\App::getLocale() == 'en')
                <div class="ms-br-30 mx-auto include__bg z-index-1 ms-overlay-1 p-relative" data-background="{{ url('public/frontend/images/top-banner2-en.jpg') }}">
                @else
                <div class="ms-br-30 mx-auto include__bg z-index-1 ms-overlay-1 p-relative" data-background="{{ url('public/frontend/images/top-banner2-fr.jpg') }}">
                @endif
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-xl-11">
                                <div class="ms-banner__main-wrapper">
                                    <div class="ms-banner__content text-center">
                                        <h1 class="ms-banner__bg-title" data-background="">
                                            {{trans('file.Musicly')}}
                                        </h1>
                                        <h2 class="ms-banner__title msg_title bd-title-anim">{{trans('file..')}}</h2>
                                    </div>
                                    <div class="ms-banner__form bdFadeUp">
                                        <form action="{{ route('musician.find') }}" method="post">
                                            @csrf
                                            <div class="ms-banner__from-inner white-bg">
                                                <div class="ms-input2-box white-bg">
                                                    <input type="text" placeholder="{{trans("file.Search Your Contestant")}}" name="search">
                                                </div>
                                                <div class="banner__form-button">
                                                    <button type="submit" class="input__btn"><i class="flaticon-loupe"></i>{{trans('file.Discover Talents')}}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Banner Area End Here  -->

        <!-- Function Brand Area Start Here  -->
        @if(\App\Helpers\SiteContent::enabled('weekly_contestants'))
        
        <section class="ms-fun-brand ms-bg-2 pb-130 pt-125">
            <div class="container">
                <div class="row justify-content-center bdFadeUp">
                    <div class="col-xl-7">
                        <div class="section__title-wrapper mb-65 text-center bd-title-anim">
                            <span class="section__subtitle">{{trans('file.Mulema The Gospel Show')}}</span>
                            <h2 class="section__title">
                                {{trans('file.Qualified')}} <span class="animated-underline active">{{trans('file.Contestants for the Week')}}!</span>

                            </h2>
                        </div>
                    </div>
                </div>
                <div class="ms-fun-brand-wrap bdFadeUp">
                    @foreach($musicians as $key=>$musician)
                        <a href="{{ route('musician.data', $musician->id) }}">
                            <div class="ms-fun-brand-item ms-fun-border" style="cursor: pointer">
                                <div class="ms-fun-brand-top mb-20">
                                    <div class="ms-fun-brand-thumb">
                                        <img src="{{url('public/images/employee',$musician->image)}}" loading="lazy" decoding="async">
                                    </div>
                                    <div class="ms-fun-brand-content">
                                        <h3 class="ms-fun-brand-title">
                                            {{ $musician->name }}</h3>
                                        <span class="ms-fun-brand-subtitle">{{ @$musician->departments->name }}</span>
                                    </div>
                                </div>
                                <div class="ms-fun-brand-bottom">
                                    <div class="ms-fun-brand-location">
                                        <i class="fa fa-vote-yea"></i> {{trans('file.Vote For Me')}}
                                    </div>
                                    @if($see_votes)
                                        <div class="ms-fun-brand-rating">
                                            <span style="color: yellow">{{ $vote_count[$key] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
        <!-- Function Brand Area End Here  -->




        <!-- judge  area start -->
        @if(\App\Helpers\SiteContent::enabled('judges'))
        <section class="ms-fun-brand ms-bg-2 pb-130 pt-125">
            <div class="container">
                <div class="row align-items-end mb-25 bdFadeUp">
                    <div class="col-xl-6 col-lg-6">
                        <div class="section__title-wrapper mb-40 bd-title-anim">
                            <span class="section__subtitle">{{trans('file.Our Judges')}}</span>
                            <h2 class="section__title msg_title">
                                <span class="animated-underline active"></span> <br>

                            </h2>
                        </div>
                    </div>
                </div>
                <div class="row bdFadeUp">
                    <div class="col-xxl-12">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-popular-1" role="tabpanel" aria-labelledby="nav-popular-1-tab" tabindex="0">
                                <div class="swiper-container ms-popular-active fix">
                                    <div class="swiper-wrapper">
                                        @foreach($judges as $contentant)
                                            <div class="swiper-slide">
                                                <div class="ms-popular__item p-relative mb-30">
                                                    <div class="ms-popular__thumb">
                                                        <div class="ms-popular-overlay"></div>
                                                        <a ><img src="{{url('public/images/employee',$contentant->image)}}" loading="lazy" decoding="async" ></a>
                                                        <a class="ms-popular__link">
                                                            <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                        </a>
                                                    </div>
                                                    <h4 class="ms-popular__title"><a >
                                                            {{ $contentant->name }}
                                                        </a>
                                                    </h4>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
        <!-- judge  area end -->


        <!-- Text scroll area start -->
        <section class="text__scroll-area include__bg ms-ts-space p-relative fix"  data-background="{{ url('public/frontend/images/sound-bg.png') }}">
            <div class="text__scroll-wrapper">
                <div class="ms-text-line-1">
                    <div class="swiper-container ms-st-active scroll__text pt-20 pb-20">
                        <div class="swiper-wrapper ms-st-active-wrapper">
                            <div class="swiper-slide">
                                <h3>{{trans('file.Strum')}}</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>{{trans('file.Sing')}}</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3><span class="text-color-1">{{trans('file.Soar')}}</span></h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>{{trans('file.Rise')}}</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3><span class="text-color-1">{{trans('file.To')}}</span></h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>{{trans('file.Mulema the')}} </h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>{{trans('file.Gospel')}}</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3><span class="text-color-2">{{trans('file.Show')}}</span></h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>{{trans('file.Music')}}</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>{{trans('file.Competition')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ms-text-line-2">
                    <div class="swiper-container ms-str-active scroll__text pt-20 pb-20">
                        <div class="swiper-wrapper ms-str-active-wrapper">
                            <div class="swiper-slide">
                                <h3>{{trans('file.Mulema')}}</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>{{trans('file.The')}}</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3><span class="text-color-1">{{trans('file.Gospel Show')}}</span></h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>{{trans('file.Music')}}</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3><span class="text-color-1">{{trans('file.Competition')}}</span></h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>{{trans('file.Awaits')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Text scroll area end -->

        <!-- Ambassador  area start -->
        @if(\App\Helpers\SiteContent::enabled('ambassadors'))
        <section class="ms-fun-brand ms-bg-2 pb-130 pt-125">
            <div class="container">
                <div class="row align-items-end mb-25 bdFadeUp">
                    <div class="col-xl-6 col-lg-6">
                        <div class="section__title-wrapper mb-40 bd-title-anim">
                            <span class="section__subtitle">{{trans('file.Meet Our Ambassadors')}}</span>
                            <h2 class="section__title msg_title">
                                <span class="animated-underline active"></span> <br>

                            </h2>
                        </div>
                    </div>
                </div>
                <div class="row bdFadeUp">
                    <div class="col-xxl-12">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-popular-1" role="tabpanel" aria-labelledby="nav-popular-1-tab" tabindex="0">
                                <div class="swiper-container ms-popular-active fix">
                                    <div class="swiper-wrapper">
                                        @foreach($ambassadors as $ambassador)
                                            <div class="swiper-slide">
                                                <div class="ms-popular__item p-relative mb-30">
                                                    <div class="ms-popular__thumb" style="border-radius: 10%;">
                                                        <div class="ms-popular-overlay"></div>
                                                        <a ><img src="{{url('public/images/employee',$ambassador->image)}}" loading="lazy" decoding="async" ></a>
                                                        <a class="ms-popular__link">
                                                            <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                        </a>
                                                    </div>
                                                    <h4 class="ms-popular__title"><a >
                                                            {{ $ambassador->name }}
                                                        </a>
                                                    </h4>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
        <!-- Ambassador  area end -->

        <!-- Special Events Area Start -->
        @if(\App\Helpers\SiteContent::enabled('casting'))
        @php
            $sc = \App\Helpers\SiteContent::all();
            $nextPrime = \App\Helpers\SiteContent::nextPrime();
        @endphp
        <section class="ms-casting-premium pt-130 pb-70">
            <div class="container">
                <div class="ms-casting-card">
                    <div class="ms-casting-head">
                        <span class="ms-casting-kicker">Mulema The Gospel Show</span>
                        <h2 class="ms-casting-title">{{ $sc['casting_title'] ?? 'Provincial Casting Calendar' }}</h2>
                        <p class="ms-casting-sub">{{ $sc['casting_subtitle'] ?? '' }}</p>
                    </div>

                    @if($nextPrime)
                        <div class="ms-countdown" data-deadline="{{ \Carbon\Carbon::parse($nextPrime['date'])->toIso8601String() }}">
                            <div class="ms-countdown-label"><i class="fa fa-bolt"></i> Next: {{ $nextPrime['label'] }} &middot; {{ \Carbon\Carbon::parse($nextPrime['date'])->format('M j, Y · g:i A') }}</div>
                            <div class="ms-countdown-grid">
                                <div class="ms-cd-box"><span class="ms-cd-num" data-cd="days">00</span><span class="ms-cd-unit">Days</span></div>
                                <div class="ms-cd-box"><span class="ms-cd-num" data-cd="hours">00</span><span class="ms-cd-unit">Hours</span></div>
                                <div class="ms-cd-box"><span class="ms-cd-num" data-cd="mins">00</span><span class="ms-cd-unit">Minutes</span></div>
                                <div class="ms-cd-box"><span class="ms-cd-num" data-cd="secs">00</span><span class="ms-cd-unit">Seconds</span></div>
                            </div>
                        </div>
                    @endif

                    <div class="ms-casting-tablewrap">
                        <table class="ms-casting-table">
                            <thead>
                                <tr>
                                    <th>Province</th>
                                    <th>City / Venue</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(($sc['casting_rows'] ?? []) as $row)
                                    <tr>
                                        <td data-label="Province">{{ $row['province'] ?? '' }}</td>
                                        <td data-label="City / Venue">{{ $row['venue'] ?? '' }}</td>
                                        <td data-label="Date"><span class="ms-date-chip">{{ $row['date'] ?? '' }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h3 class="ms-finals-title">{{ $sc['primes_title'] ?? 'Finals Schedule' }}</h3>
                    <div class="ms-finals-timeline">
                        @foreach(($sc['primes'] ?? []) as $p)
                            @php $isNext = $nextPrime && ($p['label'] ?? '') === ($nextPrime['label'] ?? '') && ($p['date'] ?? '') === ($nextPrime['date'] ?? ''); @endphp
                            <div class="ms-finals-item {{ $isNext ? 'is-next' : '' }}">
                                <span class="ms-finals-dot"></span>
                                <span class="ms-finals-name">{{ $p['label'] ?? '' }}</span>
                                <span class="ms-finals-date">{{ !empty($p['date']) ? \Carbon\Carbon::parse($p['date'])->format('M j, Y') : '' }}</span>
                                @if($isNext)<span class="ms-finals-badge">Up next</span>@endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        <script>
        (function () {
            var el = document.querySelector('.ms-countdown[data-deadline]');
            if (!el) return;
            var deadline = new Date(el.getAttribute('data-deadline')).getTime();
            var d = el.querySelector('[data-cd="days"]'), h = el.querySelector('[data-cd="hours"]'),
                m = el.querySelector('[data-cd="mins"]'), s = el.querySelector('[data-cd="secs"]');
            function pad(n) { return (n < 10 ? '0' : '') + n; }
            function tick() {
                var diff = deadline - Date.now();
                if (diff < 0) diff = 0;
                if (d) d.textContent = pad(Math.floor(diff / 86400000));
                if (h) h.textContent = pad(Math.floor(diff % 86400000 / 3600000));
                if (m) m.textContent = pad(Math.floor(diff % 3600000 / 60000));
                if (s) s.textContent = pad(Math.floor(diff % 60000 / 1000));
            }
            tick();
            setInterval(tick, 1000);
        })();
        </script>
        @endif
        <!-- Special Events Area End -->
        @php $___skip_old_casting = <<<'OLDCAST'
        <section class="ms-event-area pt-130 pb-70">
    <div class="row bdFadeUp">
        <div class="col-xl-12">
            <div class="ms-event-bg p-relative mb-60"
                 style="background-color:#1a1a1a; padding:30px; border-radius:12px;">

                <h2 style="color:#c9a24d; text-align:center; font-weight:700;">
                    Provincial Casting Calendar 2025
                </h2>

                <p style="text-align:center; font-size:16px; color:#b5b5b5;">
                    Here's a draft schedule for the Mulema Gospel casting by province in March and April.
                </p>

                <table style="width:100%; border-collapse:collapse; font-size:15px; margin-top:25px;">
                    <thead>
                        <tr style="background-color:#121212; color:#c9a24d;">
                            <th style="padding:14px; border:1px solid #2a2a2a;">Province</th>
                            <th style="padding:14px; border:1px solid #2a2a2a;">City / Venue</th>
                            <th style="padding:14px; border:1px solid #2a2a2a;">Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr style="background-color:#1f1f1f; color:#f5f5f5;">
                            <td style="padding:12px; border:1px solid #2a2a2a;">Far North</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">Maroua</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">March 21–22, 2026</td>
                        </tr>

                        <tr style="background-color:#1a1a1a; color:#f5f5f5;">
                            <td style="padding:12px; border:1px solid #2a2a2a;">North</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">Garoua</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">March 28–29, 2026</td>
                        </tr>

                        <tr style="background-color:#1f1f1f; color:#f5f5f5;">
                            <td style="padding:12px; border:1px solid #2a2a2a;">Adamawa</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">Ngaoundéré</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">April 4–5, 2026</td>
                        </tr>

                        <tr style="background-color:#1a1a1a; color:#f5f5f5;">
                            <td style="padding:12px; border:1px solid #2a2a2a;">East</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">Bertoua</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">April 11–12, 2026</td>
                        </tr>

                        <tr style="background-color:#1f1f1f; color:#f5f5f5;">
                            <td style="padding:12px; border:1px solid #2a2a2a;">South West</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">Buea</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">April 17–19, 2026</td>
                        </tr>

                        <tr style="background-color:#1a1a1a; color:#f5f5f5;">
                            <td style="padding:12px; border:1px solid #2a2a2a;">Littoral</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">Douala</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">April 17–19, 2026</td>
                        </tr>

                        <tr style="background-color:#1f1f1f; color:#f5f5f5;">
                            <td style="padding:12px; border:1px solid #2a2a2a;">North West</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">Bamenda</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">April 25–26, 2026</td>
                        </tr>

                        <tr style="background-color:#1a1a1a; color:#f5f5f5;">
                            <td style="padding:12px; border:1px solid #2a2a2a;">South</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">Sangmelima</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">April 25–26, 2026</td>
                        </tr>

                        <tr style="background-color:#1f1f1f; color:#f5f5f5;">
                            <td style="padding:12px; border:1px solid #2a2a2a;">West</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">Bafoussam</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">May 2–3, 2026</td>
                        </tr>

                        <tr style="background-color:#1a1a1a; color:#f5f5f5;">
                            <td style="padding:12px; border:1px solid #2a2a2a;">Centre</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">Yaoundé</td>
                            <td style="padding:12px; border:1px solid #2a2a2a;">May 8–10, 2026</td>
                        </tr>
                    </tbody>
                </table>

                <h3 style="color:#c9a24d; text-align:center; margin-top:30px;">
                    Finals Schedule
                </h3>

                <ul style="list-style:none; padding:0; max-width:400px; margin:20px auto;">
                    <li style="background:#1f1f1f; color:#f5f5f5; padding:12px; border-radius:6px; margin-bottom:8px;">
                        August 8: Prime 1
                    </li>
                    <li style="background:#1a1a1a; color:#f5f5f5; padding:12px; border-radius:6px; margin-bottom:8px;">
                        August 15: Prime 2
                    </li>
                    <li style="background:#1f1f1f; color:#f5f5f5; padding:12px; border-radius:6px; margin-bottom:8px;">
                        August 22: Prime 3
                    </li>
                    <li style="background:#121212; color:#c9a24d; padding:12px; border-radius:6px;">
                        August 29: Final Prime
                    </li>
                </ul>

            </div>
        </div>
    </div>
</section>
OLDCAST;
@endphp

        <!-- Partner Area Start Here  -->
        @if(\App\Helpers\SiteContent::enabled('partners'))
        <section class="ms-partner-area fix pb-130">
    <div class="container">
        <div class="row justify-content-center bdFadeUp">
            <div class="col-lg-6">
                <div class="section__title-wrapper mb-65 text-center bd-title-anim">
                    <span class="section__subtitle">{{ trans("file.Our Partners") }}</span>
                    <h2 class="section__title">{{ trans("file.Most") }}
                        <span class="animated-underline active">{{ trans("file.Valuable") }}</span>
                        {{ trans("file.Partners") }}
                    </h2>
                </div>
            </div>
        </div>

        <style>
    /* Logo container */
    .logo-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* 4 logos per row */
        gap: 30px; /* Proper spacing */
        justify-content: center;
        align-items: center;
        padding: 20px;
        text-align: center;
    }

    /* Individual logo styling */
    .logo-container img {
        width: 260px; /* 75% larger than original */
        height: auto;
        opacity: 0; /* Hidden initially */
        filter: none !important;
        transition: transform 0.5s ease-in-out, opacity 1s ease-in-out;
        animation: fadeInPop 1.5s ease-in-out forwards, float 3s infinite ease-in-out alternate;
    }

    /* Hover Effect: Spin + Zoom */
    .logo-container img:hover {
        transform: scale(1.3) rotate(360deg); /* Zoom + Full Spin */
        transition: transform 0.8s ease-in-out; /* Smooth animation */
    }

    /* Fade-in & Pop Effect */
    @keyframes fadeInPop {
        0% {
            opacity: 0;
            transform: scale(0.8);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Floating Animation (Gentle Up & Down Motion) */
    @keyframes float {
        0% {
            transform: translateY(0px);
        }
        100% {
            transform: translateY(10px);
        }
    }
    @media (max-width: 450px) {
        .ms-input2-box input, .ms-input2-box textarea {
            width: 164%;
        }
        .partner-logos {
            display: block;
        }
    }

</style>

<div class="logo-container partner-logos">
    <img src="{{ url('public/logo/' . $general_setting->site_logo) }}" alt="Site Logo">
    <img src="{{ url('public/logo/Beyond.png') }}" alt="Beyond Logo">
    <img src="{{ url('public/logo/Elfa.png') }}" alt="Elfa Logo">
    <img src="{{ url('public/logo/MBS.png') }}" alt="MBS Logo">
    <img src="{{ url('public/logo/HimFirst.png') }}" alt="HimFirst Logo">
    <img src="{{ url('public/logo/Nafi.png') }}" alt="Nafi Logo">
    <img src="{{ url('public/logo/OFCC.png') }}" alt="OFCC Logo">
    <img src="{{ url('public/logo/DEXDESIGN.png') }}" alt="DEXDESIGN Logo">
</div>

    </div>
</section>

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

<!-- Swiper JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>

    window.addEventListener('load', () => {
        const popup = document.getElementById('popup');
        const closeBtn = document.getElementById('closeBtn');

        // Show popup
        // popup.classList.add('active');

        // Close on click
        closeBtn.addEventListener('click', () => {
            popup.classList.remove('active');
        });

        // Optional: Close when clicking outside the popup
        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                popup.classList.remove('active');
            }
        });
    });

    var swiper = new Swiper(".swiper-container", {
    slidesPerView: 3,
    spaceBetween: 30,
    loop: true,
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
    },
});
</script>

<!-- CSS to Ensure Images Show Properly -->
<style>
.swiper-slide img {
    max-width: 100%;
    height: auto;
    display: block;
}
</style>

        @endif
        <!-- Partner Area End Here  -->

        <!-- CTA Area Start Here  -->
        @if(\App\Helpers\SiteContent::enabled('most_voted'))
        <section class="ms-cta-area ms-cta--120 p-relative zindex-10">
            <div class="container">
                <div class="ms-cta-bg include__bg ms-cta-overlay zindex-1 fix"  data-background="{{ url('public/frontend/images/sound-bg.png') }}">
                    @if($best_musician)
                        <a href="{{ route('musician.data', $best_musician->id) }}">
                    @endif
                        <div class="ms-cta-wrap">
                            <div class="ms-cta-item">
                                <div class="ms-cta-content">
                                    <h2 class="section__title mb-25">{{trans("file.Most Voted Contestant of the Week")}}</h2>
                                    @if($best_musician)
                                        <h4 class="section__title mb-25">({{ $best_musician->name }}) => ({{@$best_musician_data->total_vote}}   {{trans("file.Votes")}})</h4>
                                    @endif
                                    <p class="mb-0">
                                        {{trans("file.Here comes the best Contestant of the week")}}!
                                    </p>
                                </div>
                            </div>
                            <div class="ms-cta-item">
                                <div class="ms-cta-img ms-popular__thumb">
                                    @if($best_musician)
                                        <img src="{{url('public/images/employee',$best_musician->image)}}" loading="lazy" decoding="async">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @if($best_musician)
                        </a>
                    @endif
                </div>
            </div>
        </section>
        @endif

        <!-- Ambassador  area start -->
        @if(\App\Helpers\SiteContent::enabled('top_five'))
        <section class="ms-fun-brand ms-bg-2 pb-130 pt-125">
            <div class="container">
                <div class="row align-items-end mb-25 bdFadeUp">
                    <div class="col-xl-6 col-lg-6">
                        <div class="section__title-wrapper mb-40 bd-title-anim">
                            <span class="section__subtitle">{{trans('file.Top Five Contestants')}}</span>
                            <h2 class="section__title msg_title">
                                <span class="animated-underline active"></span> <br>

                            </h2>
                        </div>
                    </div>
                </div>
                <div class="row bdFadeUp">
                    <div class="col-xxl-12">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-popular-1" role="tabpanel" aria-labelledby="nav-popular-1-tab" tabindex="0">
                                <div class="swiper-container ms-popular-active fix">
                                    <div class="swiper-wrapper">
                                        @foreach($best_musicians as $key => $best_musician_data)
                                            @php
                                                $best_musician  = Employee::find($best_musician_data->musician_id);
                                            @endphp
                                            <div class="swiper-slide">
                                                <div class="ms-popular__item p-relative mb-30">
                                                    <div class="ms-popular__thumb" style="border-radius: 10%;">
                                                        <div class="ms-popular-overlay"></div>

                                                        <a ><img src="{{url('public/images/employee',$best_musician->image)}}" loading="lazy" decoding="async" ></a>
                                                        <a class="ms-popular__link" href="{{ route('musician.data', $best_musician->id) }}">
                                                            <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                        </a>
                                                    </div>

                                                    <h4 class="ms-popular__title"><a>
                                                            @if($key == 0)
                                                                1st
                                                            @elseif($key == 1)
                                                                2nd
                                                            @elseif($key == 2)
                                                                3rd
                                                            @else
                                                                {{$key + 1}}th
                                                            @endif
                                                            -- {{ $best_musician->name }} ({{ $best_musician_data->total_vote }})

                                                        </a>
                                                    </h4>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
        <!-- Ambassador  area end -->
        <!-- CTA Area End Here  -->

    </main>
    <script>
        setTimeout(function() {
            $(".alert").alert('close');
        }, 10000); // 10000 milliseconds = 10 seconds
    </script>
@endsection

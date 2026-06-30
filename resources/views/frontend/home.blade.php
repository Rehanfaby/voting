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
        <section class="ms-song-area pt-40 pb-40">
            <div class="container-fluid ms-maw-1710">
                <div class="swiper-container ms-song-active fix">
                    <div class="swiper-wrapper">
                        @foreach($musicians as $key=>$musician)
                            <div class="swiper-slide">
                                <div class="ms-song-item">
                                    <div class="ms-song-img p-relative">
                                        <a href="{{ route('musician.data', $musician->id) }}">
                                            <img src="{{url('public/images/employee',$musician->image)}}" alt="{{trans('file.Contestants name')}}">
                                        </a>
                                        @if($see_votes)
                                            <span class="ms-song-num">{{ $vote_counts[$musician->id] ?? 0 }}</span>
                                        @endif
                                    </div>
                                    <div class="ms-song-content">
                                        <h3 class="ms-song-title"><a href="{{ route('musician.data', $musician->id) }}">{{ $musician->name }}</a>
                                        </h3>
                                        <span class="ms-song-text"></span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
                <div class="ms-br-30 mx-auto include__bg z-index-1 ms-overlay-1 p-relative" data-background="{{ url('public/frontend/images/top-banner-en.jpeg') }}">
                @else
                <div class="ms-br-30 mx-auto include__bg z-index-1 ms-overlay-1 p-relative" data-background="{{ url('public/frontend/images/top-banner-fr.jpeg') }}">
                @endif
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-xl-11">
                                <div class="ms-banner__main-wrapper">
                                    <div class="ms-banner__content text-center">
                                        <h1 class="ms-banner__bg-title" data-background="">
                                            {{trans('file.Musicly')}}
                                        </h1>
                                        <h2 class="ms-banner__title msg_title bd-title-anim">{{trans('file.Vote for your favourite Contestant')}}</h2>
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
                                        <img src="{{url('public/images/employee',$musician->image)}}">
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
                                            <span style="color: yellow">{{ $vote_counts[$musician->id] ?? 0 }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
        <!-- Function Brand Area End Here  -->




        <!-- judge  area start -->
        <section class="ms-fun-brand ms-bg-2 pb-130 pt-125">
            <div class="container">
                <div class="row align-items-end mb-25 bdFadeUp">
                    <div class="col-xl-6 col-lg-6">
                        <div class="section__title-wrapper mb-40 bd-title-anim">
                            <span class="section__subtitle">{{trans('file.Our Seasoned Judges')}}</span>
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
                                                        <a ><img src="{{url('public/images/employee',$contentant->image)}}" ></a>
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
                                <h3>{{trans('file.Mumela the')}} </h3>
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
                                                        <a ><img src="{{url('public/images/employee',$ambassador->image)}}" ></a>
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
        <!-- Ambassador  area end -->


        <!-- Special Events Area Start -->
        <section class="ms-casting-area pt-130 pb-130">
            <div class="container">
                <div class="row justify-content-center bdFadeUp">
                    <div class="col-lg-8">
                        <div class="section__title-wrapper mb-60 text-center bd-title-anim">
                            <span class="section__subtitle">{{ trans('file.Casting Tour') ?? 'Casting Tour' }}</span>
                            <h2 class="section__title">Provincial Casting
                                <span class="animated-underline active">Calendar</span> 2025
                            </h2>
                            <p class="casting-lead">Here's the draft schedule for the Mulema Gospel casting by province across March and April.</p>
                        </div>
                    </div>
                </div>

                <div class="row bdFadeUp">
                    @php
                        $castingSchedule = [
                            ['province' => 'Far North',   'city' => 'Maroua',     'date' => 'March 15–16, 2025'],
                            ['province' => 'North',        'city' => 'Garoua',     'date' => 'March 22–23, 2025'],
                            ['province' => 'Adamawa',      'city' => 'Ngaoundéré', 'date' => 'March 29–30, 2025'],
                            ['province' => 'East',         'city' => 'Bertoua',    'date' => 'April 5–6, 2025'],
                            ['province' => 'South West',   'city' => 'Buea',       'date' => 'April 5–6, 2025'],
                            ['province' => 'North West',   'city' => 'Bamenda',    'date' => 'April 12–13, 2025'],
                            ['province' => 'West',         'city' => 'Bafoussam',  'date' => 'April 19–20, 2025'],
                            ['province' => 'Littoral',     'city' => 'Douala',     'date' => 'April 12–13, 2025'],
                            ['province' => 'South',        'city' => 'Ebolowa',    'date' => 'April 19–20, 2025'],
                            ['province' => 'Centre',       'city' => 'Yaoundé',    'date' => 'April 26–27, 2025'],
                        ];
                    @endphp

                    @foreach($castingSchedule as $stop)
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="casting-card">
                                <div class="casting-card__icon">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div class="casting-card__body">
                                    <span class="casting-card__province">{{ $stop['province'] }}</span>
                                    <h4 class="casting-card__city"><i class="fa-solid fa-city"></i> {{ $stop['city'] }}</h4>
                                    <span class="casting-card__date"><i class="fa-regular fa-calendar-days"></i> {{ $stop['date'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row justify-content-center bdFadeUp mt-50">
                    <div class="col-lg-8">
                        <div class="section__title-wrapper mb-40 text-center bd-title-anim">
                            <span class="section__subtitle"><i class="fa-solid fa-trophy"></i> Grand Finale</span>
                            <h2 class="section__title">Finals <span class="animated-underline active">Schedule</span></h2>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center bdFadeUp">
                    <div class="col-xl-10">
                        <div class="casting-timeline">
                            @php
                                $finals = [
                                    ['label' => 'Prime 1',     'date' => 'August 9',  'icon' => 'fa-microphone-lines'],
                                    ['label' => 'Prime 2',     'date' => 'August 17', 'icon' => 'fa-music'],
                                    ['label' => 'Prime 3',     'date' => 'August 23', 'icon' => 'fa-star'],
                                    ['label' => 'Final Prime', 'date' => 'August 30', 'icon' => 'fa-crown'],
                                ];
                            @endphp
                            @foreach($finals as $i => $final)
                                <div class="casting-timeline__item {{ $loop->last ? 'is-final' : '' }}">
                                    <div class="casting-timeline__dot"><i class="fa-solid {{ $final['icon'] }}"></i></div>
                                    <div class="casting-timeline__content">
                                        <span class="casting-timeline__date">{{ $final['date'] }}</span>
                                        <h5 class="casting-timeline__label">{{ $final['label'] }}</h5>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .ms-casting-area { background: radial-gradient(1200px 600px at 50% -10%, rgba(240,169,59,.10), transparent 60%); }
                .ms-casting-area .casting-lead { color: rgba(255,255,255,.7); font-size: 16px; margin-top: 15px; }

                .casting-card {
                    position: relative;
                    display: flex;
                    align-items: center;
                    gap: 18px;
                    margin-bottom: 30px;
                    padding: 26px 24px;
                    border-radius: 18px;
                    background: rgba(255,255,255,.04);
                    border: 1px solid rgba(255,255,255,.08);
                    backdrop-filter: blur(6px);
                    transition: transform .35s ease, border-color .35s ease, box-shadow .35s ease;
                    overflow: hidden;
                }
                .casting-card::before {
                    content: "";
                    position: absolute; inset: 0;
                    background: linear-gradient(135deg, rgba(240,169,59,.14), rgba(226,86,42,.12));
                    opacity: 0; transition: opacity .35s ease;
                }
                .casting-card:hover {
                    transform: translateY(-6px);
                    border-color: rgba(240,169,59,.5);
                    box-shadow: 0 18px 40px rgba(0,0,0,.45);
                }
                .casting-card:hover::before { opacity: 1; }
                .casting-card > * { position: relative; z-index: 1; }
                .casting-card__icon {
                    flex: 0 0 auto;
                    width: 58px; height: 58px;
                    display: flex; align-items: center; justify-content: center;
                    border-radius: 14px;
                    font-size: 22px; color: #1a1208;
                    background: linear-gradient(135deg, #e2562a, #f0a93b);
                    box-shadow: 0 8px 20px rgba(226,86,42,.35);
                }
                .casting-card__province {
                    display: inline-block;
                    font-size: 12px; letter-spacing: 1.5px; text-transform: uppercase;
                    color: #f0a93b; font-weight: 700; margin-bottom: 4px;
                }
                .casting-card__city { color: #fff; font-size: 20px; margin: 0 0 8px; font-weight: 700; }
                .casting-card__city i { color: #e2562a; font-size: 15px; margin-right: 4px; }
                .casting-card__date { display: inline-flex; align-items: center; gap: 7px; color: rgba(255,255,255,.7); font-size: 14px; }
                .casting-card__date i { color: #f6c453; }

                .casting-timeline { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
                .casting-timeline__item {
                    position: relative;
                    flex: 1 1 200px; max-width: 240px;
                    text-align: center;
                    padding: 30px 18px 24px;
                    border-radius: 18px;
                    background: rgba(255,255,255,.04);
                    border: 1px solid rgba(255,255,255,.08);
                    transition: transform .35s ease, border-color .35s ease;
                }
                .casting-timeline__item:hover { transform: translateY(-6px); border-color: rgba(246,196,83,.5); }
                .casting-timeline__item.is-final {
                    background: linear-gradient(135deg, rgba(246,196,83,.16), rgba(226,86,42,.12));
                    border-color: rgba(246,196,83,.45);
                }
                .casting-timeline__dot {
                    width: 64px; height: 64px; margin: 0 auto 16px;
                    display: flex; align-items: center; justify-content: center;
                    border-radius: 50%;
                    font-size: 24px; color: #1a1208;
                    background: linear-gradient(135deg, #e2562a, #f0a93b);
                    box-shadow: 0 10px 24px rgba(0,0,0,.4);
                }
                .casting-timeline__item.is-final .casting-timeline__dot {
                    background: linear-gradient(135deg, #f6c453, #e2562a);
                }
                .casting-timeline__date { display: block; color: #f0a93b; font-size: 13px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }
                .casting-timeline__label { color: #fff; font-size: 18px; margin: 6px 0 0; font-weight: 700; }
                .mt-50 { margin-top: 50px; }

                @media (max-width: 575px) {
                    .casting-card { padding: 20px; }
                    .casting-timeline__item { max-width: 100%; }
                }
            </style>
        </section>
        <!-- Special Events Area End -->

        <!-- Partner Area Start Here  -->
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
        popup.classList.add('active');

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

        <!-- Partner Area End Here  -->

        <!-- CTA Area Start Here  -->
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
                                        <img src="{{url('public/images/employee',$best_musician->image)}}">
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

        <!-- Ambassador  area start -->
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

                                                        <a ><img src="{{url('public/images/employee',$best_musician->image)}}" ></a>
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
        <!-- Ambassador  area end -->
        <!-- CTA Area End Here  -->

    </main>
    <script>
        setTimeout(function() {
            $(".alert").alert('close');
        }, 10000); // 10000 milliseconds = 10 seconds
    </script>
@endsection

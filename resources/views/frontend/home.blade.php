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
    </style>

    <main>
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
                                            @php
                                                //                                                $start_date = date('Y-m-d', strtotime('last monday'));
                                                //                                                $end_date = date('Y-m-d');

                                                                                                $vote_count[] = \App\vote::where('status', true)
                                                                                                ->where('musician_id', $musician->id)
                                                //                                                ->whereDate('votes.created_at', '>=', $start_date)
                                                //                                                ->whereDate('votes.created_at', '<=', $end_date)
                                                                                                ->sum('vote');
                                            @endphp
                                            <span class="ms-song-num">{{ $vote_count[$key] }}</span>
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
        <section class="ms-event-area pt-130 pb-70">
        <div class="row bdFadeUp">
    <div class="col-xl-12">
        <div class="ms-event-bg p-relative mb-60" style="background-color: #f8f9fa; padding: 20px; border-radius: 10px;">
            <h2 style="color: #ff4500; text-align: center; font-weight: bold;">Provincial Casting Calendar 2025</h2>
            <p style="text-align: center; font-size: 16px; color: #333;">Here's a draft schedule for the Mulema Gospel casting by province in March and April.</p>
            <table style="width: 100%; border-collapse: collapse; font-size: 16px;">
                <thead>
                    <tr style="background-color: #ff4500; color: white;">
                        <th style="padding: 10px; border: 1px solid #ddd;">Province</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">City/Venue</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background-color: #ffe5b4;">
                        <td style="padding: 10px; border: 1px solid #ddd;">Far North</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">Maroua</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">March 15-16, 2025</td>
                    </tr>
                    <tr style="background-color: #fff5e1;">
                        <td style="padding: 10px; border: 1px solid #ddd;">North</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">Garoua</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">March 22-23, 2025</td>
                    </tr>
                    <tr style="background-color: #ffe5b4;">
                        <td style="padding: 10px; border: 1px solid #ddd;">Adamawa</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">Ngaoundéré</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">March 29-30, 2025</td>
                    </tr>
                    <tr style="background-color: #fff5e1;">
                        <td style="padding: 10px; border: 1px solid #ddd;">East</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">Bertoua</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">April 5-6, 2025</td>
                    </tr>
                    <tr style="background-color: #ffe5b4;">
                        <td style="padding: 10px; border: 1px solid #ddd;">South West</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">Buea</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">April 5-6, 2025</td>
                    </tr>
                    <tr style="background-color: #fff5e1;">
                        <td style="padding: 10px; border: 1px solid #ddd;">North West</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">Bamenda</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">April 12-13, 2025</td>
                    </tr>
                    <tr style="background-color: #ffe5b4;">
                        <td style="padding: 10px; border: 1px solid #ddd;">West</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">Bafoussam</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">April 19-20, 2025</td>
                    </tr>
                    <tr style="background-color: #fff5e1;">
                        <td style="padding: 10px; border: 1px solid #ddd;">Littoral</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">Douala</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">April 12-13, 2025</td>
                    </tr>
                    <tr style="background-color: #ffe5b4;">
                        <td style="padding: 10px; border: 1px solid #ddd;">South</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">Ebolowa</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">April 19-20, 2025</td>
                    </tr>
                    <tr style="background-color: #fff5e1;">
                        <td style="padding: 10px; border: 1px solid #ddd;">Centre</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">Yaoundé</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">April 26-27, 2025</td>
                    </tr>
                </tbody>
            </table>
            <h3 style="color: #ff4500; text-align: center; margin-top: 20px;">Finals Schedule</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="background-color: #ffe5b4; padding: 10px; border-radius: 5px; margin-bottom: 5px;">August 9: Prime 1 (25 candidates)</li>
                <li style="background-color: #fff5e1; padding: 10px; border-radius: 5px; margin-bottom: 5px;">August 16: Prime 2 (20 candidates)</li>
                <li style="background-color: #ffe5b4; padding: 10px; border-radius: 5px; margin-bottom: 5px;">August 23: Prime 3 (15 candidates)</li>
                <li style="background-color: #fff5e1; padding: 10px; border-radius: 5px; margin-bottom: 5px;">August 30: Final Prime (10 candidates)</li>
            </ul>
        </div>
    </div>
</div>
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
                                        <h4 class="section__title mb-25">({{ $best_musician->name }})</h4>
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
                                        @foreach($best_musicians as $key => $best_musician)
                                            @php
                                                $best_musician  = Employee::find($best_musician->musician_id);
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
                                                            -- {{ $best_musician->name }}

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

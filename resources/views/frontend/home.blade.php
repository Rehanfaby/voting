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
                                            <img src="{{url('public/images/employee',$musician->image)}}" alt="musician name">
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
            <a class="ms-scroll-down" href="#">SCROLL DOWN</a>
            <div class="container-fluid ms-maw-1710">
                <div class="ms-br-30 mx-auto include__bg z-index-1 ms-overlay-1 p-relative" data-background="{{ url('public/frontend/images/banner-thumb-01.jpg') }}">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-xl-11">
                                <div class="ms-banner__main-wrapper">
                                    <div class="ms-banner__content text-center">
                                        <h1 class="ms-banner__bg-title" data-background="">
                                            Musicly
                                        </h1>
                                        <h2 class="ms-banner__title msg_title bd-title-anim">Vote your favourite Musician</h2>
                                    </div>
                                    <div class="ms-banner__form bdFadeUp">
                                        <form action="{{ route('musician.find') }}" method="post">
                                            @csrf
                                            <div class="ms-banner__from-inner white-bg">
                                                <div class="ms-input2-box white-bg">
                                                    <input type="text" placeholder="Search Your Musician" name="search">
                                                </div>
                                                <div class="banner__form-button">
                                                    <button type="submit" class="input__btn"><i class="flaticon-loupe"></i>
                                                        Find
                                                        Acts</button>
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
                            <span class="section__subtitle">Beyond The Talent Showr</span>
                            <h2 class="section__title">
                                Qualified <span class="animated-underline active">Contestants for the Week!</span>

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
                                        <img src="{{url('public/images/employee',$musician->image)}}" alt="function brand">
                                    </div>
                                    <div class="ms-fun-brand-content">
                                        <h3 class="ms-fun-brand-title">
                                            {{ $musician->name }}</h3>
                                        <span class="ms-fun-brand-subtitle">{{ @$musician->departments->name }}</span>
                                    </div>
                                </div>
                                <div class="ms-fun-brand-bottom">
                                    <div class="ms-fun-brand-location">
                                        <i class="fa fa-vote-yea"></i> Vote Me
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




        <!-- Popular  area start -->
        <section class="ms-fun-brand ms-bg-2 pb-130 pt-125">
            <div class="container">
                <div class="row align-items-end mb-25 bdFadeUp">
                    <div class="col-xl-6 col-lg-6">
                        <div class="section__title-wrapper mb-40 bd-title-anim">
                            <span class="section__subtitle">Our Seasoned Judges</span>
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
                                                        <a ><img src="{{url('public/images/employee',$contentant->image)}}" alt="popular band"></a>
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
        <!-- Popular  area end -->




        <!-- Text scroll area start -->
        <section class="text__scroll-area include__bg ms-ts-space p-relative fix"  data-background="{{ url('public/frontend/images/sound-bg.png') }}">
            <div class="text__scroll-wrapper">
                <div class="ms-text-line-1">
                    <div class="swiper-container ms-st-active scroll__text pt-20 pb-20">
                        <div class="swiper-wrapper ms-st-active-wrapper">
                            <div class="swiper-slide">
                                <h3>Strum</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>Sing</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3><span class="text-color-1">Soar</span></h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>Rise</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3><span class="text-color-1">To</span></h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>Beyond The </h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>Talent</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3><span class="text-color-2">Show</span></h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>Music</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>Competition</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ms-text-line-2">
                    <div class="swiper-container ms-str-active scroll__text pt-20 pb-20">
                        <div class="swiper-wrapper ms-str-active-wrapper">
                            <div class="swiper-slide">
                                <h3>Beyond</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>The</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3><span class="text-color-1">Talent Show</span></h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>Music</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3><span class="text-color-1">Competition</span></h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>Awaits</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Text scroll area end -->

        <!-- Special Events Area Start -->
        <section class="ms-event-area pt-130 pb-70">
            <div class="container">
                <div class="row align-items-end mb-25 bdFadeUp">
                    <div class="col-lg-8">
                        <div class="section__title-wrapper mb-40 bd-title-anim">
                            <span class="section__subtitle">Special Events</span>
                            <h2 class="section__title"><span class="animated-underline active">Special
                                        event</span>
                                coming up
                            </h2>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="trending-btn mb-40 d-flex justify-content-lg-end">
                            <a class="border__btn" href="event.html">View All Event</a>
                        </div>
                    </div>
                </div>
                <div class="row bdFadeUp">
                    <div class="col-xl-8">
                        <div class="ms-event-bg p-relative mb-60" data-background="">
                            <div class="ms-event-overlay p-absolute"></div>
                            <div class="ms-event-wrap">
                                <div class="ms-event-inner-box">
                                    <div class="ms-event-active">
                                        <div class="ms-event-item">
                                            <div class="ms-event-item-top">
                                                <h3 class="ms-event-title"><a href="event-details.html">Wedding
                                                        entertainment
                                                        ideas</a></h3>
                                                <p class="ms-event-text">Our hand-picked acts will guarantee
                                                    you fantastic
                                                    wedding
                                                    entertainment for each part.
                                                    We'll provide help and support 24 hours a day, 7 days a
                                                    week, right up
                                                    until</p>
                                                <div class="ms-event-inner">
                                                    <div class="ms-event-location">
                                                        <a href="https://www.google.com/maps" target="_blank"><i class="flaticon-pin"></i>Chicago</a>
                                                    </div>
                                                    <div class="ms-event-date">
                                                        <span>7:00 PM, Saturday, February 18, 2023</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ms-event-item-bottom">
                                                <h3 class="ms-event-title"><a href="event-details.html">Corporate
                                                        entertainment ideas</a>
                                                </h3>
                                                <p class="ms-event-text">Our hand-picked acts will guarantee
                                                    you fantastic
                                                    wedding
                                                    entertainment for each part.
                                                    We'll provide help and support 24 hours a day, 7 days a
                                                    week, right up
                                                    until</p>
                                                <div class="ms-event-inner">
                                                    <div class="ms-event-location">
                                                        <a href="https://www.google.com/maps" target="_blank"><i class="flaticon-pin"></i>Chicago</a>
                                                    </div>
                                                    <div class="ms-event-date">
                                                        <span>7:00 PM, Saturday, February 26, 2023</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ms-event-item">
                                            <div class="ms-event-item-top">
                                                <h3 class="ms-event-title"><a href="event-details.html">Party
                                                        entertainment
                                                        ideas</a></h3>
                                                <p class="ms-event-text">Our hand-picked acts will guarantee
                                                    you fantastic
                                                    wedding
                                                    entertainment for each part.
                                                    We'll provide help and support 24 hours a day, 7 days a
                                                    week, right up
                                                    until</p>
                                                <div class="ms-event-inner">
                                                    <div class="ms-event-location">
                                                        <a href="https://www.google.com/maps" target="_blank"><i class="flaticon-pin"></i>Chicago</a>
                                                    </div>
                                                    <div class="ms-event-date">
                                                        <span>7:00 PM, Saturday, February 18, 2023</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ms-event-item-bottom">
                                                <h3 class="ms-event-title"><a href="event-details.html">Corporate
                                                        entertainment ideas</a>
                                                </h3>
                                                <p class="ms-event-text">Hosted by Beyond Company Limited
                                                    weill be come together to sing hymns and worship
                                                    the almighty God.
                                                </p>
                                                <div class="ms-event-inner">
                                                    <div class="ms-event-location">
                                                        <a href="https://www.google.com/maps" target="_blank"><i class="flaticon-pin"></i>Bamenda</a>
                                                    </div>
                                                    <div class="ms-event-date">
                                                        <span>3:00 PM, Sunday, February 3rd, 2023</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ms-event-item">
                                            <div class="ms-event-item-top">
                                                <h3 class="ms-event-title"><a href="event-details.html">The Hymns
                                                        Festival
                                                        ideas</a></h3>
                                                <p class="ms-event-text">Hosted by Beyond Company Limited
                                                    weill be come together to sing hymns and worship
                                                    the almighty God.
                                                </p>
                                                <div class="ms-event-inner">
                                                    <div class="ms-event-location">
                                                        <a href="https://www.google.com/maps" target="_blank"><i class="flaticon-pin"></i>Bamenda</a>
                                                    </div>
                                                    <div class="ms-event-date">
                                                        <span>3:00 PM, Sunday, February 3rd, 2023</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ms-event-item-bottom">
                                                <h3 class="ms-event-title"><a href="event-details.html">Royal
                                                        Priesthood &rsquo;s Choir
                                                        bands</a></h3>
                                                <p class="ms-event-text">Come and Join
                                                    us as we
                                                    build
                                                    the house of God.Nothing is too small.
                                                </p>
                                                <div class="ms-event-inner">
                                                    <div class="ms-event-location">
                                                        <a href="https://www.google.com/maps" target="_blank"><i class="flaticon-pin"></i>Cameroon</a>
                                                    </div>
                                                    <div class="ms-event-date">
                                                        <span>2:00 PM, Sunday, Auguest 27, 2023</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ms-event-dots ms-round-dots"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="ms-event-play d-inline-block w-img p-relative mb-60">
                            <img src="{{ asset('public/frontend/images/event-event-bg-2.png') }}" alt="event img"  height="350px" style="border-radius: 15%;">
                        </div>
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
                            <span class="section__subtitle">Our Partners</span>
                            <h2 class="section__title">Most <span class="animated-underline active">Valuable</span>
                                Partners
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="swiper-container ms-partner-active bdFadeUp">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="{{url('public/logo', $general_setting->site_logo)}}" alt="partner image">
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
                                        <h2 class="section__title mb-25">Most Voted Music of the Week</h2>
                                        @if($best_musician)
                                            <h4 class="section__title mb-25">({{ $best_musician->name }})</h4>
                                        @endif
                                        <p class="mb-0">
                                            Here comes the best musician of the week!
                                        </p>
                                    </div>
                                </div>
                                <div class="ms-cta-item">
                                    <div class="ms-cta-img ms-popular__thumb">
                                        @if($best_musician)
                                            <img src="{{url('public/images/employee',$best_musician->image)}}" alt="cta image">
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
        <!-- CTA Area End Here  -->

    </main>
@endsection

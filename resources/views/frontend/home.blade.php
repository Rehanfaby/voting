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
                        @foreach($musicians as $musician)
                            <div class="swiper-slide">
                                <div class="ms-song-item">
                                    <div class="ms-song-img p-relative">
                                        <a href="{{ route('musician.data', $musician->id) }}">
                                            <img src="{{url('public/images/employee',$musician->image)}}" alt="musician name">
                                        </a>
                                        <span class="ms-song-num">{{ $musician->id }}</span>
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
                <div class="ms-br-30 mx-auto include__bg z-index-1 ms-overlay-1 p-relative" data-background="assets/img/banner/banner-thumb-01.jpg">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-xl-11">
                                <div class="ms-banner__main-wrapper">
                                    <div class="ms-banner__content text-center">
                                        <h1 class="ms-banner__bg-title" data-background="assets/img/banner/title-bg.jpg">
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

        <!-- Text scroll area start -->
        <section class="text__scroll-area include__bg ms-ts-space p-relative fix" data-background="assets/img/bg/text-scroll-bg.png">
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
                                <h3>The</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>Music</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3><span class="text-color-2">Competition</span></h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>Band</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>Challenge</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ms-text-line-2">
                    <div class="swiper-container ms-str-active scroll__text pt-20 pb-20">
                        <div class="swiper-wrapper ms-str-active-wrapper">
                            <div class="swiper-slide">
                                <h3>Compose</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3>Your</h3>
                            </div>
                            <div class="swiper-slide">
                                <h3><span class="text-color-1">Victory</span></h3>
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

        <!-- Function Brand Area Start Here  -->
        <section class="ms-fun-brand ms-bg-2 pb-130 pt-125">
            <div class="container">
                <div class="row justify-content-center bdFadeUp">
                    <div class="col-xl-7">
                        <div class="section__title-wrapper mb-65 text-center bd-title-anim">
                            <span class="section__subtitle">Music Competitor</span>
                            <h2 class="section__title">
                                our <span class="animated-underline active">most popular Musicians</span>

                            </h2>
                        </div>
                    </div>
                </div>
                <div class="ms-fun-brand-wrap bdFadeUp">
                    @foreach($musicians as $musician)
                        <div class="ms-fun-brand-item ms-fun-border">
                            <div class="ms-fun-brand-top mb-20">
                                <div class="ms-fun-brand-thumb">
                                    <a href="{{ route('musician.data', $musician->id) }}"><img src="{{url('public/images/employee',$musician->image)}}" alt="function brand"></a>
                                </div>
                                <div class="ms-fun-brand-content">
                                    <h3 class="ms-fun-brand-title">
                                        <a href="{{ route('musician.data', $musician->id) }}">{{ $musician->name }}</a></h3>
                                    <span class="ms-fun-brand-subtitle">{{ @$musician->departments->name }}</span>
                                </div>
                            </div>
                            <div class="ms-fun-brand-bottom">
                                <div class="ms-fun-brand-location">
                                    <a href="{{ route('musician.data', $musician->id) }}"> <i class="fa fa-vote-yea"></i>Vote</a>
                                </div>
                                <div class="ms-fun-brand-rating">
                                    <a href="{{ route('musician.data', $musician->id) }}">
                                        <button>Vote me</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!-- Function Brand Area End Here  -->


        <!-- Popular  area start -->
        <section class="ms-popular__area pt-130 pb-100 fix">
            <div class="container">
                <div class="row align-items-end mb-25 bdFadeUp">
                    <div class="col-xl-6 col-lg-6">
                        <div class="section__title-wrapper mb-40 bd-title-anim">
                            <span class="section__subtitle">Popular Categories</span>
                            <h2 class="section__title msg_title">
                                <span class="animated-underline active">Handpicked party</span> <br>
                                bands for 2023
                            </h2>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="ms-popular__tab ms-popular-flex mb-40">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-popular-1-tab" data-bs-toggle="tab" data-bs-target="#nav-popular-1" type="button" role="tab" aria-controls="nav-popular-1" aria-selected="true">Musical Acts</button>
                                    <button class="nav-link" id="nav-popular-2-tab" data-bs-toggle="tab" data-bs-target="#nav-popular-2" type="button" role="tab" aria-controls="nav-popular-2" aria-selected="false">Entertainers</button>
                                    <button class="nav-link" id="nav-popular-3-tab" data-bs-toggle="tab" data-bs-target="#nav-popular-3" type="button" role="tab" aria-controls="nav-popular-3" aria-selected="false">Event
                                        Services</button>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="row bdFadeUp">
                    <div class="col-xxl-12">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-popular-1" role="tabpanel" aria-labelledby="nav-popular-1-tab" tabindex="0">
                                <div class="swiper-container ms-popular-active fix">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-01.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Singers</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-02.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Bands &amp;
                                                        Group</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-03.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Tributes</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-04.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Solo
                                                        Musicians
                                                    </a></h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-01.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Singers</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-02.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Bands &amp;
                                                        Group</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-03.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Tributes</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-04.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Solo
                                                        Musicians
                                                    </a></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-popular-2" role="tabpanel" aria-labelledby="nav-popular-2-tab" tabindex="0">
                                <div class="swiper-container ms-popular-active fix">
                                    <div class="swiper-wrapper">

                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-03.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Tributes</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-04.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Solo
                                                        Musicians
                                                    </a></h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-01.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Singers</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-02.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Bands &amp;
                                                        Group</a>
                                                </h4>
                                            </div>
                                        </div>

                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-03.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Tributes</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-04.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Solo
                                                        Musicians
                                                    </a></h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-01.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Singers</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-02.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Bands &amp;
                                                        Group</a>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-popular-3" role="tabpanel" aria-labelledby="nav-popular-3-tab" tabindex="0">
                                <div class="swiper-container ms-popular-active fix">
                                    <div class="swiper-wrapper">

                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-04.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Solo
                                                        Musicians
                                                    </a></h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-01.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Singers</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-02.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Bands &amp;
                                                        Group</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-03.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Tributes</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-01.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Singers</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-02.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Bands &amp;
                                                        Group</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-03.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Tributes</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="genres-details.html"><img src="{{ asset('public/frontend/images/popular-popular-04.png') }}" alt="popular band"></a>
                                                    <a href="genres-details.html" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="genres-details.html">Solo
                                                        Musicians
                                                    </a></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Popular  area end -->

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
                        <div class="ms-event-bg p-relative mb-60" data-background="assets/img/event/event-testimonial-bg.png">
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
                                                <h3 class="ms-event-title"><a href="event-details.html">New
                                                        Year&rsquo;s Eve party
                                                        bands</a></h3>
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
                                    </div>
                                </div>
                                <div class="ms-event-dots ms-round-dots"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="ms-event-play d-inline-block w-img p-relative mb-60">
                            <div class="ms-event-play-overlay p-absolute"></div>
                            <img src="{{ asset('public/frontend/images/event-event-bg-2.png') }}" alt="event img">
                            <a href="https://www.youtube.com/watch?v=Rf9flQISwok" class="ms-play-border ms-event-play-btn popup-video">play</a>
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
                            <h2 class="section__title">Valuable <span class="animated-underline active">Featured</span>
                                Partners
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="swiper-container ms-partner-active bdFadeUp">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="{{ asset('public/frontend/images/partner-partner-01.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('public/frontend/images/partner-partner-02.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('public/frontend/images/partner-partner-03.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('public/frontend/images/partner-partner-04.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('public/frontend/images/partner-partner-05.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('public/frontend/images/partner-partner-04.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('public/frontend/images/partner-partner-01.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('public/frontend/images/partner-partner-02.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('public/frontend/images/partner-partner-03.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('public/frontend/images/partner-partner-04.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('public/frontend/images/partner-partner-05.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('public/frontend/images/partner-partner-04.png') }}" alt="partner image">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Partner Area End Here  -->

        <!-- CTA Area Start Here  -->
        <section class="ms-cta-area ms-cta--120 p-relative zindex-10">
            <div class="container">
                <div class="ms-cta-bg include__bg ms-cta-overlay zindex-1 fix" data-background="assets/img/cta/cta-bg.png">
                    <div class="ms-cta-wrap">
                        <div class="ms-cta-item">
                            <div class="ms-cta-content">
                                <h2 class="section__title mb-25">Best way to Hire to Musician</h2>
                                <p class="mb-0">Party Bands For Hire a Live Music Agency offering an
                                    unknown
                                    printer took a
                                    galley
                                    wtnd scrambled
                                    it
                                    to
                                    makeive centuriesbut
                                    also.</p>
                            </div>
                        </div>
                        <div class="ms-cta-item">
                            <div class="ms-cta-img">
                                <img src="{{ asset('public/frontend/images/cta-cta-01.png') }}" alt="cta image">
                            </div>
                        </div>
                        <div class="ms-cta-item">
                            <div class="ms-cta-app">
                                <a target="_blank" href="#"><img src="{{ asset('public/frontend/images/cta-app-store.png') }}" alt="app store"></a>
                                <a target="_blank" href="#"><img src="{{ asset('public/frontend/images/cta-play-store.png') }}" alt="play store"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- CTA Area End Here  -->

    </main>
@endsection

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
                        <div class="swiper-slide">
                            <div class="ms-song-item">
                                <div class="ms-song-img p-relative">
                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/brand-song-01.png') }}" alt="brand-song"></a>
                                    <span class="ms-song-num">01</span>
                                </div>
                                <div class="ms-song-content">
                                    <h3 class="ms-song-title"><a href="genres-details.html">Arcade Fire</a>
                                    </h3>
                                    <span class="ms-song-text">Canadian rock group</span>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="ms-song-item">
                                <div class="ms-song-img p-relative">
                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/brand-song-02.png') }}" alt="brand-song"></a>
                                    <span class="ms-song-num">02</span>
                                </div>
                                <div class="ms-song-content">
                                    <h3 class="ms-song-title"><a href="genres-details.html">Beastie Boys</a>
                                    </h3>
                                    <span class="ms-song-text">American hip-hop</span>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="ms-song-item">
                                <div class="ms-song-img p-relative">
                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/brand-song-03.png') }}" alt="brand-song"></a>
                                    <span class="ms-song-num">03</span>
                                </div>
                                <div class="ms-song-content">
                                    <h3 class="ms-song-title"><a href="genres-details.html">Blondie</a></h3>
                                    <span class="ms-song-text">American rock group</span>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="ms-song-item">
                                <div class="ms-song-img p-relative">
                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/brand-song-04.png') }}" alt="brand-song"></a>
                                    <span class="ms-song-num">04</span>
                                </div>
                                <div class="ms-song-content">
                                    <h3 class="ms-song-title"><a href="genres-details.html">Black
                                            Sabbath</a></h3>
                                    <span class="ms-song-text">British rock group</span>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="ms-song-item">
                                <div class="ms-song-img p-relative">
                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/brand-song-05.png') }}" alt="brand-song"></a>
                                    <span class="ms-song-num">05</span>
                                </div>
                                <div class="ms-song-content">
                                    <h3 class="ms-song-title"><a href="genres-details.html">Boy II Men</a>
                                    </h3>
                                    <span class="ms-song-text">Hong Kong Folk</span>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="ms-song-item">
                                <div class="ms-song-img p-relative">
                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/brand-song-06.png') }}" alt="brand-song"></a>
                                    <span class="ms-song-num">06</span>
                                </div>
                                <div class="ms-song-content">
                                    <h3 class="ms-song-title"><a href="genres-details.html">The Coasters</a>
                                    </h3>
                                    <span class="ms-song-text">Canada band group</span>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="ms-song-item">
                                <div class="ms-song-img p-relative">
                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/brand-song-07.png') }}" alt="brand-song"></a>
                                    <span class="ms-song-num">07</span>
                                </div>
                                <div class="ms-song-content">
                                    <h3 class="ms-song-title"><a href="genres-details.html">The
                                            Flamingos</a></h3>
                                    <span class="ms-song-text">Chicago rock group</span>
                                </div>
                            </div>
                        </div>
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
                                        <h2 class="ms-banner__title msg_title bd-title-anim">Exceptional
                                            Live Bands &amp;
                                            Musicians
                                            Hire For Your Event or Party</h2>
                                    </div>
                                    <div class="ms-banner__form bdFadeUp">
                                        <form action="#">
                                            <div class="ms-banner__from-inner white-bg">
                                                <div class="ms-banner__form-select ms-nice-select">
                                                    <select>
                                                        <option value="1" selected disabled>What are you
                                                            looking for?</option>
                                                        <option value="2">Singers</option>
                                                        <option value="3">Bands &amp; Group</option>
                                                        <option value="4">Tributes</option>
                                                        <option value="5">Solo Musicians</option>
                                                    </select>
                                                </div>
                                                <div class="ms-banner__form-select ms-nice-select">
                                                    <select>
                                                        <option value="1" selected disabled>When is your
                                                            event?</option>
                                                        <option value="2">This Week</option>
                                                        <option value="3">Next Week</option>
                                                        <option value="4">This Month</option>
                                                        <option value="4">Next Month</option>
                                                    </select>
                                                </div>
                                                <div class="ms-banner__form-select ms-nice-select ms-border-none">
                                                    <select>
                                                        <option value="1" selected disabled>Where is your
                                                            event?</option>
                                                        <option value="2">In Apartment</option>
                                                        <option value="3">In a Hall</option>
                                                        <option value="3">In a Resort</option>
                                                        <option value="3">In an Open Field</option>
                                                    </select>
                                                </div>
                                                <div class="banner__form-button">
                                                    <button class="input__btn"><i class="flaticon-loupe"></i>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-01.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-02.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-03.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-04.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-01.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-02.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-03.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-04.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-03.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-04.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-01.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-02.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-03.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-04.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-01.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-02.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-04.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-01.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-02.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-03.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-01.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-02.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-03.png') }}" alt="popular band"></a>
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
                                                    <a href="genres-details.html"><img src="{{ asset('frontend/images/popular-popular-04.png') }}" alt="popular band"></a>
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

        <!-- work area start -->
        <section class="work__area work-overlay pt-125 pb-60 include__bg" data-background="assets/img/bg/work-bg.jpg ">
            <img class="work__vactor-shape d-none d-xl-block" src="{{ asset('frontend/images/work-vactoe-shape.png') }}" alt="vactoe-shape.png">
            <div class="container">
                <div class="row align-items-center bdFadeUp">
                    <div class="col-xl-6">
                        <div class="work__thumb-wrapper d-inline-block p-relative mb-60">
                            <div class="work__thumb-inner">
                                <div class="work__thumb">
                                    <img src="{{ asset('frontend/images/work-work-thumb-01.png') }}" alt="work image">
                                </div>
                                <div class="work__small-thumb">
                                    <div class="work__thumb">
                                        <img src="{{ asset('frontend/images/work-work-thumb-02.png') }}" alt="work image">
                                    </div>
                                    <div class="work__thumb">
                                        <img src="{{ asset('frontend/images/work-work-thumb-03.png') }}" alt="work image">
                                    </div>
                                </div>
                            </div>
                            <div class="d-none d-sm-block">
                                <div class="work__thumb-card ">
                                    <div class="work__card-content">
                                        <span>Excellent :</span>
                                        <p>1050 Review On</p>
                                    </div>
                                    <div class="card__button">
                                        <a class="card__btn" href="#">
                                            <img src="{{ asset('frontend/images/work-star.png') }}" alt="work icon">
                                            <span>Trustpilot</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="work__content-wrapper work__content-space mb-70 pl-40">
                            <div class="section__title-wrapper mb-50 bd-title-anim">
                                <span class="section__subtitle">How it Works</span>
                                <h2 class="section__title two">
                                    Welcome to the UK's leading
                                    live music
                                    <span class="animated-underline active">booking agency</span>
                                </h2>
                            </div>
                            <div class="work__features-inner">
                                <div class="work__features-item">
                                    <div class="work__features-icon">
                                            <span>
                                                <svg width="46" height="27" viewbox="0 0 46 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_36_265)">
                                                        <mask id="mask0_36_265" style="mask-type:alpha" maskunits="userSpaceOnUse" x="2" y="-1" width="42" height="27">
                                                            <path d="M40.3981 0.0808105H5.59708C4.33871 0.0808105 3.3186 1.1096 3.3186 2.37868V23.0226C3.3186 24.2917 4.33871 25.3205 5.59708 25.3205H40.3981C41.6565 25.3205 42.6766 24.2917 42.6766 23.0226V2.37868C42.6766 1.1096 41.6565 0.0808105 40.3981 0.0808105Z" fill="#DFEFFF" stroke="#2690FF" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </mask>
                                                        <g mask="url(#mask0_36_265)">
                                                            <path d="M40.3981 0.0808105H5.59708C4.33871 0.0808105 3.3186 1.1096 3.3186 2.37868V23.0226C3.3186 24.2917 4.33871 25.3205 5.59708 25.3205H40.3981C41.6565 25.3205 42.6766 24.2917 42.6766 23.0226V2.37868C42.6766 1.1096 41.6565 0.0808105 40.3981 0.0808105Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </g>
                                                        <mask id="mask1_36_265" style="mask-type:alpha" maskunits="userSpaceOnUse" x="0" y="23" width="46" height="4">
                                                            <path d="M3.05673 25.8934C3.60661 26.3559 4.56615 26.7308 5.19913 26.7308H41.5731C42.2065 26.7308 43.1378 26.2914 43.6562 25.7464L45.0058 24.3275C45.523 23.7838 45.4265 23.343 44.7957 23.343H1.17102C0.537823 23.343 0.470957 23.7185 1.02017 24.1805L3.05673 25.8934Z" fill="#2690FF"></path>
                                                        </mask>
                                                        <g mask="url(#mask1_36_265)">
                                                            <path d="M3.05673 25.8934C3.60661 26.3559 4.56615 26.7308 5.19913 26.7308H41.5731C42.2065 26.7308 43.1378 26.2914 43.6562 25.7464L45.0058 24.3275C45.523 23.7838 45.4265 23.343 44.7957 23.343H1.17102C0.537823 23.343 0.470957 23.7185 1.02017 24.1805L3.05673 25.8934Z" fill="white"></path>
                                                        </g>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.6847 16.2461H16.1739L15.057 10.614H11.7063L10.9617 16.2461H12.4509V17.7478C12.4509 18.368 12.9509 18.8743 13.5678 18.8743C14.1889 18.8743 14.6847 18.37 14.6847 17.7478V16.2462V16.2461Z" fill="white" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21.7584 18.8743H20.2692L18.4077 10.6139H23.6199L21.7584 18.8743Z" fill="white" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M13.3817 9.48762C14.3069 9.48762 15.057 8.73116 15.057 7.79801C15.057 6.86486 14.3069 6.1084 13.3817 6.1084C12.4564 6.1084 11.7063 6.86486 11.7063 7.79801C11.7063 8.73116 12.4564 9.48762 13.3817 9.48762Z" fill="white" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M20.8277 9.48762C21.753 9.48762 22.5031 8.73116 22.5031 7.79801C22.5031 6.86486 21.753 6.1084 20.8277 6.1084C19.9024 6.1084 19.1523 6.86486 19.1523 7.79801C19.1523 8.73116 19.9024 9.48762 20.8277 9.48762Z" fill="white" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M27.9299 7.56274H34.8759" stroke="white" stroke-linecap="round"></path>
                                                        <path d="M27.9299 10.4714H34.8759" stroke="white" stroke-linecap="round"></path>
                                                        <path d="M27.9299 13.3801H34.8759" stroke="white" stroke-linecap="round"></path>
                                                        <path d="M27.9299 16.2888H32.5446" stroke="white" stroke-linecap="round"></path>
                                                    </g>
                                                    <defs>
                                                        <clippath id="clip0_36_265">
                                                            <rect width="45" height="27" fill="white" transform="translate(0.523438)"></rect>
                                                        </clippath>
                                                    </defs>
                                                </svg>
                                            </span>
                                    </div>
                                    <div class="work__features-content">
                                        <h4>Browse and compare.</h4>
                                        <p>Compare rates and availability of local entertainers and vendors.
                                        </p>
                                    </div>
                                </div>
                                <div class="work__features-item">
                                    <div class="work__features-icon">
                                            <span>
                                                <svg width="46" height="30" viewbox="0 0 46 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_36_354)">
                                                        <mask id="mask0_36_354" style="mask-type:alpha" maskunits="userSpaceOnUse" x="6" y="-1" width="41" height="27">
                                                            <path d="M9.26562 24.7666L43.3012 24.7666C44.5285 24.7666 45.5234 23.7717 45.5234 22.5444V2.25166C45.5234 1.02435 44.5285 0.0294323 43.3012 0.0294323L9.26562 0.0294323C8.03832 0.0294323 7.0434 1.02435 7.0434 2.25166V22.5444C7.0434 23.7717 8.03832 24.7666 9.26562 24.7666Z" fill="#DFEFFF" stroke="#2690FF" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </mask>
                                                        <g mask="url(#mask0_36_354)">
                                                            <path d="M9.26562 24.7666L43.3012 24.7666C44.5285 24.7666 45.5234 23.7717 45.5234 22.5444V2.25166C45.5234 1.02435 44.5285 0.0294323 43.3012 0.0294323L9.26562 0.0294323C8.03832 0.0294323 7.0434 1.02435 7.0434 2.25166V22.5444C7.0434 23.7717 8.03832 24.7666 9.26562 24.7666Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </g>
                                                        <path d="M40.7134 17.208L27.6577 17.208M24.9091 17.208L11.8533 17.208" stroke="white" stroke-linecap="square" stroke-linejoin="round"></path>
                                                        <path d="M31.5173 11.7109L39.6023 11.7109C40.2159 11.7109 40.7134 11.2135 40.7134 10.5998V5.95061C40.7134 5.33696 40.2159 4.8395 39.6023 4.8395L31.5173 4.8395C30.9037 4.8395 30.4062 5.33696 30.4062 5.95061V10.5998C30.4062 11.2135 30.9037 11.7109 31.5173 11.7109Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M34.5291 11.7109V4.8395M40.7134 8.96236H35.2162M34.5291 7.58808H30.4062" stroke="white" stroke-linecap="square" stroke-linejoin="round"></path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.26636 20.3433H12.9603V28.4391H1.26636V20.3433Z" fill="white" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M12.0606 19.8935C12.0606 17.1611 9.8456 14.946 7.11321 14.946C4.38081 14.946 2.16577 17.1611 2.16577 19.8935" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </g>
                                                    <defs>
                                                        <clippath id="clip0_36_354">
                                                            <rect width="45" height="29.4444" fill="white" transform="translate(0.523438)"></rect>
                                                        </clippath>
                                                    </defs>
                                                </svg>
                                            </span>
                                    </div>
                                    <div class="work__features-content">
                                        <h4>Book securely.</h4>
                                        <p>Booking through GigSalad ensures payment protection, amazing
                                            service.</p>
                                    </div>
                                </div>
                                <div class="work__features-item">
                                    <div class="work__features-icon">
                                            <span>
                                                <svg width="46" height="42" viewbox="0 0 46 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_36_396)">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M36.5665 12.3724C38.1067 12.677 39.8538 12.4088 41.4313 11.4975C44.5453 9.70015 45.8206 6.07889 44.2793 3.4094C42.7388 0.740078 38.965 0.0338131 35.8508 1.83184C34.2735 2.7425 33.1675 4.12203 32.6608 5.60739L36.5665 12.3724Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <mask id="mask0_36_396" style="mask-type:alpha" maskunits="userSpaceOnUse" x="38" y="2" width="4" height="4">
                                                            <path d="M38.9634 2.95068L40.5035 3.36336L40.0908 4.90348L38.5507 4.4908L38.9634 2.95068Z" stroke="#2690FF" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </mask>
                                                        <g mask="url(#mask0_36_396)">
                                                            <path d="M39.8188 2.83813L40.6161 4.21897L39.2352 5.01619L38.438 3.63536L39.8188 2.83813Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </g>
                                                        <mask id="mask1_36_396" style="mask-type:alpha" maskunits="userSpaceOnUse" x="39" y="5" width="4" height="4">
                                                            <path d="M40.5579 5.7124L42.098 6.12508L41.6853 7.6652L40.1452 7.25252L40.5579 5.7124Z" stroke="#2690FF" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </mask>
                                                        <g mask="url(#mask1_36_396)">
                                                            <path d="M41.4133 5.59985L42.2106 6.98069L40.8297 7.77791L40.0325 6.39708L41.4133 5.59985Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </g>
                                                        <mask id="mask2_36_396" style="mask-type:alpha" maskunits="userSpaceOnUse" x="36" y="5" width="4" height="4">
                                                            <path d="M36.999 5.92603L38.5391 6.3387L38.1265 7.87882L36.5863 7.46614L36.999 5.92603Z" stroke="#2690FF" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </mask>
                                                        <g mask="url(#mask2_36_396)">
                                                            <path d="M37.8545 5.81348L38.6517 7.19431L37.2709 7.99153L36.4737 6.6107L37.8545 5.81348Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </g>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M33.0191 6.22821L36.2079 11.7513L33.4466 13.3461L30.2571 7.82284L33.0191 6.22821Z" fill="white" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M30.2575 7.82262L33.4462 13.3457L15.0969 23.0193L12.7052 18.8768L30.2575 7.82262Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.1038 19.5673L14.6985 22.3293L11.9367 23.9233L10.3419 21.1619L13.1038 19.5673Z" fill="white" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M11.1395 22.5428L4.23499 26.5292" stroke="white" stroke-linecap="square" stroke-linejoin="round"></path>
                                                        <path d="M3.54494 26.9275C1.63803 28.0284 0.984887 30.466 2.08584 32.3729C3.18697 34.2791 5.62455 34.9323 7.53127 33.832M11.5172 40.7358C13.4241 39.6348 14.0772 37.1973 12.9763 35.2904C11.8751 33.3841 9.43756 32.731 7.53084 33.8313" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M29.0258 35.4579C30.7317 35.4579 32.115 34.5359 32.115 33.3984C32.115 32.2608 30.7317 31.3389 29.0258 31.3389C27.3198 31.3389 25.9365 32.2608 25.9365 33.3984C25.9365 34.5359 27.3198 35.4579 29.0258 35.4579Z" fill="white"></path>
                                                        <path d="M31.4285 32.7118V23.1736" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M37.9503 38.204C39.6563 38.204 41.0396 37.282 41.0396 36.1445C41.0396 35.0069 39.6563 34.085 37.9503 34.085C36.2444 34.085 34.8611 35.0069 34.8611 36.1445C34.8611 37.282 36.2444 38.204 37.9503 38.204Z" fill="white"></path>
                                                        <path d="M40.3529 34.7713V25.4452L31.4285 23.1008" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </g>
                                                    <defs>
                                                        <clippath id="clip0_36_396">
                                                            <rect width="45" height="41.4474" fill="white" transform="translate(0.523438)"></rect>
                                                        </clippath>
                                                    </defs>
                                                </svg>
                                            </span>
                                    </div>
                                    <div class="work__features-content">
                                        <h4>Enjoy your event.</h4>
                                        <p>Sit back, relax, and watch your party come to life.</p>
                                    </div>
                                </div>
                                <div class="work__features-bottom">
                                    <div class="work__features-action">
                                        <div class="work__features-btn">
                                            <a class="unfill__btn" href="contact.html">Get Started</a>
                                        </div>
                                        <div class="features__btn-text">
                                            <span>Book something <br>awesome !</span>
                                        </div>
                                    </div>
                                    <div class="work__features-arrow">
                                        <img src="{{ asset('frontend/images/work-arrow.png') }}" alt="image not found">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- work area end -->

        <!-- Trending area start -->
        <section class="trending__area p-relative z-index-11 x-clip pt-130 pb-130">
            <span class="trending-round-bg-1"></span>
            <span class="trending-round-bg-2"></span>
            <div class="container">
                <div class="row align-items-end mb-25 bdFadeUp">
                    <div class="col-lg-8">
                        <div class="section__title-wrapper mb-40 bd-title-anim">
                            <span class="section__subtitle">Trending Genres</span>
                            <div id="msg-title">
                                <h2 class="section__title"><span class="animated-underline active">Most
                                            Trending</span>
                                    genres</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="trending-btn mb-40 d-flex justify-content-lg-end">
                            <a class="border__btn" href="genres.html">View All Genres</a>
                        </div>
                    </div>
                </div>
                <div class="row bdFadeUp">
                    <div class="trending-grid">
                        <div class="trending-item">
                            <div class="trending__thumb" data-background="assets/img/trending/01.jpg"></div>
                            <div class="trending__info">
                                <div class="trending__number">
                                    <span>01</span>
                                </div>
                                <div class="trending__arrow">
                                    <a href="assets/img/trending/01.jpg" class="popup-image"><i class="fa-regular fa-arrow-right-long"></i></a>
                                </div>
                                <div class="trending__price">
                                    <span>From $99</span>
                                </div>
                                <span class="trending__title">Musicly</span>
                                <div class="trending__content">
                                    <h4><a href="genres-details.html">Party bands for hire</a></h4>
                                    <p>Party Bands For Hire a Live Music Agency offering an unknown
                                        printer took
                                        a galley
                                        wtnd
                                        scrambled.</p>
                                </div>
                            </div>
                        </div>
                        <div class="trending-item">
                            <div class="trending__thumb" data-background="assets/img/trending/02.jpg"></div>
                            <div class="trending__info">
                                <div class="trending__number">
                                    <span>02</span>
                                </div>
                                <div class="trending__arrow">
                                    <a href="assets/img/trending/02.jpg" class="popup-image"><i class="fa-regular fa-arrow-right-long"></i></a>
                                </div>
                                <div class="trending__price">
                                    <span>From $99</span>
                                </div>
                                <span class="trending__title">Musicly</span>
                                <div class="trending__content">
                                    <h4><a href="genres-details.html">Party bands for hire</a></h4>
                                    <p>Party Bands For Hire a Live Music Agency offering an unknown
                                        printer took
                                        a galley
                                        wtnd
                                        scrambled.</p>
                                </div>
                            </div>
                        </div>
                        <div class="trending-item">
                            <div class="trending__thumb" data-background="assets/img/trending/03.jpg"></div>
                            <div class="trending__info">
                                <div class="trending__number">
                                    <span>03</span>
                                </div>
                                <div class="trending__arrow">
                                    <a href="assets/img/trending/03.jpg" class="popup-image"><i class="fa-regular fa-arrow-right-long"></i></a>
                                </div>
                                <div class="trending__price">
                                    <span>From $99</span>
                                </div>
                                <span class="trending__title">Musicly</span>
                                <div class="trending__content">
                                    <h4><a href="genres-details.html">Party bands for hire</a></h4>
                                    <p>Party Bands For Hire a Live Music Agency offering an unknown
                                        printer took
                                        a galley
                                        wtnd
                                        scrambled.</p>
                                </div>
                            </div>
                        </div>
                        <div class="trending-item">
                            <div class="trending__thumb" data-background="assets/img/trending/04.jpg"></div>
                            <div class="trending__info">
                                <div class="trending__number">
                                    <span>04</span>
                                </div>
                                <div class="trending__arrow">
                                    <a href="assets/img/trending/04.jpg" class="popup-image"><i class="fa-regular fa-arrow-right-long"></i></a>
                                </div>
                                <div class="trending__price">
                                    <span>From $99</span>
                                </div>
                                <span class="trending__title">Musicly</span>
                                <div class="trending__content">
                                    <h4><a href="genres-details.html">Party bands for hire</a></h4>
                                    <p>Party Bands For Hire a Live Music Agency offering an unknown
                                        printer took
                                        a galley
                                        wtnd
                                        scrambled.</p>
                                </div>
                            </div>
                        </div>
                        <div class="trending-item">
                            <div class="trending__thumb" data-background="assets/img/trending/05.jpg"></div>
                            <div class="trending__info">
                                <div class="trending__number">
                                    <span>05</span>
                                </div>
                                <div class="trending__arrow">
                                    <a href="assets/img/trending/04.jpg" class="popup-image"><i class="fa-regular fa-arrow-right-long"></i></a>
                                </div>
                                <div class="trending__price">
                                    <span>From $99</span>
                                </div>
                                <span class="trending__title">Musicly</span>
                                <div class="trending__content">
                                    <h4><a href="genres-details.html">Party bands for hire</a></h4>
                                    <p>Party Bands For Hire a Live Music Agency offering an unknown
                                        printer took
                                        a galley
                                        wtnd
                                        scrambled.</p>
                                </div>
                            </div>
                        </div>
                        <div class="trending-item">
                            <div class="trending__thumb" data-background="assets/img/trending/06.jpg"></div>
                            <div class="trending__info">
                                <div class="trending__number">
                                    <span>06</span>
                                </div>
                                <div class="trending__arrow">
                                    <a href="assets/img/trending/06.jpg" class="popup-image"><i class="fa-regular fa-arrow-right-long"></i></a>
                                </div>
                                <div class="trending__price">
                                    <span>From $99</span>
                                </div>
                                <span class="trending__title">Musicly</span>
                                <div class="trending__content">
                                    <h4><a href="genres-details.html">Party bands for hire</a></h4>
                                    <p>Party Bands For Hire a Live Music Agency offering an unknown
                                        printer took
                                        a galley
                                        wtnd
                                        scrambled.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Trending area end -->

        <!-- Function Brand Area Start Here  -->
        <section class="ms-fun-brand ms-bg-2 pb-130 pt-125">
            <div class="container">
                <div class="row justify-content-center bdFadeUp">
                    <div class="col-xl-7">
                        <div class="section__title-wrapper mb-65 text-center bd-title-anim">
                            <span class="section__subtitle">Function Bands</span>
                            <h2 class="section__title">
                                our <span class="animated-underline active">most popular function</span>
                                bands
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="ms-fun-brand-wrap bdFadeUp">
                    <div class="ms-fun-brand-item ms-fun-border">
                        <div class="ms-fun-brand-top mb-20">
                            <div class="ms-fun-brand-thumb">
                                <a href="genres-details.html"><img src="{{ asset('frontend/images/function-brand-function-brand-01.png') }}" alt="function brand"></a>
                            </div>
                            <div class="ms-fun-brand-content">
                                <h3 class="ms-fun-brand-title"><a href="genres-details.html">Beastie
                                        Boys</a></h3>
                                <span class="ms-fun-brand-subtitle">American hip-hop</span>
                            </div>
                        </div>
                        <div class="ms-fun-brand-bottom">
                            <div class="ms-fun-brand-location">
                                <a href="https://www.google.com/maps" target="_blank"> <i class="flaticon-pin"></i>Ohio</a>
                            </div>
                            <div class="ms-fun-brand-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="ms-fun-brand-item ms-fun-border">
                        <div class="ms-fun-brand-top mb-20">
                            <div class="ms-fun-brand-thumb">
                                <a href="genres-details.html"><img src="{{ asset('frontend/images/function-brand-function-brand-02.png') }}" alt="function brand"></a>
                            </div>
                            <div class="ms-fun-brand-content">
                                <h3 class="ms-fun-brand-title"><a href="genres-details.html">Blondie</a>
                                </h3>
                                <span class="ms-fun-brand-subtitle">American rock group</span>
                            </div>
                        </div>
                        <div class="ms-fun-brand-bottom">
                            <div class="ms-fun-brand-location">
                                <a href="https://www.google.com/maps" target="_blank"> <i class="flaticon-pin"></i>Chicago</a>
                            </div>
                            <div class="ms-fun-brand-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star unrate"></i>
                            </div>
                        </div>
                    </div>
                    <div class="ms-fun-brand-item ms-fun-border">
                        <div class="ms-fun-brand-top mb-20">
                            <div class="ms-fun-brand-thumb">
                                <a href="genres-details.html"><img src="{{ asset('frontend/images/function-brand-function-brand-03.png') }}" alt="function brand"></a>
                            </div>
                            <div class="ms-fun-brand-content">
                                <h3 class="ms-fun-brand-title"><a href="genres-details.html">Black
                                        Sabbath</a></h3>
                                <span class="ms-fun-brand-subtitle">British rock group</span>
                            </div>
                        </div>
                        <div class="ms-fun-brand-bottom">
                            <div class="ms-fun-brand-location">
                                <a href="https://www.google.com/maps" target="_blank"> <i class="flaticon-pin"></i>London</a>
                            </div>
                            <div class="ms-fun-brand-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="ms-fun-brand-item ms-fun-border">
                        <div class="ms-fun-brand-top mb-20">
                            <div class="ms-fun-brand-thumb">
                                <a href="genres-details.html"><img src="{{ asset('frontend/images/function-brand-function-brand-04.png') }}" alt="function brand"></a>
                            </div>
                            <div class="ms-fun-brand-content">
                                <h3 class="ms-fun-brand-title"><a href="genres-details.html">The
                                        Coasters</a></h3>
                                <span class="ms-fun-brand-subtitle">Canada band group</span>
                            </div>
                        </div>
                        <div class="ms-fun-brand-bottom">
                            <div class="ms-fun-brand-location">
                                <a href="https://www.google.com/maps" target="_blank"> <i class="flaticon-pin"></i>London</a>
                            </div>
                            <div class="ms-fun-brand-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="ms-fun-brand-item ms-fun-border">
                        <div class="ms-fun-brand-top mb-20">
                            <div class="ms-fun-brand-thumb">
                                <a href="genres-details.html"><img src="{{ asset('frontend/images/function-brand-function-brand-05.png') }}" alt="function brand"></a>
                            </div>
                            <div class="ms-fun-brand-content">
                                <h3 class="ms-fun-brand-title"><a href="genres-details.html">Beastie
                                        Boys</a></h3>
                                <span class="ms-fun-brand-subtitle">American hip-hop</span>
                            </div>
                        </div>
                        <div class="ms-fun-brand-bottom">
                            <div class="ms-fun-brand-location">
                                <a href="https://www.google.com/maps" target="_blank"> <i class="flaticon-pin"></i>Georgia</a>
                            </div>
                            <div class="ms-fun-brand-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star unrate"></i>
                            </div>
                        </div>
                    </div>
                    <div class="ms-fun-brand-item ms-fun-border">
                        <div class="ms-fun-brand-top mb-20">
                            <div class="ms-fun-brand-thumb">
                                <a href="genres-details.html"><img src="{{ asset('frontend/images/function-brand-function-brand-06.png') }}" alt="function brand"></a>
                            </div>
                            <div class="ms-fun-brand-content">
                                <h3 class="ms-fun-brand-title"><a href="genres-details.html">Blondie</a>
                                </h3>
                                <span class="ms-fun-brand-subtitle">American rock group</span>
                            </div>
                        </div>
                        <div class="ms-fun-brand-bottom">
                            <div class="ms-fun-brand-location">
                                <a href="https://www.google.com/maps" target="_blank"> <i class="flaticon-pin"></i>City
                                    Club</a>
                            </div>
                            <div class="ms-fun-brand-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="ms-fun-brand-item ms-fun-border">
                        <div class="ms-fun-brand-top mb-20">
                            <div class="ms-fun-brand-thumb">
                                <a href="genres-details.html"><img src="{{ asset('frontend/images/function-brand-function-brand-07.png') }}" alt="function brand"></a>
                            </div>
                            <div class="ms-fun-brand-content">
                                <h3 class="ms-fun-brand-title"><a href="genres-details.html">Black
                                        Sabbath</a></h3>
                                <span class="ms-fun-brand-subtitle">British rock group</span>
                            </div>
                        </div>
                        <div class="ms-fun-brand-bottom">
                            <div class="ms-fun-brand-location">
                                <a href="https://www.google.com/maps" target="_blank"> <i class="flaticon-pin"></i>Kenia</a>
                            </div>
                            <div class="ms-fun-brand-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="ms-fun-brand-item ms-fun-border">
                        <div class="ms-fun-brand-top mb-20">
                            <div class="ms-fun-brand-thumb">
                                <a href="genres-details.html"><img src="{{ asset('frontend/images/function-brand-function-brand-08.png') }}" alt="function brand"></a>
                            </div>
                            <div class="ms-fun-brand-content">
                                <h3 class="ms-fun-brand-title"><a href="genres-details.html">The
                                        Coasters</a></h3>
                                <span class="ms-fun-brand-subtitle">Canada band group</span>
                            </div>
                        </div>
                        <div class="ms-fun-brand-bottom">
                            <div class="ms-fun-brand-location">
                                <a href="https://www.google.com/maps" target="_blank"> <i class="flaticon-pin"></i>Pakistan</a>
                            </div>
                            <div class="ms-fun-brand-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star unrate"></i>
                            </div>
                        </div>
                    </div>
                    <div class="ms-fun-brand-item ms-fun-border">
                        <div class="ms-fun-brand-top mb-20">
                            <div class="ms-fun-brand-thumb">
                                <a href="genres-details.html"><img src="{{ asset('frontend/images/function-brand-function-brand-05.png') }}" alt="function brand"></a>
                            </div>
                            <div class="ms-fun-brand-content">
                                <h3 class="ms-fun-brand-title"><a href="genres-details.html">Blondie</a>
                                </h3>
                                <span class="ms-fun-brand-subtitle">American rock group</span>
                            </div>
                        </div>
                        <div class="ms-fun-brand-bottom">
                            <div class="ms-fun-brand-location">
                                <a href="https://www.google.com/maps" target="_blank"> <i class="flaticon-pin"></i>Chicago</a>
                            </div>
                            <div class="ms-fun-brand-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star unrate"></i>
                            </div>
                        </div>
                    </div>
                    <div class="ms-fun-brand-item ms-fun-border">
                        <div class="ms-fun-brand-top mb-20">
                            <div class="ms-fun-brand-thumb">
                                <a href="genres-details.html"><img src="{{ asset('frontend/images/function-brand-function-brand-01.png') }}" alt="function brand"></a>
                            </div>
                            <div class="ms-fun-brand-content">
                                <h3 class="ms-fun-brand-title"><a href="genres-details.html">Beastie
                                        Boys</a></h3>
                                <span class="ms-fun-brand-subtitle">American hip-hop</span>
                            </div>
                        </div>
                        <div class="ms-fun-brand-bottom">
                            <div class="ms-fun-brand-location">
                                <a href="https://www.google.com/maps" target="_blank"> <i class="flaticon-pin"></i>Ohio</a>
                            </div>
                            <div class="ms-fun-brand-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="ms-fun-brand-item ms-fun-border">
                        <div class="ms-fun-brand-top mb-20">
                            <div class="ms-fun-brand-thumb">
                                <a href="genres-details.html"><img src="{{ asset('frontend/images/function-brand-function-brand-05.png') }}" alt="function brand"></a>
                            </div>
                            <div class="ms-fun-brand-content">
                                <h3 class="ms-fun-brand-title"><a href="genres-details.html">Beastie
                                        Boys</a></h3>
                                <span class="ms-fun-brand-subtitle">American hip-hop</span>
                            </div>
                        </div>
                        <div class="ms-fun-brand-bottom">
                            <div class="ms-fun-brand-location">
                                <a href="https://www.google.com/maps" target="_blank"> <i class="flaticon-pin"></i>Georgia</a>
                            </div>
                            <div class="ms-fun-brand-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star unrate"></i>
                            </div>
                        </div>
                    </div>
                    <div class="ms-fun-brand-item ms-fun-border">
                        <div class="ms-fun-brand-top mb-20">
                            <div class="ms-fun-brand-thumb">
                                <a href="genres-details.html"><img src="{{ asset('frontend/images/function-brand-function-brand-06.png') }}" alt="function brand"></a>
                            </div>
                            <div class="ms-fun-brand-content">
                                <h3 class="ms-fun-brand-title"><a href="genres-details.html">Blondie</a>
                                </h3>
                                <span class="ms-fun-brand-subtitle">American rock group</span>
                            </div>
                        </div>
                        <div class="ms-fun-brand-bottom">
                            <div class="ms-fun-brand-location">
                                <a href="https://www.google.com/maps" target="_blank"> <i class="flaticon-pin"></i>City
                                    Club</a>
                            </div>
                            <div class="ms-fun-brand-rating">
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                                <i class="flaticon-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Function Brand Area End Here  -->

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
                            <img src="{{ asset('frontend/images/event-event-bg-2.png') }}" alt="event img">
                            <a href="https://www.youtube.com/watch?v=Rf9flQISwok" class="ms-play-border ms-event-play-btn popup-video">play</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Special Events Area End -->

        <!-- Testimonial Area Start Here  -->
        <section class="ms-tm-area">
            <div class="container">
                <div class="ms-tm-border pt-130 pb-70">
                    <div class="row align-items-center bdFadeUp">
                        <div class="col-xl-5">
                            <div class="ms-tm-img-wrap ms-tm-space p-relative mb-60">
                                <div class="ms-tm-img-main p-relative m-img">
                                    <div class="ms-tm-bg-shape"></div>
                                    <div class="ms-tm-signature">
                                        <img src="{{ asset('frontend/images/testimonial-testimonial-signature.png') }}" alt="testimonial signature">
                                    </div>
                                    <img src="{{ asset('frontend/images/testimonial-testimonial-05.png') }}" alt="testimonial image">
                                </div>
                                <div class="ms-tm-img1 p-absolute w-img">
                                    <img src="{{ asset('frontend/images/testimonial-testimonial-01.jpg') }}" alt="testimonial image">
                                </div>
                                <div class="ms-tm-img2 p-absolute w-img">
                                    <img src="{{ asset('frontend/images/testimonial-testimonial-02.jpg') }}" alt="testimonial image">
                                </div>
                                <div class="ms-tm-img3 p-absolute w-img d-none d-sm-block">
                                    <img src="{{ asset('frontend/images/testimonial-testimonial-03.jpg') }}" alt="testimonial image">
                                </div>
                                <div class="ms-tm-img4 p-absolute w-img">
                                    <img src="{{ asset('frontend/images/testimonial-testimonial-04.jpg') }}" alt="testimonial image">
                                </div>
                                <div class="ms-tm-img5 p-absolute w-img">
                                    <img src="{{ asset('frontend/images/testimonial-testimonial-02.jpg') }}" alt="testimonial image">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-7">
                            <div class="ms-tm-content-wrap ms-tm-content-space mb-60">
                                <div class="ms-tm-quotation text-end">
                                    <i class="flaticon-quotation"></i>
                                </div>
                                <div class="section__title-wrapper mb-30 bd-title-anim">
                                    <span class="section__subtitle">Clients Feedback</span>
                                    <h2 class="section__title"><span class="animated-underline active">Public
                                                Awesome</span>
                                        Comments
                                    </h2>
                                </div>
                                <div class="ms-tm-content">
                                    <div class="ms-tm-active">
                                        <div class="ms-tm-slick">
                                            <div class="ms-tm-slick-item">
                                                <p>assumenda fugiat ut quibusdam aliquid qui molestiae
                                                    itaque est atque
                                                    iste
                                                    ea
                                                    ipsum
                                                    adipisci ut rerum voluptas ex autem
                                                    aliquid. Ut voluptatem voluptate et distinctio fuga vel
                                                    dicta magni.</p>
                                                <div class="ms-tm-author">
                                                    <h4 class="ms-tm-author-title">David Camerun</h4>
                                                    <span class="ms-tm-author-subtitle">Media Public
                                                            Manager</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ms-tm-slick">
                                            <div class="ms-tm-slick-item">
                                                <p>assumenda fugiat ut quibusdam aliquid qui molestiae
                                                    itaque est atque
                                                    iste
                                                    ea
                                                    ipsum
                                                    adipisci ut rerum voluptas ex autem
                                                    aliquid. Ut voluptatem voluptate et distinctio fuga vel
                                                    dicta magni.</p>
                                                <div class="ms-tm-author">
                                                    <h4 class="ms-tm-author-title">David Camerun</h4>
                                                    <span class="ms-tm-author-subtitle">Media Public
                                                            Manager</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ms-tm-slick">
                                            <div class="ms-tm-slick-item">
                                                <p>assumenda fugiat ut quibusdam aliquid qui molestiae
                                                    itaque est atque
                                                    iste
                                                    ea
                                                    ipsum
                                                    adipisci ut rerum voluptas ex autem
                                                    aliquid. Ut voluptatem voluptate et distinctio fuga vel
                                                    dicta magni.</p>
                                                <div class="ms-tm-author">
                                                    <h4 class="ms-tm-author-title">David Camerun</h4>
                                                    <span class="ms-tm-author-subtitle">Media Public
                                                            Manager</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ms-tm-dots ms-tm-dots-horizontal ms-round-dots d-flex justify-content-lg-end mt-lg-0 mt-30">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Testimonial Area End Here  -->

        <!-- Special Events Area Start -->
        <section class="ms-news-area pt-130 pb-90">
            <div class="container">
                <div class="row align-items-end mb-25 bdFadeUp">
                    <div class="col-lg-6">
                        <div class="section__title-wrapper mb-40 bd-title-anim">
                            <span class="section__subtitle">Latest News</span>
                            <h2 class="section__title">Morning <span class="animated-underline active">Insight
                                        Musicly</span>
                            </h2>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="ms-news-btn d-flex justify-content-lg-end mb-40">
                            <a class="border__btn" href="event.html">View All Event</a>
                        </div>
                    </div>
                </div>
                <div class="row bdFadeUp">
                    <div class="col-xl-4 col-md-6">
                        <div class="ms-news-item p-relative zindex-1 mb-40">
                            <div class="ms-news-overlay p-absolute"></div>
                            <a class="ms-news4-cat" href="news-details.html">Event</a>
                            <div class="ms-news-thumb w-img">
                                <a href="news-details.html"><img src="{{ asset('frontend/images/news-news-01.png') }}" alt="news image"></a>
                            </div>
                            <div class="ms-news-content ms-news-position p-absolute">
                                <h3 class="ms-news-title mb-15"><a href="news-details.html">What's more, our
                                        live music
                                        To
                                        The
                                        guaran
                                        tee means that</a></h3>
                                <p class="ms-news-text mb-25">Our hand-picked acts will guarantee you
                                    fantastic wedding
                                    entertainment
                                    for each part. We'll provide help.</p>
                                <div class="ms-news-meta d-inline-block">
                                    <span>Feb 15, 2023</span>
                                    <span><a href="news-details.html">0 Comments</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="ms-news-item p-relative zindex-1 mb-40">
                            <div class="ms-news-overlay p-absolute"></div>
                            <a class="ms-news4-cat" href="news-details.html">Wedding Party</a>
                            <div class="ms-news-thumb w-img">
                                <a href="news-details.html"><img src="{{ asset('frontend/images/news-news-02.png') }}" alt="news image"></a>
                            </div>
                            <div class="ms-news-content ms-news-position p-absolute">
                                <h3 class="ms-news-title mb-15"><a href="news-details.html">The first dance
                                        as a
                                        married
                                        couple has become</a></h3>
                                <p class="ms-news-text mb-25">Our hand-picked acts will guarantee you
                                    fantastic wedding
                                    entertainment
                                    for each part. We'll provide help.</p>
                                <div class="ms-news-meta d-inline-block">
                                    <span>Feb 20, 2023</span>
                                    <span><a href="news-details.html">10 Comments</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="ms-news-item p-relative zindex-1 mb-40">
                            <div class="ms-news-overlay p-absolute"></div>
                            <a class="ms-news4-cat" href="news-details.html">DJ Party Event</a>
                            <div class="ms-news-thumb w-img">
                                <a href="news-details.html"><img src="{{ asset('frontend/images/news-news-03.png') }}" alt="news image"></a>
                            </div>
                            <div class="ms-news-content ms-news-position p-absolute">
                                <h3 class="ms-news-title mb-15"><a href="news-details.html">Our exclusive
                                        range of live
                                        bands
                                        for hire have experience</a></h3>
                                <p class="ms-news-text mb-25">Our hand-picked acts will guarantee you
                                    fantastic wedding
                                    entertainment
                                    for each part. We'll provide help.</p>
                                <div class="ms-news-meta d-inline-block">
                                    <span>Feb 23, 2023</span>
                                    <span><a href="news-details.html">14 Comments</a></span>
                                </div>
                            </div>
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
                            <img src="{{ asset('frontend/images/partner-partner-01.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/images/partner-partner-02.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/images/partner-partner-03.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/images/partner-partner-04.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/images/partner-partner-05.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/images/partner-partner-04.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/images/partner-partner-01.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/images/partner-partner-02.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/images/partner-partner-03.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/images/partner-partner-04.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/images/partner-partner-05.png') }}" alt="partner image">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/images/partner-partner-04.png') }}" alt="partner image">
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
                                <img src="{{ asset('frontend/images/cta-cta-01.png') }}" alt="cta image">
                            </div>
                        </div>
                        <div class="ms-cta-item">
                            <div class="ms-cta-app">
                                <a target="_blank" href="#"><img src="{{ asset('frontend/images/cta-app-store.png') }}" alt="app store"></a>
                                <a target="_blank" href="#"><img src="{{ asset('frontend/images/cta-play-store.png') }}" alt="play store"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- CTA Area End Here  -->

    </main>
@endsection

<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}" />
    <title>{{$general_setting->site_title}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS here -->
    <link rel="stylesheet" href="{{ asset('public/frontend/css/css-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/frontend/css/css-meanmenu.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/frontend/css/css-nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('public/frontend/css/css-swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/frontend/css/css-slick.css') }}">
    <link rel="stylesheet" href="{{ asset('public/frontend/css/css-jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/frontend/css/css-backtotop.css') }}">
    <link rel="stylesheet" href="{{ asset('public/frontend/css/css-magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('public/frontend/css/css-flaticon_musicly.css') }}">
    <link rel="stylesheet" href="{{ asset('public/frontend/css/css-fontawesome-pro.css') }}">
    <link rel="stylesheet" href="{{ asset('public/frontend/css/css-spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('public/frontend/css/css-main.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>


<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
<![endif]-->

<div class="mouseCursor cursor-outer"></div>
<div class="mouseCursor cursor-inner"><span>Drag</span></div>

<!-- Preloader start -->
<div id="preloader">
    <div class="line-loader">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
</div>
<!-- preloader end -->

<!-- Offcanvas area start -->
<div class="fix">
    <div class="offcanvas__info">
        <div class="offcanvas__wrapper">
            <div class="offcanvas__content">
                <div class="offcanvas__top mb-40 d-flex justify-content-between align-items-center">
                    <div class="offcanvas__logo">
                        <a href="{{ route('home') }}">
                            <img src="{{url('public/logo', $general_setting->site_logo)}}" alt="logo">
                        </a>
                    </div>
                    <div class="offcanvas__close">
                        <button>
                            <i class="fal fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="offcanvas__user mb-30 d-xxl-none">
                    <div class="user__acount">
                            <span>
                                <a href="javascript:void(0)"><i class="flaticon-user"></i></a>
                            </span>
                        <div class="user__name-mail">
                            <h4 class="user__name"><a href="javascript:void(0)">Johnson</a></h4>
                            <p class="user__mail"><a href="email-protection.html" class="__cf_email__" data-cfemail="63090c0b0d100c0d231406010e020a0f4d000c0e">[email&nbsp;protected]</a></p>
                        </div>
                    </div>
                </div>
                <div class="offcanvas__search mb-30">
                    <form action="#">
                        <input type="text" placeholder="Search Here">
                        <button type="submit"><i class="far fa-search"></i></button>
                    </form>
                </div>
                <div class="hr-1 mt-30 mb-30 d-xl-none"></div>
                <div class="mobile-menu fix mb-30  d-xl-none"></div>
                <div class="hr-1 mt-30 mb-30 d-xl-none"></div>
                <div class="offcanvas__btn mb-30">
                    <a class="ms-border-btn" href="services.html"><i class="fa-solid fa-plus"></i> Vote your favourite candidate</a>
                </div>
                <div class="offcanvas__social">
                    <div class="ms-footer-social mb-0">
                        <a href="https://www.linkedin.com/" title="Instagram" target="_blank">IN</a>
                        <a href="https://twitter.com/" title="Twitter" target="_blank">TW</a>
                        <a href="https://www.facebook.com/" title="Facebook" target="_blank">FB</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="offcanvas__overlay"></div>
<div class="offcanvas__overlay-white"></div>
<!-- Offcanvas area start -->

<!-- Header area start -->
<header>
    <div id="header-sticky" class="header__area header-1 ms-header-fixed ms-bg-body">
        <div class="container-fluid ms-maw-1710">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="mega__menu-wrapper p-relative">
                        <div class="header__main ms-border2 d-flex align-items-center justify-content-between">
                            <div class="header__logo pt-25 pb-25">
                                <a href="{{ route('home') }}">
                                    <img src="{{url('public/logo', $general_setting->site_logo)}}" alt="logo not found">
                                </a>
                            </div>
                            <div class="header__right">
                                <div class="mean__menu-wrapper">
                                    <div class="main-menu main-menu-ff-space">
                                        <nav id="mobile-menu">
                                            <ul>
                                                <li>
                                                    <a href="{{ route('home') }}">Home</a>
                                                </li>
                                            </ul>
                                        </nav>
                                        <!-- for wp -->
                                        <div class="header__hamburger ml-50 d-none">
                                            <button type="button" class="hamburger-btn offcanvas-open-btn">
                                                <span>01</span>
                                                <span>01</span>
                                                <span>01</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $user = Auth::user() ?? null;
                                    if($user) {
                                        $contestents = \App\vote::select('musician_id')->where('user_id', $user->id)->where('status', true)->groupBy('musician_id')->get()->toArray();
                                    }
                                @endphp
                                <div class="header__action-inner d-flex align-items-center">
                                    @if($user)
                                        <div class="enquiry__list ml-10 mr-10 ms-browse-act-wrap p-relative">
                                            <div class="ms-enquiry-box p-relative d-none d-xl-inline-flex">
                                                <a href="#"><i class="flaticon-star icon"></i>
                                                    <span class="text">My contenstents</span> <span class="number">{{ count($contestents) }}</span></a>
                                            </div>
                                            <div class="ms-browse-act-item-wrap p-absolute">
                                                @foreach($contestents as $contestent)
                                                    @php $musician = \App\Employee::find($contestent['musician_id']); @endphp
                                                <div class="ms-song-item">
                                                    <div class="ms-song-img p-relative">
                                                        <a href="{{ route('musician.data', $musician->id) }}"><img src="{{url('public/images/employee',$musician->image)}}" alt="brand-song"></a>
                                                    </div>
                                                    <div class="ms-song-content">
                                                        <h3 class="ms-song-title">
                                                            <a href="{{ route('musician.data', $musician->id) }}">{{ $musician->name }}</a>
                                                        </h3>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    <div class="header__btn">
                                        <a href="{{ route('team') }}" class="ms-border-btn"><i class="fa-regular fa-plus"></i>Vote your favourite candidate</a>
                                    </div>
                                    @if(!$user)
                                    <div class="user__acount d-none d-xxl-inline-flex">
                                            <span>
                                                <a href="{{ route('user.login') }}"><i class="flaticon-user"></i></a>
                                            </span>
                                    </div>
                                    @else
                                        <div class="enquiry__list ml-10 mr-10 ms-browse-act-wrap p-relative">
                                            <div class="ms-enquiry-box p-relative d-none d-xl-inline-flex">
                                                <a href="#"><i class="flaticon-star icon"></i>
                                                    <span class="text">{{ $user->name }}</span></a>
                                            </div>
                                            <div class="ms-browse-act-item-wrap p-absolute">
                                                <div class="ms-song-item">
                                                    <div class="ms-song-content">
                                                        <h3 class="ms-song-title">
                                                            <a href="{{ route('logout') }}"
                                                               onclick="event.preventDefault();
                                                                 document.getElementById('logout-form').submit();"><i class="dripicons-power"></i>
                                                                {{trans('file.logout')}}
                                                            </a>
                                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                                @csrf
                                                            </form>
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="header__hamburger">
                                    <div class="sidebar__toggle">
                                        <a class="bar-icon" href="javascript:void(0)">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Header area end -->

      <div class="ms-all-content ms-content-mobile-space pt-130">
          @yield('content')
            <!-- Footer Area Start Here  -->
            <footer>
                <div class="ms-footer-bg ms-footer-overlay zindex-1 include__bg pt-120" data-background="assets/img/bg/sound-bg.png">
{{--                    <div class="ms-footer-top pt-130">--}}
{{--                        <div class="container">--}}
{{--                            <div class="ms-footer-border pb-10">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-xxl-2 col-lg-3 col-md-6">--}}
{{--                                        <div class="ms-footer-widget mb-50">--}}
{{--                                            <h3 class="ms-footer-title">Local Band Group</h3>--}}
{{--                                            <ul>--}}
{{--                                                <li><a href="#">The Tricks</a></li>--}}
{{--                                                <li><a href="#">Sound City</a></li>--}}
{{--                                                <li><a href="#">Big Teaser</a></li>--}}
{{--                                                <li><a href="#">The New Tones</a></li>--}}
{{--                                                <li><a href="#">Halos Music</a></li>--}}
{{--                                                <li><a href="#">Soho Soul</a></li>--}}
{{--                                                <li><a href="#">Atlantic</a></li>--}}
{{--                                                <li><a href="#">The Fiction</a></li>--}}
{{--                                            </ul>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-xxl-2 col-lg-3 col-md-6">--}}
{{--                                        <div class="ms-footer-widget mb-50">--}}
{{--                                            <h3 class="ms-footer-title">Trending Genres</h3>--}}
{{--                                            <ul>--}}
{{--                                                <li><a href="#">Wedding Bands</a></li>--}}
{{--                                                <li><a href="#">Jazz &amp; Swing</a></li>--}}
{{--                                                <li><a href="#">Musician</a></li>--}}
{{--                                                <li><a href="#">Classical Musician</a></li>--}}
{{--                                                <li><a href="#">Corporate Party</a></li>--}}
{{--                                                <li><a href="#">Premiere Party Band</a></li>--}}
{{--                                                <li><a href="#">DJ Songs</a></li>--}}
{{--                                            </ul>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-xxl-2 col-lg-3 col-md-6">--}}
{{--                                        <div class="ms-footer-widget mb-50">--}}
{{--                                            <h3 class="ms-footer-title">Country We Serve</h3>--}}
{{--                                            <ul>--}}
{{--                                                <li><a href="#">United State</a></li>--}}
{{--                                                <li><a href="#">Canada</a></li>--}}
{{--                                                <li><a href="#">Australia</a></li>--}}
{{--                                                <li><a href="#">China</a></li>--}}
{{--                                                <li><a href="#">South Korea</a></li>--}}
{{--                                                <li><a href="#">Japan</a></li>--}}
{{--                                                <li><a href="#">Singapore</a></li>--}}
{{--                                                <li><a href="#">Hong Kong</a></li>--}}
{{--                                            </ul>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-xxl-2 col-lg-3 col-md-6">--}}
{{--                                        <div class="ms-footer-widget mb-50">--}}
{{--                                            <h3 class="ms-footer-title">Company</h3>--}}
{{--                                            <ul>--}}
{{--                                                <li><a href="#">About us</a></li>--}}
{{--                                                <li><a href="#">Contact us</a></li>--}}
{{--                                                <li><a href="#">Terms &amp; Policy</a></li>--}}
{{--                                                <li><a href="#">Help &amp; Support</a></li>--}}
{{--                                                <li><a href="#">Press</a></li>--}}
{{--                                            </ul>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-xxl-4 col-lg-5 col-md-6">--}}
{{--                                        <div class="ms-footer-widget mb-50">--}}
{{--                                            <h3 class="ms-footer-title">Subscribe Now</h3>--}}
{{--                                            <div class="ms-subscribe-form mb-20 pt-5">--}}
{{--                                                <i class="flaticon-mail"></i>--}}
{{--                                                <input type="text" placeholder="Enter your mail">--}}
{{--                                                <button type="submit" class="ms-subscribe-btn"><i class="fa-sharp fa-solid fa-paper-plane"></i></button>--}}
{{--                                            </div>--}}
{{--                                            <div class="ms-footer-warning mb-25">--}}
{{--                                                <p>You Don&rsquo;t Get Any Spam Message !</p>--}}
{{--                                            </div>--}}
{{--                                            <div class="ms-footer-social">--}}
{{--                                                <a href="https://www.linkedin.com/" title="Instagram" target="_blank">IN</a>--}}
{{--                                                <a href="https://twitter.com/" title="Twitter" target="_blank">TW</a>--}}
{{--                                                <a href="https://www.facebook.com/" title="Facebook" target="_blank">FB</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="ms-footer-bottom">
                        <div class="container">
                            <div class="ms-footer-bottom-wrap align-items-center d-flex flex-wrap justify-content-between pt-35 pb-20">
                                <div class="ms-footer-logo mb-15">
                                    <a href="{{ route('home') }}">
                                        <img src="{{url('public/logo', $general_setting->site_logo)}}" alt="logo">
                                    </a>
                                </div>
                                <div class="ms-footer-copy">
                                    <p>{{ $general_setting->developed_by }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- Footer Area End Here  -->
            </div>


            <!-- Back to top start -->
            <div class="progress-wrap">
                <svg class="progress-circle svg-content" width="100%" height="100%" viewbox="-1 -1 102 102">
                    <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"></path>
                </svg>
            </div>
            <!-- Back to top end -->

            <!-- JS here -->
            <script data-cfasync="false" src="{{ asset('frontend/js/cloudflare-static-email-decode.min.js') }}"></script>
            <script src="{{ asset('frontend/js/js-jquery-3.6.0.min.js') }}"></script>
            <script src="{{ asset('frontend/js/js-waypoints.min.js') }}"></script>
            <script src="{{ asset('frontend/js/js-bootstrap.bundle.min.js') }}"></script>
            <script src="{{ asset('frontend/js/js-nice-select.min.js') }}"></script>
            <script src="{{ asset('frontend/js/js-meanmenu.min.js') }}"></script>
            <script src="{{ asset('frontend/js/js-swiper-bundle.min.js') }}"></script>
            <script src="{{ asset('frontend/js/js-slick.min.js') }}"></script>
            <script src="{{ asset('frontend/js/js-magnific-popup.min.js') }}"></script>
            <script src="{{ asset('frontend/js/js-backtotop.js') }}"></script>
            <script src="{{ asset('frontend/js/js-ajax-form.js') }}"></script>
            <script src="{{ asset('frontend/js/js-jquery-ui.min.js') }}"></script>
            <script src="{{ asset('frontend/js/js-gsap.min.js') }}"></script>
            <script src="{{ asset('frontend/js/js-ScrollToPlugin.min.js') }}"></script>
            <script src="{{ asset('frontend/js/js-SplitText.min.js') }}"></script>
            <script src="{{ asset('frontend/js/js-ScrollTrigger.min.js') }}"></script>
            <script src="{{ asset('frontend/js/js-jquery.jplayer.min.js') }}"></script>
            <script src="{{ asset('frontend/js/js-jplayer.playlist.js') }}"></script>
            <script src="{{ asset('frontend/js/js-settings.js') }}"></script>
            <script src="{{ asset('frontend/js/js-main.js') }}"></script>
      </body>

</html>

<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}" />
    <title>{{$general_setting->site_title}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
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
    <link rel="stylesheet" href="{{ asset('public/css/frontend-modern.css') }}?v={{ config('app.version') }}" type="text/css" id="frontend-modern-style">
    @yield('styles')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
<div class="mouseCursor cursor-outer"></div>
<div class="mouseCursor cursor-inner"><span>{{trans('file.Darg')}}</span></div>

<!-- Preloader start -->
{{--<div id="preloader">--}}
{{--    <div class="line-loader">--}}
{{--        <div class="line"></div>--}}
{{--        <div class="line"></div>--}}
{{--        <div class="line"></div>--}}
{{--        <div class="line"></div>--}}
{{--        <div class="line"></div>--}}
{{--        <div class="line"></div>--}}
{{--        <div class="line"></div>--}}
{{--        <div class="line"></div>--}}
{{--        <div class="line"></div>--}}
{{--        <div class="line"></div>--}}
{{--        <div class="line"></div>--}}
{{--    </div>--}}
{{--</div>--}}
<!-- preloader end -->

@php
    $user = Auth::user() ?? null;
@endphp
<style>
    .header__action-inner {
         gap: 5px;
    }
    .ms-header-lang--end {
        margin-left: auto;
        padding-left: 12px;
        flex-shrink: 0;
    }
    .mg-header-search {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1 1 auto;
        max-width: 360px;
        margin: 0 20px;
        padding: 9px 16px;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.22);
        border-radius: 30px;
        transition: border-color .2s, background .2s;
    }
    .mg-header-search:focus-within {
        background: rgba(255,255,255,.16);
        border-color: #e87722;
    }
    .mg-header-search i { color: #e87722; font-size: 14px; flex: 0 0 auto; }
    .mg-header-search input {
        flex: 1 1 auto;
        min-width: 0;
        border: 0;
        outline: 0;
        background: transparent;
        color: #fff;
        font-size: 14px;
    }
    .mg-header-search input::placeholder { color: rgba(255,255,255,.6); }
    @media (max-width: 1199px) {
        .mg-header-search { max-width: 240px; margin: 0 12px; }
    }
    @media (max-width: 575px) {
        .mg-header-search { margin: 0 8px; padding: 7px 12px; gap: 6px; }
        .mg-header-search input { font-size: 13px; }
    }
    /* While the header search is active, a Swiper carousel of contestants is
       turned into a simple centered wrapped grid so matches are easy to see. */
    .swiper-container.is-filtering .swiper-wrapper {
        transform: none !important;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px 20px;
    }
    .swiper-container.is-filtering .swiper-slide { flex: 0 0 auto; }
</style>
<!-- Offcanvas area start -->
<div class="fix">
    <div class="offcanvas__info">
        <div class="offcanvas__wrapper">
            <div class="offcanvas__content">
                <div class="offcanvas__top mb-40 d-flex justify-content-between align-items-center">
                    <div class="offcanvas__logo">
                        <a href="{{ route('home') }}">
                            <img src="{{url('public/logo', $general_setting->site_logo)}}" alt="{{trans('file.Site Logo')}}" style="height: 100px">
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
                        <ul></ul>
                        <div class="user__name-mail">
                            @if(!$user)
                                <h4 class="user__name"><a href="{{ route('user.login') }}">{{trans('file.LogIn')}}</a></h4>
                            @else
                            <h4 class="user__name"><a href="javascript:void(0)">{{ $user->name }}</a></h4>
                            <p class="user__mail">
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"><i class="dripicons-power"></i>
                                    {{trans('file.logout')}}
                                </a>
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="hr-1 mt-30 mb-30 d-xl-none"></div>
                <div class="offcanvas__btn mb-30">
                    <a class="user__name" href="{{ route('events') }}"><i class="fa-solid fa-plus"></i> {{trans('file.Buy Tickets')}}</a>
                </div>
                <div class="offcanvas__btn mb-30">
                    <a class="user__name" href="{{ route('home') }}"><i class="fa-solid fa-plus"></i> {{trans('file.Home')}}</a>
                </div>
                <div class="offcanvas__btn mb-30">
                    <a class="user__name" href="{{ route('about') }}"><i class="fa-solid fa-plus"></i> {{trans('file.About Us')}}</a>
                </div>
                <div class="offcanvas__btn mb-30">
                    <a class="user__name" href="{{ route('gallery.page') }}"><i class="fa-solid fa-plus"></i> {{trans('file.Gallery')}}</a>
                </div>
                <div class="offcanvas__btn mb-30">
                    <a class="user__name" href="{{ route('contact') }}"><i class="fa-solid fa-plus"></i> {{trans('file.Contact Us')}}</a>
                </div>

                @if($user)
                <div class="hr-1 mt-30 mb-30 d-xl-none"></div>
                @if($user->role_id == 3)
                <div class="offcanvas__btn mb-30">
                    <a class="user__name" href="{{ route('user.contentant') }}"><i class="fa-solid fa-plus"></i> {{trans('file.My Votes')}}</a>
                </div>
                <div class="offcanvas__btn mb-30">
                    <a class="user__name" href="{{ route('user.events') }}"><i class="fa-solid fa-plus"></i> {{trans('file.My Events')}}</a>
                </div>
                @else
                <div class="offcanvas__btn mb-30">
                    <a class="user__name" href="{{ url('/admin') }}"><i class="fa-solid fa-plus"></i> {{trans('file.Admin Dashboard')}}</a>
                </div>
                @endif
                @endif
                <div class="hr-1 mt-30 mb-30 d-xl-none"></div>
                <div class="offcanvas__btn mb-30">
                    <a class="ms-border-btn" href="{{ route('team') }}"><i class="fa-solid fa-plus"></i> {{trans('file.Vote Now')}}</a>
                </div>
                <div class="offcanvas__btn mb-30 offcanvas__lang">
                    @include('partials.lang_switch')
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
                                    <img src="{{url('public/logo', $general_setting->site_logo)}}" alt="{{trans('file.Site Logo')}}" style="height: 70px">
                                </a>
                            </div>
                            <div class="mg-header-search">
                                <i class="fa fa-search"></i>
                                <input type="text" id="mg-header-contestant-search" autocomplete="off"
                                       placeholder="{{ trans('file.Search Your Contestant') }}" aria-label="{{ trans('file.Search Your Contestant') }}">
                            </div>
                            <a class="mg-header-vote-btn" href="{{ route('team') }}">{{ trans('file.Vote Now') }}</a>
                            <div class="header__right">
                                <div class="mean__menu-wrapper">
                                    <div class="main-menu main-menu-ff-space">
                                        <nav id="mobile-menu">
                                            <ul>
{{--                                                <li>--}}
{{--                                                    <a href="https://mail.hostinger.com" target="_blank">{{trans('file.Email')}}</a>--}}
{{--                                                </li>--}}
                                                <li>
                                                    <a href="{{ route('events') }}">{{trans('file.Buy Tickets')}}</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('home') }}">{{trans('file.Home')}}</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('about') }}">{{trans('file.About Us')}}</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('gallery.page') }}">{{trans('file.Gallery')}}</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('contact') }}">{{trans('file.Contact Us')}}</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('team') }}">{{trans('file.Vote Now')}}</a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                                <div class="header__action-inner d-flex align-items-center flex-grow-1 justify-content-end">
                                    @if(!$user)
                                    <div class="user__acount d-none d-xxl-inline-flex">
                                            <span>
                                                <a href="{{ route('user.login') }}"><i class="flaticon-user"></i></a>
                                            </span>
                                    </div>
                                    @else
                                        @if($user->role_id == 3)
                                        <div class="enquiry__list ml-10 mr-10 ms-browse-act-wrap p-relative">
                                            <div class="ms-enquiry-box p-relative d-none d-xl-inline-flex">
                                                <a href="#"><i class="flaticon-star icon"></i>
                                                    <span class="text">{{ $user->name }}</span>
                                                </a>
                                            </div>
                                            <div class="ms-browse-act-item-wrap p-absolute">
                                                <div class="ms-song-item">
                                                    <div class="ms-song-content">
                                                        <h3 class="ms-song-title">
                                                            <a href="{{ route('user.contentant') }}">
                                                                <span class="text">{{trans('file.My Votes')}}</span>
                                                            </a>
                                                        </h3>
                                                        <hr>
                                                        <h3 class="ms-song-title">
                                                            <a href="{{ route('user.events') }}">
                                                                <span class="text">{{trans('file.My Events')}}</span>
                                                            </a>
                                                        </h3>
                                                        <hr>
                                                        <h3 class="ms-song-title" style="margin-bottom: 10px;">
                                                            <a href="{{ route('logout') }}"
                                                            onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();">
                                                                <i class="dripicons-power"></i> {{trans('file.logout')}}
                                                            </a>
                                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                                @csrf
                                                            </form>
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        @php $headerRoleName = optional($user->role)->name ?? 'Staff'; @endphp
                                        <div class="mg-fe-user-dropdown ml-10 mr-10">
                                            <button type="button" class="mg-fe-user-toggle" aria-haspopup="true" aria-expanded="false">
                                                <span class="mg-fe-user-avatar">{{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}</span>
                                                <span class="mg-fe-user-meta d-none d-xl-inline-flex">
                                                    <span class="mg-fe-user-name">{{ $user->name }}</span>
                                                    <span class="mg-fe-user-role">{{ strtoupper(str_replace('_', ' ', $headerRoleName)) }}</span>
                                                </span>
                                                <i class="fa fa-angle-down"></i>
                                            </button>
                                            <div class="mg-fe-user-menu">
                                                <div class="mg-fe-user-menu__head">{{ trans('file.My Account') }}</div>
                                                <a href="{{ url('/admin') }}"><i class="fa fa-th-large"></i> {{ trans('file.Admin Dashboard') }}</a>
                                                <a href="{{ route('user.profile', $user->id) }}"><i class="fa fa-user"></i> {{ trans('file.profile') }}</a>
                                                <hr>
                                                <a href="#" class="mg-fe-user-logout" onclick="event.preventDefault(); document.getElementById('frontend-admin-logout-form').submit();">
                                                    <i class="fa fa-sign-out"></i> {{ trans('file.logout') }}
                                                </a>
                                                <form id="frontend-admin-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            </div>
                                        </div>
                                        @endif
                                    @endif
                                    <div class="ms-header-lang ms-header-lang--end">
                                        @include('partials.lang_switch')
                                    </div>
                                </div>
                            </div>
                            {{-- Hamburger sits outside .header__right so logo+search cannot push it off-screen --}}
                            <div class="header__hamburger">
                                <div class="sidebar__toggle">
                                    <a class="bar-icon" href="javascript:void(0)" aria-label="Menu">
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
</header>
<!-- Header area end -->

      <div class="ms-all-content ms-content-mobile-space pt-130">
          @yield('content')
            <!-- Footer Area Start Here  -->
            <footer>
                <div class="ms-footer-bg ms-footer-overlay zindex-1 include__bg pt-120" data-background="{{ url('public/frontend/images/sound-bg.png') }}">
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
                                        <img src="{{url('public/logo', $general_setting->site_logo)}}" alt="{{trans('file.Site Logo')}}">
                                    </a>
                                </div>
                                <div class="ms-footer-copy text-end">
                                    <p class="mb-1" style="font-size:14px;color:rgba(255,255,255,.75);">&copy; {{ date('Y') }} {{ $general_setting->site_title ?? 'Mulema GC' }}</p>
                                    @include('partials.developer-credit')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <style>.mg-dev-credit{margin:0;font-size:11px;line-height:1.4;opacity:.65;color:rgba(255,255,255,.7);}.mg-dev-credit a{color:inherit;text-decoration:none;}.mg-dev-credit a:hover{text-decoration:underline;color:#e87722;}</style>
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
            <script data-cfasync="false" src="{{ asset('public/frontend/js/cloudflare-static-email-decode.min.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-jquery-3.6.0.min.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-waypoints.min.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-bootstrap.bundle.min.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-nice-select.min.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-meanmenu.min.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-swiper-bundle.min.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-slick.min.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-magnific-popup.min.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-backtotop.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-ajax-form.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-jquery-ui.min.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-gsap.min.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-ScrollToPlugin.min.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-SplitText.min.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-ScrollTrigger.min.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-jquery.jplayer.min.js') }}"></script>
            <script src="{{ asset('public/frontend/js/js-jplayer.playlist.js') }}"></script>
{{--            <script src="{{ asset('public/frontend/js/js-settings.js') }}"></script>--}}
            <script src="{{ asset('public/frontend/js/js-main.js') }}"></script>
            <script>
            (function () {
                document.querySelectorAll('.mg-fe-user-dropdown').forEach(function (wrap) {
                    var btn = wrap.querySelector('.mg-fe-user-toggle');
                    if (!btn) { return; }
                    btn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        wrap.classList.toggle('is-open');
                        btn.setAttribute('aria-expanded', wrap.classList.contains('is-open') ? 'true' : 'false');
                    });
                });
                document.addEventListener('click', function () {
                    document.querySelectorAll('.mg-fe-user-dropdown.is-open').forEach(function (wrap) {
                        wrap.classList.remove('is-open');
                        var btn = wrap.querySelector('.mg-fe-user-toggle');
                        if (btn) { btn.setAttribute('aria-expanded', 'false'); }
                    });
                });
            })();

            // Header contestant search: live-filters contestants on the current
            // page (home / Vote Now). On pages without a contestant list, Enter
            // sends the query to the Vote Now page which applies the filter.
            (function () {
                var input = document.getElementById('mg-header-contestant-search');
                if (!input) { return; }
                var teamUrl = @json(route('team'));

                // Capture the contestants in their original order once so we can
                // always restore/rank them consistently.
                var originalItems = Array.prototype.slice.call(document.querySelectorAll('.js-contestant-item'));

                function applyFilter() {
                    if (!originalItems.length) { return; }
                    var q = input.value.trim().toLowerCase();
                    var groups = [];
                    originalItems.forEach(function (el) {
                        var g = null;
                        for (var i = 0; i < groups.length; i++) {
                            if (groups[i].parent === el.parentNode) { g = groups[i]; break; }
                        }
                        if (!g) { g = { parent: el.parentNode, matched: [], rest: [] }; groups.push(g); }
                        var name = (el.getAttribute('data-name') || el.textContent || '').toLowerCase();
                        if (!q || name.indexOf(q) !== -1) { el.style.display = ''; g.matched.push(el); }
                        else { el.style.display = 'none'; g.rest.push(el); }
                    });
                    // Move matches to the top of each grid (original order preserved).
                    groups.forEach(function (g) {
                        var frag = document.createDocumentFragment();
                        g.matched.concat(g.rest).forEach(function (el) { frag.appendChild(el); });
                        g.parent.appendChild(frag);

                        // If the list lives inside a Swiper carousel, switch it to a
                        // plain wrapped grid while filtering so hidden slides collapse
                        // and matches are visible; restore the slider when cleared.
                        var container = g.parent.closest ? g.parent.closest('.swiper-container') : null;
                        if (container) {
                            if (q) {
                                container.classList.add('is-filtering');
                            } else {
                                container.classList.remove('is-filtering');
                                if (container.swiper) {
                                    container.swiper.update();
                                    container.swiper.slideTo(0, 0);
                                }
                            }
                        }
                    });
                }

                input.addEventListener('input', function () {
                    if (originalItems.length) { applyFilter(); }
                });

                input.addEventListener('keydown', function (e) {
                    if (e.key !== 'Enter') { return; }
                    e.preventDefault();
                    if (originalItems.length) {
                        applyFilter();
                    } else {
                        var q = input.value.trim();
                        window.location.href = teamUrl + (q ? ('?q=' + encodeURIComponent(q)) : '');
                    }
                });

                // Keep the header box in sync with a ?q= deep link.
                var params = new URLSearchParams(window.location.search);
                var q = params.get('q');
                if (q) { input.value = q; if (originalItems.length) { applyFilter(); } }
            })();
            </script>
            @yield('scripts')
      </body>

</html>


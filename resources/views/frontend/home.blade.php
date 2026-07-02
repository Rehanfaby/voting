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

        <!-- Popup Overlay (managed from Settings > Site Content > Homepage Popup) -->
        @if(\App\Helpers\SiteContent::enabled('popup'))
        <div class="popup-overlay" id="popup" data-autoshow="1">
            <div class="popup-content">
                <span class="close-btn" id="closeBtn">&times;</span>
                <img src="{{ \App\Helpers\SiteContent::popupImageUrl() }}" alt="Announcement" class="popup-image" />
            </div>
        </div>
        @endif
        <!-- Brand Song Area Start Here  -->
        <section class="ms-song-area ms-rank-area pt-40 pb-40">
            <div class="container-fluid ms-maw-1710">
                @php
                    // Rank contestants by their total votes (highest first).
                    $ranked = $musicians->sortByDesc(function ($m) use ($vote_counts) {
                        return $vote_counts[$m->id] ?? 0;
                    })->values();
                @endphp
                <div class="swiper-container ms-song-active fix">
                    <div class="swiper-wrapper">
                        @foreach($ranked as $key=>$musician)
                            <div class="swiper-slide">
                                <div class="ms-rank-item">
                                    <div class="ms-rank-avatar">
                                        <span class="ms-rank-badge">{{ $key + 1 }}</span>
                                        <a href="{{ route('musician.data', $musician->id) }}">
                                            <img src="{{ \App\Helpers\ImageOptimizer::employeeImageUrl($musician->image) }}" alt="{{ $musician->name }}" width="180" height="180" loading="lazy" decoding="async">
                                        </a>
                                    </div>
                                    <div class="ms-rank-info">
                                        <h3 class="ms-rank-name"><a href="{{ route('musician.data', $musician->id) }}">{{ $musician->name }}</a></h3>
                                        <span class="ms-rank-votes"><i class="fa fa-vote-yea"></i> {{ number_format($vote_counts[$musician->id] ?? 0) }} {{ trans('file.Votes') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <style>
                .ms-rank-area { background: radial-gradient(900px 400px at 50% -30%, rgba(246,196,83,.08), transparent 60%); }
                .ms-rank-item { text-align:center; padding:12px 6px 18px; }
                .ms-rank-avatar { position:relative; width:180px; height:180px; margin:0 auto 16px; border-radius:50%; padding:6px;
                    background:linear-gradient(145deg,#f6c453,#e0a021);
                    box-shadow:0 0 0 6px #12294d, 0 0 30px rgba(246,196,83,.5); }
                .ms-rank-avatar img { width:100%; height:100%; object-fit:cover; border-radius:50%; display:block; border:3px solid #12294d; background:#0d1f3c; }
                .ms-rank-badge { position:absolute; top:2px; left:2px; z-index:2; min-width:34px; height:34px; line-height:30px; padding:0 8px;
                    border-radius:20px; background:#12294d; color:#f6c453; font-weight:800; font-size:15px; text-align:center;
                    border:2px solid #f6c453; box-shadow:0 3px 8px rgba(0,0,0,.35); }
                .ms-rank-name { font-size:18px; font-weight:700; margin:0 0 5px; line-height:1.25; }
                .ms-rank-name a { color:#fff; }
                .ms-rank-name a:hover { color:#f6c453; }
                .ms-rank-votes { display:inline-block; color:#f6c453; font-weight:700; font-size:13px; letter-spacing:.6px; text-transform:uppercase; }
                .ms-rank-votes i { margin-right:4px; }
                @media (max-width:575px){ .ms-rank-avatar { width:140px; height:140px; } .ms-rank-name { font-size:16px; } }
            </style>
        </section>
        <!-- Brand Song Area End Here  -->

        <!-- Banner Area Start Here  -->
        <section class="ms-banner-area p-relative">
            <a class="ms-scroll-down" href="#">{{trans('file.SCROLL DOWN')}}</a>
            <div class="container-fluid ms-maw-1710">
                @php $sc_hero = \App\Helpers\SiteContent::heroImageUrl(); @endphp
                <div class="ms-br-30 mx-auto include__bg z-index-1 ms-overlay-1 p-relative" data-background="{{ $sc_hero }}" style="background-image:url('{{ $sc_hero }}'); background-size:cover; background-position:center;">
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
                                        <img src="{{ \App\Helpers\ImageOptimizer::employeeImageUrl($musician->image) }}" loading="lazy" decoding="async">
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
        @endif
        <!-- Function Brand Area End Here  -->

        <!-- Our Winners Area Start Here (shown only when enabled at the end of the competition) -->
        @if(\App\Helpers\SiteContent::enabled('our_winners'))
        <section class="ms-winners-area ms-bg-2 pb-130 pt-90">
            <div class="container">
                <div class="row justify-content-center bdFadeUp">
                    <div class="col-xl-8">
                        <div class="section__title-wrapper mb-65 text-center bd-title-anim">
                            <span class="section__subtitle"><i class="fa-solid fa-trophy"></i> {{ trans('file.Grand Champions') }}</span>
                            <h2 class="section__title">
                                {{ trans('file.Our') }} <span class="animated-underline active">{{ trans('file.Winners') }}!</span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="ms-winners-podium bdFadeUp">
                    @foreach($best_musicians as $key => $winner_data)
                        @php $winner = \App\Employee::find($winner_data->musician_id); @endphp
                        @if($winner && $key < 3)
                            <div class="ms-winner-card ms-winner-rank-{{ $key + 1 }}">
                                <div class="ms-winner-badge">
                                    @if($key == 0)
                                        <i class="fa-solid fa-crown"></i>
                                    @else
                                        <i class="fa-solid fa-medal"></i>
                                    @endif
                                </div>
                                <div class="ms-winner-thumb">
                                    <img src="{{ \App\Helpers\ImageOptimizer::employeeImageUrl($winner->image) }}" alt="{{ $winner->name }}" loading="lazy" decoding="async">
                                </div>
                                <div class="ms-winner-place">
                                    @if($key == 0) 1st @elseif($key == 1) 2nd @else 3rd @endif
                                </div>
                                <h4 class="ms-winner-name">{{ $winner->name }}</h4>
                                <span class="ms-winner-votes"><i class="fa fa-vote-yea"></i> {{ $winner_data->total_vote }} {{ trans('file.Votes') }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <style>
                .ms-winners-area { background: radial-gradient(1200px 600px at 50% -10%, rgba(240,169,59,.12), transparent 60%); }
                .ms-winners-podium { display: flex; flex-wrap: wrap; gap: 26px; justify-content: center; align-items: flex-end; }
                .ms-winner-card {
                    position: relative; text-align: center; width: 260px; padding: 30px 22px 26px;
                    border-radius: 18px; background: rgba(255,255,255,.04);
                    border: 1px solid rgba(246,196,83,.28); box-shadow: 0 18px 45px rgba(0,0,0,.35);
                    transition: transform .3s ease, border-color .3s ease;
                }
                .ms-winner-card:hover { transform: translateY(-8px); border-color: rgba(246,196,83,.7); }
                .ms-winner-rank-1 { order: 2; width: 290px; padding-top: 40px; background: linear-gradient(180deg, rgba(240,169,59,.16), rgba(255,255,255,.04)); border-color: rgba(240,169,59,.6); }
                .ms-winner-rank-2 { order: 1; }
                .ms-winner-rank-3 { order: 3; }
                .ms-winner-badge {
                    position: absolute; top: -22px; left: 50%; transform: translateX(-50%);
                    width: 46px; height: 46px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
                    background: linear-gradient(135deg, #f0a93b, #e2562a); color: #fff; font-size: 20px; box-shadow: 0 6px 16px rgba(226,86,42,.5);
                }
                .ms-winner-rank-1 .ms-winner-badge { background: linear-gradient(135deg, #ffd76a, #f0a93b); box-shadow: 0 6px 18px rgba(240,169,59,.6); }
                .ms-winner-thumb { width: 130px; height: 130px; margin: 8px auto 14px; border-radius: 50%; overflow: hidden; border: 3px solid rgba(246,196,83,.6); }
                .ms-winner-rank-1 .ms-winner-thumb { width: 150px; height: 150px; }
                .ms-winner-thumb img { width: 100%; height: 100%; object-fit: cover; }
                .ms-winner-place { display: inline-block; margin-bottom: 8px; color: #f0a93b; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; font-size: 13px; }
                .ms-winner-name { color: #fff; font-size: 20px; font-weight: 700; margin: 0 0 8px; }
                .ms-winner-votes { display: inline-flex; align-items: center; gap: 7px; color: rgba(255,255,255,.75); font-size: 14px; }
                .ms-winner-votes i { color: #f6c453; }
                @media (max-width: 767px) { .ms-winner-card, .ms-winner-rank-1 { width: 100%; order: 0 !important; } }
            </style>
        </section>
        @endif
        <!-- Our Winners Area End Here  -->




        <!-- judge  area start -->
        @if(\App\Helpers\SiteContent::enabled('judges'))
        <section class="ms-fun-brand ms-bg-2 ms-judges-section pb-130 pt-125">
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
                                            @php $flagUrl = \App\Helpers\CountryFlag::url($contentant->country, 24); @endphp
                                            <div class="swiper-slide">
                                                <div class="ms-popular__item p-relative mb-30">
                                                    <div class="ms-popular__thumb ms-judge-glow">
                                                        <div class="ms-popular-overlay"></div>
                                                        <a><img src="{{ \App\Helpers\ImageOptimizer::employeeImageUrl($contentant->image) }}" alt="{{ $contentant->name }}" loading="lazy" decoding="async"></a>
                                                        <a class="ms-popular__link">
                                                            <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                        </a>
                                                    </div>
                                                    <h4 class="ms-popular__title">
                                                        <a>
                                                            {{ $contentant->name }}
                                                            @if($flagUrl)
                                                                <img src="{{ $flagUrl }}" alt="{{ \App\Helpers\CountryFlag::label($contentant->country) }}" width="20" height="15" style="display:inline-block;vertical-align:middle;margin-left:6px;border-radius:2px;" loading="lazy">
                                                            @endif
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
            <style>
                .ms-judges-section .ms-judge-glow {
                    border: 2px solid rgba(246, 196, 83, 0.55);
                    box-shadow:
                        0 0 18px rgba(246, 196, 83, 0.45),
                        0 0 36px rgba(246, 196, 83, 0.2),
                        inset 0 0 12px rgba(246, 196, 83, 0.08);
                }
                .ms-judges-section .ms-popular__title a {
                    color: #f6c453;
                    background: #12294d;
                    border: 1px solid rgba(246, 196, 83, 0.45);
                    font-weight: 600;
                }
                .ms-judges-section .ms-popular__title a:hover {
                    color: #ffffff;
                    background: #1a3a6b;
                    border-color: #f6c453;
                }
            </style>
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
                                                        <a ><img src="{{ \App\Helpers\ImageOptimizer::employeeImageUrl($ambassador->image) }}" loading="lazy" decoding="async"></a>
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
        @php
            $sc_casting_on       = \App\Helpers\SiteContent::enabled('casting');
            $sc_finals_on        = \App\Helpers\SiteContent::enabled('finals');
            $sc_casting_title    = \App\Helpers\SiteContent::get('casting_title', 'Provincial Casting Calendar');
            $sc_casting_subtitle = \App\Helpers\SiteContent::get('casting_subtitle', "Here's the draft schedule for the Mulema Gospel casting by province.");
            $sc_casting_rows     = \App\Helpers\SiteContent::get('casting_rows', []);
        @endphp
        @if($sc_casting_on || $sc_finals_on)
        <section class="ms-casting-area pt-130 pb-130">
            <div class="container">
                @if($sc_casting_on)
                <div class="row justify-content-center bdFadeUp">
                    <div class="col-lg-8">
                        <div class="section__title-wrapper mb-60 text-center bd-title-anim">
                            <span class="section__subtitle">{{ trans('file.Casting Tour') }}</span>
                            <h2 class="section__title"><span class="animated-underline active">{{ $sc_casting_title }}</span></h2>
                            @if(!empty($sc_casting_subtitle))
                            <p class="casting-lead">{{ $sc_casting_subtitle }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                @php
                    $castingSchedule = [];
                    if (!empty($sc_casting_rows) && is_array($sc_casting_rows)) {
                        foreach ($sc_casting_rows as $r) {
                            $castingSchedule[] = [
                                'province' => $r['province'] ?? '',
                                'city'     => $r['venue'] ?? ($r['city'] ?? ''),
                                'date'     => $r['date'] ?? '',
                            ];
                        }
                    }
                    if (empty($castingSchedule)) {
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
                    }
                @endphp

                <div class="row bdFadeUp">
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
                @endif
                {{-- End Provincial Casting Calendar --}}

                @if($sc_finals_on)
                <div class="row justify-content-center bdFadeUp mt-50">
                    <div class="col-lg-8">
                        <div class="section__title-wrapper mb-40 text-center bd-title-anim">
                            <span class="section__subtitle"><i class="fa-solid fa-trophy"></i> Grand Finale</span>
                            <h2 class="section__title">Finals <span class="animated-underline active">Schedule</span></h2>
                        </div>
                    </div>
                </div>

                @php $sc_next_prime = \App\Helpers\SiteContent::nextPrime(); @endphp
                @if(\App\Helpers\SiteContent::get('primes_countdown', true) && $sc_next_prime && !empty($sc_next_prime['date']))
                <div class="row justify-content-center bdFadeUp mb-50">
                    <div class="col-xl-8">
                        <div class="ms-countdown" data-deadline="{{ $sc_next_prime['date'] }}">
                            <p class="ms-countdown__label"><i class="fa-regular fa-clock"></i> {{ $sc_next_prime['label'] ?? trans('file.Finals Schedule') }} {{ trans('file.starts in') }}</p>
                            <div class="ms-countdown__grid">
                                <div class="ms-countdown__cell"><span class="cd-days">00</span><small>Days</small></div>
                                <div class="ms-countdown__cell"><span class="cd-hours">00</span><small>Hrs</small></div>
                                <div class="ms-countdown__cell"><span class="cd-mins">00</span><small>Min</small></div>
                                <div class="ms-countdown__cell"><span class="cd-secs">00</span><small>Sec</small></div>
                            </div>
                        </div>
                    </div>
                </div>
                <style>
                    .ms-countdown { text-align: center; padding: 24px 20px; border-radius: 16px; background: rgba(255,255,255,.04); border: 1px solid rgba(246,196,83,.3); }
                    .ms-countdown__label { color: rgba(255,255,255,.8); font-size: 15px; margin: 0 0 14px; letter-spacing: .5px; }
                    .ms-countdown__label i { color: #f6c453; margin-right: 6px; }
                    .ms-countdown__grid { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
                    .ms-countdown__cell { min-width: 78px; padding: 14px 10px; border-radius: 12px; background: linear-gradient(180deg, rgba(240,169,59,.15), rgba(255,255,255,.03)); border: 1px solid rgba(240,169,59,.35); }
                    .ms-countdown__cell span { display: block; font-size: 30px; font-weight: 800; color: #f0a93b; line-height: 1; }
                    .ms-countdown__cell small { display: block; margin-top: 6px; color: rgba(255,255,255,.65); font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
                </style>
                <script>
                    (function () {
                        var el = document.querySelector('.ms-countdown');
                        if (!el) { return; }
                        var deadline = new Date((el.getAttribute('data-deadline') || '').replace(' ', 'T')).getTime();
                        if (isNaN(deadline)) { return; }
                        function pad(n) { return (n < 10 ? '0' : '') + n; }
                        function tick() {
                            var diff = deadline - Date.now();
                            if (diff < 0) { diff = 0; }
                            var d = Math.floor(diff / 86400000);
                            var h = Math.floor((diff % 86400000) / 3600000);
                            var m = Math.floor((diff % 3600000) / 60000);
                            var s = Math.floor((diff % 60000) / 1000);
                            el.querySelector('.cd-days').textContent = pad(d);
                            el.querySelector('.cd-hours').textContent = pad(h);
                            el.querySelector('.cd-mins').textContent = pad(m);
                            el.querySelector('.cd-secs').textContent = pad(s);
                        }
                        tick();
                        setInterval(tick, 1000);
                    })();
                </script>
                @endif

                <div class="row justify-content-center bdFadeUp">
                    <div class="col-xl-10">
                        <div class="casting-timeline">
                            @php
                                $finals = [
                                    ['label' => 'Prime 1',     'date' => 'August 9, 2026',  'icon' => 'fa-microphone-lines'],
                                    ['label' => 'Prime 2',     'date' => 'August 17, 2026', 'icon' => 'fa-music'],
                                    ['label' => 'Prime 3',     'date' => 'August 23, 2026', 'icon' => 'fa-star'],
                                    ['label' => 'Final Prime', 'date' => 'August 30, 2026', 'icon' => 'fa-crown'],
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
                @endif
                {{-- End Finals Schedule --}}
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
        @endif
        <!-- Special Events Area End -->

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
        if (!popup) { return; } // popup disabled from settings

        // Show popup
        popup.classList.add('active');

        // Close on click
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                popup.classList.remove('active');
            });
        }

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
        @if(\App\Helpers\SiteContent::enabled('most_voted') && !empty($weekly_top))
        <section class="ms-song-area ms-rank-area ms-weekly-voted pt-80 pb-80">
            <div class="container">
                <div class="text-center mb-40">
                    <h2 class="section__title mb-10">{{ trans('file.Most Voted Contestant of the Week') }}</h2>
                    <p class="mb-0">{{ trans('file.Here comes the best Contestant of the week') }}!</p>
                </div>
                <div class="row justify-content-center">
                    @foreach($weekly_top as $key => $row)
                        @php $musician = $row->employee; @endphp
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4">
                            <div class="ms-rank-item">
                                <div class="ms-rank-avatar mx-auto">
                                    <span class="ms-rank-badge">{{ $key + 1 }}</span>
                                    <a href="{{ route('musician.data', $musician->id) }}">
                                        <img src="{{ \App\Helpers\ImageOptimizer::employeeImageUrl($musician->image) }}" alt="{{ $musician->name }}" width="180" height="180" loading="lazy" decoding="async">
                                    </a>
                                </div>
                                <div class="ms-rank-info">
                                    <h3 class="ms-rank-name"><a href="{{ route('musician.data', $musician->id) }}">{{ $musician->name }}</a></h3>
                                    <span class="ms-rank-votes"><i class="fa fa-vote-yea"></i> {{ number_format($row->total_vote) }} {{ trans('file.Votes') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
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

                                                        <a ><img src="{{ \App\Helpers\ImageOptimizer::employeeImageUrl($best_musician->image) }}" loading="lazy" decoding="async"></a>
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

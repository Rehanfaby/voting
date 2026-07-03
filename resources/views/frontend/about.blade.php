@extends('frontend.layout.main')
@section('content')

    <main class="mg-about">
        {{-- Hero --}}
        <section class="mg-about__hero pt-130 pb-80">
            <div class="mg-about__hero-glow" aria-hidden="true"></div>
            <div class="container position-relative">
                <div class="row justify-content-center text-center">
                    <div class="col-xl-9 col-lg-10">
                        <span class="mg-about__badge">{{ trans('file.About Us') }}</span>
                        <h1 class="mg-about__headline">
                            {{ trans('file.Mulema the') }}
                            <span class="mg-about__accent">{{ trans('file.Gospel') }}</span>
                            {{ trans('file.Talent') }}
                        </h1>
                        <p class="mg-about__sub">{{ \App\Helpers\SiteContent::aboutField('hero_subtitle', trans('file.About hero subtitle')) }}</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Mission --}}
        <section class="mg-about__mission pb-60">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-5">
                        <div class="mg-about__media">
                            <div class="mg-about__media-frame">
                                <img src="{{ \App\Helpers\SiteContent::aboutImageUrl() }}" alt="{{ trans('file.Mulema Gospel Talent') }}" loading="lazy" decoding="async">
                            </div>
                            <div class="mg-about__media-badge">
                                <i class="fa-solid fa-heart"></i>
                                <span>{{ \App\Helpers\SiteContent::aboutField('heart_badge', trans('file.Mulema means The Heart')) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <span class="mg-about__label">{{ trans('file.Our Mission') }}</span>
                        <h2 class="mg-about__title">{{ \App\Helpers\SiteContent::aboutField('mission_title', trans('file.Raising true worshipers across Cameroon')) }}</h2>
                        <div class="mg-about__prose">
                            <p>{{ \App\Helpers\SiteContent::aboutField('mission_p1', trans('file.About mission paragraph 1')) }}</p>
                            <p>{{ \App\Helpers\SiteContent::aboutField('mission_p2', trans('file.About mission paragraph 2')) }}</p>
                            <p>{{ \App\Helpers\SiteContent::aboutField('mission_p3', trans('file.About mission paragraph 3')) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Our Values --}}
        <section class="mg-about__values-section pb-70">
            <div class="container">
                <h2 class="mg-about__values-heading">{{ \App\Helpers\SiteContent::aboutField('values_heading', trans('file.Our Values')) }}</h2>
                <ul class="mg-about__values">
                    @foreach(\App\Helpers\SiteContent::aboutValues() as $value)
                        <li><i class="fa-solid {{ $value['icon'] }}"></i> {{ $value['label'] }}</li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- Stats band --}}
        <section class="mg-about__stats-band">
            <div class="container">
                <div class="mg-about__stats">
                    <div class="mg-about__stat">
                        <span class="mg-about__stat-num">10</span>
                        <span class="mg-about__stat-label">{{ trans('file.Regions of Cameroon') }}</span>
                    </div>
                    <div class="mg-about__stat">
                        <span class="mg-about__stat-num">2023</span>
                        <span class="mg-about__stat-label">{{ trans('file.Founded') }}</span>
                    </div>
                    <div class="mg-about__stat">
                        <span class="mg-about__stat-num"><i class="fa-solid fa-music"></i></span>
                        <span class="mg-about__stat-label">{{ trans('file.Gospel Music & Worship') }}</span>
                    </div>
                    <div class="mg-about__stat">
                        <span class="mg-about__stat-num"><i class="fa-solid fa-globe-africa"></i></span>
                        <span class="mg-about__stat-label">{{ trans('file.Nationwide auditions') }}</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Cameroon intro --}}
        <section class="mg-about__cameroon pb-90 pt-90">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-9 text-center">
                        <span class="mg-about__label mg-about__label--center">{{ trans('file.Hello Cameroon') }}</span>
                        <h2 class="mg-about__title mg-about__title--center">{{ \App\Helpers\SiteContent::aboutField('intro_title', trans('file.Cameroon gospel capital')) }}</h2>
                        <p class="mg-about__intro">{{ \App\Helpers\SiteContent::aboutField('intro_text', trans('file.About intro footer text')) }}</p>
                        <div class="mg-about__regions">
                            @foreach(\App\Helpers\SiteContent::aboutRegions() as $region)
                                <span class="mg-about__region">{{ $region }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Our Leaders --}}
        @if($team->isNotEmpty())
        <section class="mg-about__team pb-90">
            <div class="container">
                <div class="text-center mb-50">
                    <span class="mg-about__label mg-about__label--center">{{ trans('file.Our Leaders') }}</span>
                    <h2 class="mg-about__title mg-about__title--light mg-about__title--center">
                        {{ \App\Helpers\SiteContent::aboutField('leaders_heading', trans('file.Our Leaders')) }}
                    </h2>
                    @php $leadersSub = \App\Helpers\SiteContent::aboutField('leaders_subheading', ''); @endphp
                    @if($leadersSub)
                        <p class="mg-about__intro">{{ $leadersSub }}</p>
                    @else
                        <p class="mg-about__intro">{{ trans('file.Leaders subheading default') }}</p>
                    @endif
                </div>
                <div class="row justify-content-center g-4">
                    @foreach($team as $member)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <article class="mg-about__person">
                            <div class="mg-about__person-photo">
                                @if($member->image)
                                    <img src="{{ \App\Helpers\ImageOptimizer::employeeImageUrl($member->image) }}" alt="{{ $member->name }}" loading="lazy" decoding="async">
                                @else
                                    <span class="mg-about__person-initial">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <h3 class="mg-about__person-name">
                                {{ $member->name }}
                                @include('partials.country_flag', ['country' => $member->country, 'size' => 18, 'class' => 'mg-about__flag'])
                            </h3>
                            @if($member->title)
                                <p class="mg-about__person-role">{{ $member->title }}</p>
                            @endif
                            @if($member->bio)
                                <p class="mg-about__person-bio">{{ $member->bio }}</p>
                            @endif
                        </article>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        {{-- Winners --}}
        @php
            $winnerRows = collect($winners ?? [])->filter(function ($row) {
                return $row && trim((string) $row->name) !== '';
            });
        @endphp
        @if($winnerRows->isNotEmpty())
        <section class="mg-about__winners pb-90">
            <div class="container">
                <div class="text-center mb-50">
                    <span class="mg-about__label mg-about__label--center">{{ trans('file.Winners') }}</span>
                    <h2 class="mg-about__title mg-about__title--light mg-about__title--center">
                        {{ \App\Helpers\SiteContent::aboutField('winners_heading', $winnersYear . ' ' . trans('file.Winners')) }}
                    </h2>
                </div>
                <div class="row justify-content-center g-4">
                    @foreach(\App\AboutWinner::PLACEMENTS as $placement => $placementLabel)
                        @php $winner = $winners[$placement] ?? null; @endphp
                        @if($winner && trim((string) $winner->name) !== '')
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <article class="mg-about__winner mg-about__winner--{{ $placement }}">
                                <span class="mg-about__winner-badge">{{ $placementLabel }}</span>
                                <div class="mg-about__winner-photo">
                                    @if($winner->image)
                                        <img src="{{ \App\Helpers\ImageOptimizer::employeeImageUrl($winner->image) }}" alt="{{ $winner->name }}" loading="lazy" decoding="async">
                                    @else
                                        <span class="mg-about__person-initial">{{ strtoupper(substr($winner->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <h3 class="mg-about__winner-name">{{ $winner->name }}</h3>
                                @if($winner->bio)
                                    <p class="mg-about__winner-bio">{{ $winner->bio }}</p>
                                @endif
                            </article>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        {{-- CTA --}}
        <section class="mg-about__cta pb-130">
            <div class="container">
                <div class="mg-about__cta-box">
                    <h2>{{ trans('file.Vote for your favourite Contestant') }}</h2>
                    <p>{{ trans('file.Discover Talents') }}</p>
                    <a href="{{ route('team') }}" class="mg-btn">{{ trans('file.Vote your Candidate') }} <i class="fa-solid fa-arrow-right-long"></i></a>
                </div>
            </div>
        </section>
    </main>
@endsection

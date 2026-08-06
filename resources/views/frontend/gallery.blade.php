@extends('frontend.layout.main')
@section('content')

    <main class="mg-gallery-page">
        <section class="mg-about__hero pt-130 pb-60">
            <div class="mg-about__hero-glow" aria-hidden="true"></div>
            <div class="container position-relative">
                <div class="row justify-content-center text-center">
                    <div class="col-xl-9 col-lg-10">
                        <span class="mg-about__badge">{{ trans('file.Gallery') }}</span>
                        <h1 class="mg-about__headline">{{ trans('file.Moments') }} <span class="mg-about__accent">{{ trans('file.Gallery') }}</span></h1>
                        <p class="mg-about__sub">{{ trans('file.Gallery intro') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="pb-130">
            <div class="container">
                @php
                    $groups = $groups ?? [];
                    $hasAny = false;
                    foreach ($groups as $g) {
                        if (!empty($g['items'])) { $hasAny = true; break; }
                    }
                    if (!$hasAny && !empty($images)) {
                        $hasAny = true;
                        $groups = [['category' => ['id' => 'all', 'name' => trans('file.Gallery')], 'items' => $images]];
                    }
                @endphp

                @if($hasAny)
                    @if(count($groups) > 1)
                        <div class="mg-gallery-tabs" role="tablist">
                            <button type="button" class="mg-gallery-tab is-active" data-gal-tab="all">All</button>
                            @foreach($groups as $group)
                                @if(!empty($group['items']))
                                    <button type="button" class="mg-gallery-tab" data-gal-tab="{{ $group['category']['id'] }}">
                                        {{ $group['category']['name'] }}
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @foreach($groups as $group)
                        @if(empty($group['items']))
                            @continue
                        @endif
                        <div class="mg-gallery-section" data-gal-section="{{ $group['category']['id'] }}">
                            @if(count($groups) > 1)
                                <h2 class="mg-gallery-section__title">{{ $group['category']['name'] }}</h2>
                            @endif
                            <div class="mg-gallery-grid">
                                @foreach($group['items'] as $image)
                                    <a href="{{ $image['url'] }}" class="mg-gallery-item" target="_blank" rel="noopener" @if($image['caption']) title="{{ $image['caption'] }}" @endif>
                                        <img src="{{ $image['url'] }}" alt="{{ $image['caption'] ?: 'Gallery' }}" loading="lazy" decoding="async">
                                        @if($image['caption'])
                                            <span class="mg-gallery-caption">{{ $image['caption'] }}</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-center" style="color:rgba(255,255,255,.7)">{{ trans('file.No gallery images yet') }}</p>
                @endif
            </div>
        </section>
    </main>

    <style>
        .mg-gallery-tabs {
            display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; margin-bottom: 28px;
        }
        .mg-gallery-tab {
            border: 1px solid rgba(245,197,24,.45); background: rgba(10,35,80,.55); color: #fff;
            border-radius: 999px; padding: 8px 16px; font-weight: 700; font-size: 13px; cursor: pointer;
        }
        .mg-gallery-tab.is-active,
        .mg-gallery-tab:hover {
            background: #f5c518; color: #0a2350; border-color: #f5c518;
        }
        .mg-gallery-section { margin-bottom: 36px; }
        .mg-gallery-section__title {
            color: #f5c518; font-size: 1.25rem; font-weight: 800; margin: 0 0 14px; text-align: center;
        }
        .mg-gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 16px;
        }
        .mg-gallery-item {
            position: relative;
            display: block;
            border-radius: 14px;
            overflow: hidden;
            aspect-ratio: 4 / 3;
            background: #0d1f3c;
            border: 1px solid rgba(232, 119, 34, .18);
        }
        .mg-gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            display: block;
            transition: transform .45s ease;
        }
        .mg-gallery-item:hover img { transform: scale(1.08); }
        .mg-gallery-caption {
            position: absolute;
            left: 0; right: 0; bottom: 0;
            padding: 18px 12px 10px;
            font-size: 13px;
            color: #fff;
            background: linear-gradient(180deg, transparent, rgba(0, 0, 0, .75));
        }
        .mg-gallery-section.is-hidden { display: none; }
    </style>

    <script>
        window.addEventListener('load', function () {
            if (window.jQuery && jQuery.fn.magnificPopup) {
                jQuery('.mg-gallery-grid').magnificPopup({
                    delegate: 'a.mg-gallery-item',
                    type: 'image',
                    gallery: { enabled: true }
                });
            }

            var tabs = document.querySelectorAll('.mg-gallery-tab');
            var sections = document.querySelectorAll('.mg-gallery-section');
            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    tabs.forEach(function (t) { t.classList.remove('is-active'); });
                    tab.classList.add('is-active');
                    var key = tab.getAttribute('data-gal-tab');
                    sections.forEach(function (sec) {
                        if (key === 'all') {
                            sec.classList.remove('is-hidden');
                        } else {
                            sec.classList.toggle('is-hidden', sec.getAttribute('data-gal-section') !== key);
                        }
                    });
                });
            });
        });
    </script>
@endsection

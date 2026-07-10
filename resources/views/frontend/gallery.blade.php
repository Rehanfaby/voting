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
                @if(count($images))
                    <div class="mg-gallery-grid">
                        @foreach($images as $image)
                            <a href="{{ $image['url'] }}" class="mg-gallery-item" target="_blank" rel="noopener" @if($image['caption']) title="{{ $image['caption'] }}" @endif>
                                <img src="{{ $image['url'] }}" alt="{{ $image['caption'] ?: 'Gallery' }}" loading="lazy" decoding="async">
                                @if($image['caption'])
                                    <span class="mg-gallery-caption">{{ $image['caption'] }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-center" style="color:rgba(255,255,255,.7)">{{ trans('file.No gallery images yet') }}</p>
                @endif
            </div>
        </section>
    </main>

    <style>
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
    </style>

    <script>
        // Bind the lightbox after the theme's jQuery + magnificPopup have loaded
        // (they are included at the very bottom of the layout). Falls back to
        // opening the image in a new tab (target="_blank") when unavailable.
        window.addEventListener('load', function () {
            if (window.jQuery && jQuery.fn.magnificPopup) {
                jQuery('.mg-gallery-grid').magnificPopup({
                    delegate: 'a.mg-gallery-item',
                    type: 'image',
                    gallery: { enabled: true },
                    image: { titleSrc: 'title' },
                    mainClass: 'mfp-with-zoom',
                    closeOnContentClick: true
                });
            }
        });
    </script>
@endsection

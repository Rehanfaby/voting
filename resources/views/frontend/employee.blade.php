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
        <style>
            .ms-vote-modern { background: radial-gradient(1100px 500px at 50% -10%, rgba(240,169,59,.10), transparent 60%); }
            .ms-vote-modern .tab-content.ms-bg-2 { background: rgba(255,255,255,.04) !important; border:1px solid rgba(246,196,83,.28); border-radius:18px; box-shadow:0 18px 45px rgba(0,0,0,.35); overflow:hidden; padding:14px; }
            .ms-vote-modern .product__modal-img img { border-radius:14px; width:100%; object-fit:cover; }
            .ms-vote-modern .ms-product-modal-content { background: rgba(255,255,255,.04); border:1px solid rgba(246,196,83,.28); border-radius:18px; padding:34px 30px; box-shadow:0 18px 45px rgba(0,0,0,.35); }
            .ms-vote-modern .ms-product-price { display:inline-block; background:linear-gradient(135deg,#f0a93b,#e2562a); color:#fff !important; font-size:15px; font-weight:700; padding:8px 16px; border-radius:30px; margin:0; }
            .ms-vote-modern .ms-product-title2 a, .ms-vote-modern h2.ms-product-title2 { color:#fff !important; }
            .ms-vote-modern .ms-is-product-stock { color:rgba(255,255,255,.82); }
            .ms-vote-modern .ms-is-product-stock span { color:#f6c453; }
            .ms-vote-modern .product-quantity-form { display:inline-flex; align-items:center; border:1px solid rgba(246,196,83,.5); border-radius:30px; overflow:hidden; background:rgba(0,0,0,.2); }
            .ms-vote-modern .product-quantity-form .cart-minus, .ms-vote-modern .product-quantity-form .cart-plus { background:transparent; border:0; color:#f0a93b; width:44px; height:48px; font-size:15px; cursor:pointer; }
            .ms-vote-modern .product-quantity-form .cart-input { width:64px; height:48px; text-align:center; border:0; background:transparent; color:#fff; font-size:18px; font-weight:700; }
            .ms-vote-modern .ms-addto-cart-btn { background:linear-gradient(135deg,#f0a93b,#e2562a) !important; color:#fff !important; border:0; border-radius:30px; padding:14px 34px; font-weight:700; margin-left:16px; box-shadow:0 10px 24px rgba(226,86,42,.4); transition:transform .2s ease; }
            .ms-vote-modern .ms-addto-cart-btn:hover { transform:translateY(-2px); }
            .ms-vote-modern #payable-amount, .ms-vote-modern #payable-coin { color:#f0a93b !important; font-weight:800; }
            .ms-vote-modern .nav-tabs .nav-link { color:rgba(255,255,255,.7); }
            .ms-vote-modern .nav-tabs .nav-link.active { color:#f0a93b; border-color:transparent transparent #f0a93b; background:transparent; }
        </style>
        <!-- page title area start  -->
        <section class="page-title-area page-title-spacing p-relative zindex-1" data-background="assets/img/shop/shop-page-title.jpg">
            <div class="ms-overlay ms-overlay9 p-absolute zindex--1"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-11">
                        <h3 class="ms-page-title text-center">{{ $musician->name }}</h3>
                    </div>
                </div>
            </div>
        </section>
        <!-- page title area end  -->

        <!-- Products Area Start  -->
        <div class="ms-product-area ms-vote-modern pt-130 pb-110 p-relative">

            <div class="container">
                <div class="row mb-30">
                    <div class="col-lg-6">
                        <div class="product__modal-box product-dbox-grid mb-60">
                            <ul class="nav nav-tabs border-0" id="modalTab" role="tablist">
                            </ul>
                            <div class="tab-content br-15 ms-bg-2 d-flex align-items-center" id="modalTabContent">
                                <div class="tab-pane fade active show" id="nav1" role="tabpanel" aria-labelledby="nav1-tab">
                                    <div class="product__modal-img w-img">
                                        <img src="{{url('public/images/employee',$musician->image)}}" alt="{{ $musician->name }}" loading="lazy" decoding="async">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="ms-product-modal-content mb-60">
                            <div class="d-flex align-items-center justify-content-between mb-35 mr-40">
                                <h3 class="ms-product-price">{{ $general_setting->vote_price }} {{ $currency->code }} {{trans('file.for 1 vote')}}</h3>
                            </div>
                            <h2 class="ms-product-title2 mb-30">
                                <a href="{{ route('musician.data', $musician->id) }}">{{ $musician->name }}</a>
                            </h2>
                            @if($see_votes)
                                <div class="ms-is-product-stock mb-25">
                                    <i class="fa-solid fa-check"></i> {{trans('file.Total votes')}}:<span> {{ $vote_count ?? 0 }}</span>
                                </div>
                            @endif
                            <div class="ms-is-product-stock mb-25">
                                <span><i class="fa-solid fa-check"></i> {{trans('file.Do vote')}}</span>
                            </div>
                            <form action="{{ route('musician.vote') }}" method="post">
                                @csrf
                                <input name="musician_id" type="hidden" value="{{ $musician->id }}">
                                <div class="product__modal-form">
                                    <div class="product-quantity-cart ms-product-quantity-flex mb-30">
                                        <div class="product-quantity-form">
                                            <button class="cart-minus">
                                                <i class="far fa-minus"></i>
                                            </button>
                                            <input class="cart-input" name="vote" type="text" value="1">
                                            <button class="cart-plus">
                                                <i class="far fa-plus"></i>
                                            </button>
                                        </div>
                                        @if(\App\Helpers\VoteSettings::votingEnabled())  <button type="submit" class="ms-addto-cart-btn"><span>{{ trans('file.Vote') }}</span></button> @endif
                                    </div>
                                </div>
                            </form>
                            <div class="ms-is-product-stock mb-25 ms-product-title2">{{trans('file.Your payable amount is')}} : <span id="payable-amount" style="font-size: 30px">{{ $general_setting->vote_price }}</span> {{ $currency->code }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="ms-product-ddesc ms-border2">
                                <ul class="nav nav-tabs pt-30 mb-45" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="image-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">{{trans('file.Images')}}</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="audio-tab" data-bs-toggle="tab" data-bs-target="#audio-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">{{trans('file.Audios')}}</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="video-tab" data-bs-toggle="tab" data-bs-target="#video-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">{{trans('file.Videos')}}</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social-tab-pane" type="button" role="tab" aria-selected="false">{{ trans('file.Social Media') }}</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                        <div class="ms-product-dcontent">
                                            <div class="ms-product-text mb-60">
                                                <div class="row">
                                                    @foreach($images as $image)
                                                        <div class="col-md-2">
                                                            <img src="{{asset('public/employee/data/'.$image->file)}}" class="img-fluid">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
{{--                                    audio--}}
                                    <div class="tab-pane fade" id="audio-tab-pane" role="tabpanel" aria-labelledby="audio-tab" tabindex="0">
                                        <div class="ms-product-dcontent">
                                            <div class="ms-product-text mb-60">
                                                <div class="row">
                                                    @foreach($audios as $audio)
                                                        <div class="col-md-3">
                                                            <audio controls>
                                                                <source src="{{asset('public/employee/data/'.$audio->file)}}" type="audio/ogg">
                                                                <source src="{{asset('public/employee/data/'.$audio->file)}}" type="audio/mpeg">
                                                            </audio>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--                                    video--}}
                                    <div class="tab-pane fade" id="video-tab-pane" role="tabpanel" aria-labelledby="video-tab" tabindex="0">
                                        <div class="ms-product-dcontent">
                                            <div class="ms-product-text mb-60">
                                                <div class="row">
                                                    @foreach($videos as $video)
                                                        <div class="col-md-4">
                                                            <video width="320" height="240" controls>
                                                                <source src="{{asset('public/employee/data/'.$video->file)}}" type="video/mp4">
                                                                <source src="{{asset('public/employee/data/'.$video->file)}}" type="video/ogg">
                                                            </video>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Social media embeds --}}
                                    <div class="tab-pane fade" id="social-tab-pane" role="tabpanel" aria-labelledby="social-tab" tabindex="0">
                                        <div class="ms-product-dcontent">
                                            <div class="ms-product-text mb-60">
                                                @if($socialLinks->isEmpty())
                                                    <p class="text-muted">{{ trans('file.No social links yet') }}</p>
                                                @else
                                                <div class="mg-social-embed-grid">
                                                    @foreach($socialLinks as $social)
                                                        @include('partials.social-embed', ['item' => $social])
                                                    @endforeach
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <!-- Products Area End  -->



        <!-- Popular  area start -->
        <section class="mg-contestants-page pt-40 pb-80">
            <div class="container">
                <div class="mg-contestants-page__head text-center">
                    <p class="mg-contestants-page__eyebrow">{{ trans('file.Our Popular Contestants') }}</p>
                    <h2 class="mg-contestants-page__title">
                        <span class="animated-underline active">{{ trans('file.Vote') }}</span>
                        {{ trans('file.Your favourite Contestant') }}
                    </h2>
                </div>

                @php
                    $ranked = $contentants->sortByDesc(function ($m) use ($vote_counts) {
                        return $vote_counts[$m->id] ?? 0;
                    })->values();
                @endphp

                <div class="row mg-contestant-grid justify-content-center">
                    @foreach($ranked as $key => $contentant)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="mg-contestant-card">
                            <div class="mg-contestant-card__avatar">
                                <span class="mg-contestant-card__badge">{{ $key + 1 }}</span>
                                <a href="{{ route('musician.data', $contentant->id) }}">
                                    <img src="{{ \App\Helpers\ImageOptimizer::employeeImageUrl($contentant->image) }}" alt="{{ $contentant->name }}" width="160" height="160" loading="lazy" decoding="async">
                                </a>
                            </div>
                            <h3 class="mg-contestant-card__name">
                                <a href="{{ route('musician.data', $contentant->id) }}">{{ $contentant->name }}</a>
                            </h3>
                            @if($see_votes)
                            <span class="mg-contestant-card__votes">
                                <i class="fa fa-vote-yea"></i>
                                {{ number_format($vote_counts[$contentant->id] ?? 0) }} {{ trans('file.Votes') }}
                            </span>
                            @else
                            <a href="{{ route('musician.data', $contentant->id) }}" class="mg-contestant-card__cta">{{ trans('file.Vote For Me') }}</a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!-- Popular  area end -->


        <script>

            /*======================================
             Cart Quantity Js
            ========================================*/
            $(".cart-minus").on("click",function () {
                var $input = $(this).parent().find("input");
                var count = parseInt($input.val()) - 1;
                count = count < 1 ? 1 : count;
                if (count == undefined) {
                    return false;
                }
                var price = count * {{ $general_setting->vote_price }};
                $("#payable-amount").html(price);
                $input.val(count);
                $input.change();
                return false;
            });

            $(".cart-plus").on("click",function () {
                var $input = $(this).parent().find("input");
                var count = parseInt($input.val()) + 1;
                count = count < 1 ? 1 : count;
                count = count < 1 ? 1 : count;
                if (count == undefined) {
                    return false;
                }
                var price = count * {{ $general_setting->vote_price }};
                $("#payable-amount").html(price);
                $input.val(count);
                $input.change();
                return false;
            });


            {{--function priceCalculate(count, $input){--}}
            {{--    var price = count * {{ $general_setting->vote_price }};--}}
            {{--    $("#payable-amount").html(price);--}}
            {{--    var coin = count * {{ $general_setting->vote_coin }};--}}
            {{--    $("#payable-coin").html(coin);--}}
            {{--    $input.val(count);--}}
            {{--    $input.change();--}}
            {{--    return false;--}}
            {{--}--}}

            $(".cart-input").on("change keyup input",function () {
                var $input = $(this);
                var count = $(this).val();
                count = count < 1 ? 0 : count;
                if (count == 0) {
                    return false;
                }
                var price = count * {{ $general_setting->vote_price }};
                $("#payable-amount").html(price);
                $input.val(count);
                $input.change();
                return false;
            });

        </script>

    </main>
@endsection

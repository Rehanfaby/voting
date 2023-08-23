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
        <div class="ms-product-area pt-130 pb-110 p-relative">

            <div class="container">
                <div class="row mb-30">
                    <div class="col-lg-6">
                        <div class="product__modal-box product-dbox-grid mb-60">
                            <ul class="nav nav-tabs border-0" id="modalTab" role="tablist">
                            </ul>
                            <div class="tab-content br-15 ms-bg-2 d-flex align-items-center" id="modalTabContent">
                                <div class="tab-pane fade active show" id="nav1" role="tabpanel" aria-labelledby="nav1-tab">
                                    <div class="product__modal-img w-img">
                                        <img src="{{url('public/images/employee',$musician->image)}}" alt="product image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="ms-product-modal-content mb-60">
                            <div class="d-flex align-items-center justify-content-between mb-35 mr-40">
                                <h3 class="ms-product-price">{{ $general_setting->vote_price }} {{ $currency->code }} for 1 vote</h3>
                            </div>
                            <h2 class="ms-product-title2 mb-30">
                                <a href="$">{{ $musician->name }}</a>
                            </h2>
                            @if($see_votes)
                                <div class="ms-is-product-stock mb-25">
                                    @php
//                                        $start_date = date('Y-m-d', strtotime('last monday'));
//                                        $end_date = date('Y-m-d');

                                        $vote_count = \App\vote::where('status', true)
                                        ->where('musician_id', $musician->id)
//                                        ->whereDate('votes.created_at', '>=', $start_date)
//                                        ->whereDate('votes.created_at', '<=', $end_date)
                                        ->sum('vote');
                                    @endphp
                                    <i class="fa-solid fa-check"></i> Total votes:<span> {{ $vote_count }}</span>
                                </div>
                            @endif
                            <div class="ms-is-product-stock mb-25">
                                <span><i class="fa-solid fa-check"></i> Do vote</span>
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
                                        <button type="submit" class="ms-addto-cart-btn"><span>Vote</span></button>
                                    </div>
                                </div>
                            </form>
                            <div class="ms-is-product-stock mb-25 ms-product-title2">Your payable amount is : <span id="payable-amount" style="font-size: 30px">{{ $general_setting->vote_price }}</span> {{ $currency->code }}</div>
                            OR
                            <div class="ms-is-product-stock mt-25 ms-product-title2">Your payable coin is : <span id="payable-coin" style="font-size: 30px">{{ $general_setting->vote_coin }}</span> Beyond Coin</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="ms-product-ddesc ms-border2">
                                <ul class="nav nav-tabs pt-30 mb-45" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="image-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Images</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="audio-tab" data-bs-toggle="tab" data-bs-target="#audio-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Audios</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="video-tab" data-bs-toggle="tab" data-bs-target="#video-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Videos</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="shorts-tab" data-bs-toggle="tab" data-bs-target="#shorts-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Shorts</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="youtube-tab" data-bs-toggle="tab" data-bs-target="#youtube-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Youtube Videos</button>
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

                                    {{--                                    shorts--}}
                                    <div class="tab-pane fade" id="shorts-tab-pane" role="tabpanel" aria-labelledby="shorts-tab" tabindex="0">
                                        <div class="ms-product-dcontent">
                                            <div class="ms-product-text mb-60">
                                                <div class="row">
                                                    @foreach($shorts as $short)
                                                        <div class="col-md-3">
                                                            <iframe src="{{$short->file}}" width="200" height="400"></iframe>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{--                                    youtube--}}
                                    <div class="tab-pane fade" id="youtube-tab-pane" role="tabpanel" aria-labelledby="youtube-tab" tabindex="0">
                                        <div class="ms-product-dcontent">
                                            <div class="ms-product-text mb-60">
                                                <div class="row">
                                                    @foreach($youtubes as $youtube)
                                                        <div class="col-md-4">
                                                            <iframe src="{{$youtube->file}}" width="400" height="290"></iframe>
                                                        </div>
                                                    @endforeach
                                                </div>
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
        <section class="ms-popular__area pb-100 fix">
            <div class="container">
                <div class="row align-items-end mb-25 bdFadeUp">
                    <div class="col-xl-6 col-lg-6">
                        <div class="section__title-wrapper mb-40 bd-title-anim">
                            <span class="section__subtitle">Our Popular Contestants</span>
                            <h2 class="section__title msg_title">
                                <span class="animated-underline active">Vote</span> <br>
                                Your favourite Contestant
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
                                        @foreach($contentants as $contentant)
                                        <div class="swiper-slide">
                                            <div class="ms-popular__item p-relative mb-30">
                                                <div class="ms-popular__thumb">
                                                    <div class="ms-popular-overlay"></div>
                                                    <a href="{{ route('musician.data', $contentant->id) }}"><img src="{{url('public/images/employee',$contentant->image)}}" alt="popular band"></a>
                                                    <a href="{{ route('musician.data', $contentant->id) }}" class="ms-popular__link">
                                                        <span class="ms-popular-icon"><i class="fa-regular fa-arrow-right-long"></i></span>
                                                    </a>
                                                </div>
                                                <h4 class="ms-popular__title"><a href="{{ route('musician.data', $contentant->id) }}">
                                                        {{ $contentant->name }}
                                                    </a></h4>
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
                var coin = count * {{ $general_setting->vote_coin }};
                $("#payable-coin").html(coin);
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
                var coin = count * {{ $general_setting->vote_coin }};
                $("#payable-coin").html(coin);
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
                var coin = count * {{ $general_setting->vote_coin }};
                $("#payable-coin").html(coin);
                $input.val(count);
                $input.change();
                return false;
            });

        </script>

    </main>
@endsection

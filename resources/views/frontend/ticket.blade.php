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
                        <h3 class="ms-page-title text-center">{{ $ticket->name }}</h3>
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
                                        <img src="{{ url('public/images/product', $ticket->image) }}" alt="product image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="ms-product-modal-content mb-60">
                            <div class="d-flex align-items-center justify-content-between mb-35 mr-40">
                                <h3 class="ms-product-price">{{trans("file.Ticket Price")}}: {{ number_format($ticket->price, 2) }}</h3>
                                <h3 class="ms-product-date">{{trans("file.Event Day")}}: {{ $ticket->event_day }}</h3>
                            </div>
                            <h2 class="ms-product-title2 mb-30">
                                <a href="$">{{ $ticket->name }}</a>
                            </h2>
                            <h5 class="ms-product-title2 mb-30">Description: {!! $ticket->product_details !!}</h5>

                            <div class="ms-is-product-stock mb-25">
                                <span><i class="fa-solid fa-check"></i> {{trans('file.Purchase Ticket')}}</span>
                            </div>
                            <form action="{{ route('purchase.ticket') }}" method="post">
                                @csrf
                                <input name="ticket_id" type="hidden" value="{{ $ticket->id }}">
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
                                        <button type="submit" class="ms-addto-cart-btn"><span>{{trans('file.Purchase')}}</span></button>
                                    </div>
                                </div>
                            </form>
                            <div class="ms-is-product-stock mb-25 ms-product-title2">{{trans('file.Your payable amount is')}} : <span id="payable-amount" style="font-size: 30px">{{ $ticket->price }}</span> {{ $currency->code }}</div>
                            <!-- OR
                            <div class="ms-is-product-stock mt-25 ms-product-title2">{{trans('file.Your payable coin is')}} : <span id="payable-coin" style="font-size: 30px">{{ $general_setting->vote_coin }}</span> {{trans('file.Beyond Coin')}}</div> -->
                        </div>
                    </div>
                    
            </div>
        </div>
        <!-- Products Area End  -->



    </div>


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
                var price = count * $('.ms-product-price').text().replace(/[^0-9.-]+/g,"");
                $("#payable-amount").html(price);
                // var coin = count * {{ $general_setting->vote_coin }};
                // $("#payable-coin").html(coin);
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
                var price = count * $('.ms-product-price').text().replace(/[^0-9.-]+/g,"");
                console.log(price);
                $("#payable-amount").html(price);
                // var coin = count * {{ $general_setting->vote_coin }};
                // $("#payable-coin").html(coin);
                $input.val(count);
                $input.change();
                return false;
            });

            $(".cart-input").on("change keyup input",function () {
                var $input = $(this);
                var count = $(this).val();
                count = count < 1 ? 0 : count;
                if (count == 0) {
                    return false;
                }
                var price = count * $('.ms-product-price').text().replace(/[^0-9.-]+/g,"");
                $("#payable-amount").html(price);
                // var coin = count * {{ $general_setting->vote_coin }};
                // $("#payable-coin").html(coin);
                $input.val(count);
                $input.change();
                return false;
            });

        </script>

    </main>
@endsection

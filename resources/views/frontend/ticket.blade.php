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
            .product__modal-img {
                padding: 0;
                width: 500px;
                overflow: hidden;
                max-height: 350px;
                position: relative;
            }
            .product__modal-img img {
                max-width: 100%;
                border-radius: 10px;
                transition: transform 0.3s ease;
                object-fit: contain;
            }

        </style>
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
                            <div class="tab-content br-15 d-flex align-items-center" id="modalTabContent">
                                <div class="tab-pane fade active show" id="nav1" role="tabpanel" aria-labelledby="nav1-tab">
                                    <div class="product__modal-img w-img text-center mb-3">
                                        <?php $images = explode(",", $ticket->image)?>
                                        <img id="main-gallery-image" src="{{ url('public/images/product', $images[0]) }}" alt="product image" style="max-width:100%; max-height:500px; border-radius:10px;">
                                    </div>
                                    <div class="gallery-thumbnails d-flex justify-content-center gap-2">
                                        @foreach($images as $key => $image)
                                            <img src="{{ url('public/images/product', $image) }}" alt="product thumbnail" class="gallery-thumb" style="width:60px; height:60px; object-fit:cover; border-radius:6px; cursor:pointer; border:2px solid #eee;">
                                        @endforeach
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
                                            @if($ticket->price == 0)
                                                <input class="cart-input" name="vote" type="text" value="1" readonly>
                                            @else
                                                <button class="cart-minus">
                                                    <i class="far fa-minus"></i>
                                                </button>
                                                <input class="cart-input" name="vote" type="text" value="1" max="{{ $ticket->qty }}" step="1">
                                                <button class="cart-plus">
                                                    <i class="far fa-plus"></i>
                                                </button>
                                            @endif
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

            const container = document.querySelector('.product__modal-img');
            const image = document.getElementById('main-gallery-image');

            container.addEventListener('mousemove', function (e) {
                const rect = container.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;

                image.style.transformOrigin = `${x}% ${y}%`;
                image.style.transform = "scale(2)"; // Adjust zoom level here
            });

            container.addEventListener('mouseleave', function () {
                image.style.transformOrigin = "center center";
                image.style.transform = "scale(1)";
            });

            document.addEventListener('DOMContentLoaded', function() {
                const mainImg = document.getElementById('main-gallery-image');
                document.querySelectorAll('.gallery-thumb').forEach(function(thumb) {
                    thumb.addEventListener('click', function() {
                        mainImg.src = this.src;
                        document.querySelectorAll('.gallery-thumb').forEach(t => t.style.border = '2px solid #eee');
                        this.style.border = '2px solid #007bff';
                    });
                });
            });

            /*======================================
             Cart Quantity Js
            ========================================*/
            var maxQty = parseInt("{{ $ticket->qty }}"); // product max quantity
            var minQty = 1;

            // MINUS BUTTON
            $(".cart-minus").on("click", function () {
                var $input = $(this).parent().find("input");
                var current = parseInt($input.val());

                if (isNaN(current) || current < minQty) current = minQty;

                var count = current - 1;
                if (count < minQty) count = minQty;

                updatePrice(count);
                $input.val(count).change();

                return false;
            });

            // PLUS BUTTON
            $(".cart-plus").on("click", function () {
                var $input = $(this).parent().find("input");
                var current = parseInt($input.val());

                if (isNaN(current) || current < minQty) current = minQty;

                var count = current + 1;
                if (count > maxQty) count = maxQty;

                updatePrice(count);
                $input.val(count).change();

                return false;
            });

            // DIRECT INPUT (typing)
            $(".cart-input").on("change keyup input", function () {
                var $input = $(this);
                var current = parseInt($input.val());

                if (isNaN(current)) current = minQty;

                if (current < minQty) current = minQty;
                if (current > maxQty) current = maxQty;

                updatePrice(current);
                $input.val(current);

                return false;
            });

            // Update price function
            function updatePrice(count) {
                var price = count * $('.ms-product-price').text().replace(/[^0-9.-]+/g,"");
                $("#payable-amount").html(price);
            }


        </script>

    </main>
@endsection

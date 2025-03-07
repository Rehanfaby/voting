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
    <section class="page-title-area page-title-spacing p-relative zindex-1 " data-background="assets/img/bg/work-bg.jpg">
        <div class="ms-overlay ms-overlay9 p-absolute zindex--1"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-11">
                    <h3 class="ms-page-title text-center">{{trans("file.Password Change")}}</h3>
                </div>
            </div>
        </div>
    </section>
    <!-- page title area end  -->
@php
    $not_permitted = $not_permitted ?? null;
@endphp
    <!-- login Area Start Here  -->
    <section class="ms-login-area pb-50 pt-130">
        <div class="container">
            <div class="ms-maxw-510 mx-auto">
                @if($not_permitted != null)
                    <div class="alert alert-danger alert-dismissible text-center"></button>{{ $not_permitted }}</div>
                @endif
                <div class="ms-login-wrap text-center ms-login-space ms-bg-2">
                    <h3 class="ms-title4 mb-50">{{trans("file.Please enter your new password and confirm password!")}}</h3>
                    <form action="{{ route('shop.password.change') }}" method="post">
                        @csrf
                        <div class="ms-input2-box mb-25">
                            <input class="form-control" name="password" type="password" placeholder="New Password">
                        </div>
                        <div class="ms-input2-box mb-50">
                            <input class="form-control" name="confirm_password" type="password" placeholder="Re-Enter Password">
                        </div>
                        <div class="ms-submit-btn mb-40">
                            <button class="unfill__btn d-block w-100" type="submit">{{trans("file.Submit")}}
                                </button>
                        </div>
                        <div class="ms-divided-btn mb-45">
                            <span>{{trans("file.or")}}</span>
                        </div>
                        <div class="ms-not-account mb-35">
                            <p>{{trans("file.Don't have an account")}}? <a href="{{ route('user.signup') }}">{{trans("file.Sign Up")}}</a></p>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- login Area End Here  -->

    <!-- Partner Area Start Here  -->
    <div class="ms-partner-area fix pt-80 pb-130">
        <div class="container">
            <div class="swiper-container ms-partner-active">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="{{ asset('public/frontend/images/partner-partner-01.png') }}" alt="partner image">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('public/frontend/images/partner-partner-02.png') }}" alt="partner image">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('public/frontend/images/partner-partner-03.png') }}" alt="partner image">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('public/frontend/images/partner-partner-04.png') }}" alt="partner image">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('public/frontend/images/partner-partner-05.png') }}" alt="partner image">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('public/frontend/images/partner-partner-04.png') }}" alt="partner image">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('public/frontend/images/partner-partner-01.png') }}" alt="partner image">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('public/frontend/images/partner-partner-02.png') }}" alt="partner image">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('public/frontend/images/partner-partner-03.png') }}" alt="partner image">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('public/frontend/images/partner-partner-04.png') }}" alt="partner image">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('public/frontend/images/partner-partner-05.png') }}" alt="partner image">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('public/frontend/images/partner-partner-04.png') }}" alt="partner image">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Partner Area End Here  -->

</main>
@endsection

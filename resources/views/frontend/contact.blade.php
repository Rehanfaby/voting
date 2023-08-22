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

        <!-- Join Area Start Here  -->
        <section class="ms-join-area pb-60 pt-130 p-relative">
            <div class="ms-join-position p-absolute text-center">
                <h2 class="ms-title2 white-text mb-50">Contact With us</h2>
                <div class="ms-banner3-item-wrap ms-join-img-grid">
                    <div class="ms-banner3-item d-none d-md-block">
                        <div class="ms-banner3-img1 ms-opacity-2 p-relative m-img">
                            <div class="ms-overlay2 p-absolute"></div>
                            <img src="{{asset('public/frontend/images/banner-banner-thumb-04.png') }}" alt="banner image">
                        </div>
                    </div>
                    <div class="ms-banner3-item d-none d-md-block">
                        <div class="ms-banner3-img2 m-img p-relative">
                            <img src="{{asset('public/frontend/images/banner-banner-thumb-05.png') }}" alt="banner image">
                        </div>
                    </div>
                    <div class="ms-banner3-item d-none d-md-block">
                        <div class="ms-banner3-img3 ms-opacity-2 p-relative m-img">
                            <div class="ms-overlay2 p-absolute"></div>
                            <img src="{{asset('public/frontend/images/banner-banner-thumb-06.png') }}" alt="banner image">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="ms-join-wrap ms-join-space mb-70 ms-bg-2">
                            <h3 class="white-text ms-title3 mb-60">Enter Your NUmber</h3>
                            <form method="post" action="{{ route('contact.message') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="ms-input-box style-2">
                                            <label>Contact Number</label>
                                            <input type="number" name="number" placeholder="237" value="237">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="ms-input-box style-2 mt-2">
                                            <button class="unfill__btn" type="submit" style="margin-top: 2vw">Contact Us</button>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="ms-submit-btn mt-70">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Join Area End Here  -->

    </main>
@endsection

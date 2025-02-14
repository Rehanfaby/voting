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
        <!-- About Area Start Here  -->
        <section class="ms-about-area fix">
            <div class="ms-about-bg include__bg p-relative zindex-1 pt-50 pb-50" data-background="{{ url('public/public/frontend/images/bottom-banner-en.jpeg') }}" style="background-image: {{ url('public/public/frontend/images/bottom-banner-en.jpeg') }}">
                <div class="ms-overlay ms-overlay5 p-absolute zindex--1"></div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-10 col-lg-10">
                            <div class="ms-about-content text-center">
                                <h2 class="ms-title2 white-text mb-30 bd-title-anim" style="perspective: 400px;"><div style="display: block; text-align: center; position: relative; translate: none; rotate: none; scale: none; transform-origin: 538px 30px; transform: translate3d(0px, 0px, 0px); opacity: 1;">{{trans('file.About Us')}} </div></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- About Area End Here  -->

        <!-- Why Choose Us Area Start Here  -->
        <section class="ms-choose-area pt-125 pb-105">
            <div class="container">
                <div class="row  mb-25 bdFadeUp" style="translate: none; rotate: none; scale: none; opacity: 1; transform: translate(0px, 0px);">

                    <div class="col-lg-6">
                        <div class=" mb-40">
                            <h2 class="section__title mb-35 bd-title-anim" style="perspective: 400px;"><div  transform-origin: 373px 24px; transform: translate3d(0px, 0px, 0px); opacity: 1;">{{trans('file.Introduction')}}:</div></h2>
                            <p>
                                {{trans("file.Introducing The Mulema Gospel Talent Show Cameroons Ultimate Gospel Talent Competition Are you ready to witness the next generation of Gospel superstars The Mulema Gospel Talent Show is a thrilling televised competition dedicated to discovering and elevating Cameroons finest Gospel talents This is more than just a show its a movement to showcase raw talent inspire hearts and spread the message of faith through music With a rigorous journey from preselections to grand prime shows in Yaoundé the best voices will rise to the top winning hearts and transforming lives Dont miss out on this life-changing experience Mulema Gospel is where talent meets divine purpose")}}.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="ms-event-play d-inline-block p-relative mb-40">
                            <img src="{{ asset('public/public/frontend/images/bottom-banner-en.jpeg') }}" alt="event img" height="350px" style="border-radius: 15%;">
                        </div>
                    </div>



         


            </div>
        </section>
        <!-- Why Choose Us Area End Here  -->


    </main>
@endsection

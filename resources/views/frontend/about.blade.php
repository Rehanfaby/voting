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
            <div class="ms-about-bg include__bg p-relative zindex-1 pt-50 pb-50" data-background="{{ url('public/frontend/images/sound-bg.png') }}" style="background-image: {{ url('public/frontend/images/sound-bg.png') }}">
                <div class="ms-overlay ms-overlay5 p-absolute zindex--1"></div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-10 col-lg-10">
                            <div class="ms-about-content text-center">
                                <h2 class="ms-title2 white-text mb-30 bd-title-anim" style="perspective: 400px;"><div style="display: block; text-align: center; position: relative; translate: none; rotate: none; scale: none; transform-origin: 538px 30px; transform: translate3d(0px, 0px, 0px); opacity: 1;">About Us </div></h2>
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
                            <h2 class="section__title mb-35 bd-title-anim" style="perspective: 400px;"><div  transform-origin: 373px 24px; transform: translate3d(0px, 0px, 0px); opacity: 1;">Introduction:</div></h2>
                            <p>
                                Beyond the Talent Show is an exciting music competition set to take place in Bamenda,
                                Cameroon, from July 1st to August 2023. This innovative event aims to showcase and
                                celebrate the rich musical talent of the region while providing a platform for aspiring
                                musicians to shine. Beyond the Talent Show goes beyond the conventional talent show
                                format by incorporating elements of mentorship, cultural exchange, and professional
                                development, making it a truly unique and transformative experience for participants and
                                audiences alike.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="ms-event-play d-inline-block p-relative mb-40">
                            <img src="{{ asset('public/frontend/images/event-event-bg-2.png') }}" alt="event img" height="350px" style="border-radius: 15%;">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class=" mb-40">
                            <h2 class="section__title mb-35 bd-title-anim" style="perspective: 400px;"><div style="display: block; text-align: start; position: relative; translate: none; rotate: none; scale: none; transform-origin: 373px 24px; transform: translate3d(0px, 0px, 0px); opacity: 1;">Competition Format:</div></h2>
                            <p>
                                The competition will span over several weeks, featuring multiple rounds designed to test the
                                contestants' musical abilities, creativity, and stage presence. The diverse genres of music,
                                including Afrobeat, Makossa, Bikutsi, and Hip‐hop, among others, will be embraced, ensuring
                                a vibrant and inclusive showcase of Cameroon's musical heritage.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class=" mb-40">
                            <h2 class="section__title mb-35 bd-title-anim" style="perspective: 400px;"><div style="display: block; text-align: start; position: relative; translate: none; rotate: none; scale: none; transform-origin: 373px 24px; transform: translate3d(0px, 0px, 0px); opacity: 1;">Auditions and Selection:</div></h2>
                            <p>
                                Beyond the Talent Show will begin with open auditions, where talented musicians from
                                Bamenda and surrounding regions will have the opportunity to demonstrate their skills. A
                                panel of renowned music industry professionals, including local and international artists,
                                producers, and experts, will carefully assess the auditions and select the most promising
                                contestants
                                to
                                advance
                                to
                                the
                                next
                                stages
                                of
                                the
                                competition.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class=" mb-40">
                            <h2 class="section__title mb-35 bd-title-anim" style="perspective: 400px;"><div style="display: block; text-align: start; position: relative; translate: none; rotate: none; scale: none; transform-origin: 373px 24px; transform: translate3d(0px, 0px, 0px); opacity: 1;">Mentorship and Workshops:</div></h2>
                            <p>
                                One of the unique aspects of Beyond the Talent Show is its focus on mentorship and
                                professional development. Contestants who progress through the competition will have the
                                invaluable opportunity to receive guidance and mentorship from established musicians and
                                industry professionals. These mentors will share their expertise, provide constructive
                                feedback, and offer insights into the music industry, enhancing the contestants' skills and
                                confidence.<br>
                                In addition to mentorship, the competition will feature workshops and masterclasses led by
                                industry experts like Oriel, Cal Keys, Nathan Bass, Didi Drums, Ernest Melody, Sir Keys and
                                more. These sessions will cover various aspects of music production, songwriting, stage
                                performance, and music marketing, equipping the contestants with a comprehensive skill set
                                and empowering them to excel in their musical careers.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class=" mb-40">
                            <h2 class="section__title mb-35 bd-title-anim" style="perspective: 400px;"><div style="display: block; text-align: start; position: relative; translate: none; rotate: none; scale: none; transform-origin: 373px 24px; transform: translate3d(0px, 0px, 0px); opacity: 1;">Cultural Exchange and Collaboration:</div></h2>
                            <p>
                                Beyond the Talent Show aims to foster cultural exchange by providing a platform for
                                musicians from diverse backgrounds to collaborate and create unique musical experiences.
                                Throughout the competition, contestants will have opportunities to work together, blending
                                their individual styles and influences to create new and exciting music. This collaboration will
                                not only enrich the competition but also promote unity and appreciation for Cameroon's
                                diverse musical heritage.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class=" mb-40">
                            <h2 class="section__title mb-35 bd-title-anim" style="perspective: 400px;"><div style="display: block; text-align: start; position: relative; translate: none; rotate: none; scale: none; transform-origin: 373px 24px; transform: translate3d(0px, 0px, 0px); opacity: 1;">Grand Finale and Prizes:</div></h2>
                            <p>
                                The competition will culminate in a spectacular grand finale, where the remaining
                                contestants will showcase their talent and compete for the top honors. The finale will be a
                                grand celebration of music, featuring electrifying performances from captivating stage
                                production, and guest appearances by renowned artists (The Prophetic Minstrel).
                                The winners of Beyond the Talent Show will receive a range of prizes designed to support
                                their musical journeys. These prizes will include , financial Rewards (500,000frs) recording
                                contracts, music production opportunities, performance bookings, mentorship packages,
                                and valuable connections within the music industry. Additionally, the exposure gained
                                through the competition will open doors to new opportunities and propel the winners
                                towards successful music careers.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class=" mb-40">
                            <h2 class="section__title mb-35 bd-title-anim" style="perspective: 400px;"><div style="display: block; text-align: start; position: relative; translate: none; rotate: none; scale: none; transform-origin: 373px 24px; transform: translate3d(0px, 0px, 0px); opacity: 1;">Conclusion:</div></h2>
                            <p>
                                Beyond the Talent Show is not just a music competition; it is a transformative experience
                                that aims to elevate the music scene in Bamenda, Cameroon. By providing a platform for
                                aspiring musicians to showcase their talent, receive mentorship, and engage in cultural
                                exchange, this event will contribute to the growth and development of the local music
                                industry. Beyond the Talent Show promises to be a remarkable celebration of music, talent,
                                and the vibrant culture of Cameroon.
                            </p>
                        </div>
                    </div>

            </div>
        </section>
        <!-- Why Choose Us Area End Here  -->


    </main>
@endsection

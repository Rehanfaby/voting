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
        <section class="ms-join-area pb-60 pt-130 p-relative" style="background-image: url('{{ asset('public/frontend/images/ai.png') }}'); background-size: cover; background-position: center;">
            <div class="ms-join-position p-absolute text-center">
                <h2 class="ms-title2 white-text mb-50">{{trans('file.Contact Us')}}</h2>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="ms-join-wrap ms-join-space mb-70 ms-bg-2">
                            <h3 class="white-text ms-title3 mb-60">{{trans('file.Contact Us')}}</h3>
                            <form method="post" action="{{ route('contact.message') }}">
                                @csrf
                                <div class="row">
                                    <!-- Contact Number Field -->
                                    <div class="col-lg-6">
    <div class="ms-input-box style-2">
        <label>{{trans('file.Contact Number')}}</label>
        <input type="number" name="number" placeholder="237" value="237">
    </div>
</div>

                                    <!-- Message Field -->
                                    <div class="col-lg-6">
                                        <div class="ms-input-box style-2">
                                            <label>{{trans('file.Message')}}</label>
                                            <textarea name="message" placeholder="Your message here..." rows="4"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="ms-input-box style-2 mt-2">
                                            <button class="unfill__btn" type="submit" style="margin-top: 2vw">{{trans('file.Contact Us')}}</button>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mt-4 text-center">
                                        <p>{{trans('file.Contact Us')}}</p>
                                        <!-- Social Media Links -->
                                        <a href="https://www.facebook.com/share/1Ej9KPRMN4/?mibextid=LQQJ4d" target="_blank" class="social-link">
                                            <i class="fab fa-facebook"></i> Facebook
                                        </a>
                                        <a href="https://www.instagram.com/mulemagospeltalent?igsh=MWZ6MGJ1YXRuMnZ2Yw==" target="_blank" class="social-link">
                                            <i class="fab fa-instagram"></i> Instagram
                                        </a>
                                        <a href="https://www.tiktok.com/@mulemagospel?_t=ZN-8t8d1fyW4m6&_r=1" target="_blank" class="social-link">
                                            <i class="fab fa-tiktok"></i> TikTok
                                        </a>
                                        <a href="mailto:info@mulemagc.com?subject=Inquiry&body=Hello,%20I%20would%20like%20to%20know%20more%20about..." class="social-link">
                                            <i class="fas fa-envelope"></i> Email
                                        </a>
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

@section('styles')
    <style>
        /* Style for Social Media Links */
        .social-link {
            font-size: 2rem; /* Default size */
            margin: 10px;
            color: white;
            text-decoration: none;
            padding: 10px;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            color: #ff7e5f; /* Change to a contrasting color on hover */
        }

        /* Make the social links responsive */
        @media (max-width: 768px) {
            .social-link {
                font-size: 1.5rem; /* Smaller font size for medium screens */
            }
        }

        @media (max-width: 480px) {
            .social-link {
                font-size: 1.2rem; /* Even smaller for small screens */
            }
        }
    </style>
@endsection

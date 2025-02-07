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
            .select-role{
                width: 100%;
                background: var(--clr-bg-4);
                border: 1px solid var(--clr-border-3);
                border-radius: 32.5px;
                height: 65px;
                color: var(--clr-text-8);
                padding: 0 30px;
                -webkit-transition: all 0.3s ease-out 0s;
                -moz-transition: all 0.3s ease-out 0s;
                -ms-transition: all 0.3s ease-out 0s;
                -o-transition: all 0.3s ease-out 0s;
                transition: all 0.3s ease-out 0s;
                resize: none;
            }
            .hide {
                display: none;
            }
            .video-file {
                transition: opacity 0.3s ease-in-out;
                opacity: 1; /* Set initial state to visible */
            }

            .video-file.hide {
                opacity: 0; /* Set the element to be hidden with opacity */
                pointer-events: none; /* Optional: prevent interactions when hidden */
            }
        </style>
    <!-- page title area start  -->
    <section class="page-title-area page-title-spacing p-relative zindex-1 " data-background="assets/img/bg/work-bg.jpg">
        <div class="ms-overlay ms-overlay9 p-absolute zindex--1"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-11">
                    <h3 class="ms-page-title text-center">{{trans("file.Sign Up")}}</h3>
                </div>
            </div>
        </div>
    </section>
    <!-- page title area end  -->

    <!-- login Area Start Here  -->
    <section class="ms-login-area pb-50 pt-130">
        <div class="container">
            <div class="ms-maxw-510 mx-auto">
                <div class="ms-login-wrap text-center ms-login-space ms-bg-2">
                    <h3 class="ms-title4 mb-50">{{trans("file.Create Your account")}}</h3>
                    <form action="{{ route('register') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="ms-input2-box mb-25">
                            <input type="text" placeholder="{{trans("file.Name")}}" name="name" required>
                            @if ($errors->has('name'))
                                <p>
                                    <strong>{{ $errors->first('name') }}</strong>
                                </p>
                            @endif
                        </div>
                        <div class="ms-input2-box mb-25">
                            <input type="email" placeholder="{{trans("file.Email")}}" name="email" required>
                            @if ($errors->has('email'))
                                <p>
                                    <strong>{{ $errors->first('email') }}</strong>
                                </p>
                            @endif
                        </div>
                        <div class="ms-input2-box mb-25">
                            <input type="text" placeholder="{{trans("file.Phone number")}}" name="phone_number" value="+237" required>
                        </div>
                        <div class="ms-input2-box mb-50">
                            <input type="password" placeholder="{{trans("file.Password")}}" name="password" required>
                            @if ($errors->has('password'))
                                <p>
                                    <strong>{{ $errors->first('password') }}</strong>
                                </p>
                            @endif
                        </div>
                        <div class="ms-input2-box mb-50">
                            <input type="password" placeholder="{{trans("file.Confirm Password")}}" name="password_confirmation" required>
                        </div>
                        <div class="ms-input2-box mb-50">
                            <select class="select-role" name="role_id">
                                <option value="3">{{trans("file.Voter")}}</option>
                                <option value="2">{{trans("file.Contestant")}}</option>
                            </select>
                        </div>
                        <div class="ms-input2-box mb-50 video-file hide">
                            <sub>{{trans("file.Video less than 10mb")}}</sub>
                            <input type="file" placeholder="" name="file" accept="video/mp4,video/avi,video/mkv,video/webm,video/flv">
                        </div>
                        <div class="ms-submit-btn mb-40">
                            <button class="unfill__btn d-block w-100" type="submit">{{trans("file.Create Account")}}</button>
                        </div>
                        <div class="ms-divided-btn mb-45">
                            <span>or</span>
                        </div>
                        <div class="ms-not-account mb-35">
                            <p>{{trans("file.Already have an account")}}? <a href="{{ route('user.login') }}">{{trans("file.Log in")}}</a></p>
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

        <script>
            $('.select-role').change(function() {
                if ($('.select-role').val() == 2) {
                    $('.video-file').removeClass('hide');
                } else {
                    $('.video-file').addClass('hide');
                }
            });

            const maxFileSize = 10 * 1024 * 1024; // 10MB in bytes

            // Listen for file input change
            $("input[type='file']").on('change', function (event) {
                const file = event.target.files[0];

                // Check if a file is selected
                if (file) {
                    // Get the file size in bytes
                    const fileSize = file.size;

                    // Compare the file size with the max size
                    if (fileSize > maxFileSize) {
                        alert("File is too large! Maximum size is 10MB.");
                        $(this).val(''); // Clear the selected file
                    } else {
                        // Optionally show the file size or proceed with form submission
                        console.log("File size is within the limit.");
                    }
                }
            });
        </script>
</main>
@endsection

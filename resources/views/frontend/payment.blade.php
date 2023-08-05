@extends('frontend.layout.main')
@section('content')

    @if($errors->has('name'))
        <div class="alert alert-danger alert-dismissible text-center">{{ $errors->first('name') }}</div>
    @endif
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center">{{ session()->get('message') }}</div>
    @endif
    @if(session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center">{{ session()->get('not_permitted') }}</div>
    @endif
    <main>
        <!-- page title area start  -->
        <section class="page-title-area page-title-spacing p-relative zindex-1" data-background="assets/img/shop/shop-page-title.jpg">
            <div class="ms-overlay ms-overlay9 p-absolute zindex--1"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-11">
                        <h3 class="ms-page-title text-center">Vote payment</h3>
                    </div>
                </div>
            </div>
        </section>
        <!-- page title area end  -->

        <!-- Products Area Start  -->
        <div class="ms-product-area pt-50 pb-70 p-relative">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="ms-product-table-wrap mb-60">
                            <div class="ms-product-table mb-50">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>Musician</th>
                                        <th>Votes</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="ms-product-name-flex">
                                            <img src="{{ url('public/images/employee', $musician->image) }}" alt="musician">
                                            <span>{{ $musician->name }}</span>
                                        </td>
                                        <td>{{ $data['vote'] }}</td>
                                        <td>{{ $data['vote'] * $general_setting->vote_price }} {{ $currency->code }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="ms-maxw-510">
                                <div class="ms-login-wrap text-center ms-login-space ms-bg-2">
                                    <h3 class="ms-title4 mb-50">Pay By Momo</h3>
                                    <div class="text-center message-status"></div>
                                    <form>
                                        @php
                                        $user = \Illuminate\Support\Facades\Auth::user();
                                        @endphp
{{--                                        @if(!$user)--}}
{{--                                            <div class="ms-input2-box mb-25">--}}
{{--                                                <input type="text" name="name" placeholder="Name">--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
                                        <div class="ms-input2-box mb-25">
                                            @if(!$user)
                                                <input type="text" name="phone" required placeholder="Phone no" value="+237" id="inputField">
                                            @else
                                                <input type="text" name="phone" required placeholder="Phone no" value="{{ $user->phone }}" id="inputField">
                                            @endif
                                            <input type="hidden" name="musician_id" value="{{ $musician->id }}">
                                            <input type="hidden" name="vote" value="{{ $data['vote'] }}">
                                            <input type="hidden" name="amount" value="{{ $data['vote'] * $general_setting->vote_price }}">
                                        </div>

{{--                                        @if(!$user)--}}
{{--                                            <div class="ms-input2-box mb-25">--}}
{{--                                                <input type="password" name="password" placeholder="Password">--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
                                        <div class="ms-submit-btn mb-40">
                                            <button id="payment-button" class="unfill__btn d-block w-100">Pay {{ $data['vote'] * $general_setting->vote_price }} {{ $currency->code }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                    </div>
{{--                    <div class="col-md-6 col-sm-12">--}}
{{--                        <div class="ms-maxw-510">--}}
{{--                            <div class="ms-login-wrap text-center ms-login-space ms-bg-2">--}}
{{--                                <h3 class="ms-title4 mb-50">Pay By Beyond Coin</h3>--}}
{{--                                @if($user)--}}
{{--                                    <p>You have total {{ $user->beyond_coin }} Beyond Coins.</p>--}}
{{--                                <form>--}}
{{--                                    <input type="hidden" name="musician_id" value="{{ $musician->id }}">--}}
{{--                                    <input type="hidden" name="vote" value="{{ $data['vote'] }}">--}}
{{--                                    <input type="hidden" name="amount_coin" value="{{ $data['vote'] * $general_setting->vote_coin }}">--}}
{{--                                    <div class="ms-submit-btn mb-40">--}}
{{--                                        @if($user->beyond_coin >= $data['vote'] * $general_setting->vote_coin)--}}
{{--                                            <button id="coin-button" class="unfill__btn d-block w-100">Pay {{ $data['vote'] * $general_setting->vote_coin }} Beyond Coin</button>--}}
{{--                                        @else--}}
{{--                                            <span class="unfill__btn d-block w-100 pt-3">You don't have enough Beyond Coin</span>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                </form>--}}
{{--                                @else--}}
{{--                                    For use Beyond Coins please <a href="{{ route('user.login') }}" class="link-light">Login</a>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="col-md-6 col-sm-12">
                        <div class="ms-maxw-510">
                            <div class="ms-login-wrap text-center ms-login-space ms-bg-2">
                                <h3 class="ms-title4 mb-50">Pay By Beyond Coin</h3>
                                <div class="text-center message-status-coin"></div>
                                <form>
                                    <div class="ms-input2-box mb-25">
                                        @if(!$user)
                                            <input type="text" name="phone_number" required placeholder="Phone no" value="+237" id="inputField">
                                        @else
                                            <input type="text" name="phone_number" required placeholder="Phone no" value="{{ $user->phone }}" id="inputField">
                                        @endif
                                    </div>
                                    <div class="ms-input2-box mb-25">
                                        <input type="text" name="code" required placeholder="Beyond Coin Code"  id="inputField">
                                        <input type="hidden" name="musician_id" value="{{ $musician->id }}">
                                        <input type="hidden" name="vote_coin" value="{{ $data['vote'] }}">
                                        <input type="hidden" name="amount_coin" value="{{ $data['vote'] * $general_setting->vote_coin }}">
                                    </div>
                                     <div class="ms-submit-btn mb-40">
                                        <button id="coin-button" class="unfill__btn d-block w-100">Pay {{ $data['vote'] * $general_setting->vote_coin }} Beyond Coin</button>
                                    </div>
                                </form>

                                <p>If you don't have coins you can contact to <br>{{ $general_setting->developed_by }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Products Area End  -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const inputField = document.getElementById('inputField');

                inputField.addEventListener('input', function(event) {
                    const maxLength = 13;
                    const currentValue = event.target.value;

                    if (currentValue.length > maxLength) {
                        event.target.value = currentValue.slice(0, maxLength);
                    }
                });
            });

            $('#payment-button').on('click', function (event) {
                event.preventDefault();
                $('#preloader').show();
                $.ajax({
                    url: "{{ route('musician.vote.payment') }}",
                    type: "GET",
                    data: {
                        // Additional data to send in the AJAX request if needed
                        name : $('input[name="name"]').val() ?? null,
                        password : $('input[name="password"]').val() ?? null,
                        phone : $('input[name="phone"]').val(),
                        musician_id : $('input[name="musician_id"]').val(),
                        vote : $('input[name="vote"]').val(),
                        amount : $('input[name="amount"]').val(),
                    },
                    success: function (response) {
                        $('#preloader').hide();
                        var message = '<div class="alert alert-success">'+response+'</div>';
                        $('.message-status-coin').html('');
                        $('.message-status').html(message);
                    },
                    error: function (xhr, status, error) {
                        // Handle the error response, if any
                        $('#preloader').hide();
                        var message = '<div class="alert alert-danger">'+response+'</div>';
                        $('.message-status-coin').html('');
                        $('.message-status').html(message);
                    }
                });
            });

            $('#coin-button').on('click', function (event) {
                event.preventDefault();
                $('#preloader').show();
                $.ajax({
                    url: "{{ route('musician.vote.payment.coin') }}",
                    type: "GET",
                    data: {
                        musician_id : $('input[name="musician_id"]').val(),
                        vote : $('input[name="vote_coin"]').val(),
                        amount : $('input[name="amount_coin"]').val(),
                        phone_number : $('input[name="phone_number"]').val(),
                        code : $('input[name="code"]').val(),
                    },
                    success: function (response) {
                        $('#preloader').hide();
                        var message = '<div class="alert alert-success">'+response+'</div>';
                        $('.message-status').html('');
                        $('.message-status-coin').html(message);
                    },
                    error: function (xhr, status, error) {
                        // Handle the error response, if any
                        $('#preloader').hide();
                        var message = '<div class="alert alert-danger">'+response+'</div>';
                        $('.message-status').html('');
                        $('.message-status-coin').html(message);
                    }
                });
            });
        </script>
    </main>
@endsection

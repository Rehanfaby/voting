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
        <style>
            .select-identity{
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
        </style>
        <!-- page title area start  -->
        <section class="page-title-area page-title-spacing p-relative zindex-1" data-background="assets/img/shop/shop-page-title.jpg">
            <div class="ms-overlay ms-overlay9 p-absolute zindex--1"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-11">
                        <h3 class="ms-page-title text-center">{{trans("file.Ticket payment")}}</h3>
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
                                        <th>{{trans("file.Ticket")}}</th>
                                        <th>{{trans("file.Qty")}}</th>
                                        <th>{{trans("file.Amount")}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="ms-product-name-flex">
                                            <img src="{{ url('public/images/product', $ticket->image) }}" alt="ticket image">
                                            <span>{{ $ticket->name }}</span>
                                        </td>
                                        <td>{{ $data['vote'] }}</td>
                                        <td>{{ $data['vote'] * $ticket->price }} {{ $currency->code }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="ms-maxw-510">
                                <div class="ms-login-wrap text-center ms-login-space ms-bg-2">
                                    <h3 class="ms-title4 mb-50">{{trans("file.Pay By MOMO or OM")}}</h3>
                                    <sub>{{ trans('file.For free tickets use MOMO or OM method') }}</sub>
                                    <div class="text-center message-status"></div>
                                    <form id="" method="post" action="{{ route('ticket.payment') }}">
                                        @csrf
                                        @php
                                        $user = \Illuminate\Support\Facades\Auth::user();
                                        @endphp
                                        <div class="ms-input2-box mb-25">
                                            @if(!$user)
                                                <input type="text" name="name" required placeholder="{{trans('file.Name')}}" >
{{--                                                <input type="text" name="email" required placeholder="email">--}}
{{--                                                <select class="select-identity" name="identity_type" id="identity_type" required>--}}
{{--                                                    <option value="1">{{ __('NIC') }}</option>--}}
{{--                                                    <option value="2">{{ __('Student ID') }}</option>--}}
{{--                                                    <option value="3">{{ __('Passport') }}</option>--}}
{{--                                                    <option value="4">{{ __('Others') }}</option>--}}
{{--                                                </select>--}}
{{--                                                <input type="text" name="identity_number" id="identity_number" class="mt-2" placeholder="{{ __('Identity Number') }}">--}}
{{--                                                <input type="text" name="student_number" id="student_number" class="mt-2" placeholder="{{ __('Student Number') }}" style="display:none;">--}}
{{--                                                <input type="text" name="passport_number" id="passport_number" class="mt-2" placeholder="{{ __('Passport Number') }}" style="display:none;">--}}
{{--                                                <input type="text" name="identity_number" id="other_number" class="mt-2" placeholder="{{ __('Others') }}" style="display:none;">--}}

                                                <input type="text" name="phone" required placeholder="{{trans('file.Momo Number')}}: +237675321739" id="inputField">
                                                <input type="text" name="whatsapp_number" required placeholder="{{trans('file.Whatsapp number')}}: +237675321739">
                                            @else

                                                <input type="text" name="name" required placeholder="{{trans('file.Name')}}" value="{{ $user->name }}">
{{--                                                <input type="text" name="email" required placeholder="email" value="{{ $user->email }}">--}}
{{--                                                <select class="select-identity" name="identity_type" id="identity_type" required>--}}
{{--                                                    <option value="1">{{ __('NIC') }}</option>--}}
{{--                                                    <option value="2">{{ __('Student ID') }}</option>--}}
{{--                                                    <option value="3">{{ __('Passport') }}</option>--}}
{{--                                                    <option value="4">{{ __('Others') }}</option>--}}
{{--                                                </select>--}}
{{--                                                <input type="text" name="identity_number" id="identity_number" class="mt-2" placeholder="{{ __('Identity Number') }}">--}}
{{--                                                <input type="text" name="student_number" id="student_number" class="mt-2" placeholder="{{ __('Student Number') }}" style="display:none;">--}}
{{--                                                <input type="text" name="passport_number" id="passport_number" class="mt-2" placeholder="{{ __('Passport Number') }}" style="display:none;">--}}
{{--                                                <input type="text" name="identity_number" id="other_number" class="mt-2" placeholder="{{ __('Others') }}" style="display:none;">--}}

                                                <input type="text" name="phone" required placeholder="{{trans('file.Momo Number')}}: +237675321739" id="inputField" value="{{ $user->phone }}">
                                                <input type="text" name="whatsapp_number" required placeholder="{{trans('file.Whatsapp number')}}: +237675321739" value="{{ $user->whatsapp_number ?? $user->phone }}">
                                            @endif
                                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                            <input type="hidden" name="qty" value="{{ $data['vote'] }}">
                                            <input type="hidden" name="amount" value="{{ $data['vote'] * $ticket->price }}">
                                        </div>
                                        <div class="ms-submit-btn mb-40">
                                            <button id="payment-button" class="unfill__btn d-block w-100">{{trans("file.Pay")}} {{ $data['vote'] * $ticket->price }} {{ $currency->code }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="ms-maxw-510">
                            <div class="ms-login-wrap text-center ms-login-space ms-bg-2">
                                <h3 class="ms-title4 mb-50">{{trans("file.Pay By Visa or Master Card")}}</h3>
                                <sub>{{ trans('file.Minimum amount for stripe is') }} {{ env('STRIPE_MINIMUM_AMOUNT') }}</sub>
                                <div class="text-center message-status"></div>
                                <form id="" method="post" action="{{ route('ticket.payment.stripe') }}">
                                    @csrf
                                    <div class="ms-input2-box mb-25">
                                        @if(!$user)
                                                <input type="text" name="name" required placeholder="{{trans('file.Name')}}" >
{{--                                                <input type="text" name="email" required placeholder="email">--}}
{{--                                                <select class="select-identity" name="identity_type" id="identity_type" required>--}}
{{--                                                    <option value="1">{{ __('ID') }}</option>--}}
{{--                                                    <option value="2">{{ __('Student No.') }}</option>--}}
{{--                                                    <option value="3">{{ __('Passport') }}</option>--}}
{{--                                                    <option value="4">{{ __('Others') }}</option>--}}
{{--                                                </select>--}}
{{--                                                <input type="text" name="identity_number" id="identity_number" class="mt-2" placeholder="{{ __('Identity Number') }}">--}}
{{--                                                <input type="text" name="student_number" id="student_number" class="mt-2" placeholder="{{ __('Student Number') }}" style="display:none;">--}}
{{--                                                <input type="text" name="passport_number" id="passport_number" class="mt-2" placeholder="{{ __('Passport Number') }}" style="display:none;">--}}
{{--                                                <input type="text" name="identity_number" id="other_number" class="mt-2" placeholder="{{ __('Others') }}" style="display:none;">--}}

                                                <input type="text" name="phone" required placeholder="{{trans('file.Phone number')}}: +237675321739" value="+237" id="inputField">
                                            @else

                                                <input type="text" name="name" required placeholder="{{trans('file.Name')}}" value="{{ $user->name }}">
{{--                                                <input type="text" name="email" required placeholder="email" value="{{ $user->email }}">--}}
{{--                                                <select class="select-identity" name="identity_type" id="identity_type" required>--}}
{{--                                                    <option value="1">{{ __('ID') }}</option>--}}
{{--                                                    <option value="2">{{ __('Student No.') }}</option>--}}
{{--                                                    <option value="3">{{ __('Passport') }}</option>--}}
{{--                                                    <option value="4">{{ __('Others') }}</option>--}}
{{--                                                </select>--}}
{{--                                                <input type="text" name="identity_number" id="identity_number" class="mt-2" placeholder="{{ __('Identity Number') }}">--}}
{{--                                                <input type="text" name="student_number" id="student_number" class="mt-2" placeholder="{{ __('Student Number') }}" style="display:none;">--}}
{{--                                                <input type="text" name="passport_number" id="passport_number" class="mt-2" placeholder="{{ __('Passport Number') }}" style="display:none;">--}}
{{--                                                <input type="text" name="identity_number" id="other_number" class="mt-2" placeholder="{{ __('Others') }}" style="display:none;">--}}


                                                <input type="text" name="phone" required placeholder="{{trans('file.Phone number')}}: +237675321739" value="{{ $user->phone }}" id="inputField">
                                            @endif
                                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                        <input type="hidden" name="qty" value="{{ $data['vote'] }}">
                                        <input type="hidden" name="amount" value="{{ $data['vote'] * $ticket->price }}">
                                    </div>
                                    <div class="ms-submit-btn mb-40">
                                        @if(($data['vote'] * $ticket->price) >= env('STRIPE_MINIMUM_AMOUNT'))
                                            <button id="payment-button" class="unfill__btn d-block w-100">{{trans("file.Pay")}} {{ $data['vote'] * $ticket->price }} {{ $currency->code }}</button>
                                        @else
                                            <button id="payment-button" style="cursor: not-allowed" disabled class="unfill__btn d-block w-100">{{trans("file.Pay")}} {{ $data['vote'] * $ticket->price }} {{ $currency->code }}</button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-4 col-sm-12">
                        <div class="ms-maxw-510">
                            <div class="ms-login-wrap text-center ms-login-space ms-bg-2">
                                <h3 class="ms-title4 mb-50">{{trans("file.Pay By Beyond Coin")}}</h3>
                                <div class="text-center message-status-coin"></div>
                                <form>
                                    <div class="ms-input2-box mb-25">
                                        @if(!$user)
                                            <input type="text" name="phone_number" required placeholder="{{trans("file.Phone number")}}" value="+237" id="inputField">
                                        @else
                                            <input type="text" name="phone_number" required placeholder="{{trans("file.Phone number")}}" value="{{ $user->phone }}" id="inputField">
                                        @endif
                                    </div>
                                    <div class="ms-input2-box mb-25">
                                        <input type="text" name="code" required placeholder="{{trans("file.Beyond Coin Code")}}"  id="inputField">
                                        <input type="hidden" name="musician_id" value="{{ $ticket->id }}">
                                        <input type="hidden" name="vote_coin" value="{{ $data['vote'] }}">
                                        <input type="hidden" name="amount_coin" value="{{ $data['vote'] * $general_setting->vote_coin }}">
                                    </div>
                                     <div class="ms-submit-btn mb-40">
                                        <button id="coin-button" class="unfill__btn d-block w-100">{{trans("file.Pay")}} {{ $data['vote'] * $general_setting->vote_coin }} {{trans("file.Beyond Coin")}}</button>
                                    </div>
                                </form>

                                <p>{{trans("file.If you don't have coins you can contact to")}} <br>{{env('APP_NAME')}}</p>
                            </div>
                        </div>
                    </div> -->
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

            document.addEventListener('DOMContentLoaded', function() {
                function toggleIdentityFields() {
                    var val = document.getElementById('identity_type').value;
                    document.getElementById('identity_number').style.display = (val === '1') ? '' : 'none';
                    document.getElementById('student_number').style.display = (val === '2') ? '' : 'none';
                    document.getElementById('passport_number').style.display = (val === '3') ? '' : 'none';
                    document.getElementById('other_number').style.display = (val === '4') ? '' : 'none';
                }
                document.getElementById('identity_type').addEventListener('change', toggleIdentityFields);
                toggleIdentityFields(); // Set initial state
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

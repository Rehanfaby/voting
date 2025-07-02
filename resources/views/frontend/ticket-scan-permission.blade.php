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
                    <h3 class="ms-page-title text-center">{{trans("file.Ticket Scan")}}</h3>
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
                    <h3 class="ms-title4 mb-50">{{trans("file.Ticket Scan Result")}}</h3>
                    @if(isset($error))
                        <p class="mb-30">
                            <b class="text-danger">Ticket Scan Alert:</b> Your ticket is not found.
                        </p>
                    @else
                        <p class="mb-30">
                            <span class="text-success">{{ @$ticket->product->name }}</span><br>
                            <b>Ticket Scan Alert:</b> Your ticket has been scanned successfully and is valid for entry.
                        <table class="table table-striped-columns text-white">
                            <tr>
                                <th>Event Date</th>
                                <th class="text-white">{{ $ticket->product->event_day }}</th>
                            </tr>
                            <tr>
                                <th>Seats</th>
                                <th class="text-white">{{ $ticket->seat_numbers }}</th>
                            </tr>
                        </table>
                        </p>
                        @if(auth()->user() && auth()->user()->role_id == 1)
                            @if($ticket->product->event_day == date('Y-m-d'))
                                <a href="{{ route('ticket.scan.used', ['token' => $ticket->token]) }}" class="btn btn-success">Do you want to attend Event</a>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- login Area End Here  -->


</main>
@endsection

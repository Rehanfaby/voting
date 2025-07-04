@extends('layout.main') @section('content')
@if(session()->has('create_message'))
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('create_message') }}</div>
@endif
@if(session()->has('edit_message'))
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('edit_message') }}</div>
@endif
@if(session()->has('import_message'))
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('import_message') }}</div>
@endif
@if(session()->has('not_permitted'))
    <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif
@if(session()->has('message'))
    <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif

<section>
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
                        <table class="table table-striped">
                            <tr>
                                <th>Event Date</th>
                                <th>{{ $ticket->product->event_day }}</th>
                            </tr>
                            <tr>
                                <th>Seats</th>
                                <th>{{ $ticket->seat_numbers }}</th>
                            </tr>
                        </table>
                        </p>
                        @if(auth()->user() && auth()->user()->role_id == 1)
                            @if($ticket->product->event_day == date('Y-m-d'))
                                <a href="{{ route('admin.ticket.scan.used', ['token' => $ticket->token]) }}" class="btn btn-success">Do you want to attend Event</a>
                            @endif
                        @endif
                    @endif

                    <a class="btn btn-warning" href="{{ route('admin.ticket.scan.screen') }}">Back to Scan</a>
                </div>
            </div>
        </div>
    </section>

</section>


<script>
    $("ul#product").siblings('a').attr('aria-expanded','true');
    $("ul#product").addClass("show");
    $("ul#product #ticket-scan").addClass("active");
</script>
@endsection

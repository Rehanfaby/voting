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
            <div class="ms-overlay ms-overlay8 p-absolute zindex--1"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-9">
                        <div class="page-title-wrapper text-center pt-15">
                            <div class="page-title-icon mx-auto mb-30">
                                <i class="flaticon-star"></i>
                            </div>
                            <h3 class="ms-page-title lh-1">{{trans("file.Purchase Ticket")}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- page title area end  -->

        <!-- ticket list area start  -->
        <section class="ms-team-area ms-bg-2 pt-125 pb-110">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7">
                        <div class="section__title-wrapper text-center mb-60">
                            <span class="section__subtitle">{{trans("file.Purchase Ticket")}}</span>
                            <h2 class="section__title">{{trans("file.Ticket List")}}</h2>
                            <div class="mg-search">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="ticket-search" placeholder="Search tickets...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="ticket-list">
                    @foreach($tickets as $ticket)
                        @php
                            $images = explode(",", $ticket->image);
                            $soldOut = $ticket && $ticket->qty < 1;
                            $ticketUrl = $soldOut ? '#' : route('ticket.data', $ticket->id);
                        @endphp
                        <div class="col-xl-4 col-md-6 ticket-item">
                            <div class="mg-card">
                                <div class="mg-card__media">
                                    @if($soldOut)
                                        <span class="mg-card__badge mg-card__badge--soldout"><i class="fa-solid fa-ban"></i> Sold Out</span>
                                    @else
                                        <span class="mg-card__badge"><i class="fa-solid fa-ticket"></i> Available</span>
                                    @endif
                                    <a href="{{ $ticketUrl }}">
                                        <img src="{{ url('public/images/product', $images[0]) }}" alt="{{ $ticket->name }}">
                                    </a>
                                </div>
                                <div class="mg-card__body">
                                    <h3 class="mg-card__title">
                                        <a href="{{ $ticketUrl }}">{{ $ticket->name }}</a>
                                    </h3>
                                    <div class="mg-card__meta">
                                        <span class="mg-chip mg-chip--price">
                                            <i class="fa-solid fa-tag"></i> {{ number_format($ticket->price, 2) }}
                                        </span>
                                        <span class="mg-chip">
                                            <i class="fa-solid fa-chair"></i> {{ @$ticket->qty }} {{trans("file.Remaining Seats")}}
                                        </span>
                                    </div>
                                    <div class="mg-card__cta">
                                        @if($soldOut)
                                            <span class="mg-btn mg-btn--ghost" style="cursor:not-allowed;"><i class="fa-solid fa-ban"></i> Sold Out</span>
                                        @else
                                            <a href="{{ $ticketUrl }}" class="mg-btn"><i class="fa-solid fa-cart-shopping"></i> {{trans("file.Buy Tickets")}}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $tickets->links() }}
            </div>
        </section>
        <!-- ticket list area end  -->

    </main>
    <script>
$(document).ready(function(){
    $('#ticket-search').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#ticket-list .ticket-item').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>

@endsection

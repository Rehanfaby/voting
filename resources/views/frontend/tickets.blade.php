@extends('frontend.layout.main')
@section('content')

    <main class="mg-tickets">
        <section class="mg-tickets__hero pt-130 pb-50">
            <div class="container text-center">
                <span class="mg-tickets__badge">{{ trans('file.Purchase Ticket') }}</span>
                <h1 class="mg-tickets__title">{{ trans('file.Ticket List') }}</h1>
                <p class="mg-tickets__lead">{{ trans('file.Choose your ticket type and select seats') }}</p>
            </div>
        </section>

        <section class="mg-tickets__grid pb-130">
            <div class="container">
                <div class="mg-ticket-types">
                    @foreach($tickets as $ticket)
                    @php $images = explode(',', $ticket->image); @endphp
                    <a href="{{ route('ticket.data', $ticket->id) }}" class="mg-ticket-type">
                        <span class="mg-ticket-type__icon" style="--zone-color:#e87722"><i class="fa-solid fa-ticket"></i></span>
                        <span class="mg-ticket-type__info">
                            <strong>{{ $ticket->name }}</strong>
                            <span>{{ number_format($ticket->price) }} {{ $currency->code }}</span>
                            @if($ticket->seat_selection_enabled ?? false)
                                <small class="mg-ticket-type__vip"><i class="fa-solid fa-chair"></i> {{ trans('file.Seat selection available') }}</small>
                            @endif
                        </span>
                        <span class="mg-ticket-type__go"><i class="fa-solid fa-circle-info"></i></span>
                    </a>
                    @endforeach
                </div>
                <div class="mt-4 d-flex justify-content-center">{{ $tickets->links() }}</div>
            </div>
        </section>
    </main>
@endsection

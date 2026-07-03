@extends('frontend.layout.main')
@section('content')

    @php
        $images = explode(',', $ticket->image);
        $seatEnabled = (bool) ($ticket->seat_selection_enabled ?? false);
    @endphp

    <main class="mg-tickets mg-ticket-detail">
        <section class="mg-tickets__hero pt-130 pb-40">
            <div class="container">
                <a href="{{ url()->previous() }}" class="mg-ticket-back"><i class="fa-solid fa-arrow-left"></i> {{ trans('file.Back') }}</a>
                <h1 class="mg-tickets__title">{{ $ticket->name }}</h1>
                @if($ticket->event_day)
                    <p class="mg-tickets__lead"><i class="fa-regular fa-calendar"></i> {{ $ticket->event_day }}</p>
                @endif
            </div>
        </section>

        <section class="pb-130">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <div class="mg-ticket-gallery">
                            <img id="main-ticket-img" src="{{ url('public/images/product', $images[0]) }}" alt="{{ $ticket->name }}">
                            @if(count($images) > 1)
                            <div class="mg-ticket-thumbs">
                                @foreach($images as $img)
                                    <img src="{{ url('public/images/product', trim($img)) }}" alt="" class="mg-ticket-thumb">
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @if($ticket->product_details)
                            <div class="mg-ticket-desc mt-4">{!! $ticket->product_details !!}</div>
                        @endif
                    </div>

                    <div class="col-lg-7">
                        <form action="{{ route('purchase.ticket') }}" method="post" id="ticket-purchase-form">
                            @csrf
                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                            @if($seatEnabled)
                                <div class="mg-seat-picker" id="seat-picker" data-product-id="{{ $ticket->id }}">
                                    <h3 class="mg-seat-picker__title">{{ trans('file.Select your seats') }}</h3>
                                    <div class="mg-seat-picker__stage">{{ trans('file.Stage') }}</div>
                                    <div class="mg-seat-picker__canvas-wrap">
                                        <div id="customer-seat-canvas" class="mg-seat-picker__canvas"></div>
                                    </div>
                                    <div id="seat-legend" class="mg-seat-picker__legend"></div>
                                    <div class="mg-seat-picker__selected">
                                        <strong>{{ trans('file.Selected seats') }}:</strong>
                                        <span id="selected-seats-label">{{ trans('file.None') }}</span>
                                    </div>
                                    <input type="hidden" name="seat_ids" id="seat-ids-input" value="">
                                    <input type="hidden" name="vote" id="qty-input" value="0">
                                </div>
                            @else
                                <div class="mg-ticket-qty mb-4">
                                    <label>{{ trans('file.Qty') }}</label>
                                    <div class="mg-qty-control">
                                        <button type="button" class="qty-minus">−</button>
                                        <input type="number" name="vote" id="qty-input" value="1" min="1" max="20">
                                        <button type="button" class="qty-plus">+</button>
                                    </div>
                                </div>
                            @endif

                            <div class="mg-ticket-total">
                                <span>{{ trans('file.Total') }}</span>
                                <strong id="payable-amount">{{ number_format($ticket->price) }}</strong>
                                <span>{{ $currency->code }}</span>
                            </div>

                            <button type="submit" class="mg-btn mg-ticket-buy" id="btn-purchase" {{ $seatEnabled ? 'disabled' : '' }}>
                                <i class="fa-solid fa-lock"></i> {{ trans('file.Continue to payment') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
<script>
(function () {
    var basePrice = {{ (float) $ticket->price }};
    var seatEnabled = {{ $seatEnabled ? 'true' : 'false' }};
    var currency = @json($currency->code);

    document.querySelectorAll('.mg-ticket-thumb').forEach(function (t) {
        t.onclick = function () {
            document.getElementById('main-ticket-img').src = this.src;
            document.querySelectorAll('.mg-ticket-thumb').forEach(function (x) { x.classList.remove('is-active'); });
            this.classList.add('is-active');
        };
    });

    if (!seatEnabled) {
        var qtyInput = document.getElementById('qty-input');
        var amountEl = document.getElementById('payable-amount');
        function upd() {
            var q = Math.max(1, parseInt(qtyInput.value, 10) || 1);
            qtyInput.value = q;
            amountEl.textContent = (q * basePrice).toLocaleString();
        }
        document.querySelector('.qty-minus').onclick = function () { qtyInput.value = Math.max(1, +qtyInput.value - 1); upd(); };
        document.querySelector('.qty-plus').onclick = function () { qtyInput.value = +qtyInput.value + 1; upd(); };
        qtyInput.oninput = upd;
        return;
    }

    var canvas = document.getElementById('customer-seat-canvas');
    var legend = document.getElementById('seat-legend');
    var selected = [];
    var mapData = null;

    fetch(@json(route('ticket.seats.public', $ticket->id)))
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (!data.enabled) return;
            mapData = data;
            canvas.style.width = data.width + 'px';
            canvas.style.height = data.height + 'px';
            legend.innerHTML = data.zones.map(function (z) {
                return '<span><i style="background:'+z.color+'"></i> '+z.name+' — '+Number(z.price).toLocaleString()+' '+currency+'</span>';
            }).join('');
            data.seats.forEach(function (s) {
                var el = document.createElement('button');
                el.type = 'button';
                el.className = 'mg-seat-cell' + (s.status !== 'available' ? ' is-sold' : '');
                el.style.left = s.pos_x + 'px';
                el.style.top = s.pos_y + 'px';
                el.style.width = s.width + 'px';
                el.style.height = s.height + 'px';
                var zone = data.zones.find(function (z) { return z.id === s.zone_id; });
                el.style.background = zone ? zone.color : '#888';
                el.textContent = s.label;
                el.dataset.id = s.id;
                if (s.status === 'available') {
                    el.onclick = function () { toggleSeat(s, el, zone); };
                }
                canvas.appendChild(el);
            });
        });

    function toggleSeat(seat, el, zone) {
        var idx = selected.findIndex(function (x) { return x.id === seat.id; });
        if (idx >= 0) {
            selected.splice(idx, 1);
            el.classList.remove('is-picked');
        } else {
            selected.push({ id: seat.id, label: seat.label, price: zone ? +zone.price : basePrice });
            el.classList.add('is-picked');
        }
        syncSelection();
    }

    function syncSelection() {
        var labels = selected.map(function (s) { return s.label; });
        document.getElementById('selected-seats-label').textContent = labels.length ? labels.join(', ') : @json(trans('file.None'));
        document.getElementById('seat-ids-input').value = selected.map(function (s) { return s.id; }).join(',');
        document.getElementById('qty-input').value = selected.length;
        var total = selected.reduce(function (a, s) { return a + s.price; }, 0);
        document.getElementById('payable-amount').textContent = total.toLocaleString();
        document.getElementById('btn-purchase').disabled = selected.length === 0;
    }
})();
</script>
@endsection

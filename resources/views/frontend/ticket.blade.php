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
                @if($ticket->category)
                    @include('partials.event_countdown', ['event' => $ticket->category, 'class' => 'mg-event-countdown--hero'])
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
                                    <div class="mg-seat-picker__layout">
                                        <aside class="mg-seat-picker__cats" id="seat-categories"></aside>
                                        <div class="mg-seat-picker__main">
                                            <h3 class="mg-seat-picker__title">{{ trans('file.Select your seats') }}</h3>
                                            <div id="hold-countdown" class="mg-seat-hold" style="display:none;"></div>
                                            <div class="mg-seat-picker__stage">{{ trans('file.Stage') }}</div>
                                            <div class="mg-seat-picker__canvas-wrap" id="seat-canvas-wrap">
                                                <div id="customer-seat-canvas" class="mg-seat-picker__canvas"></div>
                                            </div>
                                            <div id="seat-legend" class="mg-seat-picker__legend"></div>
                                            <div class="mg-seat-picker__selected">
                                                <strong>{{ trans('file.Selected seats') }}:</strong>
                                                <span id="selected-seats-label">{{ trans('file.None') }}</span>
                                            </div>
                                            <div class="mg-seat-list-fallback mt-3">
                                                <label for="seat-list-select">{{ trans('file.List selection') }} (accessibility)</label>
                                                <select id="seat-list-select" class="form-control" multiple size="6"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="seat_ids" id="seat-ids-input" value="">
                                    <input type="hidden" name="hold_token" id="hold-token-input" value="">
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

<style>
.mg-seat-picker__layout { display:flex; gap:16px; flex-wrap:wrap; }
.mg-seat-picker__cats { flex:0 0 180px; }
.mg-seat-cat { display:block; width:100%; text-align:left; border:1px solid #e5e7eb; border-radius:10px; padding:10px 12px; margin-bottom:8px; background:#fff; cursor:pointer; }
.mg-seat-cat.is-active { border-color:#e87722; box-shadow:0 0 0 2px rgba(232,119,34,.2); }
.mg-seat-cat i { display:inline-block; width:12px; height:12px; border-radius:3px; margin-right:6px; }
.mg-seat-picker__main { flex:1; min-width:260px; }
.mg-seat-picker__canvas-wrap { overflow:auto; max-height:420px; touch-action:pinch-zoom; border-radius:12px; background:#1a1a2e; padding:12px; }
.mg-seat-picker__canvas { position:relative; margin:0 auto; background:#2d2d44; border-radius:8px; }
.mg-seat-cell { position:absolute; border:0; border-radius:6px; color:#fff; font-size:10px; font-weight:700; cursor:pointer; }
.mg-seat-cell.is-sold { opacity:.45; cursor:not-allowed; }
.mg-seat-cell.is-picked { outline:3px solid #fff; outline-offset:1px; }
.mg-seat-cell.is-filtered-out { opacity:.15; pointer-events:none; }
.mg-seat-cell.is-restricted { border:2px dashed #fff; }
.mg-seat-hold { background:#fff7ed; color:#9a3412; padding:8px 12px; border-radius:8px; margin-bottom:10px; font-weight:600; }
@media (max-width: 767px) {
  .mg-seat-picker__cats { flex:1 1 100%; display:flex; gap:8px; overflow:auto; }
  .mg-seat-cat { min-width:140px; }
}
</style>
@endsection

@section('scripts')
<script src="{{ asset('public/js/event-countdown.js') }}"></script>
<script>
(function () {
    var basePrice = {{ (float) $ticket->price }};
    var seatEnabled = {{ $seatEnabled ? 'true' : 'false' }};
    var currency = @json($currency->code);
    var csrf = @json(csrf_token());
    var productId = {{ (int) $ticket->id }};

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
    var catEl = document.getElementById('seat-categories');
    var listSelect = document.getElementById('seat-list-select');
    var selected = [];
    var mapData = null;
    var activeCategoryId = null;
    var holdTimer = null;
    var seatEls = {};

    fetch(@json(route('ticket.seats.public', $ticket->id)))
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (!data.enabled) return;
            mapData = data;
            var mode = data.mode || 'legacy';
            var cats = data.categories || data.zones || [];
            canvas.style.width = data.width + 'px';
            canvas.style.height = data.height + 'px';
            if (data.background_image) {
                canvas.style.backgroundImage = "url('" + data.background_image + "')";
                canvas.style.backgroundSize = 'cover';
            }

            catEl.innerHTML = '<button type="button" class="mg-seat-cat is-active" data-id="">All</button>';
            cats.forEach(function (z) {
                var b = document.createElement('button');
                b.type = 'button';
                b.className = 'mg-seat-cat';
                b.dataset.id = z.id;
                b.innerHTML = '<i style="background:' + z.color + '"></i><strong>' + z.name + '</strong><br><small>' + Number(z.price).toLocaleString() + ' ' + currency + '</small>';
                b.onclick = function () {
                    activeCategoryId = z.id ? +z.id : null;
                    document.querySelectorAll('.mg-seat-cat').forEach(function (x) { x.classList.remove('is-active'); });
                    b.classList.add('is-active');
                    applyFilter();
                };
                catEl.appendChild(b);
            });
            catEl.querySelector('[data-id=""]').onclick = function () {
                activeCategoryId = null;
                document.querySelectorAll('.mg-seat-cat').forEach(function (x) { x.classList.remove('is-active'); });
                this.classList.add('is-active');
                applyFilter();
            };

            legend.innerHTML = cats.map(function (z) {
                return '<span><i style="background:'+z.color+'"></i> '+z.name+' — '+Number(z.price).toLocaleString()+' '+currency+'</span>';
            }).join('');

            listSelect.innerHTML = '';
            data.seats.forEach(function (s) {
                var avail = s.status === 'available';
                var color = s.color;
                if (!color) {
                    var zone = cats.find(function (z) { return z.id === (s.category_id || s.zone_id); });
                    color = zone ? zone.color : '#888';
                }
                var price = s.price != null ? +s.price : (function () {
                    var zone = cats.find(function (z) { return z.id === (s.category_id || s.zone_id); });
                    return zone ? +zone.price : basePrice;
                })();

                var el = document.createElement('button');
                el.type = 'button';
                el.className = 'mg-seat-cell' + (avail ? '' : ' is-sold') + (s.restricted_view ? ' is-restricted' : '');
                el.style.left = s.pos_x + 'px';
                el.style.top = s.pos_y + 'px';
                el.style.width = s.width + 'px';
                el.style.height = s.height + 'px';
                el.style.background = color;
                el.textContent = s.label;
                el.dataset.id = s.id;
                el.dataset.categoryId = s.category_id || s.zone_id || '';
                el.title = (s.level ? s.level + ' · ' : '') + (s.section ? s.section + ' · ' : '') + s.label + (s.restricted_view ? ' (restricted view)' : '');
                seatEls[s.id] = el;
                if (avail) {
                    el.onclick = function () { toggleSeat({ id: s.id, label: s.label, price: price, category_id: s.category_id || s.zone_id, restricted_view: s.restricted_view }, el); };
                }
                canvas.appendChild(el);

                if (avail) {
                    var opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = s.label + ' — ' + price.toLocaleString() + ' ' + currency;
                    opt.dataset.price = price;
                    opt.dataset.label = s.label;
                    opt.dataset.categoryId = s.category_id || s.zone_id || '';
                    listSelect.appendChild(opt);
                }
            });

            listSelect.onchange = function () {
                var ids = Array.prototype.map.call(listSelect.selectedOptions, function (o) { return +o.value; });
                selected = [];
                Object.keys(seatEls).forEach(function (id) { seatEls[id].classList.remove('is-picked'); });
                Array.prototype.forEach.call(listSelect.selectedOptions, function (o) {
                    selected.push({ id: +o.value, label: o.dataset.label, price: +o.dataset.price, category_id: o.dataset.categoryId ? +o.dataset.categoryId : null });
                    if (seatEls[o.value]) seatEls[o.value].classList.add('is-picked');
                });
                syncSelection();
            };
        });

    function applyFilter() {
        Object.keys(seatEls).forEach(function (id) {
            var el = seatEls[id];
            var cat = el.dataset.categoryId ? +el.dataset.categoryId : null;
            var hide = activeCategoryId && cat !== activeCategoryId;
            el.classList.toggle('is-filtered-out', !!hide);
        });
        Array.prototype.forEach.call(listSelect.options, function (o) {
            var cat = o.dataset.categoryId ? +o.dataset.categoryId : null;
            o.hidden = !!(activeCategoryId && cat !== activeCategoryId);
        });
    }

    function toggleSeat(seat, el) {
        if (seat.restricted_view && !el.classList.contains('is-picked')) {
            if (!confirm('This seat has a restricted view. Continue?')) return;
        }
        var idx = selected.findIndex(function (x) { return x.id === seat.id; });
        if (idx >= 0) {
            selected.splice(idx, 1);
            el.classList.remove('is-picked');
        } else {
            selected.push(seat);
            el.classList.add('is-picked');
        }
        Array.prototype.forEach.call(listSelect.options, function (o) {
            o.selected = selected.some(function (s) { return s.id === +o.value; });
        });
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

    document.getElementById('ticket-purchase-form').addEventListener('submit', function (e) {
        if (!mapData || mapData.mode !== 'event_map') return;
        if (!selected.length) { e.preventDefault(); return; }
        e.preventDefault();
        var form = this;
        var btn = document.getElementById('btn-purchase');
        btn.disabled = true;
        fetch('/api/ticket/' + productId + '/holds', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: JSON.stringify({ seat_ids: selected.map(function (s) { return s.id; }) })
        }).then(function (r) { return r.json().then(function (j) { if (!r.ok) throw j; return j; }); })
          .then(function (j) {
              document.getElementById('hold-token-input').value = j.hold_token;
              document.getElementById('seat-ids-input').value = (j.ids || []).join(',');
              document.getElementById('payable-amount').textContent = Number(j.total).toLocaleString();
              startCountdown(j.expires_in || 600);
              form.submit();
          })
          .catch(function (err) {
              alert((err && err.error) || 'Could not hold seats. Please try again.');
              btn.disabled = false;
          });
    });

    function startCountdown(seconds) {
        var el = document.getElementById('hold-countdown');
        el.style.display = '';
        var left = seconds;
        if (holdTimer) clearInterval(holdTimer);
        function tick() {
            var m = Math.floor(left / 60);
            var s = left % 60;
            el.textContent = 'Seats held for ' + m + ':' + (s < 10 ? '0' : '') + s;
            if (left <= 0) {
                clearInterval(holdTimer);
                el.textContent = 'Hold expired — reselect seats.';
            }
            left--;
        }
        tick();
        holdTimer = setInterval(tick, 1000);
    }
})();
</script>
@endsection

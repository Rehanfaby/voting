@extends('layout.main')
@section('content')
<section class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3>{{ trans('file.Seat map') }} — {{ $product->name }}</h3>
            <p class="text-muted mb-0">{{ trans('file.Drag seats onto the hall layout. Assign each block to a price zone.') }}</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">{{ trans('file.Back') }}</a>
    </div>

    <div class="row">
        <div class="col-lg-3">
            <div class="card mb-3">
                <div class="card-header">{{ trans('file.Settings') }}</div>
                <div class="card-body">
                    <label><input type="checkbox" id="seat-enabled" {{ $product->seat_selection_enabled ? 'checked' : '' }}> {{ trans('file.Enable seat selection') }}</label>
                    <div class="form-group mt-2"><label>{{ trans('file.Canvas width') }}</label><input type="number" id="map-width" class="form-control" value="{{ $product->seat_map_width }}"></div>
                    <div class="form-group"><label>{{ trans('file.Canvas height') }}</label><input type="number" id="map-height" class="form-control" value="{{ $product->seat_map_height }}"></div>
                    <button type="button" class="btn btn-sm btn-primary btn-block" id="btn-save-settings">{{ trans('file.Save settings') }}</button>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between"><span>{{ trans('file.Price zones') }}</span><button type="button" class="btn btn-xs btn-success" id="btn-add-zone">+</button></div>
                <div class="card-body p-2" id="zone-list"></div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-group mb-2"><label>{{ trans('file.Active zone') }}</label><select id="active-zone" class="form-control"></select></div>
                    <div class="form-group mb-2"><label>{{ trans('file.Seat label') }}</label><input type="text" id="new-label" class="form-control" placeholder="VIP1"></div>
                    <button type="button" class="btn btn-sm btn-info btn-block" id="btn-add-seat">{{ trans('file.Add seat block') }}</button>
                    <button type="button" class="btn btn-sm btn-danger btn-block mt-2" id="btn-delete-seat">{{ trans('file.Delete selected') }}</button>
                </div>
            </div>

            <button type="button" class="btn btn-primary btn-lg btn-block" id="btn-save-map">{{ trans('file.Save seat map') }}</button>
        </div>

        <div class="col-lg-9">
            <div class="seat-map-wrap">
                <div class="seat-map-stage">{{ trans('file.Stage') }}</div>
                <div id="seat-canvas" class="seat-map-canvas" style="width:{{ $product->seat_map_width }}px;height:{{ $product->seat_map_height }}px;">
                </div>
                <div class="seat-map-legend mt-2">
                    <span><i class="seat-dot seat-dot--avail"></i> {{ trans('file.Available') }}</span>
                    <span><i class="seat-dot seat-dot--sold"></i> {{ trans('file.Sold') }}</span>
                    <span><i class="seat-dot seat-dot--sel"></i> {{ trans('file.Selected') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.seat-map-wrap { background:#1a1a2e; border-radius:12px; padding:20px; overflow:auto; }
.seat-map-stage { background:linear-gradient(135deg,#e87722,#ff9533); color:#fff; text-align:center; font-weight:800; padding:12px; border-radius:8px; margin-bottom:16px; letter-spacing:2px; }
.seat-map-canvas { position:relative; background:#2d2d44; border:2px dashed rgba(255,255,255,.15); border-radius:8px; margin:0 auto; }
.seat-block { position:absolute; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:#fff; border-radius:6px; cursor:move; user-select:none; border:2px solid rgba(255,255,255,.35); box-shadow:0 2px 8px rgba(0,0,0,.3); }
.seat-block.is-selected { outline:3px solid #fff; outline-offset:2px; }
.seat-block.is-sold { opacity:.55; cursor:not-allowed; }
.seat-block .resize-handle { position:absolute; right:0; bottom:0; width:10px; height:10px; cursor:se-resize; background:rgba(255,255,255,.5); border-radius:2px; }
.zone-row { padding:8px; border:1px solid #e7edf5; border-radius:8px; margin-bottom:8px; font-size:13px; }
.zone-row input[type=color] { width:36px; height:28px; padding:0; border:0; vertical-align:middle; }
.seat-dot { display:inline-block; width:12px; height:12px; border-radius:3px; margin-right:4px; vertical-align:middle; }
.seat-dot--avail { background:#28a745; }
.seat-dot--sold { background:#6c757d; }
.seat-dot--sel { background:#e87722; }
</style>

<script>
(function () {
    var productId = {{ (int) $product->id }};
    var csrf = @json(csrf_token());
    var zones = @json($zones);
    var seats = @json($seats);
    var canvas = document.getElementById('seat-canvas');
    var selectedId = null;
    var drag = null;

    function zoneColor(id) {
        var z = zones.find(function (x) { return x.id === id; });
        return z ? z.color : '#888';
    }

    function renderZones() {
        var list = document.getElementById('zone-list');
        var sel = document.getElementById('active-zone');
        list.innerHTML = '';
        sel.innerHTML = '';
        zones.forEach(function (z, i) {
            var row = document.createElement('div');
            row.className = 'zone-row';
            row.innerHTML = '<input type="text" value="'+z.name+'" data-f="name" data-i="'+i+'" class="form-control form-control-sm mb-1">' +
                '<input type="number" step="0.01" value="'+z.price+'" data-f="price" data-i="'+i+'" class="form-control form-control-sm mb-1" placeholder="Price">' +
                '<label><input type="checkbox" data-f="is_vip" data-i="'+i+'" '+(z.is_vip?'checked':'')+'> VIP</label> ' +
                '<input type="color" value="'+z.color+'" data-f="color" data-i="'+i+'"> ' +
                '<button type="button" class="btn btn-xs btn-danger float-right" data-del="'+z.id+'">×</button>';
            list.appendChild(row);
            var opt = document.createElement('option');
            opt.value = z.id;
            opt.textContent = z.name + ' (' + z.price + ')';
            sel.appendChild(opt);
        });
        bindZoneInputs();
    }

    function bindZoneInputs() {
        document.querySelectorAll('#zone-list [data-f]').forEach(function (el) {
            el.onchange = function () {
                var i = +el.dataset.i;
                var f = el.dataset.f;
                if (f === 'is_vip') zones[i][f] = el.checked;
                else if (f === 'price') zones[i][f] = parseFloat(el.value) || 0;
                else zones[i][f] = el.value;
                renderSeats();
            };
        });
        document.querySelectorAll('#zone-list [data-del]').forEach(function (btn) {
            btn.onclick = function () {
                if (!confirm('Delete zone and its seats?')) return;
                fetch('/products/seat-zones/' + btn.dataset.del, { method:'DELETE', headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'} })
                    .then(function () { location.reload(); });
            };
        });
    }

    function renderSeats() {
        canvas.querySelectorAll('.seat-block').forEach(function (n) { n.remove(); });
        seats.forEach(function (s) {
            var el = document.createElement('div');
            el.className = 'seat-block' + (s.status === 'sold' ? ' is-sold' : '') + (selectedId === s.id ? ' is-selected' : '');
            el.dataset.id = s.id || ('new-' + s._tmp);
            el.style.left = s.pos_x + 'px';
            el.style.top = s.pos_y + 'px';
            el.style.width = s.width + 'px';
            el.style.height = s.height + 'px';
            el.style.background = zoneColor(s.zone_id);
            el.textContent = s.label;
            if (s.status !== 'sold') {
                var rh = document.createElement('span');
                rh.className = 'resize-handle';
                el.appendChild(rh);
            }
            el.onmousedown = startDrag.bind(null, el, s);
            canvas.appendChild(el);
        });
    }

    function startDrag(el, seat, e) {
        if (seat.status === 'sold') return;
        if (e.target.classList.contains('resize-handle')) {
            drag = { mode:'resize', el:el, seat:seat, sx:e.clientX, sy:e.clientY, sw:seat.width, sh:seat.height };
        } else {
            selectedId = seat.id || seat._tmp;
            renderSeats();
            drag = { mode:'move', el:el, seat:seat, ox:e.clientX - seat.pos_x, oy:e.clientY - seat.pos_y };
        }
        e.preventDefault();
    }

    document.addEventListener('mousemove', function (e) {
        if (!drag) return;
        if (drag.mode === 'move') {
            drag.seat.pos_x = Math.max(0, e.clientX - drag.ox);
            drag.seat.pos_y = Math.max(0, e.clientY - drag.oy);
            drag.el.style.left = drag.seat.pos_x + 'px';
            drag.el.style.top = drag.seat.pos_y + 'px';
        } else {
            drag.seat.width = Math.max(24, drag.sw + (e.clientX - drag.sx));
            drag.seat.height = Math.max(20, drag.sh + (e.clientY - drag.sy));
            drag.el.style.width = drag.seat.width + 'px';
            drag.el.style.height = drag.seat.height + 'px';
        }
    });
    document.addEventListener('mouseup', function () { drag = null; });

    document.getElementById('btn-add-zone').onclick = function () {
        fetch('/products/' + productId + '/seat-map/zones', {
            method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
            body: JSON.stringify({ name:'Zone ' + (zones.length+1), price: {{ (float) $product->price }}, color:'#e87722', is_vip:false, sort_order:zones.length })
        }).then(function (r) { return r.json(); }).then(function (d) { zones.push(d.zone); renderZones(); });
    };

    document.getElementById('btn-add-seat').onclick = function () {
        var zid = +document.getElementById('active-zone').value;
        var label = document.getElementById('new-label').value.trim() || ('S' + (seats.length+1));
        seats.push({ _tmp: Date.now(), zone_id: zid, label: label, pos_x: 40, pos_y: 40, width: 44, height: 36, status:'available' });
        document.getElementById('new-label').value = '';
        renderSeats();
    };

    document.getElementById('btn-delete-seat').onclick = function () {
        if (!selectedId) return;
        seats = seats.filter(function (s) { return (s.id || s._tmp) !== selectedId; });
        selectedId = null;
        renderSeats();
    };

    document.getElementById('btn-save-settings').onclick = function () {
        fetch('/products/' + productId + '/seat-map/settings', {
            method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
            body: JSON.stringify({
                seat_selection_enabled: document.getElementById('seat-enabled').checked,
                seat_map_width: +document.getElementById('map-width').value,
                seat_map_height: +document.getElementById('map-height').value
            })
        }).then(function () { alert('Settings saved'); location.reload(); });
    };

    document.getElementById('btn-save-map').onclick = function () {
        Promise.all(zones.map(function (z) {
            return fetch('/products/' + productId + '/seat-map/zones', {
                method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
                body: JSON.stringify(z)
            });
        })).then(function () {
            return fetch('/products/' + productId + '/seat-map/seats', {
                method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
                body: JSON.stringify({ seats: seats.map(function (s) {
                    return { id: s.id || null, zone_id: s.zone_id, label: s.label, pos_x: s.pos_x, pos_y: s.pos_y, width: s.width, height: s.height };
                })})
            });
        }).then(function (r) { return r.json(); }).then(function () { alert('Seat map saved'); location.reload(); });
    };

    renderZones();
    renderSeats();
})();
</script>
@endsection

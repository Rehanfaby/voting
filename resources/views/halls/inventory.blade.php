@extends('layout.main')
@section('content')
@if(session('message'))
  <div class="alert alert-success text-center">{{ session('message') }}</div>
@endif
<section class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
    <div>
      <h3>{{ trans('file.Inventory') }} — {{ $product->name }}</h3>
      <p class="text-muted mb-0">
        {{ optional(optional($map->layoutVersion)->hall)->name }}
        · v{{ optional($map->layoutVersion)->version }}
      </p>
    </div>
    <div>
      <a href="{{ route('products.event_seat_map', $product->id) }}" class="btn btn-secondary">{{ trans('file.Back') }}</a>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3"><div class="card p-3 text-center"><div class="h4 mb-0 text-success">{{ $stats['available'] }}</div><small>Available</small></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><div class="h4 mb-0 text-warning">{{ $stats['held'] }}</div><small>Held</small></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><div class="h4 mb-0 text-danger">{{ $stats['sold'] }}</div><small>Sold</small></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><div class="h4 mb-0 text-muted">{{ $stats['blocked'] }}</div><small>Blocked</small></div></div>
  </div>

  <div class="row">
    <div class="col-lg-4">
      <div class="card mb-3">
        <div class="card-header">Categories</div>
        <div class="card-body" id="cat-list">
          @foreach($map->categories as $cat)
            <div class="border rounded p-2 mb-2 cat-row" data-id="{{ $cat->id }}">
              <div class="d-flex align-items-center">
                <span style="width:16px;height:16px;background:{{ $cat->color }};display:inline-block;border-radius:3px;margin-right:8px;"></span>
                <strong>{{ $cat->name }}</strong>
                <span class="ml-auto">{{ number_format($cat->price) }}</span>
              </div>
              <small class="text-muted">{{ $cat->code }}</small>
            </div>
          @endforeach
        </div>
        <div class="card-body border-top">
          <div class="form-group mb-2"><input id="cat-name" class="form-control" placeholder="Name"></div>
          <div class="form-group mb-2"><input id="cat-code" class="form-control" placeholder="CODE"></div>
          <div class="form-row">
            <div class="form-group col-6"><input type="number" id="cat-price" class="form-control" placeholder="Price"></div>
            <div class="form-group col-6"><input type="color" id="cat-color" class="form-control" value="#e87722"></div>
          </div>
          <button type="button" class="btn btn-sm btn-primary btn-block" id="btn-save-cat">Save category</button>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header">Ops</div>
        <div class="card-body">
          <p class="small text-muted">Select inventory rows below, then assign category or run ops.</p>
          <select id="assign-cat" class="form-control mb-2">
            @foreach($map->categories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
          </select>
          <button type="button" class="btn btn-sm btn-info btn-block mb-2" id="btn-assign">Assign category to selected</button>
          <hr>
          <input type="number" id="relocate-to" class="form-control mb-2" placeholder="Target inventory ID">
          <button type="button" class="btn btn-sm btn-warning btn-block mb-2" id="btn-relocate">Relocate first selected → target</button>
          <input type="number" id="reissue-ticket-seat" class="form-control mb-2" placeholder="Ticket seat ID to reissue">
          <button type="button" class="btn btn-sm btn-secondary btn-block" id="btn-reissue">Reissue QR</button>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <span>Heat map / seats</span>
          <input type="text" id="filter-label" class="form-control form-control-sm w-auto" placeholder="Filter label">
        </div>
        <div class="seat-heat-wrap p-3">
          <div id="heat-canvas" class="seat-heat-canvas"
               style="width:{{ $map->canvas_width }}px;height:{{ $map->canvas_height }}px;position:relative;background:#2d2d44;margin:0 auto;border-radius:8px;">
          </div>
        </div>
        <div class="table-responsive" style="max-height:360px;overflow:auto;">
          <table class="table table-sm mb-0" id="inv-table">
            <thead><tr><th></th><th>Label</th><th>Location</th><th>Cat</th><th>Price</th><th>Status</th><th></th></tr></thead>
            <tbody>
              @foreach($map->inventory as $seat)
                <tr data-id="{{ $seat->id }}" data-label="{{ strtolower($seat->label) }}">
                  <td><input type="checkbox" class="inv-check" value="{{ $seat->id }}"></td>
                  <td>{{ $seat->label }}</td>
                  <td class="small">{{ $seat->locationLabel() }}</td>
                  <td>{{ optional($seat->category)->name }}</td>
                  <td>{{ number_format($seat->price) }}</td>
                  <td><span class="badge badge-{{ $seat->status === 'available' ? 'success' : ($seat->status === 'sold' ? 'danger' : 'warning') }}">{{ $seat->status }}</span></td>
                  <td>
                    @if($seat->status === 'sold' && $seat->ticket_id)
                      <button type="button" class="btn btn-xs btn-outline-danger btn-refund" data-ticket="{{ $seat->ticket_id }}">Refund</button>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
.seat-heat-wrap { background:#1a1a2e; border-radius:0; overflow:auto; }
.heat-seat { position:absolute; border-radius:4px; font-size:9px; color:#fff; display:flex; align-items:center; justify-content:center; border:1px solid rgba(255,255,255,.25); }
.heat-seat.is-available { background:#28a745; }
.heat-seat.is-held { background:#ffc107; color:#222; }
.heat-seat.is-sold { background:#dc3545; }
.heat-seat.is-blocked { background:#6c757d; }
</style>

<script>
(function () {
  var productId = {{ (int) $product->id }};
  var csrf = @json(csrf_token());
  var seats = @json($map->inventory->map(function ($s) {
      return [
          'id' => $s->id,
          'label' => $s->label,
          'pos_x' => $s->pos_x,
          'pos_y' => $s->pos_y,
          'width' => $s->width,
          'height' => $s->height,
          'status' => $s->status,
          'color' => optional($s->category)->color,
      ];
  }));
  var canvas = document.getElementById('heat-canvas');
  seats.forEach(function (s) {
    var d = document.createElement('div');
    d.className = 'heat-seat is-' + s.status;
    d.style.left = s.pos_x + 'px';
    d.style.top = s.pos_y + 'px';
    d.style.width = s.width + 'px';
    d.style.height = s.height + 'px';
    if (s.status === 'available' && s.color) d.style.background = s.color;
    d.textContent = s.label;
    d.title = s.label + ' (' + s.status + ')';
    canvas.appendChild(d);
  });

  function selectedIds() {
    return Array.prototype.map.call(document.querySelectorAll('.inv-check:checked'), function (c) { return +c.value; });
  }

  document.getElementById('filter-label').oninput = function () {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#inv-table tbody tr').forEach(function (tr) {
      tr.style.display = !q || tr.dataset.label.indexOf(q) >= 0 ? '' : 'none';
    });
  };

  document.getElementById('btn-save-cat').onclick = function () {
    fetch('/admin/products/' + productId + '/event-seat-map/categories', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
      body: JSON.stringify({
        name: document.getElementById('cat-name').value,
        code: document.getElementById('cat-code').value,
        price: document.getElementById('cat-price').value,
        color: document.getElementById('cat-color').value,
        apply_price: true
      })
    }).then(function () { location.reload(); });
  };

  document.getElementById('btn-assign').onclick = function () {
    var ids = selectedIds();
    if (!ids.length) { alert('Select seats'); return; }
    fetch('/admin/products/' + productId + '/event-seat-map/assign-category', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
      body: JSON.stringify({ category_id: +document.getElementById('assign-cat').value, inventory_ids: ids })
    }).then(function () { location.reload(); });
  };

  document.getElementById('btn-relocate').onclick = function () {
    var ids = selectedIds();
    var to = +document.getElementById('relocate-to').value;
    if (!ids.length || !to) { alert('Select one sold seat and enter target inventory id'); return; }
    fetch('/admin/products/' + productId + '/event-seat-map/relocate', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
      body: JSON.stringify({ from_inventory_id: ids[0], to_inventory_id: to })
    }).then(function (r) { return r.json(); }).then(function (j) {
      if (j.error) alert(j.error); else location.reload();
    });
  };

  document.getElementById('btn-reissue').onclick = function () {
    var id = +document.getElementById('reissue-ticket-seat').value;
    if (!id) { alert('Enter ticket_seat id'); return; }
    fetch('/admin/products/' + productId + '/event-seat-map/reissue', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
      body: JSON.stringify({ ticket_seat_id: id })
    }).then(function (r) { return r.json(); }).then(function (j) {
      if (j.error) alert(j.error);
      else alert('New token: ' + j.token);
    });
  };

  document.querySelectorAll('.btn-refund').forEach(function (btn) {
    btn.onclick = function () {
      if (!confirm('Refund this ticket and free seats?')) return;
      fetch('/admin/products/' + productId + '/event-seat-map/refund', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: JSON.stringify({ ticket_id: +btn.dataset.ticket })
      }).then(function (r) { return r.json(); }).then(function (j) {
        if (j.error) alert(j.error); else location.reload();
      });
    };
  });
})();
</script>
@endsection

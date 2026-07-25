@extends('layout.main')
@section('content')
@if(session('message'))
  <div class="alert alert-success text-center">{{ session('message') }}</div>
@endif
@if(session('error'))
  <div class="alert alert-danger text-center">{{ session('error') }}</div>
@endif
<section class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
    <div>
      <h3 class="mb-1">{{ $hall->name }} — Layout v{{ $layout->version }}</h3>
      <p class="text-muted mb-0">
        Status:
        @if($layout->status === 'published')
          <span class="badge badge-success">published (read-only geometry)</span>
        @else
          <span class="badge badge-warning">draft</span>
        @endif
        · {{ $layout->label }}
      </p>
    </div>
    <div>
      <a href="{{ route('halls.edit', $hall->id) }}" class="btn btn-secondary">{{ trans('file.Back') }}</a>
      @if($layout->status === 'draft')
        <form class="d-inline" method="post" action="{{ route('halls.layouts.publish', [$hall->id, $layout->id]) }}">
          @csrf
          <button class="btn btn-success" type="submit" onclick="return confirm('Publish? Geometry becomes immutable.')">{{ trans('file.Publish') }}</button>
        </form>
      @else
        <form class="d-inline" method="post" action="{{ route('halls.layouts.fork', [$hall->id, $layout->id]) }}">
          @csrf
          <button class="btn btn-info" type="submit">{{ trans('file.New draft') }}</button>
        </form>
      @endif
    </div>
  </div>

  <div class="row">
    <div class="col-lg-3">
      <div class="card mb-3">
        <div class="card-header">{{ trans('file.Settings') }}</div>
        <div class="card-body">
          <div class="form-group"><label>{{ trans('file.Label') }}</label><input type="text" id="layout-label" class="form-control" value="{{ $layout->label }}" {{ $readonly ? 'disabled' : '' }}></div>
          <div class="form-group"><label>{{ trans('file.Canvas width') }}</label><input type="number" id="map-width" class="form-control" value="{{ $layout->canvas_width }}" {{ $readonly ? 'disabled' : '' }}></div>
          <div class="form-group"><label>{{ trans('file.Canvas height') }}</label><input type="number" id="map-height" class="form-control" value="{{ $layout->canvas_height }}" {{ $readonly ? 'disabled' : '' }}></div>
          @unless($readonly)
            <button type="button" class="btn btn-sm btn-primary btn-block" id="btn-save-settings">{{ trans('file.Save settings') }}</button>
            <hr>
            <label class="d-block">{{ trans('file.Background image') }}</label>
            <input type="file" id="bg-file" accept="image/*" class="form-control-file mb-2">
            <button type="button" class="btn btn-sm btn-secondary btn-block" id="btn-upload-bg">Upload</button>
          @endunless
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header d-flex justify-content-between"><span>Levels</span>
          @unless($readonly)<button type="button" class="btn btn-xs btn-success" id="btn-add-level">+</button>@endunless
        </div>
        <div class="card-body p-2" id="level-list"></div>
      </div>

      <div class="card mb-3">
        <div class="card-header d-flex justify-content-between"><span>Sections</span>
          @unless($readonly)<button type="button" class="btn btn-xs btn-success" id="btn-add-section">+</button>@endunless
        </div>
        <div class="card-body p-2" id="section-list"></div>
      </div>

      @unless($readonly)
      <div class="card mb-3">
        <div class="card-header">Bulk generate rows</div>
        <div class="card-body">
          <div class="form-group mb-2"><label>Section</label><select id="gen-section" class="form-control"></select></div>
          <div class="form-row">
            <div class="form-group col-6"><label>From</label><input id="gen-from" class="form-control" value="A" maxlength="2"></div>
            <div class="form-group col-6"><label>To</label><input id="gen-to" class="form-control" value="J" maxlength="2"></div>
          </div>
          <div class="form-row">
            <div class="form-group col-6"><label>Seats/row</label><input type="number" id="gen-count" class="form-control" value="20" min="1" max="80"></div>
            <div class="form-group col-6"><label>Start #</label><input type="number" id="gen-start" class="form-control" value="1" min="1"></div>
          </div>
          <div class="form-row">
            <div class="form-group col-6"><label>Origin X</label><input type="number" id="gen-x" class="form-control" value="40"></div>
            <div class="form-group col-6"><label>Origin Y</label><input type="number" id="gen-y" class="form-control" value="80"></div>
          </div>
          <div class="form-group mb-2"><label>Prefix (optional)</label><input id="gen-prefix" class="form-control" placeholder="VIP / BAL"></div>
          <div class="form-row">
            <div class="form-group col-6"><label>Curve</label><input type="number" id="gen-curve" class="form-control" value="0" step="0.1" title="0 = straight; positive bows toward stage"></div>
            <div class="form-group col-6"><label>Seat type</label>
              <select id="gen-type" class="form-control">
                <option value="seat">Seat</option>
                <option value="standing">Standing</option>
                <option value="table">Table</option>
              </select>
            </div>
          </div>
          <label class="d-block mb-2"><input type="checkbox" id="gen-restricted"> Restricted view</label>
          <button type="button" class="btn btn-info btn-block" id="btn-generate">Generate rows</button>
        </div>
      </div>
      <button type="button" class="btn btn-primary btn-lg btn-block" id="btn-save-seats">{{ trans('file.Save seat map') }}</button>
      @endunless
    </div>

    <div class="col-lg-9">
      <div class="seat-map-wrap">
        <div class="seat-map-stage">{{ trans('file.Stage') }}</div>
        <div id="seat-canvas" class="seat-map-canvas"
             style="width:{{ $layout->canvas_width }}px;height:{{ $layout->canvas_height }}px;@if($layout->background_image)background-image:url('{{ asset($layout->background_image) }}');background-size:cover;@endif">
        </div>
        <div class="seat-map-legend mt-2 text-white-50">
          <span>Drag seats · {{ count($seatsFlat) }} seats</span>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
.seat-map-wrap { background:#1a1a2e; border-radius:12px; padding:20px; overflow:auto; }
.seat-map-stage { background:linear-gradient(135deg,#e87722,#ff9533); color:#fff; text-align:center; font-weight:800; padding:12px; border-radius:8px; margin-bottom:16px; letter-spacing:2px; }
.seat-map-canvas { position:relative; background:#2d2d44; border:2px dashed rgba(255,255,255,.15); border-radius:8px; margin:0 auto; }
.seat-block { position:absolute; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; color:#fff; border-radius:6px; cursor:{{ $readonly ? 'default' : 'move' }}; user-select:none; border:2px solid rgba(255,255,255,.35); box-shadow:0 2px 8px rgba(0,0,0,.3); background:#3d5afe; }
.seat-block.is-selected { outline:3px solid #fff; outline-offset:2px; }
.seat-block.is-restricted { opacity:.75; border-style:dashed; }
.seat-block.is-standing { border-radius:50%; }
.level-row, .section-row { padding:8px; border:1px solid #e7edf5; border-radius:8px; margin-bottom:8px; font-size:13px; }
.level-row.active, .section-row.active { border-color:#e87722; background:#fff8f2; }
</style>

<script>
(function () {
  var hallId = {{ (int) $hall->id }};
  var layoutId = {{ (int) $layout->id }};
  var readonly = @json((bool) $readonly);
  var csrf = @json(csrf_token());
  var levels = @json($levels);
  var sections = @json($sections);
  var seats = @json($seatsFlat);
  var canvas = document.getElementById('seat-canvas');
  var selectedId = null;
  var activeLevelId = levels.length ? levels[0].id : null;
  var activeSectionId = null;
  var baseUrl = '/admin/halls/' + hallId + '/layouts/' + layoutId;

  function post(url, body) {
    return fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
      body: JSON.stringify(body || {})
    }).then(function (r) { return r.json().then(function (j) { if (!r.ok) throw j; return j; }); });
  }

  function renderLevels() {
    var el = document.getElementById('level-list');
    el.innerHTML = '';
    levels.forEach(function (lv) {
      var d = document.createElement('div');
      d.className = 'level-row' + (lv.id === activeLevelId ? ' active' : '');
      d.innerHTML = '<strong>' + lv.name + '</strong> <small class="text-muted">(' + lv.code + ')</small>';
      d.onclick = function () { activeLevelId = lv.id; renderLevels(); renderSections(); };
      el.appendChild(d);
    });
  }

  function renderSections() {
    var el = document.getElementById('section-list');
    var sel = document.getElementById('gen-section');
    el.innerHTML = '';
    if (sel) sel.innerHTML = '';
    sections.filter(function (s) { return !activeLevelId || s.level_id === activeLevelId; }).forEach(function (s) {
      var d = document.createElement('div');
      d.className = 'section-row' + (s.id === activeSectionId ? ' active' : '');
      d.innerHTML = '<strong>' + s.name + '</strong> <small class="text-muted">' + s.code + ' · ' + s.type + '</small>';
      d.onclick = function () { activeSectionId = s.id; renderSections(); };
      el.appendChild(d);
      if (sel) {
        var o = document.createElement('option');
        o.value = s.id; o.textContent = s.name + ' (' + s.code + ')';
        if (s.id === activeSectionId) o.selected = true;
        sel.appendChild(o);
      }
    });
    if (!activeSectionId && sel && sel.options.length) {
      activeSectionId = parseInt(sel.value, 10);
    }
  }

  function renderSeats() {
    canvas.innerHTML = '';
    canvas.style.width = (document.getElementById('map-width') ? document.getElementById('map-width').value : {{ $layout->canvas_width }}) + 'px';
    canvas.style.height = (document.getElementById('map-height') ? document.getElementById('map-height').value : {{ $layout->canvas_height }}) + 'px';
    seats.forEach(function (seat) {
      var div = document.createElement('div');
      div.className = 'seat-block' + (seat.id === selectedId ? ' is-selected' : '') + (seat.restricted_view ? ' is-restricted' : '') + (seat.seat_type === 'standing' ? ' is-standing' : '');
      div.style.left = seat.pos_x + 'px';
      div.style.top = seat.pos_y + 'px';
      div.style.width = seat.width + 'px';
      div.style.height = seat.height + 'px';
      div.textContent = seat.label;
      div.dataset.id = seat.id;
      if (!readonly) {
        div.onmousedown = function (e) {
          if (e.target.classList.contains('resize-handle')) return;
          selectedId = seat.id;
          renderSeats();
          var startX = e.clientX, startY = e.clientY, ox = seat.pos_x, oy = seat.pos_y;
          function move(ev) {
            seat.pos_x = Math.max(0, ox + (ev.clientX - startX));
            seat.pos_y = Math.max(0, oy + (ev.clientY - startY));
            div.style.left = seat.pos_x + 'px';
            div.style.top = seat.pos_y + 'px';
          }
          function up() { document.removeEventListener('mousemove', move); document.removeEventListener('mouseup', up); }
          document.addEventListener('mousemove', move);
          document.addEventListener('mouseup', up);
        };
        var handle = document.createElement('div');
        handle.className = 'resize-handle';
        handle.style.cssText = 'position:absolute;right:0;bottom:0;width:10px;height:10px;cursor:se-resize;background:rgba(255,255,255,.5);border-radius:2px;';
        handle.onmousedown = function (e) {
          e.stopPropagation();
          var startX = e.clientX, startY = e.clientY, ow = seat.width, oh = seat.height;
          function move(ev) {
            seat.width = Math.max(20, Math.min(200, ow + (ev.clientX - startX)));
            seat.height = Math.max(20, Math.min(200, oh + (ev.clientY - startY)));
            div.style.width = seat.width + 'px';
            div.style.height = seat.height + 'px';
          }
          function up() { document.removeEventListener('mousemove', move); document.removeEventListener('mouseup', up); }
          document.addEventListener('mousemove', move);
          document.addEventListener('mouseup', up);
        };
        div.appendChild(handle);
      }
      canvas.appendChild(div);
    });
  }

  if (!readonly) {
    document.getElementById('btn-save-settings').onclick = function () {
      post(baseUrl + '/settings', {
        label: document.getElementById('layout-label').value,
        canvas_width: parseInt(document.getElementById('map-width').value, 10),
        canvas_height: parseInt(document.getElementById('map-height').value, 10)
      }).then(function () { alert('Saved'); renderSeats(); }).catch(function (e) { alert(e.message || 'Failed'); });
    };

    document.getElementById('btn-add-level').onclick = function () {
      var name = prompt('Level name (e.g. Ground, Balcony)');
      if (!name) return;
      var code = prompt('Code (e.g. G, BAL)', name.substring(0, 3).toUpperCase());
      post(baseUrl + '/levels', { name: name, code: code || 'LV', sort_order: levels.length }).then(function (j) {
        levels = j.levels; activeLevelId = levels[levels.length - 1].id; renderLevels(); renderSections();
      });
    };

    document.getElementById('btn-add-section').onclick = function () {
      if (!activeLevelId) { alert('Add a level first'); return; }
      var name = prompt('Section name (e.g. VIP Block, Regular Left)');
      if (!name) return;
      var code = prompt('Code', name.replace(/\s+/g, '').substring(0, 8).toUpperCase());
      var type = prompt('Type: seating / standing / stage / aisle / restricted', 'seating') || 'seating';
      post(baseUrl + '/sections', { level_id: activeLevelId, name: name, code: code, type: type, sort_order: sections.length }).then(function (j) {
        sections = j.sections; activeSectionId = sections[sections.length - 1].id; renderSections();
      });
    };

    document.getElementById('btn-generate').onclick = function () {
      var sectionId = parseInt(document.getElementById('gen-section').value, 10);
      if (!sectionId) { alert('Select a section'); return; }
      post(baseUrl + '/generate-rows', {
        section_id: sectionId,
        row_from: document.getElementById('gen-from').value,
        row_to: document.getElementById('gen-to').value,
        seats_per_row: parseInt(document.getElementById('gen-count').value, 10),
        start_number: parseInt(document.getElementById('gen-start').value, 10),
        origin_x: parseInt(document.getElementById('gen-x').value, 10),
        origin_y: parseInt(document.getElementById('gen-y').value, 10),
        label_prefix: document.getElementById('gen-prefix').value || null,
        seat_type: document.getElementById('gen-type').value,
        curve: parseFloat(document.getElementById('gen-curve').value || '0'),
        restricted_view: document.getElementById('gen-restricted').checked
      }).then(function (j) {
        seats = j.seats; renderSeats(); alert('Generated ' + j.seats.length + ' seats');
      }).catch(function (e) { alert(e.message || (e.error || 'Failed')); });
    };

    document.getElementById('btn-save-seats').onclick = function () {
      post(baseUrl + '/seats', { seats: seats }).then(function (j) {
        seats = j.seats; renderSeats(); alert('Seats saved');
      }).catch(function (e) { alert(e.message || 'Failed'); });
    };

    document.getElementById('btn-upload-bg').onclick = function () {
      var f = document.getElementById('bg-file').files[0];
      if (!f) { alert('Choose a file'); return; }
      var fd = new FormData();
      fd.append('background', f);
      fd.append('_token', csrf);
      fetch(baseUrl + '/background', { method: 'POST', body: fd, headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } })
        .then(function (r) { return r.json(); })
        .then(function (j) {
          if (j.background_image) {
            canvas.style.backgroundImage = "url('" + j.background_image + "')";
            canvas.style.backgroundSize = 'cover';
          }
        });
    };
  }

  if (sections.length) activeSectionId = sections[0].id;
  renderLevels();
  renderSections();
  renderSeats();
})();
</script>
@endsection

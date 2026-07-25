@extends('layout.main')
@section('content')
@if(session('message'))
  <div class="alert alert-success text-center">{{ session('message') }}</div>
@endif
@if(session('not_permitted'))
  <div class="alert alert-danger text-center">{{ session('not_permitted') }}</div>
@endif
<section class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3>{{ trans('file.Event seat map') }} — {{ $product->name }}</h3>
      <p class="text-muted mb-0">Attach a published hall layout to snapshot inventory for this ticket product.</p>
    </div>
    <div>
      <a href="{{ route('products.edit', $product->id) }}" class="btn btn-secondary">{{ trans('file.Back') }}</a>
      @if($map)
        <a href="{{ route('products.event_seat_inventory', $product->id) }}" class="btn btn-info">{{ trans('file.Inventory') }}</a>
      @endif
    </div>
  </div>

  @if($map)
    <div class="alert alert-info">
      Current map: <strong>{{ optional(optional($map->layoutVersion)->hall)->name }}</strong>
      layout v{{ optional($map->layoutVersion)->version }}
      · {{ $map->inventory()->count() }} seats
      · status {{ $map->status }}
      @if($map->isLocked())
        <span class="badge badge-danger">locked (has sales)</span>
      @endif
    </div>
  @endif

  <div class="card">
    <div class="card-body">
      <form method="post" action="{{ route('products.event_seat_map.attach', $product->id) }}">
        @csrf
        <div class="form-group">
          <label>Published hall layout *</label>
          <select name="layout_id" class="form-control" required>
            <option value="">— select —</option>
            @foreach($layouts as $layout)
              <option value="{{ $layout->id }}" @if($map && $map->hall_layout_version_id == $layout->id) selected @endif>
                {{ $layout->hall->name ?? 'Hall' }} — v{{ $layout->version }} ({{ $layout->label }})
              </option>
            @endforeach
          </select>
        </div>
        <p class="text-muted small">Replacing a map deletes unsold inventory. Maps with sold seats cannot be replaced.</p>
        <button type="submit" class="btn btn-primary" @if($map && $map->isLocked()) disabled @endif>
          {{ $map ? 'Replace / re-snapshot layout' : 'Attach layout & create inventory' }}
        </button>
      </form>
    </div>
  </div>
</section>
@endsection

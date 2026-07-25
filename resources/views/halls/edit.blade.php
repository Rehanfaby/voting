@extends('layout.main')
@section('content')
@if(session('message'))
  <div class="alert alert-success text-center">{{ session('message') }}</div>
@endif
<section class="container-fluid">
  <h3 class="mb-3">{{ trans('file.edit') }} — {{ $hall->name }}</h3>
  <div class="row">
    <div class="col-lg-5">
      <div class="card mb-3">
        <div class="card-body">
          <form method="post" action="{{ route('halls.update', $hall->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label>{{ trans('file.Name') }} *</label>
              <input type="text" name="name" class="form-control" required value="{{ $hall->name }}">
            </div>
            <div class="form-group">
              <label>{{ trans('file.City') }}</label>
              <input type="text" name="city" class="form-control" value="{{ $hall->city }}">
            </div>
            <div class="form-group">
              <label>{{ trans('file.Address') }}</label>
              <input type="text" name="address" class="form-control" value="{{ $hall->address }}">
            </div>
            <div class="form-group">
              <label>{{ trans('file.Notes') }}</label>
              <textarea name="notes" class="form-control" rows="3">{{ $hall->notes }}</textarea>
            </div>
            <label class="d-block mb-3"><input type="checkbox" name="is_active" value="1" {{ $hall->is_active ? 'checked' : '' }}> {{ trans('file.Active') }}</label>
            <button class="btn btn-primary" type="submit">{{ trans('file.update') }}</button>
            <a href="{{ route('halls.index') }}" class="btn btn-secondary">{{ trans('file.Back') }}</a>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-7">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>{{ trans('file.Layout versions') }}</span>
        </div>
        <div class="table-responsive">
          <table class="table mb-0">
            <thead><tr><th>Version</th><th>Label</th><th>Status</th><th></th></tr></thead>
            <tbody>
              @foreach($hall->layoutVersions as $layout)
                <tr>
                  <td>v{{ $layout->version }}</td>
                  <td>{{ $layout->label }}</td>
                  <td>
                    @if($layout->status === 'published')
                      <span class="badge badge-success">published</span>
                    @else
                      <span class="badge badge-warning">draft</span>
                    @endif
                  </td>
                  <td class="text-right">
                    <a class="btn btn-sm btn-info" href="{{ route('halls.layouts.edit', [$hall->id, $layout->id]) }}">{{ trans('file.Seat map') }}</a>
                    @if($layout->status === 'published')
                      <form class="d-inline" method="post" action="{{ route('halls.layouts.fork', [$hall->id, $layout->id]) }}">
                        @csrf
                        <button class="btn btn-sm btn-secondary" type="submit">{{ trans('file.New draft') }}</button>
                      </form>
                    @else
                      <form class="d-inline" method="post" action="{{ route('halls.layouts.publish', [$hall->id, $layout->id]) }}">
                        @csrf
                        <button class="btn btn-sm btn-success" type="submit" onclick="return confirm('Publish this layout? It becomes immutable.')">{{ trans('file.Publish') }}</button>
                      </form>
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
@endsection

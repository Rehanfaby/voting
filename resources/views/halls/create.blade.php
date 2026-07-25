@extends('layout.main')
@section('content')
<section class="container-fluid">
  <h3 class="mb-3">{{ trans('file.Add Hall') }}</h3>
  <div class="card">
    <div class="card-body">
      <form method="post" action="{{ route('halls.store') }}">
        @csrf
        <div class="form-group">
          <label>{{ trans('file.Name') }} *</label>
          <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>
        <div class="form-group">
          <label>{{ trans('file.City') }}</label>
          <input type="text" name="city" class="form-control" value="{{ old('city', 'Yaoundé') }}">
        </div>
        <div class="form-group">
          <label>{{ trans('file.Address') }}</label>
          <input type="text" name="address" class="form-control" value="{{ old('address') }}">
        </div>
        <div class="form-group">
          <label>{{ trans('file.Notes') }}</label>
          <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
        </div>
        <button class="btn btn-primary" type="submit">{{ trans('file.submit') }}</button>
        <a href="{{ route('halls.index') }}" class="btn btn-secondary">{{ trans('file.Back') }}</a>
      </form>
    </div>
  </div>
</section>
@endsection

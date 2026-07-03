@extends('layout.main')
@section('content')
@if(session()->has('message'))
<div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert">&times;</button>{!! session()->get('message') !!}</div>
@endif

<section class="container-fluid">
    @include('about_us.partials.frontend-preview', ['previewSection' => 'values', 'about' => $about])

    <div class="card">
        <div class="card-header"><h4>{{ trans('file.Our Values') }}</h4></div>
        <div class="card-body">
            <p class="text-muted">{{ trans('file.About values help') }}</p>
            {!! Form::open(['route' => 'about_us.values.store', 'method' => 'post']) !!}
            <div class="form-group">
                <label>{{ trans('file.Section heading') }}</label>
                <input type="text" name="values_heading" class="form-control" value="{{ $about['values_heading'] ?? '' }}" placeholder="{{ trans('file.Our Values') }}">
            </div>
            <div class="form-group">
                <label>{{ trans('file.Values comma separated') }}</label>
                <textarea name="values" class="form-control" rows="3" placeholder="Excellence, Integrity, Spirit-led worship, Purity, Innovation, Performance">{{ $about['values'] ?? '' }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div>
</section>
@endsection

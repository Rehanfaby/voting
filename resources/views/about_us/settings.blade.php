@extends('layout.main')
@section('content')
@if(session()->has('message'))
<div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert">&times;</button>{!! session()->get('message') !!}</div>
@endif

<section class="container-fluid">
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item"><a class="nav-link active" href="{{ route('about_us.settings') }}">{{ trans('file.About Page Content') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('about_us.index') }}">{{ trans('file.Leadership Team') }}</a></li>
    </ul>

    <div class="card">
        <div class="card-header"><h4>{{ trans('file.Edit About Us Page') }}</h4></div>
        <div class="card-body">
            <p class="text-muted">{{ trans('file.Leave a field empty to use the default translated text on the website.') }}</p>
            {!! Form::open(['route' => 'about_us.settings.store', 'method' => 'post', 'files' => true]) !!}

            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>{{ trans('file.Mission image') }}</label>
                        @php $img = $about['image'] ?? null; @endphp
                        @if($img && file_exists(public_path($img)))
                            <div class="mb-2"><img src="{{ url($img) }}" alt="" style="max-width:100%;border-radius:12px;max-height:280px;object-fit:cover;"></div>
                        @endif
                        <input type="file" name="about_image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group"><label>{{ trans('file.Hero subtitle') }}</label><textarea name="hero_subtitle" class="form-control" rows="2">{{ $about['hero_subtitle'] ?? '' }}</textarea></div>
                    <div class="form-group"><label>{{ trans('file.Mission heading') }}</label><input type="text" name="mission_title" class="form-control" value="{{ $about['mission_title'] ?? '' }}"></div>
                    <div class="form-group"><label>{{ trans('file.Heart badge text') }}</label><input type="text" name="heart_badge" class="form-control" value="{{ $about['heart_badge'] ?? '' }}"></div>
                </div>
            </div>

            <div class="form-group"><label>{{ trans('file.Mission paragraph') }} 1</label><textarea name="mission_p1" class="form-control" rows="3">{{ $about['mission_p1'] ?? '' }}</textarea></div>
            <div class="form-group"><label>{{ trans('file.Mission paragraph') }} 2</label><textarea name="mission_p2" class="form-control" rows="3">{{ $about['mission_p2'] ?? '' }}</textarea></div>
            <div class="form-group"><label>{{ trans('file.Mission paragraph') }} 3</label><textarea name="mission_p3" class="form-control" rows="3">{{ $about['mission_p3'] ?? '' }}</textarea></div>

            <hr>
            <div class="form-group"><label>{{ trans('file.Intro heading') }}</label><input type="text" name="intro_title" class="form-control" value="{{ $about['intro_title'] ?? '' }}"></div>
            <div class="form-group"><label>{{ trans('file.Intro text') }}</label><textarea name="intro_text" class="form-control" rows="4">{{ $about['intro_text'] ?? '' }}</textarea></div>
            <div class="form-group"><label>{{ trans('file.Regions comma separated') }}</label><input type="text" name="regions" class="form-control" value="{{ $about['regions'] ?? '' }}"></div>

            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div>
</section>
@endsection

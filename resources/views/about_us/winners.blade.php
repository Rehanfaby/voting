@extends('layout.main')
@section('content')
@if($errors->any())
<div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ $errors->first() }}</div>
@endif
@if(session()->has('message'))
<div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert">&times;</button>{!! session()->get('message') !!}</div>
@endif

<section class="container-fluid">
    @include('about_us.partials.frontend-preview', ['previewSection' => 'winners', 'about' => $about, 'previewWinners' => $winners, 'previewYear' => $year])

    <div class="card">
        <div class="card-header"><h4>{{ trans('file.Winners') }}</h4></div>
        <div class="card-body">
            <p class="text-muted">{{ trans('file.About winners help') }}</p>
            {!! Form::open(['route' => 'about_us.winners.store', 'method' => 'post', 'files' => true]) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ trans('file.Year') }}</label>
                        <input type="text" name="winners_year" class="form-control" value="{{ $year }}" placeholder="2025">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>{{ trans('file.Section heading') }}</label>
                        <input type="text" name="winners_heading" class="form-control" value="{{ $about['winners_heading'] ?? '' }}" placeholder="{{ $year }} {{ trans('file.Winners') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach(\App\AboutWinner::PLACEMENTS as $placement => $placementLabel)
                @php $winner = $winners[$placement]; @endphp
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header"><strong>{{ $placementLabel }}</strong></div>
                        <div class="card-body">
                            @if($winner->image)
                                <div class="mb-3 text-center">
                                    <img src="{{ url('public/images/employee', $winner->image) }}" alt="" style="width:120px;height:120px;object-fit:cover;border-radius:50%;">
                                </div>
                            @endif
                            <div class="form-group">
                                <label>{{ trans('file.name') }}</label>
                                <input type="text" name="name_{{ $placement }}" class="form-control" value="{{ $winner->name }}">
                            </div>
                            <div class="form-group">
                                <label>{{ trans('file.Description') }}</label>
                                <textarea name="bio_{{ $placement }}" class="form-control" rows="4">{{ $winner->bio }}</textarea>
                            </div>
                            <div class="form-group mb-0">
                                <label>{{ trans('file.Image') }}</label>
                                <input type="file" name="image_{{ $placement }}" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div>
</section>
@endsection

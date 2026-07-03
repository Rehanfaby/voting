@extends('layout.main')
@section('content')
@if(session()->has('message'))
<div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert">&times;</button>{!! session()->get('message') !!}</div>
@endif
@if(session()->has('not_permitted'))
<div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session()->get('not_permitted') }}</div>
@endif

<section class="forms">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4><i class="dripicons-document"></i> {{ trans('file.Environment File') }} (.env)</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ trans('file.Env editor help') }}</p>
                {!! Form::open(['route' => 'setting.env.store', 'method' => 'post']) !!}
                <div class="form-group">
                    <textarea name="env_contents" class="form-control" rows="28" style="font-family:monospace;font-size:13px;">{{ old('env_contents', $contents) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary" onclick="return confirm('{{ trans('file.Save changes to environment file?') }}')">{{ trans('file.submit') }}</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">$("#env-setting-menu").addClass("active");</script>
@endsection

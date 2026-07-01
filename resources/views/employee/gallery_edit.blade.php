@extends('layout.main') @section('content')
    @if($errors->has('name'))
        <div class="alert alert-danger alert-dismissible text-center">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('name') }}</div>
    @endif
    @if($errors->has('image'))
        <div class="alert alert-danger alert-dismissible text-center">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('image') }}</div>
    @endif
    @if($errors->has('email'))
        <div class="alert alert-danger alert-dismissible text-center">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('email') }}</div>
    @endif
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div>
    @endif
    @if(session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif
    <section class="forms">


        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>{{trans('file.Add Musician Gallery')}}</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                            {!! Form::open(['route' => 'musician.gallery.update' , 'method' => 'post', 'files' => true]) !!}

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.File Type')}}</label>
                                        <select name="type" class="form-control">
                                            <option value="">--Choose--</option>
                                            <option value="image" {{ $lims_employee_gallery->type == "image" ? "selected" : "" }}>Image</option>
                                            <option value="video" {{ $lims_employee_gallery->type == 'video' ? "selected" : "" }}>Video</option>
                                            <option value="audio" {{ $lims_employee_gallery->type == 'audio' ? "selected" : "" }}>Audio</option>
                                            <option value="link" {{ $lims_employee_gallery->type == 'link' ? "selected" : "" }}>Youtube video</option>
                                            <option value="short" {{ $lims_employee_gallery->type == 'short' ? "selected" : "" }}>Short Video Link</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>{{trans('file.File')}}</label>
                                        <input type="file" name="file" class="form-control" required>
                                        <input type="text" style="display: none" name="file_path" class="form-control" value="{{ $lims_employee_gallery->file }}" placeholder="youtube short link">
                                        @if($errors->has('file'))
                                            <span>
                                       <strong>{{ $errors->first('file') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mt-4">
                                        <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        $("ul#people").siblings('a').attr('aria-expanded','true');
        $("ul#people").addClass("show");
        $("ul#people #employee-menu").addClass("active");

        $('select[name="type"]').on('change', function() {
            if ($(this).val() == 'link' || $(this).val() == 'short') {
                $('input[name="file"]').prop('required', false);
                $('input[name="file"]').hide(300);
                $('input[name="file_path"]').show(300);
                $('input[name="file_path"]').prop('required', true);
            } else {
                $('input[name="file_path"]').prop('required', false);
                $('input[name="file_path"]').hide(300);
                $('input[name="file"]').show(300);
                $('input[name="file"]').prop('required', true);
            }
        });

    </script>
@endsection

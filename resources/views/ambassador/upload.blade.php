@extends('layout.main') @section('content')
    <section class="forms">

{{--        @if(in_array("users-add", $all_permission))--}}
            <div class="container-fluid my-3">
                <a href="{{route('musician.gallery', $lims_employee_data->id)}}" class="btn btn-info"><i class="dripicons-list"></i> Show Gallery</a>
            </div>
{{--        @endif--}}

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>{{trans('file.Add Musician Gallery')}}</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                            {!! Form::open(['route' => 'musician.file.store', 'method' => 'post', 'files' => true]) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.name')}} *</strong> </label>
                                        <input type="text" name="employee_name" required class="form-control">
                                        <input type="hidden" name="employee_id" value="{{ $lims_employee_data->id }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{trans('file.File Type')}}</label>
                                        <select name="type" class="form-control">
                                            <option value="">--Choose--</option>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                            <option value="audio">Audio</option>
                                            <option value="link">Link</option>
                                            <option value="short">Short Video Link</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>{{trans('file.File')}}</label>
                                        <input type="file" name="file" class="form-control" required>
                                        <input type="text" style="display: none" name="file_path" class="form-control" placeholder="youtube path">
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

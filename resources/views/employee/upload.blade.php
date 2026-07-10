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

        <div class="container-fluid my-3">
            <a href="{{route('musician.gallery', $lims_employee_data->id)}}" class="btn btn-info"><i class="dripicons-list"></i> {{ trans('file.Show Gallery') }}</a>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>{{trans('file.Add Musician Gallery')}}</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic"><small>{{ trans('file.Upload files or paste social links for the contestant voting page') }}</small></p>
                            {!! Form::open(['route' => 'musician.file.store', 'method' => 'post', 'files' => true, 'id' => 'contestant-upload-form']) !!}
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>{{trans('file.name')}}</label>
                                        <input type="text" name="employee_name" class="form-control" placeholder="{{ trans('file.Optional caption or label') }}">
                                        <input type="hidden" name="employee_id" value="{{ $lims_employee_data->id }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{trans('file.File Type')}} *</label>
                                        <select name="type" id="upload-type" class="form-control" required>
                                            <option value="">-- {{ trans('file.Choose') }} --</option>
                                            <optgroup label="{{ trans('file.Files') }}">
                                                <option value="image">{{ trans('file.Image') }}</option>
                                                <option value="video">{{ trans('file.Video') }}</option>
                                                <option value="audio">{{ trans('file.Audio') }}</option>
                                            </optgroup>
                                            <optgroup label="{{ trans('file.Social links') }}">
                                                <option value="youtube">{{ trans('file.YouTube') }}</option>
                                                <option value="short">{{ trans('file.YouTube Short') }}</option>
                                                <option value="tiktok">{{ trans('file.TikTok') }}</option>
                                                <option value="instagram">{{ trans('file.Instagram') }}</option>
                                                <option value="facebook">{{ trans('file.Facebook') }}</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="form-group" id="upload-file-group">
                                        <label>{{trans('file.File')}} *</label>
                                        <input type="file" name="file" id="upload-file" class="form-control">
                                    </div>
                                    <div class="form-group" id="upload-link-group" style="display:none;">
                                        <label>{{ trans('file.Paste link') }} *</label>
                                        <input type="url" name="file_path" id="upload-link" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                                        <small class="text-muted">{{ trans('file.Paste the full TikTok YouTube Instagram or Facebook URL') }}</small>
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

        var linkTypes = ['link', 'short', 'youtube', 'tiktok', 'instagram', 'facebook'];
        function toggleUploadFields() {
            var type = $('#upload-type').val();
            var isLink = linkTypes.indexOf(type) !== -1;
            if (isLink) {
                $('#upload-file-group').hide();
                $('#upload-file').prop('required', false);
                $('#upload-link-group').show();
                $('#upload-link').prop('required', true);
            } else {
                $('#upload-link-group').hide();
                $('#upload-link').prop('required', false);
                $('#upload-file-group').show();
                $('#upload-file').prop('required', true);
            }
        }
        $('#upload-type').on('change', toggleUploadFields);
        toggleUploadFields();
    </script>
@endsection

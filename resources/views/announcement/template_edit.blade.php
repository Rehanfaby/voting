@extends('layout.main') @section('content')
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first() }}</div>
    @endif
    <section class="forms">
        <div class="container-fluid">
            <a href="{{ route('announcement.templates') }}" class="btn btn-info"><i class="dripicons-list"></i> {{ trans('file.Templates') }}</a>
            <div class="card mt-3">
                <div class="card-header"><h4>{{ trans('file.Edit Template') }}</h4></div>
                <div class="card-body">
                    <form method="post" action="{{ route('announcement.template.update', $template->id) }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('file.name') }} *</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $template->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('file.Subject') }}</label>
                                    <input type="text" name="subject" class="form-control" value="{{ old('subject', $template->subject) }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bg-success"><b>{{ trans('file.Header') }}</b></label>
                                    <textarea name="header" id="header" class="form-control">{{ old('header', $template->header) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bg-warning"><b>{{ trans('file.Body') }}</b></label>
                                    <textarea name="body" id="body" class="form-control">{{ old('body', $template->body) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bg-danger"><b>{{ trans('file.Footer') }}</b></label>
                                    <textarea name="footer" id="footer" class="form-control">{{ old('footer', $template->footer) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ $template->is_active ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">{{ trans('file.Active') }}</label>
                                </div>
                                <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script>
        $("#announcement-top-menu").addClass("active");
        var tinyCfg = {
            height: 200,
            plugins: ['advlist autolink lists link charmap print preview anchor textcolor','searchreplace visualblocks code fullscreen','insertdatetime media table contextmenu paste code wordcount'],
            toolbar: 'insert | undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
            branding:false
        };
        tinymce.init(Object.assign({ selector: '#header' }, tinyCfg));
        tinymce.init(Object.assign({ selector: '#body', height: 320 }, tinyCfg));
        tinymce.init(Object.assign({ selector: '#footer' }, tinyCfg));
    </script>
@endsection

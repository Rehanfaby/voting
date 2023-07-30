@extends('layout.main') @section('content')
    @if($errors->has('name'))
        <div class="alert alert-danger alert-dismissible text-center">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('name') }}</div>
    @endif
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif
    @if(session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if(in_array("donor-index", $all_permission))
                    <a href="{{route('letter.index')}}" class="btn btn-info"><i class="dripicons-list"></i> {{trans('file.Letters List')}} </a>
                @endif
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.Add Letter')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        <form action="{{ route('letter.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select People Type</label>
                                    <select class="form-control" name="people_type">
                                        <option value="user">--Employee--</option>
                                        <option value="customer">--Customer--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{trans('file.Letter Category')}} </label>
                                    <select class="form-control" name="category_id">
                                        <option value="">-- Default --</option>
                                    @foreach($category as $cat)
                                            <option value="{{$cat->id}}">{{$cat->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{trans('file.Letter Template')}} </label>
                                    <select class="form-control" name="template_id">
                                        <option value="">-- Blank --</option>
                                        @foreach($template as $tem)
                                            <option value="{{$tem->id}}">{{$tem->subject}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{trans('file.Author Name')}} <strong>*</strong> </label>
                                    <input type="text" name="name" required class="form-control">
                                    <input type="hidden" name="is_active" value="1">
                                    <input type="hidden" name="is_approve" value="0">
                                    <input type="hidden" name="is_sign" value="0">
                                </div>
                            </div>
                            <div class="col-md-12 users">
                                <div class="form-group">
                                    <label>{{trans('file.To')}} <strong>*</strong></label>
                                    <select name="to[]" required class="selectpicker form-control to-user" data-live-search="true" multiple>
                                        <option value="">--choose any --</option>
                                        @foreach($user as $u)
                                            <option value="{{$u->id}}">{{$u->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 customers">
                                <div class="form-group">
                                    <label>{{trans('file.To')}} <strong>*</strong></label>
                                    <select name="to_customer[]" class="selectpicker form-control to-customer" data-live-search="true" multiple>
                                        <option value="">--choose any --</option>
                                        @foreach($customer as $u)
                                            <option value="{{$u->id}}">{{$u->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 users">
                                <div class="form-group">
                                    <label>{{trans('file.CC')}} <strong>*</strong></label>
                                    <select name="cc[]" class="selectpicker form-control" data-live-search="true" multiple>
                                        <option value="">--choose any --</option>
                                        @foreach($user as $u)
                                            <option value="{{$u->id}}">{{$u->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 customers">
                                <div class="form-group">
                                    <label>{{trans('file.CC')}} <strong>*</strong></label>
                                    <select name="cc_customer[]" class="selectpicker form-control" data-live-search="true" multiple>
                                        <option value="">--choose any --</option>
                                        @foreach($customer as $u)
                                            <option value="{{$u->id}}">{{$u->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.Subject')}} <strong>*</strong> </label>
                                    <input type="text" name="subject" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Attachment </label>
                                    <input type="file" name="attachment" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{trans('file.Header')}} </label>
                                    <textarea name="header" class="form-control" rows="2" id="header"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{trans('file.Body')}} </label>
                                    <textarea name="body" class="form-control" rows="4" id="body"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{trans('file.Footer')}} </label>
                                    <textarea name="footer" class="form-control" rows="2" id="footer"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="checkbox" name="is_template"> &nbsp;
                                    <label><b>{{trans('file.Is Template')}}</b> </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mt-4">
                                    <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

    $(document).ready(function() {
        $('select[name="template_id"]').on('change', function() {
            var url = "/letters/template/info/"
            var id = $(this).val();
            url = url.concat(id);
            $.get(url, function(data) {
                console.log(data);
                $("input[name='subject']").val(data['subject']);
                $("input[name='name']").val(data['name']);
                tinymce.get("header").setContent(data['header']);
                tinymce.get("body").setContent(data['body']);
                tinymce.get("footer").setContent(data['footer']);
            });
        });
    })


    $(".customers").hide();
    $('select[name="people_type"]').on('change', function() {
        if ($(this).val() == "user") {
            $('.to-customer').prop('required',false);
            $('.to-user').prop('required',true);
            $(".customers").hide();
            $(".users").show();
        }else{
            $('.to-customer').prop('required',true);
            $('.to-user').prop('required',false);
            $(".users").hide();
            $(".customers").show();
        }
    });
    $("ul#letter").siblings('a').attr('aria-expanded','true');
    $("ul#letter").addClass("show");
    $("ul#letter #letter-create-menu").addClass("active");

    tinymce.init({
        selector: '#header',
        height: 130,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor textcolor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code wordcount'
        ],
        toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
        branding:false
    });

    tinymce.init({
        selector: '#body',
        height: 130,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor textcolor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code wordcount'
        ],
        toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
        branding:false
    });

    tinymce.init({
        name: 'footer',
        selector: '#footer',
        height: 130,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor textcolor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code wordcount'
        ],
        toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
        branding:false
    });

</script>
@endsection

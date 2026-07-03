@extends('layout.main')

@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mg-ticket-form-card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{ trans('file.add_product') }}</h4>
                    </div>
                    <div class="card-body">
                        <p class="mg-ticket-form__hint">{{ trans('file.The field labels marked with * are required input fields') }}</p>
                        <form id="product-form" class="mg-ticket-form">
                            <input type="hidden" name="type" value="standard">
                            <input type="hidden" name="barcode_symbology" value="C128">
                            <input type="hidden" name="alert_quantity" value="0">
                            <input type="hidden" name="tax_method" value="1">
                            <input type="hidden" name="cost" id="ticket-cost-hidden" value="0">

                            <div class="mg-ticket-form__section">
                                <h5 class="mg-ticket-form__title">{{ trans('file.Ticket details') }}</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ trans('file.Product Name') }} *</label>
                                            <input type="text" name="name" class="form-control" id="name" required>
                                            <span class="validation-msg" id="name-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ trans('file.Product Code') }} *</label>
                                            <div class="input-group">
                                                <input type="text" name="code" class="form-control" id="code" required>
                                                <div class="input-group-append">
                                                    <button id="genbtn" type="button" class="btn btn-sm btn-default" title="{{ trans('file.Generate') }}"><i class="fa fa-refresh"></i></button>
                                                </div>
                                            </div>
                                            <span class="validation-msg" id="code-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 category_id">
                                        <div class="form-group">
                                            <label>{{ trans('file.category') }} *</label>
                                            <select name="category_id" required class="selectpicker form-control" data-live-search="true" title="Select Event...">
                                                @foreach($lims_category_list as $category)
                                                    <option value="{{ $category->id }}" {{ $general_setting->category == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="validation-msg"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ trans('file.Product Price') }} *</label>
                                            <input type="number" name="price" required class="form-control" step="any" min="0">
                                            <span class="validation-msg"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 qty">
                                        <div class="form-group">
                                            <label>{{ trans('file.Available seats') }} *</label>
                                            <input type="number" name="qty" required class="form-control" step="any" min="0" value="0">
                                            <span class="validation-msg"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ trans('file.Event Day') }}</label>
                                            <input type="date" name="event_day" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mg-ticket-form__section">
                                <div class="form-group featured mb-0">
                                    <label class="mg-ticket-form__check">
                                        <input type="checkbox" name="featured" value="1">
                                        <span>{{ trans('file.Featured') }}</span>
                                    </label>
                                    <p class="mg-ticket-form__note">{{ trans('file.Featured product will be displayed in POS') }}</p>
                                </div>
                            </div>

                            <div class="mg-ticket-form__section">
                                <label>{{ trans('file.Product Image') }}</label>
                                <p class="mg-ticket-form__note">{{ trans('file.Ticket image paste hint') }}</p>
                                <div id="imageUpload" class="dropzone mg-ticket-dropzone" tabindex="0"></div>
                                <span class="validation-msg" id="image-error"></span>
                            </div>

                            <div class="mg-ticket-form__section">
                                <div class="form-group mb-0">
                                    <label>{{ trans('file.Product Details') }}</label>
                                    <textarea name="product_details" class="form-control" rows="4" placeholder="{{ trans('file.Ticket details placeholder') }}"></textarea>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="button" value="{{ trans('file.submit') }}" id="submit-btn" class="btn btn-primary mg-ticket-form__submit">
                                    <i class="fa fa-ticket"></i> {{ trans('file.submit') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

    $("ul#product").siblings('a').attr('aria-expanded','true');
    $("ul#product").addClass("show");
    $("ul#product #product-create-menu").addClass("active");

    $('input[name="price"]').on('input', function () {
        $('#ticket-cost-hidden').val(this.value || 0);
    });

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#genbtn').on("click", function(){
        $.get('gencode', function(data){
            $("input[name='code']").val(data);
        });
    });

    tinymce.init({
        selector: 'textarea',
        height: 130,
        plugins: ['advlist autolink lists link charmap preview anchor', 'searchreplace visualblocks code fullscreen', 'insertdatetime table contextmenu paste wordcount'],
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | removeformat',
        branding: false
    });

    $(window).keydown(function(e){
        if (e.which == 13) {
            var $targ = $(e.target);
            if (!$targ.is("textarea") && !$targ.is(":button,:submit")) {
                var focusNext = false;
                $(this).find(":input:visible:not([disabled],[readonly]), a").each(function(){
                    if (this === e.target) focusNext = true;
                    else if (focusNext) { $(this).focus(); return false; }
                });
                return false;
            }
        }
    });

    Dropzone.autoDiscover = false;

    jQuery.validator.setDefaults({
        errorPlacement: function (error, element) {
            if(error.html() == 'Select Category...') error.html('This field is required.');
            $(element).closest('div.form-group').find('.validation-msg').html(error.html());
        },
        highlight: function (element) {
            $(element).closest('div.form-group').removeClass('has-success').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('div.form-group').removeClass('has-error').addClass('has-success');
            $(element).closest('div.form-group').find('.validation-msg').html('');
        }
    });

    function validate() {
        $('#ticket-cost-hidden').val($('input[name="price"]').val() || 0);
        return true;
    }

    $(".dropzone").sortable({
        items: '.dz-preview',
        cursor: 'grab',
        opacity: 0.5,
        containment: '.dropzone',
        distance: 20,
        tolerance: 'pointer',
        stop: function () {
            var queue = myDropzone.getAcceptedFiles();
            var newQueue = [];
            $('#imageUpload .dz-preview .dz-filename [data-dz-name]').each(function (count, el) {
                var name = el.innerHTML;
                queue.forEach(function(file) {
                    if (file.name === name) newQueue.push(file);
                });
            });
            myDropzone.files = newQueue;
        }
    });

    myDropzone = new Dropzone('div#imageUpload', {
        addRemoveLinks: true,
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 100,
        maxFilesize: 12,
        paramName: 'image',
        clickable: true,
        method: 'POST',
        url: '{{ route('products.store') }}',
        dictDefaultMessage: '{{ trans('file.Ticket image drop message') }}',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        renameFile: function(file) {
            return new Date().getTime() + file.name;
        },
        acceptedFiles: ".jpeg,.jpg,.png,.gif,.webp",
        init: function () {
            var dz = this;
            $('#submit-btn').on("click", function (e) {
                e.preventDefault();
                if ($("#product-form").valid() && validate()) {
                    tinyMCE.triggerSave();
                    if (dz.getAcceptedFiles().length) {
                        dz.processQueue();
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('products.store') }}',
                            data: $("#product-form").serialize(),
                            success: function () { location.href = '../products'; },
                            error: function (response) {
                                if (response.responseJSON && response.responseJSON.errors) {
                                    if (response.responseJSON.errors.name) $("#name-error").text(response.responseJSON.errors.name);
                                    if (response.responseJSON.errors.code) $("#code-error").text(response.responseJSON.errors.code);
                                }
                            }
                        });
                    }
                }
            });
            this.on('sending', function (file, xhr, formData) {
                $("#product-form").serializeArray().forEach(function (el) {
                    formData.append(el.name, el.value);
                });
            });
        },
        error: function (file, response) {
            if (response.errors && response.errors.name) {
                $("#name-error").text(response.errors.name);
                this.removeAllFiles(true);
            } else if (response.errors && response.errors.code) {
                $("#code-error").text(response.errors.code);
                this.removeAllFiles(true);
            }
        },
        successmultiple: function () { location.href = '../products'; }
    });

    document.addEventListener('paste', function (e) {
        if (!e.clipboardData || !e.clipboardData.items || !myDropzone) return;
        var target = e.target;
        if (target && (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA') && !target.closest('#imageUpload')) return;
        [].forEach.call(e.clipboardData.items, function (item) {
            if (item.type.indexOf('image') === -1) return;
            var file = item.getAsFile();
            if (file) myDropzone.addFile(file);
        });
    });

    $('#imageUpload').on('click', function () { this.focus(); });

</script>
@endsection

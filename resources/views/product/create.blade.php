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

    // Ensure Event dropdown (bootstrap-select) syncs the native <select> value.
    var $eventSelect = $('#product-form select[name="category_id"]');
    if ($eventSelect.length && typeof $eventSelect.selectpicker === 'function') {
        $eventSelect.selectpicker('refresh');
        if (!$eventSelect.val()) {
            var firstReal = $eventSelect.find('option[value!=""]').first().val();
            if (firstReal) {
                $eventSelect.selectpicker('val', firstReal);
            }
        }
    }

    $('#product-form input[name="price"]').on('input', function () {
        $('#product-form #ticket-cost-hidden').val(this.value || 0);
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
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        renameFile: function(file) {
            return new Date().getTime() + '_' + file.name;
        },
        acceptedFiles: ".jpeg,.jpg,.png,.gif,.webp",
        init: function () {
            var dz = this;

            function redirectAfterSave(response) {
                if (typeof response === 'string') {
                    try { response = JSON.parse(response); } catch (e) { response = null; }
                }
                var url = '{{ route('products.index') }}';
                if (response && response.redirect) {
                    url = response.redirect;
                }
                window.location.href = url;
            }

            function showSaveError(response) {
                var msg = '{{ trans('file.Could not save ticket') }}';
                if (response && response.errors) {
                    if (response.errors.name) {
                        $("#name-error").text(response.errors.name);
                        msg = response.errors.name;
                    }
                    if (response.errors.code) {
                        $("#code-error").text(response.errors.code);
                        msg = response.errors.code;
                    }
                } else if (typeof response === 'string' && response.indexOf('{') >= 0) {
                    try {
                        var parsed = JSON.parse(response);
                        if (parsed.message) msg = parsed.message;
                    } catch (e) {}
                }
                alert(msg);
            }

            $('#submit-btn').on("click", function (e) {
                e.preventDefault();
                // Scope to #product-form — the admin layout also has input[name=name]
                // (e.g. expense modal), and a page-wide selector reads that empty field first.
                var $form = $('#product-form');
                $form.find('#ticket-cost-hidden').val($form.find('input[name="price"]').val() || 0);
                if (typeof tinyMCE !== 'undefined' && tinyMCE.triggerSave) {
                    tinyMCE.triggerSave();
                }

                // bootstrap-select sometimes leaves the native <select> empty even when
                // the UI shows an event — read via plugin API first, then fall back.
                var $cat = $form.find('select[name="category_id"]');
                if ($cat.data('selectpicker') && typeof $cat.selectpicker === 'function') {
                    try { $cat.selectpicker('refresh'); } catch (err) {}
                }
                var category = null;
                if (typeof $cat.selectpicker === 'function') {
                    try { category = $cat.selectpicker('val'); } catch (err) {}
                }
                if (category === null || category === undefined || category === '') {
                    category = $cat.val();
                }
                if ($.isArray(category)) {
                    category = category.length ? category[0] : '';
                }

                var name = $.trim($form.find('input[name="name"]').val() || '');
                var code = $.trim($form.find('input[name="code"]').val() || '');
                var price = $form.find('input[name="price"]').val();
                var qty = $form.find('input[name="qty"]').val();
                var eventDay = $form.find('input[name="event_day"]').val();

                var missing = [];
                if (!name) missing.push('{{ trans('file.Product Name') }}');
                if (!code) missing.push('{{ trans('file.Product Code') }}');
                if (!category) missing.push('{{ trans('file.category') }}');
                if (price === '' || price === null || typeof price === 'undefined') missing.push('{{ trans('file.Product Price') }}');
                if (qty === '' || qty === null || typeof qty === 'undefined') missing.push('{{ trans('file.Available seats') }}');
                if (!eventDay) missing.push('{{ trans('file.Event Day') }}');

                if (missing.length) {
                    alert('{{ trans('file.Please fill in all required fields') }}' + ":\n- " + missing.join("\n- "));
                    return;
                }

                // Ensure the native select has the chosen value before serialize/ajax.
                $cat.val(category);

                $(this).prop('disabled', true).addClass('is-saving');

                if (dz.getAcceptedFiles().length) {
                    dz.processQueue();
                } else {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('products.store') }}',
                        data: $form.serialize(),
                        success: redirectAfterSave,
                        error: function (xhr) {
                            $('#submit-btn').prop('disabled', false).removeClass('is-saving');
                            showSaveError(xhr.responseJSON || xhr.responseText);
                        }
                    });
                }
            });

            this.on('sendingmultiple', function (files, xhr, formData) {
                $("#product-form").serializeArray().forEach(function (el) {
                    formData.append(el.name, el.value);
                });
            });

            this.on('successmultiple', function (files, response) {
                redirectAfterSave(response);
            });

            this.on('success', function (file, response) {
                redirectAfterSave(response);
            });

            this.on('errormultiple', function (files, response) {
                $('#submit-btn').prop('disabled', false).removeClass('is-saving');
                showSaveError(response);
                dz.removeAllFiles(true);
            });

            this.on('error', function (file, response) {
                $('#submit-btn').prop('disabled', false).removeClass('is-saving');
                showSaveError(response);
            });
        }
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

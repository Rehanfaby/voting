@extends('layout.main') @section('content')
    @php
        $cloneRecipients = [];
        if (!empty($clone)) {
            $cloneRecipients = \App\Helpers\AnnouncementRecipient::resolveForAnnouncement($clone);
        }
        $prefillCategory = old('audience_category', optional($clone)->audience_category ?? 'contestants');
    @endphp
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif
    @if(session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif
    <style>
        .mg-audience-tabs { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:12px; }
        .mg-audience-tabs button { border:1px solid #d8dee9; background:#fff; border-radius:999px; padding:6px 14px; font-size:12px; font-weight:600; }
        .mg-audience-tabs button.active { background:#7c5cc4; color:#fff; border-color:#7c5cc4; }
        .mg-recipient-results { max-height:220px; overflow:auto; border:1px solid #e8ecf3; border-radius:8px; }
        .mg-recipient-results button { display:block; width:100%; text-align:left; border:0; border-bottom:1px solid #f0f2f7; background:#fff; padding:10px 12px; }
        .mg-recipient-results button:hover { background:#f8f9fc; }
        .mg-recipient-chips { display:flex; flex-wrap:wrap; gap:8px; min-height:36px; }
        .mg-recipient-chip { display:inline-flex; align-items:center; gap:8px; background:#eef2ff; color:#4338ca; border-radius:999px; padding:6px 12px; font-size:12px; font-weight:600; }
        .mg-recipient-chip.no-phone { background:#fff7ed; color:#c2410c; }
        .mg-recipient-chip button { border:0; background:transparent; color:#dc2626; font-weight:700; line-height:1; }
        .mg-slot-row { display:flex; gap:8px; align-items:center; margin-bottom:8px; }
    </style>
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @if(in_array("announcement-index", $all_permission) || in_array("announcement_index", $all_permission))
                        <a href="{{route('announcement.index')}}" class="btn btn-info"><i class="dripicons-list"></i> {{trans('file.Announcement List')}} </a>
                    @endif
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>{{ !empty($clone) ? trans('file.Clone Announcement') : trans('file.Create Announcement') }}</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                            <form id="product-form" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ trans('file.Use a template') }}</label>
                                            <div class="d-flex align-items-center" style="gap:10px;">
                                                <select id="template-picker" class="form-control" style="max-width:420px;">
                                                    <option value="">{{ trans('file.Start from scratch') }}</option>
                                                    @foreach($templates as $tpl)
                                                        <option value="{{ $tpl->id }}">{{ $tpl->name }}</option>
                                                    @endforeach
                                                </select>
                                                <a href="{{ route('announcement.templates') }}" class="btn btn-outline-secondary btn-sm">{{ trans('file.Manage templates') }}</a>
                                            </div>
                                            <small class="text-muted">{{ trans('file.Template load help') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ trans('file.Send to') }}</label>
                                            <select class="form-control" name="audience_category" id="audience_category" required>
                                                @foreach($categories as $key => $label)
                                                    <option value="{{ $key }}" {{ $prefillCategory === $key ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{trans('file.Author Name')}} <strong>*</strong></label>
                                            <input type="text" name="name" required class="form-control" value="{{ old('name', optional($clone)->name) }}">
                                            <input type="hidden" name="is_active" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{trans('file.Subject')}} <strong>*</strong></label>
                                            <input type="text" name="subject" required class="form-control" value="{{ old('subject', optional($clone)->subject) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12" id="recipient-picker">
                                        <div class="card border mb-3">
                                            <div class="card-body">
                                                <label class="d-block mb-2"><strong>{{ trans('file.Select Recipients') }}</strong></label>
                                                <div class="mg-audience-tabs" id="category-tabs">
                                                    @foreach(['contestants','voters','users','judges','ambassadors'] as $tab)
                                                        <button type="button" data-category="{{ $tab }}" class="{{ $prefillCategory === $tab ? 'active' : '' }}">{{ $categories[$tab] ?? ucfirst($tab) }}</button>
                                                    @endforeach
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="input-group mb-2">
                                                            <input type="text" id="recipient-search" class="form-control" placeholder="{{ trans('file.Search name email or phone') }}">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-primary" id="recipient-search-btn">{{ trans('file.Search') }}</button>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-info mb-2" id="select-all-category">{{ trans('file.Add everyone in this category') }}</button>
                                                        <div class="mg-recipient-results" id="recipient-results"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <strong>{{ trans('file.Selected') }} (<span id="recipient-count">0</span>)</strong>
                                                            <button type="button" class="btn btn-link btn-sm text-danger p-0" id="clear-recipients">{{ trans('file.Clear') }}</button>
                                                        </div>
                                                        <div class="mg-recipient-chips" id="recipient-chips"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 csv-upload" style="display:none;">
                                        <div class="form-group">
                                            <label>{{trans('file.To')}} (CSV) <strong>*</strong></label>
                                            <input type="file" name="to_csv" class="form-control to-csv" accept=".csv">
                                        </div>
                                    </div>
                                    <div class="col-md-6 csv-upload" style="display:none;">
                                        <div class="form-group">
                                            <label>{{ trans('file.Sample file') }}</label><br>
                                            <a target="_blank" href="{{ asset('public/sample_file/announcement_csv_sample.csv') }}"><span class="fa fa-download"></span> Download CSV Sample</a>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Attachments</label>
                                            <div id="imageUpload" class="dropzone"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="bg-success"><b>{{trans('file.Header')}}</b></label>
                                            <textarea name="header" class="form-control" id="header">{{ old('header', optional($clone)->header) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="bg-warning"><b>{{trans('file.Body')}}</b></label>
                                            <textarea name="body" class="form-control" id="body">{{ old('body', optional($clone)->body) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="bg-danger"><b>{{trans('file.Footer')}}</b></label>
                                            <textarea name="footer" class="form-control" id="footer">{{ old('footer', optional($clone)->footer) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card border mb-3">
                                            <div class="card-body">
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="schedule_later" name="schedule_later" value="1">
                                                    <label class="custom-control-label" for="schedule_later">{{ trans('file.Schedule for later') }}</label>
                                                </div>
                                                <div id="schedule-times-wrap" style="display:none;">
                                                    <div id="schedule-times-list">
                                                        <div class="mg-slot-row schedule-row">
                                                            <input type="datetime-local" name="schedule_times[]" class="form-control">
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-link btn-sm px-0" id="add-schedule">+ {{ trans('file.Add another schedule') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border mb-3">
                                            <div class="card-body">
                                                <label class="d-block mb-2"><strong>{{ trans('file.Reminders') }}</strong></label>
                                                <p class="small text-muted">{{ trans('file.Reminder send help') }}</p>
                                                <div id="reminder-times-list">
                                                    <div class="mg-slot-row reminder-row">
                                                        <input type="datetime-local" name="reminder_times[]" class="form-control">
                                                        <button type="button" class="btn btn-outline-danger btn-sm remove-reminder" style="display:none;">×</button>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-link btn-sm px-0" id="add-reminder">+ {{ trans('file.Add reminder') }}</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="custom-control custom-checkbox mb-3">
                                            <input type="checkbox" class="custom-control-input" id="send_now" name="send_now" value="1" checked>
                                            <label class="custom-control-label" for="send_now">{{ trans('file.Send immediately after save') }}</label>
                                        </div>
                                        <div class="form-group mt-2">
                                            <input type="button" id="submit-btn" value="{{trans('file.submit')}}" class="btn btn-primary">
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
        var recipients = @json($cloneRecipients);
        var activeCategory = '{{ $prefillCategory }}';
        var recipientsUrl = @json(route('announcement.recipients'));

        function recipientKey(row) {
            return (row.type || 'user') + ':' + (row.id || '');
        }

        function renderChips() {
            var $chips = $('#recipient-chips');
            $chips.empty();
            recipients.forEach(function (row, index) {
                var phone = row.phone || '';
                var cls = phone ? 'mg-recipient-chip' : 'mg-recipient-chip no-phone';
                var label = row.name + (phone ? ' (' + phone + ')' : ' (no phone)');
                $chips.append(
                    '<span class="' + cls + '">' + label +
                    '<button type="button" data-index="' + index + '" class="chip-remove">&times;</button></span>'
                );
            });
            $('#recipient-count').text(recipients.length);
        }

        function addRecipient(row) {
            var key = recipientKey(row);
            if (recipients.some(function (r) { return recipientKey(r) === key; })) {
                return;
            }
            recipients.push(row);
            renderChips();
        }

        function toggleAudienceUi() {
            var cat = $('#audience_category').val();
            if (cat === 'csv') {
                $('#recipient-picker').hide();
                $('.csv-upload').show();
                $('.to-csv').prop('required', true);
            } else if (cat === 'everyone') {
                $('#recipient-picker').hide();
                $('.csv-upload').hide();
                $('.to-csv').prop('required', false);
            } else {
                $('#recipient-picker').show();
                $('.csv-upload').hide();
                $('.to-csv').prop('required', false);
                activeCategory = cat;
                $('#category-tabs button').removeClass('active');
                $('#category-tabs button[data-category="' + cat + '"]').addClass('active');
            }
        }

        function loadSearchResults() {
            var q = $('#recipient-search').val();
            $.get(recipientsUrl, { category: activeCategory, q: q }, function (res) {
                var $box = $('#recipient-results').empty();
                (res.items || []).forEach(function (item) {
                    var sub = [item.email, item.phone].filter(Boolean).join(' · ') || '—';
                    $box.append(
                        '<button type="button" class="pick-recipient" data-json=\'' + JSON.stringify(item).replace(/'/g, '&#39;') + '\'>' +
                        '<strong>' + item.name + '</strong><br><small class="text-muted">' + sub + '</small></button>'
                    );
                });
                if (!$box.children().length) {
                    $box.append('<div class="p-3 text-muted text-center">No results</div>');
                }
            });
        }

        $('#audience_category').on('change', toggleAudienceUi);
        $('#category-tabs').on('click', 'button', function () {
            activeCategory = $(this).data('category');
            $('#category-tabs button').removeClass('active');
            $(this).addClass('active');
            loadSearchResults();
        });
        $('#recipient-search-btn').on('click', loadSearchResults);
        $('#recipient-search').on('keypress', function (e) { if (e.which === 13) { e.preventDefault(); loadSearchResults(); } });
        $('#recipient-results').on('click', '.pick-recipient', function () {
            addRecipient(JSON.parse($(this).attr('data-json')));
        });
        $('#recipient-chips').on('click', '.chip-remove', function () {
            var idx = parseInt($(this).data('index'), 10);
            recipients.splice(idx, 1);
            renderChips();
        });
        $('#clear-recipients').on('click', function () { recipients = []; renderChips(); });
        $('#select-all-category').on('click', function () {
            $.get(recipientsUrl, { category: activeCategory }, function (res) {
                (res.items || []).forEach(addRecipient);
            });
        });

        $('#schedule_later').on('change', function () {
            $('#schedule-times-wrap').toggle(this.checked);
            if (this.checked) { $('#send_now').prop('checked', false); }
        });
        $('#send_now').on('change', function () {
            if (this.checked) { $('#schedule_later').prop('checked', false); $('#schedule-times-wrap').hide(); }
        });
        $('#add-schedule').on('click', function () {
            $('#schedule-times-list').append('<div class="mg-slot-row schedule-row"><input type="datetime-local" name="schedule_times[]" class="form-control"></div>');
        });
        $('#add-reminder').on('click', function () {
            $('#reminder-times-list').append(
                '<div class="mg-slot-row reminder-row"><input type="datetime-local" name="reminder_times[]" class="form-control">' +
                '<button type="button" class="btn btn-outline-danger btn-sm remove-reminder">×</button></div>'
            );
            $('.remove-reminder').show();
        });
        $('#reminder-times-list').on('click', '.remove-reminder', function () {
            $(this).closest('.reminder-row').remove();
        });

        $("#announcement-top-menu").addClass("active");

        var templateContentUrl = @json(url('announcement/template'));
        $('#template-picker').on('change', function () {
            var id = $(this).val();
            if (!id) { return; }
            $.get(templateContentUrl + '/' + id + '/content', function (res) {
                $('input[name="subject"]').val(res.subject || '');
                if (tinymce.get('header')) { tinymce.get('header').setContent(res.header || ''); }
                if (tinymce.get('body')) { tinymce.get('body').setContent(res.body || ''); }
                if (tinymce.get('footer')) { tinymce.get('footer').setContent(res.footer || ''); }
            });
        });

        tinymce.init({
            selector: '#header', height: 130,
            plugins: ['advlist autolink lists link charmap print preview anchor textcolor','searchreplace visualblocks code fullscreen','insertdatetime media table contextmenu paste code wordcount'],
            toolbar: 'insert | undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
            branding:false
        });
        tinymce.init({
            selector: '#body', height: 400, skin: 'oxide-dark', content_css: 'dark', paste_data_images: true, automatic_uploads: true,
            toolbar: 'pasteimage | insert | undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
            plugins: ['advlist autolink lists link charmap print preview anchor textcolor','searchreplace visualblocks code fullscreen','paste image','insertdatetime media table contextmenu paste code wordcount'],
            paste_image_handler: function (blobInfo, success, failure) {
                var formData = new FormData();
                formData.append('image', blobInfo.blob(), blobInfo.filename());
                fetch('/announcement/upload/image', { method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } })
                    .then(function(r){ return r.json(); }).then(function(data){ success(data.location); }).catch(function(){ failure('Image upload failed'); });
            }
        });
        tinymce.init({
            selector: '#footer', height: 130,
            plugins: ['advlist autolink lists link charmap print preview anchor textcolor','searchreplace visualblocks code fullscreen','insertdatetime media table contextmenu paste code wordcount'],
            toolbar: 'insert | undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
            branding:false
        });

        Dropzone.autoDiscover = false;
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        myDropzone = new Dropzone('div#imageUpload', {
            addRemoveLinks: true, autoProcessQueue: false, uploadMultiple: true, parallelUploads: 100,
            maxFilesize: 12, paramName: 'attachments', clickable: true, method: 'POST',
            url: '{{ route('announcement.store') }}',
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf",
            init: function () {
                var dz = this;
                $('#submit-btn').on('click', function (e) {
                    e.preventDefault();
                    var cat = $('#audience_category').val();
                    if (cat === 'csv' && !$('.to-csv').val()) {
                        alert('Please upload a CSV file.');
                        return;
                    }
                    tinymce.triggerSave();
                    var $btn = $('#submit-btn');
                    $btn.prop('disabled', true).val('Saving…');
                    if (dz.getAcceptedFiles().length) {
                        dz.processQueue();
                    } else {
                        submitAnnouncementForm(new FormData($('#product-form')[0]));
                    }
                });
                this.on('sending', function (file, xhr, formData) {
                    appendAnnouncementFields(formData);
                });
                this.on('successmultiple', function (files, response) {
                    var redirect = (response && response.redirect) ? response.redirect : '{{ route('announcement.index') }}';
                    if (response && response.message) { alert(response.message); }
                    location.href = redirect;
                });
                this.on('error', function (file, message) {
                    $('#submit-btn').prop('disabled', false).val('{{ trans('file.submit') }}');
                    var msg = (typeof message === 'string') ? message : (message && message.message ? message.message : 'Could not save announcement');
                    alert(msg);
                });
            }
        });

        function appendAnnouncementFields(formData) {
            var data = $('#product-form').serializeArray();
            $.each(data, function (_, el) { formData.append(el.name, el.value); });
            formData.append('recipients', JSON.stringify(recipients));
        }

        function submitAnnouncementForm(formData) {
            appendAnnouncementFields(formData);
            $.ajax({
                method: 'POST', url: '{{ route('announcement.store') }}',
                data: formData, processData: false, contentType: false,
                success: function (res) {
                    if (res && res.message) { alert(res.message); }
                    location.href = (res && res.redirect) ? res.redirect : '{{ route('announcement.index') }}';
                },
                error: function (xhr) {
                    $('#submit-btn').prop('disabled', false).val('{{ trans('file.submit') }}');
                    var msg = (xhr.responseJSON && xhr.responseJSON.message)
                        ? xhr.responseJSON.message
                        : ('Could not save announcement' + (xhr.status ? ' (HTTP ' + xhr.status + ')' : ''));
                    alert(msg);
                }
            });
        }

        toggleAudienceUi();
        renderChips();
        if ($('#recipient-picker').is(':visible')) { loadSearchResults(); }
    </script>
@endsection

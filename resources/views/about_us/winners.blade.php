@extends('layout.main')
@section('content')
@if($errors->any())
<div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ $errors->first() }}</div>
@endif
@if(session()->has('message'))
<div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert">&times;</button>{!! session()->get('message') !!}</div>
@endif

<section class="container-fluid">
    @include('about_us.partials.frontend-preview', ['previewSection' => 'winners', 'about' => $about, 'previewWinners' => $entries, 'previewYear' => $year])

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

            <div class="row" id="winners-cards">
                @foreach($entries as $i => $entry)
                    @php $links = method_exists($entry, 'linkList') ? $entry->linkList() : []; @endphp
                    <div class="col-lg-4 mb-4 winner-card" data-index="{{ $i }}">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <input type="text" name="w_label[{{ $i }}]" class="form-control form-control-sm mr-2" value="{{ $entry->placementLabel() }}" placeholder="{{ trans('file.Winner') }} / {{ trans('file.First Runner Up') }}">
                                <button type="button" class="btn btn-sm btn-danger w-remove-card" title="{{ trans('file.Remove') }}">&times;</button>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="w_sort[{{ $i }}]" value="{{ $entry->sort_order ?? $i }}">
                                <input type="hidden" name="w_image_existing[{{ $i }}]" value="{{ $entry->image }}">
                                <div class="w-paste-zone sc-paste-zone mb-3" tabindex="0">
                                    <div class="text-center mb-2 w-preview-wrap" @if(!$entry->image) style="display:none" @endif>
                                        <img class="w-preview" src="{{ $entry->image ? url('public/images/employee', $entry->image) : '' }}" style="width:110px;height:110px;object-fit:cover;border-radius:50%;">
                                    </div>
                                    <p class="mb-2"><small><strong>{{ trans('file.Paste') }}</strong> Ctrl/Cmd+V {{ trans('file.or choose a file') }}.</small></p>
                                    <input type="file" name="w_image[{{ $i }}]" class="form-control-file w-image-input" accept="image/*">
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('file.name') }}</label>
                                    <input type="text" name="w_name[{{ $i }}]" class="form-control" value="{{ $entry->name }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('file.Description') }}</label>
                                    <textarea name="w_bio[{{ $i }}]" class="form-control" rows="3">{{ $entry->bio }}</textarea>
                                </div>
                                <label>{{ trans('file.Links') }} <small class="text-muted">({{ trans('file.first link is used on the image') }})</small></label>
                                <div class="w-links">
                                    @foreach($links as $link)
                                        <div class="input-group mb-2 w-link-row">
                                            <input type="url" name="w_link_url[{{ $i }}][]" class="form-control" placeholder="https://..." value="{{ $link['url'] }}">
                                            <input type="text" name="w_link_desc[{{ $i }}][]" class="form-control" placeholder="{{ trans('file.Description') }}" value="{{ $link['label'] }}">
                                            <div class="input-group-append"><button type="button" class="btn btn-danger w-link-remove">&times;</button></div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-info w-link-add">+ {{ trans('file.Add link') }}</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-secondary mb-3" id="add-winner-card"><i class="dripicons-plus"></i> {{ trans('file.Add another runner') }}</button>
            <br>
            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div>
</section>

<style>
    .sc-paste-zone { border:2px dashed #c5d3ea; border-radius:10px; padding:14px; background:#f8fafc; cursor:text; }
    .sc-paste-zone:focus { outline:none; border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,.15); }
</style>

<script type="text/javascript">
(function () {
    var nextIndex = {{ $entries->count() }};

    function linkRow(index) {
        return '<div class="input-group mb-2 w-link-row">' +
            '<input type="url" name="w_link_url[' + index + '][]" class="form-control" placeholder="https://...">' +
            '<input type="text" name="w_link_desc[' + index + '][]" class="form-control" placeholder="{{ trans('file.Description') }}">' +
            '<div class="input-group-append"><button type="button" class="btn btn-danger w-link-remove">&times;</button></div>' +
            '</div>';
    }

    function winnerCard(index) {
        return '<div class="col-lg-4 mb-4 winner-card" data-index="' + index + '">' +
            '<div class="card h-100">' +
                '<div class="card-header d-flex justify-content-between align-items-center">' +
                    '<input type="text" name="w_label[' + index + ']" class="form-control form-control-sm mr-2" placeholder="{{ trans('file.Runner Up') }}">' +
                    '<button type="button" class="btn btn-sm btn-danger w-remove-card">&times;</button>' +
                '</div>' +
                '<div class="card-body">' +
                    '<input type="hidden" name="w_sort[' + index + ']" value="' + index + '">' +
                    '<input type="hidden" name="w_image_existing[' + index + ']" value="">' +
                    '<div class="w-paste-zone sc-paste-zone mb-3" tabindex="0">' +
                        '<div class="text-center mb-2 w-preview-wrap" style="display:none"><img class="w-preview" src="" style="width:110px;height:110px;object-fit:cover;border-radius:50%;"></div>' +
                        '<p class="mb-2"><small><strong>{{ trans('file.Paste') }}</strong> Ctrl/Cmd+V {{ trans('file.or choose a file') }}.</small></p>' +
                        '<input type="file" name="w_image[' + index + ']" class="form-control-file w-image-input" accept="image/*">' +
                    '</div>' +
                    '<div class="form-group"><label>{{ trans('file.name') }}</label><input type="text" name="w_name[' + index + ']" class="form-control"></div>' +
                    '<div class="form-group"><label>{{ trans('file.Description') }}</label><textarea name="w_bio[' + index + ']" class="form-control" rows="3"></textarea></div>' +
                    '<label>{{ trans('file.Links') }} <small class="text-muted">({{ trans('file.first link is used on the image') }})</small></label>' +
                    '<div class="w-links"></div>' +
                    '<button type="button" class="btn btn-sm btn-info w-link-add">+ {{ trans('file.Add link') }}</button>' +
                '</div>' +
            '</div>' +
        '</div>';
    }

    $('#add-winner-card').on('click', function () {
        $('#winners-cards').append(winnerCard(nextIndex));
        nextIndex++;
    });

    $(document).on('click', '.w-remove-card', function () {
        $(this).closest('.winner-card').remove();
    });

    $(document).on('click', '.w-link-add', function () {
        var card = $(this).closest('.winner-card');
        var index = card.attr('data-index');
        card.find('.w-links').append(linkRow(index));
    });

    $(document).on('click', '.w-link-remove', function () {
        $(this).closest('.w-link-row').remove();
    });

    // Image preview + paste per card
    $(document).on('change', '.w-image-input', function () {
        var input = this;
        if (!input.files || !input.files[0]) return;
        var wrap = $(input).closest('.w-paste-zone').find('.w-preview-wrap');
        var img = wrap.find('.w-preview')[0];
        var reader = new FileReader();
        reader.onload = function (e) { img.src = e.target.result; wrap.show(); };
        reader.readAsDataURL(input.files[0]);
    });

    document.addEventListener('paste', function (e) {
        var zone = document.activeElement;
        while (zone && !(zone.classList && zone.classList.contains('w-paste-zone'))) {
            zone = zone.parentElement;
        }
        if (!zone || !e.clipboardData || !e.clipboardData.items) return;
        var input = zone.querySelector('.w-image-input');
        if (!input) return;
        for (var i = 0; i < e.clipboardData.items.length; i++) {
            if (e.clipboardData.items[i].type.indexOf('image') === -1) continue;
            var file = e.clipboardData.items[i].getAsFile();
            if (!file) continue;
            e.preventDefault();
            var dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            $(input).trigger('change');
            break;
        }
    });
})();
</script>
@endsection

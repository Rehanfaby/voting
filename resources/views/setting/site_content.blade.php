@extends('layout.main') @section('content')

@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section class="forms">
    <div class="container-fluid">
        {!! Form::open(['route' => 'setting.site_content.store', 'method' => 'post', 'files' => true]) !!}

        {{-- Section visibility --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="dripicons-view-list"></i> Homepage Sections</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>Turn homepage sections on or off. Disabled sections are hidden from visitors.</small></p>
                        <div class="row">
                            @foreach($section_labels as $key => $label)
                                <div class="col-md-6 col-lg-4">
                                    <div class="sc-toggle">
                                        <label class="sc-switch">
                                            <input type="checkbox" name="sections[{{ $key }}]" value="1" {{ !empty($content['sections'][$key]) ? 'checked' : '' }}>
                                            <span class="sc-slider"></span>
                                        </label>
                                        <span class="sc-toggle-label">{{ $label }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Homepage popup image --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="dripicons-photo"></i> Homepage Popup</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>Upload a flyer / announcement image, then enable the <strong>Homepage Popup</strong> toggle above to show it. It appears once when a visitor opens the site.</small></p>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Popup image (JPG / PNG / GIF, up to 8&nbsp;MB)</label>
                                    <input type="file" name="popup_image" accept="image/*" class="form-control-file">
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <label class="d-block">Current popup</label>
                                <img src="{{ \App\Helpers\SiteContent::popupImageUrl() }}?v={{ time() }}" alt="Current popup" style="max-height:160px; max-width:100%; border:1px solid #e5e7eb; border-radius:8px; padding:4px; background:#fff;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Provincial casting calendar --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="dripicons-calendar"></i> Provincial Casting Calendar</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="casting_title" class="form-control" value="{{ $content['casting_title'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Subtitle</label>
                                    <input type="text" name="casting_subtitle" class="form-control" value="{{ $content['casting_subtitle'] ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="sc-toggle mb-3">
                            <label class="sc-switch">
                                <input type="checkbox" name="casting_countdown" value="1" {{ !empty($content['casting_countdown']) ? 'checked' : '' }}>
                                <span class="sc-slider"></span>
                            </label>
                            <span class="sc-toggle-label">Enable countdown for Provincial Castings</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table" id="casting-table">
                                <thead>
                                    <tr><th>Province</th><th>City / Venue</th><th>Date</th><th style="width:60px"></th></tr>
                                </thead>
                                <tbody>
                                    @foreach(($content['casting_rows'] ?? []) as $row)
                                        <tr>
                                            <td><input type="text" name="province[]" class="form-control" value="{{ $row['province'] ?? '' }}"></td>
                                            <td><input type="text" name="venue[]" class="form-control" value="{{ $row['venue'] ?? '' }}"></td>
                                            <td><input type="text" name="cast_date[]" class="form-control" value="{{ $row['date'] ?? '' }}" placeholder="e.g. April 4–5, 2026"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm sc-remove-row">&times;</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-info btn-sm" id="add-casting-row"><i class="dripicons-plus"></i> Add Province</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Prime / finals schedule with countdown --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="dripicons-clock"></i> Prime / Finals Schedule &amp; Countdown</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>Pick a date &amp; time for each prime. The homepage shows a live countdown to the next upcoming prime.</small></p>
                        <div class="form-group col-md-6 px-0">
                            <label>Schedule Title</label>
                            <input type="text" name="primes_title" class="form-control" value="{{ $content['primes_title'] ?? 'Finals Schedule' }}">
                        </div>
                        <div class="sc-toggle mb-3">
                            <label class="sc-switch">
                                <input type="checkbox" name="primes_countdown" value="1" {{ !empty($content['primes_countdown']) ? 'checked' : '' }}>
                                <span class="sc-slider"></span>
                            </label>
                            <span class="sc-toggle-label">Enable countdown for Primes</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="primes-table">
                                <thead>
                                    <tr><th>Prime Label</th><th>Date &amp; Time</th><th style="width:60px"></th></tr>
                                </thead>
                                <tbody>
                                    @foreach(($content['primes'] ?? []) as $p)
                                        <tr>
                                            <td><input type="text" name="prime_label[]" class="form-control" value="{{ $p['label'] ?? '' }}"></td>
                                            <td><input type="datetime-local" name="prime_date[]" class="form-control" value="{{ !empty($p['date']) ? \Carbon\Carbon::parse($p['date'])->format('Y-m-d\TH:i') : '' }}"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm sc-remove-row">&times;</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-info btn-sm" id="add-prime-row"><i class="dripicons-plus"></i> Add Prime</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Side menu order --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="dripicons-menu"></i> Side Menu</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>Reorder the admin side menu. Use the arrows to move an item up or down (e.g. move <strong>Contestants</strong> to the top). Save to apply.</small></p>
                        <ul class="sc-menu-order list-unstyled" id="sc-menu-order">
                            @foreach($menu_order as $key)
                                @if(isset($menu_labels[$key]))
                                <li class="sc-menu-item" data-key="{{ $key }}">
                                    <input type="hidden" name="menu_order[]" value="{{ $key }}">
                                    <span class="sc-menu-label">{{ $menu_labels[$key] }}</span>
                                    <span class="sc-menu-actions">
                                        <button type="button" class="btn btn-sm btn-light sc-move-up" title="Move up"><i class="dripicons-chevron-up"></i></button>
                                        <button type="button" class="btn btn-sm btn-light sc-move-down" title="Move down"><i class="dripicons-chevron-down"></i></button>
                                    </span>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
        </div>
        {!! Form::close() !!}
    </div>
</section>

<style>
    .sc-toggle { display:flex; align-items:center; gap:12px; padding:12px 0; }
    .sc-toggle-label { font-weight:600; color:#14223f; }
    .sc-switch { position:relative; display:inline-block; width:50px; height:27px; margin:0; flex:0 0 50px; }
    .sc-switch input { opacity:0; width:0; height:0; }
    .sc-slider { position:absolute; cursor:pointer; inset:0; background:#cdd6e4; border-radius:30px; transition:.25s; }
    .sc-slider:before { content:""; position:absolute; height:21px; width:21px; left:3px; top:3px; background:#fff; border-radius:50%; transition:.25s; box-shadow:0 2px 5px rgba(0,0,0,.2); }
    .sc-switch input:checked + .sc-slider { background:linear-gradient(135deg,#1d4ed8,#2563eb); }
    .sc-switch input:checked + .sc-slider:before { transform:translateX(23px); }
    .sc-menu-order { max-width:520px; margin:0; }
    .sc-menu-item { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:10px 14px; margin-bottom:8px; background:#f3f6fc; border:1px solid #dbe4f3; border-radius:8px; }
    .sc-menu-label { font-weight:600; color:#14223f; }
    .sc-menu-actions .btn { padding:2px 8px; }
</style>

<script type="text/javascript">
    $("ul#setting").siblings('a').attr('aria-expanded','true');
    $("ul#setting").addClass("show");
    $("ul#setting #site-content-menu").addClass("active");

    (function () {
        function castingRow() {
            return '<tr>' +
                '<td><input type="text" name="province[]" class="form-control"></td>' +
                '<td><input type="text" name="venue[]" class="form-control"></td>' +
                '<td><input type="text" name="cast_date[]" class="form-control" placeholder="e.g. April 4\u20135, 2026"></td>' +
                '<td><button type="button" class="btn btn-danger btn-sm sc-remove-row">&times;</button></td>' +
                '</tr>';
        }
        function primeRow() {
            return '<tr>' +
                '<td><input type="text" name="prime_label[]" class="form-control"></td>' +
                '<td><input type="datetime-local" name="prime_date[]" class="form-control"></td>' +
                '<td><button type="button" class="btn btn-danger btn-sm sc-remove-row">&times;</button></td>' +
                '</tr>';
        }
        $('#add-casting-row').on('click', function () { $('#casting-table tbody').append(castingRow()); });
        $('#add-prime-row').on('click', function () { $('#primes-table tbody').append(primeRow()); });
        $(document).on('click', '.sc-remove-row', function () { $(this).closest('tr').remove(); });

        // Side-menu reorder (up/down). The hidden menu_order[] inputs submit in DOM order.
        $(document).on('click', '.sc-move-up', function () {
            var item = $(this).closest('.sc-menu-item');
            var prev = item.prev('.sc-menu-item');
            if (prev.length) { item.insertBefore(prev); }
        });
        $(document).on('click', '.sc-move-down', function () {
            var item = $(this).closest('.sc-menu-item');
            var next = item.next('.sc-menu-item');
            if (next.length) { item.insertAfter(next); }
        });
    })();
</script>
@endsection

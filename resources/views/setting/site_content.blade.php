@extends('layout.main') @section('content')

@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section class="forms">
    <div class="container-fluid">

        {{-- Homepage section toggles (popup managed separately) --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="dripicons-view-list"></i> Homepage Sections</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'setting.site_content.section', 'method' => 'post']) !!}
                        <input type="hidden" name="section" value="homepage_sections">
                        <p class="italic"><small>Turn homepage sections on or off, then press <strong>Save</strong>. Disabled sections are hidden from visitors.</small></p>
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
                        <div class="sc-section-actions">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- Homepage popup --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="dripicons-photo"></i> Homepage Popup</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'setting.site_content.section', 'method' => 'post', 'files' => true]) !!}
                        <input type="hidden" name="section" value="popup">
                        <p class="italic"><small>Upload a flyer / announcement image and enable the popup below. It appears once when a visitor opens the site.</small></p>
                        <div class="sc-toggle mb-3">
                            <label class="sc-switch">
                                <input type="checkbox" name="sections[popup]" value="1" {{ !empty($content['sections']['popup']) ? 'checked' : '' }}>
                                <span class="sc-slider"></span>
                            </label>
                            <span class="sc-toggle-label">Enable Homepage Popup</span>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Popup image (JPG / PNG / GIF, up to 8&nbsp;MB)</label>
                                    <input type="file" name="popup_image" accept="image/*" class="form-control-file">
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <label class="d-block">Current popup</label>
                                <img src="{{ \App\Helpers\SiteContent::popupImageUrl() }}?v={{ config('app.version') }}" alt="Current popup" style="max-height:160px; max-width:100%; border:1px solid #e5e7eb; border-radius:8px; padding:4px; background:#fff;">
                                @if(!\App\Helpers\SiteContent::hasCustomPopup())
                                    <small class="text-muted d-block mt-1">Showing default flyer (upload your own to replace)</small>
                                @endif
                            </div>
                        </div>
                        <div class="sc-section-actions">
                            <button type="submit" class="btn btn-primary">Save</button>
                            @if(\App\Helpers\SiteContent::hasCustomPopup())
                                <button type="submit" name="delete_popup_image" value="1" class="btn btn-danger" onclick="return confirm('Remove the uploaded popup image?')">Delete image</button>
                            @endif
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- Most voted + hero banners --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="dripicons-star"></i> Most Voted of the Week</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'setting.site_content.section', 'method' => 'post']) !!}
                        <input type="hidden" name="section" value="most_voted_hero">
                        <p class="italic"><small>How many top-voted contestants to show (1–20).</small></p>
                        <div class="form-group">
                            <label>Number to display</label>
                            <input type="number" name="most_voted_count" class="form-control" min="1" max="20" value="{{ $content['most_voted_count'] ?? 1 }}">
                        </div>
                        <div class="sc-section-actions">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="dripicons-photo-group"></i> Hero Banner (Vote Section)</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'setting.site_content.section', 'method' => 'post', 'files' => true]) !!}
                        <input type="hidden" name="section" value="most_voted_hero">
                        <p class="italic"><small>Upload new hero images — they are <strong>automatically compressed</strong>. Leave blank to keep current banners.</small></p>
                        <div class="form-group">
                            <label>English hero (JPG/PNG, up to 8&nbsp;MB)</label>
                            <input type="file" name="hero_image_en" accept="image/jpeg,image/png" class="form-control-file">
                        </div>
                        <div class="form-group">
                            <label>French hero (JPG/PNG, up to 8&nbsp;MB)</label>
                            <input type="file" name="hero_image_fr" accept="image/jpeg,image/png" class="form-control-file">
                        </div>
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <small class="d-block text-muted">Current EN</small>
                                <img src="{{ \App\Helpers\SiteContent::heroImageUrl('en') }}?v={{ config('app.version') }}" alt="Hero EN" style="max-height:80px; max-width:100%; border-radius:6px;">
                            </div>
                            <div class="col-6">
                                <small class="d-block text-muted">Current FR</small>
                                <img src="{{ \App\Helpers\SiteContent::heroImageUrl('fr') }}?v={{ config('app.version') }}" alt="Hero FR" style="max-height:80px; max-width:100%; border-radius:6px;">
                            </div>
                        </div>
                        <div class="sc-section-actions">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                        {!! Form::close() !!}
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
                        {!! Form::open(['route' => 'setting.site_content.section', 'method' => 'post']) !!}
                        <input type="hidden" name="section" value="casting">
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
                        <button type="button" class="btn btn-info btn-sm mb-3" id="add-casting-row"><i class="dripicons-plus"></i> Add Province</button>
                        <div class="sc-section-actions">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- Prime / finals schedule --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="dripicons-clock"></i> Prime / Finals Schedule &amp; Countdown</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'setting.site_content.section', 'method' => 'post', 'files' => true]) !!}
                        <input type="hidden" name="section" value="primes">
                        <p class="italic"><small>Pick a date &amp; time and optional promo image for each prime.</small></p>
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
                                    <tr><th>Prime Label</th><th>Date &amp; Time</th><th>Promo Image</th><th style="width:60px"></th></tr>
                                </thead>
                                <tbody>
                                    @foreach(($content['primes'] ?? []) as $i => $p)
                                        <tr>
                                            <td><input type="text" name="prime_label[]" class="form-control" value="{{ $p['label'] ?? '' }}"></td>
                                            <td><input type="datetime-local" name="prime_date[]" class="form-control" value="{{ !empty($p['date']) ? \Carbon\Carbon::parse($p['date'])->format('Y-m-d\TH:i') : '' }}"></td>
                                            <td>
                                                <input type="hidden" name="prime_image_existing[]" value="{{ $p['image'] ?? '' }}">
                                                <input type="file" name="prime_image[]" accept="image/*" class="form-control-file">
                                                @if(!empty($p['image']))
                                                    <img src="{{ \App\Helpers\SiteContent::primeImageUrl($p['image']) }}?v={{ config('app.version') }}" alt="" style="max-height:48px;margin-top:6px;border-radius:6px;">
                                                @endif
                                            </td>
                                            <td><button type="button" class="btn btn-danger btn-sm sc-remove-row">&times;</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-info btn-sm mb-3" id="add-prime-row"><i class="dripicons-plus"></i> Add Prime</button>
                        <div class="sc-section-actions">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- About Us page --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="fa fa-info-circle"></i> About Us Page</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'setting.site_content.section', 'method' => 'post', 'files' => true]) !!}
                        <input type="hidden" name="section" value="about_page">
                        <p class="italic"><small>Edit all text and images shown on the public <a href="{{ route('about') }}" target="_blank">About Us</a> page. Manage leader photos and winner cards from <a href="{{ route('about_us.index') }}">About Us → Our Leaders / Winners</a>.</small></p>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>{{ trans('file.Mission image') }}</label>
                                    @php $aboutImg = $about['image'] ?? null; @endphp
                                    @if($aboutImg && file_exists(public_path($aboutImg)))
                                        <div class="mb-2"><img src="{{ url($aboutImg) }}?v={{ config('app.version') }}" alt="" style="max-width:100%;border-radius:12px;max-height:220px;object-fit:cover;"></div>
                                    @endif
                                    <input type="file" name="about_image" class="form-control" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group"><label>{{ trans('file.Hero subtitle') }}</label><textarea name="hero_subtitle" class="form-control" rows="2">{{ $about['hero_subtitle'] ?? '' }}</textarea></div>
                                <div class="form-group"><label>{{ trans('file.Mission heading') }}</label><input type="text" name="mission_title" class="form-control" value="{{ $about['mission_title'] ?? '' }}"></div>
                                <div class="form-group"><label>{{ trans('file.Heart badge text') }}</label><input type="text" name="heart_badge" class="form-control" value="{{ $about['heart_badge'] ?? '' }}"></div>
                            </div>
                        </div>
                        <div class="form-group"><label>{{ trans('file.Mission paragraph') }} 1</label><textarea name="mission_p1" class="form-control" rows="2">{{ $about['mission_p1'] ?? '' }}</textarea></div>
                        <div class="form-group"><label>{{ trans('file.Mission paragraph') }} 2</label><textarea name="mission_p2" class="form-control" rows="2">{{ $about['mission_p2'] ?? '' }}</textarea></div>
                        <div class="form-group"><label>{{ trans('file.Mission paragraph') }} 3</label><textarea name="mission_p3" class="form-control" rows="2">{{ $about['mission_p3'] ?? '' }}</textarea></div>
                        <hr>
                        <div class="form-group"><label>{{ trans('file.Our Values') }} — {{ trans('file.Section heading') }}</label><input type="text" name="values_heading" class="form-control" value="{{ $about['values_heading'] ?? '' }}"></div>
                        <div class="form-group"><label>{{ trans('file.Values comma separated') }}</label><input type="text" name="values" class="form-control" value="{{ $about['values'] ?? '' }}"></div>
                        <hr>
                        <div class="form-group"><label>{{ trans('file.Intro heading') }}</label><input type="text" name="intro_title" class="form-control" value="{{ $about['intro_title'] ?? '' }}"></div>
                        <div class="form-group"><label>{{ trans('file.Intro text') }}</label><textarea name="intro_text" class="form-control" rows="3">{{ $about['intro_text'] ?? '' }}</textarea></div>
                        <div class="form-group"><label>{{ trans('file.Regions comma separated') }}</label><input type="text" name="regions" class="form-control" value="{{ $about['regions'] ?? '' }}"></div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"><label>{{ trans('file.Our Leaders') }} — {{ trans('file.Section heading') }}</label><input type="text" name="leaders_heading" class="form-control" value="{{ $about['leaders_heading'] ?? '' }}"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label>{{ trans('file.Section subheading') }}</label><input type="text" name="leaders_subheading" class="form-control" value="{{ $about['leaders_subheading'] ?? '' }}"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group"><label>{{ trans('file.Year') }}</label><input type="text" name="winners_year" class="form-control" value="{{ $about['winners_year'] ?? '2025' }}"></div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group"><label>{{ trans('file.Winners') }} — {{ trans('file.Section heading') }}</label><input type="text" name="winners_heading" class="form-control" value="{{ $about['winners_heading'] ?? '' }}" placeholder="2025 Winners"></div>
                            </div>
                        </div>
                        <div class="sc-section-actions">
                            <button type="submit" class="btn btn-primary">Save</button>
                            @if(!empty($aboutImg) && file_exists(public_path($aboutImg)))
                                <button type="submit" name="delete_about_image" value="1" class="btn btn-danger" onclick="return confirm('Remove the mission image?')">Delete mission image</button>
                            @endif
                        </div>
                        {!! Form::close() !!}
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
                        {!! Form::open(['route' => 'setting.site_content.section', 'method' => 'post']) !!}
                        <input type="hidden" name="section" value="menu_order">
                        <p class="italic"><small>Reorder the admin side menu with the arrows, then press <strong>Save</strong>.</small></p>
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
                        <div class="sc-section-actions">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

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
    .sc-section-actions { margin-top:16px; padding-top:12px; border-top:1px solid #e8edf5; display:flex; gap:10px; flex-wrap:wrap; }
</style>

<script type="text/javascript">
    $("#site-content-top-menu").addClass("active");

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
                '<td><input type="hidden" name="prime_image_existing[]" value="">' +
                '<input type="file" name="prime_image[]" accept="image/*" class="form-control-file"></td>' +
                '<td><button type="button" class="btn btn-danger btn-sm sc-remove-row">&times;</button></td>' +
                '</tr>';
        }
        $('#add-casting-row').on('click', function () { $('#casting-table tbody').append(castingRow()); });
        $('#add-prime-row').on('click', function () { $('#primes-table tbody').append(primeRow()); });
        $(document).on('click', '.sc-remove-row', function () { $(this).closest('tr').remove(); });

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

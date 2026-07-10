@extends('layout.main') @section('content')

@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section class="forms">
    <div class="container-fluid">

        <ul class="nav nav-tabs sc-tabs" id="sc-tab-nav" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#sc-homepage_sections" role="tab"><i class="dripicons-view-list"></i> Sections</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sc-popup" role="tab"><i class="dripicons-photo"></i> Popup</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sc-most_voted_hero" role="tab"><i class="dripicons-star"></i> Most Voted &amp; Hero</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sc-casting" role="tab"><i class="dripicons-calendar"></i> Casting</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sc-primes" role="tab"><i class="dripicons-clock"></i> Primes</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sc-gallery" role="tab"><i class="dripicons-photo-group"></i> Gallery</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sc-about" role="tab"><i class="fa fa-info-circle"></i> {{ trans('file.About Us') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sc-judges" role="tab"><i class="fa fa-gavel"></i> {{ trans('file.Judges') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sc-ambassadors" role="tab"><i class="fa fa-podcast"></i> {{ trans('file.Ambassadors') }}</a></li>
            @if(in_array('employees-index', $all_permission ?? []))
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sc-partners" role="tab"><i class="fa fa-images"></i> {{ trans('file.Logos') }}</a></li>
            @endif
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sc-frontend_menu" role="tab"><i class="dripicons-web"></i> {{ trans('file.Landing Menu') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sc-menu_order" role="tab"><i class="dripicons-menu"></i> Side Menu</a></li>
        </ul>

        <div class="tab-content sc-tab-content">

        {{-- Homepage section toggles (popup managed separately) --}}
        <div class="tab-pane fade show active" id="sc-homepage_sections" role="tabpanel">
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
        </div>

        {{-- Homepage popup --}}
        <div class="tab-pane fade" id="sc-popup" role="tabpanel">
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
                        <div class="row align-items-start">
                            <div class="col-md-6">
                                <div id="popup-paste-zone" class="sc-paste-zone" tabindex="0">
                                    <p class="mb-2"><strong>Paste or upload</strong> — click here and press <kbd>Ctrl+V</kbd> / <kbd>Cmd+V</kbd>, or choose a file below.</p>
                                    <input type="file" name="popup_image" id="popup_image_input" accept="image/*" class="form-control-file">
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <label class="d-block">Preview</label>
                                <img id="popup-preview-img" src="{{ \App\Helpers\SiteContent::popupImageUrl() }}?v={{ config('app.version') }}" alt="Popup preview" style="max-height:180px; max-width:100%; border:1px solid #e5e7eb; border-radius:8px; padding:4px; background:#fff;">
                                @if(!\App\Helpers\SiteContent::hasCustomPopup())
                                    <small class="text-muted d-block mt-1">Default flyer shown until you upload your own.</small>
                                @endif
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <label><i class="dripicons-link"></i> Popup link (optional)</label>
                            <input type="url" name="popup_link" class="form-control" value="{{ $content['popup_link'] ?? '' }}" placeholder="https://example.com — clicking the popup image opens this link">
                            <small class="text-muted">Leave empty if the popup image should not be clickable.</small>
                        </div>

                        <div class="sc-toggle mb-2">
                            <label class="sc-switch">
                                <input type="checkbox" name="popup_countdown" value="1" {{ !empty($content['popup_countdown']) ? 'checked' : '' }}>
                                <span class="sc-slider"></span>
                            </label>
                            <span class="sc-toggle-label">Show a countdown on the popup</span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Countdown date &amp; time</label>
                                    <input type="datetime-local" name="popup_countdown_at" class="form-control" value="{{ !empty($content['popup_countdown_at']) ? \App\Helpers\SiteContent::parseEventDate($content['popup_countdown_at'])->format('Y-m-d\TH:i') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Countdown label (optional)</label>
                                    <input type="text" name="popup_countdown_label" class="form-control" value="{{ $content['popup_countdown_label'] ?? '' }}" placeholder="e.g. Event starts in">
                                </div>
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
        </div>

        {{-- Most voted + hero banners --}}
        <div class="tab-pane fade" id="sc-most_voted_hero" role="tabpanel">
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
        </div>

        {{-- Provincial casting calendar --}}
        <div class="tab-pane fade" id="sc-casting" role="tabpanel">
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
                                    <tr><th>Province</th><th>City / Venue</th><th>Date</th><th>Enabled</th><th style="width:60px"></th></tr>
                                </thead>
                                <tbody>
                                    @foreach(($content['casting_rows'] ?? []) as $i => $row)
                                        <tr>
                                            <td><input type="text" name="province[]" class="form-control" value="{{ $row['province'] ?? '' }}"></td>
                                            <td><input type="text" name="venue[]" class="form-control" value="{{ $row['venue'] ?? '' }}"></td>
                                            <td><input type="text" name="cast_date[]" class="form-control" value="{{ $row['date'] ?? '' }}" placeholder="e.g. April 4–5, 2026"></td>
                                            <td class="align-middle">
                                                <label class="sc-switch mb-0">
                                                    <input type="checkbox" name="cast_enabled[{{ $i }}]" value="1" {{ ($row['enabled'] ?? true) ? 'checked' : '' }}>
                                                    <span class="sc-slider"></span>
                                                </label>
                                            </td>
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
        </div>

        {{-- Prime / finals schedule --}}
        <div class="tab-pane fade" id="sc-primes" role="tabpanel">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="dripicons-clock"></i> Prime / Finals Schedule &amp; Countdown</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'setting.site_content.section', 'method' => 'post', 'files' => true]) !!}
                        <input type="hidden" name="section" value="primes">
                        <p class="italic"><small>Events are sorted automatically by date (earliest first) when you save.</small></p>
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
                                    <tr><th>Prime Label</th><th>Date &amp; Time</th><th>Promo Image</th><th>Enabled</th><th style="width:60px"></th></tr>
                                </thead>
                                <tbody>
                                    @foreach(($content['primes'] ?? []) as $i => $p)
                                        <tr>
                                            <td><input type="text" name="prime_label[]" class="form-control" value="{{ $p['label'] ?? '' }}"></td>
                                            <td><input type="datetime-local" name="prime_date[]" class="form-control" value="{{ !empty($p['date']) ? \App\Helpers\SiteContent::parseEventDate($p['date'])->format('Y-m-d\TH:i') : '' }}"></td>
                                            <td>
                                                <input type="hidden" name="prime_image_existing[]" value="{{ $p['image'] ?? '' }}">
                                                <input type="file" name="prime_image[{{ $i }}]" accept="image/*" class="form-control-file">
                                                @if(!empty($p['image']))
                                                    <img src="{{ \App\Helpers\SiteContent::primeImageUrl($p['image']) }}?v={{ config('app.version') }}" alt="" style="max-height:48px;margin-top:6px;border-radius:6px;">
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <label class="sc-switch mb-0">
                                                    <input type="checkbox" name="prime_enabled[{{ $i }}]" value="1" {{ ($p['enabled'] ?? true) ? 'checked' : '' }}>
                                                    <span class="sc-slider"></span>
                                                </label>
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
        </div>

        {{-- Site gallery --}}
        <div class="tab-pane fade" id="sc-gallery" role="tabpanel">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="dripicons-photo-group"></i> Gallery</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'setting.site_content.section', 'method' => 'post', 'files' => true]) !!}
                        <input type="hidden" name="section" value="gallery">
                        <p class="italic"><small>Add photos to the public Gallery page. Paste (Ctrl/Cmd+V) or choose files below, then press <strong>Save</strong>.</small></p>

                        <div id="gallery-paste-zone" class="sc-paste-zone mb-3" tabindex="0">
                            <p class="mb-2"><strong>Paste or upload</strong> — click here and press <kbd>Ctrl+V</kbd> / <kbd>Cmd+V</kbd>, or choose files below.</p>
                            <input type="file" name="gallery_images[]" id="gallery_images_input" accept="image/*" multiple class="form-control-file">
                        </div>

                        {{-- Thumbnails of newly pasted / chosen images (not yet saved) --}}
                        <div class="row" id="gallery-new-preview" style="display:none;">
                            <div class="col-12"><p class="mb-2"><small class="text-info"><i class="dripicons-photo"></i> {{ trans('file.New photos to be saved') }}</small></p></div>
                            <div class="col-12"><div class="row" id="gallery-new-preview-items"></div></div>
                        </div>

                        <div class="row" id="gallery-existing">
                            @foreach(($content['gallery'] ?? []) as $i => $g)
                                @php $gimg = is_array($g) ? ($g['image'] ?? '') : $g; @endphp
                                @if($gimg)
                                <div class="col-md-3 col-6 mb-3 sc-gallery-card" data-image="{{ $gimg }}">
                                    <div class="card h-100">
                                        <div class="sc-gallery-thumb">
                                            <img src="{{ \App\Helpers\SiteContent::publicUploadUrl($gimg) }}?v={{ config('app.version') }}" style="height:120px;width:100%;object-fit:cover;border-radius:6px 6px 0 0;">
                                            <button type="button" class="sc-gallery-del" title="{{ trans('file.delete') }}" aria-label="{{ trans('file.delete') }}"><i class="dripicons-trash"></i></button>
                                        </div>
                                        <div class="card-body p-2">
                                            <input type="hidden" name="gallery_existing[{{ $i }}]" value="{{ $gimg }}">
                                            <input type="text" name="gallery_caption[{{ $i }}]" class="form-control form-control-sm" placeholder="Caption (optional)" value="{{ is_array($g) ? ($g['caption'] ?? '') : '' }}">
                                        </div>
                                    </div>
                                </div>
                                @endif
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
        </div>

        {{-- About Us hub --}}
        <div class="tab-pane fade" id="sc-about" role="tabpanel">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="fa fa-info-circle"></i> {{ trans('file.About Us') }}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>Manage the public About Us page. Choose a section below to edit it.</small></p>
                        <div class="row">
                            <div class="col-md-6 col-lg-3 mb-3">
                                <a href="{{ route('about_us.settings') }}" class="sc-hub-card">
                                    <i class="dripicons-document-edit"></i>
                                    <span>{{ trans('file.About Page Content') }}</span>
                                </a>
                            </div>
                            <div class="col-md-6 col-lg-3 mb-3">
                                <a href="{{ route('about_us.values') }}" class="sc-hub-card">
                                    <i class="dripicons-star"></i>
                                    <span>{{ trans('file.Our Values') }}</span>
                                </a>
                            </div>
                            <div class="col-md-6 col-lg-3 mb-3">
                                <a href="{{ route('about_us.winners') }}" class="sc-hub-card">
                                    <i class="dripicons-trophy"></i>
                                    <span>{{ trans('file.Winners') }}</span>
                                </a>
                            </div>
                            <div class="col-md-6 col-lg-3 mb-3">
                                <a href="{{ route('about_us.index') }}" class="sc-hub-card">
                                    <i class="dripicons-user-group"></i>
                                    <span>{{ trans('file.Our Leaders') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        {{-- Judges order --}}
        <div class="tab-pane fade" id="sc-judges" role="tabpanel">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="fa fa-gavel"></i> {{ trans('file.Judges') }} — {{ trans('file.Order') }}</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'setting.site_content.section', 'method' => 'post']) !!}
                        <input type="hidden" name="section" value="judges_order">
                        <p class="italic"><small>Reorder judges with the arrows, then press <strong>Save</strong>. This controls the order they appear on the homepage.</small></p>
                        @if(($judges ?? collect())->isEmpty())
                            <p class="text-muted">No judges yet.</p>
                        @else
                        <ul class="sc-menu-order list-unstyled" id="sc-judges-order">
                            @foreach($judges as $judge)
                                <li class="sc-menu-item" data-key="{{ $judge->id }}">
                                    <input type="hidden" name="order[]" value="{{ $judge->id }}">
                                    <span class="sc-menu-label d-flex align-items-center" style="gap:10px;">
                                        @if($judge->image)
                                            <img src="{{ \App\Helpers\ImageOptimizer::employeeImageUrl($judge->image) }}" width="34" height="34" style="border-radius:50%;object-fit:cover;" loading="lazy">
                                        @endif
                                        {{ $judge->name }}
                                    </span>
                                    <span class="sc-menu-actions">
                                        <button type="button" class="btn btn-sm btn-light sc-move-up" title="Move up"><i class="dripicons-chevron-up"></i></button>
                                        <button type="button" class="btn btn-sm btn-light sc-move-down" title="Move down"><i class="dripicons-chevron-down"></i></button>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="sc-section-actions">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                        @endif
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        </div>

        {{-- Ambassadors order --}}
        <div class="tab-pane fade" id="sc-ambassadors" role="tabpanel">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="fa fa-podcast"></i> {{ trans('file.Ambassadors') }} — {{ trans('file.Order') }}</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'setting.site_content.section', 'method' => 'post']) !!}
                        <input type="hidden" name="section" value="ambassadors_order">
                        <p class="italic"><small>Reorder ambassadors with the arrows, then press <strong>Save</strong>. This controls the order they appear on the homepage.</small></p>
                        @if(($ambassadors ?? collect())->isEmpty())
                            <p class="text-muted">No ambassadors yet.</p>
                        @else
                        <ul class="sc-menu-order list-unstyled" id="sc-ambassadors-order">
                            @foreach($ambassadors as $ambassador)
                                <li class="sc-menu-item" data-key="{{ $ambassador->id }}">
                                    <input type="hidden" name="order[]" value="{{ $ambassador->id }}">
                                    <span class="sc-menu-label d-flex align-items-center" style="gap:10px;">
                                        @if($ambassador->image)
                                            <img src="{{ \App\Helpers\ImageOptimizer::employeeImageUrl($ambassador->image) }}" width="34" height="34" style="border-radius:50%;object-fit:cover;" loading="lazy">
                                        @endif
                                        {{ $ambassador->name }}
                                    </span>
                                    <span class="sc-menu-actions">
                                        <button type="button" class="btn btn-sm btn-light sc-move-up" title="Move up"><i class="dripicons-chevron-up"></i></button>
                                        <button type="button" class="btn btn-sm btn-light sc-move-down" title="Move down"><i class="dripicons-chevron-down"></i></button>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="sc-section-actions">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                        @endif
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        </div>

        {{-- Logos / Partners --}}
        @if(in_array('employees-index', $all_permission ?? []))
        <div class="tab-pane fade" id="sc-partners" role="tabpanel">
        @if(($partners ?? collect())->count() > 1)
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="fa fa-sort"></i> {{ trans('file.Logos') }} — {{ trans('file.Order') }}</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'setting.site_content.section', 'method' => 'post']) !!}
                        <input type="hidden" name="section" value="partners_order">
                        <p class="italic"><small>Reorder logos with the arrows, then press <strong>Save</strong>. This controls the order they appear on the homepage.</small></p>
                        <ul class="sc-menu-order list-unstyled" id="sc-partners-order">
                            @foreach($partners as $partner)
                                <li class="sc-menu-item" data-key="{{ $partner->id }}">
                                    <input type="hidden" name="order[]" value="{{ $partner->id }}">
                                    <span class="sc-menu-label d-flex align-items-center" style="gap:10px;">
                                        @if($partner->image)
                                            <img src="{{ url('public/images/partners', $partner->image) }}" height="30" style="max-width:90px;object-fit:contain;background:#f4f7fc;padding:2px;border-radius:6px;" loading="lazy">
                                        @endif
                                        {{ $partner->name ?: trans('file.Logo') }}
                                    </span>
                                    <span class="sc-menu-actions">
                                        <button type="button" class="btn btn-sm btn-light sc-move-up" title="Move up"><i class="dripicons-chevron-up"></i></button>
                                        <button type="button" class="btn btn-sm btn-light sc-move-down" title="Move down"><i class="dripicons-chevron-down"></i></button>
                                    </span>
                                </li>
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
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4><i class="fa fa-images"></i> {{ trans('file.Logos') }}</h4>
                        @if(in_array('employees-add', $all_permission ?? []))
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addPartnerModal"><i class="dripicons-plus"></i> {{ trans('file.Add Logo') }}</button>
                        @endif
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{ trans('file.Upload partner or sponsor logos and set the Sort Order to control which one appears first or last on the homepage.') }}</small></p>
                        <div class="table-responsive">
                            <table id="partner-table" class="table">
                                <thead>
                                    <tr>
                                        <th>{{ trans('file.Logo') }}</th>
                                        <th>{{ trans('file.name') }}</th>
                                        <th>{{ trans('file.Link') }}</th>
                                        <th>{{ trans('file.Sort Order') }}</th>
                                        <th>{{ trans('file.Status') }}</th>
                                        <th class="not-exported">{{ trans('file.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(($partners ?? []) as $partner)
                                    <tr>
                                        <td>
                                            @if($partner->image)
                                                <img src="{{ url('public/images/partners', $partner->image) }}" height="50" style="max-width:120px;object-fit:contain;background:#f4f7fc;padding:4px;border-radius:8px;">
                                            @else
                                                {{ trans('file.No Image') }}
                                            @endif
                                        </td>
                                        <td>{{ $partner->name }}</td>
                                        <td>
                                            @if($partner->link)
                                                <a href="{{ $partner->link }}" target="_blank" rel="noopener noreferrer">{{ \Illuminate\Support\Str::limit($partner->link, 32) }}</a>
                                            @else — @endif
                                        </td>
                                        <td>{{ $partner->sort_order }}</td>
                                        <td>
                                            @if($partner->is_active)
                                                <span class="badge badge-success">{{ trans('file.Active') }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ trans('file.Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">{{ trans('file.action') }} <span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    @if(in_array('employees-edit', $all_permission ?? []))
                                                    <li>
                                                        <button type="button" class="btn btn-link partner-edit-btn" data-toggle="modal" data-target="#editPartnerModal"
                                                            data-id="{{ $partner->id }}"
                                                            data-name="{{ $partner->name }}"
                                                            data-link="{{ $partner->link }}"
                                                            data-sort_order="{{ $partner->sort_order }}"
                                                            data-is_active="{{ $partner->is_active }}">
                                                            <i class="dripicons-document-edit"></i> {{ trans('file.edit') }}
                                                        </button>
                                                    </li>
                                                    @endif
                                                    @if(in_array('employees-delete', $all_permission ?? []))
                                                    {{ Form::open(['route' => ['partner.destroy', $partner->id], 'method' => 'DELETE']) }}
                                                    <li><button type="submit" class="btn btn-link" onclick="return confirm('{{ trans('file.Delete this logo?') }}')"><i class="dripicons-trash"></i> {{ trans('file.delete') }}</button></li>
                                                    {{ Form::close() }}
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        @endif

        {{-- Landing page (frontend header) menu order --}}
        <div class="tab-pane fade" id="sc-frontend_menu" role="tabpanel">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4><i class="dripicons-web"></i> {{ trans('file.Landing Menu') }} — {{ trans('file.Order') }}</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'setting.site_content.section', 'method' => 'post']) !!}
                        <input type="hidden" name="section" value="frontend_menu_order">
                        <p class="italic"><small>Reorder the public site header menu with the arrows, then press <strong>Save</strong>. This controls the order they appear on the landing page.</small></p>
                        <ul class="sc-menu-order list-unstyled" id="sc-frontend-menu-order">
                            @foreach($frontend_menu_order as $key)
                                @if(isset($frontend_menu_labels[$key]))
                                <li class="sc-menu-item" data-key="{{ $key }}">
                                    <input type="hidden" name="frontend_menu_order[]" value="{{ $key }}">
                                    <span class="sc-menu-label">{{ trans('file.' . $frontend_menu_labels[$key]) }}</span>
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

        {{-- Side menu order --}}
        <div class="tab-pane fade" id="sc-menu_order" role="tabpanel">
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

        </div>{{-- /.tab-content --}}
    </div>
</section>

@if(in_array('employees-index', $all_permission ?? []))
<div id="addPartnerModal" class="modal fade text-left" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document"><div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ trans('file.Add Logo') }}</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            {!! Form::open(['route' => 'partner.store', 'method' => 'post', 'files' => true]) !!}
            <div class="form-group"><label>{{ trans('file.Logo') }} *</label><input type="file" name="image" class="form-control" accept="image/*" required></div>
            <div class="form-group"><label>{{ trans('file.name') }}</label><input type="text" name="name" class="form-control" placeholder="{{ trans('file.Partner name') }}"></div>
            <div class="form-group"><label>{{ trans('file.Link') }}</label><input type="url" name="link" class="form-control" placeholder="https://example.com"></div>
            <div class="form-group"><label>{{ trans('file.Sort Order') }}</label><input type="number" name="sort_order" class="form-control" value="0" min="0"></div>
            <div class="form-group">
                <label>{{ trans('file.Status') }}</label>
                <select name="is_active" class="form-control">
                    <option value="1">{{ trans('file.Active') }}</option>
                    <option value="0">{{ trans('file.Inactive') }}</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div></div>
</div>

<div id="editPartnerModal" class="modal fade text-left" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document"><div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ trans('file.Edit Logo') }}</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            {!! Form::open(['route' => 'partner.update', 'method' => 'post', 'files' => true]) !!}
            <input type="hidden" name="partner_id" id="edit-partner-id">
            <div class="form-group"><label>{{ trans('file.Logo') }}</label><input type="file" name="image" class="form-control" accept="image/*"><small class="text-muted">{{ trans('file.Leave empty to keep current logo') }}</small></div>
            <div class="form-group"><label>{{ trans('file.name') }}</label><input type="text" name="name" id="edit-partner-name" class="form-control"></div>
            <div class="form-group"><label>{{ trans('file.Link') }}</label><input type="url" name="link" id="edit-partner-link" class="form-control"></div>
            <div class="form-group"><label>{{ trans('file.Sort Order') }}</label><input type="number" name="sort_order" id="edit-partner-sort-order" class="form-control" min="0"></div>
            <div class="form-group">
                <label>{{ trans('file.Status') }}</label>
                <select name="is_active" id="edit-partner-is-active" class="form-control">
                    <option value="1">{{ trans('file.Active') }}</option>
                    <option value="0">{{ trans('file.Inactive') }}</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div></div>
</div>
@endif

<style>
    .sc-tabs { border-bottom:none; margin-bottom:24px; flex-wrap:wrap; gap:6px; }
    .sc-tabs .nav-item { margin:0 4px 6px 0; }
    .sc-tabs .nav-link { color:#334155; font-weight:700; border:2px solid #cbd5e1; border-radius:30px; padding:8px 18px; background:#fff; transition:.18s; }
    .sc-tabs .nav-link i { margin-right:6px; }
    .sc-tabs .nav-link:hover { transform:translateY(-1px); }
    /* Colorful borders per tab, filled when active (like the grading tabs). */
    .sc-tabs .nav-item:nth-child(8n+1) .nav-link { border-color:#2563eb; color:#2563eb; }
    .sc-tabs .nav-item:nth-child(8n+1) .nav-link.active { background:#2563eb; color:#fff; }
    .sc-tabs .nav-item:nth-child(8n+2) .nav-link { border-color:#16a34a; color:#16a34a; }
    .sc-tabs .nav-item:nth-child(8n+2) .nav-link.active { background:#16a34a; color:#fff; }
    .sc-tabs .nav-item:nth-child(8n+3) .nav-link { border-color:#e87722; color:#e87722; }
    .sc-tabs .nav-item:nth-child(8n+3) .nav-link.active { background:#e87722; color:#fff; }
    .sc-tabs .nav-item:nth-child(8n+4) .nav-link { border-color:#7c3aed; color:#7c3aed; }
    .sc-tabs .nav-item:nth-child(8n+4) .nav-link.active { background:#7c3aed; color:#fff; }
    .sc-tabs .nav-item:nth-child(8n+5) .nav-link { border-color:#0d9488; color:#0d9488; }
    .sc-tabs .nav-item:nth-child(8n+5) .nav-link.active { background:#0d9488; color:#fff; }
    .sc-tabs .nav-item:nth-child(8n+6) .nav-link { border-color:#db2777; color:#db2777; }
    .sc-tabs .nav-item:nth-child(8n+6) .nav-link.active { background:#db2777; color:#fff; }
    .sc-tabs .nav-item:nth-child(8n+7) .nav-link { border-color:#dc2626; color:#dc2626; }
    .sc-tabs .nav-item:nth-child(8n+7) .nav-link.active { background:#dc2626; color:#fff; }
    .sc-tabs .nav-item:nth-child(8n+8) .nav-link { border-color:#4f46e5; color:#4f46e5; }
    .sc-tabs .nav-item:nth-child(8n+8) .nav-link.active { background:#4f46e5; color:#fff; }
    .sc-hub-card { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:10px; text-align:center; padding:26px 14px; height:100%; border:2px solid #e3e9f4; border-radius:14px; color:#14223f; font-weight:700; background:#f8fafc; transition:.18s; }
    .sc-hub-card:hover { border-color:#2563eb; color:#1d4ed8; transform:translateY(-2px); box-shadow:0 10px 26px rgba(37,99,235,.14); text-decoration:none; }
    .sc-hub-card i { font-size:26px; color:#2563eb; }
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
    .sc-paste-zone { border:2px dashed #c5d3ea; border-radius:10px; padding:16px; background:#f8fafc; cursor:text; }
    .sc-paste-zone:focus { outline:none; border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,.15); }
    .sc-gallery-thumb { position:relative; }
    .sc-gallery-del {
        position:absolute; top:6px; right:6px; z-index:2;
        width:30px; height:30px; border-radius:50%; border:0;
        background:rgba(220,38,38,.92); color:#fff; cursor:pointer;
        display:flex; align-items:center; justify-content:center;
        box-shadow:0 2px 6px rgba(0,0,0,.25); transition:background .15s, transform .15s;
    }
    .sc-gallery-del:hover { background:#b91c1c; transform:scale(1.08); }
    .sc-gallery-del i { font-size:14px; line-height:1; }
    .sc-gallery-newitem { position:relative; }
    .sc-gallery-newitem img { height:110px; width:100%; object-fit:cover; border-radius:8px; border:2px solid #22c55e; }
    .sc-gallery-newitem .sc-gallery-del { background:rgba(15,23,42,.85); }
    .sc-gallery-newitem .sc-gallery-del:hover { background:#0f172a; }
</style>

<script type="text/javascript">
    $("#site-content-top-menu").addClass("active");

    (function () {
        // Open the tab referenced in the URL hash (e.g. after saving a section).
        function activateTabFromHash() {
            var hash = window.location.hash;
            if (hash && $('.sc-tabs a[href="' + hash + '"]').length) {
                $('.sc-tabs a[href="' + hash + '"]').tab('show');
            }
        }
        activateTabFromHash();
        window.addEventListener('hashchange', activateTabFromHash);

        // Keep the hash in sync so a refresh / save stays on the same tab.
        $('.sc-tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            if (history.replaceState) {
                history.replaceState(null, null, $(e.target).attr('href'));
            } else {
                window.location.hash = $(e.target).attr('href');
            }
        });

        function castingRow() {
            var idx = $('#casting-table tbody tr').length;
            return '<tr>' +
                '<td><input type="text" name="province[]" class="form-control"></td>' +
                '<td><input type="text" name="venue[]" class="form-control"></td>' +
                '<td><input type="text" name="cast_date[]" class="form-control" placeholder="e.g. April 4\u20135, 2026"></td>' +
                '<td class="align-middle"><label class="sc-switch mb-0"><input type="checkbox" name="cast_enabled[' + idx + ']" value="1" checked><span class="sc-slider"></span></label></td>' +
                '<td><button type="button" class="btn btn-danger btn-sm sc-remove-row">&times;</button></td>' +
                '</tr>';
        }
        function primeRow() {
            var idx = $('#primes-table tbody tr').length;
            return '<tr>' +
                '<td><input type="text" name="prime_label[]" class="form-control"></td>' +
                '<td><input type="datetime-local" name="prime_date[]" class="form-control"></td>' +
                '<td><input type="hidden" name="prime_image_existing[]" value="">' +
                '<input type="file" name="prime_image[' + idx + ']" accept="image/*" class="form-control-file"></td>' +
                '<td class="align-middle"><label class="sc-switch mb-0"><input type="checkbox" name="prime_enabled[' + idx + ']" value="1" checked><span class="sc-slider"></span></label></td>' +
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

        var popupInput = document.getElementById('popup_image_input');
        var popupPreview = document.getElementById('popup-preview-img');
        var popupZone = document.getElementById('popup-paste-zone');

        function previewPopupFile(file) {
            if (!file || !popupPreview) return;
            var reader = new FileReader();
            reader.onload = function (ev) { popupPreview.src = ev.target.result; };
            reader.readAsDataURL(file);
        }

        if (popupInput) {
            popupInput.addEventListener('change', function () {
                if (this.files && this.files[0]) previewPopupFile(this.files[0]);
            });
        }

        function attachPopupPaste(e) {
            if (!e.clipboardData || !e.clipboardData.items || !popupInput) return;
            for (var i = 0; i < e.clipboardData.items.length; i++) {
                if (e.clipboardData.items[i].type.indexOf('image') === -1) continue;
                var file = e.clipboardData.items[i].getAsFile();
                if (!file) continue;
                e.preventDefault();
                var dt = new DataTransfer();
                dt.items.add(file);
                popupInput.files = dt.files;
                previewPopupFile(file);
                break;
            }
        }

        if (popupZone) popupZone.addEventListener('paste', attachPopupPaste);
        document.addEventListener('paste', function (e) {
            if (!popupZone) return;
            var t = e.target;
            if (t && (t.tagName === 'INPUT' || t.tagName === 'TEXTAREA') && !popupZone.contains(t)) return;
            if (document.activeElement === popupZone || popupZone.contains(document.activeElement)) {
                attachPopupPaste(e);
            }
        });

        // Gallery: paste appends images to the multiple file input.
        var galleryInput = document.getElementById('gallery_images_input');
        var galleryZone = document.getElementById('gallery-paste-zone');
        var galleryPreview = document.getElementById('gallery-new-preview');
        var galleryPreviewItems = document.getElementById('gallery-new-preview-items');

        // Re-render thumbnails of the files currently queued in the file input.
        function renderGalleryPreview() {
            if (!galleryInput || !galleryPreviewItems) return;
            galleryPreviewItems.innerHTML = '';
            var files = galleryInput.files;
            if (!files || !files.length) {
                if (galleryPreview) galleryPreview.style.display = 'none';
                return;
            }
            if (galleryPreview) galleryPreview.style.display = 'flex';
            Array.prototype.forEach.call(files, function (file, idx) {
                var col = document.createElement('div');
                col.className = 'col-md-3 col-6 mb-3 sc-gallery-newitem';
                var img = document.createElement('img');
                img.alt = file.name || 'preview';
                var reader = new FileReader();
                reader.onload = function (ev) { img.src = ev.target.result; };
                reader.readAsDataURL(file);
                var del = document.createElement('button');
                del.type = 'button';
                del.className = 'sc-gallery-del';
                del.title = 'Remove';
                del.innerHTML = '&times;';
                del.style.fontSize = '18px';
                del.style.lineHeight = '1';
                del.addEventListener('click', function () { removeGalleryPendingFile(idx); });
                col.appendChild(img);
                col.appendChild(del);
                galleryPreviewItems.appendChild(col);
            });
        }

        // Drop a single queued (not-yet-saved) file by index.
        function removeGalleryPendingFile(index) {
            if (!galleryInput || !galleryInput.files) return;
            var dt = new DataTransfer();
            Array.prototype.forEach.call(galleryInput.files, function (file, i) {
                if (i !== index) dt.items.add(file);
            });
            galleryInput.files = dt.files;
            renderGalleryPreview();
        }

        function attachGalleryPaste(e) {
            if (!e.clipboardData || !e.clipboardData.items || !galleryInput) return;
            // Guard: the same paste event can bubble to more than one listener.
            if (e.__galleryHandled) return;
            var dt = new DataTransfer();
            if (galleryInput.files) {
                for (var f = 0; f < galleryInput.files.length; f++) { dt.items.add(galleryInput.files[f]); }
            }
            var added = false;
            for (var i = 0; i < e.clipboardData.items.length; i++) {
                if (e.clipboardData.items[i].type.indexOf('image') === -1) continue;
                var file = e.clipboardData.items[i].getAsFile();
                if (!file) continue;
                dt.items.add(file);
                added = true;
            }
            if (added) {
                e.__galleryHandled = true;
                e.preventDefault();
                galleryInput.files = dt.files;
                renderGalleryPreview();
            }
        }
        if (galleryZone) galleryZone.addEventListener('paste', attachGalleryPaste);
        document.addEventListener('paste', function (e) {
            if (!galleryZone) return;
            if (document.activeElement === galleryZone || galleryZone.contains(document.activeElement)) {
                attachGalleryPaste(e);
            }
        });
        if (galleryInput) {
            galleryInput.addEventListener('change', renderGalleryPreview);
        }

        // Instant delete of an already-saved gallery image.
        var galleryDeleteUrl = @json(route('setting.site_content.gallery.delete'));
        var galleryCsrf = @json(csrf_token());
        document.querySelectorAll('#gallery-existing .sc-gallery-del').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var card = btn.closest('.sc-gallery-card');
                if (!card) return;
                if (!window.confirm('Delete this photo? This cannot be undone.')) return;
                var image = card.getAttribute('data-image');
                btn.disabled = true;
                fetch(galleryDeleteUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': galleryCsrf, 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify({ image: image })
                }).then(function (r) { return r.json(); }).then(function (res) {
                    if (res && res.status === 'ok') {
                        card.parentNode.removeChild(card);
                    } else {
                        btn.disabled = false;
                        alert('Could not delete the photo. Please try again.');
                    }
                }).catch(function () {
                    btn.disabled = false;
                    alert('Could not delete the photo. Please try again.');
                });
            });
        });

        // Logos / Partners
        $('.partner-edit-btn').on('click', function () {
            $('#edit-partner-id').val($(this).data('id'));
            $('#edit-partner-name').val($(this).data('name'));
            $('#edit-partner-link').val($(this).data('link'));
            $('#edit-partner-sort-order').val($(this).data('sort_order'));
            $('#edit-partner-is-active').val(String($(this).data('is_active')) === '0' ? '0' : '1');
        });
        if ($.fn.DataTable && $('#partner-table').length) {
            var partnerTable = $('#partner-table').DataTable({
                "order": [[3, 'asc']],
                'columnDefs': [{ "orderable": false, 'targets': [0, 5] }],
                'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });
            // Fix column widths when the Logos tab becomes visible.
            $('.sc-tabs a[href="#sc-partners"]').on('shown.bs.tab', function () {
                partnerTable.columns.adjust();
            });
        }
    })();
</script>
@endsection

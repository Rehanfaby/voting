@extends('layout.main')
@section('content')

@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

@php
    $activeCat = collect($categories)->firstWhere('id', $activeCategoryId) ?: ($categories[0] ?? null);
@endphp

<section class="forms mg-gal-admin">
    <div class="container-fluid">
        <div class="mg-gal-admin__hero">
            <div>
                <p class="mg-gal-admin__eyebrow">Site Content</p>
                <h1 class="mg-gal-admin__title">{{ trans('file.Gallery') }}</h1>
                <p class="mg-gal-admin__sub">Create albums (TikTok, Events, …) and add photos for the public Gallery page.</p>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="mb-3">Albums / categories</h5>
                <div class="mg-gal-cats mb-3">
                    @foreach($categories as $cat)
                        <a href="{{ route('gallery.admin', ['category' => $cat['id']]) }}"
                           class="mg-gal-cat {{ $cat['id'] === $activeCategoryId ? 'is-active' : '' }}">
                            {{ $cat['name'] }}
                        </a>
                    @endforeach
                </div>
                <form action="{{ route('gallery.admin.category.store') }}" method="POST" class="form-inline flex-wrap">
                    @csrf
                    <input type="text" name="name" class="form-control mr-2 mb-2" placeholder="New album name (e.g. TikTok)" required maxlength="60">
                    <button type="submit" class="btn btn-primary mb-2">Add album</button>
                </form>
                @if($activeCat && count($categories) > 1)
                    <form action="{{ route('gallery.admin.category.delete') }}" method="POST" class="mt-2" onsubmit="return confirm('Delete album “{{ $activeCat['name'] }}”? Photos move to another album.');">
                        @csrf
                        <input type="hidden" name="category_id" value="{{ $activeCat['id'] }}">
                        <button type="submit" class="btn btn-link text-danger p-0">Delete “{{ $activeCat['name'] }}” album</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="mb-0"><i class="dripicons-photo-group"></i> {{ $activeCat['name'] ?? 'Gallery' }}</h4>
            </div>
            <div class="card-body">
                {!! Form::open(['route' => 'gallery.admin.images.store', 'method' => 'post', 'files' => true, 'id' => 'gallery-admin-form']) !!}
                <input type="hidden" name="category_id" value="{{ $activeCategoryId }}">
                <p class="italic"><small>Add photos to <strong>{{ $activeCat['name'] ?? 'this album' }}</strong>. Paste (Ctrl/Cmd+V) or choose files, then press <strong>Save</strong>.</small></p>

                <div id="gallery-paste-zone" class="sc-paste-zone mb-3" tabindex="0">
                    <p class="mb-2"><strong>Paste or upload</strong> — click here and press <kbd>Ctrl+V</kbd> / <kbd>Cmd+V</kbd>, or choose files below.</p>
                    <input type="file" name="gallery_images[]" id="gallery_images_input" accept="image/*" multiple class="form-control-file">
                </div>

                <div class="row" id="gallery-new-preview" style="display:none;">
                    <div class="col-12"><p class="mb-2"><small class="text-info"><i class="dripicons-photo"></i> New photos to be saved</small></p></div>
                    <div class="col-12"><div class="row" id="gallery-new-preview-items"></div></div>
                </div>

                <div class="row" id="gallery-existing">
                    @foreach($items as $i => $g)
                        @php $gimg = is_array($g) ? ($g['image'] ?? '') : $g; @endphp
                        @if($gimg)
                        <div class="col-md-3 col-6 mb-3 sc-gallery-card" data-image="{{ $gimg }}">
                            <div class="card h-100">
                                <div class="sc-gallery-thumb">
                                    <img src="{{ \App\Helpers\SiteContent::publicUploadUrl($gimg) }}?v={{ config('app.version') }}" style="height:120px;width:100%;object-fit:cover;border-radius:6px 6px 0 0;">
                                    <button type="button" class="sc-gallery-del" title="{{ trans('file.delete') }}"><i class="dripicons-trash"></i></button>
                                </div>
                                <div class="card-body p-2">
                                    <input type="hidden" name="gallery_existing[{{ $i }}]" value="{{ $gimg }}">
                                    <input type="text" name="gallery_caption[{{ $i }}]" class="form-control form-control-sm" placeholder="Caption (optional)" value="{{ is_array($g) ? ($g['caption'] ?? '') : '' }}">
                                    <select name="gallery_category[{{ $i }}]" class="form-control form-control-sm mt-1" title="Move to album">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat['id'] }}" {{ $cat['id'] === $activeCategoryId ? 'selected' : '' }}>{{ $cat['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('gallery.page') }}" class="btn btn-outline-secondary" target="_blank">View public Gallery</a>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>

<style>
.mg-gal-admin__hero {
    background: linear-gradient(135deg, #0a2350 0%, #1d4ed8 100%);
    color: #fff; border-radius: 18px; padding: 20px 18px; margin-bottom: 16px;
}
.mg-gal-admin__eyebrow { margin: 0 0 4px; font-size: 12px; letter-spacing: .06em; text-transform: uppercase; color: #f5c518; font-weight: 700; }
.mg-gal-admin__title { margin: 0; font-size: 1.45rem; font-weight: 800; }
.mg-gal-admin__sub { margin: 8px 0 0; font-size: 13px; color: rgba(255,255,255,.82); }
.mg-gal-cats { display: flex; flex-wrap: wrap; gap: 8px; }
.mg-gal-cat {
    display: inline-flex; padding: 8px 14px; border-radius: 999px; border: 1px solid #cbd5e1;
    color: #0a2350 !important; font-weight: 700; font-size: 13px; text-decoration: none !important; background: #fff;
}
.mg-gal-cat.is-active { background: #0a2350; color: #fff !important; border-color: #0a2350; }
.sc-paste-zone {
    border: 2px dashed #93c5fd; border-radius: 12px; padding: 16px; background: #f8fafc;
}
.sc-gallery-thumb { position: relative; }
.sc-gallery-del {
    position: absolute; top: 6px; right: 6px; width: 32px; height: 32px; border: 0; border-radius: 8px;
    background: #dc2626; color: #fff; cursor: pointer;
}
.sc-gallery-del:hover { background: #b91c1c; }
.sc-gallery-newitem img { height: 110px; width: 100%; object-fit: cover; border-radius: 8px; border: 2px solid #22c55e; }
</style>

<script>
$("ul#setting").siblings('a').attr('aria-expanded','false');
document.querySelectorAll('nav.side-navbar .side-menu li[data-menu-key="gallery-admin"] > a').forEach(function (a) {
    a.classList.add('active');
});

(function () {
    var galleryInput = document.getElementById('gallery_images_input');
    var galleryZone = document.getElementById('gallery-paste-zone');
    var previewWrap = document.getElementById('gallery-new-preview');
    var previewItems = document.getElementById('gallery-new-preview-items');
    var pendingFiles = [];

    function syncInput() {
        if (!galleryInput) return;
        var dt = new DataTransfer();
        pendingFiles.forEach(function (f) { dt.items.add(f); });
        galleryInput.files = dt.files;
    }

    function renderGalleryPreview() {
        if (!previewItems || !previewWrap) return;
        previewItems.innerHTML = '';
        if (!pendingFiles.length) {
            previewWrap.style.display = 'none';
            return;
        }
        previewWrap.style.display = '';
        pendingFiles.forEach(function (file, idx) {
            var col = document.createElement('div');
            col.className = 'col-md-3 col-6 mb-3 sc-gallery-newitem';
            var img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            var del = document.createElement('button');
            del.type = 'button';
            del.className = 'sc-gallery-del';
            del.innerHTML = '<i class="dripicons-trash"></i>';
            del.addEventListener('click', function () {
                pendingFiles.splice(idx, 1);
                syncInput();
                renderGalleryPreview();
            });
            col.appendChild(img);
            col.appendChild(del);
            previewItems.appendChild(col);
        });
    }

    function attachGalleryPaste(e) {
        var items = (e.clipboardData || e.originalEvent && e.originalEvent.clipboardData || {}).items || [];
        var added = false;
        for (var i = 0; i < items.length; i++) {
            if (items[i].type && items[i].type.indexOf('image') !== -1) {
                var blob = items[i].getAsFile();
                if (blob) {
                    var ext = (blob.type.split('/')[1] || 'png').replace('jpeg', 'jpg');
                    pendingFiles.push(new File([blob], 'paste-' + Date.now() + '-' + i + '.' + ext, { type: blob.type }));
                    added = true;
                }
            }
        }
        if (added) {
            e.preventDefault();
            syncInput();
            renderGalleryPreview();
        }
    }

    if (galleryZone) galleryZone.addEventListener('paste', attachGalleryPaste);
    document.addEventListener('paste', function (e) {
        if (document.activeElement && document.activeElement.closest && document.activeElement.closest('#gallery-admin-form')) {
            attachGalleryPaste(e);
        }
    });
    if (galleryInput) {
        galleryInput.addEventListener('change', function () {
            pendingFiles = Array.prototype.slice.call(galleryInput.files || []);
            renderGalleryPreview();
        });
    }

    document.querySelectorAll('#gallery-existing .sc-gallery-del').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var card = btn.closest('.sc-gallery-card');
            if (!card) return;
            var image = card.getAttribute('data-image');
            if (!image || !confirm('Delete this photo?')) return;
            fetch('{{ route('gallery.admin.image.delete') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ image: image })
            }).then(function (r) { return r.json(); }).then(function () {
                card.parentNode.removeChild(card);
            }).catch(function () { alert('Could not delete image.'); });
        });
    });
})();
</script>
@endsection

{{-- EN / FR pill toggle (Beyond / Alpha Bridge style) --}}
<div class="ms-lang-switch ms-lang-switch--frontend" aria-label="{{ trans('file.language') }}">
    <a href="{{ url('language_switch/en') }}" class="ms-lang {{ app()->getLocale() === 'en' ? 'active' : '' }}" title="English">EN</a>
    <a href="{{ url('language_switch/fr') }}" class="ms-lang {{ app()->getLocale() === 'fr' ? 'active' : '' }}" title="Français">FR</a>
</div>

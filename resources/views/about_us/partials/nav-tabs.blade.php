<ul class="nav nav-tabs mb-4">
    <li class="nav-item"><a class="nav-link {{ ($active ?? '') === 'settings' ? 'active' : '' }}" href="{{ route('about_us.settings') }}">{{ trans('file.About Page Content') }}</a></li>
    <li class="nav-item"><a class="nav-link {{ ($active ?? '') === 'values' ? 'active' : '' }}" href="{{ route('about_us.values') }}">{{ trans('file.Our Values') }}</a></li>
    <li class="nav-item"><a class="nav-link {{ ($active ?? '') === 'leaders' ? 'active' : '' }}" href="{{ route('about_us.index') }}">{{ trans('file.Our Leaders') }}</a></li>
    <li class="nav-item"><a class="nav-link {{ ($active ?? '') === 'winners' ? 'active' : '' }}" href="{{ route('about_us.winners') }}">{{ trans('file.Winners') }}</a></li>
</ul>

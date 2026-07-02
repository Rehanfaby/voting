@php
    $flagCode = \App\Helpers\CountryFlag::code($country ?? null);
    $flagEmoji = $flagCode ? \App\Helpers\CountryFlag::emoji($flagCode) : '';
    $flagLabel = \App\Helpers\CountryFlag::label($country ?? null);
    $flagUrl = $flagCode ? \App\Helpers\CountryFlag::url($flagCode, $size ?? 20) : null;
@endphp
@if($flagEmoji || $flagUrl)
    <span class="mg-country-flag {{ $class ?? '' }}" title="{{ $flagLabel }}" aria-label="{{ $flagLabel }}">
        @if($flagUrl)
            <img src="{{ $flagUrl }}" alt="{{ $flagLabel }}" width="{{ $size ?? 20 }}" height="{{ (int) round(($size ?? 20) * 0.75) }}" loading="lazy" decoding="async" onerror="this.style.display='none';this.nextElementSibling.style.display='inline';">
        @endif
        <span class="mg-country-flag__emoji" @if($flagUrl) style="display:none;" @endif>{{ $flagEmoji }}</span>
    </span>
@endif

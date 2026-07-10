@php
    $embedSrc = \App\Helpers\SocialEmbed::embedSrc($item->file ?? '', $item->type ?? '');
    $platform = \App\Helpers\SocialEmbed::platformLabel($item->type ?? '');
    $isVertical = in_array($item->type, ['short', 'tiktok'], true);
@endphp
@if($embedSrc)
<div class="mg-social-embed {{ $isVertical ? 'mg-social-embed--vertical' : '' }}">
    <span class="mg-social-embed__badge">{{ $platform }}</span>
    <div class="mg-social-embed__frame-wrap">
        <iframe src="{{ $embedSrc }}" title="{{ $platform }} preview" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
    @if(!empty($item->employee_name))
        <p class="mg-social-embed__caption">{{ $item->employee_name }}</p>
    @endif
</div>
@endif

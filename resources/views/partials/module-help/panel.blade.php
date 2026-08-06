@php
    $guides = config('module_help', []);
    $version = config('app.version');
@endphp
<div id="ms-module-help" class="ms-module-help" style="display:none" aria-live="polite">
    <div class="card mg-help-card ms-module-help-card">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
            <h4 class="mb-0"><i class="dripicons-question"></i> <span id="ms-module-help-title">{{ trans('file.Help') }}</span></h4>
            <div class="d-flex align-items-center flex-wrap">
                <small class="text-muted mr-3">{{ config('app.version_label') }}</small>
                <a href="{{ route('setting.help') }}" class="btn btn-sm btn-outline-primary mr-2">{{ trans('file.User Guide') }}</a>
                <button type="button" class="btn btn-sm btn-light" id="ms-module-help-close">{{ trans('file.Close') }}</button>
            </div>
        </div>
        <div class="card-body">
            @foreach($guides as $key => $guide)
                <div class="ms-module-help-pane" data-help-key="{{ $key }}" style="display:none">
                    <p class="mg-help-intro">{{ $guide['intro'] ?? '' }}</p>

                    @if(!empty($guide['shots']))
                        <div class="mg-help-shot-grid">
                            @foreach($guide['shots'] as $shot)
                                @php
                                    $file = $shot['file'] ?? '';
                                    $src = $file ? asset('public/img/help/' . $file) . '?v=' . $version : null;
                                    $url = $shot['url'] ?? null;
                                    if ($url && strpos($url, 'http') !== 0) {
                                        $url = url($url);
                                    }
                                @endphp
                                @if($src)
                                <figure class="mg-help-shot">
                                    @if($url)
                                        <a href="{{ $url }}" target="_blank" rel="noopener">
                                            <img src="{{ $src }}" alt="{{ $shot['caption'] ?? '' }}" loading="lazy">
                                        </a>
                                    @else
                                        <img src="{{ $src }}" alt="{{ $shot['caption'] ?? '' }}" loading="lazy">
                                    @endif
                                    @if(!empty($shot['caption']))
                                        <figcaption>
                                            {{ $shot['caption'] }}
                                            @if($url)
                                                — <a href="{{ $url }}" target="_blank" rel="noopener">open</a>
                                            @endif
                                        </figcaption>
                                    @endif
                                </figure>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($guide['videos']))
                        <h6>Short videos (no voice)</h6>
                        <div class="mg-help-video-grid">
                            @foreach($guide['videos'] as $video)
                                <figure class="mg-help-video">
                                    <video controls playsinline preload="metadata" poster="">
                                        <source src="{{ asset('public/videos/help/' . $video['file']) }}?v={{ $version }}" type="video/mp4">
                                    </video>
                                    @if(!empty($video['caption']))
                                        <figcaption>{{ $video['caption'] }}</figcaption>
                                    @endif
                                </figure>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($guide['steps']))
                        <h6>How to use</h6>
                        <ol class="mg-help-steps">
                            @foreach($guide['steps'] as $step)
                                <li>{!! $step !!}</li>
                            @endforeach
                        </ol>
                    @endif

                    @if(!empty($guide['show_candidates']))
                        @include('partials.module-help.candidates-gallery')
                    @endif

                    @if(!empty($guide['tips']))
                        <div class="mg-help-box">
                            <strong>Tips</strong>
                            <ul class="mb-0">
                                @foreach($guide['tips'] as $tip)
                                    <li>{!! $tip !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endforeach

            <div class="ms-module-help-pane" data-help-key="default" style="display:none">
                <p class="mg-help-intro">Use this Help tab on any menu for a short guide. For the full platform manual, open Settings → Help.</p>
                <div class="mg-help-box">
                    <ul class="mb-0">
                        <li><a href="{{ route('setting.help') }}">{{ trans('file.User Guide') }}</a></li>
                        <li><a href="{{ route('team') }}" target="_blank">Vote Now (public)</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

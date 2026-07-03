@php
    $previewSection = $previewSection ?? 'settings';
    $about = $about ?? \App\Helpers\SiteContent::get('about_page', []);
    $previewMembers = $previewMembers ?? collect();
    $previewWinners = $previewWinners ?? collect();
    $previewYear = $previewYear ?? \App\Helpers\SiteContent::aboutWinnersYear();
@endphp
<div class="card mb-4 border-primary">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <strong><i class="fa fa-eye"></i> {{ trans('file.Frontend preview') }}</strong>
        <a href="{{ route('about') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">{{ trans('file.View live page') }}</a>
    </div>
    <div class="card-body" style="background:#0a2350;color:#fff;border-radius:0 0 8px 8px;">
        @if($previewSection === 'settings')
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <img src="{{ \App\Helpers\SiteContent::aboutImageUrl() }}" alt="" style="width:100%;max-height:180px;object-fit:cover;border-radius:12px;">
                </div>
                <div class="col-md-8">
                    <small class="text-warning">{{ trans('file.About Us') }}</small>
                    <h5 class="text-white mb-2">{{ \App\Helpers\SiteContent::aboutField('mission_title', trans('file.Raising true worshipers across Cameroon')) }}</h5>
                    <p class="small mb-1" style="opacity:.85">{{ \Illuminate\Support\Str::limit(\App\Helpers\SiteContent::aboutField('mission_p1', trans('file.About mission paragraph 1')), 160) }}</p>
                    <p class="small mb-0" style="opacity:.75">{{ \App\Helpers\SiteContent::aboutField('intro_title', trans('file.Cameroon gospel capital')) }}</p>
                </div>
            </div>
        @elseif($previewSection === 'values')
            <h5 class="text-white mb-3">{{ \App\Helpers\SiteContent::aboutField('values_heading', trans('file.Our Values')) }}</h5>
            <div class="d-flex flex-wrap gap-2">
                @foreach(\App\Helpers\SiteContent::aboutValues() as $value)
                    <span class="badge badge-light text-dark px-3 py-2"><i class="fa-solid {{ $value['icon'] }} text-primary"></i> {{ $value['label'] }}</span>
                @endforeach
            </div>
        @elseif($previewSection === 'leaders')
            <h5 class="text-white mb-3">{{ \App\Helpers\SiteContent::aboutField('leaders_heading', trans('file.Our Leaders')) }}</h5>
            <div class="row g-3">
                @forelse($previewMembers as $member)
                    <div class="col-md-4 col-6 text-center">
                        @if($member->image)
                            <img src="{{ url('public/images/employee', $member->image) }}" alt="" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:2px solid #e87722;">
                        @else
                            <div style="width:72px;height:72px;border-radius:50%;background:#1d4ed8;line-height:72px;margin:0 auto;font-weight:700;">{{ strtoupper(substr($member->name,0,1)) }}</div>
                        @endif
                        <div class="small mt-2 font-weight-bold">{{ $member->name }}</div>
                        <div class="small" style="opacity:.75">{{ $member->title }}</div>
                    </div>
                @empty
                    <div class="col-12"><em style="opacity:.7">{{ trans('file.No leaders added yet') }}</em></div>
                @endforelse
            </div>
        @elseif($previewSection === 'winners')
            <h5 class="text-white mb-3">{{ \App\Helpers\SiteContent::aboutField('winners_heading', $previewYear . ' ' . trans('file.Winners')) }}</h5>
            <div class="row g-3">
                @foreach(\App\AboutWinner::PLACEMENTS as $placement => $placementLabel)
                    @php $winner = $previewWinners[$placement] ?? null; @endphp
                    @if($winner && trim((string) $winner->name) !== '')
                        <div class="col-md-4 text-center">
                            @if($winner->image)
                                <img src="{{ url('public/images/employee', $winner->image) }}" alt="" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid #f59e0b;">
                            @endif
                            <div class="small mt-2"><span class="badge badge-warning">{{ $placementLabel }}</span></div>
                            <div class="font-weight-bold">{{ $winner->name }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>

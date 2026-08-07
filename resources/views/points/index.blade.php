@extends('layout.main')
@section('content')

@if($errors->has('name'))
<div class="alert alert-danger alert-dismissible text-center">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('name') }}</div>
@endif
@if($errors->has('image'))
<div class="alert alert-danger alert-dismissible text-center">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('image') }}</div>
@endif
@if($errors->has('email'))
<div class="alert alert-danger alert-dismissible text-center">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('email') }}</div>
@endif
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif
@if(session('success'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session('success') }}</div>
@endif

@php
    use App\Helpers\ImageOptimizer;
    $list = collect($points ?? []);
    $count = $list->count();
    $maxPoints = 100;
@endphp

<section class="mg-awaiting">
    <div class="container-fluid">
        <div class="mg-awaiting__hero">
            <div>
                <p class="mg-awaiting__eyebrow">Judge Grading</p>
                <h1 class="mg-awaiting__title">{{ trans('file.Grade Listing') }}</h1>
                <p class="mg-awaiting__sub">
                    Candidates you have already graded (max {{ $maxPoints }}). Search or tap a card to view or edit.
                </p>
            </div>
            <div class="mg-awaiting__count" aria-label="{{ $count }} graded">
                <span class="mg-awaiting__count-num">{{ $count }}</span>
                <span class="mg-awaiting__count-label">graded</span>
            </div>
        </div>

        <div class="mg-awaiting__toolbar">
            <div class="mg-awaiting__search">
                <i class="fa fa-search"></i>
                <input type="search" id="mg-list-search" placeholder="Search candidate or judge…" autocomplete="off">
            </div>
            @if(in_array('points_add', $all_permission ?? []))
                <a class="mg-awaiting__help-link" href="{{ route('points.create') }}">
                    <i class="fa fa-plus"></i> {{ trans('file.Grade Candidate') }}
                </a>
            @endif
            <a class="mg-awaiting__help-link mg-awaiting__help-link--ghost" href="{{ route('points.awaiting_candidates') }}">
                <i class="fa fa-list"></i> {{ trans('file.Awaiting Candidate') }}
            </a>
        </div>

        @if(!empty($grading_disabled))
            <div class="alert alert-warning">{{ trans('file.Grading is not enabled yet') }} — existing grades still show below.</div>
        @endif
        @if($count === 0)
            <div class="mg-awaiting__empty">
                <i class="fa fa-star-o"></i>
                <h3>No grades yet</h3>
                <p>When you grade a candidate, they will appear here.</p>
                <a href="{{ route('points.awaiting_candidates') }}" class="btn btn-primary">{{ trans('file.Awaiting Candidate') }}</a>
            </div>
        @else
            <div class="mg-awaiting__grid" id="mg-list-grid">
                @foreach($list as $point)
                    @php
                        $contestant = $point->contestant;
                        $name = optional($contestant)->name ?? '—';
                        $judgeName = optional($point->judge)->name ?? '—';
                        $score = (float) ($point->total ?? 0);
                        $ratio = $maxPoints > 0 ? min(1, max(0, $score / $maxPoints)) : 0;
                        $pct = (int) round($ratio * 100);
                        if ($ratio < 0.5) {
                            $barColor = '#dc2626';
                        } else {
                            $t = ($ratio - 0.5) / 0.5;
                            $hue = (int) round(8 + $t * (142 - 8));
                            $sat = (int) round(82 - $t * 10);
                            $light = (int) round(44 + $t * 4);
                            $barColor = "hsl({$hue}, {$sat}%, {$light}%)";
                        }
                        $img = $contestant ? ImageOptimizer::employeeImageUrl($contestant->image ?? '') : '';
                        $search = strtolower($name . ' ' . $judgeName);
                        $canEdit = in_array('points_edit', $all_permission ?? []);
                        $canDelete = in_array('points_delete', $all_permission ?? []);
                        $mainHref = $canEdit ? route('points.edit', $point) : route('points.show', $point);
                    @endphp
                    <div class="mg-awaiting-card mg-list-card" data-name="{{ $search }}">
                        <a class="mg-list-card__main" href="{{ $mainHref }}">
                            <div class="mg-awaiting-card__photo">
                                @if($contestant && !empty($contestant->image))
                                    <img src="{{ $img }}" alt="{{ $name }}" loading="lazy" width="88" height="88">
                                @else
                                    <span class="mg-awaiting-card__initial">{{ strtoupper(substr($name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="mg-awaiting-card__body">
                                <h3 class="mg-awaiting-card__name">{{ $name }}</h3>
                                <p class="mg-awaiting-card__hint">{{ $judgeName }} · {{ optional($point->created_at)->format('Y-m-d H:i') }}</p>
                                <div class="mg-list-card__bar" aria-hidden="true">
                                    <div class="mg-list-card__bar-fill" style="width: {{ $pct }}%; background: {{ $barColor }};"></div>
                                </div>
                            </div>
                            <span class="mg-list-card__score" style="color: {{ $barColor }};">
                                <strong>{{ round($score, 1) }}</strong><small>/{{ $maxPoints }}</small>
                            </span>
                        </a>
                        <div class="mg-list-card__actions">
                            <a href="{{ route('points.show', $point) }}" class="mg-list-card__btn" title="View"><i class="fa fa-eye"></i></a>
                            @if($canEdit)
                                <a href="{{ route('points.edit', $point) }}" class="mg-list-card__btn" title="Edit"><i class="fa fa-pencil"></i></a>
                            @endif
                            @if($canDelete)
                                <form action="{{ route('points.destroy', $point) }}" method="POST" onsubmit="return confirm('Delete this grade?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="mg-list-card__btn mg-list-card__btn--danger" title="Delete"><i class="fa fa-trash"></i></button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="mg-awaiting__none-match" id="mg-list-none" style="display:none;">No grade matches your search.</p>
        @endif
    </div>
</section>

@include('partials.grading-list-styles')

<script type="text/javascript">
    $("ul#point").siblings('a').attr('aria-expanded','true');
    $("ul#point").addClass("show");
    $("ul#point #point-menu-list").addClass("active");

    (function () {
        var input = document.getElementById('mg-list-search');
        var grid = document.getElementById('mg-list-grid');
        var none = document.getElementById('mg-list-none');
        if (!input || !grid) return;
        input.addEventListener('input', function () {
            var q = (input.value || '').toLowerCase().trim();
            var visible = 0;
            grid.querySelectorAll('.mg-list-card').forEach(function (card) {
                var match = !q || (card.getAttribute('data-name') || '').indexOf(q) !== -1;
                card.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            if (none) none.style.display = visible ? 'none' : 'block';
        });
    })();
</script>
@endsection

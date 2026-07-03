@if(isset($event) && $event && $event->hasActiveCountdown())
<div class="mg-event-countdown ms-countdown {{ $class ?? '' }}" data-deadline="{{ $event->countdownDeadlineAttr() }}">
    <p class="ms-countdown__label">
        <i class="fa-regular fa-clock"></i>
        {{ $event->countdown_label ?: trans('file.Ticket sales close in') }}
    </p>
    <div class="ms-countdown__grid">
        <div class="ms-countdown__cell"><span class="cd-days">00</span><small>{{ trans('file.Days') }}</small></div>
        <div class="ms-countdown__cell"><span class="cd-hours">00</span><small>{{ trans('file.Hrs') }}</small></div>
        <div class="ms-countdown__cell"><span class="cd-mins">00</span><small>{{ trans('file.Min') }}</small></div>
        <div class="ms-countdown__cell"><span class="cd-secs">00</span><small>{{ trans('file.Sec') }}</small></div>
    </div>
</div>
@endif

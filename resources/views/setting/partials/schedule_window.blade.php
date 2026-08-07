@php
    $status = \App\Helpers\VoteSettings::scheduleStatusLabel(
        $lims_general_setting_data ?? null,
        $flagCol,
        $startName,
        $endName
    );
@endphp
<div class="mg-schedule-window mt-2 p-2" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;">
    <small class="d-block text-muted mb-1"><strong>Schedule</strong> (optional, {{ config('app.timezone', 'Africa/Douala') }}) — leave blank for manual checkbox only</small>
    <div class="form-row">
        <div class="col-6">
            <label class="small mb-0" for="{{ $startName }}">Start</label>
            <input type="datetime-local" class="form-control form-control-sm" name="{{ $startName }}" id="{{ $startName }}"
                   value="{{ old($startName, \App\Helpers\VoteSettings::forDatetimeLocal($startValue ?? null)) }}">
        </div>
        <div class="col-6">
            <label class="small mb-0" for="{{ $endName }}">End</label>
            <input type="datetime-local" class="form-control form-control-sm" name="{{ $endName }}" id="{{ $endName }}"
                   value="{{ old($endName, \App\Helpers\VoteSettings::forDatetimeLocal($endValue ?? null)) }}">
        </div>
    </div>
    @if($status)
        <small class="d-block mt-1 text-primary"><i class="dripicons-clock"></i> {{ $status }}</small>
        <small class="d-block text-muted">While scheduled, the flag follows this window every minute.</small>
    @endif
</div>

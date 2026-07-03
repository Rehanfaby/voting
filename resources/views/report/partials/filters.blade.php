@php
    $period = $period ?? request('period', 'month');
    $start_date = $start_date ?? request('start_date');
    $end_date = $end_date ?? request('end_date');
    $departments = $departments ?? collect();
    $department_id = $department_id ?? request('department_id');
    $showRegion = $showRegion ?? true;
    $showPeriod = $showPeriod ?? true;
    $showEvent = $showEvent ?? false;
    $events = $events ?? collect();
    $category_id = $category_id ?? request('category_id');
@endphp
<form method="get" action="{{ $action }}" class="card mb-4">
    <div class="card-body">
        <div class="row align-items-end">
            @if($showPeriod)
            <div class="col-md-2">
                <label>Period</label>
                <select name="period" class="form-control report-period-select">
                    @foreach(\App\Helpers\ReportPeriod::periodOptions() as $key => $label)
                        <option value="{{ $key }}" {{ $period === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 report-custom-dates" style="{{ $period === 'custom' ? '' : 'display:none' }}">
                <label>From</label>
                <input type="date" name="start_date" class="form-control" value="{{ $start_date }}">
            </div>
            <div class="col-md-2 report-custom-dates" style="{{ $period === 'custom' ? '' : 'display:none' }}">
                <label>To</label>
                <input type="date" name="end_date" class="form-control" value="{{ $end_date }}">
            </div>
            @endif
            @if($showRegion)
            <div class="col-md-3">
                <label>Region</label>
                <select name="department_id" class="form-control">
                    <option value="">All regions</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ (string)$department_id === (string)$dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            @if($showEvent)
            <div class="col-md-3">
                <label>Event</label>
                <select name="category_id" class="form-control">
                    <option value="">All events</option>
                    @foreach($events as $ev)
                        <option value="{{ $ev->id }}" {{ (string)$category_id === (string)$ev->id ? 'selected' : '' }}>{{ $ev->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary">Apply</button>
                <button type="submit" name="export" value="csv" class="btn btn-success">Excel (CSV)</button>
                <button type="submit" name="export" value="pdf" class="btn btn-danger">PDF</button>
            </div>
        </div>
    </div>
</form>
<script>
document.querySelectorAll('.report-period-select').forEach(function (sel) {
    sel.addEventListener('change', function () {
        var show = this.value === 'custom';
        this.closest('form').querySelectorAll('.report-custom-dates').forEach(function (el) {
            el.style.display = show ? '' : 'none';
        });
    });
});
</script>

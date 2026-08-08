@extends('layout.main')
@section('content')
<section class="container-fluid">
    @if(session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ session()->get('not_permitted') }}
        </div>
    @endif

    <div class="d-flex flex-wrap align-items-start justify-content-between mb-3" style="gap:12px;">
        <div>
            <h3 class="mb-1">Detailed Contestant Grading Report</h3>
            <p class="text-muted mb-0">
                Summary of votes, ambassadors and judges — with anonymized judge grading details (including Accuracy).
                Judge and ambassador names are hidden (Judge 1, Judge 2… / Ambassador 1…).
            </p>
        </div>
        <a href="{{ route('report.centre') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Reports Centre
        </a>
    </div>

    <form id="mg-detailed-grading-form" method="POST" action="{{ route('report.detailed.grading.generate') }}">
        @csrf
        <input type="hidden" name="export" id="mg-export-field" value="">

        <div class="card mb-3" style="border:0;border-radius:14px;box-shadow:0 8px 24px rgba(15,23,42,.06);">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between" style="gap:10px;">
                <div>
                    <label class="mb-0 mr-3 font-weight-bold">
                        <input type="checkbox" id="mg-select-all"> Select all contestants
                    </label>
                    <span class="text-muted" id="mg-selected-count">0 selected</span>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary mr-2" data-export="">
                        <i class="fa fa-eye"></i> View Report
                    </button>
                    <button type="submit" class="btn btn-danger" data-export="pdf">
                        <i class="fa fa-file-pdf-o"></i> Download PDF
                    </button>
                </div>
            </div>
        </div>

        <div class="card" style="border:0;border-radius:14px;box-shadow:0 8px 24px rgba(15,23,42,.06);">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="mg-contestant-pick-table">
                    <thead>
                        <tr>
                            <th style="width:48px;"></th>
                            <th>Contestant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contestants as $c)
                            <tr>
                                <td>
                                    <input type="checkbox" class="mg-contestant-cb" name="contestant_ids[]" value="{{ $c->id }}">
                                </td>
                                <td>{{ $c->name }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-muted">No active contestants found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</section>

<script>
(function () {
    var form = document.getElementById('mg-detailed-grading-form');
    var selectAll = document.getElementById('mg-select-all');
    var exportField = document.getElementById('mg-export-field');
    var countEl = document.getElementById('mg-selected-count');
    var boxes = function () { return Array.prototype.slice.call(document.querySelectorAll('.mg-contestant-cb')); };

    function updateCount() {
        var n = boxes().filter(function (b) { return b.checked; }).length;
        if (countEl) countEl.textContent = n + ' selected';
        if (selectAll) {
            var all = boxes();
            selectAll.checked = all.length > 0 && all.every(function (b) { return b.checked; });
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            boxes().forEach(function (b) { b.checked = selectAll.checked; });
            updateCount();
        });
    }
    boxes().forEach(function (b) {
        b.addEventListener('change', updateCount);
    });
    updateCount();

    if (form) {
        form.querySelectorAll('button[type="submit"]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (exportField) exportField.value = btn.getAttribute('data-export') || '';
            });
        });
        form.addEventListener('submit', function (e) {
            var any = boxes().some(function (b) { return b.checked; });
            if (!any) {
                e.preventDefault();
                alert('Please select at least one contestant.');
                return;
            }
            // When every box is checked, also send select_all for clarity
            if (selectAll && selectAll.checked) {
                var hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'select_all';
                hidden.value = '1';
                form.appendChild(hidden);
            }
        });
    }

    $("ul#report").siblings('a').attr('aria-expanded','true');
    $("ul#report").addClass("show");
    $("ul#report #detailed-grading-report-menu").addClass("active");
})();
</script>
@endsection

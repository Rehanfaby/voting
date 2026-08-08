@extends('layout.main') @section('content')
@php
    $votePct = (int) ($vote_percentage ?? 10);
    $judgePct = (int) ($judges_percentage ?? 60);
    $elimN = (int) ($number_of_elimination ?? 0);
    $count = $contestants->count();
@endphp
<section>
    <style>
        .mg-list-hero {
            border-radius: 16px;
            padding: 22px 24px;
            margin-bottom: 16px;
            color: #fff;
            background: linear-gradient(135deg, #047857 0%, #059669 45%, #10b981 100%);
            box-shadow: 0 10px 28px rgba(4, 120, 87, 0.25);
        }
        .mg-list-hero h3 { margin: 0 0 6px; font-weight: 800; letter-spacing: .02em; }
        .mg-list-hero p { margin: 0; opacity: .95; }
        .mg-list-hero .mg-chip {
            display: inline-block; margin-top: 10px; margin-right: 8px;
            background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.35);
            border-radius: 999px; padding: 4px 12px; font-size: 12px; font-weight: 700;
        }
        #employee-table thead th {
            background: #047857 !important; color: #fff !important; border: none !important;
        }
        #employee-table tbody tr { background: #ecfdf5 !important; }
        #employee-table tbody tr:nth-child(even) { background: #f0fdf4 !important; }
        #employee-table tbody td.mg-total { color: #047857; font-weight: 800; font-size: 1.05em; }
        #employee-table tbody td.mg-pos .badge { background: #059669; }
        .mg-status-pill {
            display: inline-block; background: #059669; color: #fff; border-radius: 999px;
            padding: 3px 10px; font-size: 11px; font-weight: 700;
        }
    </style>

    <div class="container-fluid">
        <div class="mg-list-hero">
            <h3><i class="fa fa-trophy"></i> {{ trans('file.Qualified Contestants') }}</h3>
            <p>Top contestants above the elimination cut-off · Number of Elimination = {{ $elimN }}</p>
            <span class="mg-chip">{{ $count }} Qualified</span>
            <span class="mg-chip">Votes /{{ $votePct }}</span>
            <span class="mg-chip">Judges /{{ $judgePct }}</span>
        </div>
    </div>

    <div class="table-responsive">
        <table id="employee-table" class="table">
            <thead>
            <tr>
                <th class="not-exported"></th>
                <th class="not-exported">{{trans('file.Image')}}</th>
                <th>{{trans('file.name')}}</th>
                <th>{{trans('file.Votes')}} (/{{ $votePct }})</th>
                <th>{{trans('file.Points')}} (/{{ $judgePct }})</th>
                <th>{{trans('file.Ambassador Points')}}</th>
                <th>{{trans('file.Total')}}</th>
                <th>{{trans('file.Position')}}</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach($contestants as $key=>$employee)
                @php $contestant = \App\Employee::find($employee->id); @endphp
                <tr>
                    <td>{{$key}}</td>
                    @if($contestant && $contestant->image)
                        <td><img src="{{url('public/images/employee',$contestant->image)}}" height="80" width="80"></td>
                    @else
                        <td>No Image</td>
                    @endif
                    <td data-export="{{ $contestant ? $contestant->name : $employee->name }}">{{ $contestant ? $contestant->name : $employee->name }}</td>
                    <td data-export="{{ number_format((float) ($employee->score_votes ?? 0), 2) }} ({{ (int) $employee->total_votes }} raw)">
                        {{ number_format((float) ($employee->score_votes ?? 0), 2) }}
                        <div><small class="text-muted">{{ (int) $employee->total_votes }} raw votes</small></div>
                    </td>
                    <td data-export="{{ number_format((float) ($employee->score_points ?? 0), 2) }}">
                        {{ number_format((float) ($employee->score_points ?? 0), 2) }}
                    </td>
                    <td>{{ number_format((float) $employee->total_ambassador_points, 2) }}</td>
                    <td class="mg-total">{{ number_format((float) $employee->final_score, 2) }}</td>
                    <td class="mg-pos"><span class="badge">{{ $key + 1 }}</span></td>
                    <td><span class="mg-status-pill">Qualified</span></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</section>

@include('partials.report_pdf_theme', [
    'pdfTheme' => 'qualified',
    'pdfTitle' => 'Qualified Contestants',
    'pdfSubtitle' => $count . ' qualified · elimination cut-off bottom ' . $elimN,
])

<script type="text/javascript">
    $("ul#grading-setting").siblings('a').attr('aria-expanded','true');
    $("ul#grading-setting").addClass("show");
    $("ul#grading-setting #grading-qualified").addClass("active");

    function mgExportBody(data, row, column, node) {
        var exportText = $(node).attr('data-export');
        if (exportText) return exportText;
        return $('<div>').html(data == null ? '' : data).text().replace(/\s+/g, ' ').trim();
    }

    $('#employee-table').DataTable({
        order: [],
        pageLength: 50,
        language: {
            lengthMenu: '_MENU_ {{trans("file.records per page")}}',
            info: '<small>{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)</small>',
            search: '{{trans("file.Search")}}',
            paginate: {
                previous: '<i class="dripicons-chevron-left"></i>',
                next: '<i class="dripicons-chevron-right"></i>'
            }
        },
        columnDefs: [
            { orderable: false, targets: [0, 1, 7, 8] },
            {
                render: function (data, type) {
                    if (type === 'display') {
                        return '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                    }
                    return data;
                },
                checkboxes: {
                    selectRow: true,
                    selectAllRender: '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                },
                targets: [0]
            }
        ],
        select: { style: 'multi', selector: 'td:first-child' },
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"row"lfB>rtip',
        buttons: [
            {
                extend: 'pdf',
                text: '<i title="export to pdf" class="fa fa-file-pdf-o"></i>',
                title: '',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible',
                    stripHtml: true,
                    format: { body: mgExportBody }
                },
                customize: window.mgCustomizeReportPdf
            },
            {
                extend: 'csv',
                text: '<i title="export to csv" class="fa fa-file-text-o"></i>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible',
                    stripHtml: true,
                    format: { body: mgExportBody }
                }
            },
            {
                extend: 'print',
                text: '<i title="print" class="fa fa-print"></i>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible',
                    stripHtml: true,
                    format: { body: mgExportBody }
                }
            },
            {
                extend: 'colvis',
                text: '<i title="column visibility" class="fa fa-eye"></i>',
                columns: ':gt(0)'
            }
        ]
    });
</script>
@endsection

@extends('layout.main') @section('content')
@php
    $votePct = (int) ($vote_percentage ?? 10);
    $judgePct = (int) ($judges_percentage ?? 60);
    $elimN = (int) ($number_of_elimination ?? 0);
    $count = $contestants->count();
    $all_permission = $all_permission ?? [];
@endphp
<section>
    @if($errors->has('image'))
        <div class="alert alert-danger alert-dismissible text-center">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('image') }}</div>
    @endif
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div>
    @endif
    @if(session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif

    <style>
        .mg-list-hero {
            border-radius: 16px;
            padding: 22px 24px;
            margin-bottom: 16px;
            color: #fff;
            background: linear-gradient(135deg, #991b1b 0%, #b91c1c 45%, #ef4444 100%);
            box-shadow: 0 10px 28px rgba(185, 28, 28, 0.28);
            position: relative;
        }
        .mg-list-hero h3 { margin: 0 0 6px; font-weight: 800; letter-spacing: .02em; }
        .mg-list-hero p { margin: 0; opacity: .95; }
        .mg-list-hero .mg-chip {
            display: inline-block; margin-top: 10px; margin-right: 8px;
            background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.35);
            border-radius: 999px; padding: 4px 12px; font-size: 12px; font-weight: 700;
        }
        .mg-list-hero .mg-hero-action {
            position: absolute; right: 20px; top: 22px;
        }
        #employee-table thead th {
            background: #b91c1c !important; color: #fff !important; border: none !important;
        }
        #employee-table tbody tr { background: #fef2f2 !important; }
        #employee-table tbody tr:nth-child(even) { background: #fff1f2 !important; }
        #employee-table tbody td.mg-total { color: #b91c1c; font-weight: 800; font-size: 1.05em; }
        #employee-table tbody td.mg-pos .badge { background: #dc2626; }
        .mg-status-pill {
            display: inline-block; background: #dc2626; color: #fff; border-radius: 999px;
            padding: 3px 10px; font-size: 11px; font-weight: 700;
        }
        @media (max-width: 767px) {
            .mg-list-hero .mg-hero-action { position: static; margin-top: 12px; display: inline-block; }
        }
    </style>

    <div class="container-fluid">
        <div class="mg-list-hero">
            <div class="mg-hero-action">
                @if(in_array('employees-delete', $all_permission ?? []))
                <button type="button" id="mg-elim-delete-selected" class="btn btn-dark btn-sm font-weight-bold mr-2">
                    <i class="dripicons-trash"></i> Delete Selected
                </button>
                @endif
                <a class="btn btn-light btn-sm font-weight-bold" href="{{ route('eliminate.contestants') }}">{{ trans('file.Generate Elimination List') }}</a>
            </div>
            <h3><i class="fa fa-times-circle"></i> {{ trans('file.Eliminated Contestants') }}</h3>
            <p>Bottom {{ $elimN }} by current ranking · Elimination zone</p>
            <span class="mg-chip">{{ $count }} Eliminated</span>
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
                <th class="not-exported">Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($contestants as $key=>$employee)
                @php $contestant = \App\Employee::find($employee->id); @endphp
                <tr data-id="{{ $employee->id }}">
                    <td>{{ $employee->id }}</td>
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
                    <td><span class="mg-status-pill">Elimination</span></td>
                    <td>
                        @if(in_array("employees-delete", $all_permission))
                            {{ Form::open(['route' => ['musician.destroy', $employee->id], 'method' => 'DELETE'] ) }}
                                <button type="submit" class="btn btn-link text-danger" onclick="return confirm('Delete this contestant from the system? They will be removed from contestants and rankings.')"><i class="dripicons-trash"></i> Delete</button>
                            {{ Form::close() }}
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</section>

@include('partials.report_pdf_theme', [
    'pdfTheme' => 'eliminated',
    'pdfTitle' => 'Elimination List',
    'pdfSubtitle' => $count . ' contestants in elimination zone · bottom ' . $elimN,
])

<script type="text/javascript">
    $("ul#grading-setting").siblings('a').attr('aria-expanded','true');
    $("ul#grading-setting").addClass("show");
    $("ul#grading-setting #grading-eliminated").addClass("active");

    function mgExportBody(data, row, column, node) {
        var exportText = $(node).attr('data-export');
        if (exportText) return exportText;
        return $('<div>').html(data == null ? '' : data).text().replace(/\s+/g, ' ').trim();
    }

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    function deleteSelectedEliminated() {
        var ids = typeof collectSelectedTableIds === 'function'
            ? collectSelectedTableIds('#employee-table')
            : [];
        // Extra safety: only rows whose checkbox is actually checked
        if (!ids.length) {
            var seen = {};
            $('#employee-table').DataTable().$('tr').each(function () {
                var $row = $(this);
                if ($row.find('td:first-child input[type="checkbox"]').prop('checked')) {
                    var id = $row.attr('data-id');
                    if (id && !seen[id]) {
                        seen[id] = true;
                        ids.push(id);
                    }
                }
            });
        }
        if (!ids.length) {
            alert('No contestant is selected!');
            return;
        }
        if (!confirm('Delete ' + ids.length + ' selected contestant(s) from the system?\n\nThey will be removed from Contestants, rankings, and this elimination list.')) {
            return;
        }
        $.ajax({
            type: 'POST',
            url: '{{ url("musician/deletebyselection") }}',
            data: { employeeIdArray: ids },
            success: function (data) {
                alert(data);
                location.reload();
            },
            error: function () {
                alert('Delete failed. No contestants were changed.');
            }
        });
    }

    $('#mg-elim-delete-selected').on('click', deleteSelectedEliminated);

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
            { orderable: false, targets: [0, 1, 7, 8, 9] },
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
                extend: 'pdfHtml5',
                text: '<i title="export to pdf" class="fa fa-file-pdf-o"></i>',
                title: '',
                filename: 'elimination-list',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    // Always include identity + scores + status (ignore column visibility)
                    columns: [2, 3, 4, 5, 6, 7, 8],
                    rows: ':visible',
                    stripHtml: true,
                    format: { body: mgExportBody }
                },
                customize: function (doc) {
                    if (typeof window.mgCustomizeReportPdf === 'function') {
                        window.mgCustomizeReportPdf(doc);
                    }
                }
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
            @if(in_array('employees-delete', $all_permission ?? []))
            {
                text: '<i title="delete selected" class="dripicons-trash"></i>',
                className: 'buttons-delete',
                action: function () {
                    deleteSelectedEliminated();
                }
            },
            @endif
            {
                extend: 'colvis',
                text: '<i title="column visibility" class="fa fa-eye"></i>',
                columns: ':gt(0)'
            }
        ]
    });
</script>
@endsection

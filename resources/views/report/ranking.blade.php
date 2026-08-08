@extends('layout.main') @section('content')
@php
    $elimN = max(0, (int) ($number_of_elimination ?? 0));
    $totalContestants = $contestants->count();
    $elimN = min($elimN, $totalContestants);
    $qualifiedCutoff = $totalContestants - $elimN;
@endphp
    <section>
        <style>
            #employee-table tbody tr.mg-rank-qualified { background: #ecfdf5 !important; }
            #employee-table tbody tr.mg-rank-qualified td:nth-child(3) { color: #047857; font-weight: 700; }
            #employee-table tbody tr.mg-rank-eliminated { background: #fef2f2 !important; }
            #employee-table tbody tr.mg-rank-cutoff td {
                border-top: 3px solid #dc2626 !important;
            }
            .mg-rank-legend { display: flex; flex-wrap: wrap; gap: 12px; justify-content: center; margin: 0 0 12px; font-size: 13px; }
            .mg-rank-legend span { display: inline-flex; align-items: center; gap: 6px; }
            .mg-rank-swatch { width: 14px; height: 14px; border-radius: 3px; display: inline-block; }
            .mg-rank-swatch--q { background: #34d399; }
            .mg-rank-swatch--e { background: #f87171; }
            .mg-rank-line { width: 28px; height: 3px; background: #dc2626; display: inline-block; }
        </style>

        <div class="container-fluid">
            <div class="card">
                <div class="card-header mt-2">
                    <h3 class="text-center">Contestant Ranking Report</h3>
                    <p class="text-center text-muted mb-2">
                        Top {{ max(0, $qualifiedCutoff) }} qualified
                        @if($elimN > 0)
                            · bottom {{ $elimN }} in elimination zone
                        @endif
                        (from Number of Elimination)
                    </p>
                    <div class="mg-rank-legend">
                        <span><i class="mg-rank-swatch mg-rank-swatch--q"></i> Qualified (green)</span>
                        @if($elimN > 0)
                            <span><i class="mg-rank-line"></i> Elimination cut-off</span>
                            <span><i class="mg-rank-swatch mg-rank-swatch--e"></i> Elimination zone (red)</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="employee-table" class="table">
                <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th class="not-exported">{{trans('file.Image')}}</th>
                    <th>{{trans('file.name')}}</th>
                    <th>{{trans('file.Votes')}} (/{{ (int) ($vote_percentage ?? 10) }})</th>
                    <th>{{trans('file.Points')}} (/{{ (int) ($judges_percentage ?? 60) }})</th>
                    <th>{{trans('file.Ambassador Points')}}</th>
                    <th>{{trans('file.Total')}}</th>
                    <th>{{trans('file.Position')}}</th>
                    <th class="not-exported">Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($contestants as $key=>$employee)
                    @php
                        $contestant = \App\Employee::find($employee->id);
                        $isQualified = $key < $qualifiedCutoff;
                        $isCutoff = $elimN > 0 && $key === $qualifiedCutoff;
                        $rowClass = ($isQualified ? 'mg-rank-qualified' : 'mg-rank-eliminated') . ($isCutoff ? ' mg-rank-cutoff' : '');
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td>{{$key}}</td>
                        @if($contestant && $contestant->image)
                            <td> <img src="{{url('public/images/employee',$contestant->image)}}" height="80" width="80"></td>
                        @else
                            <td>No Image</td>
                        @endif
                        <td data-export="{{ $contestant ? $contestant->name : $employee->name }}">
                            <a href="{{ route('report.contestant.grading', $employee->id) }}" class="font-weight-bold" style="color:#0a2350;text-decoration:underline;">
                                {{ $contestant ? $contestant->name : $employee->name }}
                            </a>
                            <div class="d-print-none"><small class="text-muted">Tap for judge &amp; ambassador scores</small></div>
                        </td>
                        <td data-export="{{ number_format((float) ($employee->score_votes ?? 0), 2) }} ({{ (int) $employee->total_votes }} raw)">
                            {{ number_format((float) ($employee->score_votes ?? 0), 2) }}
                            <div class="d-print-none"><small class="text-muted">{{ (int) $employee->total_votes }} raw votes</small></div>
                        </td>
                        <td data-export="{{ number_format((float) ($employee->score_points ?? 0), 2) }}">
                            {{ number_format((float) ($employee->score_points ?? 0), 2) }}
                        </td>
                        <td>{{ number_format((float) $employee->total_ambassador_points, 2) }}</td>
                        <td class="{{ $isQualified ? 'text-success font-weight-bold' : 'text text-danger' }}">{{ round($employee->final_score, 2) }}</td>
                        <td class="badge badge-info">{{ $key + 1}}</td>
                        <td>
                            @if($isQualified)
                                <span class="badge badge-success">Qualified</span>
                            @else
                                <span class="badge badge-danger">Elimination</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </section>

@include('partials.report_pdf_theme', [
    'pdfTheme' => 'ranking',
    'pdfTitle' => 'Contestant Ranking Report',
    'pdfSubtitle' => 'Green = Qualified · Red = Elimination zone',
])

    <script type="text/javascript">

        $("ul#grading-setting").siblings('a').attr('aria-expanded','true');
        $("ul#grading-setting").addClass("show");
        $("ul#grading-setting #contestant-ranking").addClass("active");

        $(".daterangepicker-field").daterangepicker({
            callback: function(startDate, endDate, period){
                var start_date = startDate.format('YYYY-MM-DD');
                var end_date = endDate.format('YYYY-MM-DD');
                var title = start_date + ' To ' + end_date;
                $(this).val(title);
                $('input[name="start_date"]').val(start_date);
                $('input[name="end_date"]').val(end_date);
            }
        });

        $(document).ready(function($) {
            $('.clickable-row td:not(:last-child)').click(function () {
                window.location = $(this).closest('tr').data("href");
            });
        });


        var rankingTable = $('#employee-table').DataTable( {
            "order": [],
            'pageLength': 50,
            'language': {
                'lengthMenu': '_MENU_ {{trans("file.records per page")}}',
                "info":      '<small>{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)</small>',
                "search":  '{{trans("file.Search")}}',
                'paginate': {
                    'previous': '<i class="dripicons-chevron-left"></i>',
                    'next': '<i class="dripicons-chevron-right"></i>'
                }
            },
            'columnDefs': [
                {
                    "orderable": false,
                    'targets': [0, 1, 6, 8]
                },
                {
                    'render': function(data, type, row, meta){
                        if(type === 'display'){
                            data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                        }

                        return data;
                    },
                    'checkboxes': {
                        'selectRow': true,
                        'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                    },
                    'targets': [0]
                }
            ],
            'select': { style: 'multi',  selector: 'td:first-child'},
            'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
            dom: '<"row"lfB>rtip',
            buttons: [
                {
                    extend: 'pdf',
                    text: '<i title="export to pdf" class="fa fa-file-pdf-o"></i>',
                    title: '',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: function (idx, data, node) {
                            var selected = rankingTable.rows({ selected: true }).count();
                            if (selected > 0) {
                                return $(node).hasClass('selected');
                            }
                            return true;
                        },
                        stripHtml: true,
                        format: {
                            body: function (data, row, column, node) {
                                var exportText = $(node).attr('data-export');
                                if (exportText) { return exportText; }
                                var text = $('<div>').html(data == null ? '' : data).text();
                                return text.replace(/\s+/g, ' ').trim();
                            }
                        }
                    },
                    customize: window.mgCustomizeReportPdf,
                },
                {
                    extend: 'csv',
                    text: '<i title="export to csv" class="fa fa-file-text-o"></i>',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: function (idx, data, node) {
                            var selected = rankingTable.rows({ selected: true }).count();
                            if (selected > 0) {
                                return $(node).hasClass('selected');
                            }
                            return true;
                        },
                        stripHtml: true,
                        format: {
                            body: function (data, row, column, node) {
                                var exportText = $(node).attr('data-export');
                                if (exportText) { return exportText; }
                                var text = $('<div>').html(data == null ? '' : data).text();
                                return text.replace(/\s+/g, ' ').trim();
                            }
                        }
                    },
                },
                {
                    extend: 'print',
                    text: '<i title="print" class="fa fa-print"></i>',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: function (idx, data, node) {
                            var selected = rankingTable.rows({ selected: true }).count();
                            if (selected > 0) {
                                return $(node).hasClass('selected');
                            }
                            return true;
                        },
                        stripHtml: true,
                        format: {
                            body: function (data, row, column, node) {
                                var exportText = $(node).attr('data-export');
                                if (exportText) { return exportText; }
                                var text = $('<div>').html(data == null ? '' : data).text();
                                return text.replace(/\s+/g, ' ').trim();
                            }
                        }
                    },
                },
                {
                    extend: 'colvis',
                    text: '<i title="column visibility" class="fa fa-eye"></i>',
                    columns: ':gt(0)'
                },
            ],
        } );
    </script>
@endsection

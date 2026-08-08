@extends('layout.main') @section('content')
    <section>

        <div class="container-fluid">
            <div class="card">
                <div class="card-header mt-2">
                    <h3 class="text-center">{{ trans('file.Qualified Contestants') }}</h3>
                    <p class="text-center text-muted mb-2">
                        Top contestants above the bottom {{ (int) ($number_of_elimination ?? 0) }}
                        (Number of Elimination)
                    </p>
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
                </tr>
                </thead>
                <tbody>
                @foreach($contestants as $key=>$employee)
                    @php $contestant = \App\Employee::find($employee->id); @endphp
                    <tr>
                        <td>{{$key}}</td>
                        @if($contestant->image)
                            <td> <img src="{{url('public/images/employee',$contestant->image)}}" height="80" width="80"></td>
                        @else
                            <td>No Image</td>
                        @endif
                        <td>{{ $contestant->name }}</td>
                        <td>
                            {{ number_format((float) ($employee->score_votes ?? 0), 2) }} / {{ (int) ($vote_percentage ?? 10) }}
                            <div><small class="text-muted">{{ (int) $employee->total_votes }} raw votes</small></div>
                        </td>
                        <td>{{ number_format((float) ($employee->score_points ?? 0), 2) }} / {{ (int) ($judges_percentage ?? 60) }}</td>
                        <td>{{ number_format((float) $employee->total_ambassador_points, 2) }}</td>
                        <td class="text text-danger">{{ round($employee->final_score, 2) }}</td>
                        <td class="badge badge-info">{{ $key + 1}}</td>
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
                </tr>
                </tfoot>
            </table>
        </div>
    </section>

    <script type="text/javascript">

        $("ul#grading-setting").siblings('a').attr('aria-expanded','true');
        $("ul#grading-setting").addClass("show");
        $("ul#grading-setting #grading-qualified").addClass("active");

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


        $('#employee-table').DataTable( {
            "order": [],
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
                    'targets': [0, 1, 6]
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
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: ':visible',
                        stripHtml: true,
                        format: {
                            body: function (data) {
                                return $('<div>').html(data == null ? '' : data).text().replace(/\s+/g, ' ').trim();
                            }
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
                        format: {
                            body: function (data) {
                                return $('<div>').html(data == null ? '' : data).text().replace(/\s+/g, ' ').trim();
                            }
                        }
                    },
                },
                {
                    extend: 'print',
                    text: '<i title="print" class="fa fa-print"></i>',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: ':visible',
                        stripHtml: true,
                        format: {
                            body: function (data) {
                                return $('<div>').html(data == null ? '' : data).text().replace(/\s+/g, ' ').trim();
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

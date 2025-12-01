@extends('layout.main') @section('content')
    <section>
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
        <div class="container-fluid">
            <div class="card">
                <div class="card-header mt-2">
                    <h3 class="text-center">{{ trans('file.Eliminated Contestants') }}</h3>
                    <a class="pull-right btn btn-primary" href="{{ route('eliminate.contestants') }}">{{ trans('file.Generate Elimination List') }}</a>
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
                    <th>{{trans('file.Votes')}}</th>
                    <th>{{trans('file.Points')}}</th>
                    <th>{{trans('file.Ambassador Points')}}</th>
                    <th>{{trans('file.Total')}}</th>
                    <th>{{trans('file.Position')}}</th>
                    <th class="not-exported">{{trans('file.Eliminate')}}</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $total_votes = 0;
                @endphp
                @foreach($contestants as $key=>$employee)
                    @php $contestant = \App\Employee::find($employee->id); @endphp
                    <tr  data-id="{{ $employee->id }}">
                        <td>{{$key}}</td>
                        @if($contestant->image)
                            <td> <img src="{{url('public/images/employee',$contestant->image)}}" height="80" width="80"></td>
                        @else
                            <td>No Image</td>
                        @endif
                        <td>{{ $contestant->name }}</td>
                        <td>{{ $employee->total_votes }}</td>
                        <td>{{ $employee->total_points }}</td>
                        <td>{{ $employee->total_ambassador_points }}</td>
                        <td class="text text-danger">{{ round($employee->final_score, 2) }}</td>
                        <td class="badge badge-info">{{ $key + 1}}</td>
                        <td>
                            @if(in_array("employees-delete", $all_permission))
                                {{ Form::open(['route' => ['musician.destroy', $employee->id], 'method' => 'DELETE'] ) }}
                                    <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="dripicons-trash"></i> {{trans('file.Eliminate')}}</button>
                                {{ Form::close() }}
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

    <script type="text/javascript">

        $("ul#grading-setting").siblings('a').attr('aria-expanded','true');
        $("ul#grading-setting").addClass("show");
        $("ul#grading-setting #grading-eliminated").addClass("active");

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

        var employee_id = [];
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
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
                        stripHtml: false
                    },
                    customize: function(doc) {
                        for (var i = 1; i < doc.content[1].table.body.length; i++) {
                            if (doc.content[1].table.body[i][0].text.indexOf('<img src=') !== -1) {
                                var imagehtml = doc.content[1].table.body[i][0].text;
                                var regex = /<img.*?src=['"](.*?)['"]/;
                                var src = regex.exec(imagehtml)[1];
                                var tempImage = new Image();
                                tempImage.src = src;
                                var canvas = document.createElement("canvas");
                                canvas.width = tempImage.width;
                                canvas.height = tempImage.height;
                                var ctx = canvas.getContext("2d");
                                ctx.drawImage(tempImage, 0, 0);
                                var imagedata = canvas.toDataURL("image/png");
                                delete doc.content[1].table.body[i][0].text;
                                doc.content[1].table.body[i][0].image = imagedata;
                                doc.content[1].table.body[i][0].fit = [30, 30];
                            }
                        }
                    },
                },
                {
                    extend: 'csv',
                    text: '<i title="export to csv" class="fa fa-file-text-o"></i>',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: ':visible',
                        format: {
                            body: function ( data, row, column, node ) {
                                if (column === 0 && (data.indexOf('<img src=') != -1)) {
                                    var regex = /<img.*?src=['"](.*?)['"]/;
                                    data = regex.exec(data)[1];
                                }
                                return data;
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
                        stripHtml: false
                    },
                },
                {
                    text: '<i title="delete" class="dripicons-cross"></i>',
                    className: 'buttons-delete',
                    action: function ( e, dt, node, config ) {
                        employee_id.length = 0;
                        $(':checkbox:checked').each(function (i) {
                            if (i) {
                                employee_id[i - 1] = $(this).closest('tr').data('id');
                            }
                        });
                        if (employee_id.length && confirm("Are you sure want to delete?")) {
                            $.ajax({
                                type: 'POST',
                                url: '/musician/deletebyselection',
                                data: {
                                    ids: employee_id
                                },
                                success: function (data) {
                                    alert(data);
                                    location.reload();
                                }
                            });
                            dt.rows({page: 'current', selected: true}).remove().draw(false);
                        } else if (!employee_id.length)
                            alert('No employee is selected!');
                    }
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

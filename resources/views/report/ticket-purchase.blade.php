@extends('layout.main') @section('content')
    <section>

        <div class="container-fluid">
            <div class="card">
                <div class="card-header mt-2">
                    <h3 class="text-center">Ticket Purchase Report</h3>
                </div>
                {!! Form::open(['route' => 'report.ticket.purchase', 'method' => 'get']) !!}
                <div class="row mb-3">
                    <div class="col-md-4 offset-md-2 mt-3">
                        <div class="form-group row">
                            <label class="d-tc mt-2"><strong>{{trans('file.Choose Your Date')}}</strong> &nbsp;</label>
                            <div class="d-tc">
                                <div class="input-group">
                                    <input type="text" class="daterangepicker-field form-control" value="{{$start_date}} To {{$end_date}}" required />
                                    <input type="hidden" name="start_date" value="{{$start_date}}" />
                                    <input type="hidden" name="end_date" value="{{$end_date}}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <div class="row">
                            <label class="d-tc mt-2"><strong>{{trans('file.Is Used')}}</strong> &nbsp;</label>
                                <select name="status">
                                    <option value="2" {{ $status == 2 ? 'selected' : '' }}>All</option>
                                    <option value="0" {{ $status == 0 ? 'selected' : '' }}>Unused</option>
                                    <option value="1" {{ $status == 1 ? 'selected' : '' }}>Used</option>
                                </select>
                        </div>
                    </div>
                    <div class="col-md-2 mt-3">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">{{trans('file.submit')}}</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="table-responsive">
            <table id="employee-table" class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Qty</th>
                    <th>Token</th>
                    <th>Used?</th>
                    <th>Purchase Date</th>
                    <th>Ticket Name</th>
                    <th>Seat Numbers</th>
                    <th>Total Amount</th>
                </tr>
                </thead>
                <tbody>
                @php $total_qty = 0; $total_amount = 0; @endphp
                @foreach($tickets as $index => $ticket)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $ticket->name }}</td>
                        <td>{{ $ticket->phone }}</td>
                        <td>{{ $ticket->qty }}</td>
                        <td>{{ $ticket->token }}</td>
                        <td>
                            @if($ticket->is_used)
                                <span class="badge bg-success">Used</span>
                            @else
                                <span class="badge bg-warning text-dark">Not Used</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('Y-m-d H:i') }}</td>
                        <td>{{ optional($ticket->product)->name }}</td>
                        <td>{{ $ticket->seat_numbers }}</td>
                        <td>{{ number_format($ticket->total_amount, 2) }}</td>
                    </tr>
                    @php $total_qty += $ticket->qty; $total_amount += $ticket->total_amount; @endphp
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th>{{trans('file.Total Quantity')}}</th>
                    <th>{{ $total_qty }}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>{{trans('file.Total')}}</th>
                    <th>{{ number_format($total_amount, 2) }}</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </section>

    <script type="text/javascript">

        $("ul#report").siblings('a').attr('aria-expanded','true');
        $("ul#report").addClass("show");
        $("ul#report #ticket-report-menu").addClass("active");

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
                    'targets': [0, 7]
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
                        rows: ':visible'
                    },
                    footer:true
                },
                {
                    extend: 'csv',
                    text: '<i title="export to csv" class="fa fa-file-text-o"></i>',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: ':visible'
                    },
                    footer:true
                },
                {
                    extend: 'print',
                    text: '<i title="print" class="fa fa-print"></i>',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: ':visible'
                    },
                    footer:true
                },
                {
                    text: '<i title="delete" class="dripicons-cross"></i>',
                    className: 'buttons-delete',
                    action: function ( e, dt, node, config ) {
                        if(user_verified == '1') {
                            expense_id.length = 0;
                            $(':checkbox:checked').each(function(i){
                                if(i){
                                    expense_id[i-1] = $(this).closest('tr').data('id');
                                }
                            });
                            if(expense_id.length && confirm("Are you sure want to delete?")) {
                                $.ajax({
                                    type:'POST',
                                    url:'votes/deletebyselection',
                                    data:{
                                        expenseIdArray: expense_id
                                    },
                                    success:function(data){
                                        alert(data);
                                        location.reload();
                                    }
                                });
                                dt.rows({ page: 'current', selected: true }).remove().draw(false);
                            }
                            else if(!expense_id.length)
                                alert('No expense is selected!');
                        }
                        else
                            alert('This feature is disable for demo!');
                    }
                },
                {
                    extend: 'colvis',
                    text: '<i title="column visibility" class="fa fa-eye"></i>',
                    columns: ':gt(0)'
                },
            ]
        } );
    </script>
@endsection

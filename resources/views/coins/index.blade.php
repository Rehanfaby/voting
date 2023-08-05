@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header mt-2">
                <h3 class="text-center">Coins List</h3>
            </div>
        </div>
        @if(in_array("votes-add", $all_permission))
            <button class="btn btn-info" data-toggle="modal" data-target="#coin-modal"><i class="dripicons-plus"></i> Add Coin</button>
        @endif
    </div>
    <div class="table-responsive">
        <table id="expense-table" class="table">
            <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th>{{trans('file.Date')}}</th>
                    <th>Phone</th>
                    <th>Coins</th>
                    <th>Code</th>
                    <th>Status</th>
                    <th class="not-exported">{{trans('file.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coins as $key=>$coin)
                <tr data-id="{{$coin->id}}">
                    <td>{{$key}}</td>
                    <td>{{date('D d-M-Y', strtotime($coin->created_at->toDateString())) . ' '. $coin->created_at->toTimeString() }}</td>
                    <td>{{ $coin->phone }}</td>
                    <td>{{ $coin->coin }}</td>
                    <td>{{ $coin->code }}</td>
                    @if($coin->is_active == 0)
                        <td><span class="badge badge-warning">In Active</span></td>
                    @else
                        <td><span class="badge badge-success">Active</span></td>
                    @endif
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans('file.action')}}
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                @if(in_array("coins-edit", $all_permission))
                                <li><button type="button" data-id="{{$coin->id}}" class="open-Editexpense_categoryDialog btn btn-link" data-toggle="modal" data-target="#editModal"><i class="dripicons-document-edit"></i> {{trans('file.edit')}}</button></li>
                                @endif
                                @if(in_array("coins-delete", $all_permission))
                                <li class="divider"></li>
                                {{ Form::open(['route' => ['coins.destroy', $coin->id], 'method' => 'DELETE'] ) }}
                                <li>
                                    <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="dripicons-trash"></i> {{trans('file.delete')}}</button>
                                </li>
                                {{ Form::close() }}
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<div id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">Update Coin</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body">
              <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                {!! Form::open(['route' => ['coins.update', 1], 'method' => 'put']) !!}
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="number" name="phone" class="form-control" value="">
                </div>
                  <div class="form-group">
                      <label>Coins</label>
                      <input type="number" name="coin" class="form-control" value="">
                      <input type="hidden" name="id">
                  </div>
                <div class="form-group">
                    <label>Code</label>
                    <input type="number" name="code" class="form-control" value="">
                </div>
                    <div class="form-group">
                        <input class="mt-2" type="checkbox" name="is_active">
                        <label class="mt-2"><strong>Is Active</strong></label>
                    </div>
                  <div class="form-group">
                      <button type="submit" class="btn btn-primary">{{trans('file.submit')}}</button>
                  </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $("ul#coin").siblings('a').attr('aria-expanded','true');
    $("ul#coin").addClass("show");
    $("ul#coin #coin-menu").addClass("active");

    var expense_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;
    var all_permission = <?php echo json_encode($all_permission) ?>;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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

    $(document).ready(function() {
        $(document).on('click', 'button.open-Editexpense_categoryDialog', function() {
            var url = "coins/";
            var id = $(this).data('id').toString();
            url = url.concat(id).concat("/edit");
            $.get(url, function(data) {
                console.log(data);
                $('#editModal #reference').text(data['reference']);
                $("#editModal input[name='id']").val(data['id']);
                $("#editModal input[name='coin']").val(data['coin']);
                $("#editModal input[name='code']").val(data['code']);
                $("#editModal input[name='phone']").val(data['phone']);
                $("#editModal input[name='status']").val(data['status']);
                if(data['is_active'] == 1) {
                    $("#editModal input[name='is_active']").prop('checked', true);
                } else {
                    $("#editModal input[name='is_active']").prop('checked', false);
                }
            });
        });
    });

function confirmDelete() {
    if (confirm("Are you sure want to delete?")) {
        return true;
    }
    return false;
}

    $('#expense-table').DataTable( {
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
                'targets': [0, 6]
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
                action: function(e, dt, button, config) {
                    datatable_sum(dt, true);
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                    datatable_sum(dt, false);
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
                action: function(e, dt, button, config) {
                    datatable_sum(dt, true);
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                    datatable_sum(dt, false);
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
                action: function(e, dt, button, config) {
                    datatable_sum(dt, true);
                    $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                    datatable_sum(dt, false);
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
                                url:'coins/deletebyselection',
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
        ],
        drawCallback: function () {
            var api = this.api();
            datatable_sum(api, false);
        }
    } );

    function datatable_sum(dt_selector, is_calling_first) {
        if (dt_selector.rows( '.selected' ).any() && is_calling_first) {
            var rows = dt_selector.rows( '.selected' ).indexes();
            $( dt_selector.column( 5 ).footer() ).html(dt_selector.cells( rows, 5, { page: 'current' } ).data().sum().toFixed(2));
        }
        else {
            $( dt_selector.column( 5 ).footer() ).html(dt_selector.cells( rows, 5, { page: 'current' } ).data().sum().toFixed(2));
        }
    }

    if(all_permission.indexOf("expenses-delete") == -1)
        $('.buttons-delete').addClass('d-none');

</script>
@endsection

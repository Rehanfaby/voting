@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

@php
    $statusFilter = $statusFilter ?? 'all';
    $statusCounts = $statusCounts ?? ['all' => 0, 'success' => 0, 'pending' => 0, 'failed' => 0];
    $tabTitles = [
        'all' => trans('file.Votes List'),
        'success' => trans('file.Successful Votes'),
        'pending' => trans('file.Pending Votes'),
        'failed' => trans('file.Failed Votes'),
    ];
@endphp
<section>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header mt-2">
                <h3 class="text-center">{{ $tabTitles[$statusFilter] ?? trans('file.Votes List') }}</h3>
            </div>
            <div class="px-3 pt-3">
                <ul class="nav nav-pills flex-wrap mb-3 vote-status-tabs">
                    @foreach(['all','success','pending','failed'] as $tab)
                        <li class="nav-item mr-2 mb-2">
                            <a class="nav-link {{ $statusFilter === $tab ? 'active' : '' }}"
                               href="{{ route('votes.index', ['status' => $tab, 'start_date' => $start_date, 'end_date' => $end_date]) }}">
                                {{ $tabTitles[$tab] }}
                                <span class="badge badge-light ml-1">{{ number_format($statusCounts[$tab] ?? 0) }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            {!! Form::open(['route' => 'votes.index', 'method' => 'get']) !!}
            <input type="hidden" name="status" value="{{ $statusFilter }}" />
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
                <div class="col-md-2 mt-3">
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">{{trans('file.submit')}}</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="mb-3">
            @if(in_array("votes-add", $all_permission))
                <button class="btn btn-info" data-toggle="modal" data-target="#vote-modal"><i class="dripicons-plus"></i> {{trans('file.Add Vote')}}</button>
            @endif
            @if(in_array("votes-delete", $all_permission))
                <form action="{{ route('votes.clear') }}" method="POST" class="d-inline" onsubmit="return confirmClearVotes();">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="dripicons-wrong"></i> {{ trans('file.Clear Votes') }}
                    </button>
                </form>
            @endif
            @if(!empty($lastClearedAt))
                <span class="ml-2 text-muted">
                    {{ trans('file.Last cleared') }}:
                    <strong>{{ \Carbon\Carbon::parse($lastClearedAt)->format('D d-M-Y H:i') }}</strong>
                </span>
            @endif
        </div>
        <p class="text-muted small mb-3">
            {{ trans('file.Clear Votes help') }}
        </p>
    </div>
    <div class="table-responsive">
        <table id="expense-table" class="table">
            <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th>{{trans('file.Date')}}</th>
                    <th>{{trans('file.reference')}} No</th>
                    <th>{{trans('file.Contestant')}}</th>
                    <th>{{trans('file.Voter name')}}</th>
                    <th>{{trans('file.Votes')}}</th>
                    <th>{{trans('file.Status')}}</th>
                    <th class="not-exported">{{trans('file.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($votes as $key=>$vote)
                <tr data-id="{{$vote->id}}">
                    <td>{{$key}}</td>
                    <td>{{date('D d-M-Y', strtotime($vote->created_at->toDateString())) . ' '. $vote->created_at->toTimeString() }}</td>
                    <td>{{ $vote->reference }}</td>
                    <td>{{ @$vote->musicians->name }}</td>
                    <td>{{ @$vote->voters->name }}</td>
                    <td>
                        @if(!empty($vote->cleared_at))
                            <span class="text-muted" title="{{ trans('file.Cleared') }}">0</span>
                            <small class="text-muted">({{ (int) ($vote->cleared_vote ?? 0) }})</small>
                        @else
                            {{ $vote->vote }}
                        @endif
                    </td>
                    @if((int) $vote->status === 0)
                        <td><span class="badge badge-warning">Pending</span></td>
                    @elseif((int) $vote->status === 2)
                        <td><span class="badge badge-danger">Failed</span></td>
                    @elseif(!empty($vote->cleared_at))
                        <td><span class="badge badge-secondary">{{ trans('file.Cleared') }}</span></td>
                    @else
                        <td><span class="badge badge-success">Successful</span></td>
                    @endif
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans('file.action')}}
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                @if(in_array("votes-edit", $all_permission))
                                <li><button type="button" data-id="{{$vote->id}}" class="open-Editexpense_categoryDialog btn btn-link" data-toggle="modal" data-target="#editModal"><i class="dripicons-document-edit"></i> {{trans('file.edit')}}</button></li>
                                @endif
                                @if(in_array("votes-delete", $all_permission))
                                <li class="divider"></li>
                                {{ Form::open(['route' => ['votes.destroy', $vote->id], 'method' => 'DELETE'] ) }}
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
            <tfoot class="tfoot active">
                <th></th>
                <th>{{trans('file.Total')}}</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tfoot>
        </table>
    </div>
</section>

<div id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">Update Vote</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body">
              <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                {!! Form::open(['route' => ['votes.update', 1], 'method' => 'put']) !!}
                  <div class="form-group">
                      <input type="hidden" name="id">
                      <label>{{trans('file.reference')}}</label>
                      <p id="reference">{{'er-' . date("Ymd") . '-'. date("his")}}</p>
                  </div>
                  <div class="form-group">
                      <label>votes</label>
                      <input type="number" name="vote" class="form-control" value="">
                  </div>
                    <div class="form-group">
                        <input class="mt-2" type="checkbox" name="status">
                        <label class="mt-2"><strong>Complete</strong></label>
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

    $("ul#vote").siblings('a').attr('aria-expanded','true');
    $("ul#vote").addClass("show");
    @if(($statusFilter ?? 'all') === 'success')
        $("ul#vote #vote-menu-success").addClass("active");
    @elseif(($statusFilter ?? 'all') === 'pending')
        $("ul#vote #vote-menu-pending").addClass("active");
    @elseif(($statusFilter ?? 'all') === 'failed')
        $("ul#vote #vote-menu-failed").addClass("active");
    @else
        $("ul#vote #vote-menu").addClass("active");
    @endif

    var expense_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;
    var all_permission = <?php echo json_encode($all_permission) ?>;
    var statusFilter = @json($statusFilter ?? 'all');
    var filterStartDate = @json($start_date);
    var filterEndDate = @json($end_date);
    var filterTotal = {{ (int) ($statusCounts[$statusFilter ?? 'all'] ?? count($votes)) }};

    function collectSelectedVoteIds() {
        var ids = [];
        var table = $('#expense-table').DataTable();
        table.$('tr').each(function () {
            var $row = $(this);
            var $cb = $row.find('input.dt-checkboxes').first();
            if (!$cb.length || !$cb.prop('checked')) { return; }
            var id = $row.attr('data-id');
            if (id) { ids.push(String(id)); }
        });
        return ids.filter(function (v, i, a) { return a.indexOf(v) === i; });
    }

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
            var url = "votes/";
            var id = $(this).data('id').toString();
            url = url.concat(id).concat("/edit");
            $.get(url, function(data) {
                console.log(data);
                $('#editModal #reference').text(data['reference']);
                $("#editModal input[name='id']").val(data['id']);
                $("#editModal input[name='vote']").val(data['vote']);
                $("#editModal input[name='status']").val(data['status']);
                if(data['status'] == 1) {
                    $("#editModal input[name='status']").prop('checked', true);
                } else {
                    $("#editModal input[name='status']").prop('checked', false);
                }
                // $("#editModal input[name='expense_id']").val(data['id']);
                // $("#editModal textarea[name='note']").val(data['note']);
                // $('.selectpicker').selectpicker('refresh');
            });
        });
    });

function confirmDelete() {
    if (confirm("Are you sure want to delete?")) {
        return true;
    }
    return false;
}

function confirmClearVotes() {
    return confirm(@json(trans('file.Clear Votes confirm')));
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
                   'selectAllPages': false,
                   'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                },
                'targets': [0]
            }
        ],
        'select': { style: 'multi',  selector: 'td:first-child', info: false },
        'lengthMenu': [[10, 25, 50], [10, 25, 50]],
        'pageLength': 25,
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
                text: '<i class="dripicons-cross"></i> {{ trans('file.Delete Selected') }}',
                className: 'buttons-delete',
                action: function ( e, dt, node, config ) {
                    if(user_verified != '1') {
                        alert('This feature is disable for demo!');
                        return;
                    }
                    var ids = collectSelectedVoteIds();
                    if (!ids.length) {
                        alert('No vote is selected! Tick the checkbox next to each vote you want to delete.');
                        return;
                    }
                    if (!confirm('Delete ' + ids.length + ' selected vote(s)?')) {
                        return;
                    }
                    $.ajax({
                        type:'POST',
                        url:'{{ url("votes/deletebyselection") }}',
                        data:{ voteIdArray: ids, expenseIdArray: ids },
                        success:function(data){
                            alert(data);
                            location.reload();
                        },
                        error: function () {
                            alert('Delete failed. No votes were changed.');
                        }
                    });
                }
            },
            {
                text: '<i class="dripicons-trash"></i> {{ trans('file.Delete All in Filter') }}',
                className: 'buttons-delete-all',
                action: function () {
                    if(user_verified != '1') {
                        alert('This feature is disable for demo!');
                        return;
                    }
                    if (!filterTotal) {
                        alert('No votes in this filter.');
                        return;
                    }
                    var msg = 'WARNING: Delete ALL ' + filterTotal + ' vote(s) in the current tab/date filter?\n\nThis cannot be undone.';
                    if (!confirm(msg)) { return; }
                    if (!confirm('Confirm again: permanently delete ' + filterTotal + ' vote(s)?')) { return; }
                    $.ajax({
                        type:'POST',
                        url:'{{ url("votes/deletebyselection") }}',
                        data:{
                            delete_all: 1,
                            status: statusFilter,
                            start_date: filterStartDate,
                            end_date: filterEndDate
                        },
                        success:function(data){
                            alert(data);
                            location.reload();
                        },
                        error: function () {
                            alert('Delete failed. No votes were changed.');
                        }
                    });
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
            $( dt_selector.column( 5 ).footer() ).html(dt_selector.column( 5, { page: 'current' } ).data().sum().toFixed(2));
        }
    }

    if(all_permission.indexOf("votes-delete") == -1) {
        $('.buttons-delete, .buttons-delete-all').addClass('d-none');
    }

    try {
        var votesTable = $('#expense-table').DataTable();
        votesTable.rows().deselect();
        votesTable.$('input.dt-checkboxes').prop('checked', false).prop('indeterminate', false);
        $('#expense-table thead input.dt-checkboxes').prop('checked', false).prop('indeterminate', false);
    } catch (e) {}

</script>
<style>
.vote-status-tabs {
    gap: 8px;
}
.vote-status-tabs .nav-item {
    margin: 0 !important;
}
.vote-status-tabs .nav-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border: 2px solid #c7d7f5;
    border-radius: 12px;
    color: #0a2350;
    font-weight: 700;
    background: #ffffff;
    box-shadow: 0 1px 2px rgba(10, 35, 80, .06);
    transition: border-color .2s, background .2s, box-shadow .2s, color .2s;
}
.vote-status-tabs .nav-link .badge {
    background: #eef3ff;
    color: #1d4ed8;
    border: 1px solid #c7d7f5;
    font-weight: 800;
    border-radius: 999px;
    padding: 4px 8px;
}
.vote-status-tabs .nav-link:hover {
    border-color: #1d4ed8;
    background: #f5f8ff;
    color: #0a2350;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(29, 78, 216, .12);
}
.vote-status-tabs .nav-link.active {
    background: #1d4ed8;
    border-color: #163ea8;
    color: #fff;
    box-shadow: 0 6px 16px rgba(29, 78, 216, .28);
}
.vote-status-tabs .nav-link.active .badge {
    background: #fff;
    color: #1d4ed8;
    border-color: #fff;
}
.vote-status-tabs .nav-link[href*="status=success"] { border-color: #86efac; }
.vote-status-tabs .nav-link[href*="status=success"]:hover,
.vote-status-tabs .nav-link[href*="status=success"].active { border-color: #15803d; }
.vote-status-tabs .nav-link[href*="status=success"].active { background: #16a34a; box-shadow: 0 6px 16px rgba(22, 163, 74, .28); }
.vote-status-tabs .nav-link[href*="status=pending"] { border-color: #fcd34d; }
.vote-status-tabs .nav-link[href*="status=pending"]:hover,
.vote-status-tabs .nav-link[href*="status=pending"].active { border-color: #b45309; }
.vote-status-tabs .nav-link[href*="status=pending"].active { background: #f59e0b; box-shadow: 0 6px 16px rgba(245, 158, 11, .28); }
.vote-status-tabs .nav-link[href*="status=failed"] { border-color: #fca5a5; }
.vote-status-tabs .nav-link[href*="status=failed"]:hover,
.vote-status-tabs .nav-link[href*="status=failed"].active { border-color: #b91c1c; }
.vote-status-tabs .nav-link[href*="status=failed"].active { background: #ef4444; box-shadow: 0 6px 16px rgba(239, 68, 68, .28); }
</style>
@endsection

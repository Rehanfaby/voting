@extends('layout.main') @section('content')

@if($errors->has('name'))
<div class="alert alert-danger alert-dismissible text-center">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('name') }}</div>
@endif
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section>
    <form method="POST" action="{{ route('letter.multiple.ok') }}">
        @csrf
        <div class="container-fluid">
            @if(in_array("letter_create", $all_permission))
                <a href="{{route('letter.create')}}" class="btn btn-info"><i class="dripicons-plus"></i> {{trans('file.Add Letter')}} </a>
            @endif
            <input type="submit" class="btn btn-success approve-btn" value="Ok Multiple" style="display: none">
        </div>
    <div class="table-responsive">
        <table id="role-table" class="table">
            <thead>
            <tr>
                <th>Select</th>
                <th>{{trans('file.name')}}</th>
                <th>{{trans('file.Reference')}}</th>
                <th>{{trans('file.category')}}</th>
                <th>{{trans('file.Subject')}}</th>
                <th>{{trans('file.Status')}}</th>
                <th>{{trans('file.Created By')}}</th>
                <th>{{trans('file.Date')}}</th>
                <th>{{trans('file.Action')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $key=>$item)
                <tr  data-id="{{$item->id}}" class="clickable-row" style="cursor: pointer" data-href="{{ route('letter.show', $item->id) }}">
                    <td><input type="checkbox" class="checkbox-options" name="ids[{{$item->id}}]"></td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->reference }}</td>
                    <td>{{ $item->category ? $item->category->name : 'N/A' }}</td>
                    <td>{{ $item->subject}}</td>
                    <td><span class="badge badge-warning">Awaiting Editing</span></td>
                    <td>{{ $item->createdBy ? $item->createdBy->name : 'N/N'}}</td>
                    <td>{{ $item->created_at->format('d-M, Y')}}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans('file.action')}}
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default">
                                <li>
                                    <a href="{{ route('letter.show', $item->id) }}" class="btn btn-link"><i class="fa fa-eye"></i> {{trans('file.View')}}</a>
                                </li>
                                @if($item->is_approve == 0)
                                    @if(in_array("letter_edit", $all_permission))
                                        <li>
                                            <a href="{{ route('letter.edit', $item->id) }}" class="btn btn-link"><i class="dripicons-document-edit"></i> {{trans('file.edit')}}</a>
                                            <a href="{{ route('letter.edit.ok', $item->id) }}" class="btn btn-link"><i class="fa fa-check"></i> OK</a>
                                        </li>
                                        <li class="divider"></li>
                                    @endif
{{--                                    @if(in_array("letter_approve", $all_permission))--}}
{{--                                        <li>--}}
{{--                                            <a href="{{ route('letter.approve', $item->id) }}" class="btn btn-link"><i class="fa fa-check"></i> {{trans('file.Approve')}}</a>--}}
{{--                                        </li>--}}
{{--                                    @endif--}}
                                    @if(in_array("letter_delete", $all_permission))
                                            <li>
                                                <a href="{{ route('letter.delete', $item->id) }}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-link"><i class="dripicons-trash"></i> Delete</a>
                                            </li>
                                    @endif
                                        @if(in_array("letter_rejected", $all_permission))
                                            <li>
                                                <a href="{{ route('letter.reject', $item->id) }}" class="btn btn-link"><i class="fa fa-close"></i> Reject</a>
                                            </li>
                                        @endif
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


<script type="text/javascript">
    $(document).ready(function($) {
        $('.clickable-row td:not(:last-child, :first-child)').click(function () {
            window.location = $(this).closest('tr').data("href");
        });
    });

    $('.checkbox-options').click(function () {
        $('.approve-btn').show();
    });

    $("ul#letter").siblings('a').attr('aria-expanded','true');
    $("ul#letter").addClass("show");
    $("ul#letter #letter-awaiting-menu").addClass("active");

    $(document).ready(function() {
        $(document).on('click', '.open-EditroleDialog', function() {
            var url = "role/"
            var id = $(this).data('id').toString();
            url = url.concat(id).concat("/edit");

            $.get(url, function(data) {
                $("input[name='name']").val(data['name']);
                $("textarea[name='description']").val(data['description']);
                $("input[name='role_id']").val(data['id']);
            });
        });

        $('#role-table').DataTable( {
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
                    'targets': [0, 3]
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
                },
                {
                    extend: 'csv',
                    text: '<i title="export to csv" class="fa fa-file-text-o"></i>',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: ':visible'
                    },
                },
                {
                    extend: 'print',
                    text: '<i title="print" class="fa fa-print"></i>',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported)',
                        rows: ':visible'
                    },
                },
                {
                    extend: 'colvis',
                    text: '<i title="column visibility" class="fa fa-eye"></i>',
                    columns: ':gt(0)'
                },
            ],
        } );
    });
</script>

@endsection

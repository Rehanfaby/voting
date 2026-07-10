@extends('layout.main')
@section('content')
@if($errors->any())
<div class="alert alert-danger alert-dismissible text-center">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {{ $errors->first() }}
</div>
@endif
@if(session()->has('message'))
<div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div>
@endif
@if(session()->has('not_permitted'))
<div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section class="container-fluid">
    <div class="mb-3">
        <p class="text-muted" style="margin:6px 0 14px;">
            {{ trans('file.Upload partner or sponsor logos and set the Sort Order to control which one appears first or last on the homepage.') }}
        </p>
        @if(in_array('employees-add', $all_permission))
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addModal"><i class="dripicons-plus"></i> {{ trans('file.Add Logo') }}</button>
        @endif
    </div>

    <div class="table-responsive">
        <table id="partner-table" class="table">
            <thead>
                <tr>
                    <th>{{ trans('file.Logo') }}</th>
                    <th>{{ trans('file.name') }}</th>
                    <th>{{ trans('file.Link') }}</th>
                    <th>{{ trans('file.Sort Order') }}</th>
                    <th>{{ trans('file.Status') }}</th>
                    <th class="not-exported">{{ trans('file.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($partners as $partner)
                <tr>
                    <td>
                        @if($partner->image)
                            <img src="{{ url('public/images/partners', $partner->image) }}" height="60" style="max-width:140px;object-fit:contain;background:#f4f7fc;padding:4px;border-radius:8px;">
                        @else
                            {{ trans('file.No Image') }}
                        @endif
                    </td>
                    <td>{{ $partner->name }}</td>
                    <td>
                        @if($partner->link)
                            <a href="{{ $partner->link }}" target="_blank" rel="noopener noreferrer">{{ \Illuminate\Support\Str::limit($partner->link, 40) }}</a>
                        @else — @endif
                    </td>
                    <td>{{ $partner->sort_order }}</td>
                    <td>
                        @if($partner->is_active)
                            <span class="badge badge-success">{{ trans('file.Active') }}</span>
                        @else
                            <span class="badge badge-secondary">{{ trans('file.Inactive') }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">{{ trans('file.action') }} <span class="caret"></span></button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                @if(in_array('employees-edit', $all_permission))
                                <li>
                                    <button type="button" class="btn btn-link edit-btn" data-toggle="modal" data-target="#editModal"
                                        data-id="{{ $partner->id }}"
                                        data-name="{{ $partner->name }}"
                                        data-link="{{ $partner->link }}"
                                        data-sort_order="{{ $partner->sort_order }}"
                                        data-is_active="{{ $partner->is_active }}">
                                        <i class="dripicons-document-edit"></i> {{ trans('file.edit') }}
                                    </button>
                                </li>
                                @endif
                                @if(in_array('employees-delete', $all_permission))
                                {{ Form::open(['route' => ['partner.destroy', $partner->id], 'method' => 'DELETE']) }}
                                <li><button type="submit" class="btn btn-link" onclick="return confirm('{{ trans('file.Delete this logo?') }}')"><i class="dripicons-trash"></i> {{ trans('file.delete') }}</button></li>
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

<div id="addModal" class="modal fade text-left" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document"><div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ trans('file.Add Logo') }}</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            {!! Form::open(['route' => 'partner.store', 'method' => 'post', 'files' => true]) !!}
            <div class="form-group"><label>{{ trans('file.Logo') }} *</label><input type="file" name="image" class="form-control" accept="image/*" required></div>
            <div class="form-group"><label>{{ trans('file.name') }}</label><input type="text" name="name" class="form-control" placeholder="{{ trans('file.Partner name') }}"></div>
            <div class="form-group"><label>{{ trans('file.Link') }}</label><input type="url" name="link" class="form-control" placeholder="https://example.com"></div>
            <div class="form-group"><label>{{ trans('file.Sort Order') }}</label><input type="number" name="sort_order" class="form-control" value="0" min="0"></div>
            <div class="form-group">
                <label>{{ trans('file.Status') }}</label>
                <select name="is_active" class="form-control">
                    <option value="1">{{ trans('file.Active') }}</option>
                    <option value="0">{{ trans('file.Inactive') }}</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div></div>
</div>

<div id="editModal" class="modal fade text-left" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document"><div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ trans('file.Edit Logo') }}</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            {!! Form::open(['route' => 'partner.update', 'method' => 'post', 'files' => true]) !!}
            <input type="hidden" name="partner_id" id="edit-partner-id">
            <div class="form-group"><label>{{ trans('file.Logo') }}</label><input type="file" name="image" class="form-control" accept="image/*"><small class="text-muted">{{ trans('file.Leave empty to keep current logo') }}</small></div>
            <div class="form-group"><label>{{ trans('file.name') }}</label><input type="text" name="name" id="edit-name" class="form-control"></div>
            <div class="form-group"><label>{{ trans('file.Link') }}</label><input type="url" name="link" id="edit-link" class="form-control"></div>
            <div class="form-group"><label>{{ trans('file.Sort Order') }}</label><input type="number" name="sort_order" id="edit-sort-order" class="form-control" min="0"></div>
            <div class="form-group">
                <label>{{ trans('file.Status') }}</label>
                <select name="is_active" id="edit-is-active" class="form-control">
                    <option value="1">{{ trans('file.Active') }}</option>
                    <option value="0">{{ trans('file.Inactive') }}</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div></div>
</div>

<script type="text/javascript">
    $("#partner-top-menu").addClass("active");

    $('.edit-btn').on('click', function () {
        $('#edit-partner-id').val($(this).data('id'));
        $('#edit-name').val($(this).data('name'));
        $('#edit-link').val($(this).data('link'));
        $('#edit-sort-order').val($(this).data('sort_order'));
        $('#edit-is-active').val(String($(this).data('is_active')) === '0' ? '0' : '1');
    });

    $('#partner-table').DataTable({
        "order": [[3, 'asc']],
        'columnDefs': [{ "orderable": false, 'targets': [0, 5] }],
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
</script>
@endsection

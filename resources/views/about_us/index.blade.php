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
    @include('about_us.partials.frontend-preview', ['previewSection' => 'leaders', 'previewMembers' => $members])

    @if(in_array('employees-add', $all_permission))
    <div class="container-fluid mb-3">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addModal"><i class="dripicons-plus"></i> {{ trans('file.Add About Member') }}</button>
    </div>
    @endif
    <div class="table-responsive">
        <table id="about-table" class="table">
            <thead>
                <tr>
                    <th>{{ trans('file.Image') }}</th>
                    <th>{{ trans('file.name') }}</th>
                    <th>{{ trans('file.Title') }}</th>
                    <th>{{ trans('file.Description') }}</th>
                    <th>{{ trans('file.Country') }}</th>
                    <th>{{ trans('file.Sort Order') }}</th>
                    <th class="not-exported">{{ trans('file.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                <tr>
                    <td>
                        @if($member->image)
                            <img src="{{ url('public/images/employee', $member->image) }}" height="64" width="64" style="object-fit:cover;border-radius:50%;">
                        @else
                            {{ trans('file.No Image') }}
                        @endif
                    </td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->title }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($member->bio, 80) }}</td>
                    <td>
                        @include('partials.country_flag', ['country' => $member->country, 'size' => 24])
                        {{ \App\Helpers\CountryFlag::label($member->country) }}
                    </td>
                    <td>{{ $member->sort_order }}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">{{ trans('file.action') }} <span class="caret"></span></button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                @if(in_array('employees-edit', $all_permission))
                                <li>
                                    <button type="button" class="btn btn-link edit-btn" data-toggle="modal" data-target="#editModal"
                                        data-id="{{ $member->id }}"
                                        data-name="{{ $member->name }}"
                                        data-title="{{ $member->title }}"
                                        data-bio="{{ $member->bio }}"
                                        data-country="{{ $member->country }}"
                                        data-sort_order="{{ $member->sort_order }}">
                                        <i class="dripicons-document-edit"></i> {{ trans('file.edit') }}
                                    </button>
                                </li>
                                @endif
                                @if(in_array('employees-delete', $all_permission))
                                {{ Form::open(['route' => ['about_us.destroy', $member->id], 'method' => 'DELETE']) }}
                                <li><button type="submit" class="btn btn-link" onclick="return confirm('Delete this member?')"><i class="dripicons-trash"></i> {{ trans('file.delete') }}</button></li>
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
            <h5 class="modal-title">{{ trans('file.Add About Member') }}</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            {!! Form::open(['route' => 'about_us.store', 'method' => 'post', 'files' => true]) !!}
            <div class="form-group"><label>{{ trans('file.name') }} *</label><input type="text" name="name" class="form-control" required></div>
            <div class="form-group"><label>{{ trans('file.Title') }}</label><input type="text" name="title" class="form-control" placeholder="Founder, Board Member…"></div>
            <div class="form-group"><label>{{ trans('file.Description') }}</label><textarea name="bio" class="form-control" rows="4"></textarea></div>
            <div class="form-group"><label>{{ trans('file.Country') }}</label>@include('partials.country_select', ['selected' => old('country')])</div>
            <div class="form-group"><label>{{ trans('file.Sort Order') }}</label><input type="number" name="sort_order" class="form-control" value="0" min="0"></div>
            <div class="form-group"><label>{{ trans('file.Image') }}</label><input type="file" name="image" class="form-control" accept="image/*"></div>
            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div></div>
</div>

<div id="editModal" class="modal fade text-left" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document"><div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ trans('file.Edit About Member') }}</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            {!! Form::open(['route' => 'about_us.update', 'method' => 'post', 'files' => true]) !!}
            <input type="hidden" name="member_id" id="edit-member-id">
            <div class="form-group"><label>{{ trans('file.name') }} *</label><input type="text" name="name" id="edit-name" class="form-control" required></div>
            <div class="form-group"><label>{{ trans('file.Title') }}</label><input type="text" name="title" id="edit-title" class="form-control"></div>
            <div class="form-group"><label>{{ trans('file.Description') }}</label><textarea name="bio" id="edit-bio" class="form-control" rows="4"></textarea></div>
            <div class="form-group"><label>{{ trans('file.Country') }}</label>@include('partials.country_select', ['selected' => ''])</div>
            <div class="form-group"><label>{{ trans('file.Sort Order') }}</label><input type="number" name="sort_order" id="edit-sort-order" class="form-control" min="0"></div>
            <div class="form-group"><label>{{ trans('file.Image') }}</label><input type="file" name="image" class="form-control" accept="image/*"></div>
            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
            {!! Form::close() !!}
        </div>
    </div></div>
</div>

<script type="text/javascript">
    $('.edit-btn').on('click', function () {
        $('#edit-member-id').val($(this).data('id'));
        $('#edit-name').val($(this).data('name'));
        $('#edit-title').val($(this).data('title'));
        $('#edit-bio').val($(this).data('bio'));
        $('#edit-sort-order').val($(this).data('sort_order'));
        $('#editModal select[name="country"]').val($(this).data('country') || '');
    });
</script>
@endsection

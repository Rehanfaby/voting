@extends('layout.main') @section('content')
@if($errors->has('name'))
<div class="alert alert-danger alert-dismissible text-center">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('name') }}</div>
@endif
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
@php
    use App\Helpers\ImageOptimizer;
    $contestants = collect($lims_employee_all ?? []);
    $contestantCount = $contestants->count();
@endphp
<section class="mg-awaiting">
    <div class="container-fluid">
        <div class="mg-awaiting__hero">
            <div>
                <p class="mg-awaiting__eyebrow">Contestants</p>
                <h1 class="mg-awaiting__title">{{ !empty($pending) ? trans('file.Pending Contestants') : trans('file.Contestants') }}</h1>
                <p class="mg-awaiting__sub">Same card layout as Judge / Ambassador grading lists.</p>
            </div>
            <div class="mg-awaiting__count">
                <span class="mg-awaiting__count-num">{{ $contestantCount }}</span>
                <span class="mg-awaiting__count-label">contestants</span>
            </div>
        </div>

        <div class="mg-awaiting__toolbar">
            <div class="mg-awaiting__search">
                <i class="fa fa-search"></i>
                <input type="search" id="mg-contestant-search" placeholder="Search contestant…" autocomplete="off">
            </div>
            @if(empty($pending))
                <a class="mg-awaiting__help-link" href="{{ route('report.contestants.generate_pdf') }}" style="text-decoration:none;">
                    <i class="fa fa-file-pdf-o"></i> {{ trans('file.Generate Contestant List') }}
                </a>
            @endif
            @if(in_array("employees-add", $all_permission))
                <button type="button" class="mg-awaiting__help-link" data-toggle="modal" data-target="#addModal" style="border:0;cursor:pointer;">
                    <i class="dripicons-plus"></i> Add Contestant
                </button>
            @endif
            @if(in_array("employees-delete", $all_permission))
                <button type="button" id="mg-delete-selected" class="mg-awaiting__help-link mg-awaiting__help-link--ghost" style="border:0;cursor:pointer;color:#b91c1c !important;">
                    <i class="dripicons-trash"></i> Delete selected
                </button>
            @endif
            @if(!empty($pending) && in_array("employees-edit", $all_permission))
                <button type="button" id="mg-approve-selected" class="mg-awaiting__help-link" style="border:0;cursor:pointer;background:#16a34a;">
                    <i class="fa fa-check"></i> Approve selected
                </button>
            @endif
        </div>

        @if($contestantCount === 0)
            <div class="mg-awaiting__empty">
                <i class="fa fa-microphone"></i>
                <h3>No contestants</h3>
                <p>Add a contestant to get started.</p>
            </div>
        @else
            <div class="mg-awaiting__grid" id="mg-contestant-grid">
                @foreach($contestants->sortBy('name') as $employee)
                    @php
                        $department = \App\Department::find($employee->department_id);
                        $img = ImageOptimizer::employeeImageUrl($employee->image ?? '');
                        $search = strtolower(($employee->name ?? '') . ' ' . ($employee->email ?? '') . ' ' . ($employee->phone_number ?? '') . ' ' . (optional($department)->name ?? ''));
                    @endphp
                    <div class="mg-awaiting-card mg-list-card" data-id="{{ $employee->id }}" data-name="{{ $search }}">
                        <label class="mg-contestant-check">
                            <input type="checkbox" class="mg-contestant-cb" value="{{ $employee->id }}">
                        </label>
                        <div class="mg-awaiting-card__photo">
                            @if(!empty($employee->image))
                                <img src="{{ $img }}" alt="{{ $employee->name }}" loading="lazy" width="88" height="88">
                            @else
                                <span class="mg-awaiting-card__initial">{{ strtoupper(substr($employee->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="mg-awaiting-card__body">
                            <h3 class="mg-awaiting-card__name">{{ $employee->name }}</h3>
                            <p class="mg-awaiting-card__hint">
                                {{ $employee->phone_number }}
                                @if(optional($department)->name) · {{ $department->name }}@endif
                            </p>
                            <p class="mg-awaiting-card__hint">{{ $employee->email }}</p>
                        </div>
                        <div class="mg-list-card__actions">
                            <a href="{{ route('report.contestant.grading', $employee->id) }}" class="mg-list-card__btn" title="Grading scores"><i class="fa fa-star"></i></a>
                            @if(in_array("employees-edit", $all_permission))
                                @if((int) $employee->is_approve === 0)
                                    <a href="{{ route('musician.approve', $employee->id) }}" class="mg-list-card__btn" style="background:#16a34a;" title="Approve" onclick="return confirm('Approve this contestant?');"><i class="fa fa-check"></i></a>
                                @endif
                                <button type="button"
                                    class="mg-list-card__btn edit-btn"
                                    title="Edit"
                                    data-id="{{$employee->id}}"
                                    data-name="{{$employee->name}}"
                                    data-email="{{$employee->email}}"
                                    data-phone_number="{{$employee->phone_number}}"
                                    data-department_id="{{$employee->department_id}}"
                                    data-address="{{$employee->address}}"
                                    data-city="{{$employee->city}}"
                                    data-country="{{$employee->country}}"
                                    data-toggle="modal"
                                    data-target="#editModal"><i class="dripicons-document-edit"></i></button>
                            @endif
                            <a href="{{ route('musician.gallery', $employee->id) }}" class="mg-list-card__btn" title="Gallery"><i class="fa fa-image"></i></a>
                            <a href="{{ route('musician.votes', $employee->id) }}" class="mg-list-card__btn" title="Votes"><i class="dripicons-mail"></i></a>
                            @if(in_array("employees-delete", $all_permission))
                                {{ Form::open(['route' => ['musician.destroy', $employee->id], 'method' => 'DELETE', 'style' => 'margin:0'] ) }}
                                <button type="submit" class="mg-list-card__btn mg-list-card__btn--danger" title="Delete" onclick="return confirmDelete()"><i class="dripicons-trash"></i></button>
                                {{ Form::close() }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="mg-awaiting__none-match" id="mg-contestant-none" style="display:none;">No contestant matches your search.</p>
        @endif
    </div>
</section>
@include('partials.grading-list-styles')
<style>
.mg-contestant-check { flex-shrink: 0; display:flex; align-items:center; margin:0 4px 0 0; }
.mg-contestant-check input { width:18px; height:18px; }
.mg-list-card__actions { flex-wrap: wrap; max-width: 120px; justify-content: flex-end; }
@media (max-width: 575.98px) {
    .mg-list-card__actions { max-width: none; flex-direction: row; }
}
</style>

@php
    $cmr_regions_add = ['Adamawa','Centre','East','Far North','Littoral','North','North-West','South','South-West','West'];
@endphp
<div id="addModal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Contestant</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body">
                <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                {!! Form::open(['route' => 'musician.store', 'method' => 'post', 'files' => true]) !!}
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>{{trans('file.name')}} *</label>
                        <input type="text" name="employee_name" required class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{trans('file.Image')}}</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{trans('file.Department')}} *</label>
                        <select class="form-control selectpicker" name="department_id" required>
                            @foreach($lims_department_list as $department)
                            <option value="{{$department->id}}">{{$department->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{trans('file.Email')}} *</label>
                        <input type="email" name="email" required class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{trans('file.Phone Number')}} *</label>
                        <input type="text" name="phone_number" required class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{trans('file.Address')}}</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Region *</label>
                        <select class="form-control" name="city" required>
                            <option value="">-- Select region --</option>
                            @foreach($cmr_regions_add as $region)
                            <option value="{{ $region }}">{{ $region }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{trans('file.Country')}}</label>
                        <input type="text" name="country" class="form-control" value="Cameroon" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{trans('file.submit')}}</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<div id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">Update Contestant</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body">
              <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                {!! Form::open(['route' => ['musician.update', 1], 'method' => 'put', 'files' => true]) !!}
                <div class="row">
                    <div class="col-md-6 form-group">
                        <input type="hidden" name="employee_id" />
                        <label>{{trans('file.name')}} *</label>
                        <input type="text" name="name" required class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{trans('file.Image')}}</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{trans('file.Department')}} *</label>
                        <select class="form-control selectpicker" name="department_id" required>
                            @foreach($lims_department_list as $department)
                            <option value="{{$department->id}}">{{$department->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{trans('file.Email')}} *</label>
                        <input type="email" name="email" required class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{trans('file.Phone Number')}} *</label>
                        <input type="text" name="phone_number" required class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{trans('file.Address')}}</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                    @php
                        $cmr_regions = ['Adamawa','Centre','East','Far North','Littoral','North','North-West','South','South-West','West'];
                    @endphp
                    <div class="col-md-6 form-group">
                        <label>Region *</label>
                        <select class="form-control" name="city" required>
                            <option value="">-- Select region --</option>
                            @foreach($cmr_regions as $region)
                            <option value="{{ $region }}">{{ $region }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{trans('file.Country')}}</label>
                        <input type="text" name="country" class="form-control" value="Cameroon" readonly>
                    </div>
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

    $("ul#people").siblings('a').attr('aria-expanded','true');
    $("ul#people").addClass("show");
    @if($pending == 0)
    $("ul#people #employee-menu").addClass("active");
    @else
    $("ul#people #employee-pending-menu").addClass("active");
    @endif

    var employee_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function confirmDelete() {
        if (confirm("Are you sure want to delete?")) {
            return true;
        }
        return false;
    }

    function collectSelectedIds() {
        var ids = [];
        $('.mg-contestant-cb:checked').each(function () {
            ids.push(String($(this).val()));
        });
        return ids;
    }

    $(document).on('click', '.edit-btn', function() {
        $("#editModal input[name='employee_id']").val( $(this).data('id') );
        $("#editModal input[name='name']").val( $(this).data('name') );
        $("#editModal select[name='department_id']").val( $(this).data('department_id') );
        $("#editModal input[name='email']").val( $(this).data('email') );
        $("#editModal input[name='phone_number']").val( $(this).data('phone_number') );
        $("#editModal input[name='address']").val( $(this).data('address') );
        $("#editModal select[name='city']").val( $(this).data('city') );
        $("#editModal input[name='country']").val( 'Cameroon' );
        $('#editModal .selectpicker').selectpicker('refresh');
    });

    $('#addModal').on('show.bs.modal', function () {
        var form = $(this).find('form')[0];
        if (form) { form.reset(); }
        $(this).find('.paste-image-preview').hide();
        $('#addModal .selectpicker').selectpicker('refresh');
    });

    (function () {
        var input = document.getElementById('mg-contestant-search');
        var grid = document.getElementById('mg-contestant-grid');
        var none = document.getElementById('mg-contestant-none');
        if (!input || !grid) return;
        input.addEventListener('input', function () {
            var q = (input.value || '').toLowerCase().trim();
            var shown = 0;
            grid.querySelectorAll('.mg-list-card').forEach(function (card) {
                var match = !q || (card.getAttribute('data-name') || '').indexOf(q) !== -1;
                card.style.display = match ? '' : 'none';
                if (match) shown++;
            });
            if (none) none.style.display = shown ? 'none' : 'block';
        });
    })();

    $('#mg-delete-selected').on('click', function () {
        if (user_verified != '1') {
            alert('This feature is disable for demo!');
            return;
        }
        var ids = collectSelectedIds();
        if (!ids.length) {
            alert('No contestant is selected!');
            return;
        }
        if (!confirm('Delete ' + ids.length + ' selected contestant(s)?')) return;
        $.ajax({
            type: 'POST',
            url: '{{ url("musician/deletebyselection") }}',
            data: { employeeIdArray: ids },
            success: function (data) { alert(data); location.reload(); },
            error: function () { alert('Delete failed. No contestants were changed.'); }
        });
    });

    $('#mg-approve-selected').on('click', function () {
        var ids = collectSelectedIds();
        if (!ids.length) {
            alert('No contestant is selected!');
            return;
        }
        if (!confirm('Approve ' + ids.length + ' selected contestant(s)?')) return;
        $.ajax({
            type: 'POST',
            url: '{{ url("musician/approvebyselection") }}',
            data: { employeeIdArray: ids },
            success: function (data) { alert(data); location.reload(); }
        });
    });
</script>
@endsection

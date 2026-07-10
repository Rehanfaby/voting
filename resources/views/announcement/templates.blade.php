@extends('layout.main') @section('content')
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif
    <section>
        <div class="container-fluid">
            <a href="{{ route('announcement.index') }}" class="btn btn-info"><i class="dripicons-list"></i> {{ trans('file.Announcement List') }}</a>
            <a href="{{ route('announcement.create') }}" class="btn btn-primary"><i class="dripicons-plus"></i> {{ trans('file.Create Announcement') }}</a>
        </div>
        <div class="card mt-3">
            <div class="card-header"><h4>{{ trans('file.Announcement Templates') }}</h4></div>
            <div class="card-body">
                <p class="text-muted">{{ trans('file.Templates help') }}</p>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{ trans('file.name') }}</th>
                            <th>{{ trans('file.Subject') }}</th>
                            <th>{{ trans('file.Status') }}</th>
                            <th>{{ trans('file.Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($templates as $tpl)
                            <tr>
                                <td>{{ $tpl->name }}</td>
                                <td>{{ $tpl->subject }}</td>
                                <td>
                                    @if($tpl->is_active)
                                        <span class="badge badge-success">{{ trans('file.Active') }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ trans('file.Inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('announcement.template.edit', $tpl->id) }}" class="btn btn-sm btn-warning"><i class="dripicons-document-edit"></i> {{ trans('file.edit') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <script>$("#announcement-top-menu").addClass("active");</script>
@endsection

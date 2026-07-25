@extends('layout.main')
@section('content')
@if(session('message'))
  <div class="alert alert-success text-center">{{ session('message') }}</div>
@endif
@if(session('not_permitted'))
  <div class="alert alert-danger text-center">{{ session('not_permitted') }}</div>
@endif
<section class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>{{ trans('file.Halls') }}</h3>
    <a href="{{ route('halls.create') }}" class="btn btn-info"><i class="dripicons-plus"></i> {{ trans('file.Add Hall') }}</a>
  </div>
  <div class="card">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>{{ trans('file.Name') }}</th>
            <th>{{ trans('file.City') }}</th>
            <th>{{ trans('file.Layouts') }}</th>
            <th>{{ trans('file.Status') }}</th>
            <th>{{ trans('file.action') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($halls as $hall)
            <tr>
              <td><strong>{{ $hall->name }}</strong></td>
              <td>{{ $hall->city ?: '—' }}</td>
              <td>
                @if($hall->latestPublishedLayout)
                  <span class="badge badge-success">v{{ $hall->latestPublishedLayout->version }} published</span>
                @endif
                @if($hall->draftLayout)
                  <span class="badge badge-warning">v{{ $hall->draftLayout->version }} draft</span>
                @endif
                @if(!$hall->latestPublishedLayout && !$hall->draftLayout)
                  <span class="text-muted">None</span>
                @endif
              </td>
              <td>{{ $hall->is_active ? trans('file.Active') : trans('file.Inactive') }}</td>
              <td>
                <a class="btn btn-sm btn-primary" href="{{ route('halls.edit', $hall->id) }}">{{ trans('file.edit') }}</a>
                @php $layout = $hall->draftLayout ?: $hall->latestPublishedLayout; @endphp
                @if($layout)
                  <a class="btn btn-sm btn-info" href="{{ route('halls.layouts.edit', [$hall->id, $layout->id]) }}">{{ trans('file.Seat map') }}</a>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-muted">{{ trans('file.No halls yet') }}</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</section>
<script>$("ul#product").siblings('a').attr('aria-expanded','true'); $("ul#product").addClass("show"); $("#halls-menu").addClass("active");</script>
@endsection

@extends('layout.main')
@section('content')

@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
  <div class="row">
    <div class="container-fluid">
      <div class="col-md-12">
        <div class="brand-text float-left mt-4">
            <h3>{{trans('file.welcome')}} <span>{{Auth::user()->name}}</span> </h3>
            @if(Auth::user()->role_id == 2 && \App\Employee::where('user_id', Auth::user()->id)->value('is_approve') == false)
                <span class="alert alert-danger"> {{ trans('file.your account is not approved yet, please contact to administrator to approve your account') }}</span>
            @endif
        </div>
      </div>
    </div>
  </div>

@endsection

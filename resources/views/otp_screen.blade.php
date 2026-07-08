@extends('layout.main')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card mt-5">
                <div class="card-header">
                    <h4><i class="fab fa-whatsapp"></i> {{ trans('file.Authentication') }}</h4>
                </div>
                <div class="card-body">
                    @if(session()->has('not_permitted'))
                        <div class="alert alert-danger">{{ session()->get('not_permitted') }}</div>
                    @endif
                    <p class="text-muted">{{ trans('file.OTP login instructions') }}</p>
                    @if(Auth::user()->is_active)
                    <form action="{{ route('check.otp.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="login-otp">{{ trans('file.OTP') }}</label>
                            <input id="login-otp" type="text" name="otp" required class="form-control text-center" inputmode="numeric" maxlength="6" placeholder="000000" autocomplete="one-time-code" style="letter-spacing:8px;font-size:22px;font-weight:700;">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">{{ trans('file.Verify OTP') }}</button>
                    </form>
                    @else
                    <div class="alert alert-warning">{{ trans('file.Account not activated contact admin') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

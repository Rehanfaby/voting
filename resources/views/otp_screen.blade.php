@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
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

                    @if(Auth::user()->is_active)
                    <form action="{{ route('check.otp.store') }}" method="post">
                        @csrf
                        <div class="form-group-material">
                            <input id="login-otp" placeholder="" type="number" name="otp" required class="input-material" value="">
                            <label for="login-otp" class="label-material">OTP</label>
                            @if ($errors->has('otp'))
                                <p>
                                    <strong>{{ $errors->first('otp') }}</strong>
                                </p>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Verify OTP</button>
                    </form>
                    @else
                    You are logged in but id is not activated! Please contact with admin.
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

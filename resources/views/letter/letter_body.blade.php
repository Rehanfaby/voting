<style>
    .align-items-center-logo {
        text-align: center;
        display: inline;
        margin-left: 26%;
    }
    .card{
        width: 60vw;
        margin-left: 15%;
    }
    .pull-left {
        float: left;
        margin-left: 200px;
    }
    .pull-right-no-margin{
        float: right;
    }
    .pull-right {
        float: right;
        margin-right: 200px;
    }
    .waterm-mark {
        width: 20%;
        position: absolute;
        top: 40%;
        right: 330px;
        opacity: 0.3;
    }
    table {width: 100%;}
    tfoot tr th:first-child {text-align: left;}

    .centered {
        text-align: center;
        align-content: center;
    }
    .edit{
        position: absolute;
        margin-top: 0px;
        margin-left: 115px;
        z-index: 0;
        opacity: 0.5;
    }
    .approve{
        position: absolute;
        margin-top: 0px;
        margin-left: 50px;
        z-index: 0;
        opacity: 0.5;
    }
    .header-letter{
        margin-top: 40px;
    }
</style>
@if($data->attachment)
    <a href="{{url('public/letter/attachment',$data->attachment)}}" target="_blank">,<span class="fa fa-eye"></span> View Attachment</a>
@else
    <p>No Attachment</p>
@endif
{{--@if($general_setting->invoice_format != 'beyond_a4')--}}
    <div class="align-items-center-logo">
        @if($general_setting->site_logo)
            <img src="{{url('public/logo/', $general_setting->site_logo)}}" height="150" width="150" style="margin:10px 0;filter: brightness(0);">
        @endif
    </div>
{{--@endif--}}

@php
    if ($data->people_type == "customer") {
        $user = \App\Customer::class;
    } else {
        $user = \App\Employee::class;
    }
@endphp

<div class="pull-right-no-margin">
    @if($data->is_edit == 1)
        @php
            $edit = \App\User::find($data->edit_by);
        @endphp
        <img class="edit" src="{{url('public/images/user',$edit->stemp)}}" height="50vw">
    @endif
    @if($data->is_approve == 1)
        @php
            $approve = \App\User::find($data->approved_by);
        @endphp
        <img class="approve" src="{{url('public/images/user',$approve->stemp)}}" height="50vw">
    @endif
    <span class ="header-letter">{!! $data->header !!}</span>

</div>
<br><br><br><br>

<div>Ref: {{ $data->reference }} <br>
    {{ date('M d, Y') }}</div><br>
<div>Dear:
    @foreach (explode(",", $data->to) as $to)
        {{ $user::find($to) ? $user::find($to)->name .  ', ' : '' }}
    @endforeach
</div>
<br><br>
<div  style="text-transform: uppercase">
    <h2>Subject: <span style="text-decoration: underline;">{{ $data->subject }}</span></h2>
</div>
{!! $data->body !!}
<br>
<p>Sincerely, </p>
<div class="row">
    <div class="col-md-6">
        @if($data->is_sign == 1)
            @php
                $approve = \App\User::find($data->signed_by);
            @endphp
            <img src="{{url('public/images/user',$approve->sign)}}" height="100vw">
        @endif
    </div>
</div>
<br>
@if($data->footer != null)
    {!! $data->footer !!}
@else
    {{ $data->name }}
@endif
<br><br>
<h2>CC:
    @php
        foreach (explode(",", $data->cc) as $cc) {
            echo $user::find($cc) ? $user::find($cc)->name .  ', ' : '';
        }
    @endphp
</h2>

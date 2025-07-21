<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}" />
    <title>{{$general_setting->site_title}}</title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="all,follow">

<style type="text/css">
    .logo {
        text-align: right;
        display: inline;
        margin-left: 268px;
        margin-right: 0;
        padding-right: 0;
    }
    .pull-left {
        float: left;
        margin-left: 0px;
    }
    .pull-right {
        float: right;
    }
    .header{
        float: right;
        margin-left: 0px;
        padding-left: 0px;
        text-align: right;
        font-size: 10px;
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
        margin-top: -20px;
        margin-left: 75px;
        margin-right: 0px;
        padding-right: 0px;
        z-index: 0;
        opacity: 0.5;
    }
    .approve{
        position: absolute;
        margin-top: -20px;
        margin-left: 30px;
        z-index: 0;
        opacity: 0.5;
    }
    .header-letter{
        top: 40px;
        text-align: center;
    }
</style>
</head>
<body>

@if($general_setting->invoice_format == 'beyond_a4')
    <img src="{{public_path('logo/') . $general_setting->email_header}}" style=" width: 100%;">
    {{--    <img src="{{public_path('logo/') . $general_setting->email_water_mark}}" class="waterm-mark">--}}
    <div style="max-width:95vw;margin:0 auto; ">
        @else
            <div style="max-width:1400px;margin:0 auto; ">
                @endif
                @php
                    $user_class = \App\User::class;

                @endphp
                <div id="receipt-data">
                    @if($general_setting->invoice_format != 'beyond_a4')
                        <div class="logo">
                            @if($general_setting->site_logo)
                                <img src="{{public_path('logo/') . $general_setting->site_logo}}" height="100" style="margin:10px 0;filter: brightness(0);">
                            @endif
                        </div>
                    @endif

                    <div class="header">
                        <span class ="header-letter">{!! $data->header !!}</span>

                    </div>

                    <br><br>
                    <div>Ref: {{ $data->reference }} <br>
                        {{ date('M d, Y') }}</div><br>

                    <br>

                    <div>
                        @php
                            foreach (explode(",", $data->to) as $to) {
                                echo  $user_class::find($to) ? "Dear ". $user_class::find($to)->name .  ', ' : '';
                            }
                        @endphp
                    </div>
                    <div class="card-body" id="letter-body" style="text-transform: uppercase">
                        <h2>Subject: <span style="text-decoration: underline;">{{ $data->subject }}</span></h2>
                    </div>
                    {!! $data->body !!}
                    <br>
                    <p>Sincerely, </p>
                    <br>
                    @if($data->footer != null)
                        {!! $data->footer !!}
                    @else
                        {{ $data->name }}
                    @endif
                    <h5>CC:
                        @php
                            foreach (explode(",", $data->cc) as $cc) {
                                echo $user_class::find($cc) ? $user_class::find($cc)->name .  ', ' : '';
                            }
                        @endphp
                    </h5>
                    @if($data->attachment)
                        <h5>Files: {{ $data->attachmentLib ? count($data->attachmentLib) : 1 }}</h5>
                    @endif
                </div>
            </div>
    </div>
    @if($general_setting->invoice_format == 'beyond_a4')
        <div class="lastPage" >
            <img id="print-footer" src="{{public_path('logo/') . $general_setting->email_footer}}" style=" width: 100%;">
        </div>
    @endif

</body>
</html>

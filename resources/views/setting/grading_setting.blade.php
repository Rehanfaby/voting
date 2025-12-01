@extends('layout.main') @section('content')

@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.Grading Setting')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => 'setting.gradingStore', 'files' => true, 'method' => 'post']) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Vote Percentage')}} *</label>
                                        <input type="number" name="vote_percentage" class="form-control" value="@if($lims_general_setting_data){{$lims_general_setting_data->vote_percentage}}@endif"/>
                                    </div>
                                    @if($errors->has('vote_percentage'))
                                   <span>
                                       <strong>{{ $errors->first('vote_percentage') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Judge Percentage')}} *</label>
                                        <input type="number" name="judge_percentage" class="form-control" value="@if($lims_general_setting_data){{$lims_general_setting_data->judge_percentage}}@endif"/>
                                    </div>
                                    @if($errors->has('judge_percentage'))
                                        <span>
                                       <strong>{{ $errors->first('judge_percentage') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Ambassador Percentage')}} *</label>
                                        <input type="number" name="ambassador_percentage" class="form-control" value="@if($lims_general_setting_data){{$lims_general_setting_data->ambassador_percentage}}@endif"/>
                                    </div>
                                    @if($errors->has('ambassador_percentage'))
                                        <span>
                                       <strong>{{ $errors->first('ambassador_percentage') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Number of Elimination')}} *</label>
                                        <input type="number" name="number_of_elimination" class="form-control" value="@if($lims_general_setting_data){{$lims_general_setting_data->number_of_elimination}}@endif"/>
                                    </div>
                                    @if($errors->has('number_of_elimination'))
                                        <span>
                                       <strong>{{ $errors->first('number_of_elimination') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Voting Start')}} *</label><br>
                                        <input type="hidden" name="is_voting_start" value="0">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="is_voting_start" value="1"
                                                {{ $lims_general_setting_data->is_voting_start ? 'checked' : '' }}>
                                            {{ trans('file.Voting Start') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Available for Grading')}} *</label><br>
                                        <input type="hidden" name="available_grading" value="0">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="available_grading" value="1"
                                                {{ $lims_general_setting_data->available_grading ? 'checked' : '' }}>
                                            {{ trans('file.Available for Grading') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $("ul#grading-setting").siblings('a').attr('aria-expanded','true');
    $("ul#grading-setting").addClass("show");
    $("ul#grading-setting #grading-setting-menu").addClass("active");

    $("select[name=invoice_format]").on("change", function (argument) {
        if($(this).val() == 'standard') {
            $("#state").addClass('d-none');
            $("input[name=state]").prop("required", false);
        }
        else if($(this).val() == 'gst') {
            $("#state").removeClass('d-none');
            $("input[name=state]").prop("required", true);
        }
    })
    if($("input[name='timezone_hidden']").val()){
        $('select[name=timezone]').val($("input[name='timezone_hidden']").val());
        $('select[name=staff_access]').val($("input[name='staff_access_hidden']").val());
        $('select[name=date_format]').val($("input[name='date_format_hidden']").val());
        $('select[name=invoice_format]').val($("input[name='invoice_format_hidden']").val());
        if($("input[name='invoice_format_hidden']").val() == 'gst') {
            $('select[name=state]').val($("input[name='state_hidden']").val());
            $("#state").removeClass('d-none');
        }
        $('.selectpicker').selectpicker('refresh');
    }

    $('.theme-option').on('click', function() {
        $.get('general_setting/change-theme/' + $(this).data('color'), function(data) {
        });
        var style_link= $('#custom-style').attr('href').replace(/([^-]*)$/, $(this).data('color') );
        $('#custom-style').attr('href', style_link);
    });


</script>
@endsection

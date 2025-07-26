@extends('layout.main') @section('content')
@if(session()->has('create_message'))
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('create_message') }}</div>
@endif
@if(session()->has('edit_message'))
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('edit_message') }}</div>
@endif
@if(session()->has('import_message'))
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('import_message') }}</div>
@endif
@if(session()->has('not_permitted'))
    <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif
@if(session()->has('message'))
    <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif

<section>
    <div class="container-fluid">
        @if(in_array("scan-ticket", $all_permission))
            <div class="card">
                <div class="card-header">
                    <h2><center>{{trans('file.Ticket Scan')}}</center></h2>
                </div>
                <div class="card-body">
                    <form class="form" action="{{ route('admin.ticket.scan') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{trans('file.Enter Token Here')}}  </label>
                                    <div class="input-group">
                                        <input type="text" name="token" class="form-control" id="code"
                                               aria-describedby="code" required placeholder="{{trans('file.Enter Token Here')}}">
                                        <div class="input-group-append">
                                            <button id="genbtn" type="submit" class="btn btn-sm btn-default"
                                                    title="{{trans('file.Generate')}}"><i> {{trans('file.Search')}}</i>
                                            </button>
                                        </div>
                                    </div>
                                    <span class="validation-msg" id="code-error"></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

</section>


<script>
    $("ul#product").siblings('a').attr('aria-expanded','true');
    $("ul#product").addClass("show");
    $("ul#product #ticket-scan").addClass("active");
</script>
@endsection

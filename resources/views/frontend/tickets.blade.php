@extends('frontend.layout.main')
@section('content')

    @if($errors->has('name'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('name') }}</div>
    @endif
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif
    @if(session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif

    <main>
        <!-- page title area start  -->
        <section class="page-title-area page-title-spacing p-relative zindex-1 " data-background="assets/img/bg/work-bg.jpg">
            <div class="ms-overlay ms-overlay8 p-absolute zindex--1"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-9">
                        <div class="page-title-wrapper text-center pt-15">
                            <div class="page-title-icon mx-auto mb-30">
                                <i class="flaticon-star"></i>
                            </div>
                            <h3 class="ms-page-title lh-1">{{trans("file.Purchase Ticket")}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- page title area end  -->

        <!-- team area start here  -->
        <section class="ms-team-area ms-bg-2 pt-125 pb-110">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6">
                        <div class="section__title-wrapper text-center mb-80">
                            <h2 class="section__title">{{trans("file.Ticket List")}}</h2>
                            <input type="text" id="ticket-search" class="form-control mt-3" placeholder="Search tickets...">
                        </div>
                    </div>
                </div>
                <div class="row ms-team-inner" id="ticket-list">
                    @foreach($tickets as $ticket)
                        <div class="col-xl-4 col-md-6 ticket-item">
                            <div class="ms-team-item-wrap">
                                <div class="ms-team-item p-relative">
                                    <div class="ms-team-img mb-3">
                                        <a href="{{ route('ticket.data', $ticket->id) }}">
                                                <?php $images = explode(",", $ticket->image)?>
                                            <img src="{{ url('public/images/product', $images[0]) }}" alt="ticket image">
                                        </a>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h3 class="ms-team-title mb-0">
                                            <a href="{{ route('ticket.data', $ticket->id) }}">{{ $ticket->name }}</a>
                                        </h3>
                                        <span class="badge bg-success ms-team-price fs-6">
                                            {{trans("file.Ticket Price")}}: {{ number_format($ticket->price, 2) }}
                                        </span>
                                        <span class="badge bg-primary ms-team-price fs-6">
                                            {{trans("file.Remaining Seats")}}: {{ @$ticket->remaining_qty }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $tickets->links() }}
            </div>
        </section>
        <!-- team area end here  -->

    </main>
    <script>
$(document).ready(function(){
    $('#ticket-search').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#ticket-list .ticket-item').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>

@endsection

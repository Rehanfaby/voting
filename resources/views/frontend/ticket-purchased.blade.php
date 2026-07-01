@extends('frontend.layout.main')
@section('content')

    @if($errors->has('name'))
        <div class="alert alert-danger alert-dismissible text-center">{{ $errors->first('name') }}</div>
    @endif
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center">{{ session()->get('message') }}</div>
    @endif
    @if(session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center">{{ session()->get('not_permitted') }}</div>
    @endif
    <main>
        <!-- page title area start  -->
        <section class="page-title-area page-title-spacing p-relative zindex-1" data-background="assets/img/shop/shop-page-title.jpg">
            <div class="ms-overlay ms-overlay9 p-absolute zindex--1"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-11">
                        <h3 class="ms-page-title text-center">{{trans("file.My Events")}}</h3>
                    </div>
                </div>
            </div>
        </section>
        <!-- page title area end  -->

        <!-- Products Area Start  -->
        <div class="ms-product-area pt-50 pb-70 p-relative">
            <div class="container">
                <div class="row">
                    <div class="text-center message-status"></div>
                    <div class="col-md-12">
                        <div class="ms-product-table-wrap mb-60">
                            <div class="ms-product-table mb-50">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>{{trans("file.Event Name")}}</th>
                                        <th>{{trans("file.Ticket")}}</th>
                                        <th>{{trans("file.Status")}}</th>
                                        <th>{{trans("file.Seat No")}}</th>
                                        <th>{{trans("file.Date")}}</th>
                                        <th>{{trans("file.Event Date")}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td class="ms-product-name-flex">
                                                <img src="{{ url('public/images/product', $ticket->product->image) }}" alt="Event img" style="width: 100px">
                                                <span>{{ $ticket->product->name }}</span>
                                            </td>
                                            <td>{{ $ticket->qty }}</td>
                                            <td>{{ $ticket->status == 0 ? 'pending' : 'complete' }}</td>
                                            <td>{{ $ticket->seat_numbers }}</td>
                                            <td>{{ $ticket->created_at }}</td>
                                            <td>{{ $ticket->product->Deadline }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                    {{ $tickets->links() }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection

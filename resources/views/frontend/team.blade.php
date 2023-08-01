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
                            <h3 class="ms-page-title lh-1">Vote Your Favourite Contentment</h3>
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
                            <h2 class="section__title">Contentment</h2>
                        </div>
                    </div>
                </div>
                <div class="row ms-team-inner">
                    @foreach($musicians as $musician)
                    <div class="col-xl-4 col-md-6">
                        <div class="ms-team-item-wrap">
                            <div class="ms-team-item p-relative">
                                <div class="ms-team-img">
                                    <a href="{{ route('musician.data', $musician->id) }}">
                                        <img src="{{url('public/images/employee',$musician->image)}}" alt="team image">
                                    </a>
                                </div>
                                <h3 class="ms-team-title"><a href="{{ route('musician.data', $musician->id) }}">{{ $musician->name }}</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!-- team area end here  -->

    </main>

@endsection

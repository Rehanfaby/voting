@extends('layout.main') @section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h3>Edit Points</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('points.update', $point) }}" method="POST">
                        @method('PUT')
                        @include('points._form')
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        $("ul#point").siblings('a').attr('aria-expanded','true');
        $("ul#point").addClass("show");
        $("ul#point #point-menu-list").addClass("active");

    </script>
@endsection

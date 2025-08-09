@extends('layout.main') @section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h3>Edit Points</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('ambassador_points.update', $point) }}" method="POST">
                        @method('PUT')
                        @include('ambassador_points._form')
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        $("ul#ambassador-point").siblings('a').attr('aria-expanded','true');
        $("ul#ambassador-point").addClass("show");
        $("ul#ambassador-point #ambassador-point-menu-list").addClass("active");
    </script>
@endsection

@extends('layout.main') @section('content')
    <div class="container-fluid p-5">
        <div class="card">
            <div class="card-header">
                <h3>Point #{{ $point->id }}</h3>
            </div>
            <div class="card-body">
                <p><strong>Judge:</strong> {{ $point->judge->name }}</p>
                <p><strong>Candidate:</strong> {{ $point->contestant->name }}</p>

                <p><strong>Points:</strong> {{ $point->points }}</p>
                <a href="{{ route('points.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
    </section>

    <script type="text/javascript">
        $("ul#ambassador-point").siblings('a').attr('aria-expanded','true');
        $("ul#ambassador-point").addClass("show");
        $("ul#ambassador-point #ambassador-point-menu-create").addClass("active");

    </script>
@endsection

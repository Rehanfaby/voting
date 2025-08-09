@extends('layout.main') @section('content')
    <div class="container-fluid p-5">
        <div class="card">
            <div class="card-header">
                <h3>Point #{{ $point->id }}</h3>
            </div>
            <div class="card-body">
                <p><strong>Judge:</strong> {{ $point->judge->name }}</p>
                <p><strong>Candidate:</strong> {{ $point->contestant->name }}</p>
                <ul>
                    <li>Depth: {{ $point->depth }}</li>
                    <li>Diction: {{ $point->diction }}</li>
                    <li>Accuracy: {{ $point->accuracy }}</li>
                    <li>Interpretation: {{ $point->interpretation }}</li>
                    <li>Technique: {{ $point->technique }}</li>
                    <li>Stage presence: {{ $point->stage_presence }}</li>
                    <li>Song choice: {{ $point->song_choice }}</li>
                    <li>Overall presentation: {{ $point->overall_presentation }}</li>
                    <li>Adaptability: {{ $point->adaptability }}</li>
                    <li>Audience interaction: {{ $point->audience_interaction }}</li>
                </ul>
                <p><strong>Total:</strong> {{ $point->total }}</p>
                <a href="{{ route('points.index') }}" class="btn btn-secondary">Back</a>
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

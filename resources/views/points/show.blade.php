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
                    <li>{{ trans('file.Depth and atmosphere / Spiritual impact') }}: {{ $point->depth }}</li>
{{--                    <li>Diction: {{ $point->diction }}</li>--}}
                    <li>{{ trans('file.Accuracy and precision') }}: {{ $point->accuracy }}</li>
                    <li>{{ trans('file.Interpretation, emotion, and heartfelt engagement / Originality and style') }}: {{ $point->interpretation }}</li>
{{--                    <li>Technique: {{ $point->technique }}</li>--}}
{{--                    <li>Stage presence: {{ $point->stage_presence }}</li>--}}
                    <li>{{ trans('file.file.Choice of song / Key') }}: {{ $point->song_choice }}</li>
                    <li>{{ trans('file.Overall presentation') }}: {{ $point->overall_presentation }}</li>
{{--                    <li>Adaptability: {{ $point->adaptability }}</li>--}}
{{--                    <li>Audience interaction: {{ $point->audience_interaction }}</li>--}}
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

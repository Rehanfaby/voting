@extends('layout.main') @section('content')
<section class="forms mg-grade-page">
    <div class="container-fluid">
        <div class="mg-grade-page__hero">
            <div>
                <p class="mg-grade-page__eyebrow">Judge Grading</p>
                <h1 class="mg-grade-page__title">{{ trans('file.Grade Candidate') }}</h1>
                @if($candidate_name)
                    <p class="mg-grade-page__candidate">{{ $candidate_name }}</p>
                @else
                    <p class="mg-grade-page__sub">Select a candidate and enter each criterion score. Do not exceed Max.</p>
                @endif
            </div>
            <div class="mg-grade-page__badge">Max 100</div>
        </div>

        <div class="card mg-grade-page__card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-validation" role="alert">
                        <strong>Could not save.</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('points.store') }}" method="POST" id="judge-point-form">
                    @include('points._form')
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.mg-grade-page { padding-bottom: 28px; }
.mg-grade-page__hero {
    display: flex; align-items: flex-start; justify-content: space-between; gap: 16px;
    background: linear-gradient(135deg, #0a2350 0%, #1d4ed8 100%);
    color: #fff; border-radius: 18px; padding: 20px 18px; margin-bottom: 16px;
    box-shadow: 0 12px 30px rgba(10,35,80,.22);
}
.mg-grade-page__eyebrow {
    margin: 0 0 4px; font-size: 12px; letter-spacing: .06em; text-transform: uppercase;
    color: #f5c518; font-weight: 700;
}
.mg-grade-page__title { margin: 0; font-size: 1.45rem; font-weight: 800; line-height: 1.2; }
.mg-grade-page__candidate {
    margin: 8px 0 0; font-size: 1.05rem; font-weight: 700; color: #f5c518;
}
.mg-grade-page__sub { margin: 8px 0 0; font-size: 13px; color: rgba(255,255,255,.82); max-width: 36rem; }
.mg-grade-page__badge {
    flex-shrink: 0; align-self: center; padding: 10px 14px; border-radius: 14px;
    background: rgba(255,255,255,.12); border: 1px solid rgba(245,197,24,.45);
    color: #f5c518; font-weight: 800; font-size: 13px;
}
.mg-grade-page__card {
    border: 0; border-radius: 18px; box-shadow: 0 10px 28px rgba(15,23,42,.06);
    overflow: hidden;
}
.mg-grade-page__card .card-body { padding: 18px; }
@media (min-width: 768px) {
    .mg-grade-page__title { font-size: 1.75rem; }
    .mg-grade-page__card .card-body { padding: 24px; }
}
</style>

<script type="text/javascript">
    $("ul#point").siblings('a').attr('aria-expanded','true');
    $("ul#point").addClass("show");
    $("ul#point #point-menu-create").addClass("active");

    $('#judge_id').on('change', function () {
        var judgeId = $(this).val();

        $.ajax({
            url: '/contestants/' + judgeId + '/rated',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var $select = $('#candidate_id');
                $select.empty();
                $select.append('<option value="">Choose</option>');

                $.each(data, function (i, contestant) {
                    if (!contestant.rated) {
                        $select.append('<option value="' + contestant.id + '">' + contestant.name + '</option>');
                    }
                });

                $select.selectpicker('refresh');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        var inputs = document.querySelectorAll('.points-input');
        var totalSpan = document.querySelector('.total-points');

        function updateTotal() {
            var total = 0;
            inputs.forEach(function (input) {
                var val = parseFloat(input.value);
                if (!isNaN(val)) total += val;
            });
            if (totalSpan) totalSpan.textContent = total;
        }

        inputs.forEach(function (input) {
            input.addEventListener('input', updateTotal);
        });
        updateTotal();
    });
</script>
@endsection

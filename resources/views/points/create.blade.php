@extends('layout.main') @section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h3>{{ trans('file.Grade Candidate') }} </h3>
                @if($candidate_name) &nbsp; <h4> => ({{ $candidate_name }})</h4> @endif
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
                <form action="{{ route('points.store') }}" method="POST">
                    @include('points._form')
                </form>
            </div>
            <div class="card-footer">
                <h3  class="ml-2">{{ trans('file.Total') }} (<span class="total-points">0</span>)</h3>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $("ul#point").siblings('a').attr('aria-expanded','true');
    $("ul#point").addClass("show");
    $("ul#point #point-menu-create").addClass("active");

    // Trigger when judge is selected
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

                // Assuming data = [{id:1, name:'John', rated:true}, ...]
                $.each(data, function (i, contestant) {
                    if (!contestant.rated) { // skip rated contestants
                        $select.append('<option value="' + contestant.id + '">' + contestant.name + '</option>');
                    }
                });

                $select.selectpicker('refresh');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('.points-input');
        const totalSpan = document.querySelector('.total-points');

        function updateTotal() {
            let total = 0;
            inputs.forEach(input => {
                let val = parseFloat(input.value);
                console.log(val);
                if (!isNaN(val)) {
                    total += val;
                }
            });
            totalSpan.textContent = total;
        }

        // Update on input
        inputs.forEach(input => {
            input.addEventListener('input', updateTotal);
        });

        // Initial calculation in case values are pre-filled
        updateTotal();
    });

</script>
@endsection

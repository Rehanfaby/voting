@extends('layout.main') @section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h3>Create Point</h3>
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

</script>
@endsection

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
                <div class="card-footer">
                    <h3  class="ml-2">{{ trans('file.Total') }} (<span class="total-points">0</span>)</h3>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        $("ul#point").siblings('a').attr('aria-expanded','true');
        $("ul#point").addClass("show");
        $("ul#point #point-menu-list").addClass("active");


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

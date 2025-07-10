@extends('layout.main') @section('content')
@if(session()->has('create_message'))
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('create_message') }}</div>
@endif
@if(session()->has('edit_message'))
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('edit_message') }}</div>
@endif
@if(session()->has('import_message'))
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('import_message') }}</div>
@endif
@if(session()->has('not_permitted'))
    <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif
@if(session()->has('message'))
    <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif

<section>
    <section class="ms-login-area pb-50 pt-130">
        <div class="container">
            <div class="ms-maxw-510 mx-auto">
                <div class="row">
                    <div class="col-md-6">
                        <h3>📊 Ticket Stats — {{ $product->name }}</h3>

                        <ul class="list-group my-4">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Total Qty:</strong> {{ $totalQty }}
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Booked Qty:</strong> {{ $bookedQty }}
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Used Qty:</strong> {{ $usedQty }}
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Available Qty:</strong> {{ $availableQty }}
                            </li>
                        </ul>

                    </div>
                    <div class="col-md-6">
                        <div class="mb-5">
                            <h5 class="mb-3">📊 Ticket Stats Chart</h5>
                            <canvas id="productStatsChart" height="120"></canvas>
                        </div>
                    </div>
                </div>
                <h5>🎟️ Buyer Details</h5>
                @if($buyers->count())
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Qty</th>
                                <th>Used</th>
                                <th>Booked At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($buyers as $buyer)
                                <tr>
                                    <td>{{ $buyer->name }}</td>
                                    <td>{{ $buyer->phone }}</td>
                                    <td>{{ $buyer->qty }}</td>
                                    <td>
                                        @if($buyer->is_used)
                                            <span class="badge bg-success">Used</span>
                                        @else
                                            <span class="badge bg-warning">Not Used</span>
                                        @endif
                                    </td>
                                    <td>{{ $buyer->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>No tickets found for this product.</p>
                @endif
            </div>
        </div>
    </section>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $("ul#product").siblings('a').attr('aria-expanded','true');
    $("ul#product").addClass("show");
    $("ul#product #product-list-menu").addClass("active");

    const ctx = document.getElementById('productStatsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar', // You can also try 'pie'
        data: {
            labels: ['Total Qty', 'Booked Qty', 'Used Qty', 'Available Qty'],
            datasets: [{
                label: '{{ $product->name }}',
                data: [
                    {{ $totalQty }},
                    {{ $bookedQty }},
                    {{ $usedQty }},
                    {{ $availableQty }}
                ],
                backgroundColor: [
                    '#007bff', // Total
                    '#ffc107', // Booked
                    '#28a745', // Used
                    '#17a2b8'  // Available
                ],
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
</script>
@endsection

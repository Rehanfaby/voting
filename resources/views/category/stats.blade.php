@extends('layout.main') @section('content')
    <!-- Bootstrap 5 JS (Include once in your layout) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .ticket-details-row {
            background-color: #f9f9f9;
        }
    </style>
    @section('styles')
        <style>
            .ticket-details-row {
                background-color: #f9f9f9;
            }
        </style>
    @endsection

    <section class="py-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="mb-0">🎟️ Event Stats</h3>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush mb-4">
                                <li class="list-group-item d-flex justify-content-between"><strong>Total Qty:</strong> {{ $totalQty }}</li>
                                <li class="list-group-item d-flex justify-content-between"><strong>Booked Qty:</strong> {{ $bookedQty }}</li>
                                <li class="list-group-item d-flex justify-content-between"><strong>Used Qty:</strong> {{ $usedQty }}</li>
                                <li class="list-group-item d-flex justify-content-between"><strong>Available Qty:</strong> {{ $availableQty }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <h4 class="mb-3">📈 Ticket Stats Chart</h4>
                    <canvas id="categoryChart" height="120" class="mb-5"></canvas>
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-3">📦 Ticket Listing</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-primary text-center">
                            <tr>
                                <th>Product</th>
                                <th>Total Qty</th>
                                <th>Booked</th>
                                <th>Used</th>
                                <th>Available</th>
                                <th>Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($productStats as $stat)
                                <tr class="text-center">
                                    <td>{{ $stat['product_name'] }}</td>
                                    <td>{{ $stat['qty'] }}</td>
                                    <td>{{ $stat['booked'] }}</td>
                                    <td>{{ $stat['used'] }}</td>
                                    <td>{{ $stat['available'] }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#details-{{ $stat['product_id'] }}">
                                            View Buyers
                                        </button>
                                    </td>
                                </tr>
                                <tr class="collapse ticket-details-row" id="details-{{ $stat['product_id'] }}">
                                    <td colspan="6">
                                        @php
                                            $buyers = \App\TicketSeat::where('product_id', $stat['product_id'])->get();
                                        @endphp

                                        @if($buyers->count())
                                            <table class="table table-sm table-striped mb-0">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th>Qty</th>
                                                    <th>Token</th>
                                                    <th>Used</th>
                                                    <th>purchasing Date</th>
                                                    <th>Seat No.</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($buyers as $buyer)
                                                    <tr>
                                                        <td>{{ @$buyer->ticket->name }}</td>
                                                        <td>{{ @$buyer->ticket->phone }}</td>
                                                        <td>{{ 1 }}</td>
                                                        <td>{{ $buyer->token }}</td>
                                                        <td>{{ $buyer->is_used == 1 ? 'Yes' : 'No' }}</td>
                                                        <td>{{ @$buyer->ticket->created_at }}</td>
                                                        <td>{{ $buyer->seat_number }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="mb-0">No tickets found for this product.</p>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No products found for this category.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script type="text/javascript">
        $("ul#product").siblings('a').attr('aria-expanded','true');
        $("ul#product").addClass("show");
        $("ul#product #category-menu").addClass("active");

        const ctx = document.getElementById('categoryChart')?.getContext('2d');

        if (ctx) {
            const productLabels = {!! json_encode(collect($productStats)->pluck('product_name')) !!};
            const totalData     = {!! json_encode(collect($productStats)->pluck('qty')) !!};
            const bookedData    = {!! json_encode(collect($productStats)->pluck('booked')) !!};
            const usedData      = {!! json_encode(collect($productStats)->pluck('used')) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: productLabels,
                    datasets: [
                        {
                            label: 'Total Qty',
                            data: totalData,
                            backgroundColor: '#007bff'
                        },
                        {
                            label: 'Booked Qty',
                            data: bookedData,
                            backgroundColor: '#ffc107'
                        },
                        {
                            label: 'Used Qty',
                            data: usedData,
                            backgroundColor: '#28a745'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        },
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Products'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Quantity'
                            },
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        } else {
            console.error("Canvas with ID 'categoryChart' not found!");
        }
    </script>

@endsection

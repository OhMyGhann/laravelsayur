@extends('welcome')

@section('content')
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Order</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-white">Order</li>
        </ol>
    </div>
    <!-- Single Page Header End -->

    <!-- Orders Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row g-4">
                @foreach ($orders as $order)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Pesanan #{{ $order->order_number }}</h5>
                                <td>
                                    <span
                                        class="badge
                                    @if ($order->status == 'pending') bg-warning
                                    @elseif($order->status == 'processing') bg-info
                                    @elseif($order->status == 'completed') bg-success
                                    @elseif($order->status == 'declined') bg-danger @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </div>
                            <div class="card-body">
                                <h6>Total Harga: Rp {{ number_format($order->total_price, 0, ',', '.') }}</h6>
                                <p>Tanggal Order: {{ $order->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="card-footer">
                                @if ($order->status == 'pending')
                                    <a href="{{ route('showPaymentPage', ['order_id' => $order->id]) }}" class="btn btn-primary">Bayar Sekarang</a>
                                @elseif($order->status == 'completed')
                                    <a href="{{ route('order.pdf.download', $order->id) }}" class="btn btn-success">Download Invoice</a>
                                @elseif($order->status == 'declined')
                                    <a href="{{ route('order.pdf.download', $order->id) }}" class="btn btn-secondary">Detail Pembelian</a>
                                @elseif($order->status == 'processing')
                                    <p class="mb-0">Menunggu Pembayaran</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Orders End -->

    <style>
        @media (max-width: 600px) {
            .card-title {
                font-size: 1rem;
            }
            .card-body h6,
            .card-body p {
                font-size: 0.875rem;
            }
            .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 768px) {
            .card-title {
                font-size: 1.125rem;
            }
            .card-body h6,
            .card-body p {
                font-size: 1rem;
            }
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
        }
    </style>
@endsection

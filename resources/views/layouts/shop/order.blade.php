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
                @if ($orders->isEmpty())
                    <div class="col-md-12">
                        <div class="alert alert-warning text-center" role="alert">
                            Tidak ada order.
                        </div>
                    </div>
                @else
                    @foreach ($orders as $order)
                        <div class="col-md-12">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Pesanan #{{ $order->order_number }}</h5>
                                    <span
                                        class="badge
                                        @if ($order->status == 'pending') bg-warning
                                        @elseif($order->status == 'processing') bg-info
                                        @elseif($order->status == 'packed') bg-primary
                                        @elseif($order->status == 'completed') bg-success
                                        @elseif($order->status == 'declined') bg-danger @endif">
                                        {{ 
                                            $order->status == 'pending' ? 'Menunggu' : (
                                            $order->status == 'processing' ? 'Sedang Diproses' : (
                                            $order->status == 'packed' ? 'Sedang Dikemas' : (
                                            $order->status == 'completed' ? 'Order Selesai' : (
                                            $order->status == 'declined' ? 'Ditolak' : ucfirst($order->status)
                                        ))))
                                        }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    @if (is_null($order->shipping_price))
                                        <h6>Ongkos Kirim: <span class="text-warning">Tunggu Konfirmasi Admin</span></h6>
                                    @else
                                        <h6>Ongkos Kirim: Rp {{ number_format($order->shipping_price, 0, ',', '.') }}</h6>
                                    @endif
                                    <h6>Total Harga: Rp {{ number_format($order->total_price, 0, ',', '.') }}</h6>
                                    <p>Tanggal Order: {{ $order->created_at->format('d M Y') }}</p>
                                </div>
                                <div class="card-footer">
                                    @if ($order->status == 'pending')
                                        <p class="mb-0">Tunggu Konfirmasi Admin</p>
                                    @elseif($order->status == 'completed')
                                        <a href="{{ route('order.pdf.download', $order->id) }}" class="btn btn-success">Download Invoice</a>
                                    @elseif($order->status == 'declined')
                                        <a href="{{ route('order.pdf.download', $order->id) }}" class="btn btn-secondary">Detail Pembelian</a>
                                    @elseif($order->status == 'processing')
                                        @if ($order->bukti_tf && $order->bukti_tf !== 'no')
                                            <!-- Tombol untuk melihat bukti transfer -->
                                            <a href="javascript:void(0);" 
                                               class="btn btn-secondary" 
                                               data-bs-toggle="modal" 
                                               data-bs-target="#buktiTfModal" 
                                               data-img-url="{{ asset('storage/' . $order->bukti_tf) }}">Check Bukti Transfer</a>
                                        @else
                                            <!-- Tombol untuk mengunggah bukti pembayaran -->
                                            <a href="{{ route('showPaymentPage', ['order_id' => $order->id]) }}" class="btn btn-primary">Upload Bukti Pembayaran</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <!-- Orders End -->

    <!-- Modal -->
    <div class="modal fade" id="buktiTfModal" tabindex="-1" aria-labelledby="buktiTfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="buktiTfModalLabel">Bukti Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="buktiTfImage" src="" class="img-fluid" alt="Bukti Transfer">
                </div>
            </div>
        </div>
    </div>

    <script>
        var buktiTfModal = document.getElementById('buktiTfModal');
        buktiTfModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var imgUrl = button.getAttribute('data-img-url');
            var modalImage = buktiTfModal.querySelector('#buktiTfImage');
            modalImage.src = imgUrl;
        });
    </script>

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

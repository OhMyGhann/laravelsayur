@extends('welcome')

@section('content')
<!-- Single Page Header start -->
 <div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Pembayaran</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Pages</a></li>
        <li class="breadcrumb-item active text-white">Pembayaran</li>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Contact Start -->
<div class="container-fluid contact py-5">
    <div class="container py-5">
        <div class="container mt-5">
            <h1 class="text-center text-primary mb-4">Total Harga yang Harus Dibayar: Rp {{ number_format($order->total_price, 0, ',', '.') }}</h1>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($metodes as $metode)
                <div class="col">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $metode->image_path) }}" class="card-img-top img-fluid card-img-fixed" alt="{{ $metode->bank_name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $metode->bank_name }}</h5>
                            <p class="card-text">Bank Code: {{ $metode->bank_code }}</p>
                            <p class="card-text">Account Number: {{ $metode->no_rekening }}</p>
                            {{-- Fee dihapus --}}
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmPaymentModal" data-order-id="{{ $order_id }}" data-metode-id="{{ $metode->id }}" data-bank-name="{{ $metode->bank_name }}">Bayar dengan {{ $metode->bank_name }}</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        
        <!-- Confirm Payment Modal -->
        <div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-labelledby="confirmPaymentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmPaymentModalLabel">Konfirmasi Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin melanjutkan pembayaran menggunakan <strong><span id="bankName"></span></strong>?</p>
                        <form id="confirmPaymentForm" action="{{ route('processPayment') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="order_id" id="modalOrderId" value="">
                            <input type="hidden" name="metode_id" id="modalMetodeId" value="">
                            <div class="mb-3">
                                <label for="bukti_tf" class="form-label font-weight-bold">Upload Bukti Pembayaran</label>
                                <input type="file" class="form-control" name="bukti_tf" id="bukti_tf" required>
                                (format file: jpg/jpeg)
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="confirmPaymentForm" class="btn btn-primary">Bayar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact End -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var confirmPaymentModal = document.getElementById('confirmPaymentModal');
        confirmPaymentModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var orderId = button.getAttribute('data-order-id');
            var metodeId = button.getAttribute('data-metode-id');
            var bankName = button.getAttribute('data-bank-name');
            var modalOrderId = document.getElementById('modalOrderId');
            var modalMetodeId = document.getElementById('modalMetodeId');
            var bankNameSpan = document.getElementById('bankName');

            modalOrderId.value = orderId;
            modalMetodeId.value = metodeId;
            bankNameSpan.textContent = bankName;
        });
    });
</script>

<style>
    .card-img-fixed {
        height: 200px;
        object-fit: cover;
    }
</style>
@endsection

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


    <!-- Contact Start -->
    <div class="container-fluid contact py-5">
        <div class="container py-5">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Nomer 1 Pesanan</th>
                        <th scope="col">Total Harga</th>
                        <th scope="col">Status</th>
                        <th scope="col">Tanggal Order</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
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
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                @if ($order->status == 'pending')
                                <a href="{{ route('showPaymentPage', ['order_id' => $order->id]) }}"
                                    class="btn btn-primary">Bayar Sekarang</a>
                            @elseif($order->status == 'completed')
                                <a href="{{ route('order.pdf.download', $order->id) }}" class="btn btn-success">Download
                                    Invoice</a>
                            @elseif($order->status == 'declined')
                                <a href="{{ route('order.pdf.download', $order->id) }}" class="btn btn-secondary">Detail
                                    Pembelian</a>
                            @elseif($order->status == 'processing')
                                <p>Tunggu konfirmasi admin</p>
                            @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Contact End -->
@endsection

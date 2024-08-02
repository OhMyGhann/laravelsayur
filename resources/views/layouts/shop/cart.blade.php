@extends('welcome')

@section('content')
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Cart</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-white">Shop Detail</li>
        </ol>
    </div>
    <!-- Single Page Header End -->



    <div class="container-fluid py-5">
        <div class="container py-5">
            @if ($cart->isNotEmpty())
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Produk</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Total</th>
                                <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart as $item)
                                <tr data-id="{{ $item['id'] }}">
                                    <th scope="row">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $item['image_path']) }}"
                                                class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;"
                                                alt="{{ $item['name'] }}">
                                        </div>
                                    </th>
                                    <td>
                                        <p class="mb-0 mt-4">{{ $item['name'] }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 mt-4">Rp. {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </td>
                                    <td>
                                        <div class="input-group quantity mt-4" style="width: 100px;">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-minus rounded-circle bg-light border">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text" class="form-control form-control-sm text-center border-0"
                                                value="{{ $item['quantity'] }}">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0 mt-4">
                                           Rp. {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                                    </td>
                                    <td>
                                        <button class="btn btn-md rounded-circle bg-light border mt-4 btn-remove">
                                            <i class="fa fa-times text-danger"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- <div class="mt-5">
                <input type="text" class="border-0 border-bottom rounded me-5 py-3 mb-4" placeholder="Coupon Code">
                <button class="btn border-secondary rounded-pill px-4 py-3 text-primary" type="button">Apply
                    Coupon</button>
            </div> --}}
                <div class="row g-4 justify-content-end">
                    <div class="col-8"></div>
                    <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                        <div class="bg-light rounded">
                            <div class="p-4">
                                <h1 class="display-6 mb-4">Cart <span class="fw-normal">Total</span></h1>
                                <div class="d-flex justify-content-between mb-4">
                                    <h5 class="mb-0 me-4">Subtotal:</h5>
                                    <p class="mb-0" id="subtotal">
                                        Rp.
                                        {{ number_format(array_sum(array_map(function ($item) {return $item['price'] * $item['quantity'];}, $cart->toArray())),0,',','.') }}
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h5 class="mb-0 me-4">Shipping</h5>
                                    <div class="">
                                        <p class="mb-0">Mohon tunggu konfirmasi Admin</p>
                                    </div>
                                </div>

                            </div>
                            <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                                <h5 class="mb-0 ps-4 me-4">Total</h5>
                                <p class="mb-0 pe-4" id="total">
                                    Rp.
                                    {{ number_format(
                                        array_sum(
                                            array_map(function ($item) {
                                                return $item['price'] * $item['quantity'];
                                            }, $cart->toArray()),
                                        ),
                                        0,
                                        ',',
                                        '.',
                                    ) }}

                                </p>
                            </div>
                            <button
                                class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4"
                                type="button" id="place-order-button">Tempatkan Pesanan</button>
                            <form id="order-form" action="{{ route('order.store') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <h4 class="text-center">No items in the cart.</h4>
            @endif
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-plus').forEach(function(button) {
                button.addEventListener('click', function() {
                    var tr = this.closest('tr');
                    var id = tr.getAttribute('data-id');
                    var input = tr.querySelector('input');
                    var quantity = parseInt(input.value) + 1;
                    input.value = quantity;

                    updateCart(id, quantity, tr);
                });
            });

            document.querySelectorAll('.btn-minus').forEach(function(button) {
                button.addEventListener('click', function() {
                    var tr = this.closest('tr');
                    var id = tr.getAttribute('data-id');
                    var input = tr.querySelector('input');
                    var quantity = parseInt(input.value) - 1;
                    if (quantity >= 0) {
                        input.value = quantity;
                        updateCart(id, quantity, tr);
                    }
                });
            });

            document.querySelectorAll('.btn-remove').forEach(function(button) {
                button.addEventListener('click', function() {
                    Swal.fire({
                        title: 'Apakah Anda yakin ingin menghapus item ini dari keranjang?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var tr = this.closest('tr');
                            var id = tr.getAttribute('data-id');
                            removeFromCart(id);
                            tr.remove();
                            Swal.fire(
                                'Dihapus!',
                                'Item telah dihapus dari keranjang.',
                                'success'
                            )
                        }
                    });
                });
            });

            document.getElementById('place-order-button').addEventListener('click', function() {
                Swal.fire({
                    title: 'Apakah Anda yakin ingin memesan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, pesan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('order-form').submit();
                    }
                });
            });

            function updateCart(id, quantity, tr) {
                fetch('{{ route('cart.updateQuantity') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: id,
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            var price = parseInt(tr.querySelector('td:nth-child(3) p').textContent.replace(
                                /\D/g, ''));
                            var total = price * quantity;
                            tr.querySelector('td:nth-child(5) p').textContent = total.toLocaleString('id-ID') +
                                ' Rp';

                            document.getElementById('subtotal').textContent = data.total.subtotal + ' Rp';
                            document.getElementById('total').textContent = data.total.total + ' Rp';
                            document.getElementById('cartCount').textContent = data.cartCount;
                        } else {
                            alert(data.message);
                        }
                    });
            }

            function removeFromCart(id) {
                fetch('{{ route('cart.remove') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: id
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('subtotal').textContent = data.total.subtotal + ' Rp';
                            document.getElementById('total').textContent = data.total.total + ' Rp';
                            document.getElementById('cartCount').textContent = data.cartCount;
                        } else {
                            alert(data.message);
                        }
                    });
            }
        });
    </script>
@endsection

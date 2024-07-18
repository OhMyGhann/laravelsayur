@extends('welcome')

@section('content')
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Shop Detail</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Pages</a></li>
        <li class="breadcrumb-item active text-white">Shop Detail</li>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Single Product Start -->
<div class="container-fluid py-5 mt-5">
    <div class="container py-5">
        <div class="row g-4 mb-5">
            <div class="col-lg-4">
                <div class="border rounded">
                    <img src="{{ asset('storage/' . $item->image_path) }}" class="img-fluid rounded" alt="{{ $item->name }}">
                </div>
            </div>
            <div class="col-lg-6">
                <h4 class="fw-bold mb-3">{{ $item->name }}</h4>
                <p class="mb-3">Category: {{ $item->kategori }}</p>
                <h5 class="fw-bold mb-3">Rp <span id="totalPrice">{{ number_format($item->harga, 0, ',', '.') }}</span></h5>
                <div class="d-flex mb-4">
                    <i class="fa fa-star text-secondary"></i>
                    <i class="fa fa-star text-secondary"></i>
                    <i class="fa fa-star text-secondary"></i>
                    <i class="fa fa-star text-secondary"></i>
                    <i class="fa fa-star"></i>
                </div>
                <p class="mb-4">{{ $item->note }}</p>
                <div class="input-group quantity mb-5" style="width: 100px;">
                    <div class="input-group-btn">
                        <button class="btn btn-sm btn-minus rounded-circle bg-light border">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <input type="text" id="quantity" class="form-control form-control-sm text-center border-0" value="1">
                    <div class="input-group-btn">
                        <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <a href="#" id="addToCartButton" class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary">
                    <i class="fa fa-shopping-bag me-2 text-primary"></i> Masukan ke keranjang
                </a>
            </div>

            <div class="col-lg-12">
                <nav>
                    <div class="nav nav-tabs mb-3">
                        <button class="nav-link active border-white border-bottom-0" type="button" role="tab"
                            id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                            aria-controls="nav-about" aria-selected="true">Keterangan</button>
                        <button class="nav-link border-white border-bottom-0" type="button" role="tab"
                            id="nav-mission-tab" data-bs-toggle="tab" data-bs-target="#nav-mission"
                            aria-controls="nav-mission" aria-selected="false">Ulasan</button>
                    </div>
                </nav>
                <div class="tab-content mb-5">
                    <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                        <p>{{ $item->note }}</p>
                    </div>
                    <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">
                        <!-- Reviews Section -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Single Product End -->

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const price = {{ $item->harga }};
        const quantityInput = document.getElementById('quantity');
        const totalPriceElement = document.getElementById('totalPrice');
        const addToCartButton = document.getElementById('addToCartButton');
        const cartCountElement = document.getElementById('cartCount');

        let cartCount = 0;

        document.querySelector('.btn-plus').addEventListener('click', () => {
            let quantity = parseInt(quantityInput.value);
            quantity++;
            quantityInput.value = quantity;
            updateTotalPrice(quantity, price);
        });

        document.querySelector('.btn-minus').addEventListener('click', () => {
            let quantity = parseInt(quantityInput.value);
            if (quantity > 1) {
                quantity--;
                quantityInput.value = quantity;
                updateTotalPrice(quantity, price);
            }
        });

        addToCartButton.addEventListener('click', (event) => {
            event.preventDefault();
            const item = {
                id: '{{ $item->id }}',
                name: '{{ $item->name }}',
                price: price,
                quantity: parseInt(quantityInput.value)
            };
            addToCart(item);
        });

        const addToCart = (item) => {
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(item)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cartCountElement.textContent = data.cartCount;
                    alert('Item telah ditambahkan ke keranjang.');
                } else {
                    alert('Gagal menambahkan item ke keranjang.');
                }
            })
            .catch(error => console.error('Error:', error));
        };

        const updateTotalPrice = (quantity, price) => {
            const totalPrice = quantity * price;
            totalPriceElement.textContent = totalPrice.toLocaleString('id-ID');
        };
    });
</script>
@endsection

@extends('welcome')

@section('content')
    <div class="container-fluid fruite py-5">
        <div class="container py-5">
            <h1 class="mb-4">Buah & Sayur Segar</h1>
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="row g-4">
                        <div class="col-xl-3">
                            <div class="input-group w-100 mx-auto d-flex">
                                <form action="{{ route('shop.index') }}" method="GET" class="d-flex w-100">
                                    <input type="search" name="search" class="form-control p-3" placeholder="keywords"
                                        aria-describedby="search-icon-1" value="{{ request()->input('search') }}">
                                    <button type="submit" class="input-group-text p-3" id="search-icon-1"><i
                                            class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="col-6"></div>
                        <div class="col-xl-3">
                            <div class="bg-light ps-3 py-3 rounded d-flex justify-content-between mb-4">
                                <label for="fruits">Default Sorting:</label>
                                <select id="fruits" name="fruitlist" class="border-0 form-select-sm bg-light me-3"
                                    form="fruitform">
                                    <option value="volvo">Nothing</option>
                                    <option value="saab">Popularity</option>
                                    <option value="opel">Organic</option>
                                    <option value="audi">Fantastic</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-lg-3">
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <h4>Kategori</h4>
                                        <ul class="list-unstyled fruite-categorie">
                                            @foreach ($categories as $category)
                                                <li>
                                                    <div class="d-flex justify-content-between fruite-name">
                                                        <a
                                                            href="{{ route('shop.index', ['kategori' => $category->kategori]) }}">
                                                            @if ($category->kategori == 'Sayur')
                                                                <i class="fas fa-carrot me-2"></i>{{ $category->kategori }}
                                                            @else
                                                                <i
                                                                    class="fas fa-apple-alt me-2"></i>{{ $category->kategori }}
                                                            @endif
                                                        </a>
                                                        <span>({{ \App\Models\Item::where('kategori', $category->kategori)->count() }})</span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-12">
                                <div class="mb-3">
                                    <h4 class="mb-2">Price</h4>
                                    <input type="range" class="form-range w-100" id="rangeInput" name="rangeInput" min="0" max="500" value="0" oninput="amount.value=rangeInput.value">
                                    <output id="amount" name="amount" min-value="0" max-value="500" for="rangeInput">0</output>
                                </div>
                            </div> --}}
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="row g-4 justify-content-center">
                                @foreach ($items as $item)
                                    <div class="col-6 col-md-6 col-lg-6 col-xl-4">
                                        <div class="rounded position-relative fruite-item">
                                            <div class="fruite-img">
                                                <img src="{{ asset('storage/' . $item->image_path) }}"
                                                    class="img-fluid w-100 rounded-top" alt="{{ $item->name }}">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                style="top: 10px; left: 10px;">{{ $item->kategori }}</div>
                                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                <h4>{{ $item->name }}</h4>
                                                <p>{{ $item->description }}</p>
                                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                    <p class="text-dark fs-6 fw-bold mb-0">Rp
                                                        {{ number_format($item->harga, 0, ',', '.') }} / kg</p>
                                                    <a href="#"
                                                        class="btn border border-secondary rounded-pill px-2 py-1 mb-4 text-primary small-btn addToCartButton"
                                                        data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                                        data-price="{{ $item->harga }}">
                                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Masukan ke
                                                        keranjang
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-12">
                                <div class="pagination d-flex justify-content-center mt-5">
                                    {{ $items->links('vendor.pagination.custom') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const cartCountElement = document.getElementById('cartCount');

            document.querySelectorAll('.addToCartButton').forEach(button => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    const item = {
                        id: button.getAttribute('data-id'),
                        name: button.getAttribute('data-name'),
                        price: button.getAttribute('data-price'),
                        quantity: 1 // Default quantity to 1
                    };
                    addToCart(item);
                });
            });

            const addToCart = (item) => {
                @if (auth()->check())
                    fetch('{{ route('cart.add') }}', {
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
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Item telah ditambahkan ke keranjang.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Gagal menambahkan item ke keranjang.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                @else
                    Swal.fire({
                        title: 'Perhatian!',
                        text: 'Silahkan login terlebih dahulu.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                @endif
            };
        });
    </script>

<style>
    .small-btn {
        font-size: 0.875rem; /* Adjust button text size */
        padding: 0.5rem 1rem; /* Adjust button padding */
    }
    @media (max-width: 768px) {
        .col-6 {
            max-width: 50%;
            flex: 0 0 50%;
        }
        .fruite-item .text-dark.fs-6.fw-bold.mb-0 {
            font-size: 0.875rem; /* Adjust price text size */
            margin-bottom: 0.5rem; /* Adjust bottom margin */
        }
        .fruite-item .btn {
            padding: 0.25rem 0.5rem; /* Adjust button padding */
            font-size: 0.75rem; /* Adjust button font size */
        }
        .fruite-item .d-flex {
            flex-direction: column; /* Align price and button vertically */
            align-items: flex-start; /* Align items to the start */
        }
    }
</style>
@endsection

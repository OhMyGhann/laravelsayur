@extends('welcome')

@section('content')
    <div class="container-fluid py-5 mb-5 hero-header">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-md-12 col-lg-7">
                    <h4 class="mb-3 text-secondary">100% Makanan Organik </h4>
                    <h1 class="mb-5 display-3 text-primary">Makanan Sayuran & Buah Organik</h1>
                </div>
                <div class="col-md-12 col-lg-5">
                    <div id="carouselId" class="carousel slide position-relative" data-bs-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            @foreach ($sliders as $item)
                                <div class="carousel-item rounded {{ $loop->first ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $item->image_path) }}"
                                        class="img-fluid w-100 h-100 bg-secondary rounded" alt="{{ $item->title }}">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselId"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselId"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Fruits Shop Start-->
    <div class="container-fluid fruite py-5">
        <div class="container py-5">
            <div class="tab-class text-center">
                <div class="row g-4">
                    <div class="col-lg-4 text-start">
                        <h1>Produk Kami</h1>
                    </div>
                    <div class="col-lg-8 text-end">
                        <ul class="nav nav-pills d-inline-flex text-center mb-5">
                            <li class="nav-item">
                                <a class="d-flex m-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill"
                                    href="#tab-1">
                                    <span class="text-dark" style="width: 130px;">Semua Produk</span>
                                </a>
                            </li>
                            <!-- Tambahkan kategori lain di sini -->
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4">
                            @foreach ($items as $item)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="rounded position-relative fruite-item h-100">
                                        <div class="fruite-img">
                                            <a href="{{ route('product.show', $item->id) }}">
                                                <img src="{{ asset('storage/' . $item->image_path) }}"
                                                    class="img-fluid w-100 rounded-top" alt="{{ $item->name }}">
                                            </a>
                                        </div>
                                        <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                            style="top: 10px; left: 10px;">{{ $item->kategori }}</div>
                                        <div
                                            class="p-4 border border-secondary border-top-0 rounded-bottom h-100 d-flex flex-column justify-content-between">
                                            <div>
                                                <h4>{{ $item->name }}</h4>
                                                <p>{{ $item->note }}</p>
                                            </div>
                                            <div class="d-flex justify-content-between flex-lg-wrap mt-3 align-items-center">
                                                <p class="text-dark fs-6 fw-bold mb-0">Rp
                                                    {{ number_format($item->harga, 0, ',', '.') }} / {{ $item->satuan }}
                                                </p>
                                                <a href="#"
                                                    class="btn border border-secondary rounded-pill px-2 py-1 text-primary small-btn"><i
                                                        class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Tambahkan tab lain di sini -->
                </div>
            </div>
        </div>
    </div>
    <!-- Fruits Shop End-->

    <!-- Banner Section Start-->
    <div class="container-fluid banner bg-secondary my-5">
        <div class="container py-5">
            <div class="row g-4 align-items-center">
                @foreach ($banners as $banner)
                    <div class="col-lg-6">
                        <div class="py-4">
                            <h1 class="display-3 text-white">{{ $banner->title }}</h1>
                            <p class="fw-normal display-3 text-dark mb-4">{{ $banner->sub_title }}</p>
                            <p class="mb-4 text-dark">{{ $banner->note }}</p>
                            <a href="#"
                                class="banner-btn btn border-2 border-white rounded-pill text-dark py-3 px-5">BELI</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $banner->image_path) }}" class="img-fluid w-100 rounded"
                                alt="">
                            <div class="d-flex align-items-center justify-content-center bg-white rounded-circle position-absolute"
                                style="width: 140px; height: 140px; top: 0; left: 0;">
                                <div class="d-flex flex-column">
                                    <span class="h2 mb-0">Rp.{{ number_format($item->harga, 0, ',', '.') }} </span>
                                    <span class="h4 text-muted mb-0"> / {{ $item->satuan }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Banner Section End -->

    <!-- Fact Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="bg-light p-5 rounded">
                <div class="row g-4 justify-content-center">
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="counter bg-white rounded p-5">
                            <i class="fa fa-users text-secondary"></i>
                            <h4>PELANGGAN YANG PUAS</h4>
                            <h1>{{ $totalOrders }}</h1>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="counter bg-white rounded p-5">
                            <i class="fa fa-users text-secondary"></i>
                            <h4>PRODUK YANG TERSEDIA</h4>
                            <h1>{{$totalItems}}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fact End -->

    <style>
        .small-btn {
            font-size: 0.875rem; /* Adjust button text size */
            padding: 0.5rem 1rem; /* Adjust button padding */
        }
        @media (max-width: 768px) {
            .fruite-item .text-dark.fs-5.fw-bold.mb-0 {
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

 

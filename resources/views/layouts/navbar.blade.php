<div class="container-fluid fixed-top">
    <div class="container px-0">
        <nav class="navbar navbar-light bg-white navbar-expand-xl">
            <a href="{{ route('home') }}" class="navbar-brand">
                @foreach ($settings as $setting)
                    @if ($setting && $setting->logo_1)
                        <img src="{{ asset('storage/' . $setting->logo_1) }}" alt="Logo" 
                            style="height: 100px;">
                    @endif
                @endforeach
            </a>
            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-primary"></span>
            </button>
            <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="{{ route('home') }}"
                        class="nav-item nav-link {{ Request::is('/') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('shop.index') }}"
                        class="nav-item nav-link {{ Request::is('shop*') ? 'active' : '' }}">Shop</a>

                    @if (Auth::check())
                        <a href="{{ route('order.index') }}"
                            class="nav-item nav-link {{ Request::is('orders*') ? 'active' : '' }}">Order</a>
                    @endif

                    {{-- <a href="{{ asset('contact') }}"
                        class="nav-item nav-link {{ Request::is('contact') ? 'active' : '' }}">Kontak</a> --}}
                </div>
                <div class="d-flex m-3 me-0">
                    <a href="#" class="position-relative me-4 my-auto">
                        <button
                            class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4"
                            data-bs-toggle="modal" data-bs-target="#searchModal"><i
                                class="fas fa-search text-primary"></i></button>
                    </a>
                    <a href="{{ route('cart') }}" class="position-relative me-4 my-auto">
                        <i class="fa fa-shopping-bag fa-2x"></i>
                        <span id="cartCount"
                            class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1"
                            style="top: -5px; left: 15px; height: 20px; min-width: 20px;">
                            {{ \App\Models\KeranjangBelanja::where('user_id', Auth::id())->count() }}
                        </span>
                    </a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-user fa-2x"></i>
                        </a>
                        <div class="dropdown-menu m-0 bg-secondary rounded-0">
                            @if (Auth::check())
                                <p class="dropdown-item">{{ Auth::user()->name }}</p>
                                <a href="/app" class="dropdown-item">Dashboard</a>
                                <a href="#" class="dropdown-item"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="dropdown-item">Login</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>

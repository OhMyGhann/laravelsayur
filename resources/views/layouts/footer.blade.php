<div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
    <div class="container py-5">
        <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
            <div class="row g-4">
                <div class="col-lg-3">
                    <a href="#">
                        @foreach ($settings as $setting)
                            @if ($setting && $setting->logo_1)
                                <img src="{{ asset('storage/' . $setting->logo_1) }}" alt="Logo" class="img-fluid"
                                    style="height: 200px;">
                            @else
                                <h1 class="text-primary mb-0">Fruitables</h1>
                                <p class="text-secondary mb-0">Fresh products</p>
                            @endif
                        @endforeach
                    </a>
                </div>
                <div class="col-lg-6">
                    <!-- Konten tambahan jika diperlukan -->
                </div>

            </div>
        </div>
        <div class="row g-5">
            <div class="col-lg-6 col-md-12">
                <div class="footer-item">
                    <h4 class="text-light mb-3">Tentang Kami</h4>
                    <p class="mb-4">{{ $setting->deskripsi_web }}</p>
                </div>
            </div>



            <div class="col-lg-3 col-md-6">
                <div class="footer-item">
                    <h4 class="text-light mb-3">Kontak</h4>
                    @foreach (json_decode($setting->social_media) as $key => $social)
                        <p>{{ $key }}: {{ $social }}</p>
                    @endforeach
                    <p>Phone: {{ $setting->phone ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Copyright Start -->
<div class="container-fluid copyright bg-dark py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>Syntech</a>,
                    All right reserved.</span>
            </div>
        </div>
    </div>
</div>
<!-- Copyright End -->

<!-- Back to Top -->
<a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i
        class="fa fa-arrow-up"></i></a>

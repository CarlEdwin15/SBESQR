@extends('./layouts.main')

@section('title', 'SBESqr')

@section('content')

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Header -->
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="#" class="logo d-flex align-items-center me-auto" data-aos="fade-up" data-aos-delay="200">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="{{ asset('assets/img/logo.png') }}" style="height: 60px; width: 45px" alt="">
                <h1 style="color:#0088ff">SBESqr</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" data-aos="fade-up" data-aos-delay="100" class="active">Home</a></li>
                    <li><a href="#about" data-aos="fade-up" data-aos-delay="100">About</a></li>
                    <li><a href="#announcement-section" id="announcement-nav" data-aos="fade-up" data-aos-delay="100"
                            style="display: none;">Announcements</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/home') }}" class="cta-btn-login" data-aos="fade-up" data-aos-delay="100">
                        Dashboard
                    </a>
                @else
                    <a class="cta-btn-login" data-aos="fade-up" data-aos-delay="100" href="{{ route('login') }}">Login</a>

                    {{-- @if (Route::has('register'))
                        <a class="cta-btn" href="{{ route('register') }}">Register</a>
                    @endif --}}
                @endauth
                </nav>
            @endif
        </div>
    </header>
    <!-- /Header -->

    <main class="main">

        <!-- Home Section -->
        <section id="hero" class="hero section dark-background">

            <img src="{{ asset('assets/img/hero-bg.jpg') }}" alt="" data-aos="fade-in">

            <div class="container d-flex flex-column align-items-center">
                <h2 class="text-white" style="text-align: center" data-aos="fade-up" data-aos-delay="100">WELCOME TO
                    <span class="highlight">SBESqr</span>
                </h2>
                <p class="tagline text-center" style="color: rgb(255, 200, 0)" data-aos="fade-up" data-aos-delay="200">
                    Bringing Efficiency with Technology to
                    Sta. Barbara
                    Elementary School</p>
                <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
                    <a href="#about" class="btn-get-started">Get Started</a>
                </div>
            </div>
        </section>
        <!-- /Home Section -->

        <!-- About Section -->
        <section id="about" class="about section">
            <div class="container">
                <div class="row gy-4">
                    <div style="text-align: justify" class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <h3 class="schoolname">Sta. Barbara, Nabua, Elementary School</h3>
                        <img src="{{ asset('assets/img/logo.png') }}" class="img-fluid rounded-4 mb-4 floating-logo">
                        <p><strong>SBESqr: Student Information and Attendance Management System</strong>
                            is an advanced digital solution designed for
                            <strong>Sta. Barbara, Nabua, Elementary School.</strong>
                        </p>
                        <p>This system simplifies student record management, automates attendance tracking, and
                            facilitates ID generation using QR code technology.</p>
                    </div>
                    <div style="text-align: justify" class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
                        <div class="content ps-0 ps-lg-5">
                            <p class="fst-italic key-features">
                                Key Features
                            </p>
                            <ul>
                                <li><i class="bi bi-check-circle-fill"></i> <span><strong>Enhanced Student Information
                                            and Attendance Management.</strong></span></li>
                                <li><i class="bi bi-check-circle-fill"></i> <span><strong>QR code-based attendance
                                            tracking.</strong></span></li>
                                <li><i class="bi bi-check-circle-fill"></i> <span><strong>User-friendly interface for
                                            school administrators and teachers.</strong></span></li>
                            </ul>
                            <p>
                                Our mission is to improve the efficiency of student record management and attendance
                                monitoring at
                                <strong>Sta. Barbara, Nabua, Elementary School</strong>. By utilizing modern technology,
                                we aim to minimize manual workload, enhance accuracy, and provide a seamless experience
                                for both educators and students.
                            </p>

                            <div class="position-relative mt-4">
                                <img src="{{ asset('assets/img/about-2.jpg') }}" class="img-fluid rounded-4" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /About Section -->

        <!-- Announcement Section -->
        <section id="announcement-section" class="testimonials section dark-background" style="display: none;">
            <img src="{{ asset('assets/img/testimonials-bg.jpg') }}" class="testimonials-bg"
                alt="announcement-section-bg" />

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <h1 class="announcement-title text-warning text-center mb-5">Announcements</h1>
                <div class="swiper init-swiper">
                    <script type="application/json" class="swiper-config">
                {
                    "loop": true,
                    "speed": 600,
                    "autoplay": {
                        "delay": 5000
                    },
                    "slidesPerView": "auto",
                    "pagination": {
                        "el": ".swiper-pagination",
                        "type": "bullets",
                        "clickable": true
                    }
                }
            </script>

                    <div class="swiper-wrapper">
                        @forelse($announcements as $announcement)
                            <div class="swiper-slide">
                                <div class="testimonial-item">
                                    {{-- <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img"
                                        alt="" />
                                    <h3>Saul Goodman</h3>
                                    <h4>Ceo &amp; Founder</h4> --}}
                                    <div class="text-end">{{ $announcement->date_published?->format('M d, Y | l | h:i A') }}</div>
                                    <h3 class="text-black text-center announcement-title">{{ $announcement->title }}</h3>
                                    <div class="quill-content announcement-body">
                                        {!! $announcement->body !!}
                                    </div>
                                    <div class="text-start"> ~ {{ $announcement->user->name ?? 'Admin' }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="swiper-slide">
                                <div class="testimonial-item text-center">
                                    <p>No announcements yet.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>
        <!-- /Announcement Section -->


    </main>

    <footer id="footer" class="footer dark-background">
        <div class="container copyright text-center mt-4">
            <p>© <strong class="px-1 sitename">SBESqr</strong> <span>All Rights Reserved</span>
            </p>
            <div class="credits">
            </div>
        </div>

    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

@endsection

@push('styles')
    <!-- Favicons -->
    <link href="{{ asset('assets/img/logo.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">

    <!-- Custom CSS File -->
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

    <style>
        /* Center slides */
        #announcement-section .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Announcement card */
        #announcement-section .testimonial-item {
            width: 100%;
            max-width: 1200px;
            /* fixed card width */
            min-height: 450px;
            /* minimum height */
            max-height: 600px;
            /* maximum height */
            padding: 25px;
            background: rgba(255, 255, 255, 0.7);
            /* darker for better readability */
            border-radius: 16px;
            color: #000000;
            text-align: left;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }

        /* Quill body area */
        #announcement-section .announcement-body {
            flex: 1;
            /* take available height */
            overflow-y: auto;
            word-wrap: break-word;
            overflow-wrap: break-word;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        /* Ensure images inside body are responsive */
        #announcement-section .announcement-body img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 10px auto;
            border-radius: 8px;
        }

        /* Title styling */
        #announcement-section h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        /* Footer (date/author) pinned at bottom */
        #announcement-section .testimonial-item .text-end {
            margin-top: auto;
            font-size: 0.85rem;
            color: #000000;
        }

        .announcement-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            /* bold */
            letter-spacing: 1px;
            text-transform: uppercase;
        }
    </style>
@endpush

@push('scripts')
    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

    <!-- Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
@endpush

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>SBESqr</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/logo.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
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

</head>

<body class="index-page">

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
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/home') }}" class="cta-btn-login">
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

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section dark-background">

            <img src="{{ asset('assets/img/hero-bg.jpg') }}" alt="" data-aos="fade-in">

            <div class="container d-flex flex-column align-items-center">
                <h2 style="text-align: center" data-aos="fade-up" data-aos-delay="100">WELCOME TO <span
                        class="highlight">SBESqr</span></h2>
                <p class="tagline" style="color: rgb(255, 200, 0)" data-aos="fade-up" data-aos-delay="200">Bringing Efficiency with QR Technology to
                    Sta. Barbara
                    Elementary School</p>
                <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
                    <a href="#about" class="btn-get-started">Get Started</a>
                </div>
            </div>


        </section><!-- /Hero Section -->

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
                                <img src="{{ asset('assets/img/about-2.jpg') }}" class="img-fluid rounded-4"
                                    alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /About Section -->

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

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

</body>

</html>

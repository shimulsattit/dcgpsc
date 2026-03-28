<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $headerSetting = \App\Models\HeaderSetting::first();
        $siteTitle = $headerSetting?->site_name ?? env('APP_NAME', 'Laragon');
    @endphp
    <title>@yield('title', $siteTitle)</title>

    <!-- Dynamic Favicon -->
    @if($headerSetting)
        <link rel="icon" type="image/png" href="{{ $headerSetting->favicon ?? asset('images/logo.png') }}">
    @else
        <link rel="icon" href="{{ asset('images/logo.png') }}">
    @endif

    <!-- Performance Optimization: Preconnect to CDNs -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts for Bengali Support -->
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&family=Hind+Siliguri:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Popup Modal CSS -->
    <link rel="stylesheet" href="{{ asset('css/popup-modal.css') }}">

    <!-- Dynamic Theme Colors -->
    @php
        $theme = \App\Models\ThemeSetting::first();
    @endphp
    <style>
        :root {
            --primary-color:
                {{ $theme->primary_color ?? '#006a4e' }}
            ;
            --secondary-color:
                {{ $theme->secondary_color ?? '#f42a41' }}
            ;
            --accent-color:
                {{ $theme->accent_color ?? '#f8f9fa' }}
            ;
            --text-color:
                {{ $theme->text_color ?? '#333333' }}
            ;
            --link-color:
                {{ $theme->link_color ?? '#006a4e' }}
            ;
            --topbar-bg-color:
                {{ $theme->topbar_bg_color ?? '#5a9a7a' }}
            ;
            --header-bg-color:
                {{ $theme->header_bg_color ?? '#2d6a4f' }}
            ;
            --navbar-bg-color:
                {{ $theme->navbar_bg_color ?? '#1e5540' }}
            ;
            --navbar-hover-color:
                {{ $theme->navbar_hover_color ?? 'rgba(255,255,255,0.15)' }}
            ;
            --footer-bg-color:
                {{ $theme->footer_bg_color ?? '#2c3e50' }}
            ;
            --footer-bottom-bg-color:
                {{ $theme->footer_bottom_bg_color ?? '#1a252f' }}
            ;
            --body-bg-color:
                {{ $theme->body_bg_color ?? '#f4f6f9' }}
            ;
            --ticker-bg-color:
                {{ $theme->ticker_bg_color ?? '#006a4e' }}
            ;
            --ticker-text-color:
                {{ $theme->ticker_text_color ?? '#ffffff' }}
            ;
        }

        body {
            font-family: 'Noto Sans Bengali', 'Hind Siliguri', 'Arial', sans-serif;
            background-color: var(--body-bg-color);
            color: var(--text-color);
            overflow-x: hidden;
        }

        html {
            overflow-x: hidden;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        a {
            color: var(--link-color);
            transition: all 0.3s ease;
        }

        a:hover {
            transform: translateX(3px);
        }

        .bg-primary-custom {
            background-color: var(--primary-color) !important;
            color: white;
        }

        .text-primary-custom {
            color: var(--primary-color) !important;
        }

        .top-bar {
            background-color: var(--topbar-bg-color);
            font-size: 0.85rem;
            padding: 8px 0;
            color: white;
        }

        .top-bar a {
            color: white !important;
            text-decoration: none;
        }

        .main-header {
            background: var(--header-bg-color) !important;
            padding: 30px 0 !important;
            border-bottom: none !important;
        }

        .navbar-custom {
            background-color: var(--navbar-bg-color) !important;
            padding: 0.25rem 0 !important;
            min-height: 50px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .navbar-custom .navbar-nav {
            flex-wrap: wrap;
        }

        .navbar-custom .nav-link {
            color: white !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 6px 12px !important;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-custom .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(to right, var(--navbar-hover-color), transparent);
            transition: width 0.3s ease;
            z-index: -1;
        }

        .navbar-custom .nav-link:hover::before {
            width: 100%;
        }

        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link:focus {
            background-color: transparent !important;
            color: #ffffff !important;
            font-weight: 600;
        }

        .navbar-custom .dropdown-menu {
            background-color: var(--navbar-bg-color);
            border: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            animation: slideDown 0.3s ease;
            margin-top: 0;
            border-top: 3px solid var(--secondary-color);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .navbar-custom .dropdown-item {
            color: white !important;
            font-size: 0.85rem;
            padding: 8px 18px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .navbar-custom .dropdown-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(to bottom, var(--secondary-color), var(--primary-color));
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .navbar-custom .dropdown-item:hover {
            background: linear-gradient(to right, rgba(255, 255, 255, 0.1), transparent) !important;
            padding-left: 23px;
            color: #ffffff !important;
            font-weight: 600;
        }

        .navbar-custom .dropdown-item:hover::before {
            transform: scaleY(1);
        }

        /* Message Card Image Alignment Fix */
        .message-card-img-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1.5rem;
            width: 100%;
        }

        .message-card-img {
            width: 200px;
            height: 250px;
            object-fit: cover;
            border-radius: 12px;
            border: 3px solid white;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.6);
            transition: all 0.3s ease;
        }

        .message-card-img:hover {
            transform: scale(1.05);
        }

        /* Slider Image Height and Fit Fix */
        .slider-img {
            height: 550px;
            object-fit: cover;
            object-position: center top;
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .slider-img {
                height: 250px;
            }
        }

        /* Hover Dropdown - Show on hover instead of click */
        .navbar-custom .nav-item.dropdown:hover .dropdown-menu {
            display: block;
            animation: slideDown 0.3s ease;
        }

        .navbar-custom .dropdown-menu {
            display: none;
            transition: all 0.3s ease;
        }

        .navbar-custom .dropdown-menu.show {
            display: block;
        }

        /* Mobile Toggle Button - White Color */
        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.5) !important;
            padding: 0.25rem 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
        }

        .footer {
            background-color: var(--footer-bg-color);
            color: white;
            padding: 40px 0 0;
        }

        .footer-bottom {
            background-color: var(--footer-bottom-bg-color);
            padding: 15px 0;
        }

        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Card Hover Effects */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
        }

        /* Button Hover Effects */
        .btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        /* Fade In Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease;
        }

        /* Carousel Caption Animation */
        .carousel-caption {
            animation: fadeIn 0.8s ease;
        }

        /* News Ticker Modern Scroller */
        .ticker-container {
            overflow: hidden;
            width: 100%;
            position: relative;
            display: flex;
            align-items: center;
        }

        .ticker-wrapper {
            display: flex;
            white-space: nowrap;
            padding-left: 100%; /* Start from far right */
            animation: tickerScroll 30s linear infinite;
        }

        .ticker-wrapper:hover {
            animation-play-state: paused;
        }

        .ticker-item {
            display: inline-block;
            padding-right: 50px;
        }

        @keyframes tickerScroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .main-header {
                padding: 10px 0 !important;
                text-align: center;
            }

            .main-header h3 {
                font-size: 1.2rem !important;
                margin-bottom: 2px !important;
            }

            .main-header h5 {
                font-size: 1rem !important;
                margin-bottom: 2px !important;
            }

            .main-header p {
                font-size: 0.8rem !important;
            }

            .main-header img {
                max-height: 60px !important;
                margin-bottom: 5px;
            }

            .main-header .col-md-8 {
                text-align: center !important;
            }

            /* Navbar */
            .navbar-custom .navbar-nav {
                padding: 10px;
            }

            .navbar-custom .nav-link {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                padding: 10px !important;
            }

            .navbar-custom .dropdown-menu {
                border: none;
                background-color: rgba(0, 0, 0, 0.1);
                box-shadow: none;
                padding-left: 20px;
            }

            /* Smooth Mobile Collapse */
            .navbar-collapse {
                transition: height 0.4s ease-in-out;
            }

            .navbar-collapse.show {
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                margin-top: 10px;
            }

            /* Slider */
            #heroCarousel .carousel-item img {
                height: auto !important;
                min-height: 200px;
                object-fit: cover !important;
            }

            /* Sidebar */
            .col-lg-3 {
                margin-top: 2rem;
            }

            /* Prevent horizontal scroll */
            .row {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .container {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

            .main-header .row {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .main-header h3,
            .main-header h5 {
                word-wrap: break-word;
                overflow-wrap: break-word;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    @php
        // Get the current homepage template setting
        $currentTemplate = \App\Models\ThemeSetting::first()->homepage_template ?? 'template_1';
    @endphp

    @if($currentTemplate === 'template_1')
        @include('partials.header-template1')
    @elseif($currentTemplate === 'template_2')
        @include('partials.header-template2')
    @elseif($currentTemplate === 'template_3')
        @include('partials.header-template3')
    @else
        @include('partials.header')
    @endif

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Carousel Auto-play Fix (Bootstrap 5.2+ requires explicit initialization) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-initialize all carousels and force cycle/autoplay on page load
            document.querySelectorAll('.carousel').forEach(function (carouselEl) {
                var interval = parseInt(carouselEl.getAttribute('data-bs-interval')) || 5000;
                var carousel = new bootstrap.Carousel(carouselEl, {
                    interval: interval,
                    ride: true,
                    pause: 'hover',
                    wrap: true
                });
                carousel.cycle();
            });
        });
    </script>

    <!-- Hover Dropdown Script -->
    <script>
        // Enable hover dropdown for desktop, keep click for mobile
        document.addEventListener('DOMContentLoaded', function () {
            const dropdowns = document.querySelectorAll('.navbar-custom .nav-item.dropdown');

            dropdowns.forEach(function (dropdown) {
                const dropdownToggle = dropdown.querySelector('.dropdown-toggle');
                const dropdownMenu = dropdown.querySelector('.dropdown-menu');

                // For desktop: show on hover
                if (window.innerWidth > 992) {
                    dropdown.addEventListener('mouseenter', function () {
                        dropdownMenu.classList.add('show');
                    });

                    dropdown.addEventListener('mouseleave', function () {
                        dropdownMenu.classList.remove('show');
                    });

                    // Prevent click toggle on desktop
                    dropdownToggle.addEventListener('click', function (e) {
                        e.preventDefault();
                        // Allow navigation if dropdown has href
                        if (dropdownToggle.getAttribute('href') && dropdownToggle.getAttribute('href') !== '#') {
                            window.location.href = dropdownToggle.getAttribute('href');
                        }
                    });
                }
            });
        });
    </script>

    <!-- Popup Modal Script -->
    <script src="{{ asset('js/popup-modal.js') }}" defer></script>

    @stack('scripts')

    {{-- Popup Modal Component --}}
    @php
        $popup = \App\Models\Popup::getActivePopup(request()->is('/') ? 'homepage' : 'all_pages');
    @endphp
    <x-popup-modal :popup="$popup" />
</body>

</html>
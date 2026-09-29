<!DOCTYPE html>
<html class="loading" lang="tr" data-textdirection="ltr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - EtnoCity Otel</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('vendor/vuexy/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/vuexy/app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/vuexy/app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/vuexy/app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/vuexy/app-assets/css/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/vuexy/app-assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/vuexy/app-assets/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/vuexy/app-assets/css/themes/bordered-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/vuexy/app-assets/css/themes/semi-dark-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/vuexy/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/vuexy/assets/css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" rel="stylesheet">
    @stack('styles')
</head>
<body class="vertical-layout vertical-menu-modern navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="">
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
                </ul>
                <h4 class="mb-0 d-none d-md-block">@yield('title', 'Dashboard')</h4>
            </div>
            <ul class="nav navbar-nav align-items-center ms-auto">
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon" data-feather="moon"></i></a></li>
                <li class="nav-item dropdown dropdown-user">
                    <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown">
                        <div class="user-nav d-sm-flex d-none">
                            <span class="user-name fw-bolder">{{ auth()->user()->name }}</span>
                            <span class="user-status">Yönetici</span>
                        </div>
                        <span class="avatar"><span class="avatar-content">{{ mb_substr(auth()->user()->name, 0, 1) }}</span><span class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button class="dropdown-item" type="submit"><i class="me-50" data-feather="power"></i> Çıkış</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item me-auto">
                    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                        <span class="brand-logo"><i data-feather="sun" class="text-primary"></i></span>
                        <h2 class="brand-text text-primary">EtnoCity</h2>
                    </a>
                </li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4 text-primary" data-feather="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.dashboard') }}"><i data-feather="home"></i><span class="menu-title text-truncate">Dashboard</span></a></li>
                <li class="navigation-header"><span>Otel Yönetimi</span><i data-feather="more-horizontal"></i></li>
                <li class="nav-item disabled"><a class="d-flex align-items-center" href="#"><i data-feather="layers"></i><span class="menu-title text-truncate">Odalar</span><span class="badge badge-light-secondary rounded-pill ms-auto">Yakında</span></a></li>
                <li class="nav-item disabled"><a class="d-flex align-items-center" href="#"><i data-feather="calendar"></i><span class="menu-title text-truncate">Rezervasyonlar</span></a></li>
                <li class="nav-item disabled"><a class="d-flex align-items-center" href="#"><i data-feather="dollar-sign"></i><span class="menu-title text-truncate">Fiyatlar</span></a></li>
                <li class="navigation-header"><span>İçerik Yönetimi</span><i data-feather="more-horizontal"></i></li>

                {{-- Anasayfa açılır kapanır --}}
                <li class="nav-item has-sub {{ request()->routeIs('admin.hero-slides.*', 'admin.home-about.*', 'admin.amenities.*') ? 'open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="layout"></i>
                        <span class="menu-title text-truncate">Anasayfa</span>
                    </a>
                    <ul class="menu-content">
                        <li class="{{ request()->routeIs('admin.hero-slides.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.hero-slides.index') }}">
                                <i data-feather="monitor"></i>
                                <span class="menu-item text-truncate">Hero Slider</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.home-about.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.home-about.edit') }}">
                                <i data-feather="info"></i>
                                <span class="menu-item text-truncate">Otel Tanıtımı</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.amenities.*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.amenities.index') }}">
                                <i data-feather="check-square"></i>
                                <span class="menu-item text-truncate">Sessiz Olanaklar</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('admin.events.index') }}">
                        <i data-feather="compass"></i>
                        <span class="menu-title text-truncate">Etkinlikler</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('admin.gallery.index') }}">
                        <i data-feather="image"></i>
                        <span class="menu-title text-truncate">Galeri</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('admin.testimonials.index') }}">
                        <i data-feather="message-square"></i>
                        <span class="menu-title text-truncate">Yorumlar</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.nearby-places.*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('admin.nearby-places.index') }}">
                        <i data-feather="map-pin"></i>
                        <span class="menu-title text-truncate">Harita & Yakın Yerler</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.site-settings.*') ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.site-settings.edit') }}"><i data-feather="settings"></i><span class="menu-title text-truncate">Site Ayarları</span></a></li>
                <li class="navigation-header"><span>Site</span><i data-feather="more-horizontal"></i></li>
                <li class="nav-item"><a class="d-flex align-items-center" href="{{ route('home') }}" target="_blank"><i data-feather="external-link"></i><span class="menu-title text-truncate">Siteyi Görüntüle</span></a></li>
            </ul>
        </div>
    </div>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="alert-body">{{ session('success') }}</div>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Kapat"></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    <footer class="footer footer-static footer-light"><p class="clearfix mb-0"><span class="float-md-start d-block d-md-inline-block mt-25">EtnoCity Otel Yönetim Paneli</span></p></footer>
    <script src="{{ asset('vendor/vuexy/app-assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('vendor/vuexy/app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('vendor/vuexy/app-assets/js/core/app.js') }}"></script>
    <script>window.addEventListener('load', function () { if (window.feather) { window.feather.replace(); } });</script>
    @stack('scripts')
</body>
</html>

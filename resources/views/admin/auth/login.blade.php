<!DOCTYPE html>
<html class="loading" lang="tr" data-textdirection="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Yönetim Paneli Girişi - EtnoCity Otel</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('vendor/vuexy/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/vuexy/app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/vuexy/app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/vuexy/app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/vuexy/app-assets/css/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/vuexy/app-assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/vuexy/app-assets/css/pages/authentication.css') }}">
</head>
<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static">
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <div class="auth-wrapper auth-cover">
                    <div class="auth-inner row m-0">
                        <a class="brand-logo" href="{{ route('home') }}">
                            <h2 class="brand-text text-primary ms-1">EtnoCity Otel</h2>
                        </a>
                        <div class="d-none d-lg-flex col-lg-8 align-items-center p-5 bg-light">
                            <div class="w-100 d-flex align-items-center justify-content-center px-5">
                                <div class="text-center">
                                    <h1 class="mb-2">Otelinizi tek panelden yönetin.</h1>
                                    <p class="lead text-muted">İçerikler, odalar, fiyatlar ve rezervasyonlar için Vuexy tabanlı yönetim merkezi.</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
                            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                                <h2 class="card-title fw-bold mb-1">EtnoCity Yönetim Paneli</h2>
                                <p class="card-text mb-2">Yönetici hesabınızla giriş yapın.</p>

                                @if ($errors->any())
                                    <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
                                @endif

                                <form class="auth-login-form mt-2" method="POST" action="{{ route('admin.login.store') }}">
                                    @csrf
                                    <div class="mb-1">
                                        <label class="form-label" for="email">E-posta</label>
                                        <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="password">Şifre</label>
                                        <input class="form-control" id="password" type="password" name="password" required autocomplete="current-password">
                                    </div>
                                    <div class="mb-1">
                                        <div class="form-check">
                                            <input class="form-check-input" id="remember" type="checkbox" name="remember" value="1">
                                            <label class="form-check-label" for="remember">Beni hatırla</label>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary w-100" type="submit">Giriş yap</button>
                                </form>
                                <p class="text-center mt-2 mb-0"><a href="{{ route('home') }}">Siteye dön</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('vendor/vuexy/app-assets/vendors/js/vendors.min.js') }}"></script>
</body>
</html>

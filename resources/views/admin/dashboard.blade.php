@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row match-height">
        @foreach ([
            ['label' => 'Oda Tipleri', 'value' => $roomTypeCount, 'icon' => 'layers', 'color' => 'primary'],
            ['label' => 'Aktif Rezervasyon', 'value' => $activeReservationCount, 'icon' => 'calendar', 'color' => 'success'],
            ['label' => 'Bugünkü Giriş', 'value' => $todayCheckInCount, 'icon' => 'log-in', 'color' => 'info'],
            ['label' => 'Bekleyen Ödeme', 'value' => $pendingPaymentCount, 'icon' => 'credit-card', 'color' => 'warning'],
        ] as $stat)
            <div class="col-xl-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div><p class="card-text mb-50">{{ $stat['label'] }}</p><h3 class="fw-bolder mb-0">{{ $stat['value'] }}</h3></div>
                            <div class="avatar bg-light-{{ $stat['color'] }}"><div class="avatar-content"><i data-feather="{{ $stat['icon'] }}" class="font-medium-5 text-{{ $stat['color'] }}"></i></div></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row match-height">
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-header"><h4 class="card-title">Oda ve rezervasyon yönetimi</h4></div>
                <div class="card-body">
                    <p class="card-text">Oda tiplerini, gecelik fiyatları, görselleri ve rezervasyon kayıtlarını buradan yönetebilirsiniz.</p>
                    <div class="d-flex flex-wrap gap-1"><a class="btn btn-primary" href="{{ route('admin.rooms.index') }}">Odaları Yönet</a><a class="btn btn-outline-primary" href="{{ route('admin.reservations.index') }}">Rezervasyonları Gör</a></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="card">
                <div class="card-header"><h4 class="card-title">Hızlı Erişim</h4></div>
                <div class="card-body">
                    <a class="btn btn-outline-primary w-100" href="{{ route('home') }}" target="_blank"><i data-feather="external-link" class="me-50"></i> Siteyi görüntüle</a>
                </div>
            </div>
        </div>
    </div>
@endsection

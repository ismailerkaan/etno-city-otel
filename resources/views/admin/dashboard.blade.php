@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row match-height">
        @foreach ([
            ['label' => 'Oda Tipleri', 'value' => '—', 'icon' => 'layers', 'color' => 'primary'],
            ['label' => 'Aktif Rezervasyon', 'value' => '—', 'icon' => 'calendar', 'color' => 'success'],
            ['label' => 'Bugünkü Giriş', 'value' => '—', 'icon' => 'log-in', 'color' => 'info'],
            ['label' => 'Bekleyen Ödeme', 'value' => '—', 'icon' => 'credit-card', 'color' => 'warning'],
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
                <div class="card-header"><h4 class="card-title">Yönetim paneli hazır</h4></div>
                <div class="card-body">
                    <p class="card-text">Vuexy admin kabuğu, yönetici oturumu ve erişim koruması kuruldu. Şimdi modülleri kontrollü biçimde ekleyebiliriz.</p>
                    <div class="alert alert-primary mb-0" role="alert"><div class="alert-body"><strong>Önerilen ilk modül:</strong> Oda tipleri, oda görselleri ve temel fiyat yönetimi.</div></div>
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

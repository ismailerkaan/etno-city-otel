@extends('admin.layouts.app')

@section('title', 'HMS Entegrasyonu')

@section('content')
    <div class="content-header row mb-2">
        <div class="content-header-left col-12 mb-2">
            <h2 class="content-header-title float-start mb-0">HMS Entegrasyonu</h2>
            <div class="breadcrumb-wrapper">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Yönetim Paneli</a></li>
                    <li class="breadcrumb-item active">HMS Entegrasyonu</li>
                </ol>
            </div>
        </div>
    </div>

    @if (session('hms_test_error'))
        <div class="alert alert-danger"><div class="alert-body">{{ session('hms_test_error') }}</div></div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger"><div class="alert-body"><ul class="mb-0 ps-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
    @endif

    <div class="row">
        <div class="col-xl-7 col-12">
            <div class="card border-top-primary">
                <div class="card-header">
                    <div>
                        <h4 class="card-title text-primary"><i data-feather="link" class="me-50"></i> Bağlantı Bilgileri</h4>
                        <p class="card-text text-muted mb-0">HMS'in verdiği Online Booking API bilgileri. Test ortamında işlemleri tamamladıktan sonra canlı bilgileri buraya girin.</p>
                    </div>
                </div>
                <div class="card-body">
                    @if ($usingEnv)
                        <div class="alert alert-info p-1 small">Şu an sunucu yapılandırmasındaki (.env) bilgiler kullanılıyor. Bu formu kaydettiğinizde paneldeki bilgiler geçerli olur.</div>
                    @endif

                    <form method="POST" action="{{ route('admin.hms.update') }}" id="hms-settings-form">
                        @csrf
                        @method('PUT')

                        <div class="form-check form-switch mb-2">
                            <input type="hidden" name="enabled" value="0">
                            <input class="form-check-input" type="checkbox" id="enabled" name="enabled" value="1" @checked(old('enabled', $settings['enabled']))>
                            <label class="form-check-label fw-bold" for="enabled">Entegrasyon açık</label>
                            <small class="d-block text-muted">Açıkken oda fiyatı ve müsaitlik HMS'ten gelir, rezervasyonlar HMS'e aktarılır.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-8 col-12 mb-1">
                                <label class="form-label fw-bold" for="base_url">API Adresi</label>
                                <input class="form-control @error('base_url') is-invalid @enderror" id="base_url" name="base_url" value="{{ old('base_url', $settings['base_url']) }}" placeholder="https://test.hms.gen.tr" required>
                                <small class="text-muted">Test ortamı: https://test.hms.gen.tr — canlı adresi HMS'ten alın.</small>
                            </div>
                            <div class="col-md-4 col-12 mb-1">
                                <label class="form-label fw-bold" for="hotel_code">Otel Kodu</label>
                                <input class="form-control @error('hotel_code') is-invalid @enderror" id="hotel_code" name="hotel_code" value="{{ old('hotel_code', $settings['hotel_code']) }}" placeholder="Örn: 1000" required>
                            </div>
                            <div class="col-12 mb-1">
                                <label class="form-label fw-bold" for="api_key">API Key</label>
                                <input class="form-control font-monospace @error('api_key') is-invalid @enderror" id="api_key" name="api_key" autocomplete="off" placeholder="{{ filled($settings['api_key']) ? 'Kayıtlı (•••'.substr($settings['api_key'], -4).') — değiştirmek için yeni değeri girin' : 'HMS\'in verdiği apiKey' }}">
                            </div>
                            <div class="col-12 mb-1">
                                <label class="form-label fw-bold" for="api_secret">API Secret</label>
                                <input class="form-control font-monospace @error('api_secret') is-invalid @enderror" id="api_secret" name="api_secret" type="password" autocomplete="new-password" placeholder="{{ filled($settings['api_secret']) ? 'Kayıtlı — değiştirmek için yeni değeri girin' : 'HMS\'in verdiği apiSecret' }}">
                                <small class="text-muted">Anahtarlar veritabanında şifreli saklanır ve bu sayfada tekrar gösterilmez.</small>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between gap-1 mt-1">
                            <button class="btn btn-outline-primary" type="submit" formaction="{{ route('admin.hms.test') }}">
                                <i data-feather="activity" class="me-50"></i> Bağlantıyı Test Et
                            </button>
                            <button class="btn btn-primary" type="submit">
                                <i data-feather="save" class="me-50"></i> Kaydet
                            </button>
                        </div>
                    </form>

                    @if ($setting?->last_tested_at)
                        <p class="small text-muted mt-2 mb-0">Son test ({{ $setting->last_tested_at->format('d.m.Y H:i') }}): {{ $setting->last_test_result }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-5 col-12">
            <div class="card">
                <div class="card-header"><h4 class="card-title"><i data-feather="info" class="me-50"></i> Canlıya Geçiş</h4></div>
                <div class="card-body small">
                    <ol class="ps-1 mb-0">
                        <li class="mb-50">HMS'ten EtnoCity'nin <strong>canlı API adresini, otel kodunu, API key ve secret</strong> bilgilerini alın.</li>
                        <li class="mb-50">Bilgileri girip <strong>Bağlantıyı Test Et</strong> ile doğrulayın, sonra kaydedin.</li>
                        <li class="mb-50"><a href="{{ route('admin.rooms.index') }}">Odalar</a> sayfasında her odayı canlı HMS oda tipiyle yeniden eşleştirin.</li>
                        <li>Sitede bir oda arayıp fiyatların geldiğini kontrol edin.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <h4 class="card-title {{ $failedReservations->isNotEmpty() ? 'text-danger' : '' }}"><i data-feather="alert-triangle" class="me-50"></i> HMS'e Aktarılamayan Rezervasyonlar ({{ $failedReservations->count() }})</h4>
                <p class="card-text text-muted mb-0">HMS'in kabul etmediği yeni rezervasyonlar ve değişiklikler. Detaydan tekrar gönderebilirsiniz.</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Referans</th><th>Misafir</th><th>Konaklama</th><th>Hata</th><th></th></tr></thead>
                <tbody>
                    @forelse ($failedReservations as $reservation)
                        <tr>
                            <td><strong>{{ $reservation->reference }}</strong><br><small class="text-muted">{{ $reservation->created_at->format('d.m.Y H:i') }}</small></td>
                            <td>{{ $reservation->guestFullName() }}<br><small class="text-muted">{{ $reservation->email }}</small></td>
                            <td>{{ $reservation->check_in->format('d.m.Y') }} – {{ $reservation->check_out->format('d.m.Y') }}</td>
                            <td class="small text-danger">{{ $reservation->hms_sync_error ?: 'HMS\'e gönderilmedi' }}</td>
                            <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.reservations.show', $reservation) }}">Detay</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Aktarılamayan rezervasyon yok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

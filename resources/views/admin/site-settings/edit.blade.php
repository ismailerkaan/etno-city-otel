@extends('admin.layouts.app')

@section('title', 'Site Ayarları')

@section('content')
    <form method="POST" action="{{ route('admin.site-settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <div class="alert-body">Lütfen işaretli alanları kontrol edin.</div>
            </div>
        @endif

        <div class="card">
            <div class="card-header"><h4 class="card-title">Genel Bilgiler</h4></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <label class="form-label" for="hotel_name">Otel adı</label>
                        <input class="form-control @error('hotel_name') is-invalid @enderror" id="hotel_name" name="hotel_name" value="{{ old('hotel_name', $siteSetting->hotel_name) }}" required>
                        @error('hotel_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-1">
                        <label class="form-label" for="phone">Telefon</label>
                        <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $siteSetting->phone) }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-1">
                        <label class="form-label" for="email">E-posta</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email', $siteSetting->email) }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-1">
                        <label class="form-label" for="address">Adres</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address', $siteSetting->address) }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label fw-bold" for="logo">Otel Logosu (Görsel Yükle)</label>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <input class="form-control @error('logo') is-invalid @enderror" type="file" id="logo" name="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                            @if ($siteSetting->logoUrl())
                                <div class="d-flex align-items-center gap-2 p-1 border rounded bg-light">
                                    <img src="{{ $siteSetting->logoUrl() }}" alt="Mevcut Logo" style="max-height: 40px; max-width: 140px; object-fit: contain;">
                                    <div class="form-check form-check-inline ms-1">
                                        <input class="form-check-input" type="checkbox" id="remove_logo" name="remove_logo" value="1">
                                        <label class="form-check-label text-danger small" for="remove_logo">Logoyu Kaldır</label>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @error('logo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 mt-1 mb-1">
                        <h5 class="fw-bolder border-bottom pb-50">Sosyal Medya Kanalları</h5>
                        <p class="text-muted small mb-0">Dolu olan sosyal medya hesapları sitenin alt kısmında (footer) otomatik olarak ikon şeklinde gösterilir.</p>
                    </div>
                    <div class="col-md-4 mb-1">
                        <label class="form-label" for="instagram_url">Instagram URL</label>
                        <input class="form-control @error('instagram_url') is-invalid @enderror" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $siteSetting->instagram_url) }}" placeholder="https://instagram.com/...">
                        @error('instagram_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-1">
                        <label class="form-label" for="whatsapp_url">WhatsApp URL</label>
                        <input class="form-control @error('whatsapp_url') is-invalid @enderror" id="whatsapp_url" name="whatsapp_url" value="{{ old('whatsapp_url', $siteSetting->whatsapp_url) }}" placeholder="https://wa.me/...">
                        @error('whatsapp_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-1">
                        <label class="form-label" for="facebook_url">Facebook URL</label>
                        <input class="form-control @error('facebook_url') is-invalid @enderror" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $siteSetting->facebook_url) }}" placeholder="https://facebook.com/...">
                        @error('facebook_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label" for="youtube_url">YouTube URL</label>
                        <input class="form-control @error('youtube_url') is-invalid @enderror" id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $siteSetting->youtube_url) }}" placeholder="https://youtube.com/@...">
                        @error('youtube_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label" for="tripadvisor_url">TripAdvisor URL</label>
                        <input class="form-control @error('tripadvisor_url') is-invalid @enderror" id="tripadvisor_url" name="tripadvisor_url" value="{{ old('tripadvisor_url', $siteSetting->tripadvisor_url) }}" placeholder="https://tripadvisor.com/...">
                        @error('tripadvisor_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>



        <div class="card">
            <div class="card-header"><h4 class="card-title">Bülten Alanı</h4></div>
            <div class="card-body"><div class="row">
                <div class="col-md-4 mb-1"><label class="form-label" for="newsletter_title">Başlık</label><input class="form-control" id="newsletter_title" name="newsletter_title" value="{{ old('newsletter_title', $siteSetting->newsletter_title) }}"></div>
                <div class="col-md-8 mb-1"><label class="form-label" for="newsletter_text">Açıklama</label><textarea class="form-control" id="newsletter_text" name="newsletter_text" rows="2">{{ old('newsletter_text', $siteSetting->newsletter_text) }}</textarea></div>
                <div class="col-md-4 mb-1"><label class="form-label" for="newsletter_title_en">Title (EN)</label><input class="form-control" id="newsletter_title_en" name="newsletter_title_en" value="{{ old('newsletter_title_en', $siteSetting->newsletter_title_en) }}"></div>
                <div class="col-md-8 mb-1"><label class="form-label" for="newsletter_text_en">Description (EN)</label><textarea class="form-control" id="newsletter_text_en" name="newsletter_text_en" rows="2">{{ old('newsletter_text_en', $siteSetting->newsletter_text_en) }}</textarea></div>
            </div></div>
        </div>

        <div class="d-flex justify-content-end mb-2"><button class="btn btn-primary" type="submit"><i data-feather="save" class="me-50"></i> Ayarları kaydet</button></div>
    </form>
@endsection

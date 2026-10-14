@extends('admin.layouts.app')

@section('title', 'İletişim Sayfası')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
        <div>
            <h3 class="mb-25"><i data-feather="layout" class="me-50 text-primary"></i> İletişim Sayfası Yönetimi</h3>
            <span class="text-muted">Web sitesindeki <code>/iletisim</code> sayfasında yer alan başlıklar, açıklama metinleri, resepsiyon bilgileri ve Google Haritalar entegrasyonu.</span>
        </div>
        <div class="d-flex align-items-center gap-1">
            <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-primary shadow-sm">
                <i data-feather="inbox" class="me-50"></i> İletişim Taleplerini Görüntüle
                @if ($unreadCount > 0)
                    <span class="badge bg-danger rounded-pill ms-50">{{ $unreadCount }}</span>
                @endif
            </a>
            <a href="{{ route('contact') }}" target="_blank" class="btn btn-outline-secondary">
                <i data-feather="external-link" class="me-50"></i> Sayfayı Canlı Gör
            </a>
        </div>
    </div>

    @if (!empty($tableMissing))
        <div class="alert alert-warning p-2 mb-2 shadow-sm border-start-warning border-start-3">
            <h5 class="alert-heading mb-1"><i data-feather="alert-triangle"></i> Veritabanı Alanları Henüz Oluşturulmadı</h5>
            <p class="mb-0">Veritabanında <code>site_settings</code> iletişim sütunları henüz mevcut değil. Lütfen terminalinizde veya Docker ortamında <code>php artisan migrate</code> komutunu çalıştırınız.</p>
        </div>
    @endif

    {{-- İLETİŞİM SAYFASI AYARLARI KARTI --}}
    <div class="card border-top-primary shadow-sm">
        <div class="card-header">
            <h4 class="card-title text-primary"><i data-feather="sliders" class="me-50"></i> İletişim Sayfası Metin & Bölüm Ayarları</h4>
            <p class="card-text text-muted mb-0">Web sitesindeki /iletisim sayfasında görünen başlık, açıklama, konsiyerj saatleri ve harita bilgilerini buradan yönetebilirsiniz.</p>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.contact.settings-update') }}">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Türkçe Başlıklar --}}
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold text-primary" for="contact_eyebrow">Üst Etiket (TR)</label>
                        <input class="form-control" id="contact_eyebrow" name="contact_eyebrow" value="{{ old('contact_eyebrow', $siteSetting->contact_eyebrow ?? 'İletişim & Rezervasyon Masası') }}" placeholder="Örn: İletişim & Rezervasyon Masası">
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold text-primary" for="contact_title">Ana Başlık (TR)</label>
                        <input class="form-control" id="contact_title" name="contact_title" value="{{ old('contact_title', $siteSetting->contact_title ?? 'Konaklamanız İçin Buradayız') }}" placeholder="Örn: Konaklamanız İçin Buradayız" required>
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label fw-bold text-primary" for="contact_subtitle">Açıklama Metni (TR)</label>
                        <textarea class="form-control" id="contact_subtitle" name="contact_subtitle" rows="2" placeholder="Özel konaklama talepleriniz, transferler...">{{ old('contact_subtitle', $siteSetting->contact_subtitle) }}</textarea>
                    </div>

                    {{-- İngilizce Başlıklar --}}
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold text-info" for="contact_eyebrow_en">Eyebrow (EN)</label>
                        <input class="form-control" id="contact_eyebrow_en" name="contact_eyebrow_en" value="{{ old('contact_eyebrow_en', $siteSetting->contact_eyebrow_en ?? 'Contact & Reservations') }}" placeholder="Ex: Contact & Reservations">
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold text-info" for="contact_title_en">Title (EN)</label>
                        <input class="form-control" id="contact_title_en" name="contact_title_en" value="{{ old('contact_title_en', $siteSetting->contact_title_en ?? 'Here to Help With Your Stay') }}" placeholder="Ex: Here to Help With Your Stay">
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label fw-bold text-info" for="contact_subtitle_en">Subtitle (EN)</label>
                        <textarea class="form-control" id="contact_subtitle_en" name="contact_subtitle_en" rows="2" placeholder="Contact our team for reservations and stay information...">{{ old('contact_subtitle_en', $siteSetting->contact_subtitle_en) }}</textarea>
                    </div>

                    <div class="col-12"><hr class="my-1"></div>

                    {{-- Ek İletişim Bilgileri --}}
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold" for="contact_concierge_hours">Concierge & Çalışma Saatleri (TR)</label>
                        <input class="form-control" id="contact_concierge_hours" name="contact_concierge_hours" value="{{ old('contact_concierge_hours', $siteSetting->contact_concierge_hours ?? '7/24 Kesintisiz Concierge & Rezervasyon Masası') }}" placeholder="Örn: 7/24 Kesintisiz Concierge">
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold text-info" for="contact_concierge_hours_en">Concierge Hours (EN)</label>
                        <input class="form-control" id="contact_concierge_hours_en" name="contact_concierge_hours_en" value="{{ old('contact_concierge_hours_en', $siteSetting->contact_concierge_hours_en ?? '24/7 Front Desk Support') }}" placeholder="Ex: 24/7 Front Desk Support">
                    </div>

                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold" for="contact_phone_concierge">İkincil / VIP Masa Telefonu</label>
                        <input class="form-control" id="contact_phone_concierge" name="contact_phone_concierge" value="{{ old('contact_phone_concierge', $siteSetting->contact_phone_concierge) }}" placeholder="+90 252 000 00 01">
                        <small class="text-muted">Birincil genel telefon "Site Ayarları" sayfasından yönetilmektedir.</small>
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold" for="contact_email_reservations">Özel Rezervasyon E-postası</label>
                        <input class="form-control" id="contact_email_reservations" name="contact_email_reservations" value="{{ old('contact_email_reservations', $siteSetting->contact_email_reservations) }}" placeholder="reservations@etnocity.com">
                        <small class="text-muted">Birincil genel e-posta "Site Ayarları" sayfasından yönetilmektedir.</small>
                    </div>

                    <div class="col-12"><hr class="my-1"></div>

                    {{-- Harita Entegrasyonu --}}
                    <div class="col-12 mb-1">
                        <h5 class="fw-bold text-secondary"><i data-feather="map-pin" class="me-50"></i> Harita Entegrasyonu (Site Ayarlarından Çekilir)</h5>
                        <p class="text-muted small mb-1">İletişim sayfasında ziyaretçilere gösterilecek Google Haritalar entegrasyonu. Buradan yapılan değişiklikler otomatik olarak genel site ayarlarına yansır.</p>
                    </div>

                    <div class="col-md-8 col-12 mb-1">
                        <label class="form-label fw-bold" for="location_map_embed_url">Google Maps Embed URL veya &lt;iframe&gt; Kodu</label>
                        <textarea class="form-control font-monospace" id="location_map_embed_url" name="location_map_embed_url" rows="3" placeholder="https://maps.google.com/maps?q=37.1228,27.2789&output=embed veya <iframe src='...'></iframe>">{{ old('location_map_embed_url', $siteSetting->location_map_embed_url) }}</textarea>
                        <small class="text-muted">Google Haritalar'dan "Haritayı Yerleştir" seçeneğindeki iframe kodunu doğrudan yapıştırabilirsiniz.</small>
                    </div>

                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label fw-bold" for="location_coordinates">GPS Koordinatları</label>
                        <input class="form-control" id="location_coordinates" name="location_coordinates" value="{{ old('location_coordinates', $siteSetting->location_coordinates) }}" placeholder="37°07'22.4&quot;N 27°16'44.1&quot;E">
                        <small class="text-muted">İletişim sayfasında ziyaretçilere gösterilen enlem/boylam bilgisi.</small>
                    </div>

                    {{-- Canlı Harita Önizleme --}}
                    <div class="col-12 mb-1">
                        <label class="form-label fw-bold">Mevcut Harita Önizlemesi</label>
                        <div class="border rounded overflow-hidden shadow-sm" style="height: 260px;">
                            <iframe src="{{ $siteSetting->mapEmbedUrl() }}" class="w-100 h-100 border-0" style="width: 100%; height: 100%;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-2">
                    <button class="btn btn-primary btn-lg shadow" type="submit">
                        <i data-feather="save" class="me-50"></i> İletişim Sayfası Ayarlarını Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

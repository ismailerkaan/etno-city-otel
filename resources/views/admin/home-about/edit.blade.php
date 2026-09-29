@extends('admin.layouts.app')

@section('title', 'Otel Tanıtımı')

@section('content')
    <form method="POST" action="{{ route('admin.home-about.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <div class="alert-body">Lütfen formdaki eksik veya hatalı alanları kontrol edin.</div>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h4 class="mb-50">Anasayfa Otel Tanıtımı</h4>
                <p class="text-muted mb-0">"EtnoCity Otel'e Hoş Geldiniz - Deniz ve Yaşayan Taşla Uyum İçinde Yaratıldı" alanını buradan düzenleyebilirsiniz.</p>
            </div>
            <button class="btn btn-primary btn-lg shadow" type="submit">
                <i data-feather="save" class="me-50"></i> Değişiklikleri Kaydet
            </button>
        </div>

        <div class="row match-height">
            {{-- Sol Sütun: Metin İçerikleri (TR & EN) --}}
            <div class="col-lg-8 col-12">
                {{-- Türkçe İçerik --}}
                <div class="card border-top-primary">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title text-primary"><i data-feather="globe" class="me-50"></i> Türkçe İçerik</h4>
                        <span class="badge bg-light-primary">Varsayılan Dil</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-1">
                            <label class="form-label fw-bold" for="about_eyebrow">Üst Etiket (Eyebrow)</label>
                            <input class="form-control @error('about_eyebrow') is-invalid @enderror" id="about_eyebrow" name="about_eyebrow" value="{{ old('about_eyebrow', $siteSetting->about_eyebrow) }}" placeholder="Örn: EtnoCity Otel'e Hoş Geldiniz">
                            @error('about_eyebrow')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-1">
                            <label class="form-label fw-bold" for="about_title">Ana Başlık</label>
                            <input class="form-control @error('about_title') is-invalid @enderror" id="about_title" name="about_title" value="{{ old('about_title', $siteSetting->about_title) }}" placeholder="Örn: Deniz ve Yaşayan Taşla Uyum İçinde Yaratıldı" required>
                            @error('about_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="about_primary_text">1. Paragraf (Ana Metin)</label>
                            <textarea class="form-control @error('about_primary_text') is-invalid @enderror" id="about_primary_text" name="about_primary_text" rows="4" placeholder="Otel hakkında ilk tanıtım paragrafı...">{{ old('about_primary_text', $siteSetting->about_primary_text) }}</textarea>
                            @error('about_primary_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="about_secondary_text">2. Paragraf (Detay Metni)</label>
                            <textarea class="form-control @error('about_secondary_text') is-invalid @enderror" id="about_secondary_text" name="about_secondary_text" rows="4" placeholder="İkinci tamamlayıcı paragraf...">{{ old('about_secondary_text', $siteSetting->about_secondary_text) }}</textarea>
                            @error('about_secondary_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- İngilizce İçerik --}}
                <div class="card border-top-info">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title text-info"><i data-feather="globe" class="me-50"></i> English Content</h4>
                        <span class="badge bg-light-info">English Translation</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-1">
                            <label class="form-label fw-bold" for="about_eyebrow_en">Eyebrow (EN)</label>
                            <input class="form-control @error('about_eyebrow_en') is-invalid @enderror" id="about_eyebrow_en" name="about_eyebrow_en" value="{{ old('about_eyebrow_en', $siteSetting->about_eyebrow_en) }}" placeholder="Ex: Welcome to EtnoCity Hotel">
                            @error('about_eyebrow_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-1">
                            <label class="form-label fw-bold" for="about_title_en">Title (EN)</label>
                            <input class="form-control @error('about_title_en') is-invalid @enderror" id="about_title_en" name="about_title_en" value="{{ old('about_title_en', $siteSetting->about_title_en) }}" placeholder="Ex: Crafted in Harmony with Sea and Living Stone">
                            @error('about_title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="about_primary_text_en">Primary Text (EN)</label>
                            <textarea class="form-control @error('about_primary_text_en') is-invalid @enderror" id="about_primary_text_en" name="about_primary_text_en" rows="4" placeholder="First introductory paragraph in English...">{{ old('about_primary_text_en', $siteSetting->about_primary_text_en) }}</textarea>
                            @error('about_primary_text_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="about_secondary_text_en">Secondary Text (EN)</label>
                            <textarea class="form-control @error('about_secondary_text_en') is-invalid @enderror" id="about_secondary_text_en" name="about_secondary_text_en" rows="4" placeholder="Second paragraph in English...">{{ old('about_secondary_text_en', $siteSetting->about_secondary_text_en) }}</textarea>
                            @error('about_secondary_text_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sağ Sütun: Görsel & Ek Bilgiler --}}
            <div class="col-lg-4 col-12">
                {{-- Tanıtım Görseli --}}
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Bölüm Görseli</h4>
                    </div>
                    <div class="card-body">
                        @if ($siteSetting->aboutImageUrl())
                            <div class="mb-2 text-center">
                                <img src="{{ $siteSetting->aboutImageUrl() }}" alt="Otel Tanıtım Görseli" class="img-fluid rounded shadow-sm border" style="max-height: 260px; width: 100%; object-fit: cover;">
                                <p class="text-muted small mt-50 mb-0">Mevcut görsel önizlemesi</p>
                            </div>
                        @endif

                        <div class="mb-1">
                            <label class="form-label fw-bold" for="about_image">Görsel Yükle</label>
                            <input class="form-control @error('about_image') is-invalid @enderror" id="about_image" type="file" name="about_image" accept="image/jpeg,image/png,image/webp">
                            <div class="form-text">JPG, PNG veya WebP; en fazla 5 MB.</div>
                            @error('about_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="about_image_url">Veya Harici Görsel URL</label>
                            <input class="form-control @error('about_image_url') is-invalid @enderror" id="about_image_url" name="about_image_url" value="{{ old('about_image_url', $siteSetting->about_image_url) }}" placeholder="https://...">
                            <div class="form-text">Dosya yüklemek yerine doğrudan URL de girebilirsiniz.</div>
                            @error('about_image_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Süit Sayısı / İstatistik --}}
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Ekstra Göstergeler</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-1">
                            <label class="form-label fw-bold" for="suite_count">Toplam Süit Sayısı</label>
                            <input class="form-control @error('suite_count') is-invalid @enderror" id="suite_count" type="number" min="0" name="suite_count" value="{{ old('suite_count', $siteSetting->suite_count) }}" required>
                            <div class="form-text">Metin altında gösterilen rozetteki oda/süit sayısı (Örn: 24).</div>
                            @error('suite_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Görsel Üzerindeki Ödül Rozeti --}}
                <div class="card border-top-warning">
                    <div class="card-header">
                        <h4 class="card-title text-warning"><i data-feather="award" class="me-50"></i> Ödül / Başarı Rozeti</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-2">Görselin sol alt köşesinde yüzen 5 yıldızlı rozet kutucuğunun metinleridir.</p>

                        <div class="mb-1">
                            <label class="form-label fw-bold" for="award_badge_title">Rozet Başlığı (TR)</label>
                            <input class="form-control @error('award_badge_title') is-invalid @enderror" id="award_badge_title" name="award_badge_title" value="{{ old('award_badge_title', $siteSetting->award_badge_title) }}" placeholder="Örn: 1 Numaralı Sığınak Seçildi">
                            @error('award_badge_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="award_badge_subtitle">Rozet Alt Metni (TR)</label>
                            <input class="form-control @error('award_badge_subtitle') is-invalid @enderror" id="award_badge_subtitle" name="award_badge_subtitle" value="{{ old('award_badge_subtitle', $siteSetting->award_badge_subtitle) }}" placeholder="Örn: Akdeniz Butik Sığınak & Mimarlık Ödülü 2024">
                            @error('award_badge_subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-1 pt-1 border-top">
                            <label class="form-label fw-bold text-info" for="award_badge_title_en">Badge Title (EN)</label>
                            <input class="form-control @error('award_badge_title_en') is-invalid @enderror" id="award_badge_title_en" name="award_badge_title_en" value="{{ old('award_badge_title_en', $siteSetting->award_badge_title_en) }}" placeholder="Ex: Voted #1 Sanctuary">
                            @error('award_badge_title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-1">
                            <label class="form-label text-info" for="award_badge_subtitle_en">Badge Subtitle (EN)</label>
                            <input class="form-control @error('award_badge_subtitle_en') is-invalid @enderror" id="award_badge_subtitle_en" name="award_badge_subtitle_en" value="{{ old('award_badge_subtitle_en', $siteSetting->award_badge_subtitle_en) }}" placeholder="Ex: Mediterranean Boutique Sanctuary & Architecture Award 2024">
                            @error('award_badge_subtitle_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-primary w-100 btn-lg shadow" type="submit">
                            <i data-feather="save" class="me-50"></i> Değişiklikleri Kaydet
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

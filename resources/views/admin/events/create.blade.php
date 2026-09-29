@extends('admin.layouts.app')

@section('title', 'Yeni Etkinlik Ekle')

@section('content')
    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <div class="alert-body">Lütfen formdaki eksik veya hatalı alanları kontrol edin.</div>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <a class="btn btn-outline-secondary btn-sm mb-50" href="{{ route('admin.events.index') }}">
                    <i data-feather="arrow-left" class="me-25"></i> Etkinliklere Dön
                </a>
                <h4 class="mb-0">Yeni Etkinlik / Deneyim Oluştur</h4>
            </div>
            <button class="btn btn-success btn-lg shadow" type="submit">
                <i data-feather="check" class="me-50"></i> Etkinliği Kaydet
            </button>
        </div>

        <div class="row match-height">
            {{-- Sol Sütun: TR & EN İçerik --}}
            <div class="col-lg-8 col-12">
                {{-- Türkçe Bilgiler --}}
                <div class="card border-top-primary">
                    <div class="card-header">
                        <h4 class="card-title text-primary"><i data-feather="file-text" class="me-50"></i> Türkçe İçerik</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5 mb-1">
                                <label class="form-label fw-bold" for="category">Kategori / Etiket (TR)</label>
                                <input class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category') }}" placeholder="Örn: Gastronomi Deneyimi, Denizcilik Keşfi">
                                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-7 mb-1">
                                <label class="form-label fw-bold" for="title">Etkinlik Başlığı (TR)</label>
                                <input class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Örn: Uçurum Kenarında Mumlu Akşam Yemeği" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="description">Açıklama (TR)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Etkinlik veya deneyim hakkında detaylı açıklama...">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>

                {{-- İngilizce Bilgiler --}}
                <div class="card border-top-info">
                    <div class="card-header">
                        <h4 class="card-title text-info"><i data-feather="globe" class="me-50"></i> English Content</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5 mb-1">
                                <label class="form-label fw-bold text-info" for="category_en">Category / Badge (EN)</label>
                                <input class="form-control @error('category_en') is-invalid @enderror" id="category_en" name="category_en" value="{{ old('category_en') }}" placeholder="Ex: Gastronomic Intimacy, Maritime Discovery">
                            </div>
                            <div class="col-md-7 mb-1">
                                <label class="form-label fw-bold text-info" for="title_en">Title (EN)</label>
                                <input class="form-control @error('title_en') is-invalid @enderror" id="title_en" name="title_en" value="{{ old('title_en') }}" placeholder="Ex: Candlelit Cliffside Dining">
                            </div>
                        </div>

                        <div class="mb-1">
                            <label class="form-label text-info" for="description_en">Description (EN)</label>
                            <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="4" placeholder="Detailed English description...">{{ old('description_en') }}</textarea>
                        </div>


                    </div>
                </div>
            </div>

            {{-- Sağ Sütun: Görsel, Yayın Seçenekleri --}}
            <div class="col-lg-4 col-12">
                {{-- Görsel Seçimi --}}
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Etkinlik Görseli</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-1">
                            <label class="form-label fw-bold" for="image">Görsel Yükle</label>
                            <input class="form-control @error('image') is-invalid @enderror" id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp">
                            <div class="form-text">JPG, PNG veya WebP; en fazla 5 MB.</div>
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="image_url">Veya Görsel URL</label>
                            <input class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://...">
                            <div class="form-text">Harici bir görsel linki de girebilirsiniz.</div>
                            @error('image_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Yayın ve Görünürlük Ayarları --}}
                <div class="card border-top-warning">
                    <div class="card-header">
                        <h4 class="card-title text-warning"><i data-feather="settings" class="me-50"></i> Yayın Ayarları</h4>
                    </div>
                    <div class="card-body">
                        {{-- Kilit Özellik: Anasayfada Yayınla --}}
                        <div class="mb-2 p-1 bg-light-success rounded border border-success">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" id="show_on_home" type="checkbox" name="show_on_home" value="1" @checked(old('show_on_home', true))>
                                <label class="form-check-label fw-bolder text-dark" for="show_on_home">
                                    ⭐ Anasayfada Yayınla
                                </label>
                            </div>
                            <small class="text-muted d-block mt-50">İşaretlenirse bu etkinlik anasayfadaki "Özel Deneyimler" vitrininde görünür.</small>
                        </div>

                        <div class="mb-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                                <label class="form-check-label fw-bold" for="is_active">Aktif (Sitede Yayında)</label>
                            </div>
                        </div>

                        <div class="mb-1">
                            <label class="form-label fw-bold" for="sort_order">Sıralama</label>
                            <input class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $nextSortOrder) }}" required>
                            <div class="form-text">Küçük numaralı etkinlikler daha önce listelenir.</div>
                            @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button class="btn btn-success w-100 btn-lg shadow mt-1" type="submit">
                            <i data-feather="check" class="me-50"></i> Etkinliği Kaydet
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

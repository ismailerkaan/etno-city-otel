@extends('admin.layouts.app')

@section('title', 'Etkinliği Düzenle: ' . $event->title)

@section('content')
    <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                <h4 class="mb-0">Etkinliği Düzenle: {{ $event->title }}</h4>
            </div>
            <button class="btn btn-primary btn-lg shadow" type="submit">
                <i data-feather="save" class="me-50"></i> Değişiklikleri Kaydet
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
                                <input class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $event->category) }}" placeholder="Örn: Gastronomi Deneyimi">
                                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-7 mb-1">
                                <label class="form-label fw-bold" for="title">Etkinlik Başlığı (TR)</label>
                                <input class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $event->title) }}" placeholder="Örn: Uçurum Kenarında Mumlu Akşam Yemeği" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="description">Açıklama (TR)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Detaylı açıklama...">{{ old('description', $event->description) }}</textarea>
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
                                <input class="form-control @error('category_en') is-invalid @enderror" id="category_en" name="category_en" value="{{ old('category_en', $event->category_en) }}" placeholder="Ex: Gastronomic Intimacy">
                            </div>
                            <div class="col-md-7 mb-1">
                                <label class="form-label fw-bold text-info" for="title_en">Title (EN)</label>
                                <input class="form-control @error('title_en') is-invalid @enderror" id="title_en" name="title_en" value="{{ old('title_en', $event->title_en) }}" placeholder="Ex: Candlelit Cliffside Dining">
                            </div>
                        </div>

                        <div class="mb-1">
                            <label class="form-label text-info" for="description_en">Description (EN)</label>
                            <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="4" placeholder="English description...">{{ old('description_en', $event->description_en) }}</textarea>
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
                        @if ($event->imageUrl())
                            <div class="mb-2 text-center">
                                <img src="{{ $event->imageUrl() }}" alt="{{ $event->title }}" class="img-fluid rounded shadow-sm border" style="max-height: 200px; width: 100%; object-fit: cover;">
                                <p class="text-muted small mt-50 mb-0">Mevcut görsel</p>
                            </div>
                        @endif

                        <div class="mb-1">
                            <label class="form-label fw-bold" for="image">Yeni Görsel Yükle</label>
                            <input class="form-control @error('image') is-invalid @enderror" id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp">
                            <div class="form-text">Mevcut görseli değiştirmek istemiyorsanız boş bırakın.</div>
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="image_url">Veya Görsel URL</label>
                            <input class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" value="{{ old('image_url', str_starts_with($event->image_path ?? '', 'http') ? $event->image_path : '') }}" placeholder="https://...">
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
                                <input class="form-check-input" id="show_on_home" type="checkbox" name="show_on_home" value="1" @checked(old('show_on_home', $event->show_on_home))>
                                <label class="form-check-label fw-bolder text-dark" for="show_on_home">
                                    ⭐ Anasayfada Yayınla
                                </label>
                            </div>
                            <small class="text-muted d-block mt-50">İşaretlenirse bu etkinlik anasayfadaki "Özel Deneyimler" vitrininde görünür.</small>
                        </div>

                        <div class="mb-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $event->is_active))>
                                <label class="form-check-label fw-bold" for="is_active">Aktif (Sitede Yayında)</label>
                            </div>
                        </div>

                        <div class="mb-1">
                            <label class="form-label fw-bold" for="sort_order">Sıralama</label>
                            <input class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $event->sort_order) }}" required>
                            @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button class="btn btn-primary w-100 btn-lg shadow mt-1" type="submit">
                            <i data-feather="save" class="me-50"></i> Değişiklikleri Kaydet
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

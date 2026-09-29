@extends('admin.layouts.app')

@section('title', 'Harita & Yakın Yerler')

@section('content')
    <div class="content-header row mb-2">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Harita & Yakın Yerler</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Yönetim Paneli</a></li>
                            <li class="breadcrumb-item active">Harita & Yakın Yerler</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 1. HARİTA VE BÖLÜM AYARLARI --}}
    <div class="card border-top-primary mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title text-primary"><i data-feather="map-pin" class="me-50"></i> Harita ve Konum Bölüm Ayarları</h4>
                <p class="card-text text-muted mb-0">Anasayfadaki gerçek harita alanı, GPS koordinatları ve konum tanıtım başlıkları.</p>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.nearby-places.section-update') }}">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Harita Embed URL veya iframe kodu --}}
                    <div class="col-12 mb-2">
                        <label class="form-label fw-bold" for="location_map_embed_url">
                            Google Maps Embed URL veya İframe Kodu
                            <span class="text-muted fw-normal font-small-2">(Google Maps üzerinden Paylaş > Haritayı Yerleştir diyerek iframe kodunu veya linkini buraya yapıştırabilirsiniz)</span>
                        </label>
                        <textarea class="form-control font-monospace" id="location_map_embed_url" name="location_map_embed_url" rows="2" placeholder="https://maps.google.com/maps?q=37.1228,27.2789&output=embed veya <iframe src='...'></iframe>">{{ old('location_map_embed_url', $siteSetting->location_map_embed_url) }}</textarea>
                    </div>

                    {{-- Koordinat ve Adres Bilgileri --}}
                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label fw-bold" for="location_coordinates">GPS Koordinat Metni</label>
                        <input class="form-control" id="location_coordinates" name="location_coordinates" value="{{ old('location_coordinates', $siteSetting->location_coordinates) }}" placeholder="Örn: 37°07'22.4&quot;N 27°16'44.1&quot;E">
                    </div>
                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label fw-bold" for="location_address">Kısa Konum / Bölge Etiketi</label>
                        <input class="form-control" id="location_address" name="location_address" value="{{ old('location_address', $siteSetting->location_address) }}" placeholder="Örn: Cape Artemis, Gökçebel Koyu, Bodrum">
                    </div>
                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label fw-bold" for="location_pin_label">Harita Rozet / Pin Başlığı</label>
                        <input class="form-control" id="location_pin_label" name="location_pin_label" value="{{ old('location_pin_label', $siteSetting->location_pin_label) }}" placeholder="Örn: EtnoCity Otel">
                    </div>

                    <div class="col-12 my-1"><hr class="my-50"></div>

                    {{-- Türkçe Bölüm Başlıkları --}}
                    <div class="col-md-6 col-12 mb-1">
                        <label class="form-label fw-bold" for="location_eyebrow">Üst Etiket (TR)</label>
                        <input class="form-control" id="location_eyebrow" name="location_eyebrow" value="{{ old('location_eyebrow', $siteSetting->location_eyebrow) }}" placeholder="Örn: Yarımadaya Varış">
                    </div>
                    <div class="col-md-6 col-12 mb-1">
                        <label class="form-label fw-bold" for="location_title">Ana Başlık (TR)</label>
                        <input class="form-control" id="location_title" name="location_title" value="{{ old('location_title', $siteSetting->location_title) }}" placeholder="Örn: Sakin Ama Kolayca Ulaşılabilir" required>
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label fw-bold" for="location_description">Tanıtım Açıklaması (TR)</label>
                        <textarea class="form-control" id="location_description" name="location_description" rows="2" placeholder="Otel konumu hakkında açıklama...">{{ old('location_description', $siteSetting->location_description) }}</textarea>
                    </div>

                    {{-- İngilizce Bölüm Başlıkları --}}
                    <div class="col-md-6 col-12 mb-1">
                        <label class="form-label fw-bold text-info" for="location_eyebrow_en">Eyebrow (EN)</label>
                        <input class="form-control" id="location_eyebrow_en" name="location_eyebrow_en" value="{{ old('location_eyebrow_en', $siteSetting->location_eyebrow_en) }}" placeholder="Ex: Peninsula Arrival">
                    </div>
                    <div class="col-md-6 col-12 mb-1">
                        <label class="form-label fw-bold text-info" for="location_title_en">Title (EN)</label>
                        <input class="form-control" id="location_title_en" name="location_title_en" value="{{ old('location_title_en', $siteSetting->location_title_en) }}" placeholder="Ex: Secluded Yet Effortlessly Accessible">
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label fw-bold text-info" for="location_description_en">Description (EN)</label>
                        <textarea class="form-control" id="location_description_en" name="location_description_en" rows="2" placeholder="English description of location...">{{ old('location_description_en', $siteSetting->location_description_en) }}</textarea>
                    </div>
                </div>

                {{-- Canlı Harita Önizlemesi --}}
                @if ($siteSetting->mapEmbedUrl())
                    <div class="mb-2">
                        <label class="form-label text-muted small fw-bold">Harita Canlı Önizlemesi:</label>
                        <div class="rounded overflow-hidden border shadow-sm" style="height: 240px; background: #e5e3df;">
                            <iframe src="{{ $siteSetting->mapEmbedUrl() }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                @endif

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">
                        <i data-feather="save" class="me-50"></i> Harita ve Konum Ayarlarını Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. YENİ YAKIN YER / ULAŞIM NOKTASI EKLEME KARTI --}}
    <div class="card border-top-success mb-3">
        <div class="card-header">
            <h4 class="card-title text-success"><i data-feather="plus-circle" class="me-50"></i> Yeni Yakın Yer / Ulaşım Noktası Ekle</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.nearby-places.store') }}">
                @csrf
                <div class="row">
                    {{-- İkon Seçimi --}}
                    <div class="col-md-3 col-12 mb-1">
                        <label class="form-label fw-bold" for="new_icon">İkon Seçin</label>
                        <select class="form-select" id="new_icon" name="icon" required onchange="updateNewIconPreview(this.value)">
                            @foreach ($availableIcons as $iconCode => $iconName)
                                <option value="{{ $iconCode }}">{{ $iconName }}</option>
                            @endforeach
                        </select>
                        <div class="mt-1 d-flex align-items-center gap-2">
                            <span class="small text-muted">Önizleme:</span>
                            <div class="p-1 bg-light rounded d-inline-flex align-items-center justify-content-center text-primary" style="width: 38px; height: 38px;">
                                <span class="material-symbols-outlined" id="new_icon_preview" style="font-size: 24px;">flight</span>
                            </div>
                        </div>
                    </div>

                    {{-- Başlık (TR) --}}
                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label fw-bold" for="new_title">Nokta / Yer Adı (TR) <span class="text-danger">*</span></label>
                        <input class="form-control @error('title') is-invalid @enderror" id="new_title" name="title" value="{{ old('title') }}" placeholder="Örn: Bodrum-Milas Airport (BJV)" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Başlık (EN) --}}
                    <div class="col-md-5 col-12 mb-1">
                        <label class="form-label fw-bold text-info" for="new_title_en">Place Name (EN)</label>
                        <input class="form-control" id="new_title_en" name="title_en" value="{{ old('title_en') }}" placeholder="Ex: Bodrum-Milas Airport (BJV)">
                    </div>

                    {{-- Ulaşım Açıklaması (TR) --}}
                    <div class="col-md-5 col-12 mb-1">
                        <label class="form-label fw-bold" for="new_desc">Ulaşım / Süre Açıklaması (TR)</label>
                        <input class="form-control" id="new_desc" name="description" value="{{ old('description') }}" placeholder="Örn: Özel Şoför: 35 dk · Doğrudan Helikopter: 10 dk">
                    </div>

                    {{-- Ulaşım Açıklaması (EN) --}}
                    <div class="col-md-5 col-12 mb-1">
                        <label class="form-label fw-bold text-info" for="new_desc_en">Transit / Duration (EN)</label>
                        <input class="form-control" id="new_desc_en" name="description_en" value="{{ old('description_en') }}" placeholder="Ex: Private Chauffeur: 35 min · Direct Helicopter: 10 min">
                    </div>

                    {{-- Mesafe --}}
                    <div class="col-md-2 col-6 mb-1">
                        <label class="form-label fw-bold" for="new_distance">Mesafe</label>
                        <input class="form-control" id="new_distance" name="distance" value="{{ old('distance') }}" placeholder="Örn: 38 km">
                    </div>

                    {{-- Sıra --}}
                    <div class="col-md-3 col-6 mb-1">
                        <label class="form-label fw-bold" for="new_sort_order">Sıra <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" id="new_sort_order" name="sort_order" value="{{ old('sort_order', $nextSortOrder) }}" min="0" required>
                    </div>

                    {{-- Durum --}}
                    <div class="col-md-3 col-12 mb-1 d-flex align-items-center">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" id="new_is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="new_is_active">Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-1">
                    <button class="btn btn-success" type="submit">
                        <i data-feather="plus" class="me-50"></i> Noktayı Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 3. MEVCUT YERLER LİSTESİ --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title"><i data-feather="compass" class="me-50"></i> Kayıtlı Yakın Yerler & Ulaşım Noktaları ({{ $places->count() }})</h4>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">Sıra</th>
                        <th style="width: 80px;">İkon</th>
                        <th>Yer / Nokta Adı</th>
                        <th>Ulaşım / Süre Detayı</th>
                        <th style="width: 120px;">Mesafe</th>
                        <th style="width: 100px;">Durum</th>
                        <th style="width: 160px;" class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($places as $item)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark fw-bold">{{ $item->sort_order }}</span>
                            </td>
                            <td>
                                <div class="rounded bg-light-primary text-primary d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                    <span class="material-symbols-outlined" style="font-size: 22px;">{{ $item->icon }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $item->title }}</div>
                                @if ($item->title_en)
                                    <div class="small text-info">{{ $item->title_en }}</div>
                                @endif
                            </td>
                            <td>
                                <div>{{ $item->description ?: '-' }}</div>
                                @if ($item->description_en)
                                    <div class="small text-info">{{ $item->description_en }}</div>
                                @endif
                            </td>
                            <td>
                                @if ($item->distance)
                                    <span class="badge bg-light-secondary text-secondary fw-bold">{{ $item->distance }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.nearby-places.toggle-status', $item) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }} border-0" title="Durumu değiştirmek için tıklayın">
                                        {{ $item->is_active ? 'Aktif' : 'Pasif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.nearby-places.edit', $item) }}" class="btn btn-sm btn-outline-primary me-50">
                                    <i data-feather="edit-2"></i> Düzenle
                                </a>
                                <form method="POST" action="{{ route('admin.nearby-places.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Bu noktayı silmek istediğinize emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Henüz kayıtlı yakın yer / ulaşım noktası bulunmuyor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateNewIconPreview(icon) {
            const preview = document.getElementById('new_icon_preview');
            if (preview) {
                preview.textContent = icon;
            }
        }
    </script>
@endpush

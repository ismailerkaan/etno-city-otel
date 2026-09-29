@extends('admin.layouts.app')

@section('title', 'Noktayı Düzenle - '.$place->title)

@section('content')
    <div class="content-header row mb-2">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Noktayı Düzenle</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Yönetim Paneli</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.nearby-places.index') }}">Harita & Yakın Yerler</a></li>
                            <li class="breadcrumb-item active">{{ $place->title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
            <a href="{{ route('admin.nearby-places.index') }}" class="btn btn-outline-secondary">
                <i data-feather="arrow-left" class="me-50"></i> Geri Dön
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><i data-feather="edit" class="me-50"></i> Yakın Yer / Ulaşım Noktası Detayları</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.nearby-places.update', $place) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- İkon Seçimi --}}
                    <div class="col-md-3 col-12 mb-1">
                        <label class="form-label fw-bold" for="icon">İkon Seçin</label>
                        <select class="form-select" id="icon" name="icon" required onchange="updateIconPreview(this.value)">
                            @foreach ($availableIcons as $iconCode => $iconName)
                                <option value="{{ $iconCode }}" {{ old('icon', $place->icon) === $iconCode ? 'selected' : '' }}>{{ $iconName }}</option>
                            @endforeach
                        </select>
                        <div class="mt-1 d-flex align-items-center gap-2">
                            <span class="small text-muted">Önizleme:</span>
                            <div class="p-1 bg-light rounded d-inline-flex align-items-center justify-content-center text-primary" style="width: 38px; height: 38px;">
                                <span class="material-symbols-outlined" id="icon_preview" style="font-size: 24px;">{{ old('icon', $place->icon) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Başlık (TR) --}}
                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label fw-bold" for="title">Nokta / Yer Adı (TR) <span class="text-danger">*</span></label>
                        <input class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $place->title) }}" placeholder="Örn: Bodrum-Milas Airport (BJV)" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Başlık (EN) --}}
                    <div class="col-md-5 col-12 mb-1">
                        <label class="form-label fw-bold text-info" for="title_en">Place Name (EN)</label>
                        <input class="form-control" id="title_en" name="title_en" value="{{ old('title_en', $place->title_en) }}" placeholder="Ex: Bodrum-Milas Airport (BJV)">
                    </div>

                    {{-- Ulaşım Açıklaması (TR) --}}
                    <div class="col-md-5 col-12 mb-1">
                        <label class="form-label fw-bold" for="description">Ulaşım / Süre Açıklaması (TR)</label>
                        <input class="form-control" id="description" name="description" value="{{ old('description', $place->description) }}" placeholder="Örn: Özel Şoför: 35 dk · Doğrudan Helikopter: 10 dk">
                    </div>

                    {{-- Ulaşım Açıklaması (EN) --}}
                    <div class="col-md-5 col-12 mb-1">
                        <label class="form-label fw-bold text-info" for="description_en">Transit / Duration (EN)</label>
                        <input class="form-control" id="description_en" name="description_en" value="{{ old('description_en', $place->description_en) }}" placeholder="Ex: Private Chauffeur: 35 min · Direct Helicopter: 10 min">
                    </div>

                    {{-- Mesafe --}}
                    <div class="col-md-2 col-6 mb-1">
                        <label class="form-label fw-bold" for="distance">Mesafe</label>
                        <input class="form-control" id="distance" name="distance" value="{{ old('distance', $place->distance) }}" placeholder="Örn: 38 km">
                    </div>

                    {{-- Sıra --}}
                    <div class="col-md-3 col-6 mb-1">
                        <label class="form-label fw-bold" for="sort_order">Sıra <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $place->sort_order) }}" min="0" required>
                    </div>

                    {{-- Durum --}}
                    <div class="col-md-3 col-12 mb-1 d-flex align-items-center">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $place->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_active">Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-2 border-top pt-2">
                    <a href="{{ route('admin.nearby-places.index') }}" class="btn btn-outline-secondary">
                        <i data-feather="x" class="me-50"></i> İptal
                    </a>
                    <button class="btn btn-primary" type="submit">
                        <i data-feather="save" class="me-50"></i> Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateIconPreview(icon) {
            const preview = document.getElementById('icon_preview');
            if (preview) {
                preview.textContent = icon;
            }
        }
    </script>
@endpush

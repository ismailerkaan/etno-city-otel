@extends('admin.layouts.app')

@section('title', 'Sessiz Olanaklar')

@section('content')
    {{-- 1. Bölüm Başlığı & Açıklaması --}}
    <div class="card border-top-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title text-primary"><i data-feather="type" class="me-50"></i> Bölüm Başlığı ve Açıklaması</h4>
                <p class="card-text text-muted mb-0">Anasayfadaki "Sessiz Olanaklar / Mimari & Özen" alanının başlık ve açıklama metinleri.</p>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.amenities.section-update') }}">
                @csrf
                @method('PUT')
                <div class="row">
                    {{-- Türkçe Başlıklar --}}
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold" for="amenities_eyebrow">Üst Etiket (TR)</label>
                        <input class="form-control" id="amenities_eyebrow" name="amenities_eyebrow" value="{{ old('amenities_eyebrow', $siteSetting->amenities_eyebrow) }}" placeholder="Örn: Sessiz Olanaklar">
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold" for="amenities_title">Ana Başlık (TR)</label>
                        <input class="form-control" id="amenities_title" name="amenities_title" value="{{ old('amenities_title', $siteSetting->amenities_title) }}" placeholder="Örn: Mimari & Özen" required>
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label" for="amenities_subtitle">Açıklama (TR)</label>
                        <textarea class="form-control" id="amenities_subtitle" name="amenities_subtitle" rows="2" placeholder="Bölüm tanıtım açıklaması...">{{ old('amenities_subtitle', $siteSetting->amenities_subtitle) }}</textarea>
                    </div>

                    {{-- İngilizce Başlıklar --}}
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold text-info" for="amenities_eyebrow_en">Eyebrow (EN)</label>
                        <input class="form-control" id="amenities_eyebrow_en" name="amenities_eyebrow_en" value="{{ old('amenities_eyebrow_en', $siteSetting->amenities_eyebrow_en) }}" placeholder="Ex: Quiet Facilities">
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold text-info" for="amenities_title_en">Title (EN)</label>
                        <input class="form-control" id="amenities_title_en" name="amenities_title_en" value="{{ old('amenities_title_en', $siteSetting->amenities_title_en) }}" placeholder="Ex: Curated Architecture & Care">
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label text-info" for="amenities_subtitle_en">Subtitle (EN)</label>
                        <textarea class="form-control" id="amenities_subtitle_en" name="amenities_subtitle_en" rows="2" placeholder="English description...">{{ old('amenities_subtitle_en', $siteSetting->amenities_subtitle_en) }}</textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">
                        <i data-feather="save" class="me-50"></i> Başlık Bilgilerini Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. Yeni Olanak Ekleme Kartı --}}
    <div class="card border-top-success">
        <div class="card-header">
            <h4 class="card-title text-success"><i data-feather="plus-circle" class="me-50"></i> Yeni Olanak Ekle</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.amenities.store') }}">
                @csrf
                <div class="row">
                    {{-- İkon Seçimi --}}
                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label fw-bold" for="new_icon">İkon Seçin</label>
                        <select class="form-select" id="new_icon" name="icon" required onchange="updateNewIconPreview(this.value)">
                            @foreach ($availableIcons as $iconCode => $iconName)
                                <option value="{{ $iconCode }}">{{ $iconName }} ({{ $iconCode }})</option>
                            @endforeach
                        </select>
                        <div class="mt-1 d-flex align-items-center gap-2">
                            <span class="small text-muted">Önizleme:</span>
                            <div class="p-1 bg-light rounded d-inline-flex align-items-center justify-content-center text-primary" style="width: 38px; height: 38px;">
                                <span class="material-symbols-outlined" id="new_icon_preview" style="font-size: 26px;">pool</span>
                            </div>
                        </div>
                    </div>

                    {{-- TR / EN Başlık --}}
                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label fw-bold" for="new_title">Başlık (TR)</label>
                        <input class="form-control @error('title') is-invalid @enderror" id="new_title" name="title" value="{{ old('title') }}" placeholder="Örn: Sonsuzluk Tuzlu Su Havuzu" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label fw-bold text-info" for="new_title_en">Title (EN)</label>
                        <input class="form-control" id="new_title_en" name="title_en" value="{{ old('title_en') }}" placeholder="Ex: Infinity Saltwater Pool">
                    </div>

                    {{-- TR / EN Açıklama --}}
                    <div class="col-md-6 col-12 mb-1">
                        <label class="form-label" for="new_desc">Açıklama (TR)</label>
                        <textarea class="form-control" id="new_desc" name="description" rows="2" placeholder="Olanak açıklaması...">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-md-6 col-12 mb-1">
                        <label class="form-label text-info" for="new_desc_en">Description (EN)</label>
                        <textarea class="form-control" id="new_desc_en" name="description_en" rows="2" placeholder="English description...">{{ old('description_en') }}</textarea>
                    </div>

                    {{-- Sıra & Aktiflik --}}
                    <div class="col-md-3 col-6 mb-1">
                        <label class="form-label" for="new_sort">Sıra No</label>
                        <input class="form-control" id="new_sort" type="number" min="0" name="sort_order" value="{{ old('sort_order', ($amenities->max('sort_order') ?? 0) + 1) }}" required>
                    </div>
                    <div class="col-md-3 col-6 mb-1 d-flex align-items-center pt-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" id="new_active" type="checkbox" name="is_active" value="1" checked>
                            <label class="form-check-label fw-bold" for="new_active">Aktif (Yayında)</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-12 mb-1 d-flex align-items-end justify-content-end">
                        <button class="btn btn-success w-100" type="submit">
                            <i data-feather="plus" class="me-50"></i> Bu Olanağı Ekle
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- 3. Mevcut Olanaklar Listesi (Toplu Düzenleme) --}}
    @if ($amenities->isNotEmpty())
        <form method="POST" action="{{ route('admin.amenities.batch-update') }}">
            @csrf
            @method('PUT')

            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h4 class="mb-25">Mevcut Olanaklar ({{ $amenities->count() }})</h4>
                    <span class="text-muted small">İstediğiniz alanları düzenleyip tek seferde kaydedebilirsiniz.</span>
                </div>
                <button class="btn btn-primary btn-lg shadow" type="submit">
                    <i data-feather="save" class="me-50"></i> Tüm Olanakları Kaydet
                </button>
            </div>

            <div class="row match-height">
                @foreach ($amenities as $item)
                    <div class="col-xl-6 col-12">
                        <div class="card border">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-1">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="p-1 bg-white rounded shadow-sm d-flex align-items-center justify-content-center text-secondary border" style="width: 42px; height: 42px;">
                                        <span class="material-symbols-outlined" id="icon_display_{{ $item->id }}" style="font-size: 26px;">{{ $item->icon }}</span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold">{{ $item->title }}</h5>
                                        <small class="text-muted">#{{ $item->id }} · İkon: <code>{{ $item->icon }}</code></small>
                                    </div>
                                </div>
                                <button class="btn btn-outline-danger btn-sm" type="submit" form="delete-form-{{ $item->id }}" title="Sil">
                                    <i data-feather="trash-2"></i> Sil
                                </button>
                            </div>
                            <div class="card-body pt-2">
                                <div class="row">
                                    {{-- İkon Seçimi --}}
                                    <div class="col-12 mb-1">
                                        <label class="form-label fw-bold" for="icon_{{ $item->id }}">İkonu Değiştir</label>
                                        <select class="form-select form-select-sm" id="icon_{{ $item->id }}" name="amenities[{{ $item->id }}][icon]" onchange="document.getElementById('icon_display_{{ $item->id }}').innerText = this.value">
                                            @foreach ($availableIcons as $iconCode => $iconName)
                                                <option value="{{ $iconCode }}" @selected($item->icon === $iconCode)>{{ $iconName }} ({{ $iconCode }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- TR Başlık & EN Başlık --}}
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label fw-bold text-primary" for="title_{{ $item->id }}">Başlık (TR)</label>
                                        <input class="form-control" id="title_{{ $item->id }}" name="amenities[{{ $item->id }}][title]" value="{{ $item->title }}" required>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label fw-bold text-info" for="title_en_{{ $item->id }}">Title (EN)</label>
                                        <input class="form-control" id="title_en_{{ $item->id }}" name="amenities[{{ $item->id }}][title_en]" value="{{ $item->title_en }}">
                                    </div>

                                    {{-- TR Açıklama & EN Açıklama --}}
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label text-muted" for="desc_{{ $item->id }}">Açıklama (TR)</label>
                                        <textarea class="form-control" id="desc_{{ $item->id }}" name="amenities[{{ $item->id }}][description]" rows="3">{{ $item->description }}</textarea>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label text-muted" for="desc_en_{{ $item->id }}">Description (EN)</label>
                                        <textarea class="form-control" id="desc_en_{{ $item->id }}" name="amenities[{{ $item->id }}][description_en]" rows="3">{{ $item->description_en }}</textarea>
                                    </div>

                                    {{-- Sıra & Aktiflik --}}
                                    <div class="col-6 mb-1">
                                        <label class="form-label" for="sort_{{ $item->id }}">Sıra Numarası</label>
                                        <input class="form-control" id="sort_{{ $item->id }}" type="number" min="0" name="amenities[{{ $item->id }}][sort_order]" value="{{ $item->sort_order }}" required>
                                    </div>
                                    <div class="col-6 mb-1 d-flex align-items-center pt-2">
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="amenities[{{ $item->id }}][is_active]" value="0">
                                            <input class="form-check-input" id="active_{{ $item->id }}" name="amenities[{{ $item->id }}][is_active]" type="checkbox" value="1" @checked($item->is_active)>
                                            <label class="form-check-label fw-bold" for="active_{{ $item->id }}">Yayında</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>

        {{-- Silme Formları --}}
        @foreach ($amenities as $item)
            <form id="delete-form-{{ $item->id }}" method="POST" action="{{ route('admin.amenities.destroy', $item) }}" style="display:none;" onsubmit="return confirm('Bu olanağı silmek istediğinize emin misiniz?')">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    @else
        <div class="alert alert-info">
            <div class="alert-body">Henüz olanak eklenmedi. Yukarıdaki formdan yeni olanak ekleyebilirsiniz.</div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    function updateNewIconPreview(icon) {
        const preview = document.getElementById('new_icon_preview');
        if (preview) {
            preview.innerText = icon;
        }
    }
</script>
@endpush

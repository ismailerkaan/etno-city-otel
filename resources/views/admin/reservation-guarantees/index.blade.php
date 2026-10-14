@extends('admin.layouts.app')

@section('title', 'Rezervasyon Avantajları')

@section('content')
    @if (!empty($tableMissing))
        <div class="alert alert-warning p-2 mb-2 shadow-sm border-start-warning border-start-3">
            <h5 class="alert-heading mb-1"><i data-feather="alert-triangle"></i> Veritabanı Tablosu Henüz Oluşturulmadı</h5>
            <p class="mb-0">Veritabanında <code>reservation_guarantees</code> tablosu henüz mevcut değil. Lütfen terminalinizde veya Docker ortamında <code>php artisan migrate</code> komutunu çalıştırınız.</p>
        </div>
    @endif

    {{-- 1. Bölüm Başlığı & Açıklaması --}}
    <div class="card border-top-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title text-primary"><i data-feather="shield" class="me-50"></i> Bölüm Başlıkları</h4>
                <p class="card-text text-muted mb-0">Rezervasyon odalar sayfasındaki "Rezervasyon Avantajları & Güvenceleri" alanının başlıkları.</p>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.reservation-guarantees.section-update') }}">
                @csrf
                @method('PUT')
                <div class="row">
                    {{-- Türkçe Başlıklar --}}
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold" for="guarantees_eyebrow">Üst Etiket (TR)</label>
                        <input class="form-control" id="guarantees_eyebrow" name="guarantees_eyebrow" value="{{ old('guarantees_eyebrow', $siteSetting->guarantees_eyebrow ?? 'Doğrudan Rezervasyon') }}" placeholder="Örn: Doğrudan Rezervasyon">
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold" for="guarantees_title">Ana Başlık (TR)</label>
                        <input class="form-control" id="guarantees_title" name="guarantees_title" value="{{ old('guarantees_title', $siteSetting->guarantees_title ?? 'Doğrudan Rezervasyon Avantajları') }}" placeholder="Örn: Doğrudan Rezervasyon Avantajları" required>
                    </div>

                    {{-- İngilizce Başlıklar --}}
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold text-info" for="guarantees_eyebrow_en">Eyebrow (EN)</label>
                        <input class="form-control" id="guarantees_eyebrow_en" name="guarantees_eyebrow_en" value="{{ old('guarantees_eyebrow_en', $siteSetting->guarantees_eyebrow_en ?? 'Book Direct') }}" placeholder="Ex: Book Direct">
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold text-info" for="guarantees_title_en">Title (EN)</label>
                        <input class="form-control" id="guarantees_title_en" name="guarantees_title_en" value="{{ old('guarantees_title_en', $siteSetting->guarantees_title_en ?? 'Direct Reservation Benefits') }}" placeholder="Ex: Direct Reservation Benefits">
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

    {{-- 2. Yeni Taahhüt / İçerik Maddesi Ekleme Kartı --}}
    <div class="card border-top-success">
        <div class="card-header">
            <h4 class="card-title text-success"><i data-feather="plus-circle" class="me-50"></i> Yeni Avantaj / Güvence Maddesi Ekle</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.reservation-guarantees.store') }}">
                @csrf
                <div class="row">
                    {{-- İkon Seçimi --}}
                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label fw-bold" for="new_icon_select">İkon Seçin</label>
                        <select class="form-select" id="new_icon_select" onchange="syncNewIcon(this.value)">
                            @foreach ($availableIcons as $iconCode => $iconName)
                                <option value="{{ $iconCode }}">{{ $iconName }} ({{ $iconCode }})</option>
                            @endforeach
                            <option value="custom">-- Özel İkon Kodu Yaz --</option>
                        </select>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i data-feather="hash"></i></span>
                            <input class="form-control @error('icon') is-invalid @enderror" id="new_icon" name="icon" value="{{ old('icon', 'verified') }}" placeholder="Material Symbol İkon Kodu" required oninput="updateNewIconPreview(this.value)">
                        </div>
                        <div class="mt-1 d-flex align-items-center gap-2">
                            <span class="small text-muted">Önizleme:</span>
                            <div class="p-1 bg-light rounded d-inline-flex align-items-center justify-content-center text-primary border" style="width: 42px; height: 42px;">
                                <span class="material-symbols-outlined" id="new_icon_preview" style="font-size: 28px;">{{ old('icon', 'verified') }}</span>
                            </div>
                            <small class="text-muted"><a href="https://fonts.google.com/icons" target="_blank" class="text-secondary text-decoration-underline">Tüm Material İkonları İncele</a></small>
                        </div>
                    </div>

                    {{-- TR / EN Başlık --}}
                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label fw-bold" for="new_title">Başlık (TR)</label>
                        <input class="form-control @error('title') is-invalid @enderror" id="new_title" name="title" value="{{ old('title') }}" placeholder="Örn: Doğrudan Fiyat Güvencesi" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 col-12 mb-1">
                        <label class="form-label fw-bold text-info" for="new_title_en">Title (EN)</label>
                        <input class="form-control" id="new_title_en" name="title_en" value="{{ old('title_en') }}" placeholder="Ex: Direct Rate Assurance">
                    </div>

                    {{-- TR / EN Açıklama --}}
                    <div class="col-md-6 col-12 mb-1">
                        <label class="form-label" for="new_desc">Açıklama (TR)</label>
                        <textarea class="form-control" id="new_desc" name="description" rows="2" placeholder="Taahhüt veya garanti açıklaması...">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-md-6 col-12 mb-1">
                        <label class="form-label text-info" for="new_desc_en">Description (EN)</label>
                        <textarea class="form-control" id="new_desc_en" name="description_en" rows="2" placeholder="English description...">{{ old('description_en') }}</textarea>
                    </div>

                    {{-- Sıra & Aktiflik --}}
                    <div class="col-md-3 col-6 mb-1">
                        <label class="form-label" for="new_sort">Sıra No</label>
                        <input class="form-control" id="new_sort" type="number" min="0" name="sort_order" value="{{ old('sort_order', ($guarantees->max('sort_order') ?? 0) + 1) }}" required>
                    </div>
                    <div class="col-md-3 col-6 mb-1 d-flex align-items-center pt-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" id="new_active" type="checkbox" name="is_active" value="1" checked>
                            <label class="form-check-label fw-bold" for="new_active">Aktif (Yayında)</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-12 mb-1 d-flex align-items-end justify-content-end">
                        <button class="btn btn-success w-100" type="submit">
                            <i data-feather="plus" class="me-50"></i> Bu Avantajı Ekle
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- 3. Mevcut Taahhüt & İçerikler Listesi (Toplu Düzenleme) --}}
    @if ($guarantees->isNotEmpty())
        <form method="POST" action="{{ route('admin.reservation-guarantees.batch-update') }}">
            @csrf
            @method('PUT')

            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
                <div>
                    <h4 class="mb-25">Mevcut Avantaj & Güvence Maddeleri ({{ $guarantees->count() }})</h4>
                    <span class="text-muted small">İstediğiniz alanları düzenleyip tek seferde kaydedebilirsiniz. Kullanıcı istediği kadar madde ekleyebilir.</span>
                </div>
                <div class="d-flex gap-1">
                    <button class="btn btn-outline-secondary" type="submit" form="seed-defaults-form">
                        <i data-feather="refresh-cw" class="me-50"></i> Orijinal 4 Maddeyi Ekle / Geri Getir
                    </button>
                    <button class="btn btn-primary btn-lg shadow" type="submit">
                        <i data-feather="save" class="me-50"></i> Tüm Değişiklikleri Kaydet
                    </button>
                </div>
            </div>

            <div class="row match-height">
                @foreach ($guarantees as $item)
                    <div class="col-xl-6 col-12">
                        <div class="card border">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-1">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="p-1 bg-white rounded shadow-sm d-flex align-items-center justify-content-center text-secondary border" style="width: 44px; height: 44px;">
                                        <span class="material-symbols-outlined" id="icon_display_{{ $item->id }}" style="font-size: 28px;">{{ $item->icon }}</span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold">{{ $item->title }}</h5>
                                        <small class="text-muted">#{{ $item->id }} · İkon: <code id="icon_code_{{ $item->id }}">{{ $item->icon }}</code></small>
                                    </div>
                                </div>
                                <button class="btn btn-outline-danger btn-sm" type="submit" form="delete-form-{{ $item->id }}" title="Sil">
                                    <i data-feather="trash-2"></i> Sil
                                </button>
                            </div>
                            <div class="card-body pt-2">
                                <div class="row">
                                    {{-- İkon Seçimi & Özel İkon Kodu --}}
                                    <div class="col-md-6 col-12 mb-1">
                                        <label class="form-label fw-bold" for="icon_select_{{ $item->id }}">Hızlı İkon Seç</label>
                                        <select class="form-select form-select-sm" id="icon_select_{{ $item->id }}" onchange="syncItemIcon({{ $item->id }}, this.value)">
                                            @foreach ($availableIcons as $iconCode => $iconName)
                                                <option value="{{ $iconCode }}" @selected($item->icon === $iconCode)>{{ $iconName }} ({{ $iconCode }})</option>
                                            @endforeach
                                            <option value="custom" @selected(!array_key_exists($item->icon, $availableIcons))>-- Özel İkon --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12 mb-1">
                                        <label class="form-label fw-bold" for="icon_input_{{ $item->id }}">İkon Kodu</label>
                                        <input class="form-control form-control-sm" id="icon_input_{{ $item->id }}" name="guarantees[{{ $item->id }}][icon]" value="{{ $item->icon }}" required oninput="updateItemIconPreview({{ $item->id }}, this.value)">
                                    </div>

                                    {{-- TR Başlık & EN Başlık --}}
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label fw-bold text-primary" for="title_{{ $item->id }}">Başlık (TR)</label>
                                        <input class="form-control" id="title_{{ $item->id }}" name="guarantees[{{ $item->id }}][title]" value="{{ $item->title }}" required>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label fw-bold text-info" for="title_en_{{ $item->id }}">Title (EN)</label>
                                        <input class="form-control" id="title_en_{{ $item->id }}" name="guarantees[{{ $item->id }}][title_en]" value="{{ $item->title_en }}">
                                    </div>

                                    {{-- TR Açıklama & EN Açıklama --}}
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label text-muted" for="desc_{{ $item->id }}">Açıklama (TR)</label>
                                        <textarea class="form-control" id="desc_{{ $item->id }}" name="guarantees[{{ $item->id }}][description]" rows="3">{{ $item->description }}</textarea>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label text-muted" for="desc_en_{{ $item->id }}">Description (EN)</label>
                                        <textarea class="form-control" id="desc_en_{{ $item->id }}" name="guarantees[{{ $item->id }}][description_en]" rows="3">{{ $item->description_en }}</textarea>
                                    </div>

                                    {{-- Sıra & Aktiflik --}}
                                    <div class="col-6 mb-1">
                                        <label class="form-label" for="sort_{{ $item->id }}">Sıra Numarası</label>
                                        <input class="form-control" id="sort_{{ $item->id }}" type="number" min="0" name="guarantees[{{ $item->id }}][sort_order]" value="{{ $item->sort_order }}" required>
                                    </div>
                                    <div class="col-6 mb-1 d-flex align-items-center pt-2">
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="guarantees[{{ $item->id }}][is_active]" value="0">
                                            <input class="form-check-input" id="active_{{ $item->id }}" name="guarantees[{{ $item->id }}][is_active]" type="checkbox" value="1" @checked($item->is_active)>
                                            <label class="form-check-label fw-bold" for="active_{{ $item->id }}">Yayında</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-end mt-2 mb-3">
                <button class="btn btn-primary btn-lg shadow" type="submit">
                    <i data-feather="save" class="me-50"></i> Tüm Değişiklikleri Kaydet
                </button>
            </div>
        </form>

        {{-- Silme Formları --}}
        @foreach ($guarantees as $item)
            <form id="delete-form-{{ $item->id }}" method="POST" action="{{ route('admin.reservation-guarantees.destroy', $item) }}" style="display:none;" onsubmit="return confirm('Bu avantaj / güvence maddesini silmek istediğinize emin misiniz?')">
                @csrf
                @method('DELETE')
            </form>
        @endforeach

        <form id="seed-defaults-form" method="POST" action="{{ route('admin.reservation-guarantees.seed-defaults') }}" style="display:none;" onsubmit="return confirm('Orijinal 4 varsayılan madde eklenecektir / geri getirilecektir. Onaylıyor musunuz?')">
            @csrf
        </form>
    @else
        <div class="card text-center p-4">
            <div class="card-body">
                <i data-feather="shield" class="text-primary mb-2" style="width: 48px; height: 48px;"></i>
                <h4 class="fw-bold">Henüz avantaj veya güvence maddesi bulunmuyor</h4>
                <p class="text-muted mb-2">Ön yüzde yer alan varsayılan rezervasyon avantajlarını tek tıkla yükleyebilirsiniz.</p>
                <form method="POST" action="{{ route('admin.reservation-guarantees.seed-defaults') }}">
                    @csrf
                    <button class="btn btn-primary btn-lg shadow" type="submit">
                        <i data-feather="download" class="me-50"></i> Orijinal 4 Maddeyi Yükle
                    </button>
                </form>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    function syncNewIcon(val) {
        if (val !== 'custom') {
            document.getElementById('new_icon').value = val;
            updateNewIconPreview(val);
        }
    }

    function updateNewIconPreview(icon) {
        const preview = document.getElementById('new_icon_preview');
        if (preview) {
            preview.innerText = icon.trim() || 'help_outline';
        }
    }

    function syncItemIcon(id, val) {
        if (val !== 'custom') {
            const input = document.getElementById('icon_input_' + id);
            if (input) {
                input.value = val;
                updateItemIconPreview(id, val);
            }
        }
    }

    function updateItemIconPreview(id, icon) {
        const preview = document.getElementById('icon_display_' + id);
        const codeEl = document.getElementById('icon_code_' + id);
        if (preview) {
            preview.innerText = icon.trim() || 'help_outline';
        }
        if (codeEl) {
            codeEl.innerText = icon.trim();
        }
    }
</script>
@endpush

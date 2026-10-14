@php
    $existingImages = $roomType->exists ? $roomType->images->keyBy('id') : collect();
    $formImages = old('images', $roomType->exists ? $roomType->images->map(fn ($image) => [
        'id' => $image->id,
        'preview_url' => $image->imageUrl(),
        'alt_text' => $image->alt_text,
        'alt_text_en' => $image->alt_text_en,
        'is_primary' => $image->is_primary,
    ])->all() : []);
    $formImages = collect($formImages)->map(function (array $image) use ($existingImages): array {
        if (! empty($image['id']) && empty($image['preview_url'])) {
            $image['preview_url'] = $existingImages->get((int) $image['id'])?->imageUrl();
        }

        return $image;
    })->all();
    $formImages = array_pad($formImages, max(1, count($formImages)), []);
@endphp

@if ($errors->any())
    <div class="alert alert-danger"><div class="alert-body"><strong>Kaydedilemedi.</strong> Lütfen işaretli alanları kontrol edin.</div></div>
@endif

<div class="row">
    <div class="col-xl-8 col-12">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Oda Bilgileri</h4></div>
            <div class="card-body">
                <div class="row g-1">
                    <div class="col-md-6"><label class="form-label" for="name">Oda Adı (TR)</label><input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $roomType->name) }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="form-label" for="name_en">Oda Adı (EN)</label><input class="form-control" id="name_en" name="name_en" value="{{ old('name_en', $roomType->name_en) }}"></div>
                    <div class="col-12"><label class="form-label" for="slug">Adres Kısa Adı</label><input class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $roomType->slug) }}" placeholder="balkonlu-oda" required>@error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="form-label" for="description">Açıklama (TR)</label><textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $roomType->description) }}</textarea></div>
                    <div class="col-md-6"><label class="form-label" for="description_en">Açıklama (EN)</label><textarea class="form-control" id="description_en" name="description_en" rows="5">{{ old('description_en', $roomType->description_en) }}</textarea></div>
                    <div class="col-md-4"><label class="form-label" for="nightly_price">Gecelik Fiyat (₺)</label><input class="form-control @error('nightly_price') is-invalid @enderror" id="nightly_price" name="nightly_price" type="number" min="0" step="0.01" value="{{ old('nightly_price', $roomType->nightly_price) }}" required>@error('nightly_price')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-4"><label class="form-label" for="max_adults">Maks. Yetişkin</label><input class="form-control" id="max_adults" name="max_adults" type="number" min="1" value="{{ old('max_adults', $roomType->max_adults ?: 2) }}" required></div>
                    <div class="col-md-4"><label class="form-label" for="max_children">Maks. Çocuk</label><input class="form-control" id="max_children" name="max_children" type="number" min="0" value="{{ old('max_children', $roomType->max_children ?: 0) }}" required></div>
                    <div class="col-md-4"><label class="form-label" for="size_sqm">Oda Alanı (m²)</label><input class="form-control" id="size_sqm" name="size_sqm" type="number" min="1" value="{{ old('size_sqm', $roomType->size_sqm) }}"></div>
                    <div class="col-md-4"><label class="form-label" for="bed_type">Yatak Tipi (TR)</label><input class="form-control" id="bed_type" name="bed_type" value="{{ old('bed_type', $roomType->bed_type) }}"></div>
                    <div class="col-md-4"><label class="form-label" for="bed_type_en">Yatak Tipi (EN)</label><input class="form-control" id="bed_type_en" name="bed_type_en" value="{{ old('bed_type_en', $roomType->bed_type_en) }}"></div>
                    <div class="col-md-6"><label class="form-label" for="features_text">Özellikler (TR)</label><textarea class="form-control" id="features_text" name="features_text" rows="6" placeholder="Her satıra bir özellik">{{ old('features_text', implode("\n", $roomType->features ?? [])) }}</textarea></div>
                    <div class="col-md-6"><label class="form-label" for="features_en_text">Özellikler (EN)</label><textarea class="form-control" id="features_en_text" name="features_en_text" rows="6" placeholder="One feature per line">{{ old('features_en_text', implode("\n", $roomType->features_en ?? [])) }}</textarea></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-12">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Yayın Ayarları</h4></div>
            <div class="card-body">
                <div class="mb-1"><label class="form-label" for="sort_order">Sıralama</label><input class="form-control" id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $roomType->sort_order ?? 0) }}" required></div>
                <div class="form-check form-switch"><input type="hidden" name="is_active" value="0"><input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $roomType->exists ? $roomType->is_active : true))><label class="form-check-label" for="is_active">Rezervasyona açık</label></div>
                <div class="form-check form-switch mt-1"><input type="hidden" name="show_on_home" value="0"><input class="form-check-input" id="show_on_home" name="show_on_home" type="checkbox" value="1" @checked(old('show_on_home', $roomType->show_on_home ?? false))><label class="form-check-label" for="show_on_home">Anasayfada göster</label></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><div><h4 class="card-title">Oda Görselleri</h4><p class="text-muted mb-0">JPG, PNG veya WebP yükleyebilirsiniz. En fazla 12 görsel, dosya başına 8 MB.</p></div></div>
    <div class="card-body" id="image-rows">
        @foreach ($formImages as $index => $image)
            <div class="border rounded p-1 mb-1 image-row">
                <input type="hidden" name="images[{{ $index }}][id]" value="{{ $image['id'] ?? '' }}">
                <div class="row g-1 align-items-center">
                    <div class="col-lg-2">@if(! empty($image['preview_url']))<img class="rounded border w-100" src="{{ $image['preview_url'] }}" alt="Mevcut oda görseli" style="height: 92px; object-fit: cover;">@else<div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted" style="height: 92px;"><i data-feather="image"></i></div>@endif</div>
                    <div class="col-lg-4"><label class="form-label">{{ ! empty($image['id']) ? 'Yeni Görselle Değiştir' : 'Görsel Dosyası' }}</label><input class="form-control @error('images.'.$index.'.file') is-invalid @enderror" name="images[{{ $index }}][file]" type="file" accept="image/jpeg,image/png,image/webp">@error('images.'.$index.'.file')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-lg-2"><label class="form-label">Alt Metin (TR)</label><input class="form-control" name="images[{{ $index }}][alt_text]" value="{{ $image['alt_text'] ?? '' }}"></div>
                    <div class="col-lg-2"><label class="form-label">Alt Metin (EN)</label><input class="form-control" name="images[{{ $index }}][alt_text_en]" value="{{ $image['alt_text_en'] ?? '' }}"></div>
                    <div class="col-lg-1"><div class="form-check"><input class="form-check-input primary-image" id="primary_{{ $index }}" name="images[{{ $index }}][is_primary]" type="checkbox" value="1" @checked($image['is_primary'] ?? false)><label class="form-check-label" for="primary_{{ $index }}">Kapak</label></div></div>
                    <div class="col-lg-1">@if(! empty($image['id']))<div class="form-check"><input type="hidden" name="images[{{ $index }}][remove]" value="0"><input class="form-check-input" id="remove_{{ $index }}" name="images[{{ $index }}][remove]" type="checkbox" value="1"><label class="form-check-label text-danger" for="remove_{{ $index }}">Sil</label></div>@endif</div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="card-footer"><button class="btn btn-outline-secondary" id="add-image" type="button"><i data-feather="plus" class="me-50"></i>Yeni Görsel Ekle</button></div>
</div>

<div class="d-flex flex-wrap gap-1"><button class="btn btn-primary" type="submit"><i data-feather="save" class="me-50"></i>Kaydet</button><a class="btn btn-outline-secondary" href="{{ route('admin.rooms.index') }}">Listeye Dön</a></div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rows = document.getElementById('image-rows');
    let nextImageIndex = rows.querySelectorAll('.image-row').length;
    document.getElementById('add-image').addEventListener('click', function () {
        const index = nextImageIndex++;
        rows.insertAdjacentHTML('beforeend', `<div class="border rounded p-1 mb-1 image-row"><div class="row g-1 align-items-center"><div class="col-lg-2"><div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted" style="height:92px"><span>Yeni görsel</span></div></div><div class="col-lg-4"><label class="form-label">Görsel Dosyası</label><input class="form-control" name="images[${index}][file]" type="file" accept="image/jpeg,image/png,image/webp"></div><div class="col-lg-2"><label class="form-label">Alt Metin (TR)</label><input class="form-control" name="images[${index}][alt_text]"></div><div class="col-lg-2"><label class="form-label">Alt Metin (EN)</label><input class="form-control" name="images[${index}][alt_text_en]"></div><div class="col-lg-1"><div class="form-check"><input class="form-check-input primary-image" id="primary_${index}" name="images[${index}][is_primary]" type="checkbox" value="1"><label class="form-check-label" for="primary_${index}">Kapak</label></div></div><div class="col-lg-1"><button class="btn btn-sm btn-outline-danger remove-new-image" type="button">Kaldır</button></div></div></div>`);
    });
    rows.addEventListener('click', function (event) { if (event.target.classList.contains('remove-new-image')) { event.target.closest('.image-row').remove(); } });
    rows.addEventListener('change', function (event) { if (event.target.classList.contains('primary-image') && event.target.checked) { rows.querySelectorAll('.primary-image').forEach(function (checkbox) { if (checkbox !== event.target) checkbox.checked = false; }); } });
});
</script>
@endpush

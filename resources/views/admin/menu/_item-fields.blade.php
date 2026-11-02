<div class="row">
    <div class="col-md-4 col-12 mb-1">
        <label class="form-label fw-bold" for="{{ $prefix }}category">Kategori <span class="text-danger">*</span></label>
        <select class="form-select @error('menu_category_id') is-invalid @enderror" id="{{ $prefix }}category" name="menu_category_id" required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ (int) old('menu_category_id', $item?->menu_category_id) === $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('menu_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 col-12 mb-1">
        <label class="form-label fw-bold" for="{{ $prefix }}name">Ürün Adı (TR) <span class="text-danger">*</span></label>
        <input class="form-control @error('name') is-invalid @enderror" id="{{ $prefix }}name" name="name" value="{{ old('name', $item?->name) }}" placeholder="Örn: Türk Kahvesi" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 col-12 mb-1">
        <label class="form-label fw-bold text-info" for="{{ $prefix }}name_en">Item Name (EN)</label>
        <input class="form-control" id="{{ $prefix }}name_en" name="name_en" value="{{ old('name_en', $item?->name_en) }}" placeholder="Ex: Turkish Coffee">
    </div>

    <div class="col-md-6 col-12 mb-1">
        <label class="form-label fw-bold" for="{{ $prefix }}description">Kısa Açıklama (TR)</label>
        <input class="form-control" id="{{ $prefix }}description" name="description" value="{{ old('description', $item?->description) }}" placeholder="Örn: Lokum ile servis edilir">
    </div>

    <div class="col-md-6 col-12 mb-1">
        <label class="form-label fw-bold text-info" for="{{ $prefix }}description_en">Short Description (EN)</label>
        <input class="form-control" id="{{ $prefix }}description_en" name="description_en" value="{{ old('description_en', $item?->description_en) }}" placeholder="Ex: Served with Turkish delight">
    </div>

    <div class="col-md-3 col-6 mb-1">
        <label class="form-label fw-bold" for="{{ $prefix }}price">Fiyat (TL) <span class="text-danger">*</span></label>
        <input class="form-control @error('price') is-invalid @enderror" type="number" step="0.01" min="0" id="{{ $prefix }}price" name="price" value="{{ old('price', $item ? (float) $item->price : null) }}" placeholder="Örn: 90" required>
        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3 col-6 mb-1">
        <label class="form-label fw-bold" for="{{ $prefix }}sort_order">Sıra <span class="text-danger">*</span></label>
        <input class="form-control" type="number" id="{{ $prefix }}sort_order" name="sort_order" value="{{ old('sort_order', $item?->sort_order ?? 0) }}" min="0" required>
    </div>

    <div class="col-md-3 col-12 mb-1 d-flex align-items-center">
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" id="{{ $prefix }}is_active" name="is_active" value="1" {{ old('is_active', $item?->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold" for="{{ $prefix }}is_active">Aktif</label>
        </div>
    </div>
</div>

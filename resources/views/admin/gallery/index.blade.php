@extends('admin.layouts.app')

@section('title', 'Galeri Yönetimi')

@section('content')
    {{-- 1. Anasayfa Galeri Bölüm Başlığı Düzenleme Kartı --}}
    <div class="card border-top-primary mb-2">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title text-primary"><i data-feather="type" class="me-50"></i> Anasayfa Galeri Başlığı</h4>
                <p class="card-text text-muted mb-0">Anasayfadaki "Görsel Arşiv / Atmosferik Anlar" alanının başlık metinleri.</p>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.gallery.section-update') }}">
                @csrf
                @method('PUT')
                <div class="row align-items-end">
                    <div class="col-md-3 mb-1">
                        <label class="form-label fw-bold" for="gallery_eyebrow">Üst Etiket (TR)</label>
                        <input class="form-control" id="gallery_eyebrow" name="gallery_eyebrow" value="{{ old('gallery_eyebrow', $siteSetting->gallery_eyebrow) }}" placeholder="Örn: Görsel Arşiv">
                    </div>
                    <div class="col-md-3 mb-1">
                        <label class="form-label fw-bold" for="gallery_title">Ana Başlık (TR)</label>
                        <input class="form-control" id="gallery_title" name="gallery_title" value="{{ old('gallery_title', $siteSetting->gallery_title) }}" placeholder="Örn: Atmosferik Anlar" required>
                    </div>
                    <div class="col-md-3 mb-1">
                        <label class="form-label fw-bold text-info" for="gallery_eyebrow_en">Eyebrow (EN)</label>
                        <input class="form-control" id="gallery_eyebrow_en" name="gallery_eyebrow_en" value="{{ old('gallery_eyebrow_en', $siteSetting->gallery_eyebrow_en) }}" placeholder="Ex: Visual Archive">
                    </div>
                    <div class="col-md-3 mb-1">
                        <label class="form-label fw-bold text-info" for="gallery_title_en">Title (EN)</label>
                        <input class="form-control" id="gallery_title_en" name="gallery_title_en" value="{{ old('gallery_title_en', $siteSetting->gallery_title_en) }}" placeholder="Ex: Atmospheric Moments">
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold" for="gallery_description">Açıklama / Alt Metin (TR)</label>
                        <textarea class="form-control" id="gallery_description" name="gallery_description" rows="2" placeholder="Örn: Ege'nin dingin sığınağını keşfedin...">{{ old('gallery_description', $siteSetting->gallery_description) }}</textarea>
                        <small class="text-muted">Galeri sayfasının en üstündeki tanıtım metni</small>
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold text-info" for="gallery_description_en">Description (EN)</label>
                        <textarea class="form-control" id="gallery_description_en" name="gallery_description_en" rows="2" placeholder="Ex: Immerse in the serene Aegean sanctuary...">{{ old('gallery_description_en', $siteSetting->gallery_description_en) }}</textarea>
                        <small class="text-muted">Introductory text on the English gallery page</small>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit">
                            <i data-feather="save" class="me-50"></i> Başlık ve Açıklamaları Kaydet
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. Galeri Yönetim Alanı --}}
    <div class="row">
        {{-- Sol Sütun: Galeriler / Kategoriler Listesi --}}
        <div class="col-lg-4 col-12 mb-2">
            <div class="card border">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"><i data-feather="folder" class="me-50"></i> Galeriler</h4>
                    <button class="btn btn-sm btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#newCategoryCollapse">
                        <i data-feather="plus"></i> Yeni Galeri
                    </button>
                </div>

                {{-- Yeni Galeri Ekleme Collapse Formu --}}
                <div class="collapse p-1 border-bottom bg-light" id="newCategoryCollapse">
                    <form method="POST" action="{{ route('admin.gallery.categories.store') }}">
                        @csrf
                        <div class="mb-1">
                            <label class="form-label fw-bold" for="cat_name">Galeri Adı (TR)</label>
                            <input class="form-control form-control-sm" id="cat_name" name="name" placeholder="Örn: Odalar & Süitler" required>
                        </div>
                        <div class="mb-1">
                            <label class="form-label fw-bold text-info" for="cat_name_en">Gallery Name (EN)</label>
                            <input class="form-control form-control-sm" id="cat_name_en" name="name_en" placeholder="Ex: Rooms & Suites">
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="cat_sort">Sıra Numarası</label>
                            <input class="form-control form-control-sm" id="cat_sort" type="number" min="0" name="sort_order" value="{{ ($categories->max('sort_order') ?? 0) + 1 }}" required>
                        </div>
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn btn-sm btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#newCategoryCollapse">İptal</button>
                            <button class="btn btn-sm btn-success" type="submit">Ekle</button>
                        </div>
                    </form>
                </div>

                {{-- Kategori Listesi --}}
                <div class="list-group list-group-flush">
                    @forelse ($categories as $cat)
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ ($selectedCategory && $selectedCategory->id === $cat->id) ? 'active bg-light-primary text-primary fw-bold' : '' }}">
                            <a href="{{ route('admin.gallery.index', ['category_id' => $cat->id]) }}" class="d-flex align-items-center gap-1 text-reset text-decoration-none flex-grow-1">
                                <i data-feather="{{ ($selectedCategory && $selectedCategory->id === $cat->id) ? 'folder-minus' : 'folder' }}"></i>
                                <span>{{ $cat->name }}</span>
                                <span class="badge rounded-pill bg-secondary ms-1">{{ $cat->images->count() }}</span>
                            </a>
                            <div class="d-flex align-items-center gap-50">
                                <form method="POST" action="{{ route('admin.gallery.categories.destroy', $cat) }}" onsubmit="return confirm('Bu galeriyi ve içindeki tüm fotoğrafları silmek istediğinize emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-icon btn-sm btn-flat-danger" type="submit" title="Galeriyi Sil">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-2 text-center text-muted">Henüz galeri oluşturulmadı.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sağ Sütun: Seçili Galerinin Fotoğrafları & Etiketleri --}}
        <div class="col-lg-8 col-12">
            @if ($selectedCategory)
                {{-- Fotoğraf Yükleme Kartı --}}
                <div class="card border mb-2">
                    <div class="card-header bg-light py-1">
                        <div>
                            <h4 class="card-title mb-25">
                                <span class="text-primary">{{ $selectedCategory->name }}</span> Galerisine Fotoğraf Yükle
                            </h4>
                            <small class="text-muted">JPG, PNG veya WebP görseller seçebilirsiniz (çoklu seçim desteklenir).</small>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <form method="POST" action="{{ route('admin.gallery.images.store', $selectedCategory) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row align-items-end">
                                <div class="col-md-9 mb-1">
                                    <label class="form-label" for="images">Yeni Fotoğraflar Seçin</label>
                                    <input class="form-control" id="images" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple required>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <button class="btn btn-success w-100" type="submit">
                                        <i data-feather="upload" class="me-50"></i> Yükle
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Mevcut Fotoğraflar ve Etiketleri --}}
                @if ($images->isNotEmpty())
                    <form method="POST" action="{{ route('admin.gallery.images.batch-update') }}">
                        @csrf
                        @method('PUT')

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h4 class="mb-25">{{ $selectedCategory->name }} Fotoğrafları ({{ $images->count() }})</h4>
                                <small class="text-muted">Fotoğrafların sol altındaki etiketleri düzenleyip tek seferde kaydedebilirsiniz.</small>
                            </div>
                            <button class="btn btn-primary btn-lg shadow" type="submit">
                                <i data-feather="save" class="me-50"></i> Tüm Etiketleri Kaydet
                            </button>
                        </div>

                        <div class="row match-height">
                            @foreach ($images as $img)
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="card border h-100">
                                        <div class="position-relative bg-dark" style="height: 220px;">
                                            <img src="{{ $img->imageUrl() }}" alt="{{ $img->tag }}" class="w-full h-full object-fit-cover" style="width: 100%; height: 100%; object-fit: cover;">
                                            
                                            {{-- Canlı Etiket Önizlemesi --}}
                                            @if ($img->tag)
                                                <div class="position-absolute bottom-0 start-0 m-1 bg-white text-dark px-1 py-50 rounded shadow-sm small fw-bold">
                                                    {{ $img->tag }}
                                                </div>
                                            @endif

                                            <button class="btn btn-icon btn-danger btn-sm position-absolute top-0 end-0 m-1 shadow" type="submit" form="delete-img-{{ $img->id }}" title="Bu Fotoğrafı Sil">
                                                <i data-feather="trash-2"></i>
                                            </button>
                                        </div>

                                        <div class="card-body p-1">
                                            {{-- Etiket TR --}}
                                            <div class="mb-1">
                                                <label class="form-label fw-bold text-primary small" for="tag_{{ $img->id }}">Fotoğraf Etiketi (TR)</label>
                                                <input class="form-control form-control-sm" id="tag_{{ $img->id }}" name="images[{{ $img->id }}][tag]" value="{{ $img->tag }}" placeholder="Örn: Sonsuzluk Tuzlu Su Ufku · 07:45">
                                            </div>

                                            {{-- Etiket EN --}}
                                            <div class="mb-1">
                                                <label class="form-label fw-bold text-info small" for="tag_en_{{ $img->id }}">Photo Tag (EN)</label>
                                                <input class="form-control form-control-sm" id="tag_en_{{ $img->id }}" name="images[{{ $img->id }}][tag_en]" value="{{ $img->tag_en }}" placeholder="Ex: Infinity Saltwater Horizon · 07:45">
                                            </div>

                                            <div class="row align-items-center">
                                                <div class="col-6">
                                                    <label class="form-label small" for="sort_{{ $img->id }}">Sıra No</label>
                                                    <input class="form-control form-control-sm" id="sort_{{ $img->id }}" type="number" min="0" name="images[{{ $img->id }}][sort_order]" value="{{ $img->sort_order }}" required>
                                                </div>
                                                <div class="col-6 pt-1">
                                                    <div class="form-check form-switch mb-0">
                                                        <input type="hidden" name="images[{{ $img->id }}][is_active]" value="0">
                                                        <input class="form-check-input" id="act_{{ $img->id }}" type="checkbox" name="images[{{ $img->id }}][is_active]" value="1" @checked($img->is_active)>
                                                        <label class="form-check-label small fw-bold" for="act_{{ $img->id }}">Aktif</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </form>

                    {{-- Silme formları --}}
                    @foreach ($images as $img)
                        <form id="delete-img-{{ $img->id }}" method="POST" action="{{ route('admin.gallery.images.destroy', $img) }}" style="display:none;" onsubmit="return confirm('Bu fotoğrafı silmek istediğinize emin misiniz?')">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach
                @else
                    <div class="alert alert-info">
                        <div class="alert-body">Bu galeride henüz fotoğraf yok. Yukarıdaki formdan fotoğraf yükleyebilirsiniz.</div>
                    </div>
                @endif
            @else
                <div class="alert alert-warning">
                    <div class="alert-body">Lütfen sol taraftan bir galeri seçin veya yeni bir galeri ekleyin.</div>
                </div>
            @endif
        </div>
    </div>
@endsection

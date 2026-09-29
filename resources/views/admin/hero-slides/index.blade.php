@extends('admin.layouts.app')

@section('title', 'Hero Slider')

@section('content')
    {{-- Yeni Görsel Yükleme --}}
    <div class="card">
        <div class="card-header">
            <div>
                <h4 class="card-title">Yeni Görseller Yükle</h4>
                <p class="card-text text-muted mb-0">JPG, PNG veya WebP görsel; görsel başına en fazla 5 MB.</p>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.hero-slides.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-9 mb-1">
                        <label class="form-label" for="images">Slider görselleri</label>
                        <input class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" id="images" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple required>
                        @error('images')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @error('images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-1">
                        <button class="btn btn-primary w-100" type="submit">
                            <i data-feather="upload" class="me-50"></i> Görselleri yükle
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if ($heroSlides->isNotEmpty())
        {{-- Toplu Güncelleme Formu --}}
        <form method="POST" action="{{ route('admin.hero-slides.batch-update') }}">
            @csrf
            @method('PUT')

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="mb-0">Mevcut Slide'lar ({{ $heroSlides->count() }})</h4>
                <button class="btn btn-success btn-lg shadow" type="submit">
                    <i data-feather="save" class="me-50"></i> Tüm Değişiklikleri Kaydet
                </button>
            </div>

            <div class="row match-height">
                @foreach ($heroSlides as $slide)
                    <div class="col-xl-6 col-12">
                        <div class="card border">
                            <div class="position-relative">
                                <img class="card-img-top" src="{{ asset('storage/'.$slide->image_path) }}" alt="Slide {{ $slide->id }}" style="height: 240px; object-fit: cover;">
                                <span class="badge bg-dark position-absolute top-0 start-0 m-1">#{{ $slide->id }}</span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    {{-- Türkçe Alanlar --}}
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label fw-bold text-primary" for="title_{{ $slide->id }}">Başlık (TR)</label>
                                        <input class="form-control" id="title_{{ $slide->id }}" name="slides[{{ $slide->id }}][title]" value="{{ $slide->title }}" placeholder="Örn: Zamansız Bir Kaçış">
                                    </div>
                                    {{-- İngilizce Başlık --}}
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label fw-bold text-info" for="title_en_{{ $slide->id }}">Title (EN)</label>
                                        <input class="form-control" id="title_en_{{ $slide->id }}" name="slides[{{ $slide->id }}][title_en]" value="{{ $slide->title_en }}" placeholder="Ex: A Timeless Escape">
                                    </div>

                                    {{-- Türkçe Açıklama --}}
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label text-muted" for="desc_{{ $slide->id }}">Açıklama (TR)</label>
                                        <textarea class="form-control" id="desc_{{ $slide->id }}" name="slides[{{ $slide->id }}][description]" rows="3" placeholder="Kısa açıklama metni">{{ $slide->description }}</textarea>
                                    </div>
                                    {{-- İngilizce Açıklama --}}
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label text-muted" for="desc_en_{{ $slide->id }}">Description (EN)</label>
                                        <textarea class="form-control" id="desc_en_{{ $slide->id }}" name="slides[{{ $slide->id }}][description_en]" rows="3" placeholder="Short description text">{{ $slide->description_en }}</textarea>
                                    </div>

                                    {{-- Sıra & Aktiflik --}}
                                    <div class="col-6 mb-1">
                                        <label class="form-label" for="sort_{{ $slide->id }}">Sıra Numarası</label>
                                        <input class="form-control" id="sort_{{ $slide->id }}" type="number" min="0" name="slides[{{ $slide->id }}][sort_order]" value="{{ $slide->sort_order }}" required>
                                    </div>
                                    <div class="col-6 mb-1 d-flex align-items-center pt-2">
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="slides[{{ $slide->id }}][is_active]" value="0">
                                            <input class="form-check-input" id="active_{{ $slide->id }}" name="slides[{{ $slide->id }}][is_active]" type="checkbox" value="1" @checked($slide->is_active)>
                                            <label class="form-check-label fw-bold" for="active_{{ $slide->id }}">Yayında (Aktif)</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-1 pt-1 border-top d-flex justify-content-end">
                                    <button class="btn btn-outline-danger btn-sm" type="submit" form="delete-form-{{ $slide->id }}">
                                        <i data-feather="trash-2" class="me-25"></i> Bu Görseli Sil
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </form>

        {{-- Her slide için bağımsız silme formları --}}
        @foreach ($heroSlides as $slide)
            <form id="delete-form-{{ $slide->id }}" method="POST" action="{{ route('admin.hero-slides.destroy', $slide) }}" style="display:none;" onsubmit="return confirm('Bu görseli silmek istediğinize emin misiniz?')">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    @else
        <div class="alert alert-info">
            <div class="alert-body">Henüz slider görseli yüklenmedi. Yukarıdaki alandan görsel yükleyerek başlayabilirsiniz.</div>
        </div>
    @endif
@endsection

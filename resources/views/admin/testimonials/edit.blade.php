@extends('admin.layouts.app')

@section('title', 'Yorumu Düzenle - '.$testimonial->author_name)

@section('content')
    <div class="content-header row mb-2">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Yorumu Düzenle</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Yönetim Paneli</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.testimonials.index') }}">Yorumlar</a></li>
                            <li class="breadcrumb-item active">{{ $testimonial->author_name }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary">
                <i data-feather="arrow-left" class="me-50"></i> Geri Dön
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><i data-feather="edit" class="me-50"></i> Misafir Yorum Detayları</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Misafir Adı --}}
                    <div class="col-md-6 col-12 mb-1">
                        <label class="form-label fw-bold" for="author_name">Misafir Adı / Soyadı <span class="text-danger">*</span></label>
                        <input class="form-control @error('author_name') is-invalid @enderror" id="author_name" name="author_name" value="{{ old('author_name', $testimonial->author_name) }}" required>
                        @error('author_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Puan / Yıldız --}}
                    <div class="col-md-3 col-6 mb-1">
                        <label class="form-label fw-bold" for="rating">Puan (Yıldız) <span class="text-danger">*</span></label>
                        <select class="form-select" id="rating" name="rating">
                            <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 Yıldız)</option>
                            <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (4 Yıldız)</option>
                            <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>⭐⭐⭐ (3 Yıldız)</option>
                            <option value="2" {{ old('rating', $testimonial->rating) == 2 ? 'selected' : '' }}>⭐⭐ (2 Yıldız)</option>
                            <option value="1" {{ old('rating', $testimonial->rating) == 1 ? 'selected' : '' }}>⭐ (1 Yıldız)</option>
                        </select>
                    </div>

                    {{-- Sıra --}}
                    <div class="col-md-3 col-6 mb-1">
                        <label class="form-label fw-bold" for="sort_order">Görüntüleme Sırası <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order) }}" min="0" required>
                    </div>

                    {{-- Yorum Metni (TR) --}}
                    <div class="col-md-6 col-12 mb-1">
                        <label class="form-label fw-bold" for="comment">Yorum Metni (TR) <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="4" required>{{ old('comment', $testimonial->comment) }}</textarea>
                        @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Yorum Metni (EN) --}}
                    <div class="col-md-6 col-12 mb-1">
                        <label class="form-label fw-bold text-info" for="comment_en">Review / Quote (EN)</label>
                        <textarea class="form-control" id="comment_en" name="comment_en" rows="4">{{ old('comment_en', $testimonial->comment_en) }}</textarea>
                    </div>

                    {{-- Avatar / Fotoğraf --}}
                    <div class="col-md-6 col-12 mb-1">
                        <label class="form-label" for="avatar">Fotoğraf / Avatar Değiştir</label>
                        <input class="form-control" type="file" id="avatar" name="avatar" accept="image/*">
                        @if ($testimonial->avatarUrl())
                            <div class="mt-1 d-flex align-items-center gap-2">
                                <img src="{{ $testimonial->avatarUrl() }}" alt="{{ $testimonial->author_name }}" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remove_avatar" name="remove_avatar" value="1">
                                    <label class="form-check-label text-danger small" for="remove_avatar">Fotoğrafı Kaldır</label>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Durum --}}
                    <div class="col-md-6 col-12 mb-1 d-flex align-items-center">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_active">Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-2 border-top pt-2">
                    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary">
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

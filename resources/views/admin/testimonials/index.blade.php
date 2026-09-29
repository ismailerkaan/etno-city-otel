@extends('admin.layouts.app')

@section('title', 'Misafir Yorumları')

@section('content')
    <div class="content-header row mb-2">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Misafir Yorumları</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Yönetim Paneli</a></li>
                            <li class="breadcrumb-item active">Yorumlar</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 1. BÖLÜM BAŞLIĞI VE AÇIKLAMASI AYARLARI --}}
    <div class="card border-top-primary mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title text-primary"><i data-feather="type" class="me-50"></i> Yorumlar Bölüm Başlığı ve Açıklaması</h4>
                <p class="card-text text-muted mb-0">Anasayfadaki misafir yorumları alanının üst etiketi, ana başlığı ve açıklama metni.</p>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.testimonials.section-update') }}">
                @csrf
                @method('PUT')
                <div class="row">
                    {{-- Türkçe Alanlar --}}
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold" for="testimonials_eyebrow">Üst Etiket (TR)</label>
                        <input class="form-control" id="testimonials_eyebrow" name="testimonials_eyebrow" value="{{ old('testimonials_eyebrow', $siteSetting->testimonials_eyebrow) }}" placeholder="Örn: Misafir Yansımaları">
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold" for="testimonials_title">Ana Başlık (TR)</label>
                        <input class="form-control" id="testimonials_title" name="testimonials_title" value="{{ old('testimonials_title', $siteSetting->testimonials_title) }}" placeholder="Örn: Eşsiz Deneyimler & Hatıralar" required>
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label fw-bold" for="testimonials_description">Bölüm Açıklaması (TR)</label>
                        <textarea class="form-control" id="testimonials_description" name="testimonials_description" rows="2" placeholder="Bölüm tanıtım açıklaması...">{{ old('testimonials_description', $siteSetting->testimonials_description) }}</textarea>
                    </div>

                    {{-- İngilizce Alanlar --}}
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold text-info" for="testimonials_eyebrow_en">Eyebrow (EN)</label>
                        <input class="form-control" id="testimonials_eyebrow_en" name="testimonials_eyebrow_en" value="{{ old('testimonials_eyebrow_en', $siteSetting->testimonials_eyebrow_en) }}" placeholder="Ex: Guest Reflections">
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold text-info" for="testimonials_title_en">Title (EN)</label>
                        <input class="form-control" id="testimonials_title_en" name="testimonials_title_en" value="{{ old('testimonials_title_en', $siteSetting->testimonials_title_en) }}" placeholder="Ex: Timeless Impressions">
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label fw-bold text-info" for="testimonials_description_en">Description (EN)</label>
                        <textarea class="form-control" id="testimonials_description_en" name="testimonials_description_en" rows="2" placeholder="English description...">{{ old('testimonials_description_en', $siteSetting->testimonials_description_en) }}</textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">
                        <i data-feather="save" class="me-50"></i> Bölüm Bilgilerini Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. YENİ YORUM EKLEME KARTI --}}
    <div class="card border-top-success mb-3">
        <div class="card-header">
            <h4 class="card-title text-success"><i data-feather="plus-circle" class="me-50"></i> Yeni Yorum Ekle</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- Misafir Adı --}}
                    <div class="col-md-6 col-12 mb-1">
                        <label class="form-label fw-bold" for="author_name">Misafir Adı / Soyadı <span class="text-danger">*</span></label>
                        <input class="form-control @error('author_name') is-invalid @enderror" id="author_name" name="author_name" value="{{ old('author_name') }}" placeholder="Örn: Lady Helena Vance-Cross" required>
                        @error('author_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Puan / Yıldız --}}
                    <div class="col-md-3 col-6 mb-1">
                        <label class="form-label fw-bold" for="rating">Puan (Yıldız) <span class="text-danger">*</span></label>
                        <select class="form-select" id="rating" name="rating">
                            <option value="5" {{ old('rating', 5) == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 Yıldız)</option>
                            <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (4 Yıldız)</option>
                            <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>⭐⭐⭐ (3 Yıldız)</option>
                            <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>⭐⭐ (2 Yıldız)</option>
                            <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>⭐ (1 Yıldız)</option>
                        </select>
                    </div>

                    {{-- Sıra --}}
                    <div class="col-md-3 col-6 mb-1">
                        <label class="form-label fw-bold" for="sort_order">Görüntüleme Sırası <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $nextSortOrder) }}" min="0" required>
                    </div>

                    {{-- Yorum Metni (TR) --}}
                    <div class="col-md-6 col-12 mb-1">
                        <label class="form-label fw-bold" for="comment">Yorum Metni (TR) <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="3" placeholder="Misafirin yorum veya alıntısı..." required>{{ old('comment') }}</textarea>
                        @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Yorum Metni (EN) --}}
                    <div class="col-md-6 col-12 mb-1">
                        <label class="form-label fw-bold text-info" for="comment_en">Review / Quote (EN)</label>
                        <textarea class="form-control" id="comment_en" name="comment_en" rows="3" placeholder="Guest comment in English...">{{ old('comment_en') }}</textarea>
                    </div>

                    {{-- Avatar / Fotoğraf --}}
                    <div class="col-md-6 col-12 mb-1">
                        <label class="form-label" for="avatar">Fotoğraf / Avatar (Opsiyonel)</label>
                        <input class="form-control" type="file" id="avatar" name="avatar" accept="image/*">
                    </div>

                    {{-- Durum --}}
                    <div class="col-md-6 col-12 mb-1 d-flex align-items-center">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_active">Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-1">
                    <button class="btn btn-success" type="submit">
                        <i data-feather="check" class="me-50"></i> Yorumu Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 3. MEVCUT YORUMLAR LİSTESİ --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title"><i data-feather="message-square" class="me-50"></i> Kayıtlı Yorumlar ({{ $testimonials->count() }})</h4>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">Sıra</th>
                        <th style="width: 240px;">Misafir</th>
                        <th>Yorum</th>
                        <th style="width: 120px;">Puan</th>
                        <th style="width: 100px;">Durum</th>
                        <th style="width: 160px;" class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($testimonials as $item)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark fw-bold">{{ $item->sort_order }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    @if ($item->avatarUrl())
                                        <img src="{{ $item->avatarUrl() }}" alt="{{ $item->author_name }}" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-light-primary text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">
                                            {{ mb_strtoupper(mb_substr($item->author_name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="fw-bold">{{ $item->author_name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 480px;" title="{{ $item->comment }}">
                                    “{{ $item->comment }}”
                                </div>
                                @if ($item->comment_en)
                                    <div class="text-truncate small text-info" style="max-width: 480px;" title="{{ $item->comment_en }}">
                                        “{{ $item->comment_en }}”
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="text-warning">
                                    @for ($i = 0; $i < $item->rating; $i++)
                                        ★
                                    @endfor
                                </div>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.testimonials.toggle-status', $item) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }} border-0" title="Durumu değiştirmek için tıklayın">
                                        {{ $item->is_active ? 'Aktif' : 'Pasif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.testimonials.edit', $item) }}" class="btn btn-sm btn-outline-primary me-50">
                                    <i data-feather="edit-2"></i> Düzenle
                                </a>
                                <form method="POST" action="{{ route('admin.testimonials.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Bu yorumu silmek istediğinize emin misiniz?');">
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
                                Henüz kayıtlı misafir yorumu bulunmuyor. Yukarıdaki formdan yeni yorum ekleyebilirsiniz.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Etkinlikler')

@section('content')
    {{-- 1. Anasayfa Bölüm Başlığı Düzenleme Kartı --}}
    <div class="card border-top-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title text-primary"><i data-feather="type" class="me-50"></i> Anasayfa Bölüm Başlığı</h4>
                <p class="card-text text-muted mb-0">Anasayfadaki "Villanın Ötesinde / Özel Deneyimler" alanının başlık ve açıklama metinleri.</p>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.events.section-update') }}">
                @csrf
                @method('PUT')
                <div class="row">
                    {{-- Türkçe --}}
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold" for="events_eyebrow">Üst Etiket (TR)</label>
                        <input class="form-control" id="events_eyebrow" name="events_eyebrow" value="{{ old('events_eyebrow', $siteSetting->events_eyebrow) }}" placeholder="Örn: Villanın Ötesinde">
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold" for="events_title">Ana Başlık (TR)</label>
                        <input class="form-control" id="events_title" name="events_title" value="{{ old('events_title', $siteSetting->events_title) }}" placeholder="Örn: Özel Deneyimler" required>
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label" for="events_subtitle">Açıklama (TR)</label>
                        <textarea class="form-control" id="events_subtitle" name="events_subtitle" rows="2" placeholder="Bölüm tanıtım metni...">{{ old('events_subtitle', $siteSetting->events_subtitle) }}</textarea>
                    </div>

                    {{-- İngilizce --}}
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold text-info" for="events_eyebrow_en">Eyebrow (EN)</label>
                        <input class="form-control" id="events_eyebrow_en" name="events_eyebrow_en" value="{{ old('events_eyebrow_en', $siteSetting->events_eyebrow_en) }}" placeholder="Ex: Beyond the Villa">
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label fw-bold text-info" for="events_title_en">Title (EN)</label>
                        <input class="form-control" id="events_title_en" name="events_title_en" value="{{ old('events_title_en', $siteSetting->events_title_en) }}" placeholder="Ex: Bespoke Chapters">
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label text-info" for="events_subtitle_en">Subtitle (EN)</label>
                        <textarea class="form-control" id="events_subtitle_en" name="events_subtitle_en" rows="2" placeholder="English description...">{{ old('events_subtitle_en', $siteSetting->events_subtitle_en) }}</textarea>
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

    {{-- 2. Etkinlikler Başlığı ve Yeni Ekle Butonu --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h4 class="mb-25">Tüm Etkinlikler & Deneyimler ({{ $events->count() }})</h4>
            <span class="text-muted small">Anasayfada yayınlanmasını istediğiniz etkinliklerin <strong>"Anasayfada Yayınla"</strong> seçeneğini aktif edebilirsiniz.</span>
        </div>
        <a class="btn btn-success btn-lg shadow" href="{{ route('admin.events.create') }}">
            <i data-feather="plus" class="me-50"></i> Yeni Etkinlik Ekle
        </a>
    </div>

    {{-- 3. Etkinlikler Tablosu / Kart Listesi --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Görsel</th>
                        <th>Kategori & Başlık</th>
                        <th>Açıklama</th>
                        <th class="text-center" style="width: 170px;">Anasayfada Yayın</th>
                        <th class="text-center" style="width: 100px;">Durum</th>
                        <th class="text-center" style="width: 80px;">Sıra</th>
                        <th class="text-end" style="width: 140px;">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                        <tr>
                            <td>
                                @if ($event->imageUrl())
                                    <img src="{{ $event->imageUrl() }}" alt="{{ $event->title }}" class="rounded object-fit-cover shadow-sm" style="width: 70px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 70px; height: 50px;">
                                        <i data-feather="image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if ($event->category)
                                    <span class="badge bg-light-primary text-primary mb-25">{{ $event->category }}</span>
                                @endif
                                <div class="fw-bold text-dark">{{ $event->title }}</div>
                                @if ($event->title_en)
                                    <small class="text-muted d-block">{{ $event->title_en }}</small>
                                @endif
                            </td>
                            <td>
                                <p class="text-muted small mb-0 text-truncate" style="max-width: 320px;" title="{{ $event->description }}">
                                    {{ $event->description ?: '—' }}
                                </p>
                            </td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('admin.events.toggle-home', $event) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm {{ $event->show_on_home ? 'btn-success' : 'btn-outline-secondary' }}" type="submit" title="Anasayfa görünürlüğünü değiştir">
                                        @if ($event->show_on_home)
                                            <i data-feather="check" class="me-25"></i> Anasayfada
                                        @else
                                            <i data-feather="eye-off" class="me-25"></i> Gösterilmiyor
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                @if ($event->is_active)
                                    <span class="badge bg-light-success text-success">Aktif</span>
                                @else
                                    <span class="badge bg-light-danger text-danger">Pasif</span>
                                @endif
                            </td>
                            <td class="text-center fw-bold">{{ $event->sort_order }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.events.edit', $event) }}" title="Düzenle">
                                    <i data-feather="edit-2"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.events.destroy', $event) }}" class="d-inline-block" onsubmit="return confirm('Bu etkinliği silmek istediğinize emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit" title="Sil">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Henüz etkinlik eklenmedi. Yukarıdaki <strong>"Yeni Etkinlik Ekle"</strong> butonunu kullanarak başlayabilirsiniz.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

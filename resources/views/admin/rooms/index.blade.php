@extends('admin.layouts.app')

@section('title', 'Odalar')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-2">
        <div>
            <h2 class="mb-25">Oda Tipleri</h2>
            <p class="text-muted mb-0">Tek gecelik fiyatları, kapasiteyi, içerikleri ve görselleri yönetin.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.rooms.create') }}"><i data-feather="plus" class="me-50"></i>Yeni Oda Tipi</a>
    </div>

    @if ($errors->has('room'))
        <div class="alert alert-danger"><div class="alert-body">{{ $errors->first('room') }}</div></div>
    @endif

    <div class="row match-height">
        @forelse ($roomTypes as $roomType)
            <div class="col-xl-4 col-md-6 col-12">
                <div class="card h-100">
                    <img class="card-img-top" style="height: 220px; object-fit: cover" src="{{ $roomType->primaryImageUrl() ?: asset('favicon.ico') }}" alt="{{ $roomType->name }}">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start gap-1">
                            <div><h4 class="card-title mb-25">{{ $roomType->name }}</h4><small class="text-muted">{{ $roomType->name_en }}</small></div>
                            <span class="badge {{ $roomType->is_active ? 'badge-light-success' : 'badge-light-secondary' }}">{{ $roomType->is_active ? 'Aktif' : 'Pasif' }}</span>
                        </div>
                        @if ($roomType->show_on_home)<span class="badge badge-light-primary align-self-start mt-1">Anasayfada</span>@endif
                        <div class="d-flex flex-wrap gap-1 my-2">
                            <span><i data-feather="users" class="me-25"></i>{{ $roomType->max_adults }} yetişkin / {{ $roomType->max_children }} çocuk</span>
                            @if ($roomType->size_sqm)<span><i data-feather="maximize" class="me-25"></i>{{ $roomType->size_sqm }} m²</span>@endif
                        </div>
                        <h3 class="text-primary">₺{{ number_format((float) $roomType->nightly_price, 0, ',', '.') }} <small class="fs-6 text-muted">/ gece</small></h3>
                        <p class="text-muted">{{ $roomType->images->count() }} görsel · {{ $roomType->reservations_count }} rezervasyon</p>
                        <a class="btn btn-outline-primary mt-auto" href="{{ route('admin.rooms.edit', $roomType) }}"><i data-feather="edit-2" class="me-50"></i>Düzenle</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-info"><div class="alert-body">Henüz oda tipi eklenmedi.</div></div></div>
        @endforelse
    </div>
@endsection

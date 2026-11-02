@extends('admin.layouts.app')

@section('title', 'Menümüz')

@section('content')
    <div class="content-header row mb-2">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Menümüz</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Yönetim Paneli</a></li>
                            <li class="breadcrumb-item active">Menümüz</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
            <a href="{{ route('menu') }}" target="_blank" class="btn btn-outline-primary">
                <i data-feather="external-link" class="me-50"></i> Menüyü Görüntüle
            </a>
        </div>
    </div>

    <div class="row">
        {{-- 1. QR KOD KARTI --}}
        <div class="col-lg-4 col-12">
            <div class="card border-top-primary mb-3">
                <div class="card-header">
                    <div>
                        <h4 class="card-title text-primary"><i data-feather="maximize" class="me-50"></i> Menü QR Kodu</h4>
                        <p class="card-text text-muted mb-0">Masalara, odalara veya lobiye koyabileceğiniz QR kod. Okutulduğunda menü sayfası açılır.</p>
                    </div>
                </div>
                <div class="card-body text-center">
                    <style>#menu-qr svg { display: block; width: 100%; height: auto; }</style>
                    <div id="menu-qr" class="d-inline-block p-1 bg-white border rounded" style="width: 220px;">
                        {!! $qrSvg !!}
                    </div>
                    <div class="mt-1 small text-muted text-break">
                        <a href="{{ $menuUrl }}" target="_blank">{{ $menuUrl }}</a>
                    </div>
                    <div class="d-flex flex-wrap justify-content-center gap-1 mt-2">
                        <a href="{{ route('admin.menu.qr-code') }}" class="btn btn-primary btn-sm">
                            <i data-feather="download" class="me-50"></i> SVG İndir
                        </a>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="downloadMenuQrPng()">
                            <i data-feather="image" class="me-50"></i> PNG İndir
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="printMenuQr()">
                            <i data-feather="printer" class="me-50"></i> Yazdır
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-12">
            {{-- 2. YENİ KATEGORİ EKLEME --}}
            <div class="card border-top-success mb-3">
                <div class="card-header">
                    <h4 class="card-title text-success"><i data-feather="folder-plus" class="me-50"></i> Yeni Kategori Ekle</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.menu.categories.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 col-12 mb-1">
                                <label class="form-label fw-bold" for="new_category_name">Kategori Adı (TR) <span class="text-danger">*</span></label>
                                <input class="form-control @error('name') is-invalid @enderror" id="new_category_name" name="name" value="{{ old('name') }}" placeholder="Örn: Sıcak İçecekler" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 col-12 mb-1">
                                <label class="form-label fw-bold text-info" for="new_category_name_en">Category Name (EN)</label>
                                <input class="form-control" id="new_category_name_en" name="name_en" value="{{ old('name_en') }}" placeholder="Ex: Hot Drinks">
                            </div>
                            <div class="col-md-2 col-6 mb-1">
                                <label class="form-label fw-bold" for="new_category_sort_order">Sıra</label>
                                <input class="form-control" type="number" id="new_category_sort_order" name="sort_order" value="{{ old('sort_order', $nextCategorySortOrder) }}" min="0" required>
                            </div>
                            <div class="col-md-2 col-6 mb-1 d-flex align-items-center">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="new_category_is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label fw-bold" for="new_category_is_active">Aktif</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-1">
                            <button class="btn btn-success" type="submit">
                                <i data-feather="plus" class="me-50"></i> Kategoriyi Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 3. YENİ ÜRÜN EKLEME --}}
            @if ($categories->isNotEmpty())
                <div class="card border-top-success mb-3">
                    <div class="card-header">
                        <h4 class="card-title text-success"><i data-feather="plus-circle" class="me-50"></i> Yeni Ürün Ekle</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.menu.items.store') }}">
                            @csrf
                            @include('admin.menu._item-fields', ['item' => null, 'prefix' => 'new_item_'])
                            <div class="d-flex justify-content-end mt-1">
                                <button class="btn btn-success" type="submit">
                                    <i data-feather="plus" class="me-50"></i> Ürünü Kaydet
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- 4. KATEGORİLER VE ÜRÜNLER --}}
    @forelse ($categories as $category)
        <div class="card mb-3">
            <div class="card-header border-bottom">
                <form method="POST" action="{{ route('admin.menu.categories.update', $category) }}" class="row w-100 align-items-end g-1">
                    @csrf
                    @method('PUT')
                    <div class="col-md-4 col-12">
                        <label class="form-label small text-muted mb-0" for="category_name_{{ $category->id }}">Kategori (TR)</label>
                        <input class="form-control fw-bold" id="category_name_{{ $category->id }}" name="name" value="{{ $category->name }}" required>
                    </div>
                    <div class="col-md-3 col-12">
                        <label class="form-label small text-info mb-0" for="category_name_en_{{ $category->id }}">Category (EN)</label>
                        <input class="form-control" id="category_name_en_{{ $category->id }}" name="name_en" value="{{ $category->name_en }}">
                    </div>
                    <div class="col-md-1 col-4">
                        <label class="form-label small text-muted mb-0" for="category_sort_{{ $category->id }}">Sıra</label>
                        <input class="form-control" type="number" id="category_sort_{{ $category->id }}" name="sort_order" value="{{ $category->sort_order }}" min="0" required>
                    </div>
                    <div class="col-md-1 col-4">
                        <div class="form-check form-switch mb-50">
                            <input class="form-check-input" type="checkbox" id="category_active_{{ $category->id }}" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
                            <label class="form-check-label small" for="category_active_{{ $category->id }}">Aktif</label>
                        </div>
                    </div>
                    <div class="col-md-3 col-12 text-end">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i data-feather="save"></i> Kaydet
                        </button>
                        <button type="submit" form="delete-category-{{ $category->id }}" class="btn btn-sm btn-outline-danger">
                            <i data-feather="trash-2"></i>
                        </button>
                    </div>
                </form>
                <form method="POST" action="{{ route('admin.menu.categories.destroy', $category) }}" id="delete-category-{{ $category->id }}" class="d-none" onsubmit="return confirm('Bu kategori ve içindeki tüm ürünler silinecek. Emin misiniz?');">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px;">Sıra</th>
                            <th>Ürün</th>
                            <th style="width: 120px;">Fiyat</th>
                            <th style="width: 100px;">Durum</th>
                            <th style="width: 190px;" class="text-end">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($category->items as $item)
                            <tr>
                                <td><span class="badge bg-light text-dark fw-bold">{{ $item->sort_order }}</span></td>
                                <td>
                                    <div class="fw-bold">{{ $item->name }}</div>
                                    @if ($item->name_en)
                                        <div class="small text-info">{{ $item->name_en }}</div>
                                    @endif
                                    @if ($item->description)
                                        <div class="small text-muted">{{ $item->description }}</div>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ $item->formattedPrice() }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.menu.items.toggle-status', $item) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }} border-0" title="Durumu değiştirmek için tıklayın">
                                            {{ $item->is_active ? 'Aktif' : 'Pasif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('admin.menu.items.edit', $item) }}" class="btn btn-sm btn-outline-primary me-50">
                                        <i data-feather="edit-2"></i> Düzenle
                                    </a>
                                    <form method="POST" action="{{ route('admin.menu.items.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?');">
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
                                <td colspan="5" class="text-center py-3 text-muted">Bu kategoride henüz ürün yok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-4 text-muted">Henüz menü kategorisi bulunmuyor. Yukarıdan ilk kategoriyi ekleyin.</div>
        </div>
    @endforelse
@endsection

@push('scripts')
    <script>
        function downloadMenuQrPng() {
            const svg = document.querySelector('#menu-qr svg');
            if (! svg) {
                return;
            }

            const size = 1024;
            const image = new Image();
            image.onload = function () {
                const canvas = document.createElement('canvas');
                canvas.width = size;
                canvas.height = size;
                const context = canvas.getContext('2d');
                context.fillStyle = '#ffffff';
                context.fillRect(0, 0, size, size);
                context.drawImage(image, 0, 0, size, size);

                const link = document.createElement('a');
                link.download = 'menu-qr-kod.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            };
            image.src = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(new XMLSerializer().serializeToString(svg));
        }

        function printMenuQr() {
            const svg = document.querySelector('#menu-qr svg');
            const printWindow = window.open('', '_blank');
            if (! svg || ! printWindow) {
                return;
            }

            printWindow.document.write(
                '<html><head><title>Menü QR Kodu</title></head>' +
                '<body style="font-family: sans-serif; text-align: center; padding-top: 48px;">' +
                '<h1 style="margin-bottom: 4px;">' + {{ Js::from(e($siteSetting?->hotel_name ?? 'EtnoCity Otel')) }} + '</h1>' +
                '<p style="margin-top: 0; color: #666;">Menümüz / Our Menu</p>' +
                '<div style="width: 320px; margin: 24px auto;">' + svg.outerHTML + '</div>' +
                '<p style="color: #666;">Menüyü görüntülemek için QR kodu okutun<br>Scan the QR code to view our menu</p>' +
                '</body></html>'
            );
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }
    </script>
@endpush

@extends('admin.layouts.app')
@section('title', 'Rezervasyonu Düzenle '.$reservation->reference)
@section('content')
    @php($childAges = old('child_ages', $reservation->child_ages ?? []))
    <div class="mb-2"><a class="text-muted" href="{{ route('admin.reservations.show', $reservation) }}">← {{ $reservation->reference }}</a><h2 class="mt-50 mb-0">Rezervasyonu Düzenle</h2></div>

    @if ($errors->any() && ! $errors->has('hms'))
        <div class="alert alert-danger"><div class="alert-body"><ul class="mb-0 ps-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
    @endif

    <form method="POST" action="{{ route('admin.reservations.update-stay', $reservation) }}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Konaklama</h4></div>
                    <div class="card-body">
                        <div class="alert alert-info p-1 small">Tarih veya kişi sayısı değişirse fiyat, aynı pansiyon tipi (<strong>{{ $reservation->board_name }}</strong>{{ $reservation->non_refundable ? ', iade edilemez' : '' }}) için HMS'ten yeniden alınır. Değişiklik HMS'e gönderilir; HMS kabul etmezse rezervasyon değişmez.</div>
                        <div class="row g-1">
                            <div class="col-md-4"><label class="form-label" for="check_in">Giriş</label><input class="form-control" id="check_in" name="check_in" type="date" value="{{ old('check_in', $reservation->check_in->toDateString()) }}" required></div>
                            <div class="col-md-4"><label class="form-label" for="check_out">Çıkış</label><input class="form-control" id="check_out" name="check_out" type="date" value="{{ old('check_out', $reservation->check_out->toDateString()) }}" required></div>
                            <div class="col-md-4"><label class="form-label" for="adults">Yetişkin</label><input class="form-control" id="adults" name="adults" type="number" min="1" max="20" value="{{ old('adults', $reservation->adults) }}" required></div>
                            <div class="col-12">
                                <label class="form-label">Çocuk Yaşları</label>
                                <div class="d-flex flex-wrap gap-1" id="child-ages">
                                    @foreach ($childAges as $age)
                                        <div class="input-group" style="width: 150px;"><input class="form-control" name="child_ages[]" type="number" min="0" max="16" value="{{ $age }}" required><button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">×</button></div>
                                    @endforeach
                                </div>
                                <button class="btn btn-sm btn-outline-secondary mt-50" type="button" onclick="addChildAge()">+ Çocuk Ekle</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Misafir</h4></div>
                    <div class="card-body">
                        <div class="row g-1">
                            <div class="col-md-6"><label class="form-label" for="first_name">Ad</label><input class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $reservation->first_name) }}" required></div>
                            <div class="col-md-6"><label class="form-label" for="last_name">Soyad</label><input class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $reservation->last_name) }}" required></div>
                            <div class="col-md-6"><label class="form-label" for="email">E-posta</label><input class="form-control" id="email" name="email" type="email" value="{{ old('email', $reservation->email) }}" required></div>
                            <div class="col-md-6"><label class="form-label" for="phone">Telefon</label><input class="form-control" id="phone" name="phone" value="{{ old('phone', $reservation->phone) }}" required></div>
                            <div class="col-12"><label class="form-label" for="special_requests">Misafir Notu</label><textarea class="form-control" id="special_requests" name="special_requests" rows="3" maxlength="2000">{{ old('special_requests', $reservation->special_requests) }}</textarea></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="card">
                    <div class="card-body">
                        <h4>{{ $reservation->room_name }}</h4>
                        <p class="text-muted mb-50">{{ $reservation->board_name }} · HMS #{{ $reservation->hms_booking_id }}</p>
                        <p class="mb-2">Mevcut tutar: <strong>{{ \App\Support\Price::format($reservation->total_price, $reservation->currency) }}</strong></p>
                        <button class="btn btn-primary w-100" type="submit"><i data-feather="save" class="me-50"></i>Kaydet ve HMS'e Gönder</button>
                        <a class="btn btn-outline-secondary w-100 mt-1" href="{{ route('admin.reservations.show', $reservation) }}">Vazgeç</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function addChildAge() {
            const wrapper = document.createElement('div');
            wrapper.className = 'input-group';
            wrapper.style.width = '150px';
            wrapper.innerHTML = '<input class="form-control" name="child_ages[]" type="number" min="0" max="16" value="7" required><button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">×</button>';
            document.getElementById('child-ages').appendChild(wrapper);
        }
    </script>
@endpush

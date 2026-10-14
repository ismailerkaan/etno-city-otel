@extends('admin.layouts.app')
@section('title', 'Rezervasyonlar')
@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-1 mb-2">
        <div><h2 class="mb-25">Rezervasyonlar</h2><p class="text-muted mb-0">Ödeme öncesinde oluşturulan ve tamamlanan rezervasyon kayıtları.</p></div>
        <form method="GET" action="{{ route('admin.reservations.index') }}"><select class="form-select" name="status" onchange="this.form.submit()"><option value="">Tüm Durumlar</option><option value="payment_pending" @selected($status === 'payment_pending')>Ödeme Bekliyor</option><option value="confirmed" @selected($status === 'confirmed')>Onaylandı</option><option value="cancelled" @selected($status === 'cancelled')>İptal Edildi</option></select></form>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Referans</th><th>Misafir</th><th>Oda</th><th>Konaklama</th><th>Tutar</th><th>Durum</th><th></th></tr></thead>
                <tbody>
                @forelse ($reservations as $reservation)
                    @php $badge = ['payment_pending' => 'warning', 'confirmed' => 'success', 'cancelled' => 'danger'][$reservation->status] ?? 'secondary'; @endphp
                    <tr>
                        <td><strong>{{ $reservation->reference }}</strong><br><small class="text-muted">{{ $reservation->created_at->format('d.m.Y H:i') }}</small></td>
                        <td>{{ $reservation->guestFullName() }}<br><small class="text-muted">{{ $reservation->email }}</small></td>
                        <td>{{ $reservation->room_name }}</td>
                        <td>{{ $reservation->check_in->format('d.m.Y') }} – {{ $reservation->check_out->format('d.m.Y') }}<br><small class="text-muted">{{ $reservation->nights }} gece</small></td>
                        <td>₺{{ number_format((float) $reservation->total_price, 0, ',', '.') }}</td>
                        <td><span class="badge badge-light-{{ $badge }}">{{ ['payment_pending' => 'Ödeme Bekliyor', 'confirmed' => 'Onaylandı', 'cancelled' => 'İptal Edildi'][$reservation->status] ?? $reservation->status }}</span></td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.reservations.show', $reservation) }}">Detay</a></td>
                    </tr>
                @empty
                    <tr><td class="text-center text-muted py-3" colspan="7">Bu filtreye uygun rezervasyon bulunamadı.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($reservations->hasPages())
            <div class="card-footer d-flex justify-content-between"><span>{{ $reservations->firstItem() }}–{{ $reservations->lastItem() }} / {{ $reservations->total() }}</span><div class="d-flex gap-50">@if ($reservations->onFirstPage())<span class="btn btn-sm btn-outline-secondary disabled">Önceki</span>@else<a class="btn btn-sm btn-outline-secondary" href="{{ $reservations->previousPageUrl() }}">Önceki</a>@endif @if ($reservations->hasMorePages())<a class="btn btn-sm btn-outline-secondary" href="{{ $reservations->nextPageUrl() }}">Sonraki</a>@else<span class="btn btn-sm btn-outline-secondary disabled">Sonraki</span>@endif</div></div>
        @endif
    </div>
@endsection

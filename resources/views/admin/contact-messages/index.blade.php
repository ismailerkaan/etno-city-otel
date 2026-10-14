@extends('admin.layouts.app')

@section('title', 'İletişim Talepleri')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
        <div>
            <h3 class="mb-25"><i data-feather="inbox" class="me-50 text-primary"></i> İletişim Talepleri</h3>
            <span class="text-muted">Web sitesi iletişim formundan misafirler tarafından iletilen tüm mesaj ve rezervasyon talepleri.</span>
        </div>
        <div class="d-flex align-items-center gap-1">
            @if ($unreadCount > 0)
                <span class="badge bg-danger rounded-pill px-1 py-50 font-small-3 shadow-sm">
                    <i data-feather="mail" class="me-25"></i> {{ $unreadCount }} Yeni Talep
                </span>
            @else
                <span class="badge bg-light-success rounded-pill px-1 py-50 font-small-3">
                    <i data-feather="check" class="me-25"></i> Tüm Talepler Okundu
                </span>
            @endif
            <a href="{{ route('admin.contact.index') }}" class="btn btn-outline-primary btn-sm">
                <i data-feather="settings" class="me-50"></i> İletişim Sayfasını Düzenle
            </a>
        </div>
    </div>

    @if (!empty($tableMissing))
        <div class="alert alert-warning p-2 mb-2 shadow-sm border-start-warning border-start-3">
            <h5 class="alert-heading mb-1"><i data-feather="alert-triangle"></i> Veritabanı Tablosu Henüz Oluşturulmadı</h5>
            <p class="mb-0">Veritabanında <code>contact_messages</code> tablosu henüz mevcut değil. Lütfen terminalinizde veya Docker ortamında <code>php artisan migrate</code> komutunu çalıştırınız.</p>
        </div>
    @endif

    {{-- Filtre & Arama Kartı --}}
    <div class="card mb-2 shadow-sm">
        <div class="card-body p-1">
            <div class="row align-items-center g-1">
                <div class="col-md-6 col-12">
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('admin.contact-messages.index') }}" class="btn {{ $currentFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Tümü ({{ $totalCount }})
                        </a>
                        <a href="{{ route('admin.contact-messages.index', ['status' => 'unread']) }}" class="btn {{ $currentFilter === 'unread' ? 'btn-danger' : 'btn-outline-secondary' }}">
                            Okunmamış ({{ $unreadCount }})
                        </a>
                        <a href="{{ route('admin.contact-messages.index', ['status' => 'read']) }}" class="btn {{ $currentFilter === 'read' ? 'btn-success' : 'btn-outline-secondary' }}">
                            Okunmuş ({{ max(0, $totalCount - $unreadCount) }})
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <form method="GET" action="{{ route('admin.contact-messages.index') }}">
                        @if ($currentFilter !== 'all')
                            <input type="hidden" name="status" value="{{ $currentFilter }}">
                        @endif
                        <div class="input-group input-group-sm">
                            <input type="text" name="q" class="form-control" placeholder="Ad, e-posta, telefon veya konuda ara..." value="{{ $searchQuery }}">
                            <button class="btn btn-outline-primary" type="submit">
                                <i data-feather="search"></i> Ara
                            </button>
                            @if (!empty($searchQuery))
                                <a href="{{ route('admin.contact-messages.index', ['status' => $currentFilter]) }}" class="btn btn-outline-secondary">
                                    <i data-feather="x"></i> Temizle
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Talepler Listesi --}}
    <div class="card border-top-primary shadow-sm">
        <div class="card-body p-0">
            @if ($messages->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Tarih</th>
                                <th>Gönderen</th>
                                <th>İletişim</th>
                                <th>Konu</th>
                                <th>Mesaj Özeti</th>
                                <th>Durum</th>
                                <th class="text-end" style="width: 140px;">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($messages as $msg)
                                <tr class="{{ $msg->is_read ? '' : 'table-light-primary fw-bold' }}">
                                    <td>{{ $msg->id }}</td>
                                    <td class="text-nowrap font-small-3">
                                        {{ $msg->created_at->format('d.m.Y') }}<br>
                                        <small class="text-muted">{{ $msg->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="d-block text-dark">{{ $msg->name }}</span>
                                        @if ($msg->ip_address)
                                            <small class="text-muted font-monospace">{{ $msg->ip_address }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $msg->email }}" class="d-block font-small-3 text-primary">{{ $msg->email }}</a>
                                        @if ($msg->phone)
                                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $msg->phone) }}" class="font-small-3 text-muted">{{ $msg->phone }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-light-secondary font-small-2">{{ $msg->subject ?: 'Genel' }}</span>
                                    </td>
                                    <td style="max-width: 280px;">
                                        <div class="text-truncate" title="{{ $msg->message }}">{{ $msg->message }}</div>
                                    </td>
                                    <td>
                                        @if ($msg->is_read)
                                            <span class="badge badge-light-success font-small-2"><i data-feather="check" class="me-25"></i> Okundu</span>
                                        @else
                                            <span class="badge badge-light-danger font-small-2"><i data-feather="mail" class="me-25"></i> Yeni</span>
                                        @endif
                                    </td>
                                    <td class="text-end text-nowrap">
                                        {{-- Detay Modal Butonu --}}
                                        <button class="btn btn-icon btn-sm btn-outline-info me-50" type="button"
                                                onclick="openMessageModal('{{ addslashes($msg->name) }}', '{{ addslashes($msg->email) }}', '{{ addslashes($msg->phone ?? '-') }}', '{{ addslashes($msg->subject ?? '-') }}', '{{ addslashes(str_replace(["\r", "\n"], [' ', '\n'], $msg->message)) }}', '{{ $msg->created_at->format('d.m.Y H:i') }}')"
                                                title="Mesajı Oku">
                                            <i data-feather="eye"></i>
                                        </button>

                                        {{-- Okundu / Okunmadı Butonu --}}
                                        <form method="POST" action="{{ route('admin.contact-messages.toggle-read', $msg) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-icon btn-sm {{ $msg->is_read ? 'btn-outline-secondary' : 'btn-outline-success' }} me-50" type="submit" title="{{ $msg->is_read ? 'Okunmadı Olarak İşaretle' : 'Okundu Olarak İşaretle' }}">
                                                <i data-feather="{{ $msg->is_read ? 'rotate-ccw' : 'check' }}"></i>
                                            </button>
                                        </form>

                                        {{-- Silme Butonu --}}
                                        <form method="POST" action="{{ route('admin.contact-messages.destroy', $msg) }}" class="d-inline" onsubmit="return confirm('Bu talebi silmek istediğinize emin misiniz?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-icon btn-sm btn-outline-danger" type="submit" title="Sil">
                                                <i data-feather="trash-2"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Sayfalama --}}
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <small class="text-muted">Toplam {{ $messages->total() }} kayıttan {{ $messages->firstItem() ?? 0 }}-{{ $messages->lastItem() ?? 0 }} arası gösteriliyor</small>
                    {{ $messages->links() }}
                </div>
            @else
                <div class="text-center p-5">
                    <i data-feather="inbox" class="text-muted mb-2" style="width: 54px; height: 54px;"></i>
                    <h5 class="text-muted">Kayıtlı iletişim talebi bulunamadı.</h5>
                    <p class="text-muted font-small-3 mb-0">Ziyaretçiler web sitenizdeki /iletisim sayfasından form doldurduğunda talepler bu ekranda toplanacaktır.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Mesaj İnceleme Modal --}}
    <div class="modal fade" id="messageDetailModal" tabindex="-1" aria-labelledby="messageDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="messageDetailModalLabel"><i data-feather="mail" class="me-50 text-primary"></i> İletişim Talebi Detayı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body p-2">
                    <div class="row g-2 mb-2 pb-2 border-bottom">
                        <div class="col-sm-6">
                            <small class="text-muted d-block font-label-sm text-uppercase">Gönderen Misafir</small>
                            <span class="fw-bold fs-5 text-dark" id="modalMsgName">-</span>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <small class="text-muted d-block font-label-sm text-uppercase">Gönderim Tarihi</small>
                            <span class="fw-bold text-dark" id="modalMsgDate">-</span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block font-label-sm text-uppercase">E-Posta Adresi</small>
                            <a href="#" id="modalMsgEmail" class="text-primary fw-bold">-</a>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <small class="text-muted d-block font-label-sm text-uppercase">Telefon Numarası</small>
                            <a href="#" id="modalMsgPhone" class="text-dark fw-bold">-</a>
                        </div>
                        <div class="col-12 pt-1">
                            <small class="text-muted d-block font-label-sm text-uppercase mb-25">Talep Konusu</small>
                            <span class="badge badge-light-primary fs-6 px-1" id="modalMsgSubject">-</span>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted d-block font-label-sm text-uppercase mb-1">Misafirin Mesajı</small>
                        <div class="p-2 bg-light rounded border text-dark font-body-sm" id="modalMsgContent" style="white-space: pre-wrap; line-height: 1.7; min-height: 100px;">-</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kapat</button>
                    <a href="#" id="modalReplyBtn" class="btn btn-primary"><i data-feather="send" class="me-50"></i> E-Posta ile Yanıtla</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openMessageModal(name, email, phone, subject, message, date) {
        document.getElementById('modalMsgName').innerText = name;
        document.getElementById('modalMsgDate').innerText = date;

        const emailEl = document.getElementById('modalMsgEmail');
        emailEl.innerText = email;
        emailEl.href = 'mailto:' + email;

        const phoneEl = document.getElementById('modalMsgPhone');
        phoneEl.innerText = phone;
        phoneEl.href = phone !== '-' ? 'tel:' + phone.replace(/[^0-9+]/g, '') : '#';

        document.getElementById('modalMsgSubject').innerText = subject;
        document.getElementById('modalMsgContent').innerText = message.replace(/\\n/g, '\n');

        const replyBtn = document.getElementById('modalReplyBtn');
        replyBtn.href = 'mailto:' + email + '?subject=' + encodeURIComponent('Ynt: ' + subject);

        const modalEl = document.getElementById('messageDetailModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
</script>
@endpush

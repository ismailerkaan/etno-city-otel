<x-layouts.reservation :site-setting="$siteSetting" title="Odalar | EtnoCity Otel">
    <main class="min-h-screen bg-background pb-20 pt-28 lg:pb-0">
        <section class="border-b border-surface-container-high bg-surface">
            <div class="mx-auto max-w-[1440px] px-6 py-10 lg:px-12">
                <span class="font-label-sm text-label-sm uppercase tracking-[0.24em] text-secondary">Rezervasyon · 1. Adım</span>
                <div class="mt-2 flex flex-col justify-between gap-5 lg:flex-row lg:items-end">
                    <div><h1 class="font-headline-lg text-headline-lg text-on-surface">Size Uygun Odayı Seçin</h1><p class="mt-2 max-w-2xl text-on-surface-variant">Konfor, çalışma düzeni ve misafir kapasitesini karşılaştırarak konaklamanıza en uygun seçeneği bulun.</p></div>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-on-surface-variant"><span class="inline-flex items-center gap-2"><span class="material-symbols-outlined text-secondary">calendar_month</span>{{ $checkIn->format('d.m.Y') }} – {{ $checkOut->format('d.m.Y') }}</span><span class="inline-flex items-center gap-2"><span class="material-symbols-outlined text-secondary">bedtime</span>{{ $nights }} gece</span><span class="inline-flex items-center gap-2"><span class="material-symbols-outlined text-secondary">group</span>{{ $adults }} yetişkin @if($children) · {{ $children }} çocuk @endif</span></div>
                </div>
                <form class="mt-8 grid grid-cols-1 gap-3 bg-surface-container-low p-4 shadow-sm md:grid-cols-5" method="GET" action="{{ route('reservation.step1') }}">
                    <label class="space-y-1"><span class="text-xs font-semibold uppercase tracking-wider text-secondary">Giriş</span><input class="w-full bg-white px-4 py-3 text-on-surface outline-none ring-secondary focus:ring-1" name="checkin" type="date" min="{{ today()->toDateString() }}" value="{{ $checkIn->toDateString() }}" required></label>
                    <label class="space-y-1"><span class="text-xs font-semibold uppercase tracking-wider text-secondary">Çıkış</span><input class="w-full bg-white px-4 py-3 text-on-surface outline-none ring-secondary focus:ring-1" name="checkout" type="date" min="{{ today()->addDay()->toDateString() }}" value="{{ $checkOut->toDateString() }}" required></label>
                    <label class="space-y-1"><span class="text-xs font-semibold uppercase tracking-wider text-secondary">Yetişkin</span><input class="w-full bg-white px-4 py-3 text-on-surface outline-none ring-secondary focus:ring-1" name="adults" type="number" min="1" max="20" value="{{ $adults }}" required></label>
                    <label class="space-y-1"><span class="text-xs font-semibold uppercase tracking-wider text-secondary">Çocuk</span><input class="w-full bg-white px-4 py-3 text-on-surface outline-none ring-secondary focus:ring-1" name="children" type="number" min="0" max="20" value="{{ $children }}" required></label>
                    <button class="mt-5 bg-primary px-5 py-3 font-label-md text-label-md uppercase tracking-[0.18em] text-on-primary transition-colors hover:bg-secondary" type="submit">Seçenekleri Güncelle</button>
                </form>
            </div>
        </section>

        <section class="mx-auto max-w-[1440px] px-6 py-12 lg:px-12">
            <div class="mb-7 flex flex-col justify-between gap-3 sm:flex-row sm:items-center"><div><h2 class="font-headline-md text-headline-md text-on-surface">{{ $roomTypes->count() }} Oda Seçeneği</h2><p class="mt-1 text-sm text-on-surface-variant">Fiyatlar seçtiğiniz {{ $nights }} gecelik konaklama için hesaplanmıştır.</p></div><a class="inline-flex items-center gap-2 text-sm font-semibold text-secondary hover:text-primary" href="#oda-karsilastirma"><span class="material-symbols-outlined">compare_arrows</span>Odaları karşılaştır</a></div>
            <div class="space-y-8">
                @forelse ($roomTypes as $roomType)
                    @php
                        $fits = $adults <= $roomType->max_adults && $children <= $roomType->max_children;
                        $badge = match ($roomType->slug) {
                            'ekonomik' => 'En Uygun Fiyat', 'single' => 'Tek Kişilik Seyahat', 'aile-odasi' => 'Aileler İçin',
                            'balkonlu-oda' => 'Balkonlu', 'manzarali-oda' => 'Özel Manzara', default => 'En Çok Tercih Edilen',
                        };
                        $detailParameters = ['roomType' => $roomType, 'checkin' => $checkIn->toDateString(), 'checkout' => $checkOut->toDateString(), 'adults' => $adults, 'children' => $children];
                    @endphp
                    <article class="group grid overflow-hidden border border-surface-container-high bg-surface shadow-sm transition-shadow hover:shadow-xl lg:grid-cols-12">
                        <a class="relative block min-h-72 overflow-hidden lg:col-span-5 lg:min-h-[410px]" href="{{ route('reservation.step2', $detailParameters) }}">
                            <img class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" src="{{ $roomType->primaryImageUrl() }}" alt="{{ $roomType->images->firstWhere('is_primary', true)?->alt_text ?: $roomType->name }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                            <span class="absolute left-5 top-5 bg-primary px-3 py-2 text-xs font-semibold uppercase tracking-wider text-on-primary">{{ $badge }}</span>
                            @if($roomType->images->count() > 1)<span class="absolute bottom-5 right-5 inline-flex items-center gap-2 bg-black/60 px-3 py-2 text-xs font-semibold text-white backdrop-blur"><span class="material-symbols-outlined text-base">photo_library</span>{{ $roomType->images->count() }} fotoğraf</span>@endif
                        </a>
                        <div class="flex flex-col justify-between gap-7 p-6 lg:col-span-7 lg:p-8">
                            <div>
                                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start"><div><h3 class="font-headline-md text-headline-md text-on-surface">{{ $roomType->translatedName() }}</h3><p class="mt-3 max-w-2xl leading-7 text-on-surface-variant">{{ $roomType->translatedDescription() }}</p></div>@unless($fits)<span class="shrink-0 bg-error-container px-3 py-2 text-xs font-semibold text-on-error-container">Misafir sayısına uygun değil</span>@endunless</div>
                                <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3"><div class="flex items-center gap-3 bg-surface-container-low p-3"><span class="material-symbols-outlined text-secondary">group</span><span class="text-sm">{{ $roomType->max_adults }} yetişkin<br>{{ $roomType->max_children }} çocuk</span></div><div class="flex items-center gap-3 bg-surface-container-low p-3"><span class="material-symbols-outlined text-secondary">square_foot</span><span class="text-sm">{{ $roomType->size_sqm ? $roomType->size_sqm.' m²' : 'Alan bilgisi' }}</span></div><div class="col-span-2 flex items-center gap-3 bg-surface-container-low p-3 sm:col-span-1"><span class="material-symbols-outlined text-secondary">bed</span><span class="text-sm">{{ $roomType->translatedBedType() }}</span></div></div>
                                <div class="mt-5 flex flex-wrap gap-2">@foreach(array_slice($roomType->translatedFeatures(), 0, 4) as $feature)<span class="inline-flex items-center gap-1.5 border border-surface-container-high px-3 py-2 text-xs text-on-surface-variant"><span class="material-symbols-outlined text-sm text-secondary">check</span>{{ $feature }}</span>@endforeach</div>
                            </div>
                            <div class="flex flex-col justify-between gap-5 border-t border-surface-container-high pt-6 sm:flex-row sm:items-end">
                                <div><p class="text-xs uppercase tracking-wider text-on-surface-variant">{{ $nights }} gece toplam</p><div class="mt-1 flex items-end gap-2"><strong class="font-headline-md text-headline-md text-on-surface">₺{{ number_format((float) $roomType->nightly_price * $nights, 0, ',', '.') }}</strong><span class="pb-1 text-sm text-on-surface-variant">₺{{ number_format((float) $roomType->nightly_price, 0, ',', '.') }} / gece</span></div><p class="mt-1 text-xs text-on-surface-variant">Vergiler dahil</p></div>
                                <div class="flex gap-3"><a class="border border-primary px-5 py-3 text-center text-sm font-semibold uppercase tracking-wider text-primary hover:bg-primary hover:text-on-primary" href="{{ route('reservation.step2', $detailParameters) }}">Detayları Gör</a>@if($fits)<a class="bg-primary px-5 py-3 text-center text-sm font-semibold uppercase tracking-wider text-on-primary hover:bg-secondary" href="{{ route('reservation.step4', $detailParameters) }}">Odayı Seç</a>@endif</div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="bg-surface p-10 text-center"><h2 class="font-headline-md text-headline-md">Şu anda yayında oda bulunmuyor.</h2><p class="mt-2 text-on-surface-variant">Lütfen rezervasyon ekibimizle iletişime geçin.</p></div>
                @endforelse
            </div>
        </section>

        @if($guarantees->isNotEmpty())
            <section class="border-y border-surface-container-high bg-surface py-14">
                <div class="mx-auto max-w-[1440px] px-6 lg:px-12">
                    <div class="mb-9 flex flex-col justify-between gap-3 md:flex-row md:items-end">
                        <div><span class="text-xs font-semibold uppercase tracking-[0.24em] text-secondary">{{ app()->isLocale('en') ? ($siteSetting?->guarantees_eyebrow_en ?: 'Book Direct') : ($siteSetting?->guarantees_eyebrow ?: 'Doğrudan Rezervasyon') }}</span><h2 class="mt-2 font-headline-md text-headline-md text-on-surface">{{ app()->isLocale('en') ? ($siteSetting?->guarantees_title_en ?: 'Clear, Easy and Reliable') : ($siteSetting?->guarantees_title ?: 'Açık, Kolay ve Güvenilir') }}</h2></div>
                        <p class="max-w-xl text-sm leading-6 text-on-surface-variant">Odanızı seçerken fiyatı, kapsamı ve rezervasyon koşullarını baştan sona açık biçimde görürsünüz.</p>
                    </div>
                    <div class="grid gap-px overflow-hidden bg-surface-container-high sm:grid-cols-2 lg:grid-cols-4">
                        @foreach($guarantees as $guarantee)
                            <article class="bg-surface-container-low p-6"><span class="material-symbols-outlined text-3xl text-secondary">{{ $guarantee->icon }}</span><h3 class="mt-5 font-semibold text-on-surface">{{ $guarantee->translatedTitle() }}</h3><p class="mt-2 text-sm leading-6 text-on-surface-variant">{{ $guarantee->translatedDescription() }}</p></article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if($roomTypes->isNotEmpty())
            <section class="bg-surface-container-low py-16" id="oda-karsilastirma"><div class="mx-auto max-w-[1440px] overflow-x-auto px-6 lg:px-12"><div class="mb-7"><span class="text-xs font-semibold uppercase tracking-[0.22em] text-secondary">Hızlı Karşılaştırma</span><h2 class="mt-2 font-headline-md text-headline-md">Odalar Yan Yana</h2></div><table class="w-full min-w-[820px] bg-surface text-left"><thead><tr class="border-b border-surface-container-high"><th class="p-4">Oda</th><th class="p-4">Kapasite</th><th class="p-4">Alan</th><th class="p-4">Yatak</th><th class="p-4">Gecelik</th><th class="p-4"></th></tr></thead><tbody>@foreach($roomTypes as $roomType)<tr class="border-b border-surface-container-high/70"><td class="p-4 font-semibold">{{ $roomType->translatedName() }}</td><td class="p-4 text-sm text-on-surface-variant">{{ $roomType->max_adults }} yetişkin · {{ $roomType->max_children }} çocuk</td><td class="p-4 text-sm">{{ $roomType->size_sqm ? $roomType->size_sqm.' m²' : '—' }}</td><td class="p-4 text-sm">{{ $roomType->translatedBedType() }}</td><td class="p-4 font-semibold">₺{{ number_format((float) $roomType->nightly_price, 0, ',', '.') }}</td><td class="p-4 text-right"><a class="text-sm font-semibold text-secondary hover:text-primary" href="{{ route('reservation.step2', ['roomType' => $roomType, 'checkin' => $checkIn->toDateString(), 'checkout' => $checkOut->toDateString(), 'adults' => $adults, 'children' => $children]) }}">İncele →</a></td></tr>@endforeach</tbody></table></div></section>
        @endif
    </main>
</x-layouts.reservation>

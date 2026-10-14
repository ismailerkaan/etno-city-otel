<x-layouts.reservation :site-setting="$siteSetting" title="Misafir Bilgileri | EtnoCity Otel">
    @php
        $inputClass = 'w-full border border-transparent bg-surface-container-low px-4 py-3.5 text-on-surface outline-none transition placeholder:text-outline focus:border-secondary focus:bg-surface focus:ring-1 focus:ring-secondary';
        $searchParameters = ['checkin' => $checkIn->toDateString(), 'checkout' => $checkOut->toDateString(), 'adults' => $adults, 'children' => $children];
    @endphp

    <main class="min-h-screen bg-background pb-24 pt-28 lg:pb-16">
        <section class="border-b border-surface-container-high bg-surface">
            <div class="mx-auto max-w-[1320px] px-6 py-9 lg:px-12">
                <a class="inline-flex items-center gap-2 text-sm font-semibold text-secondary transition-colors hover:text-primary" href="{{ route('reservation.step2', ['roomType' => $roomType, ...$searchParameters]) }}"><span class="material-symbols-outlined text-lg">arrow_back</span>Oda detayına dön</a>
                <div class="mt-5 flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                    <div><span class="font-label-sm text-label-sm uppercase tracking-[0.24em] text-secondary">Rezervasyon · 2. Adım</span><h1 class="mt-2 font-headline-lg text-headline-lg text-on-surface">Misafir Bilgileri</h1><p class="mt-2 max-w-2xl leading-7 text-on-surface-variant">Rezervasyon onayınızı ve konaklamanızla ilgili önemli bilgileri paylaşabilmemiz için aşağıdaki alanları doldurun.</p></div>
                    <ol class="flex min-w-fit items-center gap-2 text-xs font-semibold uppercase tracking-wider text-on-surface-variant" aria-label="Rezervasyon adımları"><li class="flex items-center gap-2 text-secondary"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-secondary text-on-secondary">✓</span>Oda</li><li class="h-px w-7 bg-secondary"></li><li class="flex items-center gap-2 text-primary"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary text-on-primary">2</span>Bilgiler</li><li class="h-px w-7 bg-surface-container-high"></li><li class="flex items-center gap-2"><span class="flex h-7 w-7 items-center justify-center rounded-full border border-outline-variant">3</span>Ödeme</li></ol>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-[1320px] px-6 py-10 lg:px-12">
            @if ($errors->any())
                <div class="mb-7 flex gap-4 border border-error/30 bg-error-container p-5 text-on-error-container" role="alert"><span class="material-symbols-outlined shrink-0">error</span><div><strong>Bilgiler kaydedilemedi.</strong><p class="mt-1 text-sm">Lütfen işaretlenen alanları kontrol edip yeniden deneyin.</p><ul class="mt-2 list-inside list-disc text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
            @endif

            <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-12">
                <form class="space-y-6 lg:col-span-8" method="POST" action="{{ route('reservation.store', $roomType) }}">
                    @csrf
                    <input type="hidden" name="check_in" value="{{ $checkIn->toDateString() }}"><input type="hidden" name="check_out" value="{{ $checkOut->toDateString() }}"><input type="hidden" name="adults" value="{{ $adults }}"><input type="hidden" name="children" value="{{ $children }}">

                    <section class="border border-surface-container-high bg-surface p-6 shadow-sm sm:p-8">
                        <div class="flex items-start gap-4 border-b border-surface-container-high pb-6"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-secondary-fixed text-secondary"><span class="material-symbols-outlined">person</span></span><div><h2 class="font-headline-sm text-headline-sm text-on-surface">Konaklayacak Misafir</h2><p class="mt-1 text-sm text-on-surface-variant">Rezervasyon, aşağıda bilgileri bulunan ana misafir adına oluşturulacaktır.</p></div></div>
                        <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-6">
                            <label class="space-y-2 md:col-span-3"><span class="text-sm font-semibold">Ad <span class="text-error">*</span></span><input class="{{ $inputClass }}" name="first_name" value="{{ old('first_name') }}" autocomplete="given-name" placeholder="Adınız" required>@error('first_name')<span class="block text-xs text-error">{{ $message }}</span>@enderror</label>
                            <label class="space-y-2 md:col-span-3"><span class="text-sm font-semibold">Soyad <span class="text-error">*</span></span><input class="{{ $inputClass }}" name="last_name" value="{{ old('last_name') }}" autocomplete="family-name" placeholder="Soyadınız" required>@error('last_name')<span class="block text-xs text-error">{{ $message }}</span>@enderror</label>
                        </div>
                    </section>

                    <section class="border border-surface-container-high bg-surface p-6 shadow-sm sm:p-8">
                        <div class="flex items-start gap-4 border-b border-surface-container-high pb-6"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-secondary-fixed text-secondary"><span class="material-symbols-outlined">contact_mail</span></span><div><h2 class="font-headline-sm text-headline-sm text-on-surface">İletişim Bilgileri</h2><p class="mt-1 text-sm text-on-surface-variant">Onay belgesi e-posta adresinize, gerekli bilgilendirmeler telefonunuza iletilir.</p></div></div>
                        <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">
                            <label class="space-y-2"><span class="text-sm font-semibold">E-posta Adresi <span class="text-error">*</span></span><div class="relative"><span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-xl text-outline">mail</span><input class="{{ $inputClass }} pl-12" name="email" type="email" value="{{ old('email') }}" autocomplete="email" placeholder="ornek@email.com" required></div><span class="block text-xs text-on-surface-variant">Rezervasyon belgeniz bu adrese gönderilir.</span>@error('email')<span class="block text-xs text-error">{{ $message }}</span>@enderror</label>
                            <label class="space-y-2"><span class="text-sm font-semibold">Telefon Numarası <span class="text-error">*</span></span><div class="relative"><span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-xl text-outline">call</span><input class="{{ $inputClass }} pl-12" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" placeholder="+90 5XX XXX XX XX" required></div><span class="block text-xs text-on-surface-variant">Yalnızca rezervasyonunuzla ilgili durumlarda kullanılır.</span>@error('phone')<span class="block text-xs text-error">{{ $message }}</span>@enderror</label>
                        </div>
                    </section>

                    <section class="border border-surface-container-high bg-surface p-6 shadow-sm sm:p-8">
                        <div class="flex items-start gap-4"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-secondary-fixed text-secondary"><span class="material-symbols-outlined">edit_note</span></span><div class="w-full"><h2 class="font-headline-sm text-headline-sm text-on-surface">Konaklama Notunuz</h2><p class="mt-1 text-sm text-on-surface-variant">Oda konumu, erişilebilirlik veya varış saati gibi taleplerinizi ekibimize iletebilirsiniz.</p><label class="mt-5 block space-y-2"><span class="sr-only">Özel İstekler</span><textarea class="{{ $inputClass }} resize-y" name="special_requests" rows="5" maxlength="2000" placeholder="Örneğin: Tahmini varış saatimiz 21.00 olacaktır.">{{ old('special_requests') }}</textarea><span class="flex justify-between gap-3 text-xs text-on-surface-variant"><span>Talepler uygunluk durumuna göre karşılanır.</span><span>En fazla 2.000 karakter</span></span>@error('special_requests')<span class="block text-xs text-error">{{ $message }}</span>@enderror</label></div></div>
                    </section>

                    <section class="border border-surface-container-high bg-surface p-6 shadow-sm sm:p-8">
                        <label class="flex cursor-pointer items-start gap-4"><input class="mt-1 h-5 w-5 shrink-0 accent-primary" name="terms_accepted" type="checkbox" value="1" @checked(old('terms_accepted')) required><span><strong class="block text-sm text-on-surface">Rezervasyon koşullarını kabul ediyorum.</strong><span class="mt-1 block text-sm leading-6 text-on-surface-variant">Rezervasyon ve konaklama koşullarını, iptal politikasını ve kişisel verilerin işlenmesine ilişkin bilgilendirmeyi okudum, kabul ediyorum.</span>@error('terms_accepted')<span class="mt-2 block text-xs text-error">{{ $message }}</span>@enderror</span></label>
                        <button class="mt-6 flex w-full items-center justify-center gap-3 bg-primary px-6 py-4 font-label-md text-label-md uppercase tracking-[0.2em] text-on-primary transition-colors hover:bg-secondary" type="submit"><span>Güvenli Ödemeye Geç</span><span class="material-symbols-outlined">arrow_forward</span></button>
                        <p class="mt-4 flex items-center justify-center gap-2 text-center text-xs text-on-surface-variant"><span class="material-symbols-outlined text-base text-secondary">lock</span>Bilgileriniz güvenli bağlantı üzerinden iletilir.</p>
                    </section>
                </form>

                <aside class="space-y-5 lg:sticky lg:top-28 lg:col-span-4">
                    <div class="overflow-hidden border border-surface-container-high bg-surface shadow-sm">
                        <div class="relative"><img class="h-56 w-full object-cover" src="{{ $roomType->primaryImageUrl() }}" alt="{{ $roomType->name }}"><span class="absolute left-4 top-4 bg-primary px-3 py-2 text-xs font-semibold uppercase tracking-wider text-on-primary">Seçilen Oda</span></div>
                        <div class="p-6"><h2 class="font-headline-sm text-headline-sm text-on-surface">{{ $roomType->translatedName() }}</h2><div class="mt-5 space-y-4 text-sm"><div class="flex gap-3"><span class="material-symbols-outlined text-secondary">calendar_month</span><div><strong class="block text-on-surface">{{ $checkIn->format('d.m.Y') }} – {{ $checkOut->format('d.m.Y') }}</strong><span class="text-on-surface-variant">{{ $nights }} gece konaklama</span></div></div><div class="flex gap-3"><span class="material-symbols-outlined text-secondary">group</span><div><strong class="block text-on-surface">{{ $adults }} yetişkin @if($children) · {{ $children }} çocuk @endif</strong><span class="text-on-surface-variant">{{ $roomType->translatedBedType() }}</span></div></div><div class="flex gap-3"><span class="material-symbols-outlined text-secondary">square_foot</span><div><strong class="block text-on-surface">{{ $roomType->size_sqm ? $roomType->size_sqm.' m²' : 'Konforlu oda düzeni' }}</strong><span class="text-on-surface-variant">Vergiler dahil fiyat</span></div></div></div>
                            <div class="mt-6 border-t border-surface-container-high pt-5"><div class="flex justify-between gap-4 text-sm text-on-surface-variant"><span>₺{{ number_format((float) $roomType->nightly_price, 0, ',', '.') }} × {{ $nights }} gece</span><span>₺{{ number_format((float) $roomType->nightly_price * $nights, 0, ',', '.') }}</span></div><div class="mt-3 flex items-end justify-between gap-4"><span class="font-semibold text-on-surface">Toplam</span><strong class="font-headline-md text-headline-md text-on-surface">₺{{ number_format((float) $roomType->nightly_price * $nights, 0, ',', '.') }}</strong></div></div>
                            <a class="mt-6 flex items-center justify-center gap-2 border border-primary px-4 py-3 text-sm font-semibold uppercase tracking-wider text-primary transition-colors hover:bg-primary hover:text-on-primary" href="{{ route('reservation.step1', $searchParameters) }}"><span class="material-symbols-outlined text-lg">sync_alt</span>Odayı veya Tarihi Değiştir</a>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-px overflow-hidden border border-surface-container-high bg-surface-container-high"><div class="bg-surface p-4 text-center"><span class="material-symbols-outlined text-secondary">verified_user</span><strong class="mt-2 block text-xs uppercase tracking-wider">Güvenli İşlem</strong></div><div class="bg-surface p-4 text-center"><span class="material-symbols-outlined text-secondary">support_agent</span><strong class="mt-2 block text-xs uppercase tracking-wider">Rezervasyon Desteği</strong></div></div>
                </aside>
            </div>
        </section>
    </main>
</x-layouts.reservation>

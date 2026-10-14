<x-layouts.reservation :site-setting="$siteSetting" title="Güvenli Ödeme | EtnoCity Otel">
    @php
        $inputClass = 'w-full border border-transparent bg-surface-container-low px-4 py-3.5 text-on-surface outline-none transition placeholder:text-outline focus:border-secondary focus:bg-surface focus:ring-1 focus:ring-secondary';
    @endphp

    <main class="min-h-screen bg-background pb-24 pt-28 lg:pb-16">
        <section class="border-b border-surface-container-high bg-surface">
            <div class="mx-auto max-w-[1320px] px-6 py-9 lg:px-12">
                <div class="flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                    <div><span class="font-label-sm text-label-sm uppercase tracking-[0.24em] text-secondary">Rezervasyon · 3. Adım</span><h1 class="mt-2 font-headline-lg text-headline-lg text-on-surface">Güvenli Ödeme</h1><p class="mt-2 max-w-2xl leading-7 text-on-surface-variant">Ödemenizi tamamladığınızda rezervasyonunuz anında onaylanır ve bilgiler e-posta adresinize gönderilir.</p></div>
                    <ol class="flex min-w-fit items-center gap-2 text-xs font-semibold uppercase tracking-wider text-on-surface-variant" aria-label="Rezervasyon adımları"><li class="flex items-center gap-2 text-secondary"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-secondary text-on-secondary">✓</span>Oda</li><li class="h-px w-7 bg-secondary"></li><li class="flex items-center gap-2 text-secondary"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-secondary text-on-secondary">✓</span>Bilgiler</li><li class="h-px w-7 bg-secondary"></li><li class="flex items-center gap-2 text-primary"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary text-on-primary">3</span>Ödeme</li></ol>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-[1320px] px-6 py-10 lg:px-12">
            @error('payment')
                <div class="mb-7 border border-error/30 bg-error-container p-6 text-on-error-container" role="alert"><div class="flex items-start gap-4"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-error text-on-error"><span class="material-symbols-outlined">credit_card_off</span></span><div><span class="text-xs font-semibold uppercase tracking-[0.2em]">Ödeme Tamamlanamadı</span><h2 class="mt-1 font-headline-sm text-headline-sm">Kartınızdan tahsilat yapılamadı</h2><p class="mt-2 max-w-2xl text-sm leading-6">{{ $message }}</p></div></div><div class="mt-5 grid gap-3 border-t border-error/20 pt-5 text-sm sm:grid-cols-3"><span class="flex items-center gap-2"><span class="material-symbols-outlined text-lg">check</span>Kart numarasını kontrol edin</span><span class="flex items-center gap-2"><span class="material-symbols-outlined text-lg">check</span>Bakiyenizi kontrol edin</span><span class="flex items-center gap-2"><span class="material-symbols-outlined text-lg">check</span>Farklı bir kart deneyin</span></div></div>
            @enderror
            @if ($errors->any() && ! $errors->has('payment'))
                <div class="mb-7 flex gap-4 border border-error/30 bg-error-container p-5 text-on-error-container" role="alert"><span class="material-symbols-outlined shrink-0">error</span><div><strong>Ödeme bilgilerini kontrol edin.</strong><p class="mt-1 text-sm">İşaretlenen alanlarda eksik veya geçersiz bilgi bulunuyor.</p><ul class="mt-2 list-inside list-disc text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
            @endif

            <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-12">
                <form class="space-y-6 lg:col-span-8" method="POST" action="{{ route('reservation.payment.store', $reservation) }}" autocomplete="off">
                    @csrf
                    <section class="border border-surface-container-high bg-surface p-6 shadow-sm sm:p-8">
                        <div class="flex flex-col justify-between gap-4 border-b border-surface-container-high pb-6 sm:flex-row sm:items-center"><div class="flex items-start gap-4"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-secondary-fixed text-secondary"><span class="material-symbols-outlined">credit_card</span></span><div><h2 class="font-headline-sm text-headline-sm text-on-surface">Kart Bilgileri</h2><p class="mt-1 text-sm text-on-surface-variant">Kredi veya banka kartınızla ödemenizi tamamlayın.</p></div></div><div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-on-surface-variant"><span class="border border-surface-container-high bg-white px-2.5 py-1.5">Visa</span><span class="border border-surface-container-high bg-white px-2.5 py-1.5">Mastercard</span><span class="border border-surface-container-high bg-white px-2.5 py-1.5">Troy</span></div></div>

                        <div class="mt-8 space-y-10">
                            <div class="relative mx-auto aspect-[1.586/1] w-full max-w-[440px] overflow-hidden rounded-2xl bg-gradient-to-br from-[#101b25] via-[#17364a] to-[#8a6942] p-5 text-white shadow-[0_30px_70px_-25px_rgba(15,30,42,0.8)] ring-1 ring-white/20 sm:p-6" aria-hidden="true">
                                <div class="absolute -right-20 -top-24 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div><div class="absolute -bottom-32 -left-20 h-72 w-72 rounded-full bg-[#d9b77c]/20 blur-xl"></div><div class="absolute inset-0 opacity-20" style="background-image: linear-gradient(115deg, transparent 20%, rgba(255,255,255,.18) 45%, transparent 65%);"></div>
                                <div class="relative flex h-full flex-col justify-between gap-3 sm:gap-5">
                                    <div class="flex items-start justify-between gap-4"><div><span class="block font-headline-sm text-lg tracking-wide sm:text-2xl">EtnoCity</span><span class="block text-[8px] uppercase tracking-[0.28em] text-white/60 sm:mt-1 sm:text-[10px] sm:tracking-[0.34em]">Hotel Collection</span></div><span class="material-symbols-outlined rotate-90 text-2xl text-white/75 sm:text-4xl">contactless</span></div>
                                    <div class="flex items-center gap-3"><div class="grid h-8 w-10 grid-cols-3 grid-rows-3 overflow-hidden rounded-md border border-[#f6dfa7]/60 bg-gradient-to-br from-[#f2d596] via-[#c79b51] to-[#f5e0a6] p-1 shadow-inner sm:h-12 sm:w-14">@foreach(range(1, 9) as $segment)<span class="border border-[#9b7237]/30"></span>@endforeach</div><span class="text-[8px] uppercase tracking-[0.24em] text-white/45 sm:text-[10px]">Secure Card</span></div>
                                    <div><p class="font-mono text-base tracking-[0.12em] text-white drop-shadow-sm sm:text-2xl sm:tracking-[0.16em]" id="card-preview-number">•••• •••• •••• ••••</p><div class="mt-3 flex items-end justify-between gap-3 sm:mt-6 sm:gap-6"><div class="min-w-0 flex-1"><span class="block text-[8px] uppercase tracking-[0.2em] text-white/50 sm:text-[10px]">Kart Sahibi</span><strong class="block truncate text-[10px] uppercase tracking-[0.12em] sm:mt-1 sm:text-base sm:tracking-[0.16em]" id="card-preview-name">AD SOYAD</strong></div><div class="shrink-0 text-right"><span class="block text-[8px] uppercase tracking-[0.2em] text-white/50 sm:text-[10px]">Geçerlilik</span><strong class="block font-mono text-xs tracking-wider sm:mt-1 sm:text-base" id="card-preview-expiry">AA/YY</strong></div><div class="relative flex h-7 w-11 shrink-0 items-center sm:h-11 sm:w-16"><span class="absolute left-0 h-7 w-7 rounded-full bg-white/80 sm:h-11 sm:w-11"></span><span class="absolute right-0 h-7 w-7 rounded-full bg-[#d8ae72]/80 mix-blend-screen sm:h-11 sm:w-11"></span></div></div></div>
                                </div>
                            </div>

                            <div class="mx-auto w-full max-w-3xl space-y-5">
                                <label class="block space-y-2"><span class="text-sm font-semibold">Kart Üzerindeki İsim <span class="text-error">*</span></span><input class="{{ $inputClass }} uppercase" id="cardholder_name" name="cardholder_name" value="{{ old('cardholder_name') }}" maxlength="255" autocomplete="cc-name" placeholder="AD SOYAD" required>@error('cardholder_name')<span class="block text-xs text-error">{{ $message }}</span>@enderror</label>
                                <label class="block space-y-2"><span class="text-sm font-semibold">Kart Numarası <span class="text-error">*</span></span><div class="relative"><input class="{{ $inputClass }} pr-12 font-mono tracking-wider" id="card_number" name="card_number" inputmode="numeric" maxlength="23" autocomplete="cc-number" placeholder="0000 0000 0000 0000" required><span class="material-symbols-outlined pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-secondary">credit_card</span></div>@error('card_number')<span class="block text-xs text-error">{{ $message }}</span>@enderror</label>
                                <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">
                                    <label class="space-y-2 sm:col-span-2"><span class="text-sm font-semibold">Ay <span class="text-error">*</span></span><select class="{{ $inputClass }}" id="expiry_month" name="expiry_month" autocomplete="cc-exp-month" required><option value="">Ay</option>@foreach(range(1, 12) as $month)<option value="{{ $month }}" @selected(old('expiry_month') == $month)>{{ str_pad((string) $month, 2, '0', STR_PAD_LEFT) }}</option>@endforeach</select>@error('expiry_month')<span class="block text-xs text-error">{{ $message }}</span>@enderror</label>
                                    <label class="space-y-2 sm:col-span-2"><span class="text-sm font-semibold">Yıl <span class="text-error">*</span></span><select class="{{ $inputClass }}" id="expiry_year" name="expiry_year" autocomplete="cc-exp-year" required><option value="">Yıl</option>@foreach(range(now()->year, now()->addYears(15)->year) as $year)<option value="{{ $year }}" @selected(old('expiry_year') == $year)>{{ $year }}</option>@endforeach</select>@error('expiry_year')<span class="block text-xs text-error">{{ $message }}</span>@enderror</label>
                                    <label class="col-span-2 space-y-2 sm:col-span-1"><span class="flex items-center gap-1 text-sm font-semibold">CVV <span class="text-error">*</span><span class="material-symbols-outlined cursor-help text-base text-outline" title="Kartınızın arkasındaki 3 veya 4 haneli güvenlik kodu">help</span></span><input class="{{ $inputClass }} text-center font-mono" name="security_code" type="password" inputmode="numeric" minlength="3" maxlength="4" autocomplete="cc-csc" placeholder="•••" required>@error('security_code')<span class="block text-xs text-error">{{ $message }}</span>@enderror</label>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="border border-surface-container-high bg-surface p-6 shadow-sm sm:p-8">
                        <div class="grid gap-5 sm:grid-cols-3"><div class="flex gap-3"><span class="material-symbols-outlined text-secondary">lock</span><div><strong class="block text-sm">Şifreli Bağlantı</strong><span class="mt-1 block text-xs leading-5 text-on-surface-variant">Ödeme bilgileriniz güvenli bağlantıyla iletilir.</span></div></div><div class="flex gap-3"><span class="material-symbols-outlined text-secondary">visibility_off</span><div><strong class="block text-sm">Kart Bilgisi Saklanmaz</strong><span class="mt-1 block text-xs leading-5 text-on-surface-variant">Kart numaranız ve CVV kodunuz otel sisteminde tutulmaz.</span></div></div><div class="flex gap-3"><span class="material-symbols-outlined text-secondary">verified_user</span><div><strong class="block text-sm">Anında Onay</strong><span class="mt-1 block text-xs leading-5 text-on-surface-variant">Başarılı ödeme sonrası rezervasyonunuz kesinleşir.</span></div></div></div>
                        <button class="mt-7 flex w-full items-center justify-center gap-3 bg-primary px-6 py-4 font-label-md text-label-md uppercase tracking-[0.2em] text-on-primary transition-colors hover:bg-secondary" type="submit"><span class="material-symbols-outlined">lock</span><span>₺{{ number_format((float) $reservation->total_price, 0, ',', '.') }} Güvenli Öde</span></button>
                        <p class="mt-4 text-center text-xs leading-5 text-on-surface-variant">Butona tıkladığınızda belirtilen toplam tutar kartınızdan tahsil edilir.</p>
                    </section>
                </form>

                <aside class="space-y-5 lg:sticky lg:top-28 lg:col-span-4">
                    <div class="overflow-hidden border border-surface-container-high bg-surface shadow-sm"><div class="relative"><img class="h-52 w-full object-cover" src="{{ $reservation->roomType->primaryImageUrl() }}" alt="{{ $reservation->room_name }}"><span class="absolute left-4 top-4 bg-primary px-3 py-2 text-xs font-semibold uppercase tracking-wider text-on-primary">Rezervasyon Özeti</span></div><div class="p-6"><h2 class="font-headline-sm text-headline-sm text-on-surface">{{ $reservation->room_name }}</h2><p class="mt-1 text-xs uppercase tracking-wider text-on-surface-variant">Rezervasyon No: {{ $reservation->reference }}</p><dl class="mt-6 space-y-4 text-sm"><div class="flex gap-3"><span class="material-symbols-outlined text-secondary">calendar_month</span><div><dt class="text-xs uppercase tracking-wider text-on-surface-variant">Tarihler</dt><dd class="mt-1 font-semibold text-on-surface">{{ $reservation->check_in->format('d.m.Y') }} – {{ $reservation->check_out->format('d.m.Y') }}</dd><span class="text-on-surface-variant">{{ $reservation->nights }} gece</span></div></div><div class="flex gap-3"><span class="material-symbols-outlined text-secondary">person</span><div><dt class="text-xs uppercase tracking-wider text-on-surface-variant">Ana Misafir</dt><dd class="mt-1 font-semibold text-on-surface">{{ $reservation->first_name }} {{ $reservation->last_name }}</dd><span class="text-on-surface-variant">{{ $reservation->adults }} yetişkin @if($reservation->children) · {{ $reservation->children }} çocuk @endif</span></div></div></dl><div class="mt-6 border-t border-surface-container-high pt-5"><div class="flex justify-between gap-4 text-sm text-on-surface-variant"><span>₺{{ number_format((float) $reservation->nightly_price, 0, ',', '.') }} × {{ $reservation->nights }} gece</span><span>₺{{ number_format((float) $reservation->total_price, 0, ',', '.') }}</span></div><div class="mt-3 flex items-end justify-between gap-4"><span class="font-semibold text-on-surface">Ödenecek Toplam</span><strong class="font-headline-md text-headline-md text-on-surface">₺{{ number_format((float) $reservation->total_price, 0, ',', '.') }}</strong></div><p class="mt-1 text-right text-xs text-on-surface-variant">Vergiler dahil</p></div></div></div>
                    <div class="flex gap-3 border border-surface-container-high bg-surface p-5"><span class="material-symbols-outlined text-secondary">support_agent</span><div><strong class="block text-sm">Yardıma mı ihtiyacınız var?</strong><p class="mt-1 text-xs leading-5 text-on-surface-variant">Ödeme sırasında destek için rezervasyon ekibimizle iletişime geçebilirsiniz.</p></div></div>
                </aside>
            </div>
        </section>
    </main>

    @push('scripts')
        <script>
            (() => {
                const numberInput = document.getElementById('card_number');
                const nameInput = document.getElementById('cardholder_name');
                const monthInput = document.getElementById('expiry_month');
                const yearInput = document.getElementById('expiry_year');
                const numberPreview = document.getElementById('card-preview-number');
                const namePreview = document.getElementById('card-preview-name');
                const expiryPreview = document.getElementById('card-preview-expiry');

                const updateNumber = () => {
                    numberInput.value = numberInput.value.replace(/\D/g, '').slice(0, 19).replace(/(.{4})/g, '$1 ').trim();
                    numberPreview.textContent = numberInput.value || '•••• •••• •••• ••••';
                };
                const updateName = () => namePreview.textContent = nameInput.value.trim().toUpperCase() || 'AD SOYAD';
                const updateExpiry = () => expiryPreview.textContent = `${monthInput.value ? monthInput.value.padStart(2, '0') : 'AA'}/${yearInput.value ? yearInput.value.slice(-2) : 'YY'}`;

                numberInput?.addEventListener('input', updateNumber);
                nameInput?.addEventListener('input', updateName);
                monthInput?.addEventListener('change', updateExpiry);
                yearInput?.addEventListener('change', updateExpiry);
                updateName();
                updateExpiry();
            })();
        </script>
    @endpush
</x-layouts.reservation>

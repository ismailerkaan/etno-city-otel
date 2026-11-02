{{-- HMS çocuk fiyatını yaşa göre hesapladığı için fiyatlardan önce yaşlar sorulur --}}
<form class="flex flex-col gap-4 border border-secondary/40 bg-secondary/5 p-5 sm:flex-row sm:items-end" method="GET" action="{{ $action }}">
    <input type="hidden" name="checkin" value="{{ $checkIn->toDateString() }}">
    <input type="hidden" name="checkout" value="{{ $checkOut->toDateString() }}">
    <input type="hidden" name="adults" value="{{ $adults }}">
    <input type="hidden" name="children" value="{{ $children }}">
    <div class="flex-1">
        <p class="flex items-center gap-2 font-semibold text-on-surface"><span class="material-symbols-outlined text-secondary">child_care</span>Çocuk yaşlarını seçin</p>
        <p class="mt-1 text-sm text-on-surface-variant">Çocuk fiyatları yaşa göre hesaplandığı için güncel fiyatları göstermeden önce yaşları öğrenmemiz gerekiyor.</p>
        <div class="mt-3 flex flex-wrap gap-3">
            @for ($index = 0; $index < $children; $index++)
                <label class="space-y-1">
                    <span class="text-xs font-semibold uppercase tracking-wider text-secondary">{{ $index + 1 }}. Çocuk</span>
                    <select class="block bg-white px-4 py-2.5 text-on-surface outline-none ring-secondary focus:ring-1" name="child_ages[]" required>
                        @for ($age = 0; $age <= 16; $age++)
                            <option value="{{ $age }}" @selected(($childAges[$index] ?? null) === $age)>{{ $age }} yaş</option>
                        @endfor
                    </select>
                </label>
            @endfor
        </div>
    </div>
    <button class="bg-primary px-5 py-3 font-label-md text-label-md uppercase tracking-[0.18em] text-on-primary transition-colors hover:bg-secondary" type="submit">Fiyatları Göster</button>
</form>

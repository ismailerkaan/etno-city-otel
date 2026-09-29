<x-layouts.app :site-setting="$siteSetting">
<div class="flex flex-col w-full bg-surface">

    {{-- 1. Hero Bölümü --}}
    <section class="relative w-full -mt-20 pt-20 min-h-[580px] lg:min-h-[640px] flex flex-col justify-end overflow-hidden bg-primary">
        @if ($event->imageUrl())
            <img class="absolute inset-0 w-full h-full object-cover opacity-85" src="{{ $event->imageUrl() }}" alt="{{ $event->translatedTitle() }}">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/50 to-primary/30 pointer-events-none"></div>

        <div class="relative z-10 max-w-[1440px] mx-auto px-6 lg:px-12 w-full pb-16">
            {{-- Geri Dön Linki --}}
            <div class="mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 font-label-md text-label-md uppercase tracking-[0.2em] text-on-primary/80 hover:text-secondary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    <span>{{ app()->isLocale('en') ? 'Back to Home' : 'Anasayfaya Dön' }}</span>
                </a>
            </div>

            @if ($event->translatedCategory())
                <div class="inline-flex items-center gap-3 px-3.5 py-1.5 bg-surface/20 backdrop-blur-md mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-secondary-fixed"></span>
                    <span class="font-label-sm text-label-sm uppercase tracking-[0.25em] text-secondary-fixed">{{ $event->translatedCategory() }}</span>
                </div>
            @endif

            <h1 class="font-display-lg text-display-lg-mobile lg:text-display-lg text-on-primary max-w-4xl tracking-tight leading-tight">
                {{ $event->translatedTitle() }}
            </h1>
        </div>
    </section>

    {{-- 2. İçerik ve Rezervasyon Alanı --}}
    <section class="w-full py-16 lg:py-24">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">

                {{-- Sol: Detaylı Açıklama --}}
                <div class="lg:col-span-8 space-y-8">
                    <div class="space-y-4">
                        <span class="font-label-md text-label-md uppercase text-secondary tracking-[0.28em] block">
                            {{ app()->isLocale('en') ? 'Bespoke Chapter & Experience' : 'Özel Deneyim Detayı' }}
                        </span>
                        <h2 class="font-headline-lg text-headline-lg text-on-surface">
                            {{ $event->translatedTitle() }}
                        </h2>
                    </div>

                    <div class="prose prose-lg text-on-surface-variant font-light leading-relaxed space-y-6">
                        @if ($event->translatedDescription())
                            <p class="text-body-lg text-body-lg whitespace-pre-line leading-relaxed">
                                {{ $event->translatedDescription() }}
                            </p>
                        @endif
                    </div>

                    {{-- Deneyim Ayrıcalıkları --}}
                    <div class="pt-8 border-t border-outline-variant grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="flex items-start gap-4 p-5 bg-surface-container-low border border-outline-variant/30">
                            <span class="material-symbols-outlined text-secondary text-[28px]">lock</span>
                            <div class="space-y-1">
                                <h4 class="font-headline-sm text-title-md text-on-surface">{{ app()->isLocale('en') ? 'Exclusive & Private' : 'Tamamen Kişiye Özel' }}</h4>
                                <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                    {{ app()->isLocale('en') ? 'Curated exclusively for our residing guests with bespoke attention.' : 'Yalnızca otel misafirlerimize özel olarak, kişisel ritminize göre organize edilir.' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-5 bg-surface-container-low border border-outline-variant/30">
                            <span class="material-symbols-outlined text-secondary text-[28px]">concierge</span>
                            <div class="space-y-1">
                                <h4 class="font-headline-sm text-title-md text-on-surface">{{ app()->isLocale('en') ? 'Dedicated Concierge' : '7/24 Butler & Concierge' }}</h4>
                                <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                    {{ app()->isLocale('en') ? 'Our peninsula concierge coordinates all details seamlessly.' : 'Tüm detaylar ve rezervasyon saatleri özel concierge ekibimiz tarafından koordine edilir.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sağ: Rezervasyon & İletişim Kartı --}}
                <div class="lg:col-span-4 sticky top-28 space-y-6">
                    <div class="bg-surface-container-low p-8 border border-outline-variant/40 space-y-6 shadow-sm">
                        <div class="space-y-2">
                            <span class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-secondary block">
                                {{ app()->isLocale('en') ? 'Experience Reservation' : 'Deneyim Rezervasyonu' }}
                            </span>
                            <h3 class="font-headline-md text-headline-md text-on-surface">
                                {{ app()->isLocale('en') ? 'Plan Your Journey' : 'Deneyiminizi Planlayın' }}
                            </h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                {{ app()->isLocale('en') ? 'This bespoke chapter can be reserved alongside your stay or arranged directly with our concierge desk.' : 'Bu özel deneyimi konaklamanıza dahil etmek veya detaylı bilgi almak için bizimle iletişime geçebilirsiniz.' }}
                            </p>
                        </div>

                        <div class="space-y-3 pt-2">
                            <a href="{{ route('reservation.step1') }}" class="w-full block py-4 bg-primary text-on-primary hover:bg-secondary hover:text-on-secondary text-center font-label-md text-label-md uppercase tracking-[0.22em] transition-all duration-300 shadow-sm">
                                {{ app()->isLocale('en') ? 'Reserve Stay & Experience' : 'Konaklama ile Birlikte Rezerve Et' }}
                            </a>

                            @if ($siteSetting?->phone)
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSetting->phone) }}" class="w-full block py-3 border border-outline text-on-surface hover:border-primary text-center font-label-sm text-label-sm uppercase tracking-[0.2em] transition-colors">
                                    <span class="material-symbols-outlined text-[16px] align-middle me-1">call</span>
                                    {{ $siteSetting->phone }}
                                </a>
                            @endif
                        </div>

                        <div class="pt-4 border-t border-outline-variant/40 text-center">
                            <span class="font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant block">
                                {{ app()->isLocale('en') ? 'EtnoCity Peninsula Butler Service' : 'EtnoCity Özel Butler Hizmeti' }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- 3. Diğer Deneyimler --}}
    @if ($otherEvents->isNotEmpty())
        <section class="w-full py-28 lg:py-36 bg-surface-container-high border-t border-outline-variant/30">
            <div class="max-w-[1440px] mx-auto px-6 lg:px-12 space-y-16">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-4 border-b border-outline-variant/20">
                    <div class="space-y-2">
                        <span class="font-label-md text-label-md uppercase text-secondary tracking-[0.28em] block">
                            {{ app()->isLocale('en') ? 'More Experiences' : 'Diğer Deneyimler' }}
                        </span>
                        <h3 class="font-headline-lg text-headline-lg text-on-surface">
                            {{ app()->isLocale('en') ? 'Discover Beyond' : 'Keşfetmeye Devam Edin' }}
                        </h3>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant max-w-md mb-1">
                        {{ app()->isLocale('en') ? 'Explore our other curated bespoke chapters and private journeys across Cape Artemis.' : 'Cape Artemis yarımadasında sizin için özenle tasarladığımız diğer ayrıcalıklı anları keşfedin.' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-4">
                    @foreach ($otherEvents as $other)
                        <a href="{{ route('events.show', $other) }}" class="relative h-[520px] overflow-hidden group cursor-pointer bg-primary block shadow-md">
                            @if ($other->imageUrl())
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000 opacity-80" src="{{ $other->imageUrl() }}" alt="{{ $other->translatedTitle() }}">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/30 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 w-full p-8 space-y-3">
                                @if ($other->translatedCategory())
                                    <span class="font-label-sm text-label-sm uppercase tracking-[0.25em] text-secondary-fixed">{{ $other->translatedCategory() }}</span>
                                @endif
                                <h4 class="font-headline-md text-headline-md text-on-primary">{{ $other->translatedTitle() }}</h4>
                                <div class="pt-2 flex items-center gap-2 text-on-primary font-label-md text-label-md uppercase tracking-wider group-hover:translate-x-1 transition-transform">
                                    <span>{{ app()->isLocale('en') ? 'Explore' : 'İncele' }}</span>
                                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</div>
</x-layouts.app>

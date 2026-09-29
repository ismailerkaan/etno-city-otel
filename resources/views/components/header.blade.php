@props(['siteSetting' => null])
@php
    if (! $siteSetting) {
        try {
            $siteSetting = \App\Models\SiteSetting::query()->first();
        } catch (\Throwable) {
            $siteSetting = null;
        }
    }
@endphp

<header class="fixed top-0 left-0 w-full z-50 transition-all duration-500 bg-surface/85 backdrop-blur-md shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
    <div class="h-20 max-w-[1440px] mx-auto px-6 lg:px-12 flex items-center justify-between gap-6">
        <div class="flex items-center gap-4 flex-shrink-0">
            <a class="flex items-center gap-3.5 group" href="{{ route('home') }}">
                @if ($siteSetting?->logoUrl())
                    <img alt="{{ $siteSetting->hotel_name }}" class="h-10 w-auto object-contain" src="{{ $siteSetting->logoUrl() }}">
                @else
                    <span class="font-headline-sm text-headline-sm tracking-wide text-on-surface uppercase">{{ $siteSetting?->hotel_name ?? 'EtnoCity Otel' }}</span>
                @endif
            </a>
        </div>
        <nav class="hidden xl:flex items-center gap-8">
            <a class="font-label-md text-label-md uppercase tracking-[0.2em] {{ request()->routeIs('home') ? 'text-on-surface' : 'text-on-surface-variant' }} hover:text-on-surface transition-colors py-2" href="{{ route('home') }}#odalar">{{ __('site.rooms') }}</a>
            <a class="font-label-md text-label-md uppercase tracking-[0.2em] text-on-surface-variant hover:text-on-surface transition-colors py-2" href="{{ route('home') }}#etkinlikler">{{ __('site.experiences') }}</a>
            <a class="font-label-md text-label-md uppercase tracking-[0.2em] {{ request()->routeIs('gallery') ? 'text-on-surface font-semibold underline underline-offset-8 decoration-secondary' : 'text-on-surface-variant hover:text-on-surface' }} transition-colors py-2" href="{{ route('gallery') }}">{{ __('site.gallery') }}</a>
            <a class="font-label-md text-label-md uppercase tracking-[0.2em] text-on-surface-variant hover:text-on-surface transition-colors py-2" href="{{ route('home') }}#hikayemiz">{{ __('site.story') }}</a>
            <a class="font-label-md text-label-md uppercase tracking-[0.2em] text-on-surface-variant hover:text-on-surface transition-colors py-2" href="{{ route('home') }}#iletisim">{{ __('site.contact') }}</a>
        </nav>
        <div class="flex items-center gap-4 sm:gap-5 flex-shrink-0">
            <div class="hidden sm:flex items-center gap-2 text-on-surface-variant font-label-md text-label-md uppercase">
                <a class="{{ app()->isLocale('tr') ? 'text-on-surface font-semibold underline underline-offset-4 decoration-secondary' : 'hover:text-on-surface' }} transition-colors" href="{{ route('locale.switch', 'tr') }}">TR</a>
                <span class="text-outline-variant text-[10px]">/</span>
                <a class="{{ app()->isLocale('en') ? 'text-on-surface font-semibold underline underline-offset-4 decoration-secondary' : 'hover:text-on-surface' }} transition-colors" href="{{ route('locale.switch', 'en') }}">EN</a>
            </div>
            <a class="px-5 sm:px-6 py-2.5 bg-primary text-on-primary hover:bg-secondary hover:text-on-secondary transition-colors duration-300 font-label-md text-label-md uppercase tracking-[0.22em] text-center" href="{{ route('reservation.step1') }}">{{ __('site.reserve') }}</a>
            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
            </div>
            <button type="button" id="mobile-nav-toggle" class="xl:hidden p-1.5 text-on-surface hover:text-secondary transition-colors focus:outline-none flex items-center justify-center" aria-label="Menü">
                <span class="material-symbols-outlined text-[26px]" id="mobile-nav-icon">menu</span>
            </button>
        </div>
    </div>
    {{-- Mobil Menü Dropdown --}}
    <div id="mobile-nav-menu" class="hidden xl:hidden bg-surface/98 backdrop-blur-xl border-t border-surface-container-high px-6 py-6 shadow-xl transition-all">
        <nav class="flex flex-col space-y-3">
            <a class="font-label-md text-label-md uppercase tracking-[0.2em] {{ request()->routeIs('home') ? 'text-secondary font-semibold' : 'text-on-surface-variant' }} hover:text-on-surface py-2 border-b border-surface-container-high/50" href="{{ route('home') }}#odalar">{{ __('site.rooms') }}</a>
            <a class="font-label-md text-label-md uppercase tracking-[0.2em] text-on-surface-variant hover:text-on-surface py-2 border-b border-surface-container-high/50" href="{{ route('home') }}#etkinlikler">{{ __('site.experiences') }}</a>
            <a class="font-label-md text-label-md uppercase tracking-[0.2em] {{ request()->routeIs('gallery') ? 'text-secondary font-semibold' : 'text-on-surface-variant' }} hover:text-on-surface py-2 border-b border-surface-container-high/50" href="{{ route('gallery') }}">{{ __('site.gallery') }}</a>
            <a class="font-label-md text-label-md uppercase tracking-[0.2em] text-on-surface-variant hover:text-on-surface py-2 border-b border-surface-container-high/50" href="{{ route('home') }}#hikayemiz">{{ __('site.story') }}</a>
            <a class="font-label-md text-label-md uppercase tracking-[0.2em] text-on-surface-variant hover:text-on-surface py-2 border-b border-surface-container-high/50" href="{{ route('home') }}#iletisim">{{ __('site.contact') }}</a>
            <div class="flex sm:hidden items-center justify-between pt-3 text-on-surface-variant font-label-md uppercase tracking-[0.16em]">
                <span>Dil / Language</span>
                <div class="flex items-center gap-2">
                    <a class="{{ app()->isLocale('tr') ? 'text-on-surface font-semibold underline underline-offset-4 decoration-secondary' : 'hover:text-on-surface' }}" href="{{ route('locale.switch', 'tr') }}">TR</a>
                    <span class="text-outline-variant text-[10px]">/</span>
                    <a class="{{ app()->isLocale('en') ? 'text-on-surface font-semibold underline underline-offset-4 decoration-secondary' : 'hover:text-on-surface' }}" href="{{ route('locale.switch', 'en') }}">EN</a>
                </div>
            </div>
        </nav>
    </div>
</header>
<script>
    (function() {
        const toggleBtn = document.getElementById('mobile-nav-toggle');
        const menu = document.getElementById('mobile-nav-menu');
        const icon = document.getElementById('mobile-nav-icon');
        if (toggleBtn && menu) {
            toggleBtn.addEventListener('click', function() {
                const isClosed = menu.classList.toggle('hidden');
                if (icon) {
                    icon.textContent = isClosed ? 'menu' : 'close';
                }
            });
        }
    })();
</script>

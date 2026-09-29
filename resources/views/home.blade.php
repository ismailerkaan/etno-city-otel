<x-layouts.app :site-setting="$siteSetting">
<div class="flex flex-col w-full">
<!-- 1. HERO SECTION (Under-shell bleed with negative margin and inner clearance) -->
<section class="relative w-full -mt-20 pt-20 min-h-[942px] flex flex-col justify-between overflow-hidden">
<!-- Background Slider with Ambient Scrim -->
<div class="absolute inset-0" id="hero-slider">
@forelse ($heroSlides as $slide)
<img class="absolute inset-0 w-full h-full object-cover transition-opacity duration-[1600ms] ease-in-out {{ $loop->first ? 'opacity-100' : 'opacity-0' }}" data-hero-slide alt="{{ $slide->translatedTitle() }}" src="{{ asset('storage/'.$slide->image_path) }}">
@empty
<div class="absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('{{ $siteSetting->hero_image_url }}')"></div>
@endforelse
</div>
<div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-primary/25 to-primary/40 pointer-events-none"></div>
@if ($heroSlides->count() > 1)
<div class="absolute right-6 lg:right-12 bottom-6 z-20 flex items-center gap-2" aria-label="Hero slider">
@foreach ($heroSlides as $slide)
<button class="w-8 h-1 transition-colors {{ $loop->first ? 'bg-white' : 'bg-white/40' }}" data-hero-dot type="button" aria-label="{{ $loop->iteration }}. görsel"></button>
@endforeach
</div>
@endif
<!-- Empty Spacer to balance layout -->
<div class="hidden lg:block h-12"></div>
{{-- Hero Content --}}
<div class="relative z-10 max-w-[1440px] mx-auto px-6 lg:px-12 w-full pt-28 pb-16 flex flex-col items-start">

    @if ($heroSlides->isNotEmpty())
        {{-- Her slide için ayrı metin katmanı --}}
        <div class="relative w-full min-h-[240px] lg:min-h-[280px]">
            @foreach ($heroSlides as $slide)
            <div class="w-full flex flex-col items-start transition-opacity duration-[1000ms] ease-in-out {{ $loop->first ? 'opacity-100' : 'opacity-0 absolute inset-0 pointer-events-none' }}" data-hero-text>
                <div class="inline-flex items-center gap-3 px-3.5 py-1.5 bg-surface/20 backdrop-blur-md mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-secondary-fixed"></span>
                    <span class="font-label-sm text-label-sm uppercase tracking-[0.25em] text-on-primary">EtnoCity Otel &amp; Retreat</span>
                </div>
                <h1 class="font-display-lg text-display-lg-mobile lg:text-display-lg text-on-primary max-w-4xl tracking-tight leading-none mb-6">
                    {{ $slide->translatedTitle() ?: ($siteSetting?->translated('hero_title') ?? 'Zamansız Bir Kaçış, Sizin İçin Tasarlandı') }}
                </h1>
                @if ($slide->translatedDescription() || $siteSetting?->translated('hero_subtitle'))
                <p class="font-body-lg text-body-lg text-surface-container-high max-w-2xl font-light leading-relaxed mb-12">
                    {{ $slide->translatedDescription() ?: $siteSetting?->translated('hero_subtitle') }}
                </p>
                @else
                <div class="mb-12"></div>
                @endif
            </div>
            @endforeach
        </div>
    @else
        {{-- Slide yoksa site settings'ten --}}
        <div class="inline-flex items-center gap-3 px-3.5 py-1.5 bg-surface/20 backdrop-blur-md mb-6">
            <span class="w-1.5 h-1.5 rounded-full bg-secondary-fixed"></span>
            <span class="font-label-sm text-label-sm uppercase tracking-[0.25em] text-on-primary">EtnoCity Otel &amp; Retreat</span>
        </div>
        <h1 class="font-display-lg text-display-lg-mobile lg:text-display-lg text-on-primary max-w-4xl tracking-tight leading-none mb-6">
            {{ $siteSetting?->translated('hero_title') ?: 'Zamansız Bir Kaçış, Sizin İçin Tasarlandı' }}
        </h1>
        <p class="font-body-lg text-body-lg text-surface-container-high max-w-2xl font-light leading-relaxed mb-12">
            {{ $siteSetting?->translated('hero_subtitle') ?? '' }}
        </p>
    @endif

    {{-- Floating Booking Widget --}}
    <x-booking-widget />
</div>
</section>
@if ($heroSlides->count() > 1)
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slides = Array.from(document.querySelectorAll('[data-hero-slide]'));
        const texts = Array.from(document.querySelectorAll('[data-hero-text]'));
        const dots = Array.from(document.querySelectorAll('[data-hero-dot]'));
        let currentIndex = 0;
        let timer;

        function showSlide(index) {
            slides.forEach(function (slide, slideIndex) {
                slide.classList.toggle('opacity-100', slideIndex === index);
                slide.classList.toggle('opacity-0', slideIndex !== index);
            });
            texts.forEach(function (text, textIndex) {
                text.classList.toggle('opacity-100', textIndex === index);
                text.classList.toggle('opacity-0', textIndex !== index);
                text.classList.toggle('pointer-events-none', textIndex !== index);
            });
            dots.forEach(function (dot, dotIndex) {
                dot.classList.toggle('bg-white', dotIndex === index);
                dot.classList.toggle('bg-white/40', dotIndex !== index);
            });
            currentIndex = index;
        }

        function startSlider() {
            window.clearInterval(timer);
            timer = window.setInterval(function () {
                showSlide((currentIndex + 1) % slides.length);
            }, 6000);
        }

        dots.forEach(function (dot, index) {
            dot.addEventListener('click', function () {
                showSlide(index);
                startSlider();
            });
        });

        startSlider();
    });
</script>
@endpush
@endif
<!-- 2. HOTEL INTRODUCTION (Narrative & Architecture) -->
<section class="w-full py-28 lg:py-36 bg-surface" id="hikayemiz">
<div class="max-w-[1440px] mx-auto px-6 lg:px-12">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
<!-- Left: Editorial Narrative -->
<div class="lg:col-span-6 space-y-8">
<div class="space-y-3">
<span class="font-label-md text-label-md uppercase text-secondary tracking-[0.28em] block">{{ $siteSetting->translated('about_eyebrow') }}</span>
<h2 class="font-headline-lg text-headline-lg text-on-surface leading-tight">
              {{ $siteSetting->translated('about_title') }}
</h2>
</div>
<p class="font-body-lg text-body-lg text-on-surface-variant font-light leading-relaxed">{{ $siteSetting->translated('about_primary_text') }}</p>
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
            {{ $siteSetting->translated('about_secondary_text') }}
          </p>
<div class="pt-4 flex flex-col sm:flex-row items-start sm:items-center gap-8">
<a class="inline-flex items-center gap-3 font-label-md text-label-md uppercase tracking-[0.24em] text-primary hover:text-secondary group transition-colors" href="#">
<span>Hikayemizi &amp; Mirasımızı Keşfedin</span>
<span class="material-symbols-outlined text-[18px] group-hover:translate-x-1.5 transition-transform">arrow_right_alt</span>
</a>
<div class="flex items-center gap-3 px-4 py-2 bg-surface-container">
<span class="font-headline-sm text-headline-sm text-secondary">{{ $siteSetting->suite_count }}</span>
<span class="font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant leading-tight">{{ __('site.private_suites') }}</span>
</div>
</div>
</div>
<!-- Right: Architectural Composition with Floating Quote -->
<div class="lg:col-span-6 relative">
<div class="relative overflow-hidden bg-surface-container-high shadow-xl">
<img class="w-full h-[520px] lg:h-[600px] object-cover transition-transform duration-700 hover:scale-105" alt="{{ $siteSetting->translated('about_title') }}" src="{{ $siteSetting->aboutImageUrl() }}">
</div>
<!-- Floating Awards Badge Overlap -->
<div class="absolute -bottom-8 -left-4 sm:-left-8 bg-surface-container-lowest p-6 lg:p-8 shadow-2xl max-w-xs space-y-2">
<div class="flex items-center gap-1 text-secondary">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<p class="font-headline-sm text-headline-sm text-on-surface">{{ $siteSetting->translated('award_badge_title') ?: '1 Numaralı Sığınak Seçildi' }}</p>
<p class="font-label-sm text-[11px] text-on-surface-variant uppercase tracking-wider">{{ $siteSetting->translated('award_badge_subtitle') ?: 'Akdeniz Butik Sığınak & Mimarlık Ödülü 2024' }}</p>
</div>
</div>
</div>
</div>
</section>
<!-- 3. ROOMS & SUITES (Editorial Showcase) -->
<section class="w-full py-28 bg-surface-container-low" id="odalar">
<div class="max-w-[1440px] mx-auto px-6 lg:px-12 space-y-16">
<!-- Section Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6">
<div class="space-y-3 max-w-xl">
<span class="font-label-md text-label-md uppercase text-secondary tracking-[0.28em] block">Konaklama Birimleri</span>
<h2 class="font-headline-lg text-headline-lg text-on-surface">Özel Süitler</h2>
<p class="font-body-md text-body-md text-on-surface-variant">Her konaklama birimi kasıtlı olarak açık ufka yönlendirilmiş, akustik huzur ve mutlak gizlilik için kireçtaşı kayalıklara oyulmuştur.</p>
</div>
<div class="flex items-center gap-3">
<button class="px-5 py-2.5 bg-surface text-on-surface font-label-md text-label-md uppercase tracking-[0.2em] hover:bg-surface-container-highest transition-colors">Tüm Süitler (24)</button>
<button class="px-5 py-2.5 text-on-surface-variant font-label-md text-label-md uppercase tracking-[0.2em] hover:text-on-surface transition-colors">Villalar</button>
</div>
</div>
<!-- Suite 1: Deluxe Sea View Room -->
<div class="bg-surface p-6 lg:p-10 shadow-sm flex flex-col lg:flex-row gap-10 items-stretch transition-shadow duration-300 hover:shadow-xl group">
<div class="lg:w-7/12 relative overflow-hidden bg-surface-container h-[360px] lg:h-[440px]">
<img class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700" data-alt="Interior of a luxury minimalist bedroom suite with expansive sliding glass walls opening to panoramic Aegean sea view. White linen king bed, hand-loomed wool rugs, travertine bedside tables with warm ceramic lamps, serene morning natural light." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAO5hP7nqtL_R23Vy3Loapk82f6u4HyldXocrgh6h_kA14McR051EJndzxtDVbVNrCjLYthQBaCTPLxdFdMGYNQM9GBDrZ1QciKH9zWDqtnok8OMBj_c_bEQ9yU92naR1TBbQlcjSlZIw003TPAWia4Lq1txkfaofJ7WdHWDRXIj8TiqdxEEQDTpnwtjRFBdd-SSispOrI6P9hAXMvcCmo692106hLEVKC4G9ojgQqfEH28oTn7zWjisQ">
<div class="absolute top-4 left-4 bg-surface/90 backdrop-blur-md px-3 py-1 font-label-sm text-label-sm uppercase tracking-widest text-on-surface">
            Ufuk Katı
          </div>
</div>
<div class="lg:w-5/12 flex flex-col justify-between space-y-6">
<div class="space-y-4">
<div class="flex flex-wrap gap-2">
<span class="px-2.5 py-1 bg-surface-container font-label-sm text-[10px] uppercase text-on-surface-variant tracking-wider">35 m² İç Mekan</span>
<span class="px-2.5 py-1 bg-surface-container font-label-sm text-[10px] uppercase text-on-surface-variant tracking-wider">2 Misafir</span>
<span class="px-2.5 py-1 bg-surface-container font-label-sm text-[10px] uppercase text-on-surface-variant tracking-wider">King Yatak</span>
<span class="px-2.5 py-1 bg-surface-container font-label-sm text-[10px] uppercase text-on-surface-variant tracking-wider">Panoramik Deniz Manzarası</span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface group-hover:text-secondary transition-colors">Deluxe Deniz Manzaralı Oda</h3>
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
              Yumuşak deniz esintilerinde, Ege taş terasına açılan tavandan tabana cam kapılı bu oda; el oyması traverten banyo, organik keten yatak örtüsü ve her gün oda servisi kahvaltısı sunar.
            </p>
</div>
<div class="pt-6 space-y-4">
<div class="flex items-baseline gap-3">
<span class="font-headline-md text-headline-md text-on-surface">₺5,750</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">/ gece · yakl. €155 / $168</span>
</div>
<div class="flex items-center gap-4">
<button class="flex-1 py-3.5 bg-primary text-on-primary hover:bg-secondary hover:text-on-secondary transition-colors font-label-md text-label-md uppercase tracking-[0.2em] text-center">
                Oda Seç
              </button>
<button class="px-5 py-3.5 bg-surface-container text-on-surface hover:bg-surface-container-highest transition-colors font-label-md text-label-md uppercase tracking-[0.18em]">
                Detaylar
              </button>
</div>
</div>
</div>
</div>
<!-- Suite 2: Cliffside Infinity Suite -->
<div class="bg-surface p-6 lg:p-10 shadow-sm flex flex-col lg:flex-row-reverse gap-10 items-stretch transition-shadow duration-300 hover:shadow-xl group">
<div class="lg:w-7/12 relative overflow-hidden bg-surface-container h-[360px] lg:h-[440px]">
<img class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700" data-alt="Luxury cliffside suite terrace with private private heated stone infinity plunge pool looking toward sunset over the Aegean archipelago. Travertine daybeds, sunken outdoor lounge, warm architectural lighting, high-end resort aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBuFcpUugKnBg_bi85jvxZWTOUm_AVwy8DtO_xKHNehNfoAQaKp41yNGYpE5Y-hD_23f24SPB5Y_4ppzJiwxpXpjarHa56QPwzZIYZt9DjIkAqYxiXUw_973dSppa78vzBeFv9GdcGjJBIn3qf63PJJiS7pr8bdYwVH2vSTv3BbjeDNOz9so3NSKSlFKM1qtTQwmSyldNzF9cH3f9httuo5lO1H4OOae0CdQ-DeLG5tLExLNyFOHbI8bg">
<div class="absolute top-4 right-4 bg-secondary text-on-secondary px-3 py-1 font-label-sm text-label-sm uppercase tracking-widest">
            İmza Süit
          </div>
</div>
<div class="lg:w-5/12 flex flex-col justify-between space-y-6">
<div class="space-y-4">
<div class="flex flex-wrap gap-2">
<span class="px-2.5 py-1 bg-surface-container font-label-sm text-[10px] uppercase text-on-surface-variant tracking-wider">65 m² İç Mekan</span>
<span class="px-2.5 py-1 bg-surface-container font-label-sm text-[10px] uppercase text-on-surface-variant tracking-wider">3 Misafir</span>
<span class="px-2.5 py-1 bg-secondary-fixed text-on-secondary-fixed font-label-sm text-[10px] uppercase tracking-wider font-semibold">Özel Derin Havuz</span>
<span class="px-2.5 py-1 bg-surface-container font-label-sm text-[10px] uppercase text-on-surface-variant tracking-wider">Gün Batımı Manzarası</span>
<span class="px-2.5 py-1 bg-surface-container font-label-sm text-[10px] uppercase text-on-surface-variant tracking-wider">Butler</span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface group-hover:text-secondary transition-colors">Uçurum Kenarı Sonsuzluk Süiti</h3>
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
              Doğrudan kireçtaşı yamaçlara oyulmuştur. Geniş özel teras, batı deniz ufkuna doğru uzanan ısıtmalı tuzlu havuzu barındırır. 24 saat özel butler ve her akşam gün batımı kokteyl servisi dahildir.
            </p>
</div>
<div class="pt-6 space-y-4">
<div class="flex items-baseline gap-3">
<span class="font-headline-md text-headline-md text-on-surface">₺9,200</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">/ gece · yakl. €248 / $268</span>
</div>
<div class="flex items-center gap-4">
<button class="flex-1 py-3.5 bg-primary text-on-primary hover:bg-secondary hover:text-on-secondary transition-colors font-label-md text-label-md uppercase tracking-[0.2em] text-center">
                Oda Seç
              </button>
<button class="px-5 py-3.5 bg-surface-container text-on-surface hover:bg-surface-container-highest transition-colors font-label-md text-label-md uppercase tracking-[0.18em]">
                Detaylar
              </button>
</div>
</div>
</div>
</div>
<!-- Suite 3: The Olive Grove Villa -->
<div class="bg-surface p-6 lg:p-10 shadow-sm flex flex-col lg:flex-row gap-10 items-stretch transition-shadow duration-300 hover:shadow-xl group">
<div class="lg:w-7/12 relative overflow-hidden bg-surface-container h-[360px] lg:h-[440px]">
<img class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700" data-alt="Master living room and private courtyard of a luxury Mediterranean two-bedroom villa. Century-old olive trees in an internal atrium, minimalist open-concept lounge with bespoke timber furniture, private 12-meter heated pool beyond glass partitions." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCjzHMiwbjmqrt49p7HvgVilrrz_TYkilgfROxkF6kTELQ9VVTGtwqvO0sTianZwMEVRLl4CKRU8p9AG0lY3VS9Yk1pt34gg21xKlmV0NuYbUprN3RPRE6fogcFOz7H6E325gfnUWgYtNORNdnm2cGxLTqGtlDVmCCXhqfsDuF-F4g5kstOVIdMW1gePOTaM3fQLqzOXHTALlJiPE87Kb1iI-kk6vxspnw-it2dMB3bEp8H46QY_ZequQ">
<div class="absolute top-4 left-4 bg-primary text-on-primary px-3 py-1 font-label-sm text-label-sm uppercase tracking-widest">
            Özel Villa
          </div>
</div>
<div class="lg:w-5/12 flex flex-col justify-between space-y-6">
<div class="space-y-4">
<div class="flex flex-wrap gap-2">
<span class="px-2.5 py-1 bg-surface-container font-label-sm text-[10px] uppercase text-on-surface-variant tracking-wider">140 m² Malikane</span>
<span class="px-2.5 py-1 bg-surface-container font-label-sm text-[10px] uppercase text-on-surface-variant tracking-wider">4 Misafir</span>
<span class="px-2.5 py-1 bg-secondary-fixed text-on-secondary-fixed font-label-sm text-[10px] uppercase tracking-wider font-semibold">12m Özel Havuz</span>
<span class="px-2.5 py-1 bg-surface-container font-label-sm text-[10px] uppercase text-on-surface-variant tracking-wider">Özel Şef</span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface group-hover:text-secondary transition-colors">Zeytin Bahçesi Villası</h3>
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
              Yüzyıllık zeytin bahçeleriyle çevrili özel villamız. İki ana süit, açık hava yemek rotundası, özel spor salonu köşkü ve kişiye özel mutfak deneyimi sunan özel şef içerir.
            </p>
</div>
<div class="pt-6 space-y-4">
<div class="flex items-baseline gap-3">
<span class="font-headline-md text-headline-md text-on-surface">₺18,500</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">/ gece · yakl. €498 / $540</span>
</div>
<div class="flex items-center gap-4">
<button class="flex-1 py-3.5 bg-primary text-on-primary hover:bg-secondary hover:text-on-secondary transition-colors font-label-md text-label-md uppercase tracking-[0.2em] text-center">
                Oda Seç
              </button>
<button class="px-5 py-3.5 bg-surface-container text-on-surface hover:bg-surface-container-highest transition-colors font-label-md text-label-md uppercase tracking-[0.18em]">
                Detaylar
              </button>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- 4. CURATED AMENITIES & FEATURES -->
<section class="w-full py-28 bg-surface">
<div class="max-w-[1440px] mx-auto px-6 lg:px-12">
<div class="text-center max-w-2xl mx-auto space-y-3 mb-20">
<span class="font-label-md text-label-md uppercase text-secondary tracking-[0.28em] block">{{ $siteSetting->translated('amenities_eyebrow') ?: 'Sessiz Olanaklar' }}</span>
<h2 class="font-headline-lg text-headline-lg text-on-surface">{{ $siteSetting->translated('amenities_title') ?: 'Mimari & Özen' }}</h2>
<p class="font-body-md text-body-md text-on-surface-variant">{{ $siteSetting->translated('amenities_subtitle') ?: 'Mülkün her boyutu, sakin fiziksel rahatlamayı ve derin, yenileyici uykuyu teşvik etmek için tasarlanmıştır.' }}</p>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
@forelse ($amenities as $amenity)
<div class="bg-surface-container-low p-8 space-y-4 hover:bg-surface-container transition-colors duration-300">
<div class="w-12 h-12 bg-surface flex items-center justify-center text-secondary">
<span class="material-symbols-outlined text-[28px]">{{ $amenity->icon }}</span>
</div>
<h4 class="font-headline-sm text-headline-sm text-on-surface">{{ $amenity->translatedTitle() }}</h4>
@if ($amenity->translatedDescription())
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
    {{ $amenity->translatedDescription() }}
</p>
@endif
</div>
@empty
<div class="col-span-full text-center py-12 text-on-surface-variant">
    Henüz olanak eklenmedi.
</div>
@endforelse
</div>
</div>
</section>
<!-- 5. BESPOKE EXPERIENCES -->
<section class="w-full py-28 bg-surface-container-high" id="etkinlikler">
<div class="max-w-[1440px] mx-auto px-6 lg:px-12 space-y-16">
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
<div class="space-y-3 max-w-xl">
<span class="font-label-md text-label-md uppercase text-secondary tracking-[0.28em] block">{{ $siteSetting->translated('events_eyebrow') ?: 'Villanın Ötesinde' }}</span>
<h2 class="font-headline-lg text-headline-lg text-on-surface">{{ $siteSetting->translated('events_title') ?: 'Özel Deneyimler' }}</h2>
<p class="font-body-md text-body-md text-on-surface-variant">{{ $siteSetting->translated('events_subtitle') ?: 'Her deneyim özel olarak tasarlanmış, aceleye getirilmeden ve değişen gün ışığıyla senkronize edilmiştir.' }}</p>
</div>
<div class="flex items-center gap-6">
@if ($events->isNotEmpty())
<a class="font-label-md text-label-md uppercase tracking-[0.22em] text-primary hover:text-secondary flex items-center gap-2" href="#">
<span>Tüm {{ $events->count() }} Deneyimi Görüntüle</span>
<span class="material-symbols-outlined text-[18px]">east</span>
</a>
{{-- Carousel İleri / Geri Butonları --}}
<div class="hidden sm:flex items-center gap-2">
<button id="prevEventsBtn" type="button" class="w-11 h-11 rounded-full border border-primary/20 flex items-center justify-center text-primary hover:bg-primary hover:text-on-primary transition-all duration-300 shadow-sm" aria-label="Önceki Deneyim">
    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
</button>
<button id="nextEventsBtn" type="button" class="w-11 h-11 rounded-full border border-primary/20 flex items-center justify-center text-primary hover:bg-primary hover:text-on-primary transition-all duration-300 shadow-sm" aria-label="Sonraki Deneyim">
    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
</button>
</div>
@endif
</div>
</div>

{{-- Kaydırılabilir Deneyimler Vitrini --}}
<div id="eventsCarousel" class="flex gap-6 lg:gap-8 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4 pt-2 -mx-6 px-6 lg:-mx-12 lg:px-12" style="scrollbar-width: none; -ms-overflow-style: none;">
@forelse ($events as $event)
<a href="{{ route('events.show', $event) }}" class="flex-shrink-0 w-[85vw] sm:w-[360px] lg:w-[410px] snap-start relative h-[560px] overflow-hidden group cursor-pointer bg-primary rounded-none shadow-md transition-all duration-500 block text-decoration-none">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000 opacity-80" alt="{{ $event->translatedTitle() }}" src="{{ $event->imageUrl() }}">
<div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/30 to-transparent"></div>
<div class="absolute bottom-0 left-0 w-full p-8 space-y-4">
@if ($event->translatedCategory())
<span class="font-label-sm text-label-sm uppercase tracking-[0.25em] text-secondary-fixed">{{ $event->translatedCategory() }}</span>
@endif
<h3 class="font-headline-md text-headline-md text-on-primary">{{ $event->translatedTitle() }}</h3>
@if ($event->translatedDescription())
<p class="font-body-sm text-body-sm text-surface-container-high line-clamp-3">
    {{ $event->translatedDescription() }}
</p>
@endif
<div class="pt-2 flex items-center gap-2 text-on-primary font-label-md text-label-md uppercase tracking-wider group-hover:translate-x-1 transition-transform">
<span>{{ app()->isLocale('en') ? 'Explore' : 'İncele' }}</span>
<span class="material-symbols-outlined text-[16px]">arrow_forward</span>
</div>
</div>
</a>
@empty
<div class="w-full text-center py-12 text-on-surface-variant">
    Henüz anasayfada yayınlanan bir deneyim bulunmuyor.
</div>
@endforelse
</div>
</div>
</section>
<!-- 6. ASYMMETRICAL EDITORIAL GALLERY -->
<section class="w-full py-28 bg-surface" id="galeri">
<div class="max-w-[1440px] mx-auto px-6 lg:px-12 space-y-12">
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
<div class="space-y-3">
<span class="font-label-md text-label-md uppercase text-secondary tracking-[0.28em] block">{{ $siteSetting?->translated('gallery_eyebrow') ?: (app()->isLocale('en') ? 'Visual Archive' : 'Görsel Arşiv') }}</span>
<h2 class="font-headline-lg text-headline-lg text-on-surface">{{ $siteSetting?->translated('gallery_title') ?: (app()->isLocale('en') ? 'Atmospheric Moments' : 'Atmosferik Anlar') }}</h2>
</div>
<a href="{{ route('gallery') }}" class="inline-flex items-center gap-3 px-5 py-2.5 bg-surface-container text-on-surface hover:bg-primary hover:text-on-primary font-label-sm text-label-sm uppercase tracking-wider transition-all duration-300 group">
<span>{{ app()->isLocale('en') ? 'Explore Full Gallery' : 'Tüm Galeriyi İncele' }}</span>
<span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
</a>
</div>
<!-- Asymmetrical Masonry Grid (5 Images) -->
@if ($galleryImages->isNotEmpty())
<div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-stretch" id="homeGalleryGrid">
@if ($img1 = $galleryImages->get(0))
<div class="home-gallery-item md:col-span-7 relative overflow-hidden bg-surface-container h-[420px] md:h-[580px] group cursor-pointer"
     data-src="{{ $img1->imageUrl() }}"
     data-tag="{{ $img1->translatedTag() }}"
     data-category-name="{{ $img1->category?->translatedName() }}">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="{{ $img1->imageUrl() }}" alt="{{ $img1->translatedTag() }}">
@if ($img1->translatedTag())
<div class="absolute bottom-6 left-6 bg-surface/90 backdrop-blur-md px-4 py-2 pointer-events-none shadow-sm">
<p class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface">{{ $img1->translatedTag() }}</p>
</div>
@endif
<div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
<div class="w-10 h-10 rounded-full bg-surface/85 backdrop-blur-md flex items-center justify-center text-on-surface shadow-md">
<span class="material-symbols-outlined text-[20px]">zoom_in</span>
</div>
</div>
</div>
@endif

@if ($galleryImages->count() > 1)
<div class="md:col-span-5 flex flex-col gap-6">
@if ($img2 = $galleryImages->get(1))
<div class="home-gallery-item relative overflow-hidden bg-surface-container h-[200px] md:h-[278px] group cursor-pointer flex-1"
     data-src="{{ $img2->imageUrl() }}"
     data-tag="{{ $img2->translatedTag() }}"
     data-category-name="{{ $img2->category?->translatedName() }}">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="{{ $img2->imageUrl() }}" alt="{{ $img2->translatedTag() }}">
@if ($img2->translatedTag())
<div class="absolute bottom-4 left-4 bg-surface/90 backdrop-blur-md px-3.5 py-1 pointer-events-none shadow-sm">
<p class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface">{{ $img2->translatedTag() }}</p>
</div>
@endif
<div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
<div class="w-9 h-9 rounded-full bg-surface/85 backdrop-blur-md flex items-center justify-center text-on-surface shadow-md">
<span class="material-symbols-outlined text-[18px]">zoom_in</span>
</div>
</div>
</div>
@endif
@if ($img3 = $galleryImages->get(2))
<div class="home-gallery-item relative overflow-hidden bg-surface-container h-[200px] md:h-[278px] group cursor-pointer flex-1"
     data-src="{{ $img3->imageUrl() }}"
     data-tag="{{ $img3->translatedTag() }}"
     data-category-name="{{ $img3->category?->translatedName() }}">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="{{ $img3->imageUrl() }}" alt="{{ $img3->translatedTag() }}">
@if ($img3->translatedTag())
<div class="absolute bottom-4 left-4 bg-surface/90 backdrop-blur-md px-3.5 py-1 pointer-events-none shadow-sm">
<p class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface">{{ $img3->translatedTag() }}</p>
</div>
@endif
<div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
<div class="w-9 h-9 rounded-full bg-surface/85 backdrop-blur-md flex items-center justify-center text-on-surface shadow-md">
<span class="material-symbols-outlined text-[18px]">zoom_in</span>
</div>
</div>
</div>
@endif
</div>
@endif

    {{-- 4. ve 5. Görseller (Alt Satır - 2 Büyük Editoryal Blok) --}}
    @if ($galleryImages->count() > 3)
        <div class="col-span-1 md:col-span-12 grid grid-cols-1 md:grid-cols-2 gap-6">
            @if ($img4 = $galleryImages->get(3))
                <div class="home-gallery-item {{ $galleryImages->get(4) ? '' : 'md:col-span-2' }} relative overflow-hidden bg-surface-container h-[380px] md:h-[500px] lg:h-[540px] group cursor-pointer"
                     data-src="{{ $img4->imageUrl() }}"
                     data-tag="{{ $img4->translatedTag() }}"
                     data-category-name="{{ $img4->category?->translatedName() }}">
                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="{{ $img4->imageUrl() }}" alt="{{ $img4->translatedTag() }}">
                    @if ($img4->translatedTag())
                        <div class="absolute bottom-6 left-6 bg-surface/90 backdrop-blur-md px-4 py-2 pointer-events-none shadow-sm">
                            <p class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface">{{ $img4->translatedTag() }}</p>
                        </div>
                    @endif
                    <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                        <div class="w-10 h-10 rounded-full bg-surface/85 backdrop-blur-md flex items-center justify-center text-on-surface shadow-md">
                            <span class="material-symbols-outlined text-[20px]">zoom_in</span>
                        </div>
                    </div>
                </div>
            @endif

            @if ($img5 = $galleryImages->get(4))
                <div class="home-gallery-item relative overflow-hidden bg-surface-container h-[380px] md:h-[500px] lg:h-[540px] group cursor-pointer"
                     data-src="{{ $img5->imageUrl() }}"
                     data-tag="{{ $img5->translatedTag() }}"
                     data-category-name="{{ $img5->category?->translatedName() }}">
                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="{{ $img5->imageUrl() }}" alt="{{ $img5->translatedTag() }}">
                    @if ($img5->translatedTag())
                        <div class="absolute bottom-6 left-6 bg-surface/90 backdrop-blur-md px-4 py-2 pointer-events-none shadow-sm">
                            <p class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface">{{ $img5->translatedTag() }}</p>
                        </div>
                    @endif
                    <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                        <div class="w-10 h-10 rounded-full bg-surface/85 backdrop-blur-md flex items-center justify-center text-on-surface shadow-md">
                            <span class="material-symbols-outlined text-[20px]">zoom_in</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
@endif
</div>

<!-- FULLSCREEN LUXURY LIGHTBOX MODAL (HOMEPAGE) -->
<div id="homeGalleryLightbox" class="fixed inset-0 z-[99999] hidden items-center justify-center p-3 sm:p-6 md:p-10 select-none transition-all duration-300 opacity-0" style="background-color: rgba(6, 6, 6, 0.82); backdrop-filter: blur(28px); -webkit-backdrop-filter: blur(28px);" role="dialog" aria-modal="true">
    <div id="homeLightboxBackdrop" class="absolute inset-0 z-10 cursor-pointer"></div>

    <div class="absolute top-4 sm:top-6 left-4 sm:left-8 right-4 sm:right-8 z-30 flex items-center justify-between pointer-events-none">
        <div class="pointer-events-auto bg-black/50 backdrop-blur-md px-4 py-2 border border-white/10 shadow-lg text-white font-label-sm text-xs tracking-widest uppercase">
            <span id="homeLightboxCounter">1 / 5</span>
        </div>
        <button type="button" id="homeLightboxClose" class="pointer-events-auto w-12 h-12 rounded-full bg-black/50 hover:bg-white text-white hover:text-black flex items-center justify-center transition-all duration-300 border border-white/20 hover:scale-105 shadow-2xl backdrop-blur-md cursor-pointer" aria-label="Kapat">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <button type="button" id="homeLightboxPrev" class="absolute left-3 sm:left-6 md:left-10 top-1/2 -translate-y-1/2 z-30 w-12 sm:w-16 h-12 sm:h-16 rounded-full bg-black/60 hover:bg-white text-white hover:text-black flex items-center justify-center transition-all duration-300 border border-white/25 hover:border-white hover:scale-110 shadow-2xl backdrop-blur-md cursor-pointer group" aria-label="Önceki Görsel">
        <svg class="w-7 sm:w-8 h-7 sm:h-8 -translate-x-0.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
    </button>

    <button type="button" id="homeLightboxNext" class="absolute right-3 sm:right-6 md:right-10 top-1/2 -translate-y-1/2 z-30 w-12 sm:w-16 h-12 sm:h-16 rounded-full bg-black/60 hover:bg-white text-white hover:text-black flex items-center justify-center transition-all duration-300 border border-white/25 hover:border-white hover:scale-110 shadow-2xl backdrop-blur-md cursor-pointer group" aria-label="Sonraki Görsel">
        <svg class="w-7 sm:w-8 h-7 sm:h-8 translate-x-0.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
    </button>

    <div class="relative z-20 w-full max-w-6xl h-full flex flex-col items-center justify-center pointer-events-none px-2 sm:px-16">
        <div class="relative max-h-[78vh] sm:max-h-[82vh] w-full flex items-center justify-center pointer-events-auto">
            <img id="homeLightboxImg" src="" alt="" class="max-h-[76vh] sm:max-h-[80vh] w-auto max-w-[92vw] sm:max-w-[85vw] object-contain shadow-[0_25px_60px_-15px_rgba(0,0,0,0.85)] rounded-sm transition-all duration-300">
        </div>
        <div class="mt-4 text-center space-y-1 max-w-3xl px-4 pointer-events-auto">
            <span id="homeLightboxCategory" class="font-label-sm text-[11px] uppercase tracking-[0.28em] text-secondary-fixed block"></span>
            <h3 id="homeLightboxTag" class="font-headline-sm text-base sm:text-headline-sm text-white font-light tracking-wide"></h3>
        </div>
    </div>
</div>
</section>
<!-- 7. GUEST REFLECTIONS / TESTIMONIALS -->
<section class="w-full py-28 bg-surface-container-low" id="testimonialsSection">
<div class="max-w-[1000px] mx-auto px-6 text-center space-y-12">

  {{-- Bölüm Başlığı ve Açıklaması --}}
  <div class="space-y-3 max-w-2xl mx-auto">
    @if ($eyebrow = $siteSetting?->translated('testimonials_eyebrow'))
      <span class="font-label-md text-label-md uppercase text-secondary tracking-[0.28em] block">{{ $eyebrow }}</span>
    @endif
    <h2 class="font-headline-lg text-headline-lg text-on-surface">
      {{ $siteSetting?->translated('testimonials_title') ?: (app()->isLocale('en') ? 'Timeless Impressions' : 'Eşsiz Deneyimler & Hatıralar') }}
    </h2>
    @if ($desc = $siteSetting?->translated('testimonials_description'))
      <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
        {{ $desc }}
      </p>
    @endif
  </div>

  {{-- Yorumlar Slider Alanı --}}
  @if (isset($testimonials) && $testimonials->isNotEmpty())
    <div id="testimonialsSlider" class="grid grid-cols-1 items-center min-h-[220px]">
      @foreach ($testimonials as $index => $item)
        <div class="testimonial-slide w-full transition-opacity duration-700 ease-in-out space-y-6 {{ $index === 0 ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none' }}" style="grid-area: 1 / 1;" data-index="{{ $index }}">
          <div class="inline-flex items-center justify-center gap-1.5 text-secondary">
            @for ($s = 0; $s < ($item->rating ?: 5); $s++)
              <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
            @endfor
          </div>

          <blockquote class="font-display-md text-headline-lg lg:text-display-md text-on-surface leading-tight font-normal">
            “{{ $item->translatedComment() }}”
          </blockquote>

          <div class="pt-1">
            <p class="font-headline-sm text-headline-sm text-on-surface">{{ $item->author_name }}</p>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Dots & Navigation --}}
    @if ($testimonials->count() > 1)
      <div class="flex items-center justify-center gap-6 pt-4">
        <button type="button" id="prevTestimonialBtn" class="w-10 h-10 bg-surface flex items-center justify-center text-on-surface hover:bg-surface-container-highest transition-colors cursor-pointer" aria-label="Önceki Yorum">
          <span class="material-symbols-outlined text-[20px]">west</span>
        </button>
        <div class="flex items-center gap-2" id="testimonialDots">
          @foreach ($testimonials as $idx => $t)
            <button type="button" class="testimonial-dot h-1 transition-all duration-300 {{ $idx === 0 ? 'w-6 bg-primary' : 'w-2 bg-surface-variant hover:bg-primary/50' }} cursor-pointer" data-target="{{ $idx }}" aria-label="Yorum {{ $idx + 1 }}"></button>
          @endforeach
        </div>
        <button type="button" id="nextTestimonialBtn" class="w-10 h-10 bg-surface flex items-center justify-center text-on-surface hover:bg-surface-container-highest transition-colors cursor-pointer" aria-label="Sonraki Yorum">
          <span class="material-symbols-outlined text-[20px]">east</span>
        </button>
      </div>
    @endif
  @endif

</div>
</section>
<!-- 8. LOCATION & CONCIERGE GUIDE -->
<section class="w-full py-28 bg-surface" id="locationSection">
<div class="max-w-[1440px] mx-auto px-6 lg:px-12">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
<!-- Real Interactive Map Container -->
<div class="lg:col-span-7 relative">
  <div class="w-full h-[460px] lg:h-[540px] shadow-xl relative overflow-hidden bg-surface-container rounded-sm border border-outline-variant/30">
    <iframe
      src="{{ $siteSetting?->mapEmbedUrl() }}"
      class="w-full h-full border-0 filter contrast-[1.02] saturate-[0.88]"
      allowfullscreen=""
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade"
      title="{{ $siteSetting?->translated('hotel_name') ?: 'EtnoCity Otel' }} Haritası">
    </iframe>

    {{-- Overlay coordinate & location tag --}}
    @if ($siteSetting?->location_coordinates || $siteSetting?->location_address)
      <div class="absolute top-6 left-6 bg-surface/90 backdrop-blur-md p-4 space-y-1 max-w-xs shadow-md pointer-events-none border border-black/5">
        <span class="font-label-sm text-[10px] uppercase tracking-widest text-secondary block">
          {{ app()->isLocale('en') ? 'GPS Geographic Anchor' : 'GPS Coğrafi Konumu' }}
        </span>
        @if ($siteSetting?->location_coordinates)
          <p class="font-title-md text-title-md text-on-surface">{{ $siteSetting->location_coordinates }}</p>
        @endif
        @if ($siteSetting?->location_address)
          <p class="font-body-sm text-body-sm text-on-surface-variant">{{ $siteSetting->location_address }}</p>
        @endif
      </div>
    @endif

    {{-- Pin badge tag if set --}}
    @if ($pinLabel = ($siteSetting?->location_pin_label ?: $siteSetting?->hotel_name))
      <div class="absolute bottom-6 right-6 bg-primary text-on-primary px-3.5 py-1.5 font-label-sm text-[11px] uppercase tracking-widest shadow-xl flex items-center gap-2 pointer-events-none">
        <span class="material-symbols-outlined text-[16px] text-secondary-fixed">location_on</span>
        <span>{{ $pinLabel }}</span>
      </div>
    @endif
  </div>
</div>
<!-- Location Information & Transit -->
<div class="lg:col-span-5 space-y-8">
  <div class="space-y-3">
    @if ($eyebrow = $siteSetting?->translated('location_eyebrow'))
      <span class="font-label-md text-label-md uppercase text-secondary tracking-[0.28em] block">{{ $eyebrow }}</span>
    @endif
    <h2 class="font-headline-lg text-headline-lg text-on-surface">
      {{ $siteSetting?->translated('location_title') ?: (app()->isLocale('en') ? 'Secluded Yet Effortlessly Accessible' : 'Sakin Ama Kolayca Ulaşılabilir') }}
    </h2>
    @if ($desc = $siteSetting?->translated('location_description'))
      <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
        {{ $desc }}
      </p>
    @endif
  </div>

  {{-- Yakın Yerler / Ulaşım Noktaları --}}
  <div class="space-y-4">
    @if (isset($nearbyPlaces) && $nearbyPlaces->isNotEmpty())
      @foreach ($nearbyPlaces as $place)
        <div class="p-4 bg-surface-container flex items-center justify-between gap-4 transition-all duration-300 hover:bg-surface-container-high">
          <div class="flex items-center gap-3.5 min-w-0">
            <div class="w-10 h-10 rounded-full bg-surface/80 flex items-center justify-center shrink-0 text-secondary shadow-sm">
              <span class="material-symbols-outlined text-[22px]">{{ $place->icon ?: 'location_on' }}</span>
            </div>
            <div class="min-w-0">
              <p class="font-title-md text-title-md text-on-surface truncate">{{ $place->translatedTitle() }}</p>
              @if ($place->translatedDescription())
                <p class="font-body-sm text-body-sm text-on-surface-variant truncate">{{ $place->translatedDescription() }}</p>
              @endif
            </div>
          </div>
          @if ($place->distance)
            <span class="font-label-sm text-[11px] text-secondary font-semibold uppercase shrink-0 px-2.5 py-1 bg-surface/80 rounded-sm shadow-sm">{{ $place->distance }}</span>
          @endif
        </div>
      @endforeach
    @endif
  </div>
</div>
</div>
</div>
</section>
<!-- 9. INTERACTIVE AVAILABILITY OVERLAY MODAL -->
<div class="fixed inset-0 z-50 bg-primary/60 backdrop-blur-sm hidden items-center justify-center p-4" id="availabilityModal">
<div class="bg-surface max-w-xl w-full p-8 lg:p-10 shadow-2xl space-y-6 relative">
<button class="absolute top-6 right-6 text-on-surface-variant hover:text-on-surface" id="closeModalBtn">
<span class="material-symbols-outlined text-[24px]">close</span>
</button>
<div class="space-y-2">
<span class="font-label-sm text-label-sm uppercase tracking-[0.25em] text-secondary">EtnoCity Rezervasyon Masası</span>
<h3 class="font-headline-md text-headline-md text-on-surface">Konaklama Müsaitlik Sorgusu</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant">Ekim 12 – Ekim 17, 2025 tarihleri için gerçek zamanlı müsaitlik kontrolü yapılıyor.</p>
</div>
<div class="space-y-4 pt-2">
<div class="p-4 bg-surface-container-low flex items-center justify-between">
<div>
<p class="font-title-md text-title-md text-on-surface">Deluxe Deniz Manzaralı Oda</p>
<p class="font-body-sm text-body-sm text-secondary">2 Oda Müsait</p>
</div>
<span class="font-headline-sm text-headline-sm text-on-surface">₺5,750</span>
</div>
<div class="p-4 bg-surface-container-low flex items-center justify-between">
<div>
<p class="font-title-md text-title-md text-on-surface">Uçurum Kenarı Sonsuzluk Süiti</p>
<p class="font-body-sm text-body-sm text-secondary">1 Oda Müsait (Son Oda)</p>
</div>
<span class="font-headline-sm text-headline-sm text-on-surface">₺9,200</span>
</div>
<div class="p-4 bg-surface-container-low flex items-center justify-between opacity-60">
<div>
<p class="font-title-md text-title-md text-on-surface">Zeytin Bahçesi Villası</p>
<p class="font-body-sm text-body-sm text-error">Seçilen Tarihlerde Dolu</p>
</div>
<span class="font-label-sm text-label-sm uppercase text-outline">Tükendi</span>
</div>
</div>
<div class="pt-4 flex items-center gap-4">
<a class="flex-1 py-3.5 bg-primary text-on-primary hover:bg-secondary hover:text-on-secondary transition-colors font-label-md text-label-md uppercase tracking-[0.2em] text-center" href="#">
          Özel Rezervasyona Geç
        </a>
</div>
</div>
</div>
<!-- Inline Vanilla Interactive Script -->

</div>

@push('scripts')
<script>
    (function initSanctuaryInteractions() {
      // Modal triggers
      const heroCheckBtn = document.getElementById('heroCheckBtn');
      const conciergeModalTrigger = document.getElementById('conciergeModalTrigger');
      const modal = document.getElementById('availabilityModal');
      const closeModalBtn = document.getElementById('closeModalBtn');

      function openModal() {
        if (modal) {
          modal.classList.remove('hidden');
          modal.classList.add('flex');
        }
      }

      function closeModal() {
        if (modal) {
          modal.classList.add('hidden');
          modal.classList.remove('flex');
        }
      }

      if (heroCheckBtn) heroCheckBtn.addEventListener('click', openModal);
      if (conciergeModalTrigger) conciergeModalTrigger.addEventListener('click', openModal);
      if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);

      if (modal) {
        modal.addEventListener('click', (e) => {
          if (e.target === modal) closeModal();
        });
      }

      // Homepage Gallery Lightbox Logic
      const homeLightbox = document.getElementById('homeGalleryLightbox');
      const homeLightboxImg = document.getElementById('homeLightboxImg');
      const homeLightboxTag = document.getElementById('homeLightboxTag');
      const homeLightboxCategory = document.getElementById('homeLightboxCategory');
      const homeLightboxCounter = document.getElementById('homeLightboxCounter');
      const homeCloseBtn = document.getElementById('homeLightboxClose');
      const homeBackdrop = document.getElementById('homeLightboxBackdrop');
      const homePrevBtn = document.getElementById('homeLightboxPrev');
      const homeNextBtn = document.getElementById('homeLightboxNext');
      const homeCards = Array.from(document.querySelectorAll('.home-gallery-item'));

      let homeCurrentIndex = 0;

      function updateHomeLightbox(animate = true) {
        if (homeCards.length === 0) return;
        const card = homeCards[homeCurrentIndex];
        const src = card.getAttribute('data-src');
        const tag = card.getAttribute('data-tag') || '';
        const catName = card.getAttribute('data-category-name') || '';

        if (animate && homeLightboxImg) {
          homeLightboxImg.style.opacity = '0.3';
          homeLightboxImg.style.transform = 'scale(0.98)';
        }

        const imgLoader = new Image();
        imgLoader.onload = function () {
          if (homeLightboxImg) {
            homeLightboxImg.src = src;
            homeLightboxImg.style.opacity = '1';
            homeLightboxImg.style.transform = 'scale(1)';
          }
          if (homeLightboxTag) homeLightboxTag.textContent = tag;
          if (homeLightboxCategory) homeLightboxCategory.textContent = catName;
          if (homeLightboxCounter) homeLightboxCounter.textContent = (homeCurrentIndex + 1) + ' / ' + homeCards.length;
        };
        imgLoader.src = src;
      }

      function openHomeLightbox(card) {
        homeCurrentIndex = homeCards.indexOf(card);
        if (homeCurrentIndex === -1) homeCurrentIndex = 0;

        updateHomeLightbox(false);

        if (homeLightbox) {
          homeLightbox.classList.remove('hidden');
          homeLightbox.classList.add('flex');
          setTimeout(() => {
            homeLightbox.classList.remove('opacity-0');
            homeLightbox.classList.add('opacity-100');
          }, 10);
          document.body.style.overflow = 'hidden';
        }
      }

      function closeHomeLightbox() {
        if (homeLightbox) {
          homeLightbox.classList.add('opacity-0');
          homeLightbox.classList.remove('opacity-100');
          setTimeout(() => {
            homeLightbox.classList.add('hidden');
            homeLightbox.classList.remove('flex');
            document.body.style.overflow = '';
          }, 250);
        }
      }

      function prevHomeImage() {
        if (homeCards.length <= 1) return;
        homeCurrentIndex = (homeCurrentIndex - 1 + homeCards.length) % homeCards.length;
        updateHomeLightbox(true);
      }

      function nextHomeImage() {
        if (homeCards.length <= 1) return;
        homeCurrentIndex = (homeCurrentIndex + 1) % homeCards.length;
        updateHomeLightbox(true);
      }

      homeCards.forEach(card => {
        card.addEventListener('click', () => openHomeLightbox(card));
      });

      if (homeCloseBtn) homeCloseBtn.addEventListener('click', closeHomeLightbox);
      if (homeBackdrop) homeBackdrop.addEventListener('click', closeHomeLightbox);
      if (homePrevBtn) homePrevBtn.addEventListener('click', prevHomeImage);
      if (homeNextBtn) homeNextBtn.addEventListener('click', nextHomeImage);

      // Touch swipe on homepage lightbox
      if (homeLightbox) {
        let touchStartX = 0;
        let touchEndX = 0;
        homeLightbox.addEventListener('touchstart', (e) => {
          touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        homeLightbox.addEventListener('touchend', (e) => {
          touchEndX = e.changedTouches[0].screenX;
          const diff = touchEndX - touchStartX;
          if (Math.abs(diff) > 40) {
            if (diff < 0) nextHomeImage();
            else prevHomeImage();
          }
        }, { passive: true });
      }

      document.addEventListener('keydown', function (e) {
        if (!homeLightbox || homeLightbox.classList.contains('hidden')) return;
        if (e.key === 'Escape') closeHomeLightbox();
        if (e.key === 'ArrowLeft') prevHomeImage();
        if (e.key === 'ArrowRight') nextHomeImage();
      });

      // Events Carousel Controls (Infinite Continuous Loop)
      const eventsCarousel = document.getElementById('eventsCarousel');
      const prevEventsBtn = document.getElementById('prevEventsBtn');
      const nextEventsBtn = document.getElementById('nextEventsBtn');

      if (eventsCarousel) {
        const cards = Array.from(eventsCarousel.querySelectorAll('.snap-start'));
        
        // Sonsuz döngü (infinite seamless loop) için kartları çoğalt
        if (cards.length > 0) {
          cards.forEach(card => {
            const clone = card.cloneNode(true);
            eventsCarousel.appendChild(clone);
          });
        }

        let isPaused = false;
        let speed = 0.9; // Sürekli akış hızı (piksel / frame)
        let animationFrameId;

        function autoScroll() {
          if (!isPaused) {
            eventsCarousel.scrollLeft += speed;
            const halfWidth = eventsCarousel.scrollWidth / 2;
            if (eventsCarousel.scrollLeft >= halfWidth) {
              eventsCarousel.scrollLeft = 0;
            } else if (eventsCarousel.scrollLeft <= 0 && speed < 0) {
              eventsCarousel.scrollLeft = halfWidth;
            }
          }
          animationFrameId = requestAnimationFrame(autoScroll);
        }

        animationFrameId = requestAnimationFrame(autoScroll);

        // Kullanıcı üzerine gelince veya dokununca duraklat
        eventsCarousel.addEventListener('mouseenter', () => { isPaused = true; });
        eventsCarousel.addEventListener('mouseleave', () => { isPaused = false; });
        eventsCarousel.addEventListener('touchstart', () => { isPaused = true; }, { passive: true });
        eventsCarousel.addEventListener('touchend', () => {
          setTimeout(() => { isPaused = false; }, 1500);
        });

        // Butonlarla manuel kaydırma
        const getCardWidth = () => {
          const first = eventsCarousel.querySelector('.snap-start');
          return first ? first.offsetWidth + 24 : 420;
        };

        if (prevEventsBtn) {
          prevEventsBtn.addEventListener('click', () => {
            isPaused = true;
            eventsCarousel.scrollBy({ left: -getCardWidth(), behavior: 'smooth' });
            setTimeout(() => { isPaused = false; }, 2000);
          });
        }

        if (nextEventsBtn) {
          nextEventsBtn.addEventListener('click', () => {
            isPaused = true;
            eventsCarousel.scrollBy({ left: getCardWidth(), behavior: 'smooth' });
            setTimeout(() => { isPaused = false; }, 2000);
          });
        }

        // Mouse Drag / Touch Swipe desteği
        let isDown = false;
        let startX, scrollLeft;

        eventsCarousel.addEventListener('mousedown', (e) => {
          isDown = true;
          isPaused = true;
          eventsCarousel.classList.add('cursor-grabbing');
          startX = e.pageX - eventsCarousel.offsetLeft;
          scrollLeft = eventsCarousel.scrollLeft;
        });

        window.addEventListener('mouseup', () => {
          if (isDown) {
            isDown = false;
            eventsCarousel.classList.remove('cursor-grabbing');
            setTimeout(() => { isPaused = false; }, 1000);
          }
        });

        eventsCarousel.addEventListener('mousemove', (e) => {
          if (!isDown) return;
          e.preventDefault();
          const x = e.pageX - eventsCarousel.offsetLeft;
          const walk = (x - startX) * 1.5;
          eventsCarousel.scrollLeft = scrollLeft - walk;
        });
      }

      // 4. Testimonials Slider Controls
      const testimonialSlides = Array.from(document.querySelectorAll('.testimonial-slide'));
      const testimonialDots = Array.from(document.querySelectorAll('.testimonial-dot'));
      const prevTestimonialBtn = document.getElementById('prevTestimonialBtn');
      const nextTestimonialBtn = document.getElementById('nextTestimonialBtn');
      const testimonialsSlider = document.getElementById('testimonialsSlider');

      if (testimonialSlides.length > 1) {
        let currentSlide = 0;
        let slideInterval = null;

        function showSlide(index) {
          if (index < 0) index = testimonialSlides.length - 1;
          if (index >= testimonialSlides.length) index = 0;
          currentSlide = index;

          testimonialSlides.forEach((slide, i) => {
            const isActive = i === currentSlide;
            slide.classList.toggle('opacity-100', isActive);
            slide.classList.toggle('pointer-events-auto', isActive);
            slide.classList.toggle('opacity-0', !isActive);
            slide.classList.toggle('pointer-events-none', !isActive);
            slide.setAttribute('aria-hidden', !isActive);
          });

          testimonialDots.forEach((dot, i) => {
            const isActive = i === currentSlide;
            if (isActive) {
              dot.classList.remove('w-2', 'bg-surface-variant');
              dot.classList.add('w-6', 'bg-primary');
            } else {
              dot.classList.remove('w-6', 'bg-primary');
              dot.classList.add('w-2', 'bg-surface-variant');
            }
          });
        }

        function nextSlide() {
          showSlide(currentSlide + 1);
        }

        function prevSlide() {
          showSlide(currentSlide - 1);
        }

        if (nextTestimonialBtn) {
          nextTestimonialBtn.addEventListener('click', () => {
            resetAutoPlay();
            nextSlide();
          });
        }

        if (prevTestimonialBtn) {
          prevTestimonialBtn.addEventListener('click', () => {
            resetAutoPlay();
            prevSlide();
          });
        }

        testimonialDots.forEach((dot, idx) => {
          dot.addEventListener('click', () => {
            resetAutoPlay();
            showSlide(idx);
          });
        });

        function startAutoPlay() {
          if (slideInterval) clearInterval(slideInterval);
          slideInterval = setInterval(nextSlide, 7000);
        }

        function resetAutoPlay() {
          clearInterval(slideInterval);
          startAutoPlay();
        }

        startAutoPlay();

        if (testimonialsSlider) {
          testimonialsSlider.addEventListener('mouseenter', () => clearInterval(slideInterval));
          testimonialsSlider.addEventListener('mouseleave', startAutoPlay);

          let touchStart = 0;
          testimonialsSlider.addEventListener('touchstart', (e) => {
            touchStart = e.changedTouches[0].screenX;
            clearInterval(slideInterval);
          }, { passive: true });

          testimonialsSlider.addEventListener('touchend', (e) => {
            const touchEnd = e.changedTouches[0].screenX;
            const diff = touchEnd - touchStart;
            if (Math.abs(diff) > 40) {
              if (diff < 0) nextSlide();
              else prevSlide();
            }
            startAutoPlay();
          }, { passive: true });
        }
      }
    })();
  </script>

@endpush
</x-layouts.app>

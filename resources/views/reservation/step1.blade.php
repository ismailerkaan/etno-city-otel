<x-layouts.reservation>
<main class="w-full pt-32 bg-background min-h-screen"><div class="flex flex-col w-full">
{{-- Sayfa Başlığı & Filtreler --}}
<section class="w-full bg-surface border-b border-surface-container-high">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-12 py-5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            {{-- Sol: Başlık + Tarih/Kişi --}}
            <div>
                <h1 class="font-headline-md text-headline-md text-on-surface">Müsait Odalar & Villalar</h1>
                <div class="flex flex-wrap items-center gap-3 mt-1.5">
                    <span class="inline-flex items-center gap-1.5 font-label-sm text-label-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-secondary text-[15px]">calendar_today</span>
                        12 Eki – 17 Eki, 2025 · <strong class="text-on-surface font-semibold">5 Gece</strong>
                    </span>
                    <span class="w-1 h-1 rounded-full bg-outline-variant inline-block"></span>
                    <span class="inline-flex items-center gap-1.5 font-label-sm text-label-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-secondary text-[15px]">group</span>
                        <strong class="text-on-surface font-semibold">2 Yetişkin</strong>
                    </span>
                </div>
            </div>
            {{-- Sağ: Filtreler + Sıralama --}}
            <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center gap-2 overflow-x-auto" id="filter-container">
                    <button class="filter-pill px-4 py-1.5 bg-primary text-on-primary font-label-sm text-label-sm uppercase tracking-[0.18em] whitespace-nowrap" data-filter="all">Tümü</button>
                    <button class="filter-pill px-4 py-1.5 bg-surface-container text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm uppercase tracking-[0.18em] whitespace-nowrap transition-colors" data-filter="sea-view">Deniz Manzarası</button>
                    <button class="filter-pill px-4 py-1.5 bg-surface-container text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm uppercase tracking-[0.18em] whitespace-nowrap transition-colors" data-filter="pool">Özel Havuz</button>
                    <button class="filter-pill px-4 py-1.5 bg-surface-container text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm uppercase tracking-[0.18em] whitespace-nowrap transition-colors" data-filter="villa">Villa</button>
                </div>
                <div class="relative flex-shrink-0">
                    <select class="appearance-none bg-surface-container pl-3 pr-8 py-1.5 text-on-surface font-label-sm text-label-sm uppercase tracking-[0.16em] focus:outline-none cursor-pointer" id="sort-select">
                        <option value="recommended">Önerilen</option>
                        <option value="price-asc">Fiyat ↑</option>
                        <option value="price-desc">Fiyat ↓</option>
                        <option value="size-desc">En Büyük</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[16px]">expand_more</span>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Sanctuary Feed / Editorial List -->
<section class="max-w-[1440px] mx-auto px-6 lg:px-12 py-8 space-y-16 w-full" id="sanctuaries-list">
<!-- Card 1: Deluxe Sea View Room -->
<article class="sanctuary-card bg-surface-container-lowest shadow-md transition-all duration-500 overflow-hidden group" data-category="sea-view" data-price="5750" data-size="35">
<div class="grid grid-cols-1 lg:grid-cols-12">
<!-- Visual Gallery Side -->
<div class="lg:col-span-7 relative h-[360px] lg:h-auto min-h-[380px] overflow-hidden bg-surface-container">
<img class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" data-alt="An ethereal minimalist luxury hotel bedroom with floor-to-ceiling glass doors opening onto an azure Aegean ocean panorama. Warm travertine floors, crisp linen bedding in cream tones, soft morning sunlight casting linear architectural shadows. Hand-carved walnut bedside tables, ceramic vessels with olive branches, serene warm sand color palette." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDkjr4kU0hQ3xtGRxiz5oAve396ugGEU-axXb42GBsuDAXkCOYXs7YTQbub_43TO5o583e7LLf3v5-rSdLTiiLHxUBYyapRC43E1k2ZsfFI-wSMYDkK2pWQ0FGnymsTfdV8_KlfnjOqzwfiyCtDzo509Z-9bhra0CiCD-5o5_zxjw4MZ_WIkJGLb5_R1-J5uVwipajBKu2jyLHmtesnV1aRPVAOYlfuTrhrcKBq0ikcbUKb6MlmT_58oQ">
<div class="absolute inset-0 bg-gradient-to-t from-primary/60 via-transparent to-transparent lg:hidden"></div>
<!-- Badges Overlay -->
<div class="absolute top-5 left-5 flex flex-wrap gap-2 z-10">
<span class="px-3 py-1 bg-surface/90 backdrop-blur-sm text-on-surface font-label-sm text-label-sm uppercase tracking-[0.2em] shadow-sm">
              Ufuk Katı
            </span>
<span class="px-3 py-1 bg-secondary-fixed text-on-secondary-fixed font-label-sm text-label-sm uppercase tracking-[0.2em] shadow-sm font-semibold">
              Yalnızca 2 Oda Kaldı
            </span>
</div>
<div class="absolute bottom-5 left-5 flex items-center gap-2 bg-surface/85 backdrop-blur-sm px-3 py-1.5 shadow-sm text-on-surface">
<span class="material-symbols-outlined text-[16px] text-secondary">photo_camera</span>
<span class="font-label-sm text-label-sm uppercase tracking-[0.16em]">Görsel Arşiv (12)</span>
</div>
</div>
<!-- Narrative & Reservation Side -->
<div class="lg:col-span-5 p-8 lg:p-10 flex flex-col justify-between bg-surface-container-lowest">
<div class="space-y-6">
<div class="flex items-center justify-between">
<span class="font-label-sm text-label-sm uppercase tracking-[0.24em] text-secondary">Cape Ufuk Konaklama</span>
<div class="flex items-center gap-1.5 text-secondary">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
</div>
<div>
<h2 class="font-headline-md text-headline-md text-on-surface font-normal">Deluxe Deniz Manzaralı Oda</h2>
<div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-on-surface-variant font-body-sm text-body-sm">
<span class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-[18px] text-secondary">square_foot</span>
                  35 m² İç Alan
                </span>
<span>·</span>
<span class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-[18px] text-secondary">group</span>
                  2 Misafir
                </span>
<span>·</span>
<span class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-[18px] text-secondary">bed</span>
                  Özel King Yatak
                </span>
</div>
</div>
<!-- Perks Strip -->
<div class="space-y-2.5 pt-2">
<div class="flex items-center gap-2.5 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-secondary text-[18px]">check_circle</span>
<span>Her gün The Pergola'da zanaatkâr organik kahvaltı dahil</span>
</div>
<div class="flex items-center gap-2.5 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-secondary text-[18px]">check_circle</span>
<span>5 Ekim 2025'e kadar ücretsiz iptal imkânı</span>
</div>
</div>
<!-- Signature Amenities Grid -->
<div class="grid grid-cols-2 gap-3 pt-4 bg-surface-container-low p-4">
<div class="flex items-center gap-2 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-[18px] text-secondary">deck</span>
<span>Ege Denizi Terası</span>
</div>
<div class="flex items-center gap-2 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-[18px] text-secondary">bathtub</span>
<span>Traverten Oturma Banyosu</span>
</div>
<div class="flex items-center gap-2 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-[18px] text-secondary">spa</span>
<span>Diptyque Botanikler</span>
</div>
<div class="flex items-center gap-2 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-[18px] text-secondary">wifi</span>
<span>Starlink Bağlantısı</span>
</div>
</div>
</div>
<!-- Commercial & CTA -->
<div class="pt-8 mt-8 space-y-4">
<div class="flex items-baseline justify-between">
<div>
<span class="font-headline-sm text-headline-sm text-on-surface font-medium">₺5,750</span>
<span class="font-body-sm text-body-sm text-on-surface-variant font-light"> / gece</span>
</div>
<div class="text-right">
<span class="font-label-sm text-label-sm uppercase tracking-[0.16em] text-secondary font-semibold">Toplam ₺28,750</span>
<p class="font-body-sm text-[11px] text-on-surface-variant">5 gece, vergiler ve ücretler dahil</p>
</div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
<a class="py-3 px-4 text-center bg-surface-container hover:bg-surface-container-high transition-colors font-label-md text-label-md uppercase tracking-[0.2em] text-on-surface" href="{{ route('reservation.step2') }}">
                Detayları İncele
              </a>
<a class="py-3 px-4 text-center bg-primary hover:bg-secondary transition-colors duration-300 font-label-md text-label-md uppercase tracking-[0.2em] text-on-primary" href="{{ route('reservation.step2') }}">
                Odayı Seç
              </a>
</div>
</div>
</div>
</div>
</article>
<!-- Card 2: Cliffside Infinity Suite -->
<article class="sanctuary-card bg-surface-container-lowest shadow-md transition-all duration-500 overflow-hidden group" data-category="cliffside pool" data-price="9200" data-size="65">
<div class="grid grid-cols-1 lg:grid-cols-12">
<!-- Visual Gallery Side -->
<div class="lg:col-span-7 relative h-[360px] lg:h-auto min-h-[380px] overflow-hidden bg-surface-container">
<img class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" data-alt="An extraordinary Aegean clifftop luxury suite overlooking the Aegean sea at golden hour. A heated black volcanic stone plunge pool with infinity edge blends into the ocean horizon. Minimalist sun loungers, pergolas with woven reed shade, whitewashed stone architectural walls, olive trees in terracotta planters, ultra-serene atmosphere." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBLU9LwFXn8mpgmHuFZoiVGv9GXSXlwTBeSdZYBswFniutTwki5e46PaIVJGUj5mXYYokSmmKSdj0QYEHyVUHOP34OBHMfSPRGHOmX0eZ-M5pONn97Z7aMXB0_WkIRk02372GkxCcQ5ymkC5-xfXntyRl1h6gMpNQxzAGkLE-nEbx9SKvAIOoE3iGSpq1-InNk2_cfNqtkIhZIBg6Dm3eUv7iX1PTH4NLHAia60v9kAArrfIGtXW8l9BQ">
<div class="absolute inset-0 bg-gradient-to-t from-primary/60 via-transparent to-transparent lg:hidden"></div>
<!-- Badges Overlay -->
<div class="absolute top-5 left-5 flex flex-wrap gap-2 z-10">
<span class="px-3 py-1 bg-surface/90 backdrop-blur-sm text-on-surface font-label-sm text-label-sm uppercase tracking-[0.2em] shadow-sm">
              İmza Oda
            </span>
<span class="px-3 py-1 bg-secondary-fixed text-on-secondary-fixed font-label-sm text-label-sm uppercase tracking-[0.2em] shadow-sm font-semibold">
              Hızlı Doluyor · 1 Kaldı
            </span>
</div>
<div class="absolute bottom-5 left-5 flex items-center gap-2 bg-surface/85 backdrop-blur-sm px-3 py-1.5 shadow-sm text-on-surface">
<span class="material-symbols-outlined text-[16px] text-secondary">pool</span>
<span class="font-label-sm text-label-sm uppercase tracking-[0.16em]">Isıtmalı Özel Derin Havuz</span>
</div>
</div>
<!-- Narrative & Reservation Side -->
<div class="lg:col-span-5 p-8 lg:p-10 flex flex-col justify-between bg-surface-container-lowest">
<div class="space-y-6">
<div class="flex items-center justify-between">
<span class="font-label-sm text-label-sm uppercase tracking-[0.24em] text-secondary">Uçurum Köşk Bölümü</span>
<span class="font-label-sm text-label-sm uppercase tracking-[0.16em] text-on-surface-variant font-semibold">Misafir Favorisi</span>
</div>
<div>
<h2 class="font-headline-md text-headline-md text-on-surface font-normal">Uçurum Kenarı Sonsuzluk Süiti</h2>
<div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-on-surface-variant font-body-sm text-body-sm">
<span class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-[18px] text-secondary">square_foot</span>
                  65 m² İç Alan + 40 m² Teras
                </span>
<span>·</span>
<span class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-[18px] text-secondary">group</span>
                  3 Misafir
                </span>
<span>·</span>
<span class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-[18px] text-secondary">wb_twilight</span>
                  Doğrudan Gün Batımı Manzarası
                </span>
</div>
</div>
<!-- Perks Strip -->
<div class="space-y-2.5 pt-2">
<div class="flex items-center gap-2.5 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-secondary text-[18px]">sailing</span>
<span>Meze eşliğinde ücretsiz özel gün batımı gulet turu</span>
</div>
<div class="flex items-center gap-2.5 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-secondary text-[18px]">room_service</span>
<span>Özel hizmetinizde sürekli yarımada butler'ı</span>
</div>
<div class="flex items-center gap-2.5 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-secondary text-[18px]">check_circle</span>
<span>Varıştan 7 gün öncesine kadar ücretsiz iptal imkânı</span>
</div>
</div>
<!-- Signature Amenities Grid -->
<div class="grid grid-cols-2 gap-3 pt-4 bg-surface-container-low p-4">
<div class="flex items-center gap-2 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-[18px] text-secondary">pool</span>
<span>Isıtmalı Derin Havuz</span>
</div>
<div class="flex items-center gap-2 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-[18px] text-secondary">outdoor_grill</span>
<span>Açık Hava Yemek Terası</span>
</div>
<div class="flex items-center gap-2 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-[18px] text-secondary">local_bar</span>
<span>Özel Bodrum Cin Bar'ı</span>
</div>
<div class="flex items-center gap-2 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-[18px] text-secondary">shower</span>
<span>Monolit Yağmur Duşu</span>
</div>
</div>
</div>
<!-- Commercial & CTA -->
<div class="pt-8 mt-8 space-y-4">
<div class="flex items-baseline justify-between">
<div>
<span class="font-headline-sm text-headline-sm text-on-surface font-medium">₺9,200</span>
<span class="font-body-sm text-body-sm text-on-surface-variant font-light"> / gece</span>
</div>
<div class="text-right">
<span class="font-label-sm text-label-sm uppercase tracking-[0.16em] text-secondary font-semibold">Toplam ₺46,000</span>
<p class="font-body-sm text-[11px] text-on-surface-variant">5 gece, vergiler ve ücretler dahil</p>
</div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
<a class="py-3 px-4 text-center bg-surface-container hover:bg-surface-container-high transition-colors font-label-md text-label-md uppercase tracking-[0.2em] text-on-surface" href="{{ route('reservation.step2') }}">
                Detayları İncele
              </a>
<a class="py-3 px-4 text-center bg-primary hover:bg-secondary transition-colors duration-300 font-label-md text-label-md uppercase tracking-[0.2em] text-on-primary" href="{{ route('reservation.step2') }}">
                Odayı Seç
              </a>
</div>
</div>
</div>
</div>
</article>
<!-- Card 3: The Olive Grove Villa -->
<article class="sanctuary-card bg-surface-container-lowest shadow-md transition-all duration-500 overflow-hidden group" data-category="villa pool" data-price="18500" data-size="140">
<div class="grid grid-cols-1 lg:grid-cols-12">
<!-- Visual Gallery Side -->
<div class="lg:col-span-7 relative h-[360px] lg:h-auto min-h-[380px] overflow-hidden bg-surface-container">
<img class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" data-alt="Expansive architectural private estate sanctuary nestled within ancient Aegean olive groves. Natural rough stone walls, 12-meter private stone pool with calm water reflections, shaded teak daybeds, open-air living pavilion with sunken lounge seating. Distant sea glimmer, serene dusk lighting with warm lanterns, ultra-luxury retreat aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBf2621qG3ZjxtM6JbFP2zKWssMkcYkilOJGspL7_L8uagbKRZSnm9IOZIwCK7QoZ8t7RB0SboRtrnkPo0639mcZg76lf2-4gcB-8x25nl6xFNfzrojMuDeuCUgsgJ4qr9DnY9GHpYhu-tYaFR4jQundJ1CymUH6PKbU58YY7TUCGt_NHpfXrvdn4Ltbb8Zqf4qWyuWd3SpFe0vBzUI2hC-VlmEdte5OoI3kr1lW6w1W2uFQnyhW2fv1Q">
<div class="absolute inset-0 bg-gradient-to-t from-primary/60 via-transparent to-transparent lg:hidden"></div>
<!-- Badges Overlay -->
<div class="absolute top-5 left-5 flex flex-wrap gap-2 z-10">
<span class="px-3 py-1 bg-surface/90 backdrop-blur-sm text-on-surface font-label-sm text-label-sm uppercase tracking-[0.2em] shadow-sm">
              Özel Mülk
            </span>
<span class="px-3 py-1 bg-primary text-on-primary font-label-sm text-label-sm uppercase tracking-[0.2em] shadow-sm font-semibold">
              Helipad Transferi Dahil
            </span>
</div>
<div class="absolute bottom-5 left-5 flex items-center gap-2 bg-surface/85 backdrop-blur-sm px-3 py-1.5 shadow-sm text-on-surface">
<span class="material-symbols-outlined text-[16px] text-secondary">cottage</span>
<span class="font-label-sm text-label-sm uppercase tracking-[0.16em]">Gizli Kompleks · 1.200 m² Alan</span>
</div>
</div>
<!-- Narrative & Reservation Side -->
<div class="lg:col-span-5 p-8 lg:p-10 flex flex-col justify-between bg-surface-container-lowest">
<div class="space-y-6">
<div class="flex items-center justify-between">
<span class="font-label-sm text-label-sm uppercase tracking-[0.24em] text-secondary">Kraliyet Bölgesi</span>
<span class="font-label-sm text-label-sm uppercase tracking-[0.16em] text-secondary font-semibold">Prestij Kategorisi</span>
</div>
<div>
<h2 class="font-headline-md text-headline-md text-on-surface font-normal">Zeytin Korusu Villası</h2>
<div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-on-surface-variant font-body-sm text-body-sm">
<span class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-[18px] text-secondary">square_foot</span>
                  140 m² Villa
                </span>
<span>·</span>
<span class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-[18px] text-secondary">group</span>
                  4 Misafire Kadar
                </span>
<span>·</span>
<span class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-[18px] text-secondary">hotel</span>
                  2 Ana Süit
                </span>
</div>
</div>
<!-- Perks Strip -->
<div class="space-y-2.5 pt-2">
<div class="flex items-center gap-2.5 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-secondary text-[18px]">wine_bar</span>
<span>Varışta Dom Pérignon Vintage Karşılama İkramı</span>
</div>
<div class="flex items-center gap-2.5 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-secondary text-[18px]">restaurant</span>
<span>Özel villa mutfak deneyimleri için sürekli yerleşik şef</span>
</div>
<div class="flex items-center gap-2.5 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-secondary text-[18px]">flight_takeoff</span>
<span>Ücretsiz Milas-Bodrum VIP Helikopter veya Yat Transferi</span>
</div>
</div>
<!-- Signature Amenities Grid -->
<div class="grid grid-cols-2 gap-3 pt-4 bg-surface-container-low p-4">
<div class="flex items-center gap-2 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-[18px] text-secondary">pool</span>
<span>12m Doğal Taş Havuz</span>
</div>
<div class="flex items-center gap-2 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-[18px] text-secondary">nature_people</span>
<span>Özel Yüzyıllık Zeytin Korusu</span>
</div>
<div class="flex items-center gap-2 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-[18px] text-secondary">fireplace</span>
<span>Açık Hava Ocağı &amp; Majlis</span>
</div>
<div class="flex items-center gap-2 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-[18px] text-secondary">local_laundry_service</span>
<span>Tam Ücretsiz Vale Hizmeti</span>
</div>
</div>
</div>
<!-- Commercial & CTA -->
<div class="pt-8 mt-8 space-y-4">
<div class="flex items-baseline justify-between">
<div>
<span class="font-headline-sm text-headline-sm text-on-surface font-medium">₺18,500</span>
<span class="font-body-sm text-body-sm text-on-surface-variant font-light"> / gece</span>
</div>
<div class="text-right">
<span class="font-label-sm text-label-sm uppercase tracking-[0.16em] text-secondary font-semibold">Toplam ₺92,500</span>
<p class="font-body-sm text-[11px] text-on-surface-variant">5 gece, tüm seçilmiş dahiliyatlar</p>
</div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
<a class="py-3 px-4 text-center bg-surface-container hover:bg-surface-container-high transition-colors font-label-md text-label-md uppercase tracking-[0.2em] text-on-surface" href="{{ route('reservation.step2') }}">
                Detayları İncele
              </a>
<a class="py-3 px-4 text-center bg-primary hover:bg-secondary transition-colors duration-300 font-label-md text-label-md uppercase tracking-[0.2em] text-on-primary" href="{{ route('reservation.step2') }}">
                Odayı Seç
              </a>
</div>
</div>
</div>
</div>
</article>
<!-- Card 4: Aegean Cliff Villa -->
<article class="sanctuary-card bg-surface-container-lowest shadow-md transition-all duration-500 overflow-hidden group" data-category="cliffside sea-view villa" data-price="13400" data-size="95">
<div class="grid grid-cols-1 lg:grid-cols-12">
<!-- Visual Gallery Side -->
<div class="lg:col-span-7 relative h-[360px] lg:h-auto min-h-[380px] overflow-hidden bg-surface-container">
<img class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" data-alt="Architectural modern cantilevered stone cliff villa suspended over crashing Aegean waves. Panoramic glass walls, sunken infinity whirlpool bath, timber wood deck, modern brass reading lamps, earthy textures, warm sand minimalist aesthetics during late afternoon golden sunlight." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBpSNttRONJUgJo9NJvwkoHnQ4CRXRlpFk_Xtv_I_rdvK-2yddYoqCYlNZf8NCwZUGn8l8bynAjj51LvnZTOvF65kRkTJW4LDTqgMMRTkUuieInPi9vY0J0nRLfByljeTp8HKHXBNp_BTaW1nXSliU3LQeD9VwwZyu1JpalhNm8LUPfmKFk7wEcYouvDYNJr6HyN12IwCtBphtupvAR2yHhd-stjZDrkbb6NwTgcyVbbyNTpyHX9crw8g">
<div class="absolute inset-0 bg-gradient-to-t from-primary/60 via-transparent to-transparent lg:hidden"></div>
<!-- Badges Overlay -->
<div class="absolute top-5 left-5 flex flex-wrap gap-2 z-10">
<span class="px-3 py-1 bg-surface/90 backdrop-blur-sm text-on-surface font-label-sm text-label-sm uppercase tracking-[0.2em] shadow-sm">
              Yüksek Konumlu Süit
            </span>
<span class="px-3 py-1 bg-surface-container-high text-on-surface font-label-sm text-label-sm uppercase tracking-[0.2em] shadow-sm">
              Mimari Özellik
            </span>
</div>
<div class="absolute bottom-5 left-5 flex items-center gap-2 bg-surface/85 backdrop-blur-sm px-3 py-1.5 shadow-sm text-on-surface">
<span class="material-symbols-outlined text-[16px] text-secondary">hot_tub</span>
<span class="font-label-sm text-label-sm uppercase tracking-[0.16em]">Sonsuzluk Kenarlı Isıtmalı Jakuzi</span>
</div>
</div>
<!-- Narrative & Reservation Side -->
<div class="lg:col-span-5 p-8 lg:p-10 flex flex-col justify-between bg-surface-container-lowest">
<div class="space-y-6">
<div class="flex items-center justify-between">
<span class="font-label-sm text-label-sm uppercase tracking-[0.24em] text-secondary">Ufuk Konsol</span>
<span class="font-label-sm text-label-sm uppercase tracking-[0.16em] text-on-surface-variant font-semibold">2 Müsait</span>
</div>
<div>
<h2 class="font-headline-md text-headline-md text-on-surface font-normal">Ege Uçurum Villası</h2>
<div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-on-surface-variant font-body-sm text-body-sm">
<span class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-[18px] text-secondary">square_foot</span>
                  95 m² İç Alan
                </span>
<span>·</span>
<span class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-[18px] text-secondary">group</span>
                  3 Misafir
                </span>
<span>·</span>
<span class="flex items-center gap-1.5">
<span class="material-symbols-outlined text-[18px] text-secondary">wb_sunny</span>
                  270° Panoramik Okyanus
                </span>
</div>
</div>
<!-- Perks Strip -->
<div class="space-y-2.5 pt-2">
<div class="flex items-center gap-2.5 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-secondary text-[18px]">liquor</span>
<span>Özel villa Türk &amp; Akdeniz şarap mahzeni</span>
</div>
<div class="flex items-center gap-2.5 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-secondary text-[18px]">self_improvement</span>
<span>Terasta günlük özel ses terapisi &amp; gün doğumu yogası</span>
</div>
</div>
<!-- Signature Amenities Grid -->
<div class="grid grid-cols-2 gap-3 pt-4 bg-surface-container-low p-4">
<div class="flex items-center gap-2 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-[18px] text-secondary">hot_tub</span>
<span>Uçurum Jakuzi</span>
</div>
<div class="flex items-center gap-2 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-[18px] text-secondary">deck</span>
<span>Konsol Şezlonglar</span>
</div>
<div class="flex items-center gap-2 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-[18px] text-secondary">auto_stories</span>
<span>Seçkin Sanat Kütüphanesi</span>
</div>
<div class="flex items-center gap-2 text-on-surface font-body-sm text-body-sm">
<span class="material-symbols-outlined text-[18px] text-secondary">local_cafe</span>
<span>Özel Espresso Laboratuvarı</span>
</div>
</div>
</div>
<!-- Commercial & CTA -->
<div class="pt-8 mt-8 space-y-4">
<div class="flex items-baseline justify-between">
<div>
<span class="font-headline-sm text-headline-sm text-on-surface font-medium">₺13,400</span>
<span class="font-body-sm text-body-sm text-on-surface-variant font-light"> / gece</span>
</div>
<div class="text-right">
<span class="font-label-sm text-label-sm uppercase tracking-[0.16em] text-secondary font-semibold">Toplam ₺67,000</span>
<p class="font-body-sm text-[11px] text-on-surface-variant">5 gece, vergiler ve ücretler dahil</p>
</div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
<a class="py-3 px-4 text-center bg-surface-container hover:bg-surface-container-high transition-colors font-label-md text-label-md uppercase tracking-[0.2em] text-on-surface" href="{{ route('reservation.step2') }}">
                Detayları İncele
              </a>
<a class="py-3 px-4 text-center bg-primary hover:bg-secondary transition-colors duration-300 font-label-md text-label-md uppercase tracking-[0.2em] text-on-primary" href="{{ route('reservation.step2') }}">
                Odayı Seç
              </a>
</div>
</div>
</div>
</div>
</article>
</section>
<!-- Sanctuary Comparison & Subtle Spatial Feature Graphic -->
<section class="max-w-[1440px] mx-auto px-6 lg:px-12 py-16 w-full">
<div class="bg-surface-container-low p-8 lg:p-14 shadow-sm">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
<div class="lg:col-span-6 space-y-4">
<span class="font-label-sm text-label-sm uppercase tracking-[0.25em] text-secondary">Mimari Ana Plan</span>
<h3 class="font-headline-md text-headline-md text-on-surface">Cape Artemis Yarımadası</h3>
<p class="font-body-md text-body-md text-on-surface-variant">
            Her konaklama birimi, mevsimsel Ege ticaret rüzgarlarını ve engelsiz gün batımlarını yakalamak için hassas bir şekilde konumlandırılmıştır. Peyzaj düzenimiz her konaklama pavyonu arasında tam akustik gizlilik sağlar.
          </p>
<div class="pt-2 flex flex-wrap gap-6 text-on-surface">
<div class="flex items-center gap-2.5">
<span class="material-symbols-outlined text-secondary text-[20px]">air</span>
<span class="font-body-sm text-body-sm">Doğal Hava Sirkülasyonu</span>
</div>
<div class="flex items-center gap-2.5">
<span class="material-symbols-outlined text-secondary text-[20px]">volume_off</span>
<span class="font-body-sm text-body-sm">Akustik Yalıtım</span>
</div>
<div class="flex items-center gap-2.5">
<span class="material-symbols-outlined text-secondary text-[20px]">landscape</span>
<span class="font-body-sm text-body-sm">Özel Kıyı Erişimi</span>
</div>
</div>
</div>
<!-- Inline Architectural Compass / Diagram Element -->
<div class="lg:col-span-6 flex justify-center">
<div class="relative w-full max-w-md aspect-square bg-surface flex items-center justify-center p-8 shadow-inner">
<svg class="w-full h-full text-secondary/30" fill="none" stroke="currentColor" viewbox="0 0 200 200">
<!-- Concentric subtle rings -->
<circle cx="100" cy="100" r="90" stroke-dasharray="2 3" stroke-width="0.75"></circle>
<circle cx="100" cy="100" r="65" stroke-width="0.75"></circle>
<circle cx="100" cy="100" r="40" stroke-dasharray="4 2" stroke-width="0.75"></circle>
<!-- Subtle Axis lines -->
<line stroke-width="0.75" x1="100" x2="100" y1="5" y2="195"></line>
<line stroke-width="0.75" x1="5" x2="195" y1="100" y2="100"></line>
<!-- Directional markers -->
<text fill="#735a3c" font-family="Manrope" font-size="7" font-weight="600" letter-spacing="2" text-anchor="middle" x="100" y="20">EGE UFUK HATTI (KUZEY)</text>
<text fill="#735a3c" font-family="Manrope" font-size="7" font-weight="600" letter-spacing="2" text-anchor="middle" x="100" y="185">ZEYTİN KORULARI (GÜNEY)</text>
<text fill="#735a3c" font-family="Manrope" font-size="7" font-weight="600" letter-spacing="2" text-anchor="middle" x="180" y="102">GÜN DOĞUMU</text>
<text fill="#735a3c" font-family="Manrope" font-size="7" font-weight="600" letter-spacing="2" text-anchor="middle" x="22" y="102">GÜN BATIMI</text>
<!-- Node markers -->
<circle class="animate-pulse" cx="70" cy="65" fill="#735a3c" r="4"></circle>
<circle cx="140" cy="80" fill="#1c1c19" r="3"></circle>
<circle cx="60" cy="130" fill="#1c1c19" r="3.5"></circle>
<circle cx="125" cy="140" fill="#1c1c19" r="3"></circle>
</svg>
<div class="absolute bottom-4 left-4 bg-surface/90 px-3 py-1 shadow-sm">
<span class="font-label-sm text-[10px] uppercase tracking-[0.2em] text-on-surface">Cape Yüksekliği: +64m</span>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Trust & Sanctuary Guarantees Strip -->
<section class="w-full bg-surface-container py-16">
<div class="max-w-[1440px] mx-auto px-6 lg:px-12">
<div class="text-center max-w-xl mx-auto mb-12">
<span class="font-label-sm text-label-sm uppercase tracking-[0.26em] text-secondary">AURA Taahhüdü</span>
<h4 class="font-headline-sm text-headline-sm text-on-surface mt-1">Doğrudan Rezervasyon İçerikleri</h4>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
<!-- Feature 1 -->
<div class="flex flex-col items-center text-center p-6 bg-surface-container-low shadow-sm">
<span class="material-symbols-outlined text-secondary text-[32px] mb-4">format_image_left</span>
<h5 class="font-title-md text-title-md text-on-surface font-semibold mb-2">Doğrudan Fiyat Güvencesi</h5>
<p class="font-body-sm text-body-sm text-on-surface-variant">Varışta doğrudan yarımada ayrıcalığı ile dünya genelinde garantili en düşük ücret.</p>
</div>
<!-- Feature 2 -->
<div class="flex flex-col items-center text-center p-6 bg-surface-container-low shadow-sm">
<span class="material-symbols-outlined text-secondary text-[32px] mb-4">price_check</span>
<h5 class="font-title-md text-title-md text-on-surface font-semibold mb-2">Şeffaf Hesap</h5>
<p class="font-body-sm text-body-sm text-on-surface-variant">Gizli tesis ücretleri, varış yeri ek ücretleri veya ayrılışta açıklanmamış enerji ekleri bulunmamaktadır.</p>
</div>
<!-- Feature 3 -->
<div class="flex flex-col items-center text-center p-6 bg-surface-container-low shadow-sm">
<span class="material-symbols-outlined text-secondary text-[32px] mb-4">support_agent</span>
<h5 class="font-title-md text-title-md text-on-surface font-semibold mb-2">7/24 Yarımada Ev Sahibi</h5>
<p class="font-body-sm text-body-sm text-on-surface-variant">Konaklamanızdan önce, süresince ve sonrasında Bodrum concierge masasıyla kesintisiz doğrudan iletişim.</p>
</div>
<!-- Feature 4 -->
<div class="flex flex-col items-center text-center p-6 bg-surface-container-low shadow-sm">
<span class="material-symbols-outlined text-secondary text-[32px] mb-4">event_repeat</span>
<h5 class="font-title-md text-title-md text-on-surface font-semibold mb-2">Esnek Depozito Politikası</h5>
<p class="font-body-sm text-body-sm text-on-surface-variant">Mütevazı bir ön ödemeli rezervasyon; bakiye tesise varışta ödenir.</p>
</div>
</div>
</div>
</section>
<!-- Interactive Search / Filter Client Logic -->

</div></main>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
      const filterPills = document.querySelectorAll('.filter-pill');
      const sanctuaryCards = document.querySelectorAll('.sanctuary-card');
      const sortSelect = document.getElementById('sort-select');
      const listContainer = document.getElementById('sanctuaries-list');

      // Filter handling
      filterPills.forEach(pill => {
        pill.addEventListener('click', () => {
          filterPills.forEach(p => {
            p.classList.remove('bg-primary', 'text-on-primary');
            p.classList.add('bg-surface-container', 'text-on-surface-variant');
          });

          pill.classList.remove('bg-surface-container', 'text-on-surface-variant');
          pill.classList.add('bg-primary', 'text-on-primary');

          const filter = pill.getAttribute('data-filter');

          sanctuaryCards.forEach(card => {
            if (filter === 'all') {
              card.style.display = 'block';
            } else {
              const categories = card.getAttribute('data-category') || '';
              if (categories.includes(filter)) {
                card.style.display = 'block';
              } else {
                card.style.display = 'none';
              }
            }
          });
        });
      });

      // Sorting handling
      sortSelect.addEventListener('change', (e) => {
        const value = e.target.value;
        const cardsArray = Array.from(sanctuaryCards);

        cardsArray.sort((a, b) => {
          const priceA = parseInt(a.getAttribute('data-price') || '0', 10);
          const priceB = parseInt(b.getAttribute('data-price') || '0', 10);
          const sizeA = parseInt(a.getAttribute('data-size') || '0', 10);
          const sizeB = parseInt(b.getAttribute('data-size') || '0', 10);

          if (value === 'price-asc') return priceA - priceB;
          if (value === 'price-desc') return priceB - priceA;
          if (value === 'size-desc') return sizeB - sizeA;
          return 0; // default order
        });

        cardsArray.forEach(card => listContainer.appendChild(card));
      });
    });
  </script>

@endpush
</x-layouts.reservation>
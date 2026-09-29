<x-layouts.app :site-setting="$siteSetting" :title="(app()->isLocale('en') ? 'Visual Archive & Gallery' : 'Görsel Arşiv & Galeri') . ' | ' . ($siteSetting?->hotel_name ?? 'EtnoCity Otel')">
    <div class="flex flex-col w-full">
        <!-- 1. HERO / BANNER -->
        <section class="relative w-full pt-32 pb-16 bg-surface-container-low border-b border-surface-container">
            <div class="max-w-[1440px] mx-auto px-6 lg:px-12 space-y-4">
                {{-- Breadcrumbs --}}
                <div class="flex items-center gap-2 font-label-sm text-[11px] uppercase tracking-[0.2em] text-secondary">
                    <a href="{{ route('home') }}" class="hover:text-primary transition-colors">{{ app()->isLocale('en') ? 'Home' : 'Anasayfa' }}</a>
                    <span class="text-outline/40">/</span>
                    <span class="text-on-surface">{{ app()->isLocale('en') ? 'Gallery' : 'Galeri' }}</span>
                </div>

                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pt-2">
                    <div class="space-y-3 max-w-3xl">
                        <span class="font-label-md text-label-md uppercase text-secondary tracking-[0.28em] block">
                            {{ $siteSetting?->translated('gallery_eyebrow') ?: (app()->isLocale('en') ? 'Visual Archive' : 'Görsel Arşiv') }}
                        </span>
                        <h1 class="font-display-lg text-headline-lg lg:text-display-md text-on-surface font-normal">
                            {{ $siteSetting?->translated('gallery_title') ?: (app()->isLocale('en') ? 'Atmospheric Moments' : 'Atmosferik Anlar') }}
                        </h1>
                        <p class="font-body-md text-body-md text-on-surface-variant font-light leading-relaxed">
                            {{ $siteSetting?->translated('gallery_description') ?: (app()->isLocale('en') 
                                ? 'Immerse in the serene Aegean sanctuary. Explore our architectural suites, secluded coves, tranquil wellness spaces, and Mediterranean culinary artistry.' 
                                : 'Ege\'nin dingin sığınağını keşfedin. Mimari süitlerimiz, gizli koylarımız, arındırıcı sağlık alanlarımız ve Akdeniz lezzet sanatının görsel yolculuğu.') }}
                        </p>
                    </div>

                    <div class="text-right flex-shrink-0">
                        <span class="font-headline-md text-headline-md text-secondary">{{ $images->count() }}</span>
                        <span class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant block">{{ app()->isLocale('en') ? 'Curated Frames' : 'Seçkin Kare' }}</span>
                    </div>
                </div>

                <!-- Filter Pill Tabs -->
                <div class="pt-8 flex flex-wrap items-center gap-2.5" id="galleryFilters">
                    <button type="button" data-filter="all" class="gallery-pill px-5 py-2.5 bg-primary text-on-primary font-label-sm text-label-sm uppercase tracking-wider transition-all duration-300">
                        {{ app()->isLocale('en') ? 'All Moments' : 'Tümü' }} <span class="opacity-75 text-[11px] ml-1">({{ $images->count() }})</span>
                    </button>
                    @foreach ($categories as $cat)
                        <button type="button" data-filter="{{ $cat->id }}" class="gallery-pill px-5 py-2.5 bg-surface-container text-on-surface hover:bg-surface-container-highest font-label-sm text-label-sm uppercase tracking-wider transition-all duration-300">
                            {{ $cat->translatedName() }} <span class="opacity-60 text-[11px] ml-1">({{ $cat->images_count }})</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- 2. PHOTO GALLERY GRID -->
        <section class="w-full py-16 lg:py-24 bg-surface">
            <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
                <div id="galleryGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($images as $index => $img)
                        <div class="gallery-card group relative overflow-hidden bg-surface-container cursor-pointer transition-all duration-500 hover:shadow-2xl" 
                             data-category="{{ $img->gallery_category_id }}"
                             data-index="{{ $index }}"
                             data-src="{{ $img->imageUrl() }}"
                             data-tag="{{ $img->translatedTag() }}"
                             data-category-name="{{ $img->category?->translatedName() }}">
                            
                            {{-- Image Container --}}
                            <div class="relative aspect-[4/3] w-full overflow-hidden bg-surface-container-high">
                                <img src="{{ $img->imageUrl() }}" 
                                     alt="{{ $img->translatedTag() ?: ($img->category?->translatedName() ?? 'Galeri') }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                                     loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-primary/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                                
                                {{-- Top Category Badge --}}
                                @if ($img->category)
                                    <div class="absolute top-4 left-4 pointer-events-none">
                                        <span class="inline-block px-3 py-1 bg-surface/85 backdrop-blur-md font-label-sm text-[10px] uppercase tracking-[0.2em] text-on-surface shadow-sm">
                                            {{ $img->category->translatedName() }}
                                        </span>
                                    </div>
                                @endif

                                {{-- Zoom Icon overlay on hover --}}
                                <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                                    <div class="w-9 h-9 rounded-full bg-surface/80 backdrop-blur-md flex items-center justify-center text-on-surface shadow-sm">
                                        <span class="material-symbols-outlined text-[18px]">zoom_in</span>
                                    </div>
                                </div>

                                {{-- Bottom Tag Overlay --}}
                                @if ($img->translatedTag())
                                    <div class="absolute bottom-4 left-4 right-4 pointer-events-none">
                                        <div class="inline-block bg-surface/90 backdrop-blur-md px-4 py-2 shadow-sm max-w-full truncate">
                                            <p class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface truncate">
                                                {{ $img->translatedTag() }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center space-y-4">
                            <span class="material-symbols-outlined text-[48px] text-outline/50 block">photo_library</span>
                            <p class="font-body-lg text-on-surface-variant">
                                {{ app()->isLocale('en') ? 'No images uploaded yet.' : 'Henüz görsel yüklenmemiş.' }}
                            </p>
                        </div>
                    @endforelse
                </div>

                {{-- Empty state when a filtered category has no images --}}
                <div id="galleryEmptyState" class="hidden py-24 text-center space-y-3">
                    <span class="material-symbols-outlined text-[44px] text-outline/50 block">filter_alt_off</span>
                    <p class="font-body-lg text-on-surface-variant font-light">
                        {{ app()->isLocale('en') ? 'No images found in this category.' : 'Bu kategoride henüz görsel bulunmuyor.' }}
                    </p>
                </div>
            </div>
        </section>

        <!-- 3. FULLSCREEN LUXURY LIGHTBOX MODAL -->
        <div id="galleryLightbox" class="fixed inset-0 z-[99999] hidden items-center justify-center p-3 sm:p-6 md:p-10 select-none transition-all duration-300 opacity-0" style="background-color: rgba(6, 6, 6, 0.82); backdrop-filter: blur(28px); -webkit-backdrop-filter: blur(28px);" role="dialog" aria-modal="true">
            {{-- Clickable Backdrop to close --}}
            <div id="lightboxBackdrop" class="absolute inset-0 z-10 cursor-pointer"></div>

            {{-- Top Controls Bar --}}
            <div class="absolute top-4 sm:top-6 left-4 sm:left-8 right-4 sm:right-8 z-30 flex items-center justify-between pointer-events-none">
                <div class="pointer-events-auto bg-black/50 backdrop-blur-md px-4 py-2 border border-white/10 shadow-lg text-white font-label-sm text-xs tracking-widest uppercase">
                    <span id="lightboxCounter">1 / 1</span>
                </div>

                <button type="button" id="lightboxClose" class="pointer-events-auto w-12 h-12 rounded-full bg-black/50 hover:bg-white text-white hover:text-black flex items-center justify-center transition-all duration-300 border border-white/20 hover:scale-105 shadow-2xl backdrop-blur-md cursor-pointer" aria-label="Kapat">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Previous Button --}}
            <button type="button" id="lightboxPrev" class="absolute left-3 sm:left-6 md:left-10 top-1/2 -translate-y-1/2 z-30 w-12 sm:w-16 h-12 sm:h-16 rounded-full bg-black/60 hover:bg-white text-white hover:text-black flex items-center justify-center transition-all duration-300 border border-white/25 hover:border-white hover:scale-110 shadow-2xl backdrop-blur-md cursor-pointer group" aria-label="Önceki Görsel">
                <svg class="w-7 sm:w-8 h-7 sm:h-8 -translate-x-0.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </button>

            {{-- Next Button --}}
            <button type="button" id="lightboxNext" class="absolute right-3 sm:right-6 md:right-10 top-1/2 -translate-y-1/2 z-30 w-12 sm:w-16 h-12 sm:h-16 rounded-full bg-black/60 hover:bg-white text-white hover:text-black flex items-center justify-center transition-all duration-300 border border-white/25 hover:border-white hover:scale-110 shadow-2xl backdrop-blur-md cursor-pointer group" aria-label="Sonraki Görsel">
                <svg class="w-7 sm:w-8 h-7 sm:h-8 translate-x-0.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
            </button>

            {{-- Main Image Area --}}
            <div class="relative z-20 w-full max-w-6xl h-full flex flex-col items-center justify-center pointer-events-none px-2 sm:px-16">
                <div class="relative max-h-[78vh] sm:max-h-[82vh] w-full flex items-center justify-center pointer-events-auto">
                    <img id="lightboxImg" src="" alt="" class="max-h-[76vh] sm:max-h-[80vh] w-auto max-w-[92vw] sm:max-w-[85vw] object-contain shadow-[0_25px_60px_-15px_rgba(0,0,0,0.85)] rounded-sm transition-all duration-300">
                </div>
                
                {{-- Caption / Tag --}}
                <div class="mt-4 text-center space-y-1 max-w-3xl px-4 pointer-events-auto">
                    <span id="lightboxCategory" class="font-label-sm text-[11px] uppercase tracking-[0.28em] text-secondary-fixed block"></span>
                    <h3 id="lightboxTag" class="font-headline-sm text-base sm:text-headline-sm text-white font-light tracking-wide"></h3>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Category Filtering
            const filterBtns = document.querySelectorAll('.gallery-pill');
            const cards = Array.from(document.querySelectorAll('.gallery-card'));
            const emptyState = document.getElementById('galleryEmptyState');

            function applyFilter(filterVal) {
                let visibleCount = 0;
                cards.forEach(card => {
                    const cat = card.getAttribute('data-category');
                    if (filterVal === 'all' || cat === filterVal) {
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                if (emptyState) {
                    if (visibleCount === 0 && cards.length > 0) {
                        emptyState.classList.remove('hidden');
                    } else {
                        emptyState.classList.add('hidden');
                    }
                }
            }

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    filterBtns.forEach(b => {
                        b.classList.remove('bg-primary', 'text-on-primary');
                        b.classList.add('bg-surface-container', 'text-on-surface');
                    });
                    this.classList.add('bg-primary', 'text-on-primary');
                    this.classList.remove('bg-surface-container', 'text-on-surface');

                    const filterVal = this.getAttribute('data-filter');
                    applyFilter(filterVal);
                });
            });

            // Lightbox Logic
            const lightbox = document.getElementById('galleryLightbox');
            const lightboxImg = document.getElementById('lightboxImg');
            const lightboxTag = document.getElementById('lightboxTag');
            const lightboxCategory = document.getElementById('lightboxCategory');
            const lightboxCounter = document.getElementById('lightboxCounter');
            const closeBtn = document.getElementById('lightboxClose');
            const backdrop = document.getElementById('lightboxBackdrop');
            const prevBtn = document.getElementById('lightboxPrev');
            const nextBtn = document.getElementById('lightboxNext');

            let currentVisibleCards = [];
            let currentIndex = 0;

            function getVisibleCards() {
                return cards.filter(card => !card.classList.contains('hidden'));
            }

            function updateLightboxContent(animate = true) {
                if (currentVisibleCards.length === 0) return;
                const card = currentVisibleCards[currentIndex];
                const src = card.getAttribute('data-src');
                const tag = card.getAttribute('data-tag') || '';
                const catName = card.getAttribute('data-category-name') || '';

                if (animate) {
                    lightboxImg.style.opacity = '0.3';
                    lightboxImg.style.transform = 'scale(0.98)';
                }

                const imgLoader = new Image();
                imgLoader.onload = function () {
                    lightboxImg.src = src;
                    lightboxTag.textContent = tag;
                    lightboxCategory.textContent = catName;
                    lightboxCounter.textContent = (currentIndex + 1) + ' / ' + currentVisibleCards.length;
                    lightboxImg.style.opacity = '1';
                    lightboxImg.style.transform = 'scale(1)';
                };
                imgLoader.src = src;
            }

            function openLightbox(card) {
                currentVisibleCards = getVisibleCards();
                currentIndex = currentVisibleCards.indexOf(card);
                if (currentIndex === -1) currentIndex = 0;

                updateLightboxContent(false);

                lightbox.classList.remove('hidden');
                lightbox.classList.add('flex');
                setTimeout(() => {
                    lightbox.classList.remove('opacity-0');
                    lightbox.classList.add('opacity-100');
                }, 10);
                document.body.style.overflow = 'hidden';
            }

            function closeLightbox() {
                lightbox.classList.add('opacity-0');
                lightbox.classList.remove('opacity-100');
                setTimeout(() => {
                    lightbox.classList.add('hidden');
                    lightbox.classList.remove('flex');
                    document.body.style.overflow = '';
                }, 250);
            }

            function prevImage() {
                if (currentVisibleCards.length <= 1) return;
                currentIndex = (currentIndex - 1 + currentVisibleCards.length) % currentVisibleCards.length;
                updateLightboxContent(true);
            }

            function nextImage() {
                if (currentVisibleCards.length <= 1) return;
                currentIndex = (currentIndex + 1) % currentVisibleCards.length;
                updateLightboxContent(true);
            }

            cards.forEach(card => {
                card.addEventListener('click', () => openLightbox(card));
            });

            if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
            if (backdrop) backdrop.addEventListener('click', closeLightbox);
            if (prevBtn) prevBtn.addEventListener('click', prevImage);
            if (nextBtn) nextBtn.addEventListener('click', nextImage);

            // Touch gestures for mobile
            let touchStartX = 0;
            let touchEndX = 0;

            lightbox.addEventListener('touchstart', function (e) {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            lightbox.addEventListener('touchend', function (e) {
                touchEndX = e.changedTouches[0].screenX;
                const diff = touchEndX - touchStartX;
                if (Math.abs(diff) > 40) {
                    if (diff < 0) nextImage();
                    else prevImage();
                }
            }, { passive: true });

            document.addEventListener('keydown', function (e) {
                if (!lightbox || lightbox.classList.contains('hidden')) return;
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowLeft') prevImage();
                if (e.key === 'ArrowRight') nextImage();
            });
        });
    </script>
    @endpush
</x-layouts.app>

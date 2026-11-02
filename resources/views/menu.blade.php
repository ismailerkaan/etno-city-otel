<x-layouts.app :site-setting="$siteSetting" :title="__('site.our_menu') . ' | ' . ($siteSetting?->hotel_name ?? 'EtnoCity Otel')">
    <div class="flex flex-col w-full">
        <!-- 1. HERO / BANNER -->
        <section class="relative w-full pt-32 pb-12 bg-surface-container-low border-b border-surface-container">
            <div class="max-w-[1440px] mx-auto px-6 lg:px-12 space-y-4">
                {{-- Breadcrumbs --}}
                <div class="flex items-center gap-2 font-label-sm text-[11px] uppercase tracking-[0.2em] text-secondary">
                    <a href="{{ route('home') }}" class="hover:text-primary transition-colors">{{ app()->isLocale('en') ? 'Home' : 'Anasayfa' }}</a>
                    <span class="text-outline/40">/</span>
                    <span class="text-on-surface">{{ __('site.our_menu') }}</span>
                </div>

                <div class="space-y-3 max-w-3xl pt-2">
                    <span class="font-label-md text-label-md uppercase text-secondary tracking-[0.28em] block">Cafe & Lounge</span>
                    <h1 class="font-display-lg text-headline-lg lg:text-display-md text-on-surface font-normal">{{ __('site.our_menu') }}</h1>
                    <p class="font-body-md text-body-md text-on-surface-variant font-light leading-relaxed">
                        {{ app()->isLocale('en')
                            ? 'Hot and cold drinks, desserts and freshly baked treats served throughout the day.'
                            : 'Gün boyu servis edilen sıcak ve soğuk içecekler, tatlılar ve taze lezzetler.' }}
                    </p>
                </div>

                @if ($categories->count() > 1)
                    <nav class="pt-6 flex flex-wrap items-center gap-2.5">
                        @foreach ($categories as $category)
                            <a href="#menu-{{ $category->id }}" class="px-5 py-2.5 bg-surface-container text-on-surface hover:bg-primary hover:text-on-primary font-label-sm text-label-sm uppercase tracking-wider transition-all duration-300">
                                {{ $category->translatedName() }}
                            </a>
                        @endforeach
                    </nav>
                @endif
            </div>
        </section>

        <!-- 2. MENU LIST -->
        <section class="w-full py-12 lg:py-20 bg-surface">
            <div class="max-w-4xl mx-auto px-6 lg:px-12 space-y-14">
                @forelse ($categories as $category)
                    <div id="menu-{{ $category->id }}" class="scroll-mt-28">
                        <h2 class="font-headline-md text-headline-md text-on-surface border-b border-outline-variant/60 pb-3 mb-2">
                            {{ $category->translatedName() }}
                        </h2>
                        <ul class="divide-y divide-surface-container-high">
                            @foreach ($category->items as $item)
                                <li class="flex items-baseline justify-between gap-6 py-4">
                                    <div class="min-w-0">
                                        <p class="font-body-md text-body-md text-on-surface font-medium">{{ $item->translatedName() }}</p>
                                        @if ($item->translatedDescription())
                                            <p class="font-body-sm text-sm text-on-surface-variant font-light mt-0.5">{{ $item->translatedDescription() }}</p>
                                        @endif
                                    </div>
                                    <span class="flex-shrink-0 font-label-md text-label-md text-secondary font-semibold tracking-wide whitespace-nowrap">{{ $item->formattedPrice() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <p class="text-center text-on-surface-variant py-12">
                        {{ app()->isLocale('en') ? 'Our menu will be available soon.' : 'Menümüz çok yakında burada olacak.' }}
                    </p>
                @endforelse
            </div>
        </section>
    </div>
</x-layouts.app>

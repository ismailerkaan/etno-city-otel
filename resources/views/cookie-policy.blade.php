@php
    $hotelName = $siteSetting?->hotel_name ?? 'EtnoCity Otel';
    $cookies = [
        [config('session.cookie'), __('cookies.session_purpose'), __('cookies.duration_session'), __('cookies.type_necessary')],
        ['XSRF-TOKEN', __('cookies.xsrf_purpose'), __('cookies.duration_session'), __('cookies.type_necessary')],
        ['cookie_consent', __('cookies.consent_purpose'), __('cookies.duration_year'), __('cookies.type_necessary')],
        ['_ga, _ga_*', __('cookies.ga_purpose'), __('cookies.duration_two_years'), __('cookies.type_analytics')],
    ];
@endphp

<x-layouts.app :site-setting="$siteSetting" :title="__('cookies.page_title') . ' | ' . $hotelName">
    <div class="flex flex-col w-full">
        <section class="relative w-full pt-32 pb-12 bg-surface-container-low border-b border-surface-container">
            <div class="max-w-[1440px] mx-auto px-6 lg:px-12 space-y-4">
                <div class="flex items-center gap-2 font-label-sm text-[11px] uppercase tracking-[0.2em] text-secondary">
                    <a href="{{ route('home') }}" class="hover:text-primary transition-colors">{{ app()->isLocale('en') ? 'Home' : 'Anasayfa' }}</a>
                    <span class="text-outline/40">/</span>
                    <span class="text-on-surface">{{ __('cookies.page_title') }}</span>
                </div>
                <h1 class="font-display-lg text-headline-lg lg:text-display-md text-on-surface font-normal pt-2">{{ __('cookies.page_title') }}</h1>
                <p class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant">{{ __('cookies.last_updated') }}: 02.11.2026</p>
            </div>
        </section>

        <section class="w-full py-12 lg:py-20 bg-surface">
            <div class="max-w-3xl mx-auto px-6 lg:px-12 space-y-10 font-body-md text-body-md text-on-surface-variant font-light leading-relaxed">
                <p>{{ __('cookies.page_intro', ['hotel' => $hotelName]) }}</p>

                <div class="space-y-3">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __('cookies.what_title') }}</h2>
                    <p>{{ __('cookies.what_text') }}</p>
                </div>

                <div class="space-y-4">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __('cookies.types_title') }}</h2>
                    <div class="space-y-2">
                        <h3 class="font-medium text-on-surface">{{ __('cookies.necessary_title') }}</h3>
                        <p>{{ __('cookies.necessary_text') }}</p>
                    </div>
                    <div class="space-y-2">
                        <h3 class="font-medium text-on-surface">{{ __('cookies.analytics_title') }}</h3>
                        <p>{{ __('cookies.analytics_text') }}</p>
                    </div>

                    <div class="overflow-x-auto pt-2">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="border-b border-outline-variant text-on-surface">
                                    <th class="py-3 pr-4 font-medium">{{ __('cookies.table_name') }}</th>
                                    <th class="py-3 pr-4 font-medium">{{ __('cookies.table_purpose') }}</th>
                                    <th class="py-3 pr-4 font-medium">{{ __('cookies.table_duration') }}</th>
                                    <th class="py-3 font-medium">{{ __('cookies.table_type') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cookies as [$name, $purpose, $duration, $type])
                                    <tr class="border-b border-surface-container-high">
                                        <td class="py-3 pr-4 font-mono text-xs text-on-surface whitespace-nowrap">{{ $name }}</td>
                                        <td class="py-3 pr-4">{{ $purpose }}</td>
                                        <td class="py-3 pr-4 whitespace-nowrap">{{ $duration }}</td>
                                        <td class="py-3 whitespace-nowrap">{{ $type }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-3">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __('cookies.manage_title') }}</h2>
                    <p>{{ __('cookies.manage_text') }}</p>
                    <button type="button" data-cookie-preferences class="mt-2 px-6 py-3 bg-primary text-on-primary hover:bg-secondary hover:text-on-secondary transition-colors duration-300 font-label-md text-label-md uppercase tracking-[0.2em]">
                        {{ __('cookies.preferences') }}
                    </button>
                </div>

                <div class="space-y-3">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __('cookies.contact_title') }}</h2>
                    <p>
                        {{ __('cookies.contact_text') }}
                        <a href="{{ route('contact') }}" class="text-secondary underline underline-offset-4">{{ __('site.contact') }}</a>
                    </p>
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>

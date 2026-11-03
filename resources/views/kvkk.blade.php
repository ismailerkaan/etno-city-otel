@php
    $company = $siteSetting?->legal_company_name ?: ($siteSetting?->hotel_name ?? 'EtnoCity Otel');
    $sections = [
        ['purposes_title', 'purposes_items'],
        ['legal_title', 'legal_items'],
    ];
@endphp

<x-layouts.app :site-setting="$siteSetting" :title="__('kvkk.page_title') . ' | ' . ($siteSetting?->hotel_name ?? 'EtnoCity Otel')">
    <div class="flex flex-col w-full">
        <section class="relative w-full pt-32 pb-12 bg-surface-container-low border-b border-surface-container">
            <div class="max-w-[1440px] mx-auto px-6 lg:px-12 space-y-4">
                <div class="flex items-center gap-2 font-label-sm text-[11px] uppercase tracking-[0.2em] text-secondary">
                    <a href="{{ route('home') }}" class="hover:text-primary transition-colors">{{ app()->isLocale('en') ? 'Home' : 'Anasayfa' }}</a>
                    <span class="text-outline/40">/</span>
                    <span class="text-on-surface">{{ __('kvkk.page_title') }}</span>
                </div>
                <h1 class="font-display-lg text-headline-lg lg:text-display-md text-on-surface font-normal pt-2">{{ __('kvkk.page_title') }}</h1>
                <p class="font-body-md text-body-md text-on-surface-variant font-light">{{ __('kvkk.subtitle') }}</p>
                <p class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant">{{ __('kvkk.last_updated') }}: 03.11.2026</p>
            </div>
        </section>

        <section class="w-full py-12 lg:py-20 bg-surface">
            <div class="max-w-3xl mx-auto px-6 lg:px-12 space-y-10 font-body-md text-body-md text-on-surface-variant font-light leading-relaxed">
                <p>{{ __('kvkk.intro', ['company' => $company]) }}</p>

                <div class="space-y-3">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __('kvkk.controller_title') }}</h2>
                    <p>{{ __('kvkk.controller_text', ['company' => $company]) }}</p>
                    <dl class="grid grid-cols-1 sm:grid-cols-[140px_1fr] gap-x-6 gap-y-2 bg-surface-container-low p-5 text-sm">
                        <dt class="font-medium text-on-surface">{{ __('kvkk.controller_name') }}</dt><dd>{{ $company }}</dd>
                        @if ($siteSetting?->address)<dt class="font-medium text-on-surface">{{ __('kvkk.controller_address') }}</dt><dd>{{ $siteSetting->address }}</dd>@endif
                        @if ($siteSetting?->email)<dt class="font-medium text-on-surface">{{ __('kvkk.controller_email') }}</dt><dd><a class="text-secondary underline underline-offset-4" href="mailto:{{ $siteSetting->email }}">{{ $siteSetting->email }}</a></dd>@endif
                        @if ($siteSetting?->phone)<dt class="font-medium text-on-surface">{{ __('kvkk.controller_phone') }}</dt><dd>{{ $siteSetting->phone }}</dd>@endif
                    </dl>
                </div>

                <div class="space-y-3">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __('kvkk.data_title') }}</h2>
                    <ul class="list-disc pl-5 space-y-2">@foreach (__('kvkk.data_items') as $item)<li>{{ $item }}</li>@endforeach</ul>
                    <p class="text-sm">{{ __('kvkk.data_note') }}</p>
                </div>

                @foreach ($sections as [$title, $items])
                    <div class="space-y-3">
                        <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __('kvkk.'.$title) }}</h2>
                        <ul class="list-disc pl-5 space-y-2">@foreach (__('kvkk.'.$items) as $item)<li>{{ $item }}</li>@endforeach</ul>
                    </div>
                @endforeach

                <div class="space-y-3">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __('kvkk.transfer_title') }}</h2>
                    <p>{{ __('kvkk.transfer_text') }}</p>
                    <ul class="list-disc pl-5 space-y-2">@foreach (__('kvkk.transfer_items') as $item)<li>{{ $item }}</li>@endforeach</ul>
                </div>

                <div class="space-y-3">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __('kvkk.method_title') }}</h2>
                    <p>{{ __('kvkk.method_text') }} <a class="text-secondary underline underline-offset-4" href="{{ route('cookie-policy') }}">{{ __('cookies.policy_link') }}</a></p>
                </div>

                <div class="space-y-3">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __('kvkk.retention_title') }}</h2>
                    <p>{{ __('kvkk.retention_text') }}</p>
                </div>

                <div class="space-y-3">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __('kvkk.rights_title') }}</h2>
                    <p>{{ __('kvkk.rights_text') }}</p>
                    <ul class="list-disc pl-5 space-y-2">@foreach (__('kvkk.rights_items') as $item)<li>{{ $item }}</li>@endforeach</ul>
                    @if (__('kvkk.rights_suffix'))<p>{{ __('kvkk.rights_suffix') }}</p>@endif
                </div>

                <div class="space-y-3">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __('kvkk.apply_title') }}</h2>
                    <p>{{ __('kvkk.apply_text') }}</p>
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>

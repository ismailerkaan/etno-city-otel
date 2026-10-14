@props(['siteSetting' => null, 'title' => null])

<x-layouts.app :site-setting="$siteSetting" :title="$title ?? ((app()->isLocale('en') ? 'Reservation' : 'Rezervasyon') . ' | ' . ($siteSetting?->hotel_name ?? 'EtnoCity Otel'))">
    {{ $slot }}
</x-layouts.app>
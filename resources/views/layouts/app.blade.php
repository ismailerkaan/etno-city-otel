@props(['siteSetting' => null, 'title' => null])

<x-layouts.app :site-setting="$siteSetting" :title="$title">
    {{ $slot }}
    @push('scripts')
        @stack('scripts')
    @endpush
</x-layouts.app>

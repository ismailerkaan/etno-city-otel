@props(['siteSetting' => null])

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if ($siteSetting?->google_search_console_verification)
        <meta name="google-site-verification" content="{{ $siteSetting->google_search_console_verification }}">
    @endif
    <title>{{ $title ?? ($siteSetting?->hotel_name ?? 'EtnoCity Otel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon-etnocity.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon-etnocity.png') }}">
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @if ($siteSetting?->google_analytics_measurement_id)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $siteSetting->google_analytics_measurement_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $siteSetting->google_analytics_measurement_id }}');
        </script>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background font-body-md text-on-surface antialiased selection:bg-secondary-fixed-dim selection:text-on-secondary-fixed">
    <x-header :site-setting="$siteSetting" />
    {{ $slot }}
    <x-footer :site-setting="$siteSetting" />
    @stack('scripts')
</body>
</html>

@props(['title' => null, 'description' => null, 'header' => 'overlay', 'bodyClass' => 'bg-cream'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title.' – ' : '' }}Njikoka Fashions LLC</title>
    <meta name="description" content="{{ $description ?? 'Premium tropical-inspired styles for adults and children designed for everyday elegance, special occasions, and unforgettable personal expression.' }}">
    <link rel="icon" href="{{ asset('images/site/cropped-Group-3-32x32.png') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('images/site/cropped-Group-3-192x192.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('images/site/cropped-Group-3-180x180.png') }}">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col {{ $bodyClass }}">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:bg-white focus:p-3">Skip to content</a>

    @include('partials.header', ['variant' => $header])

    <main id="main" class="flex-1">
        {{ $slot }}
    </main>

    @include('partials.footer')
    @if (config('store.whatsapp'))
        @include('partials.whatsapp-chat')
        @include('partials.toast', ['offset' => 'bottom-24 sm:bottom-28'])
    @else
        @include('partials.toast')
    @endif
</body>
</html>

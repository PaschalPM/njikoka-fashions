@props(['title' => null])

@php
    $links = [
        ['admin.products.index', 'admin.products.*', 'Products', 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z', 0],
        ['admin.design-requests.index', 'admin.design-requests.*', 'Design requests', 'M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42', $newDesignRequests],
        ['admin.appointments.index', 'admin.appointments.*', 'Appointments', 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z', $newAppointments],
        ['admin.whatsapp.index', 'admin.whatsapp.*', 'WhatsApp', 'M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z', 0],
        ['shop', null, 'View shop', 'M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25', 0],
        ['account', null, 'My account', 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z', 0],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">
    <title>{{ $title ? $title.' – ' : '' }}Admin – Njikoka Fashions LLC</title>
    <link rel="icon" href="{{ asset('images/site/cropped-Group-3-32x32.png') }}" sizes="32x32">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-mist text-base leading-normal">
    <div class="lg:grid lg:min-h-screen lg:grid-cols-[260px_1fr]">
        <aside class="bg-forest-dark text-white lg:sticky lg:top-0 lg:flex lg:h-screen lg:flex-col">
            <div class="flex items-center justify-between gap-4 px-5 py-4 lg:block lg:px-7 lg:pt-8 lg:pb-10">
                <a href="{{ route('admin.home') }}" class="block">
                    <span class="block font-display text-2xl leading-tight text-white lg:text-[28px]">Njikoka<br class="hidden lg:block"> Fashions</span>
                    <span class="mt-1 inline-block rounded-full bg-gold/20 px-2.5 py-0.5 text-xs font-bold tracking-wider text-gold uppercase">Admin</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="lg:hidden">
                    @csrf
                    <button class="text-sm text-white/70 hover:text-white">Log out</button>
                </form>
            </div>

            <nav class="flex gap-1 overflow-x-auto px-3 pb-3 lg:flex-1 lg:flex-col lg:px-4 lg:pb-0" aria-label="Admin">
                @foreach ($links as [$route, $pattern, $label, $icon, $badge])
                    @php($active = $pattern && request()->routeIs($pattern))
                    <a href="{{ route($route) }}" @class([
                        'flex shrink-0 items-center gap-3 rounded-xl px-4 py-2.5 text-[15px] transition',
                        'bg-white/12 font-bold text-white' => $active,
                        'text-white/75 hover:bg-white/8 hover:text-white' => ! $active,
                    ]) @if ($active) aria-current="page" @endif>
                        <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                        {{ $label }}
                        @if ($badge)
                            <span class="ml-auto rounded-full bg-gold px-2 py-0.5 text-xs font-bold text-cocoa">{{ $badge }}<span class="sr-only"> new</span></span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="hidden border-t border-white/10 px-7 py-6 lg:block">
                <p class="truncate text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                <p class="truncate text-xs text-white/60">{{ auth()->user()->email }}</p>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button class="text-sm text-white/70 underline-offset-4 hover:text-white hover:underline">Log out</button>
                </form>
            </div>
        </aside>

        <main id="main" class="min-w-0 px-4 py-6 sm:px-6 lg:px-10 lg:py-10">
            {{ $slot }}
        </main>
    </div>

    @include('partials.toast')
</body>
</html>

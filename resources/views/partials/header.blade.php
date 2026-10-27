@php
    $links = [
        'shop' => 'Shop',
        'custom-designs' => 'Custom Designs',
        'book-appointment' => 'Book Appointment',
        'about' => 'About',
        'contact' => 'Contact',
    ];
    $cartCount = array_sum(session('cart', []));
    $accountLabel = auth()->check() ? 'Account' : 'Login';
@endphp

<header @class([
    'z-40 w-full text-white',
    'absolute inset-x-0 top-0' => $variant === 'overlay',
    'bg-black' => $variant === 'black',
    'bg-umber' => $variant === 'brown',
])>
    {{-- Desktop --}}
    <div class="hidden h-[144px] items-start px-[30px] pt-[27px] pr-[95px] lg:flex">
        <a href="{{ route('home') }}" class="w-[290px] font-display text-[42px] leading-[42px] font-medium text-white">Njikoka<br>Fashions LLC</a>

        <nav class="mt-3 ml-[100px] flex items-center rounded-[50px] bg-cream/[0.33] px-[10px] py-[10px]" aria-label="Primary">
            @foreach ($links as $route => $label)
                <a href="{{ route($route) }}" @class([
                    'px-5 py-[13px] font-sans text-base leading-5 transition hover:text-gold',
                    'text-gold' => request()->routeIs($route),
                    'text-white' => ! request()->routeIs($route),
                ])>{{ $label }}</a>
            @endforeach
        </nav>

        <div class="mt-[22px] ml-auto flex items-center gap-5">
            <a href="{{ route('account') }}" class="rounded-[50px] border border-white bg-cream/[0.33] px-[30px] py-[15px] font-sans text-[15px] leading-[15px] font-medium text-white">Account</a>
            @include('partials.wishlist-icon')
            @include('partials.cart-icon')
        </div>
    </div>

    {{-- Mobile / tablet --}}
    <div class="flex flex-col items-center gap-4 px-[10px] pt-[10px] pb-4 lg:hidden">
        <a href="{{ route('home') }}" class="text-center font-display text-[26px] leading-[26px] font-medium text-white">Njikoka<br>Fashions LLC</a>

        <div class="flex items-center gap-[60px]">
            <details class="relative">
                <summary class="flex size-[38px] cursor-pointer list-none items-center justify-center rounded-[3px] border-2 border-white text-white [&::-webkit-details-marker]:hidden" aria-label="Menu">
                    <svg class="size-5" fill="currentColor" viewBox="0 0 448 512" aria-hidden="true"><path d="M0 96c0-17.7 14.3-32 32-32h384c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zm0 160c0-17.7 14.3-32 32-32h384c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zm448 160c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32h384c17.7 0 32 14.3 32 32z"/></svg>
                </summary>
                <nav class="absolute top-12 left-0 z-50 w-60 bg-white py-2 shadow-xl" aria-label="Mobile">
                    @foreach ($links as $route => $label)
                        <a href="{{ route($route) }}" @class([
                            'block px-5 py-3 font-sans text-base',
                            'text-gold' => request()->routeIs($route),
                            'text-ink' => ! request()->routeIs($route),
                        ])>{{ $label }}</a>
                    @endforeach
                </nav>
            </details>

            <a href="{{ route('account') }}" class="rounded-[50px] border border-white bg-cream/[0.33] px-[30px] py-[15px] font-sans text-[15px] leading-[15px] font-medium text-white">{{ $accountLabel }}</a>

            <div class="flex items-center gap-5">
                @include('partials.wishlist-icon')
                @include('partials.cart-icon')
            </div>
        </div>
    </div>
</header>

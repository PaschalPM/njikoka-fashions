{{-- "We make any Nigerian attire, and more" call to action, shown where a shopper might not find their style. --}}
<aside class="rounded-[25px] bg-linen px-5 py-8 text-center font-sans sm:px-10 lg:py-12 {{ $class ?? '' }}" aria-labelledby="any-attire-heading">
    <p class="text-xs font-bold tracking-[0.12em] text-forest uppercase">Made to order</p>
    <h2 id="any-attire-heading" class="mt-2 text-[28px] leading-tight lg:text-[40px]">{{ $heading ?? 'Don’t see your style? We’ll make it.' }}</h2>
    <p class="mx-auto mt-3 max-w-[660px] text-base leading-6 text-ink/75 lg:text-[18px] lg:leading-[27px]">We make any Nigerian attire, and we don’t stop there: evening gowns, bridal looks, suits and everyday wear too. Tell us what you have in mind and we’ll make it in your choice of fabric and colour, for you, your family or your whole aso ebi group.</p>
    @foreach ([
        'Nigerian attire' => ['Agbada', 'Babban riga', 'Buba and sokoto', 'Kaftan and senator', 'Isiagu', 'Iro and buba', 'George and Akwete wrappers', 'Ankara gowns', 'Bubu', 'Aso ebi', 'Gele and ichafu'],
        'And beyond' => ['Evening gowns', 'Bridal and reception wear', 'Suits and blazers', 'Corporate wear', 'Casual wear', 'Children’s wear'],
    ] as $group => $styles)
        <p class="mt-5 text-xs font-bold tracking-[0.08em] text-ink/50 uppercase">{{ $group }}</p>
        <ul class="mx-auto mt-2 flex max-w-[760px] flex-wrap justify-center gap-2" aria-label="{{ $group }} we make">
            @foreach ($styles as $style)
                <li class="rounded-full border border-sand bg-white px-3.5 py-1.5 text-sm text-cocoa">{{ $style }}</li>
            @endforeach
        </ul>
    @endforeach
    <div class="mt-6 flex flex-col items-center justify-center gap-3 sm:flex-row">
        <a href="{{ route('custom-designs') }}" class="btn-primary">Request a custom design</a>
        @if ($whatsappContacts->isNotEmpty())
            <a href="{{ route('whatsapp.chat', $whatsappContacts->first()->number) }}" target="_blank" rel="nofollow noopener" class="btn gap-2 border-2 border-[#25d366] text-[#128c4a] hover:bg-[#25d366] hover:text-white">
                @include('partials.icons.whatsapp', ['class' => 'size-5 shrink-0'])
                Chat on WhatsApp
            </a>
        @endif
    </div>
</aside>

@php
    $product = request()->route('product');
    $greeting = $product instanceof \App\Models\Product
        ? "Hi Njikoka Fashions! I'm interested in {$product->name}: ".route('product', $product)
        : 'Hi Njikoka Fashions! I have a question.';
    $chatUrl = 'https://wa.me/'.config('store.whatsapp').'?text='.rawurlencode($greeting);
@endphp

{{-- Floating "Chat with us" launcher that hands the visitor over to WhatsApp; resources/js/app.js closes it on Escape or an outside click. --}}
<details data-chat class="group fixed right-4 bottom-4 z-40 sm:right-6 sm:bottom-6">
    <summary class="ml-auto flex w-fit cursor-pointer list-none items-center gap-2 rounded-full bg-[#25d366] p-3.5 text-white shadow-[0_8px_24px_rgba(0,0,0,0.25)] transition hover:scale-105 hover:bg-[#1ebe5b] sm:py-3 sm:pr-5 sm:pl-4 [&::-webkit-details-marker]:hidden" aria-label="Chat with us on WhatsApp">
        @include('partials.icons.whatsapp', ['class' => 'size-7 group-open:hidden'])
        <svg class="hidden size-7 group-open:block" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        <span class="hidden font-sans text-base font-bold sm:inline">Chat with us</span>
    </summary>

    <div class="absolute right-0 bottom-[calc(100%+12px)] w-[calc(100vw-32px)] max-w-[340px] origin-bottom-right overflow-hidden rounded-2xl bg-white font-sans shadow-[0_16px_40px_rgba(0,0,0,0.22)]">
        <div class="flex items-center gap-3 bg-forest-dark px-5 py-4 text-white">
            <span class="relative flex size-11 shrink-0 items-center justify-center rounded-full bg-cream font-display text-lg text-cocoa">
                NF
                <span class="absolute right-0 bottom-0 size-3 rounded-full border-2 border-forest-dark bg-[#25d366]" aria-hidden="true"></span>
            </span>
            <div>
                <p class="text-base leading-tight font-bold">Njikoka Fashions</p>
                <p class="text-sm leading-tight text-white/75">Typically replies within a few hours</p>
            </div>
        </div>

        <div class="bg-linen px-5 py-5">
            <p class="w-fit max-w-[85%] rounded-2xl rounded-tl-sm bg-white px-4 py-3 text-[15px] leading-snug text-ink shadow-sm">
                Hi there 👋<br>
                @if ($product instanceof \App\Models\Product)
                    Questions about <strong>{{ $product->name }}</strong>? Sizing, fabric or delivery, we're happy to help.
                @else
                    How can we help you? Ask us about sizing, custom designs or your order.
                @endif
            </p>
        </div>

        <div class="px-5 py-4">
            <a href="{{ $chatUrl }}" target="_blank" rel="noopener" class="flex w-full items-center justify-center gap-2 rounded-full bg-[#25d366] px-5 py-3 text-base font-bold text-white transition hover:bg-[#1ebe5b]">
                @include('partials.icons.whatsapp')
                Start chat on WhatsApp
            </a>
            <p class="mt-2 text-center text-xs text-ink/50">Opens WhatsApp in a new tab</p>
        </div>
    </div>
</details>

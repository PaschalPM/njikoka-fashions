<x-layouts.app title="Cart" header="black">
    <section class="font-sans text-base text-ink">
        <div class="container-site pt-[50px] pb-[80px] lg:pt-[70px] lg:pb-[100px]">
            <div class="flex flex-wrap items-end justify-between gap-4 border-b border-sand pb-6">
                <div>
                    <h1 class="text-[40px] lg:text-[55px]">Your cart</h1>
                    @if ($items->isNotEmpty())
                        <p class="mt-3 text-base text-ink/70">{{ $item_count }} {{ Str::plural('item', $item_count) }}</p>
                    @endif
                </div>
                <a href="{{ route('shop') }}" class="link-underline">Continue shopping</a>
            </div>

            @if ($items->isEmpty())
                <div class="mx-auto mt-14 flex max-w-[480px] flex-col items-center text-center">
                    <span class="flex size-20 items-center justify-center rounded-full bg-white text-forest shadow-[0_6px_20px_rgba(43,29,24,0.08)]">
                        <svg class="size-9" fill="currentColor" viewBox="0 0 448 512" aria-hidden="true"><path d="M160 112c0-35.3 28.7-64 64-64s64 28.7 64 64v48H160v-48zm-48 48H48c-26.5 0-48 21.5-48 48v208c0 53 43 96 96 96h256c53 0 96-43 96-96V208c0-26.5-21.5-48-48-48h-64v-48C336 50.1 285.9 0 224 0S112 50.1 112 112v48zm24 48a24 24 0 1 1 0 48 24 24 0 1 1 0-48zm152 24a24 24 0 1 1 48 0 24 24 0 1 1-48 0z"/></svg>
                    </span>
                    <h2 class="mt-6 text-[32px]">Your cart is currently empty!</h2>
                    <p class="mt-3 text-ink/70">Find something you love in our latest collection of tropical-inspired styles.</p>
                    <a href="{{ route('shop') }}" class="btn-primary mt-8">Browse the shop</a>
                </div>
            @else
                <div class="mt-10 grid items-start gap-8 lg:grid-cols-[minmax(0,1fr)_400px] lg:gap-12">
                    <ul class="space-y-4">
                        @foreach ($items as $item)
                            <li class="flex gap-4 rounded-2xl bg-white p-4 shadow-[0_6px_20px_rgba(43,29,24,0.06)] sm:gap-6 sm:p-5">
                                <a href="{{ route('product', $item->product) }}" class="w-24 shrink-0 overflow-hidden rounded-xl bg-linen sm:w-32">
                                    <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="aspect-[4/5] size-full object-cover">
                                </a>

                                <div class="flex min-w-0 flex-1 flex-col">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold tracking-[0.08em] text-ink/50 uppercase">{{ $item->product->category->name }}</p>
                                            <h2 class="mt-1 truncate text-[22px] leading-tight sm:text-[26px]"><a href="{{ route('product', $item->product) }}" class="hover:text-forest">{{ $item->product->name }}</a></h2>
                                            <p class="mt-1 text-sm text-ink/60">{{ $item->product->formattedPrice() }} each</p>
                                        </div>
                                        <p class="shrink-0 text-lg font-bold text-cocoa">${{ number_format($item->subtotal_cents / 100, 2) }}</p>
                                    </div>

                                    <div class="mt-auto flex flex-wrap items-center justify-between gap-3 pt-4">
                                        <form method="POST" action="{{ route('cart.update', $item->product) }}" class="flex items-center rounded-full border border-sand bg-cream/60">
                                            @csrf @method('PATCH')
                                            <button name="quantity" value="{{ $item->quantity - 1 }}" class="flex size-10 items-center justify-center rounded-full text-lg transition hover:bg-sand/50" aria-label="Reduce quantity of {{ $item->product->name }}">&minus;</button>
                                            <span class="w-8 text-center font-bold" aria-label="Quantity">{{ $item->quantity }}</span>
                                            <button name="quantity" value="{{ min(20, $item->quantity + 1) }}" @disabled($item->quantity >= 20) class="flex size-10 items-center justify-center rounded-full text-lg transition hover:bg-sand/50 disabled:opacity-30" aria-label="Increase quantity of {{ $item->product->name }}">+</button>
                                        </form>

                                        <div class="flex items-center gap-1">
                                            @include('partials.wishlist-button', ['product' => $item->product])
                                            <form method="POST" action="{{ route('cart.destroy', $item->product) }}">
                                                @csrf @method('DELETE')
                                                <button class="flex items-center gap-1.5 rounded-full px-3 py-2 text-sm text-ink/60 transition hover:bg-[#e2401c]/8 hover:text-[#e2401c]">
                                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                                    Remove<span class="sr-only"> {{ $item->product->name }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <aside class="rounded-2xl bg-white p-6 shadow-[0_6px_20px_rgba(43,29,24,0.06)] lg:sticky lg:top-6 lg:p-8">
                        <h2 class="text-[28px]">Order summary</h2>
                        <dl class="mt-6 space-y-3 text-base">
                            <div class="flex justify-between">
                                <dt class="text-ink/70">Subtotal ({{ $item_count }} {{ Str::plural('item', $item_count) }})</dt>
                                <dd>${{ number_format($total_cents / 100, 2) }}</dd>
                            </div>
                            <div class="flex items-baseline justify-between border-t border-sand pt-4">
                                <dt class="text-lg font-bold text-cocoa">Estimated total</dt>
                                <dd class="text-[26px] font-bold text-forest">${{ number_format($total_cents / 100, 2) }}</dd>
                            </div>
                        </dl>

                        <a href="{{ route('checkout') }}" class="btn-primary mt-6 w-full py-[18px] text-[17px]">Proceed to Checkout</a>

                        @if ($whatsappOrderUrl)
                            <div class="my-4 flex items-center gap-3 text-xs tracking-[0.08em] text-ink/40 uppercase">
                                <span class="h-px flex-1 bg-sand"></span>or<span class="h-px flex-1 bg-sand"></span>
                            </div>
                            <a href="{{ $whatsappOrderUrl }}" target="_blank" rel="noopener" class="btn w-full gap-2 border-2 border-[#25d366] py-4 text-[17px] text-[#128c4a] hover:bg-[#25d366] hover:text-white">
                                @include('partials.icons.whatsapp')
                                Order via WhatsApp
                            </a>
                            <p class="mt-2 text-center text-xs text-ink/50">Sends your cart to our team so we can finish the order with you in chat.</p>
                        @endif

                        <p class="mt-4 flex items-center justify-center gap-2 text-sm text-ink/60">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            Secure checkout powered by Stripe
                        </p>
                        <img src="{{ asset('images/site/Frame-1948755515.png') }}" alt="Mastercard, Visa, Cash App, American Express, Discover, JCB, Apple Pay, Google Pay" class="mx-auto mt-4 h-auto w-full max-w-[300px]">
                    </aside>
                </div>
            @endif

            @if ($favorites->isNotEmpty() || $newInStore->isNotEmpty())
                <div class="mt-16 lg:mt-24">
                    <div class="flex flex-wrap items-end justify-between gap-4">
                        <h2 class="text-[30px] lg:text-[40px]">{{ $favorites->isNotEmpty() ? 'From your favorites' : 'New in store' }}</h2>
                        <a href="{{ $favorites->isNotEmpty() ? route('wishlist') : route('shop') }}" class="link-underline">{{ $favorites->isNotEmpty() ? 'View all favorites' : 'Shop all' }}</a>
                    </div>
                    <div class="mt-8 grid grid-cols-2 gap-x-4 gap-y-8 sm:gap-x-6 md:grid-cols-4">
                        @foreach ($favorites->isNotEmpty() ? $favorites : $newInStore as $product)
                            <x-product-tile :product="$product" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>

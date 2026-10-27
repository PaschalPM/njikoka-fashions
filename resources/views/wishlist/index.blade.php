<x-layouts.app title="Favorites" header="black">
    <section class="font-sans text-base text-ink">
        <div class="container-site pt-[50px] pb-[80px] lg:pt-[70px] lg:pb-[100px]">
            <div class="flex flex-wrap items-end justify-between gap-4 border-b border-sand pb-6">
                <div>
                    <h1 class="text-[40px] lg:text-[55px]">Favorites</h1>
                    @if ($products->isNotEmpty())
                        <p class="mt-3 text-base text-ink/70">{{ $products->count() }} saved {{ Str::plural('piece', $products->count()) }}. Tap a heart to remove it.</p>
                    @endif
                </div>
                <a href="{{ route('shop') }}" class="link-underline">Continue shopping</a>
            </div>

            @if ($products->isEmpty())
                <div class="mx-auto mt-14 flex max-w-[480px] flex-col items-center text-center">
                    <span class="flex size-20 items-center justify-center rounded-full bg-white text-[#cc3366] shadow-[0_6px_20px_rgba(43,29,24,0.08)]">
                        <svg class="size-9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                    </span>
                    <h2 class="mt-6 text-[32px]">No favorites yet</h2>
                    <p class="mt-3 text-ink/70">You haven't saved any favorites yet. Tap the heart on any piece to keep it here while you decide.</p>
                    <a href="{{ route('shop') }}" class="btn-primary mt-8">Browse the shop</a>
                </div>
            @else
                <div data-wishlist-grid class="mt-10 grid grid-cols-2 gap-x-4 gap-y-8 sm:gap-x-6 md:grid-cols-3 xl:grid-cols-4">
                    @foreach ($products as $product)
                        <x-product-tile :product="$product" :in-cart="in_array($product->id, $cartIds)" remove-on-unsave />
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>

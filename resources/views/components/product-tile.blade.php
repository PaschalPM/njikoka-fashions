@props(['product', 'inCart' => false, 'removeOnUnsave' => false])

{{-- Compact card for the cart and favorites pages: photo with heart, price and a single add-to-cart action. --}}
<article @if ($removeOnUnsave) data-wishlist-item @endif class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-[0_6px_20px_rgba(43,29,24,0.06)] transition duration-300 data-leaving:scale-95 data-leaving:opacity-0">
    <div class="relative aspect-[4/5] overflow-hidden bg-linen">
        <a href="{{ route('product', $product) }}" class="block size-full">
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" loading="lazy" class="size-full object-cover transition duration-500 group-hover:scale-[1.03]">
        </a>
        @include('partials.wishlist-button', ['class' => 'absolute top-3 right-3'])
        @unless ($product->in_stock)
            <span class="absolute top-3 left-3 rounded-full bg-cocoa/85 px-3 py-1 text-xs font-bold text-white">Sold out</span>
        @endunless
    </div>

    <div class="flex flex-1 flex-col p-4 text-center">
        @if ($product->relationLoaded('category'))
            <p class="text-xs font-bold tracking-[0.08em] text-ink/50 uppercase">{{ $product->category->name }}</p>
        @endif
        <h3 class="mt-1 text-[22px] leading-tight"><a href="{{ route('product', $product) }}" class="hover:text-forest">{{ $product->name }}</a></h3>
        <p class="mt-1 font-sans text-base text-forest">{{ $product->formattedPrice() }}</p>

        <div class="mt-auto pt-4">
            @if ($inCart)
                <a href="{{ route('cart') }}" class="btn-outline w-full py-3 text-[15px]">In your cart</a>
            @elseif ($product->in_stock)
                <form method="POST" action="{{ route('cart.store', $product) }}">
                    @csrf
                    <button class="btn-primary w-full py-3 text-[15px]">Add to cart</button>
                </form>
            @else
                <span class="btn w-full cursor-not-allowed bg-black/5 py-3 text-[15px] text-ink/50">Sold out</span>
            @endif
        </div>
    </div>
</article>

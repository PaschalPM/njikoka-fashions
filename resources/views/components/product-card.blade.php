@props(['product', 'imageClass' => 'h-[410px] lg:h-[660px]'])

<article class="flex flex-col items-center text-center">
    <div class="relative w-full overflow-hidden rounded-[25px] {{ $imageClass }}">
        <a href="{{ route('product', $product) }}" class="block size-full">
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" loading="lazy" class="size-full object-cover">
        </a>
        @include('partials.wishlist-button', ['class' => 'absolute top-[30px] right-[30px]'])
        @if ($product->in_stock)
            <form method="POST" action="{{ route('cart.store', $product) }}" class="absolute inset-x-5 bottom-5">
                @csrf
                <button class="btn w-full bg-forest/75 py-[15px] text-[18px] leading-[18px] font-normal text-white hover:bg-forest">Add to cart</button>
            </form>
        @endif
    </div>
    <a href="{{ route('product', $product) }}" class="link-underline mt-5">Shop Now<span class="sr-only">: {{ $product->name }}</span></a>
</article>

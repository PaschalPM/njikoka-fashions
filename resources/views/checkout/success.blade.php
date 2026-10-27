<x-layouts.app title="Order received" header="black" body-class="bg-white">
    <section class="bg-white font-sans text-base text-ink">
        <div class="container-site max-w-[900px] pt-[50px] pb-[80px]">
            <h1 class="font-sans text-[32px] leading-[1.2] text-ink">Order received</h1>
            @if ($order->isPaid())
                <p class="mt-6">Thank you. Your order has been received.</p>
            @else
                <p class="mt-6">Thank you. We’re confirming your payment — you’ll receive an email as soon as it clears.</p>
            @endif

            <div class="mt-8">
                @include('partials.order-summary')
            </div>

            <a href="{{ route('shop') }}" class="woo-button mt-10">Continue shopping</a>
        </div>
    </section>
</x-layouts.app>

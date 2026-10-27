<x-layouts.app :title="'Order #'.$order->number" header="black" body-class="bg-white">
    <section class="bg-white font-sans text-base text-ink">
        <div class="container-site max-w-[900px] pt-[50px] pb-[80px]">
            <h1 class="font-sans text-[32px] leading-[1.2] text-ink">Order #{{ $order->number }}</h1>
            <div class="mt-8">
                @include('partials.order-summary')
            </div>
            <a href="{{ route('account') }}" class="woo-button mt-10">Back to orders</a>
        </div>
    </section>
</x-layouts.app>

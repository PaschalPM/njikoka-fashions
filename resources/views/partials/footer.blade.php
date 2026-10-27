<footer class="bg-cream text-ink">
    <div class="container-site grid gap-5 py-10 text-center lg:grid-cols-[645px_265px_265px_1fr] lg:items-center lg:gap-0 lg:py-[100px] lg:text-left">
        <a href="{{ route('home') }}" class="mx-auto font-display text-[26px] leading-[26px] font-medium text-cocoa lg:mx-0 lg:w-[200px] lg:text-[47px] lg:leading-[47px]">
            Njikoka<br class="lg:hidden"> Fashions<br>LLC
        </a>

        <div class="lg:self-start">
            <h2 class="text-[20px] lg:text-[25px]">Quick Links</h2>
            <ul class="mt-[18px] space-y-[6px] lg:space-y-3">
                <li><a class="hover:text-forest" href="{{ route('shop') }}">Shop</a></li>
                <li><a class="hover:text-forest" href="{{ route('custom-designs') }}">Custom Designs</a></li>
                <li><a class="hover:text-forest" href="{{ route('about') }}">About</a></li>
            </ul>
        </div>

        <div class="lg:self-start">
            <h2 class="text-[20px] lg:text-[25px]">Support</h2>
            <ul class="mt-[18px] space-y-[6px] lg:space-y-3">
                <li><a class="hover:text-forest" href="{{ route('shipping') }}">Shipping Policy</a></li>
                <li><a class="hover:text-forest" href="{{ route('returns') }}">Returns</a></li>
                <li><a class="hover:text-forest" href="{{ route('faqs') }}">FAQs</a></li>
                <li><a class="hover:text-forest" href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </div>

        <div class="lg:self-start">
            <h2 class="text-[20px] lg:text-[25px]">Contact us</h2>
            @include('partials.contact-details', ['class' => 'mt-[18px] items-center lg:items-start'])
        </div>
    </div>

    <div class="container-site">
        <div class="flex flex-col items-center gap-6 border-t border-sand py-5 lg:flex-row lg:justify-between lg:py-10">
            <p>&copy; {{ date('Y') }} Njikoka Fashions LLC. All Rights Reserved.</p>
            <img src="{{ asset('images/site/Frame-1948755515.png') }}" alt="Mastercard, Visa, Cash App, American Express, Discover, JCB, Apple Pay, Google Pay" class="h-auto w-full max-w-[503px]">
        </div>
    </div>
</footer>

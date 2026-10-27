<x-layouts.app title="Checkout" header="black" body-class="bg-white">
    <section class="bg-white font-sans text-base text-ink">
        <form method="POST" action="{{ route('checkout.store') }}" class="container-site grid gap-10 pt-[50px] pb-[80px] lg:grid-cols-[1fr_400px] lg:gap-[60px]">
            @csrf
            <div class="space-y-8">
                @include('partials.form-errors')

                @guest
                    <p class="text-[14px] text-[#757575]">Returning customer? <a href="{{ route('account') }}" class="text-[#cc3366] underline">Log in</a></p>
                @endguest

                @php($input = 'block w-full rounded-[4px] border border-[#50575e] bg-white px-4 pt-6 pb-2 text-base text-ink outline-none focus:border-forest')
                @php($float = 'pointer-events-none absolute top-2 left-4 text-[12px] text-[#757575]')

                <fieldset>
                    <legend class="font-sans text-[18px] leading-[1.2]">Contact information</legend>
                    <p class="mt-2 text-[14px] text-[#757575]">We'll use this email to send you details and updates about your order.</p>
                    <div class="relative mt-4">
                        <label class="{{ $float }}" for="email">Email address</label>
                        <input class="{{ $input }}" type="email" id="email" name="email" value="{{ old('email', $user?->email) }}" autocomplete="email" required>
                    </div>
                </fieldset>

                <fieldset>
                    <legend class="font-sans text-[18px] leading-[1.2]">Billing address</legend>
                    <p class="mt-2 text-[14px] text-[#757575]">Enter the billing address that matches your payment method.</p>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div class="relative sm:col-span-2">
                            <label class="{{ $float }}" for="country">Country/Region</label>
                            <select class="{{ $input }}" id="country" name="country" autocomplete="country" required>
                                @foreach (['US' => 'United States (US)', 'CA' => 'Canada', 'GB' => 'United Kingdom (UK)', 'NG' => 'Nigeria'] as $code => $label)
                                    <option value="{{ $code }}" @selected(old('country', 'US') === $code)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative">
                            <label class="{{ $float }}" for="first_name">First name</label>
                            <input class="{{ $input }}" id="first_name" name="first_name" value="{{ old('first_name', str($user?->name)->before(' ')) }}" autocomplete="given-name" required>
                        </div>
                        <div class="relative">
                            <label class="{{ $float }}" for="last_name">Last name</label>
                            <input class="{{ $input }}" id="last_name" name="last_name" value="{{ old('last_name', $user && str_contains($user->name, ' ') ? str($user->name)->after(' ') : '') }}" autocomplete="family-name" required>
                        </div>
                        <div class="relative sm:col-span-2">
                            <label class="{{ $float }}" for="address_line1">Address</label>
                            <input class="{{ $input }}" id="address_line1" name="address_line1" value="{{ old('address_line1') }}" autocomplete="address-line1" required>
                        </div>
                        <div class="relative sm:col-span-2">
                            <label class="{{ $float }}" for="address_line2">Apartment, suite, etc. (optional)</label>
                            <input class="{{ $input }}" id="address_line2" name="address_line2" value="{{ old('address_line2') }}" autocomplete="address-line2">
                        </div>
                        <div class="relative">
                            <label class="{{ $float }}" for="city">City</label>
                            <input class="{{ $input }}" id="city" name="city" value="{{ old('city') }}" autocomplete="address-level2" required>
                        </div>
                        <div class="relative">
                            <label class="{{ $float }}" for="state">State</label>
                            <input class="{{ $input }}" id="state" name="state" value="{{ old('state') }}" autocomplete="address-level1" required>
                        </div>
                        <div class="relative">
                            <label class="{{ $float }}" for="postcode">ZIP Code</label>
                            <input class="{{ $input }}" id="postcode" name="postcode" value="{{ old('postcode') }}" autocomplete="postal-code" required>
                        </div>
                        <div class="relative">
                            <label class="{{ $float }}" for="phone">Phone (optional)</label>
                            <input class="{{ $input }}" type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel">
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend class="font-sans text-[18px] leading-[1.2]">Payment options</legend>
                    <div class="mt-4 rounded-[4px] border border-[#50575e] p-4">
                        <p class="font-bold">Credit / Debit Card</p>
                        <p class="mt-1 text-[14px] text-[#757575]">You will be taken to Stripe’s secure checkout to complete your payment.</p>
                        <img src="{{ asset('images/site/Frame-1948755515.png') }}" alt="Mastercard, Visa, Cash App, American Express, Discover, JCB, Apple Pay, Google Pay" class="mt-3 h-auto w-full max-w-[380px]">
                    </div>
                </fieldset>

                <div>
                    <label class="text-[14px]" for="notes">Add a note to your order</label>
                    <textarea class="mt-2 block w-full rounded-[4px] border border-[#50575e] p-3 text-base outline-none focus:border-forest" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                </div>

                <p class="text-[13px] text-[#757575]">By proceeding with your purchase you agree to our Terms and Conditions and Privacy Policy</p>

                <div class="flex flex-wrap items-center justify-between gap-4">
                    <a href="{{ route('cart') }}" class="text-[14px] text-[#cc3366] underline">&larr; Return to Cart</a>
                    <button class="woo-button px-10 py-4 text-base">Place Order</button>
                </div>
            </div>

            <aside class="h-fit border border-[#e0e0e0] lg:sticky lg:top-6">
                <h2 class="border-b border-[#e0e0e0] px-4 py-4 font-sans text-[18px] leading-[1.2]">Order summary</h2>
                <ul class="divide-y divide-[#e0e0e0] px-4">
                    @foreach ($items as $item)
                        <li class="flex items-start gap-4 py-4">
                            <span class="relative shrink-0">
                                <img src="{{ asset($item->product->image) }}" alt="" class="size-12 border border-[#e0e0e0] object-cover">
                                <span class="absolute -top-2 -right-2 flex size-5 items-center justify-center rounded-full border-2 border-white bg-white text-[11px] shadow">{{ $item->quantity }}</span>
                            </span>
                            <span class="flex-1 text-[14px]">{{ $item->product->name }}<br><span class="text-[#757575]">{{ $item->product->formattedPrice() }}</span></span>
                            <span class="text-[14px]">${{ number_format($item->subtotal_cents / 100, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="flex justify-between border-t border-[#e0e0e0] px-4 py-3 text-[14px]">
                    <span>Subtotal</span>
                    <span>${{ number_format($total_cents / 100, 2) }}</span>
                </div>
                <div class="flex justify-between border-t border-[#e0e0e0] px-4 py-4 text-[18px] font-bold">
                    <span>Total</span>
                    <span>${{ number_format($total_cents / 100, 2) }}</span>
                </div>
            </aside>
        </form>
    </section>
</x-layouts.app>

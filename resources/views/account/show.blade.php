<x-layouts.app title="My account" header="black" body-class="bg-white">
    <section class="bg-white font-sans text-base text-ink">
        <div class="container-site pt-[50px] pb-[80px]">
            @auth
                <h1 class="font-sans text-[32px] leading-[1.2] text-ink">My account</h1>
                <div class="mt-8 grid gap-10 lg:grid-cols-[230px_1fr]">
                    <nav class="h-fit border border-[#ddd]" aria-label="Account">
                        <a href="{{ route('account') }}" class="block border-b border-[#ddd] bg-[#f7f7f7] px-4 py-3 font-bold">Orders</a>
                        @can('admin')
                            <a href="{{ route('admin.products.index') }}" class="block border-b border-[#ddd] px-4 py-3 hover:bg-[#f7f7f7]">Manage products</a>
                        @endcan
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="block w-full px-4 py-3 text-left hover:bg-[#f7f7f7]">Log out</button>
                        </form>
                    </nav>

                    <div>
                        <p>Hello <strong>{{ auth()->user()->name }}</strong> (not <strong>{{ auth()->user()->name }}</strong>? <button form="logout-inline" class="text-[#cc3366] underline">Log out</button>)</p>
                        <form id="logout-inline" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>

                        @if ($orders->isEmpty())
                            <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-t-[3px] border-forest bg-[#f6f5f8] px-6 py-4">
                                <span>No order has been made yet.</span>
                                <a href="{{ route('shop') }}" class="woo-button">Browse products</a>
                            </div>
                        @else
                            <div class="mt-6 overflow-x-auto">
                                <table class="w-full min-w-[560px] border border-[#ddd] text-left">
                                    <thead class="bg-[#f7f7f7]">
                                        <tr><th class="px-4 py-3">Order</th><th class="px-4 py-3">Date</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Total</th><th class="px-4 py-3">Actions</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr class="border-t border-[#ddd]">
                                                <td class="px-4 py-3"><a href="{{ route('account.order', $order) }}" class="text-[#cc3366] underline">#{{ $order->number }}</a></td>
                                                <td class="px-4 py-3">{{ $order->created_at->format('F j, Y') }}</td>
                                                <td class="px-4 py-3">{{ ucfirst($order->status) }}</td>
                                                <td class="px-4 py-3">{{ $order->formattedTotal() }} for {{ $order->items->sum('quantity') }} {{ Str::plural('item', $order->items->sum('quantity')) }}</td>
                                                <td class="px-4 py-3"><a href="{{ route('account.order', $order) }}" class="woo-button px-4 py-2 text-sm">View</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <h1 class="font-sans text-[32px] leading-[1.2] text-ink">Login</h1>

                <form method="POST" action="{{ route('login') }}" class="mt-[45px] border border-[#ddd] px-[30px] py-[30px] text-[14px] text-[#515151]">
                    @csrf
                    @include('partials.form-errors')
                    <label class="block" for="email">Username or email address&nbsp;<span class="text-[#e2401c]">*</span></label>
                    <input class="woo-input mt-[5px] h-[50px]" type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="username" required>

                    <label class="mt-[25px] block" for="password">Password&nbsp;<span class="text-[#e2401c]">*</span></label>
                    <input class="woo-input mt-[5px] h-[50px]" type="password" id="password" name="password" autocomplete="current-password" required>

                    <div class="mt-[25px] flex items-center gap-[14px]">
                        <button class="woo-button w-[100px] px-0 py-[11px] text-[14px]">Log in</button>
                        <label class="flex items-center gap-1">
                            <input type="checkbox" name="remember" value="1"> Remember me
                        </label>
                    </div>
                    <a href="{{ route('password.request') }}" class="mt-[18px] inline-block text-[12px] text-ink hover:underline">Lost your password?</a>
                </form>
            @endauth
        </div>
    </section>
</x-layouts.app>

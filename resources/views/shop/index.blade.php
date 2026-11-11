<x-layouts.app title="Shop" body-class="bg-white">
    <x-page-hero title="Shop" />

    <section class="bg-white py-[30px] lg:pt-[100px] lg:pb-[100px]">
        <div class="container-site grid gap-[30px] lg:grid-cols-[363px_1fr] lg:gap-[29px]">
            <form method="GET" action="{{ route('shop') }}" class="h-fit rounded-xl border border-[#e5e5e5] bg-white p-[18px] shadow-[0_6px_20px_rgba(0,0,0,0.06)]">
                <label class="block text-base leading-6 text-[#222]" for="q">Search products</label>
                <input class="mt-2 block w-full rounded-lg border border-[#d9d9d9] px-[14px] py-[11px] font-sans text-base text-[#222] outline-none placeholder:text-[#666] focus:border-forest" type="search" id="q" name="q" value="{{ request('q') }}" placeholder="Search, e.g. babban riga, gele, lace…">

                @if ($styleCounts)
                    <fieldset class="mt-5">
                        <legend class="text-base leading-6 font-bold text-[#222]">Styles</legend>
                        <div class="mt-[10px] space-y-[10px] text-base leading-6 text-[#222]">
                            @foreach ($styleCounts as $style => $count)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="styles[]" value="{{ $style }}" @checked(in_array($style, $selectedStyles))>
                                    {{ config("store.styles.$style.label") }}
                                    <span class="text-sm text-[#888]">({{ $count }})</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                @endif

                <fieldset class="mt-5">
                    <legend class="text-base leading-6 font-bold text-[#222]">Categories</legend>
                    <div class="mt-[10px] space-y-[10px] text-base leading-6 text-[#222]">
                        @foreach ($categories as $category)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="categories[]" value="{{ $category->slug }}" @checked(in_array($category->slug, request()->array('categories')))>
                                {{ $category->name }}
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <div class="mt-[18px] flex items-center gap-[10px]">
                    <button class="rounded-lg bg-forest px-4 py-3 font-system text-base leading-[22px] text-white hover:bg-forest-dark">Apply Filters</button>
                    <a href="{{ route('shop') }}" class="rounded-lg border border-[#e5e5e5] bg-white px-4 py-3 font-system text-base leading-[22px] text-forest hover:border-forest">Reset</a>
                </div>
            </form>

            <div>
                @if ($styleCounts)
                    <nav class="-mx-1 mb-6 flex gap-2 overflow-x-auto px-1 pb-1" aria-label="Shop by style">
                        @php($keep = array_filter(['categories' => request()->array('categories'), 'stock' => request('stock')]))
                        <a href="{{ route('shop', $keep) }}" @class([
                            'shrink-0 rounded-full border px-4 py-2 font-sans text-sm transition',
                            'border-forest bg-forest text-white' => ! $selectedStyles,
                            'border-[#e5e5e5] bg-white text-[#222] hover:border-forest' => $selectedStyles,
                        ]) @if (! $selectedStyles) aria-current="page" @endif>All styles</a>
                        @foreach ($styleCounts as $style => $count)
                            @php($active = $selectedStyles === [$style])
                            <a href="{{ route('shop', [...$keep, 'styles' => [$style]]) }}" @class([
                                'shrink-0 rounded-full border px-4 py-2 font-sans text-sm transition',
                                'border-forest bg-forest text-white' => $active,
                                'border-[#e5e5e5] bg-white text-[#222] hover:border-forest' => ! $active,
                            ]) @if ($active) aria-current="page" @endif>{{ config("store.styles.$style.label") }} <span @class(['text-xs', 'text-white/75' => $active, 'text-[#888]' => ! $active])>{{ $count }}</span></a>
                        @endforeach
                    </nav>
                @endif

                @if ($products->isEmpty())
                    <p class="text-base">No products were found matching your selection.</p>
                    @include('partials.any-attire', ['class' => 'mt-8', 'heading' => 'Can’t find it? We can make it for you.'])
                @else
                    <div class="grid gap-x-5 gap-y-[30px] sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                    <div class="mt-10">{{ $products->links() }}</div>
                    @include('partials.any-attire', ['class' => 'mt-12'])
                @endif
            </div>
        </div>
    </section>
</x-layouts.app>

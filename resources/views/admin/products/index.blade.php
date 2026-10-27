<x-layouts.admin title="Products">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-[36px] text-cocoa">Products</h1>
            <p class="mt-2 text-[15px] text-ink/70">Upload new designs, update prices and mark pieces as sold out.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn-primary gap-2">
            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add product
        </a>
    </div>

    <dl class="mt-8 grid grid-cols-3 gap-3 sm:gap-5">
        @foreach ([
            ['All products', $totals['all'], null, 'text-cocoa'],
            ['In stock', $totals['in_stock'], 'in', 'text-forest'],
            ['Sold out', $totals['sold_out'], 'out', 'text-[#b4532a]'],
        ] as [$label, $count, $stock, $color])
            <a href="{{ route('admin.products.index', array_filter(['stock' => $stock])) }}" @class([
                'rounded-2xl border bg-white p-4 transition hover:border-forest/40 sm:p-6',
                'border-forest ring-1 ring-forest' => request('stock') === $stock || (! $stock && ! request('stock')),
                'border-black/8' => ! (request('stock') === $stock || (! $stock && ! request('stock'))),
            ])>
                <dt class="text-xs font-bold tracking-wide text-ink/60 uppercase sm:text-sm">{{ $label }}</dt>
                <dd class="mt-1 font-display text-3xl {{ $color }} sm:text-4xl">{{ $count }}</dd>
            </a>
        @endforeach
    </dl>

    <form method="GET" action="{{ route('admin.products.index') }}" class="mt-6 flex flex-col gap-3 rounded-2xl border border-black/8 bg-white p-3 sm:flex-row sm:items-center">
        <label class="relative flex-1">
            <span class="sr-only">Search products</span>
            <svg class="pointer-events-none absolute top-1/2 left-3.5 size-5 -translate-y-1/2 text-ink/40" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search by name…" class="ff-input pl-11">
        </label>
        <label>
            <span class="sr-only">Category</span>
            <select name="category" class="ff-input sm:w-44">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((int) request('category') === $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </label>
        <label>
            <span class="sr-only">Stock</span>
            <select name="stock" class="ff-input sm:w-40">
                <option value="">Any stock</option>
                <option value="in" @selected(request('stock') === 'in')>In stock</option>
                <option value="out" @selected(request('stock') === 'out')>Sold out</option>
            </select>
        </label>
        <div class="flex gap-2">
            <button class="ff-submit flex-1 sm:flex-none">Filter</button>
            @if (request()->hasAny(['q', 'category', 'stock']))
                <a href="{{ route('admin.products.index') }}" class="rounded-[7px] px-4 py-3 text-center text-[15px] text-ink/70 hover:bg-mist">Clear</a>
            @endif
        </div>
    </form>

    @if ($products->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-black/15 bg-white px-6 py-16 text-center">
            <h2 class="font-display text-2xl text-cocoa">{{ $totals['all'] ? 'No products match those filters' : 'No products yet' }}</h2>
            <p class="mt-2 text-[15px] text-ink/70">{{ $totals['all'] ? 'Try a different search or clear the filters.' : 'Add your first design to start selling.' }}</p>
            @unless ($totals['all'])
                <a href="{{ route('admin.products.create') }}" class="btn-primary mt-6">Add product</a>
            @endunless
        </div>
    @else
        <ul class="mt-6 grid grid-cols-2 gap-3 sm:gap-5 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
            @foreach ($products as $product)
                <li class="group flex flex-col overflow-hidden rounded-2xl border border-black/8 bg-white transition hover:shadow-lg">
                    <a href="{{ route('admin.products.edit', $product) }}" class="relative block aspect-[4/5] overflow-hidden bg-linen">
                        @if ($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" loading="lazy" class="size-full object-cover transition duration-300 group-hover:scale-[1.03]">
                        @endif
                        <span @class([
                            'absolute top-3 left-3 rounded-full px-2.5 py-1 text-xs font-bold backdrop-blur',
                            'bg-white/90 text-forest' => $product->in_stock,
                            'bg-cocoa/85 text-white' => ! $product->in_stock,
                        ])>{{ $product->in_stock ? 'In stock' : 'Sold out' }}</span>
                    </a>
                    <div class="flex flex-1 flex-col p-3 sm:p-4">
                        <p class="text-xs font-bold tracking-wide text-ink/50 uppercase">{{ $product->category->name }}</p>
                        <h2 class="mt-1 truncate font-sans text-base font-bold text-cocoa sm:text-lg">{{ $product->name }}</h2>
                        <p class="mt-0.5 text-lg text-forest">{{ $product->formattedPrice() }}</p>
                        <div class="mt-auto flex items-center gap-2 pt-3">
                            <a href="{{ route('admin.products.edit', $product) }}" class="flex-1 rounded-full border border-forest py-2 text-center text-sm font-medium text-forest transition hover:bg-forest hover:text-white">Edit</a>
                            <a href="{{ route('product', $product) }}" target="_blank" rel="noopener" class="flex size-9 shrink-0 items-center justify-center rounded-full text-ink/50 transition hover:bg-mist hover:text-ink" aria-label="View {{ $product->name }} in the shop" title="View in shop">
                                <svg class="size-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            </a>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-8">{{ $products->links() }}</div>
    @endif
</x-layouts.admin>

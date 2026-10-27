@php($heading = $product->exists ? 'Edit '.$product->name : 'Add product')

<x-layouts.admin :title="$heading">
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 text-sm text-ink/70 hover:text-forest">
        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        All products
    </a>

    <div class="mt-3 flex flex-wrap items-end justify-between gap-4">
        <h1 class="font-display text-[36px] text-cocoa">{{ $heading }}</h1>
        @if ($product->exists)
            <a href="{{ route('product', $product) }}" target="_blank" rel="noopener" class="text-sm font-medium text-forest underline-offset-4 hover:underline">View in shop &nearr;</a>
        @endif
    </div>

    <form method="POST" enctype="multipart/form-data"
          action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
          class="mt-6">
        @csrf
        @if ($product->exists)
            @method('PUT')
        @endif
        @include('partials.form-errors')

        <div class="grid items-start gap-5 lg:grid-cols-[minmax(0,1fr)_360px]">
            <section class="rounded-2xl border border-black/8 bg-white p-5 sm:p-7">
                <h2 class="font-sans text-lg font-bold text-cocoa">Details</h2>

                <div class="mt-5 space-y-5">
                    <div>
                        <label class="ff-label" for="name">Name<span class="ff-required">*</span></label>
                        <input class="ff-input" type="text" id="name" name="name" value="{{ old('name', $product->name) }}" placeholder="e.g. Ankara Wrap Dress" required>
                    </div>

                    <div>
                        <label class="ff-label" for="summary">Short summary</label>
                        <textarea class="ff-input min-h-[110px]" id="summary" name="summary" placeholder="One or two sentences shown next to the photo.">{{ old('summary', $product->summary) }}</textarea>
                    </div>

                    <div>
                        <label class="ff-label" for="description">Description</label>
                        <textarea class="ff-input min-h-[220px]" id="description" name="description" placeholder="Fabric, fit, sizing and care details for the Description tab.">{{ old('description', $product->description) }}</textarea>
                        <p class="mt-1.5 text-xs text-ink/60">HTML such as &lt;p&gt; and &lt;strong&gt; is allowed in both fields.</p>
                    </div>
                </div>
            </section>

            <div class="space-y-5">
                <section class="rounded-2xl border border-black/8 bg-white p-5">
                    <h2 class="font-sans text-lg font-bold text-cocoa">Photo @unless ($product->exists)<span class="ff-required">*</span>@endunless</h2>

                    <label data-dropzone class="mt-4 block cursor-pointer overflow-hidden rounded-xl border-2 border-dashed border-black/15 bg-mist transition hover:border-forest data-dragging:border-forest data-dragging:bg-forest/5">
                        <img data-image-preview src="{{ $product->image ? asset($product->image) : '' }}" alt="" @class(['aspect-[4/5] w-full object-cover', 'hidden' => ! $product->image])>
                        <span data-image-empty @class(['flex aspect-[4/5] flex-col items-center justify-center gap-2 px-6 text-center', 'hidden' => $product->image])>
                            <svg class="size-10 text-ink/30" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            <span class="text-[15px] font-bold text-cocoa">Drop a photo here or click to choose</span>
                            <span class="text-xs text-ink/60">JPG, PNG or WebP, up to 10 MB</span>
                        </span>
                        <input data-image-input class="sr-only" type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp" @required(! $product->exists)>
                    </label>
                    <p data-image-name class="mt-2 truncate text-xs text-ink/60">{{ $product->exists ? 'Click the photo to replace it.' : '' }}</p>
                </section>

                <section class="rounded-2xl border border-black/8 bg-white p-5">
                    <h2 class="font-sans text-lg font-bold text-cocoa">Pricing &amp; category</h2>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="ff-label" for="price">Price<span class="ff-required">*</span></label>
                            <div class="relative">
                                <span class="pointer-events-none absolute top-1/2 left-[15px] -translate-y-1/2 text-ink/50">$</span>
                                <input class="ff-input pl-8" type="number" id="price" name="price" min="0.50" step="0.01" inputmode="decimal" placeholder="0.00"
                                       value="{{ old('price', $product->price_cents !== null ? number_format($product->price_cents / 100, 2, '.', '') : '') }}" required>
                            </div>
                        </div>

                        <div>
                            <label class="ff-label" for="category_id">Category<span class="ff-required">*</span></label>
                            <select class="ff-input" id="category_id" name="category_id" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected((int) old('category_id', $product->category_id) === $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </section>

                <section class="rounded-2xl border border-black/8 bg-white p-5">
                    <label class="flex cursor-pointer items-center justify-between gap-4">
                        <span>
                            <span class="block font-sans text-lg font-bold text-cocoa">In stock</span>
                            <span class="block text-xs text-ink/60">Turn off to show this piece as sold out.</span>
                        </span>
                        <input type="checkbox" name="in_stock" value="1" class="peer sr-only" @checked(old('in_stock', $product->in_stock))>
                        <span class="relative h-7 w-12 shrink-0 rounded-full bg-black/15 transition peer-checked:bg-forest peer-focus-visible:ring-2 peer-focus-visible:ring-forest peer-focus-visible:ring-offset-2 after:absolute after:top-1 after:left-1 after:size-5 after:rounded-full after:bg-white after:shadow after:transition peer-checked:after:translate-x-5" aria-hidden="true"></span>
                    </label>
                </section>

                <div class="flex gap-3">
                    <button class="btn-primary flex-1">{{ $product->exists ? 'Save changes' : 'Add product' }}</button>
                    <a href="{{ route('admin.products.index') }}" class="btn-outline">Cancel</a>
                </div>
            </div>
        </div>
    </form>

    @if ($product->exists)
        <section class="mt-10 flex flex-col gap-4 rounded-2xl border border-[#e2401c]/25 bg-white p-5 sm:flex-row sm:items-center sm:justify-between sm:p-7">
            <div>
                <h2 class="font-sans text-lg font-bold text-cocoa">Delete this product</h2>
                <p class="mt-1 text-sm text-ink/70">It disappears from the shop straight away. Past orders keep their own copy of the name and price.</p>
            </div>
            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button class="btn shrink-0 border border-[#e2401c] text-[#e2401c] hover:bg-[#e2401c] hover:text-white">Delete product</button>
            </form>
        </section>
    @endif
</x-layouts.admin>

<x-layouts.app :title="$product->name" :description="strip_tags($product->summary)" header="brown">
    <section class="bg-umber pt-[30px] pb-[18px] lg:pt-[52px]">
        <h1 class="text-center font-system text-[32px] leading-[1.2] font-bold text-white">Product</h1>
    </section>

    <section class="bg-cream font-system text-base leading-normal text-ink">
        <div class="mx-auto max-w-[1340px] px-[10px] pt-[50px] pb-[100px] lg:px-0">
            <div class="grid gap-10 lg:grid-cols-2">
                <div class="flex items-start justify-center">
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="aspect-square w-full max-w-[325px] object-cover">
                </div>

                <div>
                    <nav class="text-[15px] text-[#767676]" aria-label="Breadcrumb">
                        <a href="{{ route('home') }}" class="hover:text-ink">Home</a>&nbsp;/&nbsp;<a href="{{ route('shop', ['categories' => [$product->category->slug]]) }}" class="hover:text-ink">{{ $product->category->name }}</a>&nbsp;/&nbsp;{{ $product->name }}
                    </nav>
                    <h2 class="mt-[30px] text-[40px] lg:text-[55px]">{{ $product->name }}</h2>
                    @if ($product->summary)
                        <div class="mt-[30px] space-y-[14px] [&_p]:leading-6">{!! $product->summary !!}</div>
                    @endif
                    <p class="mt-[45px] text-[20px] font-bold text-black">{{ $product->formattedPrice() }}</p>

                    @if ($product->in_stock)
                        <form method="POST" action="{{ route('cart.store', $product) }}" class="mt-[30px] flex items-center gap-[14px]">
                            @csrf
                            <label class="sr-only" for="quantity">{{ $product->name }} quantity</label>
                            <input class="h-[38px] w-[58px] border border-forest bg-transparent text-center text-base text-ink outline-none" type="number" id="quantity" name="quantity" value="1" min="1" max="20">
                            <button class="rounded-[50px] bg-forest px-[17px] py-[9px] font-sans text-base leading-5 font-bold text-white hover:bg-forest-dark">Add to cart</button>
                        </form>
                    @else
                        <p class="mt-[30px] font-bold text-[#a00]">Out of stock</p>
                    @endif
                </div>
            </div>

            {{-- WooCommerce-style tabs --}}
            <div class="mt-[100px]" data-tabs>
                <ul class="flex gap-0 border-b border-[#d3ced2] pl-[11px]" role="tablist">
                    <li>
                        <button type="button" role="tab" data-tab="description" aria-selected="true" class="-mb-px rounded-t border border-[#d3ced2] bg-[#ebe9eb] px-5 py-[9px] font-bold text-[#515151] aria-selected:border-b-white aria-selected:bg-white">Description</button>
                    </li>
                    <li>
                        <button type="button" role="tab" data-tab="reviews" aria-selected="false" class="-mb-px rounded-t border border-[#d3ced2] bg-[#ebe9eb] px-4 py-[9px] font-bold text-[#515151] aria-selected:border-b-white aria-selected:bg-white">Reviews ({{ $product->reviews->count() }})</button>
                    </li>
                </ul>

                <div role="tabpanel" data-panel="description" class="px-5 pt-[30px]">
                    <h2 class="font-system text-[30px] leading-[1.2] text-ink">Description</h2>
                    <div class="mt-5 space-y-[14px]">{!! $product->description ?? '' !!}</div>
                </div>

                <div role="tabpanel" data-panel="reviews" id="reviews" class="hidden px-5 pt-[30px]">
                    <h2 class="font-system text-[30px] leading-[1.2] text-ink">Reviews</h2>
                    @forelse ($product->reviews as $review)
                        <div class="mt-6 border-b border-[#e4e1e3] pb-6">
                            <p class="text-gold" aria-label="Rated {{ $review->rating }} out of 5">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</p>
                            <p class="mt-1"><strong>{{ $review->author }}</strong> <span class="text-[#767676]">– {{ $review->created_at->format('F j, Y') }}</span></p>
                            <p class="mt-2">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <p class="mt-5">There are no reviews yet.</p>
                    @endforelse

                    <form method="POST" action="{{ route('reviews.store', $product) }}" class="mt-8 max-w-[800px] space-y-4">
                        @csrf
                        <h3 class="font-system text-[20px] leading-[1.2] text-ink">{{ $product->reviews->isEmpty() ? 'Be the first to review “'.$product->name.'”' : 'Add a review' }}</h3>
                        <p>Your email address will not be published. Required fields are marked <span class="text-[#a00]">*</span></p>
                        @include('partials.form-errors')
                        <div>
                            <label class="block" for="rating">Your rating&nbsp;<span class="text-[#a00]">*</span></label>
                            <select class="mt-1 border border-[#ccc] bg-white px-3 py-2" id="rating" name="rating" required>
                                <option value="">Rate&hellip;</option>
                                @foreach ([5 => 'Perfect', 4 => 'Good', 3 => 'Average', 2 => 'Not that bad', 1 => 'Very poor'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('rating') == $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block" for="comment">Your review&nbsp;<span class="text-[#a00]">*</span></label>
                            <textarea class="mt-1 block w-full border border-[#ccc] bg-white p-3" id="comment" name="comment" rows="8" required>{{ old('comment') }}</textarea>
                        </div>
                        <div>
                            <label class="block" for="author">Name&nbsp;<span class="text-[#a00]">*</span></label>
                            <input class="mt-1 block w-full border border-[#ccc] bg-white p-3" id="author" name="author" value="{{ old('author', auth()->user()?->name) }}" required>
                        </div>
                        <div>
                            <label class="block" for="review_email">Email&nbsp;<span class="text-[#a00]">*</span></label>
                            <input class="mt-1 block w-full border border-[#ccc] bg-white p-3" type="email" id="review_email" name="email" value="{{ old('email', auth()->user()?->email) }}" required>
                        </div>
                        <button class="woo-button">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

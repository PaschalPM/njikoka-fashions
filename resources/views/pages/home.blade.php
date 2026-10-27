<x-layouts.app>
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-cocoa text-white">
        <img src="{{ asset('images/site/Frame-2147226244.png') }}" alt="" class="absolute inset-0 size-full object-cover object-[70%_center] lg:object-left">
        <div class="absolute inset-0 bg-black/40 lg:bg-black/[0.38]"></div>
        <div class="relative flex min-h-[390px] flex-col justify-end px-[10px] pt-[160px] pb-5 lg:min-h-[1028px] lg:px-0 lg:pt-[300px] lg:pb-[50px]">
            <div class="text-center lg:max-w-[720px] lg:pl-[10px] lg:text-left">
                <h1 class="text-[26px] leading-[26px] text-white lg:text-[70px] lg:leading-[70px]">Fashion Made to Celebrate Culture, Color, and Confidence</h1>
                <p class="mx-auto mt-5 max-w-[500px] text-base leading-6 text-white lg:mx-0 lg:mt-[30px]">Premium tropical-inspired styles for adults and children designed for everyday elegance, special occasions, and unforgettable personal expression.</p>
                <div class="mt-[30px] flex justify-center gap-[10px] lg:justify-start lg:gap-5 lg:pl-[10px]">
                    <a href="{{ route('shop') }}" class="btn-primary shadow-[0_0_10px_rgba(0,0,0,0.5)] max-lg:text-sm">Shop Collection</a>
                    <a href="{{ route('book-appointment') }}" class="btn-glass max-lg:text-sm">Book Consultation</a>
                </div>
            </div>
            <ul class="mt-auto hidden flex-wrap gap-5 pt-[288px] pl-[10px] lg:-mb-12 lg:flex">
                @foreach (['Tropical fabrics', 'Adult styles', 'Children’s fashion', 'Custom designs'] as $tag)
                    <li class="btn-glass">{{ $tag }}</li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- About --}}
    <section class="bg-cream py-[30px] lg:pt-[70px] lg:pb-[70px]">
        <div class="container-site">
            <div class="flex flex-col items-center gap-[10px] text-center lg:flex-row lg:items-end lg:justify-between lg:text-left">
                <h2 class="section-title lg:max-w-[680px]">Designed for People Who Love to Stand Out Beautifully</h2>
                <a href="{{ route('about') }}" class="btn-outline">Learn More About Njikoka</a>
            </div>

            <div class="mt-5 grid gap-[10px] lg:mt-[60px] lg:grid-cols-3 lg:gap-10">
                <div class="space-y-5 lg:space-y-[30px]">
                    <img src="{{ asset('images/site/Rectangle-14.png') }}" alt="" class="w-full rounded-[25px] object-cover">
                    <div class="space-y-[14px] text-center text-base leading-6 lg:text-left lg:text-[18px] lg:leading-[27px]">
                        <p>Njikoka Fashions creates elegant styles using tropical fabrics, expressive patterns, and thoughtful design. Each piece is made to help adults and children feel confident, comfortable, and beautifully dressed for life’s everyday and special moments.</p>
                        <p>From bold prints to polished silhouettes, our fashion is created for people who want clothing with personality, meaning, and presence.</p>
                    </div>
                </div>
                <img src="{{ asset('images/site/Rectangle-15.png') }}" alt="" class="w-full rounded-[25px] object-cover lg:h-[634px]">
                <div class="flex flex-col-reverse gap-5 lg:flex-col lg:pt-[80px]">
                    @include('partials.checklist')
                    <img src="{{ asset('images/site/Rectangle-16.png') }}" alt="" class="w-full rounded-[25px] object-cover">
                </div>
            </div>
        </div>
    </section>

    {{-- Find your perfect look --}}
    <section class="bg-linen py-[30px] lg:pt-[70px] lg:pb-[70px]">
        <div class="container-site grid gap-[10px] lg:grid-cols-[minmax(0,477fr)_minmax(0,477fr)_minmax(0,406fr)] lg:gap-x-[20px] lg:gap-y-0">
            <div class="text-center lg:col-span-2 lg:pb-[10px] lg:text-left">
                <h2 class="section-title">Find Your Perfect Look</h2>
                <p class="mx-auto mt-5 max-w-[400px] text-base leading-6 lg:mx-0 lg:mt-[30px] lg:text-[18px] lg:leading-[27px]">Explore tropical-inspired fashion made for adults, children, custom style moments, and family expression.</p>
            </div>

            @foreach ([
                ['Custom Designs', 'Bring your fashion idea to life with custom pieces made around your vision.', 'Custom Design', route('custom-designs'), 'Frame-1948754955-1.png', 'lg:row-start-2 lg:col-start-1 lg:min-h-[526px]'],
                ['Children’s fashion', 'Elegant tropical fashion for personal style, events, and everyday confidence.', 'Shop Adults', route('shop'), 'Frame-1948754956-1.png', 'lg:row-start-2 lg:col-start-2 lg:min-h-[660px]'],
                ['Adults Collection', 'Elegant tropical fashion for personal style, events, and everyday confidence.', 'Shop Adults', route('shop'), 'Frame-1948754950.png', 'lg:row-span-2 lg:row-start-1 lg:col-start-3 lg:ml-5 lg:min-h-[739px]'],
            ] as [$heading, $copy, $cta, $url, $image, $placement])
                <div class="relative flex min-h-[350px] flex-col justify-end self-end overflow-hidden rounded-[25px] p-[15px] lg:p-5 lg:pb-[30px] {{ $placement }}">
                    <img src="{{ asset('images/site/'.$image) }}" alt="" loading="lazy" class="absolute inset-0 size-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#2b1d18]/90 via-[#2b1d18]/20 to-transparent"></div>
                    <div class="relative flex flex-col items-center gap-[10px] text-center lg:items-start lg:gap-3 lg:text-left">
                        <h3 class="text-[25px] leading-[25px] text-cream">{{ $heading }}</h3>
                        <div class="flex w-full flex-col items-center gap-[10px] lg:flex-row lg:items-end lg:justify-between">
                            <p class="text-[15px] leading-[22px] text-white lg:max-w-[200px]">{{ $copy }}</p>
                            <a href="{{ $url }}" class="btn-glass shrink-0 px-[15px]">{{ $cta }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Adults collection: tabs on desktop, accordion on mobile --}}
    <section class="bg-[#f8f4ee] py-[30px] lg:pt-[70px] lg:pb-[70px]">
        <div class="container-site" data-tabs>
            <div class="flex flex-col items-center gap-5 text-center lg:flex-row lg:items-start lg:justify-between lg:text-left">
                <h2 class="section-title">Adults Collection</h2>
                <p class="text-base leading-6 lg:max-w-[352px] lg:text-[18px] lg:leading-[27px]">From bold everyday pieces to polished event-ready looks, Njikok Fashions brings color, culture, and elegance into every design.</p>
            </div>

            <div class="mt-[60px] hidden lg:flex" role="tablist">
                @foreach ($categories as $category)
                    <button type="button" role="tab" data-tab="{{ $category->slug }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                        class="bg-white/[0.13] px-[30px] py-3 font-system text-base text-black aria-selected:bg-forest aria-selected:text-white">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <div class="mt-[30px] lg:mt-0">
                @foreach ($categories as $category)
                    <button type="button" data-tab="{{ $category->slug }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                        class="block w-full bg-white/[0.13] px-[30px] py-3 text-center font-system text-base text-black aria-selected:bg-forest aria-selected:text-white lg:hidden">
                        {{ $category->name }}
                    </button>

                    <div data-panel="{{ $category->slug }}" role="tabpanel" @class(['relative py-[30px] lg:pt-[60px] lg:pb-0', 'hidden' => ! $loop->first]) data-carousel>
                        <div data-track class="flex snap-x snap-mandatory gap-[10px] overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                            @foreach ($category->products as $product)
                                <div class="w-full shrink-0 snap-start lg:w-[calc((100%-30px)/4)]">
                                    <x-product-card :product="$product" image-class="h-[384px]" />
                                </div>
                            @endforeach
                        </div>
                        <button type="button" data-prev class="absolute top-[40%] left-[5px] text-ink/30 hover:text-ink lg:left-[10px]" aria-label="Previous products">
                            <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                        </button>
                        <button type="button" data-next class="absolute top-[40%] right-[5px] text-ink/30 hover:text-ink lg:right-[10px]" aria-label="Next products">
                            <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                        </button>
                    </div>
                @endforeach
            </div>

            <div class="mt-5 flex flex-col items-center gap-5 text-center lg:mt-[60px] lg:flex-row lg:justify-between lg:text-left">
                <p class="text-base leading-6 lg:max-w-[370px] lg:text-[18px] lg:leading-[27px]">Explore tropical-inspired fashion designed for adults, children, family moments, and custom occasions.</p>
                <a href="{{ route('shop') }}" class="btn bg-cocoa text-white hover:bg-black">Visit Our Shop</a>
            </div>
        </div>
    </section>

    {{-- Values --}}
    <section class="bg-linen py-[30px] lg:pt-[100px] lg:pb-[90px]">
        <div class="container-site">
            <div class="flex flex-col items-center gap-[10px] text-center lg:flex-row lg:items-start lg:justify-between lg:text-left">
                <h2 class="section-title lg:max-w-[560px]">Fashion with Meaning, Beauty, and Presence</h2>
                <p class="text-base leading-6 lg:mt-[10px] lg:max-w-[352px] lg:text-[18px] lg:leading-[27px]">Njikoka Fashions is made for people who want more than clothing — they want style that feels expressive, elegant, and personal.</p>
            </div>
            <div class="mt-[30px] grid gap-[10px] lg:mt-[50px] lg:grid-cols-4 lg:gap-0">
                @foreach ([
                    ['Group-2.png', 'Tropical Fabric Inspiration', 'Rich prints, warm colors, and expressive patterns give every piece a vibrant signature look.'],
                    ['Frame-7.png', 'Styles for Adults & Children', 'Elegant fashion options for adults, children, and families who want coordinated, memorable style.'],
                    ['Frame-9.png', 'Custom Fashion Support', 'Book a consultation to create a personalized piece designed around your occasion, fit, and vision.'],
                    ['Group-3.png', 'Made to Stand Out', 'Each look is created to help you feel confident, polished, and beautifully dressed for life’s special moments.'],
                ] as [$icon, $heading, $copy])
                    <div class="flex min-h-[250px] flex-col items-center justify-between border border-sand p-[15px] text-center transition hover:bg-white lg:min-h-[350px] lg:items-start lg:border-y-0 lg:border-l-0 lg:p-5 lg:pl-[30px] lg:text-left lg:first:pl-[10px]">
                        <img src="{{ asset('images/site/'.$icon) }}" alt="" class="h-10 w-auto object-contain">
                        <div>
                            <h3 class="text-[20px] leading-[20px] lg:text-[30px] lg:leading-[30px]">{{ $heading }}</h3>
                            <p class="mt-[10px] text-base leading-6 lg:mt-[22px] lg:text-[18px] lg:leading-[27px]">{{ $copy }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Signature look + custom services --}}
    <section class="bg-cream lg:flex">
        <div class="relative flex min-h-[400px] flex-col justify-end overflow-hidden p-5 lg:min-h-[1203px] lg:w-[48.54%] lg:p-[30px]">
            <img src="{{ asset('images/site/Frame-2147226021.png') }}" alt="" loading="lazy" class="absolute inset-0 size-full object-cover">
            <div class="relative flex flex-col items-center gap-[10px] rounded-[35px] border border-white/60 bg-[#6b605a]/75 p-[15px] text-center backdrop-blur-[2px] lg:flex-row lg:items-end lg:justify-between lg:gap-5 lg:p-[30px] lg:text-left">
                <div class="lg:max-w-[340px]">
                    <h2 class="text-[26px] leading-[26px] text-cream lg:text-[35px] lg:leading-[35px]">Let’s Create Your Signature Look</h2>
                    <p class="mt-[10px] text-base leading-6 text-white lg:mt-5 lg:text-[18px] lg:leading-[27px]">Schedule a consultation and share your style idea, fabric inspiration, or occasion details.</p>
                </div>
                <a href="{{ route('book-appointment') }}" class="btn-primary shrink-0 lg:w-[180px]">Book Consultation</a>
            </div>
        </div>

        <div class="px-[10px] py-[30px] text-center lg:flex lg:w-[51.46%] lg:flex-col lg:justify-center lg:pt-[100px] lg:pr-[50px] lg:pb-[100px] lg:pl-[70px] lg:text-left">
            <h2 class="text-[26px] leading-[26px] lg:max-w-[620px] lg:text-[70px] lg:leading-[70px]">Need Something Made Just for You?</h2>
            <p class="mx-auto mt-[10px] text-base leading-6 lg:mx-0 lg:mt-[30px] lg:max-w-[420px] lg:text-[18px] lg:leading-[27px]">Book a consultation for custom fashion, family looks, children’s pieces, event outfits, or personal style guidance.</p>
            <div class="mt-[10px] lg:mt-[30px]">
                @foreach ([
                    ['Custom Adult Fashion', 'Personalized tropical-inspired pieces designed around your occasion, style, and fit.'],
                    ['Children’s Custom Pieces', 'Colorful, comfortable fashion for children with personality, movement, and charm.'],
                    ['Family Matching Looks', 'Styles for family photos, celebrations, cultural moments, and special events.'],
                    ['Event & Occasion Wear', 'Fashion for birthdays, gatherings, ceremonies, and memorable personal milestones.'],
                ] as [$heading, $copy])
                    <div class="border-b border-sand px-[10px] pt-[10px] pb-[10px] lg:pt-5 lg:pb-[13px]">
                        <h3 class="text-[26px] leading-[26px] lg:text-[30px] lg:leading-[30px]">{{ $heading }}</h3>
                        <p class="mt-[10px] text-base leading-6 lg:mt-5 lg:text-[18px] lg:leading-[27px]">{{ $copy }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="relative overflow-hidden bg-cocoa text-white">
        <img src="{{ asset('images/site/Frame-2147226136.png') }}" alt="" loading="lazy" class="absolute inset-0 size-full object-cover object-[70%_center]">
        <div class="absolute inset-0 bg-[#2b1d18]/70 lg:bg-transparent lg:bg-gradient-to-r lg:from-[#2b1d18]/85 lg:via-[#2b1d18]/35 lg:via-40% lg:to-transparent lg:to-75%"></div>
        <div class="relative container-site flex min-h-[370px] flex-col items-center justify-center gap-5 py-5 text-center lg:min-h-[800px] lg:flex-row lg:items-center lg:justify-between lg:pt-[300px] lg:pb-[50px] lg:text-left">
            <div class="flex flex-col items-center lg:items-start">
                <h2 class="text-[26px] leading-[26px] text-cream lg:max-w-[445px] lg:text-[55px] lg:leading-[55px]">Ready to Find Your Signature Look?</h2>
                <p class="mt-[10px] text-base leading-6 text-white lg:mt-[30px] lg:max-w-[360px] lg:text-[18px] lg:leading-[27px]">Explore the collection or book a consultation for a custom design made just for you.</p>
                <a href="{{ route('about') }}" class="btn-outline mt-5 lg:hidden">Learn More About Njikoka</a>
                <div class="mt-5 flex gap-[10px] lg:mt-[50px] lg:-ml-[10px] lg:gap-5">
                    <a href="{{ route('shop') }}" class="btn-primary max-lg:text-sm">Shop Collection</a>
                    <a href="{{ route('book-appointment') }}" class="btn-dark max-lg:text-sm">Book Consultation</a>
                </div>
            </div>
            <a href="{{ route('about') }}" class="btn-outline hidden lg:inline-flex">Learn More About Njikoka</a>
        </div>
    </section>
</x-layouts.app>

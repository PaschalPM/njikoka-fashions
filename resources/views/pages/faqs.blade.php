<x-layouts.app title="FAQs" body-class="bg-mist">
    <x-page-hero title="FAQs" />

    <section class="bg-mist py-[30px] lg:py-[60px]">
        <div class="container-site lg:px-[80px]">
            @foreach ([
                'What is your return policy?' => 'You can return unworn items within 30 days for a full refund. Please ensure that tags are attached.',
                'How do I care for my clothes?' => 'We recommend following the instructions on the garment label. Typically, machine wash cold and tumble dry low is best.',
                'What sizes do you offer?' => 'Our clothing ranges from XS to XXXL. Check our size guide for specific measurements to find your best fit.',
                'Do you ship internationally?' => 'Yes, we ship to select countries. During checkout, ensure your address is eligible for international shipping.',
                'Can I track my order?' => 'Absolutely! Once your order has shipped, you’ll receive a tracking link to monitor its progress.',
                'What materials are used?' => 'We use high-quality fabrics, primarily cotton and linen, chosen for comfort and durability. Each product listing details the material used.',
            ] as $question => $answer)
                <details class="group border-b border-ink">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-[27px] font-sans text-[18px] leading-[27px] font-bold text-ink [&::-webkit-details-marker]:hidden">
                        {{ $question }}
                        <svg class="mr-6 size-4 shrink-0 text-cocoa transition group-open:rotate-180" fill="currentColor" viewBox="0 0 448 512" aria-hidden="true"><path d="M201.4 374.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 306.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z"/></svg>
                    </summary>
                    <p class="pb-[27px] text-base leading-6">{{ $answer }}</p>
                </details>
            @endforeach
        </div>
    </section>
</x-layouts.app>

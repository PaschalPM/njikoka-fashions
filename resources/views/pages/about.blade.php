<x-layouts.app title="About">
    <x-page-hero title="About" />

    <section class="bg-cream py-[30px] lg:pt-[70px] lg:pb-[70px]">
        <div class="container-site">
            <h2 class="section-title text-center lg:max-w-[680px] lg:text-left">Designed for People Who Love to Stand Out Beautifully</h2>

            <div class="mt-5 grid items-center gap-5 lg:mt-[60px] lg:grid-cols-[540px_1fr] lg:gap-10">
                <img src="{{ asset('images/site/Rectangle-14.png') }}" alt="" class="w-full rounded-[25px] object-cover lg:h-[414px]">

                <div class="text-center lg:text-left">
                    <div class="space-y-[14px] text-base leading-6 lg:text-[18px] lg:leading-[27px]">
                        <p>Njikoka Fashions creates elegant styles using tropical fabrics, expressive patterns, and thoughtful design. Each piece is made to help adults and children feel confident, comfortable, and beautifully dressed for life’s everyday and special moments.</p>
                        <p>From bold prints to polished silhouettes, our fashion is created for people who want clothing with personality, meaning, and presence.</p>
                    </div>
                    @include('partials.checklist', ['class' => 'mt-[30px] inline-block lg:block'])
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

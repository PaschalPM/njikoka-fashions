@props(['title'])

{{-- 450px banner with the family photo; the overlay header sits on top of it. --}}
<section class="relative flex h-[300px] items-center justify-center overflow-hidden bg-cocoa pt-[100px] lg:h-[450px] lg:pt-0">
    <img src="{{ asset('images/site/Frame-2147226244.png') }}" alt="" class="absolute inset-0 size-full object-cover object-top">
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/25 to-black/10"></div>
    <h1 class="relative px-4 text-center text-[36px] text-white lg:mt-10 lg:text-[55px]">{{ $title }}</h1>
</section>

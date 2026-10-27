@php
    $checklist = [
        'Tropical-inspired fabrics with rich visual character',
        'Fashion for adults, children, and families',
        'Custom design options for personal occasions',
        'Looks for events, lifestyle wear, and cultural moments',
    ];
@endphp

<ul class="space-y-[10px] {{ $class ?? '' }}">
    @foreach ($checklist as $point)
        <li class="flex items-center gap-[7px] text-left text-base leading-6 lg:text-[18px] lg:leading-[27px]">
            <svg class="size-[14px] shrink-0 text-forest" fill="currentColor" viewBox="0 0 512 512" aria-hidden="true"><path d="M173.9 439.4l-166.4-166.4c-10-10-10-26.2 0-36.2l36.2-36.2c10-10 26.2-10 36.2 0L192 312.7 432.1 72.6c10-10 26.2-10 36.2 0l36.2 36.2c10 10 10 26.2 0 36.2L210.1 439.4c-10 10-26.2 10-36.2 0z"/></svg>
            <span>{{ $point }}</span>
        </li>
    @endforeach
</ul>

@php
    $phones = config('store.phones');
    $icon = 'size-5 shrink-0 text-forest';
@endphp

<ul class="flex flex-col gap-3 {{ $class ?? '' }}">
    <li class="flex items-center gap-[10px]">
        <svg class="{{ $icon }}" fill="currentColor" viewBox="0 0 512 512" aria-hidden="true"><path d="M64 112c-8.8 0-16 7.2-16 16v22.1l172.5 141.6c20.7 17 50.4 17 71.1 0L464 150.1V128c0-8.8-7.2-16-16-16H64zM48 212.2V384c0 8.8 7.2 16 16 16h384c8.8 0 16-7.2 16-16V212.2L322 328.8c-38.4 31.5-93.7 31.5-132 0L48 212.2zM0 128c0-35.3 28.7-64 64-64h384c35.3 0 64 28.7 64 64v256c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z"/></svg>
        <a class="hover:text-forest" href="mailto:{{ config('store.email') }}">{{ config('store.email') }}</a>
    </li>
    @isset($phones[0])
        <li class="flex items-center gap-[10px]">
            <svg class="{{ $icon }}" fill="currentColor" viewBox="0 0 512 512" aria-hidden="true"><path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64c0 247.4 200.6 448 448 448 18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368c-70.4-33.3-127.4-90.3-160.7-160.7l49.3-40.3c13.7-11.2 18.4-30 11.6-46.3l-40-96z"/></svg>
            <a class="hover:text-forest" href="tel:{{ preg_replace('/\D/', '', $phones[0]) }}">{{ $phones[0] }}</a>
        </li>
    @endisset
    @isset($phones[1])
        <li class="flex items-center gap-[10px]">
            <svg class="{{ $icon }}" fill="currentColor" viewBox="0 0 512 512" aria-hidden="true"><path d="M128 64v96h64V64h138.7l29.3 29.3V160h64V93.3c0-17-6.7-33.3-18.7-45.3L376 18.7C364 6.7 347.7 0 330.7 0H192c-35.3 0-64 28.7-64 64zM0 160v320c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32V160c0-17.7-14.3-32-32-32H32c-17.7 0-32 14.3-32 32zm480 32H128v288c0 17.7 14.3 32 32 32h320c17.7 0 32-14.3 32-32V224c0-17.7-14.3-32-32-32zM256 256a32 32 0 1 1 0 64 32 32 0 1 1 0-64zm96 32a32 32 0 1 1 64 0 32 32 0 1 1-64 0zm32 96a32 32 0 1 1 0 64 32 32 0 1 1 0-64zm-160 32a32 32 0 1 1 64 0 32 32 0 1 1-64 0z"/></svg>
            <a class="hover:text-forest" href="tel:{{ preg_replace('/\D/', '', $phones[1]) }}">{{ $phones[1] }}</a>
        </li>
    @endisset
    <li class="flex items-center gap-[10px]">
        <svg class="{{ $icon }}" fill="currentColor" viewBox="0 0 384 512" aria-hidden="true"><path d="M215.7 499.2C267 435 384 279.4 384 192 384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2 12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>
        <span>{{ config('store.address') }}</span>
    </li>
</ul>

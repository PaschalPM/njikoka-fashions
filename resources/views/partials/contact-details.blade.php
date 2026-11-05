@php($icon = 'size-5 shrink-0 text-forest')

<ul class="flex flex-col gap-3 {{ $class ?? '' }}">
    <li class="flex items-center gap-[10px]">
        <svg class="{{ $icon }}" fill="currentColor" viewBox="0 0 512 512" aria-hidden="true"><path d="M64 112c-8.8 0-16 7.2-16 16v22.1l172.5 141.6c20.7 17 50.4 17 71.1 0L464 150.1V128c0-8.8-7.2-16-16-16H64zM48 212.2V384c0 8.8 7.2 16 16 16h384c8.8 0 16-7.2 16-16V212.2L322 328.8c-38.4 31.5-93.7 31.5-132 0L48 212.2zM0 128c0-35.3 28.7-64 64-64h384c35.3 0 64 28.7 64 64v256c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z"/></svg>
        <a class="hover:text-forest" href="mailto:{{ config('store.email') }}">{{ config('store.email') }}</a>
    </li>
    @foreach ($whatsappContacts as $contact)
        <li class="flex items-center gap-[10px]">
            @include('partials.icons.whatsapp', ['class' => $icon])
            <a class="hover:text-forest" href="{{ route('whatsapp.chat', $contact->number) }}" target="_blank" rel="nofollow noopener">{{ $contact->label }}: {{ $contact->displayNumber() }}</a>
        </li>
    @endforeach
    <li class="flex items-center gap-[10px]">
        <svg class="{{ $icon }}" fill="currentColor" viewBox="0 0 384 512" aria-hidden="true"><path d="M215.7 499.2C267 435 384 279.4 384 192 384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2 12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>
        <span>{{ config('store.address') }}</span>
    </li>
</ul>

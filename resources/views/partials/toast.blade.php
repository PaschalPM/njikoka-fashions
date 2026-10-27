{{-- Flash messages pop up bottom-right; resources/js/app.js closes them and adds new ones from [data-toast-template]. --}}
<div data-toast-region class="pointer-events-none fixed inset-x-4 {{ $offset ?? 'bottom-4' }} z-50 flex flex-col items-end gap-3 sm:left-auto sm:w-[400px]" aria-live="polite">
    @if (session('status'))
        @include('partials.toast-item', ['message' => session('status')])
    @endif
</div>

<template data-toast-template>
    @include('partials.toast-item', ['message' => ''])
</template>

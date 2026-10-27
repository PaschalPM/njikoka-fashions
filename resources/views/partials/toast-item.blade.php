<div data-toast role="status" class="pointer-events-auto flex w-full items-start gap-3 rounded-xl bg-forest py-3 pr-2 pl-4 text-white shadow-[0_10px_30px_rgba(0,0,0,0.2)] transition duration-300 data-leaving:translate-y-2 data-leaving:opacity-0">
    <svg class="mt-0.5 size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p data-toast-message class="flex-1 font-sans text-[15px] leading-snug font-bold">{{ $message }}</p>
    <button type="button" data-toast-close class="-my-1 flex size-8 shrink-0 items-center justify-center rounded-full text-white/80 transition hover:bg-white/15 hover:text-white" aria-label="Dismiss message">
        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>

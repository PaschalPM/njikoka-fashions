{{-- Coloured pill for a follow-up status; pass the stored $status and its $label. --}}
<span @class([
    'inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold',
    'bg-gold/20 text-[#8a6420]' => $status === 'new',
    'bg-sky-100 text-sky-800' => in_array($status, ['contacted', 'confirmed']),
    'bg-forest/10 text-forest' => $status === 'in_progress',
    'bg-cocoa/10 text-cocoa' => $status === 'completed',
    'bg-black/5 text-ink/60' => in_array($status, ['declined', 'cancelled']),
])>
    @if ($status === 'new')
        <span class="size-1.5 rounded-full bg-gold" aria-hidden="true"></span>
    @endif
    {{ $label }}
</span>

@php($periodLabels = ['7' => 'Last 7 days', '30' => 'Last 30 days', '90' => 'Last 90 days', 'all' => 'All time'])

<x-layouts.admin title="WhatsApp">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-[36px] text-cocoa">WhatsApp</h1>
            <p class="mt-2 max-w-2xl text-[15px] text-ink/70">Every time someone taps a WhatsApp button in the shop. WhatsApp doesn't tell us whether they sent the message, so this counts people who opened a chat.</p>
        </div>
        <a href="{{ route('admin.whatsapp.edit') }}" class="btn-primary gap-2">
            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
            Manage numbers
        </a>
    </div>

    <nav class="mt-8 flex gap-2 overflow-x-auto pb-1" aria-label="Time period">
        @foreach ($periods as $value)
            <a href="{{ route('admin.whatsapp.index', ['period' => $value]) }}" @class([
                'shrink-0 rounded-full border px-4 py-2 text-sm transition',
                'border-forest bg-forest text-white' => $period === $value,
                'border-black/10 bg-white text-ink/80 hover:border-forest/40' => $period !== $value,
            ]) @if ($period === $value) aria-current="page" @endif>{{ $periodLabels[$value] }}</a>
        @endforeach
    </nav>

    <dl class="mt-4 grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4">
        @foreach ([
            ['People', $totals['people'], 'text-forest', 'Different visitors who opened WhatsApp'],
            ['Chats opened', $totals['clicks'], 'text-cocoa', 'All taps, including repeat visits'],
            ['Questions', $totals['chats'], 'text-cocoa', 'From the Chat with us button'],
            ['Cart orders', $totals['orders'], 'text-[#128c4a]', 'Sent their cart as an order'],
        ] as [$label, $count, $color, $hint])
            <div class="rounded-2xl border border-black/8 bg-white p-4 sm:p-6">
                <dt class="text-xs font-bold tracking-wide text-ink/60 uppercase sm:text-sm">{{ $label }}</dt>
                <dd class="mt-1 font-display text-3xl {{ $color }} sm:text-4xl">{{ number_format($count) }}</dd>
                <dd class="mt-1 text-xs text-ink/50">{{ $hint }}</dd>
            </div>
        @endforeach
    </dl>

    <section class="mt-8">
        <h2 class="font-sans text-lg font-bold text-cocoa">Who they chatted with</h2>
        @if ($byContact->isEmpty())
            <p class="mt-3 rounded-2xl border border-dashed border-black/15 bg-white px-6 py-10 text-center text-[15px] text-ink/70">No one has opened WhatsApp from the shop in this period yet.</p>
        @else
            <ul class="mt-3 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($byContact as $row)
                    <li class="rounded-2xl border border-black/8 bg-white p-5">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-[#25d366] text-white">
                                @include('partials.icons.whatsapp', ['class' => 'size-5'])
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-base font-bold text-cocoa">{{ $row->label }}</p>
                                <p class="text-sm text-ink/60">+{{ $row->contact_number }}</p>
                            </div>
                        </div>
                        <dl class="mt-4 grid grid-cols-3 gap-2 text-center">
                            @foreach (['People' => $row->people, 'Chats' => $row->clicks, 'Orders' => $row->orders] as $label => $value)
                                <div class="rounded-xl bg-linen py-2">
                                    <dt class="text-[11px] font-bold tracking-wide text-ink/50 uppercase">{{ $label }}</dt>
                                    <dd class="font-display text-2xl text-cocoa">{{ number_format($value) }}</dd>
                                </div>
                            @endforeach
                        </dl>
                        <p class="mt-3 text-xs text-ink/50">Last chat {{ \Illuminate\Support\Carbon::parse($row->last_clicked_at)->diffForHumans() }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    @if ($recent->isNotEmpty())
        <section class="mt-10">
            <h2 class="font-sans text-lg font-bold text-cocoa">Recent activity</h2>
            <ul class="mt-3 divide-y divide-black/6 overflow-hidden rounded-2xl border border-black/8 bg-white">
                @foreach ($recent as $click)
                    <li class="flex flex-col gap-1 px-4 py-3.5 sm:flex-row sm:items-center sm:gap-5 sm:px-5">
                        <span class="w-36 shrink-0 text-sm text-ink/60" title="{{ $click->created_at->format('F j, Y g:i A') }}">{{ $click->created_at->format('M j, g:i A') }}</span>
                        <span class="min-w-0 flex-1 text-[15px] text-cocoa">
                            @if ($click->user)
                                <span class="font-bold">{{ $click->user->name }}</span>
                            @else
                                <span class="text-ink/60">Guest</span>
                            @endif
                            → <span class="font-bold">{{ $click->contact_label }}</span>
                            <span class="block text-sm text-ink/60 sm:inline sm:pl-1">
                                @if ($click->source === 'order')
                                    · {{ $click->cart_items }} {{ Str::plural('item', $click->cart_items) }}, ${{ number_format($click->cart_total_cents / 100, 2) }}
                                @elseif ($click->product)
                                    · about <a href="{{ route('product', $click->product) }}" target="_blank" rel="noopener" class="text-forest underline-offset-4 hover:underline">{{ $click->product->name }}</a>
                                @endif
                            </span>
                        </span>
                        <span @class([
                            'w-fit shrink-0 rounded-full px-2.5 py-0.5 text-xs font-bold',
                            'bg-[#25d366]/15 text-[#128c4a]' => $click->source === 'order',
                            'bg-mist text-ink/70' => $click->source !== 'order',
                        ])>{{ $click->sourceLabel() }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="mt-6">{{ $recent->links() }}</div>
        </section>
    @endif
</x-layouts.admin>

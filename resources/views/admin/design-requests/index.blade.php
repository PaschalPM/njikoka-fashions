<x-layouts.admin title="Design requests">
    <div>
        <h1 class="font-display text-[36px] text-cocoa">Design requests</h1>
        <p class="mt-2 text-[15px] text-ink/70">Submissions from the Custom Designs form. Open one to see the full brief and track your follow-up.</p>
    </div>

    <nav class="mt-8 flex gap-2 overflow-x-auto pb-1" aria-label="Filter by status">
        @foreach (['' => 'All'] + \App\Models\DesignRequest::STATUSES as $value => $label)
            @php($active = (string) request('status') === (string) $value)
            <a href="{{ route('admin.design-requests.index', array_filter(['status' => $value, 'q' => request('q')])) }}" @class([
                'flex shrink-0 items-center gap-2 rounded-full border px-4 py-2 text-sm transition',
                'border-forest bg-forest text-white' => $active,
                'border-black/10 bg-white text-ink/80 hover:border-forest/40' => ! $active,
            ]) @if ($active) aria-current="page" @endif>
                {{ $label }}
                <span @class(['rounded-full px-1.5 text-xs font-bold', 'bg-white/20' => $active, 'bg-mist text-ink/60' => ! $active])>{{ $value === '' ? $counts->sum() : ($counts[$value] ?? 0) }}</span>
            </a>
        @endforeach
    </nav>

    <form method="GET" action="{{ route('admin.design-requests.index') }}" class="mt-4 flex gap-2 rounded-2xl border border-black/8 bg-white p-3">
        @if (request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <label class="relative flex-1">
            <span class="sr-only">Search by name or email</span>
            <svg class="pointer-events-none absolute top-1/2 left-3.5 size-5 -translate-y-1/2 text-ink/40" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search by name or email…" class="ff-input pl-11">
        </label>
        <button class="ff-submit">Search</button>
    </form>

    @if ($requests->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-black/15 bg-white px-6 py-16 text-center">
            <h2 class="font-display text-2xl text-cocoa">{{ $counts->sum() ? 'No requests match' : 'No design requests yet' }}</h2>
            <p class="mt-2 text-[15px] text-ink/70">{{ $counts->sum() ? 'Try another status or search.' : 'When someone fills in the Custom Designs form, it shows up here.' }}</p>
        </div>
    @else
        <ul class="mt-6 space-y-3">
            @foreach ($requests as $designRequest)
                <li>
                    <a href="{{ route('admin.design-requests.show', $designRequest) }}" @class([
                        'group flex flex-col gap-3 rounded-2xl border bg-white p-4 transition hover:border-forest/40 hover:shadow-md sm:flex-row sm:items-start sm:gap-6 sm:p-5',
                        'border-gold/50' => $designRequest->status === 'new',
                        'border-black/8' => $designRequest->status !== 'new',
                    ])>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                <h2 @class(['font-sans text-lg text-cocoa group-hover:text-forest', 'font-bold' => $designRequest->status === 'new'])>{{ $designRequest->fullName() }}</h2>
                                <span class="text-sm text-ink/60">{{ $designRequest->email }}</span>
                            </div>
                            <p class="mt-1 text-sm font-bold text-ink/80">
                                {{ $designRequest->garment_type }}
                                @if ($designRequest->fabrics)
                                    <span class="font-normal text-ink/60">· {{ implode(', ', $designRequest->fabrics) }}</span>
                                @endif
                            </p>
                            <p class="mt-2 line-clamp-2 text-[15px] text-ink/70">{{ $designRequest->description }}</p>
                        </div>
                        <div class="flex shrink-0 items-center justify-between gap-3 sm:flex-col sm:items-end">
                            @include('admin.status-badge', ['status' => $designRequest->status, 'label' => $designRequest->statusLabel()])
                            <time datetime="{{ $designRequest->created_at->toIso8601String() }}" class="text-xs text-ink/50" title="{{ $designRequest->created_at->format('F j, Y g:i A') }}">{{ $designRequest->created_at->diffForHumans() }}</time>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="mt-8">{{ $requests->links() }}</div>
    @endif
</x-layouts.admin>

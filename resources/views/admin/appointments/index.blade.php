@php($when = request('when') === 'past' ? 'past' : 'upcoming')

<x-layouts.admin title="Appointments">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-[36px] text-cocoa">Appointments</h1>
            <p class="mt-2 text-[15px] text-ink/70">Bookings from the Book Appointment form. Confirm each one with the customer, then update its status.</p>
        </div>
        <div class="flex rounded-full border border-black/10 bg-white p-1 text-sm">
            @foreach (['upcoming' => 'Upcoming', 'past' => 'Past'] as $value => $label)
                <a href="{{ route('admin.appointments.index', array_filter(['when' => $value === 'past' ? 'past' : null, 'status' => request('status'), 'q' => request('q')])) }}" @class([
                    'rounded-full px-4 py-1.5 transition',
                    'bg-forest font-bold text-white' => $when === $value,
                    'text-ink/70 hover:text-forest' => $when !== $value,
                ]) @if ($when === $value) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <nav class="mt-8 flex gap-2 overflow-x-auto pb-1" aria-label="Filter by status">
        @foreach (['' => 'All'] + \App\Models\Appointment::STATUSES as $value => $label)
            @php($active = (string) request('status') === (string) $value)
            <a href="{{ route('admin.appointments.index', array_filter(['status' => $value, 'when' => request('when'), 'q' => request('q')])) }}" @class([
                'flex shrink-0 items-center gap-2 rounded-full border px-4 py-2 text-sm transition',
                'border-forest bg-forest text-white' => $active,
                'border-black/10 bg-white text-ink/80 hover:border-forest/40' => ! $active,
            ]) @if ($active) aria-current="page" @endif>
                {{ $label }}
                <span @class(['rounded-full px-1.5 text-xs font-bold', 'bg-white/20' => $active, 'bg-mist text-ink/60' => ! $active])>{{ $value === '' ? $counts->sum() : ($counts[$value] ?? 0) }}</span>
            </a>
        @endforeach
    </nav>

    <form method="GET" action="{{ route('admin.appointments.index') }}" class="mt-4 flex gap-2 rounded-2xl border border-black/8 bg-white p-3">
        @foreach (['status', 'when'] as $keep)
            @if (request($keep))
                <input type="hidden" name="{{ $keep }}" value="{{ request($keep) }}">
            @endif
        @endforeach
        <label class="relative flex-1">
            <span class="sr-only">Search by name or email</span>
            <svg class="pointer-events-none absolute top-1/2 left-3.5 size-5 -translate-y-1/2 text-ink/40" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search by name or email…" class="ff-input pl-11">
        </label>
        <button class="ff-submit">Search</button>
    </form>

    @if ($appointments->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-black/15 bg-white px-6 py-16 text-center">
            <h2 class="font-display text-2xl text-cocoa">No {{ $when }} appointments{{ request()->hasAny(['status', 'q']) ? ' match' : '' }}</h2>
            <p class="mt-2 text-[15px] text-ink/70">
                {{ $when === 'upcoming' ? 'New bookings from the Book Appointment form show up here.' : 'Appointments move here once their date has passed.' }}
            </p>
        </div>
    @else
        <ul class="mt-6 space-y-3">
            @foreach ($appointments as $appointment)
                <li>
                    <a href="{{ route('admin.appointments.show', $appointment) }}" @class([
                        'group flex items-center gap-4 rounded-2xl border bg-white p-4 transition hover:border-forest/40 hover:shadow-md sm:gap-6 sm:p-5',
                        'border-gold/50' => $appointment->status === 'new',
                        'border-black/8' => $appointment->status !== 'new',
                    ])>
                        <div @class([
                            'flex w-16 shrink-0 flex-col items-center rounded-xl py-2 text-center',
                            'bg-forest text-white' => $appointment->preferred_date->isToday(),
                            'bg-linen text-cocoa' => ! $appointment->preferred_date->isToday(),
                        ])>
                            <span class="text-xs font-bold tracking-wide uppercase">{{ $appointment->preferred_date->format('M') }}</span>
                            <span class="font-display text-2xl leading-none">{{ $appointment->preferred_date->format('j') }}</span>
                            <span class="text-[11px] opacity-70">{{ $appointment->preferred_date->isToday() ? 'Today' : $appointment->preferred_date->format('D') }}</span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                <h2 @class(['font-sans text-lg text-cocoa group-hover:text-forest', 'font-bold' => $appointment->status === 'new'])>{{ $appointment->fullName() }}</h2>
                                <span class="truncate text-sm text-ink/60">{{ $appointment->email }}</span>
                            </div>
                            <p class="mt-1 text-sm text-ink/80"><span class="font-bold">{{ $appointment->time_slot }}</span> · {{ $appointment->service }}</p>
                        </div>

                        <div class="hidden shrink-0 sm:block">
                            @include('admin.status-badge', ['status' => $appointment->status, 'label' => $appointment->statusLabel()])
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="mt-8">{{ $appointments->links() }}</div>
    @endif
</x-layouts.admin>

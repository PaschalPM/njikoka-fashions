@php
    $when = $appointment->preferred_date->format('l, F j, Y').' at '.$appointment->time_slot;
    $confirmBody = "Hi {$appointment->first_name},\n\nThank you for booking a {$appointment->service} with Njikoka Fashions. We're pleased to confirm your appointment on {$when}.\n\n";
@endphp

<x-layouts.admin :title="'Appointment for '.$appointment->fullName()">
    <a href="{{ route('admin.appointments.index') }}" class="inline-flex items-center gap-1.5 text-sm text-ink/70 hover:text-forest">
        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        All appointments
    </a>

    <div class="mt-3 flex flex-wrap items-center gap-3">
        <h1 class="font-display text-[36px] text-cocoa">{{ $appointment->fullName() }}</h1>
        @include('admin.status-badge', ['status' => $appointment->status, 'label' => $appointment->statusLabel()])
    </div>
    <p class="mt-1 text-sm text-ink/60">Booked {{ $appointment->created_at->format('F j, Y \a\t g:i A') }} ({{ $appointment->created_at->diffForHumans() }})</p>

    <div class="mt-6 grid items-start gap-5 lg:grid-cols-[minmax(0,1fr)_360px]">
        <section class="rounded-2xl border border-black/8 bg-white p-5 sm:p-7">
            <h2 class="font-sans text-lg font-bold text-cocoa">Appointment</h2>

            <div class="mt-5 flex items-center gap-4 rounded-xl bg-linen p-4">
                <div class="flex w-16 shrink-0 flex-col items-center rounded-lg bg-white py-2 text-center text-cocoa">
                    <span class="text-xs font-bold tracking-wide uppercase">{{ $appointment->preferred_date->format('M') }}</span>
                    <span class="font-display text-2xl leading-none">{{ $appointment->preferred_date->format('j') }}</span>
                </div>
                <div>
                    <p class="text-lg font-bold text-cocoa">{{ $appointment->preferred_date->format('l, F j, Y') }}</p>
                    <p class="text-base text-ink/80">{{ $appointment->time_slot }}
                        @if ($appointment->preferred_date->isToday())
                            <span class="ml-1 rounded-full bg-forest px-2 py-0.5 text-xs font-bold text-white">Today</span>
                        @elseif ($appointment->preferred_date->isPast())
                            <span class="ml-1 text-sm text-ink/50">(past)</span>
                        @else
                            <span class="ml-1 text-sm text-ink/50">({{ $appointment->preferred_date->diffForHumans() }})</span>
                        @endif
                    </p>
                </div>
            </div>

            <h3 class="mt-7 text-xs font-bold tracking-wide text-ink/50 uppercase">Service</h3>
            <p class="mt-1 text-base text-cocoa">{{ $appointment->service }}</p>

            <h3 class="mt-7 text-xs font-bold tracking-wide text-ink/50 uppercase">Notes from the customer</h3>
            @if ($appointment->notes)
                <p class="mt-2 text-base leading-relaxed whitespace-pre-line text-ink">{{ $appointment->notes }}</p>
            @else
                <p class="mt-2 text-base text-ink/50">None</p>
            @endif
        </section>

        <div class="space-y-5">
            <section class="rounded-2xl border border-black/8 bg-white p-5">
                <h2 class="font-sans text-lg font-bold text-cocoa">Contact</h2>
                <p class="mt-3 text-base text-cocoa">{{ $appointment->fullName() }}</p>
                <p class="text-sm break-all text-ink/70">{{ $appointment->email }}</p>
                <a href="mailto:{{ $appointment->email }}?subject={{ rawurlencode('Your appointment with Njikoka Fashions – '.$when) }}&body={{ rawurlencode($confirmBody) }}"
                   class="btn-primary mt-4 w-full gap-2 py-3 text-[15px]">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    Confirm by email
                </a>
                <p class="mt-2 text-center text-xs text-ink/50">Opens your email app with a confirmation drafted.</p>
            </section>

            <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}" class="rounded-2xl border border-black/8 bg-white p-5">
                @csrf
                @method('PATCH')
                @include('partials.form-errors')
                <h2 class="font-sans text-lg font-bold text-cocoa">Follow-up</h2>

                <label class="ff-label mt-4" for="status">Status</label>
                <select class="ff-input" id="status" name="status">
                    @foreach (\App\Models\Appointment::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $appointment->status) === $value)>{{ $label }}</option>
                    @endforeach
                </select>

                <label class="ff-label mt-4" for="admin_notes">Private notes</label>
                <textarea class="ff-input min-h-[140px]" id="admin_notes" name="admin_notes" placeholder="Rescheduled times, measurements, outcome… only admins see this.">{{ old('admin_notes', $appointment->admin_notes) }}</textarea>

                <button class="btn-primary mt-4 w-full py-3 text-[15px]">Save</button>
            </form>
        </div>
    </div>

    <section class="mt-10 flex flex-col gap-4 rounded-2xl border border-[#e2401c]/25 bg-white p-5 sm:flex-row sm:items-center sm:justify-between sm:p-7">
        <div>
            <h2 class="font-sans text-lg font-bold text-cocoa">Delete this appointment</h2>
            <p class="mt-1 text-sm text-ink/70">Removes it permanently. Mark it as Cancelled instead if you want to keep a record.</p>
        </div>
        <form method="POST" action="{{ route('admin.appointments.destroy', $appointment) }}" onsubmit="return confirm('Delete this appointment? This cannot be undone.')">
            @csrf
            @method('DELETE')
            <button class="btn shrink-0 border border-[#e2401c] text-[#e2401c] hover:bg-[#e2401c] hover:text-white">Delete appointment</button>
        </form>
    </section>
</x-layouts.admin>

@php($replySubject = 'Your custom '.strtolower($designRequest->garment_type).' design – Njikoka Fashions')

<x-layouts.admin :title="'Request from '.$designRequest->fullName()">
    <a href="{{ route('admin.design-requests.index') }}" class="inline-flex items-center gap-1.5 text-sm text-ink/70 hover:text-forest">
        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        All design requests
    </a>

    <div class="mt-3 flex flex-wrap items-center gap-3">
        <h1 class="font-display text-[36px] text-cocoa">{{ $designRequest->fullName() }}</h1>
        @include('admin.status-badge', ['status' => $designRequest->status, 'label' => $designRequest->statusLabel()])
    </div>
    <p class="mt-1 text-sm text-ink/60">Submitted {{ $designRequest->created_at->format('F j, Y \a\t g:i A') }} ({{ $designRequest->created_at->diffForHumans() }})</p>

    <div class="mt-6 grid items-start gap-5 lg:grid-cols-[minmax(0,1fr)_360px]">
        <section class="rounded-2xl border border-black/8 bg-white p-5 sm:p-7">
            <h2 class="font-sans text-lg font-bold text-cocoa">The brief</h2>

            <dl class="mt-5 grid gap-5 sm:grid-cols-3">
                <div>
                    <dt class="text-xs font-bold tracking-wide text-ink/50 uppercase">Garment</dt>
                    <dd class="mt-1 text-base text-cocoa">{{ $designRequest->garment_type }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-bold tracking-wide text-ink/50 uppercase">Fabrics</dt>
                    <dd class="mt-1 flex flex-wrap gap-1.5">
                        @forelse ($designRequest->fabrics ?? [] as $fabric)
                            <span class="rounded-full bg-linen px-2.5 py-0.5 text-sm text-cocoa">{{ $fabric }}</span>
                        @empty
                            <span class="text-base text-ink/50">No preference</span>
                        @endforelse
                    </dd>
                </div>
                @if ($designRequest->budget)
                    <div>
                        <dt class="text-xs font-bold tracking-wide text-ink/50 uppercase">Budget</dt>
                        <dd class="mt-1 text-base text-cocoa">{{ $designRequest->budget }}</dd>
                    </div>
                @endif
            </dl>

            <h3 class="mt-7 text-xs font-bold tracking-wide text-ink/50 uppercase">Description</h3>
            <p class="mt-2 text-base leading-relaxed whitespace-pre-line text-ink">{{ $designRequest->description }}</p>
        </section>

        <div class="space-y-5">
            <section class="rounded-2xl border border-black/8 bg-white p-5">
                <h2 class="font-sans text-lg font-bold text-cocoa">Contact</h2>
                <p class="mt-3 text-base text-cocoa">{{ $designRequest->fullName() }}</p>
                <p class="text-sm break-all text-ink/70">{{ $designRequest->email }}</p>
                <a href="mailto:{{ $designRequest->email }}?subject={{ rawurlencode($replySubject) }}&body={{ rawurlencode("Hi {$designRequest->first_name},\n\nThank you for your custom design request.\n\n") }}"
                   class="btn-primary mt-4 w-full gap-2 py-3 text-[15px]">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    Reply by email
                </a>
            </section>

            <form method="POST" action="{{ route('admin.design-requests.update', $designRequest) }}" class="rounded-2xl border border-black/8 bg-white p-5">
                @csrf
                @method('PATCH')
                @include('partials.form-errors')
                <h2 class="font-sans text-lg font-bold text-cocoa">Follow-up</h2>

                <label class="ff-label mt-4" for="status">Status</label>
                <select class="ff-input" id="status" name="status">
                    @foreach (\App\Models\DesignRequest::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $designRequest->status) === $value)>{{ $label }}</option>
                    @endforeach
                </select>

                <label class="ff-label mt-4" for="admin_notes">Private notes</label>
                <textarea class="ff-input min-h-[140px]" id="admin_notes" name="admin_notes" placeholder="Measurements, quotes, call notes… only admins see this.">{{ old('admin_notes', $designRequest->admin_notes) }}</textarea>

                <button class="btn-primary mt-4 w-full py-3 text-[15px]">Save</button>
            </form>
        </div>
    </div>

    <section class="mt-10 flex flex-col gap-4 rounded-2xl border border-[#e2401c]/25 bg-white p-5 sm:flex-row sm:items-center sm:justify-between sm:p-7">
        <div>
            <h2 class="font-sans text-lg font-bold text-cocoa">Delete this request</h2>
            <p class="mt-1 text-sm text-ink/70">Removes it permanently. Mark it as Declined instead if you want to keep a record.</p>
        </div>
        <form method="POST" action="{{ route('admin.design-requests.destroy', $designRequest) }}" onsubmit="return confirm('Delete this design request? This cannot be undone.')">
            @csrf
            @method('DELETE')
            <button class="btn shrink-0 border border-[#e2401c] text-[#e2401c] hover:bg-[#e2401c] hover:text-white">Delete request</button>
        </form>
    </section>
</x-layouts.admin>

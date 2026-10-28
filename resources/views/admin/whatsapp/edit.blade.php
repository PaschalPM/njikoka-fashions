@php($placeholders = ['Sales', 'Custom designs', 'Customer care'])

<x-layouts.admin title="WhatsApp numbers">
    <a href="{{ route('admin.whatsapp.index') }}" class="inline-flex items-center gap-1.5 text-sm text-ink/70 hover:text-forest">
        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        WhatsApp activity
    </a>
    <h1 class="mt-3 font-display text-[36px] text-cocoa">WhatsApp numbers</h1>
    <p class="mt-2 max-w-2xl text-[15px] text-ink/70">Up to {{ \App\Models\WhatsappContact::SLOTS }} numbers customers can reach from the "Chat with us" button. Customers choose one of them when they chat or send their cart as an order.</p>

    @if ($fallbackNumber)
        <p class="mt-4 max-w-2xl rounded-xl border border-gold/50 bg-gold/10 px-4 py-3 text-sm text-cocoa">
            No numbers saved yet, so the shop is using the default number <strong>+{{ $fallbackNumber }}</strong>.
        </p>
    @endif

    <form method="POST" action="{{ route('admin.whatsapp.update') }}" class="mt-6 max-w-2xl">
        @csrf
        @method('PUT')
        @include('partials.form-errors')

        <div class="space-y-4">
            @foreach ($slots as $index => $contact)
                <fieldset class="rounded-2xl border border-black/8 bg-white p-5 sm:p-6">
                    <legend class="sr-only">WhatsApp number {{ $index + 1 }}</legend>
                    <div class="flex items-center gap-3">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-[#25d366] text-white">
                            @include('partials.icons.whatsapp', ['class' => 'size-5'])
                        </span>
                        <h2 class="font-sans text-lg font-bold text-cocoa">Number {{ $index + 1 }}</h2>
                    </div>

                    <div class="mt-4 grid gap-4 sm:grid-cols-[minmax(0,1fr)_minmax(0,1.2fr)]">
                        <div>
                            <label class="ff-label" for="contacts-{{ $index }}-label">Label</label>
                            <input class="ff-input" type="text" id="contacts-{{ $index }}-label" name="contacts[{{ $index }}][label]" maxlength="40"
                                   value="{{ old("contacts.$index.label", $contact?->label) }}" placeholder="e.g. {{ $placeholders[$index] ?? 'Support' }}">
                        </div>
                        <div>
                            <label class="ff-label" for="contacts-{{ $index }}-number">WhatsApp number</label>
                            <input class="ff-input" type="tel" id="contacts-{{ $index }}-number" name="contacts[{{ $index }}][number]" inputmode="tel"
                                   value="{{ old("contacts.$index.number", $contact ? $contact->displayNumber() : '') }}" placeholder="+1 301 968 0567"
                                   aria-describedby="whatsapp-number-hint">
                        </div>
                    </div>
                </fieldset>
            @endforeach
        </div>

        <p id="whatsapp-number-hint" class="mt-3 text-sm text-ink/60">Include the country code (1 for the US, 234 for Nigeria). Leave a number empty to hide it from the shop.</p>

        <button class="btn-primary mt-6 px-8 py-3 text-[15px]">Save numbers</button>
    </form>
</x-layouts.admin>

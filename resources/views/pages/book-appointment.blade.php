<x-layouts.app title="Book Appointment" body-class="bg-white">
    <x-page-hero title="Book Appointment" />

    <section class="bg-white px-[10px] py-[30px] lg:pt-[80px] lg:pb-[80px]">
        <form method="POST" action="{{ route('book-appointment.store') }}" class="mx-auto max-w-[650px] rounded-2xl border border-[#e6e6e6] bg-white p-5 shadow-[0_10px_30px_rgba(0,0,0,0.04)] sm:p-10">
            @csrf
            <h2 class="sr-only">Book an Appointment</h2>
            @include('partials.form-errors')

            @php($field = 'ff-input bg-field')

            <div class="grid gap-5 sm:grid-cols-2 sm:gap-[15px]">
                <div>
                    <label class="ff-label" for="first_name">First Name<span class="ff-required">*</span></label>
                    <input class="{{ $field }}" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" required>
                </div>
                <div>
                    <label class="ff-label" for="last_name">Last Name<span class="ff-required">*</span></label>
                    <input class="{{ $field }}" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required>
                </div>
                <div>
                    <label class="ff-label" for="email">Email Address<span class="ff-required">*</span></label>
                    <input class="{{ $field }}" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="your.email@example.com" required>
                </div>
            </div>

            <div class="mt-5">
                <label class="ff-label" for="service">Select Service / Consultation<span class="ff-required">*</span></label>
                <select class="ff-input rounded-[4px] py-[9px] pl-[10px]" id="service" name="service" required>
                    <option value="">- Choose a Service -</option>
                    @foreach (config('store.services') as $service)
                        <option @selected(old('service') === $service)>{{ $service }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-5 grid gap-5 sm:grid-cols-2 sm:gap-[15px]">
                <div>
                    <label class="ff-label" for="preferred_date">Preferred Date<span class="ff-required">*</span></label>
                    <input class="{{ $field }}" type="text" id="preferred_date" name="preferred_date" value="{{ old('preferred_date') }}" placeholder="Select Date"
                        onfocus="this.type='date'; this.min='{{ now()->toDateString() }}'" onblur="if (! this.value) this.type='text'" required>
                </div>
                <fieldset>
                    <legend class="ff-label">Preferred Time Slot<span class="ff-required">*</span></legend>
                    <div class="flex flex-wrap gap-[10px]">
                        @foreach (config('store.time_slots') as $slot)
                            <label class="flex items-center gap-[3px] rounded-[7px] border border-[#dadbdd] bg-field px-5 py-[10px] text-base leading-[1.2] text-[#1e1f21] has-checked:border-forest">
                                <input type="radio" name="time_slot" value="{{ $slot }}" @checked(old('time_slot') === $slot) required>
                                {{ $slot }}
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            </div>

            <div class="mt-5">
                <label class="ff-label" for="notes">Additional Information / Goals</label>
                <textarea class="{{ $field }} min-h-[90px] text-[15px]" id="notes" name="notes" rows="4" placeholder="Please share any specific goals or topics you would like to discuss during our session...">{{ old('notes') }}</textarea>
            </div>

            <label class="mt-5 inline-flex items-center gap-[7px] bg-[#f2f2f2] pr-[5px] text-base leading-6 text-[#1e1f21]">
                <input type="checkbox" name="policy" value="1" @checked(old('policy')) required>
                I agree to the Cancellation &amp; Rescheduling Policy.
            </label>

            <button class="ff-submit mt-5 block w-full py-[12px] text-[15px] font-bold tracking-[1px]">Submit Form</button>
        </form>
    </section>
</x-layouts.app>

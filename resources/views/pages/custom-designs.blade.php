<x-layouts.app title="Custom Designs" body-class="bg-white">
    <x-page-hero title="Custom Designs" />

    <section class="bg-white px-[10px] py-[30px] lg:pt-[80px] lg:pb-[80px]">
        <form method="POST" action="{{ route('custom-designs.store') }}" class="mx-auto max-w-[700px] rounded-2xl border border-[#e6e6e6] bg-[#fcfbf9] p-5 shadow-[0_10px_30px_rgba(0,0,0,0.04)] sm:p-10">
            @csrf
            <h2 class="sr-only">Custom Clothing Design Request</h2>
            @include('partials.form-errors')

            @php($label = 'ff-label text-[15px] tracking-[0.3px] uppercase')

            <h3 class="border-b border-[#e0e0e0] pb-[10px] font-system text-[22px] leading-[1.2] font-bold text-[#333]">1. Contact Information</h3>
            <p class="mt-[30px] text-base leading-6 text-[#333]">Please enter your details so we can reach out regarding your custom design.</p>

            <div class="mt-5 grid gap-5 sm:grid-cols-2 sm:gap-[15px]">
                <div>
                    <label class="{{ $label }}" for="first_name">First Name<span class="ff-required">*</span></label>
                    <input class="ff-input" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" required>
                </div>
                <div>
                    <label class="{{ $label }}" for="last_name">Last Name<span class="ff-required">*</span></label>
                    <input class="ff-input" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required>
                </div>
            </div>
            <div class="mt-5">
                <label class="{{ $label }}" for="email">Email Address<span class="ff-required">*</span></label>
                <input class="ff-input" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="your.email@example.com" required>
            </div>

            <h3 class="mt-[30px] border-b border-[#e0e0e0] pb-[10px] font-system text-[22px] leading-[1.2] font-bold text-[#333]">2. Design Specifications</h3>
            <p class="mt-[30px] text-base leading-6 text-[#333]">Tell us about the garment you want us to create.</p>

            <div class="mt-5">
                <label class="{{ $label }}" for="garment_type">Garment Type<span class="ff-required">*</span></label>
                <select class="ff-input rounded-[4px] py-[9px] pl-[10px]" id="garment_type" name="garment_type" required>
                    <option value="">- Select Garment Type -</option>
                    @foreach (config('store.garment_types') as $type)
                        <option @selected(old('garment_type') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <fieldset class="mt-5">
                <legend class="{{ $label }}">Preferred Fabrics</legend>
                <div class="space-y-[6px] text-base leading-6 text-[#1e1f21]">
                    @foreach (config('store.fabrics') as $fabric)
                        <label class="flex items-center gap-[3px]">
                            <input type="checkbox" name="fabrics[]" value="{{ $fabric }}" @checked(in_array($fabric, old('fabrics', [])))>
                            {{ $fabric }}
                        </label>
                    @endforeach
                </div>
            </fieldset>

            <div class="mt-5">
                <label class="{{ $label }}" for="description">Design Details &amp; Description<span class="ff-required">*</span></label>
                <textarea class="ff-input min-h-[90px] text-[15px]" id="description" name="description" rows="4" placeholder="Describe your vision, colors, patterns, fit, and any specific details..." required>{{ old('description') }}</textarea>
            </div>

            <h3 class="mt-[30px] border-b border-[#e0e0e0] pb-[10px] font-system text-[22px] leading-[1.2] font-bold text-[#333]">3. Budget &amp; Consultation Deposit</h3>
            <p class="mt-[30px] text-base leading-6 text-[#333]">Specify your budget range and secure your design consultation slot.</p>

            <button class="ff-submit mt-5 w-full py-[12px] text-[15px] font-bold tracking-[1px]">Submit Form</button>
        </form>
    </section>
</x-layouts.app>

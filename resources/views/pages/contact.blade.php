<x-layouts.app title="Contact" body-class="bg-white">
    <x-page-hero title="Contact" />

    <section class="bg-white py-[30px] lg:pt-[80px] lg:pb-[100px]">
        <div class="container-site grid gap-10 lg:grid-cols-2 lg:gap-5">
            <div class="lg:px-5">
                <h2 class="text-[30px] leading-[30px] lg:text-[45px] lg:leading-[45px]">Contact us</h2>
                @include('partials.contact-details', ['class' => 'mt-[30px] gap-5 text-[18px] lg:text-[20px] [&_li]:gap-[23px] [&_svg]:size-[30px]'])
            </div>

            <form method="POST" action="{{ route('contact.store') }}" class="space-y-5 lg:pr-5">
                @csrf
                <h2 class="sr-only">Contact Form Demo</h2>
                @include('partials.form-errors')

                <div class="grid gap-5 sm:grid-cols-2 sm:gap-[15px]">
                    <div>
                        <label class="ff-label font-normal" for="first_name">First Name</label>
                        <input class="ff-input" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="First Name">
                    </div>
                    <div>
                        <label class="ff-label font-normal" for="last_name">Last Name</label>
                        <input class="ff-input" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name">
                    </div>
                </div>
                <div>
                    <label class="ff-label font-normal" for="email">Email<span class="ff-required">*</span></label>
                    <input class="ff-input" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required>
                </div>
                <div>
                    <label class="ff-label font-normal" for="subject">Subject</label>
                    <input class="ff-input" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Subject">
                </div>
                <div>
                    <label class="ff-label font-normal" for="message">Your Message<span class="ff-required">*</span></label>
                    <textarea class="ff-input min-h-[90px]" id="message" name="message" rows="4" placeholder="Your Message" required>{{ old('message') }}</textarea>
                </div>
                <button class="ff-submit rounded-[4px] px-[10px] py-[13px]">Submit Form</button>
            </form>
        </div>
    </section>
</x-layouts.app>

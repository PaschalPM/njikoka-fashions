<x-layouts.app title="Reset password" header="black" body-class="bg-white">
    <section class="bg-white font-sans text-base text-ink">
        <div class="container-site pt-[50px] pb-[80px]">
            <h1 class="font-sans text-[32px] leading-[1.2] text-ink">Reset password</h1>

            <form method="POST" action="{{ route('password.update') }}" class="mt-[30px] max-w-[640px] text-[14px] text-[#515151]">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                @include('partials.form-errors')
                <p class="text-base text-ink">Enter a new password below.</p>
                <label class="mt-[25px] block" for="email">Email address&nbsp;<span class="text-[#e2401c]">*</span></label>
                <input class="woo-input mt-[5px] h-[50px]" type="email" id="email" name="email" value="{{ old('email', $email) }}" autocomplete="username" required>
                <label class="mt-[25px] block" for="password">New password&nbsp;<span class="text-[#e2401c]">*</span></label>
                <input class="woo-input mt-[5px] h-[50px]" type="password" id="password" name="password" autocomplete="new-password" required>
                <label class="mt-[25px] block" for="password_confirmation">Re-enter new password&nbsp;<span class="text-[#e2401c]">*</span></label>
                <input class="woo-input mt-[5px] h-[50px]" type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
                <button class="woo-button mt-[25px] text-[14px]">Save</button>
            </form>
        </div>
    </section>
</x-layouts.app>

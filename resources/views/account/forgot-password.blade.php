<x-layouts.app title="Lost password" header="black" body-class="bg-white">
    <section class="bg-white font-sans text-base text-ink">
        <div class="container-site pt-[50px] pb-[80px]">
            <h1 class="font-sans text-[32px] leading-[1.2] text-ink">Lost password</h1>

            <form method="POST" action="{{ route('password.email') }}" class="mt-[30px] max-w-[640px] text-[14px] text-[#515151]">
                @csrf
                @include('partials.form-errors')
                <p class="text-base text-ink">Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.</p>
                <label class="mt-[25px] block" for="email">Username or email&nbsp;<span class="text-[#e2401c]">*</span></label>
                <input class="woo-input mt-[5px] h-[50px]" type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="username" required>
                <button class="woo-button mt-[25px] text-[14px]">Reset password</button>
            </form>
        </div>
    </section>
</x-layouts.app>

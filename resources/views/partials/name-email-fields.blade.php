<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="field-label" for="first_name">First Name</label>
        <input class="field" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" required>
    </div>
    <div>
        <label class="field-label" for="last_name">Last Name</label>
        <input class="field" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required>
    </div>
</div>
<div>
    <label class="field-label" for="email">Email Address</label>
    <input class="field" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="your.email@example.com" required>
</div>

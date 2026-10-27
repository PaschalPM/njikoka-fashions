@if ($errors->any())
    <div class="mb-8 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
        <p class="font-bold">Please fix the following:</p>
        <ul class="mt-2 list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

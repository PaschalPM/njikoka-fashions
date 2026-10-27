<section class="relative overflow-hidden bg-cocoa text-white">
    <img src="{{ asset('images/site/Frame-2147226136.png') }}" alt="" loading="lazy" class="absolute inset-0 size-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/30 to-transparent"></div>
    <div class="relative container-site flex min-h-[370px] flex-col justify-center py-12 lg:min-h-[800px]">
        <div class="max-w-xl">
            <h2 class="text-4xl text-white sm:text-5xl">Ready to Find Your Signature Look?</h2>
            <p class="mt-4 text-white/90">Explore the collection or book a consultation for a custom design made just for you.</p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('about') }}" class="btn-outline text-white">Learn More About Njikoka</a>
                <a href="{{ route('shop') }}" class="btn-primary">Shop Collection</a>
                <a href="{{ route('book-appointment') }}" class="btn bg-cream text-cocoa hover:bg-sand">Book Consultation</a>
            </div>
        </div>
    </div>
</section>

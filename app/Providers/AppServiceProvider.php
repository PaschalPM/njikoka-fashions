<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\DesignRequest;
use App\Models\User;
use App\Models\WhatsappContact;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Stripe\StripeClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(StripeClient::class, fn () => new StripeClient(['api_key' => config('services.stripe.secret') ?: null]));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin', fn (User $user) => $user->is_admin);

        View::composer('components.layouts.admin', fn ($view) => $view->with([
            'newDesignRequests' => DesignRequest::where('status', 'new')->count(),
            'newAppointments' => Appointment::where('status', 'new')->count(),
        ]));

        View::composer('components.layouts.app', fn ($view) => $view->with('whatsappContacts', WhatsappContact::forStorefront()));
    }
}

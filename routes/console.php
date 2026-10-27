<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:grant {email}', function (string $email) {
    $user = User::firstOrNew(['email' => $email]);

    if (! $user->exists) {
        $user->name = $this->ask('Name');
        $user->password = $this->secret('Password');
    }

    $user->forceFill(['is_admin' => true])->save();
    $this->info("{$email} can now log in at /my-account and manage products at /admin/products.");
})->purpose('Create an admin account, or give an existing account admin access');

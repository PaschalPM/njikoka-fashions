<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\DesignRequestController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/faqs', [PageController::class, 'faqs'])->name('faqs');
Route::get('/shipping-policy', [PageController::class, 'shipping'])->name('shipping');
Route::get('/returns', [PageController::class, 'returns'])->name('returns');
Route::get('/custom-designs', [PageController::class, 'customDesigns'])->name('custom-designs');
Route::get('/book-appointment', [PageController::class, 'bookAppointment'])->name('book-appointment');

Route::post('/contact', [FormController::class, 'contact'])->name('contact.store');
Route::post('/book-appointment', [FormController::class, 'appointment'])->name('book-appointment.store');
Route::post('/custom-designs', [FormController::class, 'design'])->name('custom-designs.store');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{product}', [ShopController::class, 'show'])->name('product');
Route::post('/product/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/order-received', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel/{order}', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
Route::post('/stripe/webhook', StripeWebhookController::class)->name('stripe.webhook');

Route::get('/my-account', [AccountController::class, 'show'])->name('account');
Route::middleware('guest')->group(function () {
    Route::post('/my-account/login', [AccountController::class, 'login'])->name('login');
    Route::get('/my-account/lost-password', [AccountController::class, 'forgotPassword'])->name('password.request');
    Route::post('/my-account/lost-password', [AccountController::class, 'sendResetLink'])->name('password.email');
    Route::get('/my-account/reset-password/{token}', [AccountController::class, 'resetPassword'])->name('password.reset');
    Route::post('/my-account/reset-password', [AccountController::class, 'updatePassword'])->name('password.update');
});
Route::middleware('auth')->group(function () {
    Route::get('/my-account/orders/{order}', [AccountController::class, 'order'])->name('account.order');
    Route::post('/my-account/logout', [AccountController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/products')->name('home');
    Route::resource('products', AdminProductController::class)->except('show');
    Route::resource('design-requests', DesignRequestController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('appointments', AdminAppointmentController::class)->only(['index', 'show', 'update', 'destroy']);
});

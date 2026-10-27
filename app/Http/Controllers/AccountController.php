<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * My Account: login form for guests, order history for customers.
     */
    public function show(Request $request): View
    {
        return view('account.show', [
            'orders' => $request->user()?->orders()->with('items')->get() ?? collect(),
        ]);
    }

    public function order(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        return view('account.order', ['order' => $order->load('items')]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Unknown email address or incorrect password.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('account'));
    }

    public function forgotPassword(): View
    {
        return view('account.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        // Always report success so the form does not reveal which emails have accounts.
        Password::sendResetLink($request->only('email'));

        return back()->with('status', 'Password reset email has been sent.');
    }

    public function resetPassword(Request $request, string $token): View
    {
        return view('account.reset-password', ['token' => $token, 'email' => $request->string('email')]);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => $password])->save();
                event(new PasswordReset($user));
            },
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('account')->with('status', 'Your password has been reset.')
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }

    public function logout(Request $request): RedirectResponse
    {
        // Keep the cart across logout, like WooCommerce does.
        $cart = $request->session()->get('cart', []);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->put('cart', $cart);

        return redirect()->route('home');
    }
}

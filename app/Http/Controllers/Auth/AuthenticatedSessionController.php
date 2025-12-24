<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Log remember flag and remember_token for debugging remember-me issues
        try {
            if ($request->boolean('remember')) {
                \Log::info('Login requested with remember', [
                    'user_id' => Auth::id(),
                    'remember_token' => Auth::user() ? Auth::user()->getRememberToken() : null,
                ]);
            } else {
                \Log::info('Login requested without remember', ['user_id' => Auth::id()]);
            }
        } catch (\Throwable $e) {
            \Log::error('Error logging remember info', ['error' => $e->getMessage()]);
        }

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

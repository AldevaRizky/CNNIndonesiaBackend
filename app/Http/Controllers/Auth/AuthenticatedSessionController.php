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
    public function create(Request $request): View
    {
        // Check if user is locked out
        $identifier = $this->getIdentifier($request);
        $lockoutKey = 'login_lockout_' . $identifier;
        $attemptsKey = 'login_attempts_' . $identifier;

        $isLocked = false;
        $remainingMinutes = 0;
        $attempts = 0;

        if ($request->cookie($lockoutKey)) {
            $lockoutTime = (int) $request->cookie($lockoutKey);

            if (time() < $lockoutTime) {
                $isLocked = true;
                $remainingMinutes = ceil(($lockoutTime - time()) / 60);
            }
        }

        if ($request->cookie($attemptsKey)) {
            $attempts = (int) $request->cookie($attemptsKey);
        }

        return view('auth.login', [
            'isLocked' => $isLocked,
            'remainingMinutes' => $remainingMinutes,
            'attempts' => $attempts
        ]);
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

    /**
     * Get unique identifier for rate limiting
     */
    private function getIdentifier(Request $request): string
    {
        $email = $request->input('email', '');
        return md5($request->ip() . '|' . $email . '|' . $request->userAgent());
    }
}

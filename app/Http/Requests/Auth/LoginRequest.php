<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Maximum login attempts allowed
     */
    const MAX_ATTEMPTS = 5;

    /**
     * Lockout duration in minutes
     */
    const LOCKOUT_MINUTES = 30;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            $this->incrementLoginAttempts();

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Clear attempts on successful login
        $this->clearLoginAttempts();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        $identifier = $this->getIdentifier();
        $lockoutKey = 'login_lockout_' . $identifier;
        $attemptsKey = 'login_attempts_' . $identifier;

        // Check cookie-based lockout
        if ($this->cookie($lockoutKey)) {
            $lockoutTime = (int) $this->cookie($lockoutKey);

            if (time() < $lockoutTime) {
                $seconds = $lockoutTime - time();

                event(new Lockout($this));

                throw ValidationException::withMessages([
                    'email' => 'Terlalu banyak percobaan login gagal. Akun Anda telah dikunci selama ' . ceil($seconds / 60) . ' menit. Silakan coba lagi nanti.',
                ]);
            } else {
                // Lockout expired, clear cookies
                Cookie::queue(Cookie::forget($lockoutKey));
                Cookie::queue(Cookie::forget($attemptsKey));
            }
        }

        // Check Laravel RateLimiter as backup
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), self::MAX_ATTEMPTS)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => 'Terlalu banyak percobaan login gagal. Silakan coba lagi dalam ' . ceil($seconds / 60) . ' menit.',
        ]);
    }

    /**
     * Increment login attempts and set lockout if needed
     */
    protected function incrementLoginAttempts(): void
    {
        RateLimiter::hit($this->throttleKey(), self::LOCKOUT_MINUTES * 60);

        $identifier = $this->getIdentifier();
        $attemptsKey = 'login_attempts_' . $identifier;
        $lockoutKey = 'login_lockout_' . $identifier;

        // Get current attempts from cookie
        $attempts = (int) $this->cookie($attemptsKey, 0);
        $attempts++;

        // Set attempts cookie (expires in 30 minutes)
        Cookie::queue($attemptsKey, $attempts, self::LOCKOUT_MINUTES);

        // If max attempts reached, set lockout cookie
        if ($attempts >= self::MAX_ATTEMPTS) {
            $lockoutTime = time() + (self::LOCKOUT_MINUTES * 60);
            Cookie::queue($lockoutKey, $lockoutTime, self::LOCKOUT_MINUTES);
        }
    }

    /**
     * Clear login attempts on successful login
     */
    protected function clearLoginAttempts(): void
    {
        RateLimiter::clear($this->throttleKey());

        $identifier = $this->getIdentifier();
        Cookie::queue(Cookie::forget('login_attempts_' . $identifier));
        Cookie::queue(Cookie::forget('login_lockout_' . $identifier));
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }

    /**
     * Get unique identifier for cookie-based rate limiting
     */
    protected function getIdentifier(): string
    {
        $email = $this->input('email', '');
        return md5($this->ip() . '|' . $email . '|' . $this->userAgent());
    }
}

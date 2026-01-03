<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CheckLoginAttempts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $identifier = $this->getIdentifier($request);
        $lockoutKey = 'login_lockout_' . $identifier;
        $attemptsKey = 'login_attempts_' . $identifier;
        
        // Check if user is locked out
        if ($request->cookie($lockoutKey)) {
            $lockoutTime = (int) $request->cookie($lockoutKey);
            
            if (time() < $lockoutTime) {
                $remainingMinutes = ceil(($lockoutTime - time()) / 60);
                
                return response()->json([
                    'success' => false,
                    'message' => "Terlalu banyak percobaan login gagal. Silakan coba lagi dalam {$remainingMinutes} menit.",
                    'locked' => true,
                    'remaining_minutes' => $remainingMinutes
                ], 429);
            } else {
                // Lockout expired, clear cookies
                Cookie::queue(Cookie::forget($lockoutKey));
                Cookie::queue(Cookie::forget($attemptsKey));
            }
        }
        
        return $next($request);
    }
    
    /**
     * Get unique identifier for rate limiting
     */
    private function getIdentifier(Request $request): string
    {
        // Kombinasi IP dan email (jika ada) atau user agent
        $email = $request->input('email', '');
        return md5($request->ip() . '|' . $email . '|' . $request->userAgent());
    }
}

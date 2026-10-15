<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate(['password' => ['required', 'string']]);

        $key = 'admin-login:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->with('error', 'Trop de tentatives. Réessayez dans ' . RateLimiter::availableIn($key) . ' s.');
        }

        $attendu = (string) config('app.admin_password');

        // Plus de mot de passe par défaut : si ADMIN_PASSWORD est vide, l'accès est refusé.
        if ($attendu !== '' && hash_equals($attendu, (string) $request->password)) {
            RateLimiter::clear($key);
            $request->session()->regenerate();          // anti session-fixation
            $request->session()->put('admin_logged_in', true);

            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($key, 60);

        return back()->with('error', 'Mot de passe incorrect.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_logged_in');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

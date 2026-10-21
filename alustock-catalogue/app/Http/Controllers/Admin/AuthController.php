<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Verrou progressif : nombre d'échecs cumulés (par IP) => durée du blocage en secondes.
     * Le compteur d'échecs est conservé 24 h et remis à zéro après une connexion réussie.
     */
    private const PALIERS = [
        5  => 60,       // 5 échecs   -> 1 minute
        10 => 900,      // 10 échecs  -> 15 minutes
        15 => 3600,     // 15 échecs  -> 1 heure
        20 => 86400,    // 20 échecs  -> 24 heures
    ];

    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate(['password' => ['required', 'string']]);

        $ip = $request->ip();
        $cleVerrou = "admin-lock:{$ip}";
        $cleEchecs = "admin-fails:{$ip}";

        // 1. IP actuellement verrouillée ?
        if ($finVerrou = Cache::get($cleVerrou)) {
            $reste = max(1, $finVerrou - time());
            Log::warning('Admin login : tentative pendant un verrouillage', ['ip' => $ip, 'reste_s' => $reste]);

            return back()->with('error', 'Trop de tentatives. Réessayez dans ' . $this->duree($reste) . '.');
        }

        $attendu = (string) config('app.admin_password');

        // 2. Succès. Si ADMIN_PASSWORD est vide, l'accès est refusé (pas de mot de passe par défaut).
        if ($attendu !== '' && hash_equals($attendu, (string) $request->password)) {
            Cache::forget($cleEchecs);
            $request->session()->regenerate();          // anti session-fixation
            $request->session()->put('admin_logged_in', true);

            Log::info('Admin login : connexion réussie', ['ip' => $ip]);

            return redirect()->intended(route('admin.dashboard'));
        }

        // 3. Échec : on compte, on journalise (jamais le mot de passe saisi), on verrouille si palier atteint.
        $echecs = (int) Cache::get($cleEchecs, 0) + 1;
        Cache::put($cleEchecs, $echecs, now()->addDay());

        Log::warning('Admin login : échec', [
            'ip'         => $ip,
            'echecs'     => $echecs,
            'user_agent' => substr((string) $request->userAgent(), 0, 200),
        ]);

        if (isset(self::PALIERS[$echecs])) {
            $secondes = self::PALIERS[$echecs];
            Cache::put($cleVerrou, time() + $secondes, $secondes);
            Log::alert('Admin login : IP verrouillée', ['ip' => $ip, 'echecs' => $echecs, 'duree_s' => $secondes]);

            return back()->with('error', 'Trop de tentatives. Réessayez dans ' . $this->duree($secondes) . '.');
        }

        return back()->with('error', 'Mot de passe incorrect.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_logged_in');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    private function duree(int $s): string
    {
        return match (true) {
            $s >= 3600 => (int) ceil($s / 3600) . ' h',
            $s >= 60   => (int) ceil($s / 60) . ' min',
            default    => $s . ' s',
        };
    }
}

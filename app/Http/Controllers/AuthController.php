<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login utama.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect('/admin');
            }

            return redirect()->route('portal');
        }

        $demoUsers = User::where('is_active', true)->get();

        return view('auth.login', compact('demoUsers'));
    }

    /**
     * Quick dev login untuk kemudahan testing lokal.
     */
    public function devLogin(User $user): RedirectResponse
    {
        Auth::login($user);

        if ($user->isAdmin()) {
            return redirect('/admin');
        }

        return redirect()->route('portal');
    }

    /**
     * Redirect ke Google OAuth.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('login')->with('error', 'Google Client ID & Secret belum dikonfigurasi di .env. Untuk pengujian lokal saat ini, silakan klik salah satu akun pada Simulasi Lokal di bawah.');
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google OAuth.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal terhubung dengan akun Google: '.$e->getMessage());
        }

        // Cari user berdasarkan email
        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Email '.$googleUser->getEmail().' belum terdaftar di sistem SMA Global Darussalam. Silakan hubungi admin sekolah/TU.');
        }

        if (! $user->is_active) {
            return redirect()->route('login')->with('error', 'Akun Anda dinonaktifkan oleh administrator.');
        }

        // Update google_id & avatar
        $user->update([
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
        ]);

        Auth::login($user, remember: true);

        if ($user->isAdmin()) {
            return redirect('/admin');
        }

        return redirect()->route('portal');
    }

    /**
     * Logout pengguna.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

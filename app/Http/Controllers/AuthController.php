<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    /**
     * Handle login form submission.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (! $user->isActive()) {
                Auth::logout();
                $request->session()->invalidate();

                return back()->withErrors([
                    'username' => 'Akun Anda belum aktif atau telah ditolak.',
                ])->onlyInput('username');
            }

            return $this->redirectByRole($user->role);
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirect user to their role-specific dashboard.
     */
    private function redirectByRole(string $role): RedirectResponse
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'mentor' => redirect()->route('mentor.dashboard'),
            'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            default => redirect()->route('login'),
        };
    }
}

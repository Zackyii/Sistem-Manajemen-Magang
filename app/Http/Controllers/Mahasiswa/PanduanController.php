<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PanduanController extends Controller
{
    /**
     * Show the rules/agreement page.
     */
    public function index(): View
    {
        return view('mahasiswa.panduan.index');
    }

    /**
     * Record that the mahasiswa has agreed to the rules.
     */
    public function agree(): RedirectResponse
    {
        auth()->user()->update(['has_agreed_to_rules' => true]);

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Selamat datang! Anda telah menyetujui peraturan magang.');
    }
}

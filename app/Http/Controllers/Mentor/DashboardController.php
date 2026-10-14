<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Tanggalan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the mentor dashboard.
     */
    public function index(): View
    {
        $mentor = auth()->user()->mentor;

        $mahasiswas = $mentor->bidang
            ? $mentor->bidang->mahasiswas()->where('status_verifikasi', 'disetujui')->get()
            : collect();

        $stats = [
            'total_mahasiswa' => $mahasiswas->count(),
            'kehadiran_pending' => Kehadiran::whereIn('mahasiswa_id', $mahasiswas->pluck('id'))
                ->where('status_mentor', 'pending')
                ->whereDate('tanggal', today())
                ->count(),
            'logbook_pending' => Tanggalan::whereIn('mahasiswa_id', $mahasiswas->pluck('id'))
                ->where('status_logbook', 'submitted')
                ->count(),
            'kehadiran_hari_ini' => Kehadiran::whereIn('mahasiswa_id', $mahasiswas->pluck('id'))
                ->whereDate('tanggal', today())
                ->count(),
        ];

        $kehadiranHariIni = Kehadiran::with('mahasiswa')
            ->whereIn('mahasiswa_id', $mahasiswas->pluck('id'))
            ->whereDate('tanggal', today())
            ->where('status_mentor', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('mentor.dashboard', compact('mentor', 'mahasiswas', 'stats', 'kehadiranHariIni'));
    }

    /**
     * Update the quota for the mentor's bidang.
     */
    public function updateKuota(Request $request): RedirectResponse
    {
        $request->validate([
            'kuota' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $mentor = auth()->user()->mentor;

        if (! $mentor->bidang) {
            return back()->withErrors(['kuota' => 'Anda belum terdaftar di bidang manapun.']);
        }

        $mentor->bidang->update(['kuota' => $request->kuota]);

        return back()->with('success', 'Kuota bidang berhasil diperbarui menjadi '.$request->kuota.' peserta.');
    }
}

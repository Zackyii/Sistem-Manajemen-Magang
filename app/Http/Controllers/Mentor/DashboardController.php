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
     * Show the mentor dashboard — location monitoring view.
     * Approve/reject removed; shows attendance + location data for today.
     */
    public function index(): View
    {
        $mentor = auth()->user()->mentor;

        $mahasiswas = $mentor->bidang
            ? $mentor->bidang->mahasiswas()->where('status_verifikasi', 'disetujui')->get()
            : collect();

        $mahasiswaIds = $mahasiswas->pluck('id');

        $stats = [
            'total_mahasiswa' => $mahasiswas->count(),
            'kehadiran_hari_ini' => Kehadiran::whereIn('mahasiswa_id', $mahasiswaIds)
                ->whereDate('tanggal', today())
                ->count(),
            'logbook_pending' => Tanggalan::whereIn('mahasiswa_id', $mahasiswaIds)
                ->where('status_logbook', 'submitted')
                ->count(),
            'hadir_hari_ini' => Kehadiran::whereIn('mahasiswa_id', $mahasiswaIds)
                ->whereDate('tanggal', today())
                ->whereIn('status', ['hadir', 'terlambat'])
                ->count(),
        ];

        // Today's attendance with location info — no pending filter (monitoring, not verification)
        $kehadiranHariIni = Kehadiran::with('mahasiswa')
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->whereDate('tanggal', today())
            ->latest()
            ->take(10)
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

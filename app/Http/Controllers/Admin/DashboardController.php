<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Pengumuman;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index(): View
    {
        $stats = [
            'total_mahasiswa' => Mahasiswa::count(),
            'mahasiswa_aktif' => Mahasiswa::where('status_verifikasi', 'disetujui')->count(),
            'mahasiswa_pending' => Mahasiswa::where('status_verifikasi', 'pending')->count(),
            'total_mentor' => Mentor::count(),
            'kehadiran_hari_ini' => Kehadiran::whereDate('tanggal', today())->where('status_mentor', 'disetujui')->count(),
            'kehadiran_pending' => Kehadiran::where('status_mentor', 'pending')->count(),
        ];

        $mahasiswaPending = Mahasiswa::with(['user', 'bidang'])
            ->where('status_verifikasi', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $pengumumanTerbaru = Pengumuman::with('admin')
            ->latest('tanggal_publikasi')
            ->take(3)
            ->get();

        return view('admin.dashboard', compact('stats', 'mahasiswaPending', 'pengumumanTerbaru'));
    }
}

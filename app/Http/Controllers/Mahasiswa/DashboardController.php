<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Pengumuman;
use App\Models\Tanggalan;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show mahasiswa dashboard.
     */
    public function index(): View
    {
        $mahasiswa = auth()->user()->mahasiswa;

        $kehadiranHariIni = Kehadiran::where('mahasiswa_id', $mahasiswa->id)
            ->whereDate('tanggal', today())
            ->first();

        $bulanIni = now();
        $ringkasanBulan = [
            'hadir' => Kehadiran::where('mahasiswa_id', $mahasiswa->id)
                ->whereMonth('tanggal', $bulanIni->month)
                ->whereYear('tanggal', $bulanIni->year)
                ->whereIn('status', ['hadir', 'terlambat'])
                ->count(),
            'izin_sakit' => Kehadiran::where('mahasiswa_id', $mahasiswa->id)
                ->whereMonth('tanggal', $bulanIni->month)
                ->whereYear('tanggal', $bulanIni->year)
                ->whereIn('status', ['izin', 'sakit'])
                ->count(),
        ];

        $logbookIsi = Tanggalan::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status_logbook', ['submitted', 'disetujui'])
            ->count();

        $tugasMendatang = Tanggalan::where('mahasiswa_id', $mahasiswa->id)
            ->whereNotNull('info_tugas')
            ->where(function ($q) {
                $q->whereNull('deadline_tugas')->orWhere('deadline_tugas', '>=', today());
            })
            ->orderBy('deadline_tugas')
            ->take(3)
            ->get();

        $pengumuman = Pengumuman::where(function ($q) use ($mahasiswa) {
            $q->whereNull('bidang_id')
                ->orWhere('bidang_id', $mahasiswa->bidang_id);
        })
            ->latest('tanggal_publikasi')
            ->take(3)
            ->get();

        return view('mahasiswa.dashboard', compact(
            'mahasiswa',
            'kehadiranHariIni',
            'ringkasanBulan',
            'logbookIsi',
            'tugasMendatang',
            'pengumuman'
        ));
    }
}

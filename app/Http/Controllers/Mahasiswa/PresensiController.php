<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PresensiController extends Controller
{
    /**
     * Show the presensi page.
     */
    public function index(): View
    {
        $mahasiswa = auth()->user()->mahasiswa;

        $kehadiranHariIni = Kehadiran::where('mahasiswa_id', $mahasiswa->id)
            ->whereDate('tanggal', today())
            ->first();

        $riwayatKehadiran = Kehadiran::where('mahasiswa_id', $mahasiswa->id)
            ->latest('tanggal')
            ->take(10)
            ->get();

        $bulanIni = now();
        $ringkasanBulan = [
            'hadir' => Kehadiran::where('mahasiswa_id', $mahasiswa->id)
                ->whereMonth('tanggal', $bulanIni->month)->whereYear('tanggal', $bulanIni->year)
                ->whereIn('status', ['hadir', 'terlambat'])->count(),
            'izin_sakit' => Kehadiran::where('mahasiswa_id', $mahasiswa->id)
                ->whereMonth('tanggal', $bulanIni->month)->whereYear('tanggal', $bulanIni->year)
                ->whereIn('status', ['izin', 'sakit'])->count(),
        ];

        $totalHariKerja = Kehadiran::where('mahasiswa_id', $mahasiswa->id)->count();
        $persentaseKehadiran = $totalHariKerja > 0
            ? round(($ringkasanBulan['hadir'] / max($totalHariKerja, 1)) * 100)
            : 0;

        return view('mahasiswa.presensi.index', compact(
            'mahasiswa',
            'kehadiranHariIni',
            'riwayatKehadiran',
            'ringkasanBulan',
            'persentaseKehadiran'
        ));
    }

    /**
     * Store attendance check-in with selfie and GPS.
     * NOTE: Photo is submitted as base64 from the camera capture — no gallery upload allowed.
     */
    public function checkIn(Request $request): RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;

        if (Kehadiran::where('mahasiswa_id', $mahasiswa->id)->whereDate('tanggal', today())->exists()) {
            return back()->withErrors(['presensi' => 'Anda sudah melakukan presensi hari ini.']);
        }

        $request->validate([
            'foto_selfie' => ['required', 'string'], // base64 image
            'koordinat_lat' => ['required', 'numeric', 'between:-90,90'],
            'koordinat_lng' => ['required', 'numeric', 'between:-180,180'],
            'nama_lokasi' => ['nullable', 'string', 'max:255'],
        ]);

        // Decode and store base64 selfie photo
        $base64Image = $request->foto_selfie;
        if (str_contains($base64Image, ',')) {
            $base64Image = explode(',', $base64Image)[1];
        }

        $imageName = 'selfies/'.$mahasiswa->id.'_'.date('Ymd_His').'.jpg';
        Storage::disk('public')->put($imageName, base64_decode($base64Image));

        $waktuMasuk = now()->toTimeString();
        $batasTerlambat = '08:00:00';
        $status = $waktuMasuk > $batasTerlambat ? 'terlambat' : 'hadir';

        Kehadiran::create([
            'mahasiswa_id' => $mahasiswa->id,
            'tanggal' => today()->toDateString(),
            'waktu_masuk' => $waktuMasuk,
            'foto_selfie' => $imageName,
            'koordinat_lat' => $request->koordinat_lat,
            'koordinat_lng' => $request->koordinat_lng,
            'nama_lokasi' => $request->nama_lokasi,
            'status' => $status,
            'status_mentor' => 'pending',
        ]);

        return redirect()->route('mahasiswa.presensi.index')
            ->with('success', 'Presensi masuk berhasil dicatat. Status: '.ucfirst($status));
    }

    /**
     * Store check-out time.
     */
    public function checkOut(Request $request): RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;

        $kehadiran = Kehadiran::where('mahasiswa_id', $mahasiswa->id)
            ->whereDate('tanggal', today())
            ->firstOrFail();

        if ($kehadiran->waktu_keluar) {
            return redirect()->route('mahasiswa.presensi.index')
                ->withErrors(['presensi' => 'Anda sudah melakukan check-out hari ini.']);
        }

        $kehadiran->update(['waktu_keluar' => now()->toTimeString()]);

        return redirect()->route('mahasiswa.presensi.index')
            ->with('success', 'Check-out berhasil dicatat.');
    }
}

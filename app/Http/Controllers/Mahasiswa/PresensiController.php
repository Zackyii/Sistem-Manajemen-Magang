<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
     * Store attendance check-in.
     * Handles three types: hadir (selfie+GPS), izin (file bukti), sakit (file bukti).
     */
    public function checkIn(Request $request): RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;

        if (Kehadiran::where('mahasiswa_id', $mahasiswa->id)->whereDate('tanggal', today())->exists()) {
            return back()->withErrors(['presensi' => 'Anda sudah melakukan presensi hari ini.']);
        }

        $jenisPresensi = $request->input('jenis_presensi', 'hadir');

        // Validate based on type
        if ($jenisPresensi === 'hadir') {
            $request->validate([
                'foto_selfie' => ['required', 'string'],
                'koordinat_lat' => ['required', 'numeric', 'between:-90,90'],
                'koordinat_lng' => ['required', 'numeric', 'between:-180,180'],
                'nama_lokasi' => ['nullable', 'string', 'max:255'],
            ]);
        } else {
            $request->validate([
                'file_bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
                'keterangan' => ['nullable', 'string', 'max:500'],
                'koordinat_lat' => ['nullable', 'numeric', 'between:-90,90'],
                'koordinat_lng' => ['nullable', 'numeric', 'between:-180,180'],
                'nama_lokasi' => ['nullable', 'string', 'max:255'],
            ]);
        }

        $data = [
            'mahasiswa_id' => $mahasiswa->id,
            'tanggal' => today()->toDateString(),
            'waktu_masuk' => now()->toTimeString(),
            'koordinat_lat' => $request->koordinat_lat,
            'koordinat_lng' => $request->koordinat_lng,
            'nama_lokasi' => $request->nama_lokasi,
            'status_mentor' => 'pending',
            'keterangan' => $request->keterangan,
        ];

        if ($jenisPresensi === 'hadir') {
            // Decode and store base64 selfie — stored in private disk
            $base64Image = $request->foto_selfie;
            if (str_contains($base64Image, ',')) {
                $base64Image = explode(',', $base64Image)[1];
            }
            $decoded = base64_decode($base64Image, true);
            if ($decoded === false || strlen($decoded) < 100) {
                return back()->withErrors(['foto_selfie' => 'Data foto tidak valid.']);
            }

            $imageName = 'selfies/'.$mahasiswa->id.'_'.date('Ymd_His').'.jpg';
            Storage::disk('local')->put($imageName, $decoded);
            $data['foto_selfie'] = $imageName;

            // Determine status by time
            $waktuMasuk = now()->toTimeString();
            $batasTerlambat = '08:00:00';
            $data['status'] = $waktuMasuk > $batasTerlambat ? 'terlambat' : 'hadir';
        } elseif ($jenisPresensi === 'izin') {
            $data['status'] = 'izin';
            $data['file_bukti'] = $this->storeUploadedFile($request->file('file_bukti'), 'bukti-izin', $mahasiswa->id);
        } else {
            // sakit
            $data['status'] = 'sakit';
            $data['file_bukti'] = $this->storeUploadedFile($request->file('file_bukti'), 'bukti-sakit', $mahasiswa->id);
        }

        Kehadiran::create($data);

        $statusLabel = match ($data['status']) {
            'hadir' => 'Hadir',
            'terlambat' => 'Terlambat',
            'izin' => 'Izin',
            'sakit' => 'Izin Sakit',
            default => ucfirst($data['status']),
        };

        return redirect()->route('mahasiswa.presensi.index')
            ->with('success', "Presensi berhasil dicatat. Status: {$statusLabel}.");
    }

    /**
     * Store check-out time and GPS location.
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

        $request->validate([
            'foto_checkout' => ['nullable', 'string'],
            'koordinat_lat_checkout' => ['nullable', 'numeric', 'between:-90,90'],
            'koordinat_lng_checkout' => ['nullable', 'numeric', 'between:-180,180'],
            'nama_lokasi_checkout' => ['nullable', 'string', 'max:255'],
        ]);

        $updateData = [
            'waktu_keluar' => now()->toTimeString(),
            'koordinat_lat_checkout' => $request->koordinat_lat_checkout,
            'koordinat_lng_checkout' => $request->koordinat_lng_checkout,
            'nama_lokasi_checkout' => $request->nama_lokasi_checkout,
        ];

        // Optional checkout selfie
        if ($request->filled('foto_checkout')) {
            $base64 = $request->foto_checkout;
            if (str_contains($base64, ',')) {
                $base64 = explode(',', $base64)[1];
            }
            $decoded = base64_decode($base64, true);
            if ($decoded !== false && strlen($decoded) >= 100) {
                $path = 'selfies/checkout_'.$mahasiswa->id.'_'.date('Ymd_His').'.jpg';
                Storage::disk('local')->put($path, $decoded);
                $updateData['foto_checkout'] = $path;
            }
        }

        $kehadiran->update($updateData);

        return redirect()->route('mahasiswa.presensi.index')
            ->with('success', 'Check-out berhasil dicatat.');
    }

    /**
     * Store an uploaded file to the private local disk.
     *
     * @return string The relative path stored in the database.
     */
    private function storeUploadedFile(UploadedFile $file, string $subfolder, int $mahasiswaId): string
    {
        $ext = $file->getClientOriginalExtension();
        $filename = $subfolder.'/'.$mahasiswaId.'_'.date('Ymd_His').'.'.$ext;
        $file->storeAs(dirname($filename), basename($filename), ['disk' => 'local']);

        return $filename;
    }
}

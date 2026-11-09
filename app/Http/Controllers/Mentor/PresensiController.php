<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PresensiController extends Controller
{
    /**
     * List attendance for mentor's bidang — location monitoring view.
     * No approve/reject — replaced by monitoring and export.
     */
    public function index(Request $request): View
    {
        $mentor = auth()->user()->mentor;

        $mahasiswas = $mentor->bidang
            ? $mentor->bidang->mahasiswas()->where('status_verifikasi', 'disetujui')->get()
            : collect();

        $mahasiswaIds = $mahasiswas->pluck('id');

        // Available universities from actual mahasiswa data (within mentor's scope)
        $universitas = $mahasiswas->pluck('asal_universitas')->unique()->sort()->values();

        $query = Kehadiran::with('mahasiswa')
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->when($request->tanggal, fn ($q) => $q->whereDate('tanggal', $request->tanggal))
            ->when(! $request->tanggal && $request->filter === 'daily', fn ($q) => $q->whereDate('tanggal', today()))
            ->when($request->filter === 'weekly', fn ($q) => $q->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]))
            ->when($request->filter === 'monthly', fn ($q) => $q->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year))
            ->when($request->mahasiswa_id, fn ($q) => $q->where('mahasiswa_id', $request->mahasiswa_id))
            ->when($request->universitas, function ($q) use ($request, $mahasiswaIds) {
                $q->whereIn('mahasiswa_id', function ($sub) use ($request, $mahasiswaIds) {
                    $sub->select('id')->from('mahasiswas')
                        ->whereIn('id', $mahasiswaIds)
                        ->where('asal_universitas', $request->universitas);
                });
            })
            ->latest('tanggal');

        $kehadirans = $query->paginate(20)->withQueryString();

        return view('mentor.presensi.index', compact('kehadirans', 'mahasiswas', 'mentor', 'universitas'));
    }

    /**
     * Export attendance as CSV.
     * Filters: period (daily/weekly/monthly/all), mahasiswa_id, universitas.
     * Scoped strictly to mentor's bidang — prevents cross-bidang access.
     */
    public function export(Request $request): Response
    {
        $mentor = auth()->user()->mentor;

        $mahasiswas = $mentor->bidang
            ? $mentor->bidang->mahasiswas()->where('status_verifikasi', 'disetujui')->get()
            : collect();

        $mahasiswaIds = $mahasiswas->pluck('id');

        // Apply filters
        $query = Kehadiran::with('mahasiswa')
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->when($request->mahasiswa_id && $mahasiswaIds->contains($request->mahasiswa_id),
                fn ($q) => $q->where('mahasiswa_id', $request->mahasiswa_id)
            )
            ->when($request->universitas, function ($q) use ($request, $mahasiswaIds) {
                $q->whereIn('mahasiswa_id', function ($sub) use ($request, $mahasiswaIds) {
                    $sub->select('id')->from('mahasiswas')
                        ->whereIn('id', $mahasiswaIds)
                        ->where('asal_universitas', $request->universitas);
                });
            })
            ->orderBy('tanggal')
            ->orderBy('mahasiswa_id');

        // Apply period filter
        $period = $request->input('period', 'monthly');
        $query = match ($period) {
            'daily' => $query->whereDate('tanggal', $request->tanggal ?? today()),
            'weekly' => $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]),
            'all' => $query, // entire period
            default => $query->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year),
        };

        $rows = $query->get();
        $bidangNama = $mentor->bidang?->nama_bidang ?? '-';

        // Build CSV in-memory
        $output = fopen('php://temp', 'r+');
        fputcsv($output, [
            'No', 'Nama Mahasiswa', 'NIM', 'Universitas', 'Program Studi',
            'Bidang', 'Tanggal', 'Jam Masuk', 'Jam Keluar',
            'Jenis Presensi', 'Status Masuk',
            'Koordinat Masuk', 'Lokasi Masuk',
            'Koordinat Keluar', 'Lokasi Keluar',
            'Keterangan',
        ]);

        foreach ($rows as $i => $item) {
            $mhs = $item->mahasiswa;
            $latIn = $item->koordinat_lat ? "{$item->koordinat_lat},{$item->koordinat_lng}" : '-';
            $latOut = $item->koordinat_lat_checkout ? "{$item->koordinat_lat_checkout},{$item->koordinat_lng_checkout}" : '-';

            fputcsv($output, [
                $i + 1,
                $mhs?->nama ?? '-',
                $mhs?->nim ?? '-',
                $mhs?->asal_universitas ?? '-',
                $mhs?->program_studi ?? '-',
                $bidangNama,
                Carbon::parse($item->tanggal)->format('d/m/Y'),
                $item->waktu_masuk ? Carbon::parse($item->waktu_masuk)->format('H:i') : '-',
                $item->waktu_keluar ? Carbon::parse($item->waktu_keluar)->format('H:i') : '-',
                ucfirst($item->status),
                ucfirst($item->status),
                $latIn,
                $item->nama_lokasi ?? '-',
                $latOut,
                $item->nama_lokasi_checkout ?? '-',
                $item->keterangan ?? '-',
            ]);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        $filename = 'presensi_'.str_replace(' ', '_', strtolower($bidangNama)).'_'.now()->format('Ymd_His').'.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store',
        ]);
    }
}

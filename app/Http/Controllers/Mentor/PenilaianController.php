<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    /**
     * List mahasiswas for grading.
     */
    public function index(): View
    {
        $mentor = auth()->user()->mentor;
        $mahasiswas = $mentor->bidang
            ? $mentor->bidang->mahasiswas()
                ->where('status_verifikasi', 'disetujui')
                ->with('penilaian')
                ->get()
            : collect();

        return view('mentor.penilaian.index', compact('mentor', 'mahasiswas'));
    }

    /**
     * Store or update a final grade.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! $request->filled('nilai_akhir') && $request->filled('nilai_kehadiran')) {
            $kehadiran = (float) $request->input('nilai_kehadiran', 0);
            $tugas = (float) $request->input('nilai_tugas', 0);
            $sikap = (float) $request->input('nilai_sikap', 0);
            $calculatedAkhir = round(($kehadiran * 0.3) + ($tugas * 0.4) + ($sikap * 0.3), 1);
            $request->merge(['nilai_akhir' => $calculatedAkhir]);
        }

        $validated = $request->validate([
            'mahasiswa_id' => ['required', 'exists:mahasiswas,id'],
            'nilai_kehadiran' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_tugas' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_sikap' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_akhir' => ['required', 'numeric', 'min:0', 'max:100'],
            'catatan_evaluasi' => ['nullable', 'string', 'max:1000'],
        ]);

        $mentor = auth()->user()->mentor;

        $nilaiAkhir = (float) $validated['nilai_akhir'];
        $grade = match (true) {
            $nilaiAkhir >= 85 => 'A',
            $nilaiAkhir >= 78 => 'B+',
            $nilaiAkhir >= 70 => 'B',
            $nilaiAkhir >= 60 => 'C',
            $nilaiAkhir >= 50 => 'D',
            default => 'E',
        };

        Penilaian::updateOrCreate(
            ['mahasiswa_id' => $validated['mahasiswa_id'], 'mentor_id' => $mentor->id],
            [
                'nilai_kehadiran' => $validated['nilai_kehadiran'] ?? null,
                'nilai_tugas' => $validated['nilai_tugas'] ?? null,
                'nilai_sikap' => $validated['nilai_sikap'] ?? null,
                'nilai_akhir' => $nilaiAkhir,
                'grade' => $grade,
                'catatan_evaluasi' => $validated['catatan_evaluasi'] ?? null,
                'tanggal_penilaian' => now(),
            ]
        );

        return redirect()->route('mentor.penilaian.index')
            ->with('success', 'Penilaian berhasil disimpan.');
    }
}

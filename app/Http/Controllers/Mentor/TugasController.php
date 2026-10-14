<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Tanggalan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TugasController extends Controller
{
    /**
     * Show task assignment form.
     */
    public function index(): View
    {
        $mentor = auth()->user()->mentor;
        $mahasiswas = $mentor->bidang
            ? $mentor->bidang->mahasiswas()->where('status_verifikasi', 'disetujui')->get()
            : collect();

        $tugas = Tanggalan::with('mahasiswa')
            ->whereNotNull('info_tugas')
            ->whereIn('mahasiswa_id', $mahasiswas->pluck('id'))
            ->latest()
            ->paginate(10);

        $tugasTerbaru = $tugas;

        return view('mentor.tugas.index', compact('mentor', 'mahasiswas', 'tugasTerbaru', 'tugas'));
    }

    /**
     * Assign a task to a mahasiswa.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->mergeIfMissing(['tanggal' => today()->toDateString()]);

        $validated = $request->validate([
            'mahasiswa_id' => ['required', 'exists:mahasiswas,id'],
            'tanggal' => ['required', 'date'],
            'info_tugas' => ['required', 'string', 'max:500'],
            'deadline_tugas' => ['nullable', 'date'],
        ]);

        $mentor = auth()->user()->mentor;

        $entry = Tanggalan::where('mahasiswa_id', $validated['mahasiswa_id'])
            ->whereDate('tanggal', $validated['tanggal'])
            ->first();

        if ($entry) {
            $entry->update([
                'mentor_id' => $mentor->id,
                'info_tugas' => $validated['info_tugas'],
                'deadline_tugas' => $validated['deadline_tugas'],
            ]);
        } else {
            Tanggalan::create([
                'mahasiswa_id' => $validated['mahasiswa_id'],
                'tanggal' => $validated['tanggal'],
                'mentor_id' => $mentor->id,
                'info_tugas' => $validated['info_tugas'],
                'deadline_tugas' => $validated['deadline_tugas'],
            ]);
        }

        return redirect()->route('mentor.tugas.index')
            ->with('success', 'Tugas berhasil diberikan.');
    }
}

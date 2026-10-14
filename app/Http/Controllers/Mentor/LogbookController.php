<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Tanggalan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogbookController extends Controller
{
    /**
     * List submitted logbooks for mentor's bidang.
     */
    public function index(Request $request): View
    {
        $mentor = auth()->user()->mentor;

        $mahasiswas = $mentor->bidang
            ? $mentor->bidang->mahasiswas()->where('status_verifikasi', 'disetujui')->get()
            : collect();

        $mahasiswaIds = $mahasiswas->pluck('id');

        $logbooks = Tanggalan::with('mahasiswa')
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->whereIn('status_logbook', ['submitted', 'disetujui', 'ditolak'])
            ->when($request->status, fn ($q) => $q->where('status_logbook', $request->status))
            ->when($request->mahasiswa_id, fn ($q) => $q->where('mahasiswa_id', $request->mahasiswa_id))
            ->latest('tanggal')
            ->paginate(20)
            ->withQueryString();

        return view('mentor.logbook.index', compact('logbooks', 'mahasiswas', 'mentor'));
    }

    /**
     * Show a single logbook entry.
     */
    public function show(Tanggalan $tanggalan): View
    {
        $tanggalan->load(['mahasiswa.bidang', 'kehadiran']);

        return view('mentor.logbook.show', compact('tanggalan'));
    }

    /**
     * Approve a logbook entry.
     */
    public function approve(Request $request, Tanggalan $tanggalan): RedirectResponse
    {
        $tanggalan->update([
            'status_logbook' => 'disetujui',
            'catatan_mentor' => $request->input('catatan_mentor', $tanggalan->catatan_mentor),
            'mentor_id' => auth()->user()->mentor->id,
        ]);

        return redirect()->route('mentor.logbook.index')->with('success', 'Logbook berhasil disetujui.');
    }

    /**
     * Reject a logbook entry with notes.
     */
    public function reject(Request $request, Tanggalan $tanggalan): RedirectResponse
    {
        $request->validate([
            'catatan_mentor' => ['required', 'string', 'max:500'],
        ]);

        $tanggalan->update([
            'status_logbook' => 'ditolak',
            'catatan_mentor' => $request->catatan_mentor,
            'mentor_id' => auth()->user()->mentor->id,
        ]);

        return redirect()->route('mentor.logbook.index')->with('success', 'Logbook telah ditolak dengan catatan.');
    }
}

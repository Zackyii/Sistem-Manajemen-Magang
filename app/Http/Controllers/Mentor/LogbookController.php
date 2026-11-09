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
            ->when($request->tanggal_dari, fn ($q) => $q->whereDate('tanggal', '>=', $request->tanggal_dari))
            ->when($request->tanggal_sampai, fn ($q) => $q->whereDate('tanggal', '<=', $request->tanggal_sampai))
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
        $this->authorizeMentorScope($tanggalan);

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
        $this->authorizeMentorScope($tanggalan);

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

    /**
     * Bulk approve selected logbook entries.
     * Backend validates each ID is within mentor's scope and is in 'submitted' status.
     */
    public function bulkApprove(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ]);

        $mentor = auth()->user()->mentor;
        $mahasiswaIds = $mentor->bidang
            ? $mentor->bidang->mahasiswas()->pluck('id')
            : collect();

        // Only approve submitted entries that belong to mentor's mahasiswa
        $approved = Tanggalan::whereIn('id', $request->ids)
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->where('status_logbook', 'submitted')
            ->get();

        $approvedCount = $approved->count();
        $skippedCount = count($request->ids) - $approvedCount;

        $approved->each(fn ($t) => $t->update([
            'status_logbook' => 'disetujui',
            'mentor_id' => $mentor->id,
        ]));

        $message = "{$approvedCount} logbook berhasil disetujui.";
        if ($skippedCount > 0) {
            $message .= " {$skippedCount} item dilewati (di luar cakupan atau status tidak sesuai).";
        }

        return back()->with('success', $message);
    }

    /**
     * Ensure the tanggalan belongs to a mahasiswa in this mentor's bidang.
     */
    private function authorizeMentorScope(Tanggalan $tanggalan): void
    {
        $mentor = auth()->user()->mentor;
        $allowedIds = $mentor->bidang
            ? $mentor->bidang->mahasiswas()->pluck('id')
            : collect();

        abort_if(! $allowedIds->contains($tanggalan->mahasiswa_id), 403, 'Logbook ini bukan tanggung jawab Anda.');
    }
}

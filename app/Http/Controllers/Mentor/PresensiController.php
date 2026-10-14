<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PresensiController extends Controller
{
    /**
     * List attendance for mentor's bidang with filters.
     */
    public function index(Request $request): View
    {
        $mentor = auth()->user()->mentor;

        $mahasiswas = $mentor->bidang
            ? $mentor->bidang->mahasiswas()->where('status_verifikasi', 'disetujui')->get()
            : collect();

        $mahasiswaIds = $mahasiswas->pluck('id');

        $query = Kehadiran::with('mahasiswa')
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->when($request->tanggal, fn ($q) => $q->whereDate('tanggal', $request->tanggal))
            ->when(! $request->tanggal && $request->filter === 'daily', fn ($q) => $q->whereDate('tanggal', today()))
            ->when($request->filter === 'weekly', fn ($q) => $q->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]))
            ->when($request->filter === 'monthly', fn ($q) => $q->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year))
            ->when($request->status, fn ($q) => $q->where('status_mentor', $request->status))
            ->when($request->mahasiswa_id, fn ($q) => $q->where('mahasiswa_id', $request->mahasiswa_id))
            ->latest('tanggal');

        $kehadirans = $query->paginate(20)->withQueryString();

        return view('mentor.presensi.index', compact('kehadirans', 'mahasiswas', 'mentor'));
    }

    /**
     * Approve a single attendance record.
     */
    public function approve(Kehadiran $kehadiran): RedirectResponse
    {
        $kehadiran->update(['status_mentor' => 'disetujui']);

        return redirect()->route('mentor.presensi.index')->with('success', 'Kehadiran berhasil disetujui.');
    }

    /**
     * Reject a single attendance record.
     */
    public function reject(Request $request, Kehadiran $kehadiran): RedirectResponse
    {
        $request->validate([
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $kehadiran->update([
            'status_mentor' => 'ditolak',
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('mentor.presensi.index')->with('success', 'Kehadiran telah ditolak.');
    }

    /**
     * Bulk approve attendance records.
     */
    public function bulkApprove(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:kehadiran,id'],
        ]);

        $mentor = auth()->user()->mentor;
        $mahasiswaIds = $mentor->bidang
            ? $mentor->bidang->mahasiswas()->pluck('id')
            : collect();

        Kehadiran::whereIn('id', $request->ids)
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->where('status_mentor', 'pending')
            ->update(['status_mentor' => 'disetujui']);

        return back()->with('success', count($request->ids).' kehadiran berhasil disetujui.');
    }
}

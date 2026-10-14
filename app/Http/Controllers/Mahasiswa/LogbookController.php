<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Tanggalan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class LogbookController extends Controller
{
    /**
     * List all logbook entries.
     */
    public function index(): View
    {
        $mahasiswa = auth()->user()->mahasiswa;

        $stats = [
            'total_jam' => Tanggalan::where('mahasiswa_id', $mahasiswa->id)->sum('durasi_jam'),
            'total_kegiatan' => Tanggalan::where('mahasiswa_id', $mahasiswa->id)->whereNotNull('aktivitas_utama')->count(),
            'disetujui' => Tanggalan::where('mahasiswa_id', $mahasiswa->id)->where('status_logbook', 'disetujui')->count(),
            'pending' => Tanggalan::where('mahasiswa_id', $mahasiswa->id)->where('status_logbook', 'submitted')->count(),
        ];

        $logbooks = Tanggalan::where('mahasiswa_id', $mahasiswa->id)
            ->whereNotNull('aktivitas_utama')
            ->latest('tanggal')
            ->paginate(15);

        return view('mahasiswa.logbook.index', compact('mahasiswa', 'stats', 'logbooks'));
    }

    /**
     * Show create/edit form for a logbook entry.
     */
    public function create(): View
    {
        $mahasiswa = auth()->user()->mahasiswa;
        $tanggal = request('tanggal', today()->toDateString());
        $existing = Tanggalan::where('mahasiswa_id', $mahasiswa->id)->where('tanggal', $tanggal)->first();

        return view('mahasiswa.logbook.create', compact('mahasiswa', 'tanggal', 'existing'));
    }

    /**
     * Store or update a logbook entry.
     */
    public function store(Request $request): RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'aktivitas_utama' => ['required', 'string', 'max:255'],
            'deskripsi_kegiatan' => ['required', 'string'],
            'durasi_jam' => ['required', 'numeric', 'min:0.5', 'max:24'],
            'status_logbook' => ['nullable', 'string'],
            'submit' => ['nullable', 'boolean'],
        ]);

        $status = ($request->input('status_logbook') === 'submitted' || $request->boolean('submit')) ? 'submitted' : 'draft';

        $entry = Tanggalan::where('mahasiswa_id', $mahasiswa->id)
            ->whereDate('tanggal', $validated['tanggal'])
            ->first();

        if ($entry) {
            $entry->update([
                'aktivitas_utama' => $validated['aktivitas_utama'],
                'deskripsi_kegiatan' => $validated['deskripsi_kegiatan'],
                'durasi_jam' => $validated['durasi_jam'],
                'status_logbook' => $status,
            ]);
        } else {
            Tanggalan::create([
                'mahasiswa_id' => $mahasiswa->id,
                'tanggal' => $validated['tanggal'],
                'aktivitas_utama' => $validated['aktivitas_utama'],
                'deskripsi_kegiatan' => $validated['deskripsi_kegiatan'],
                'durasi_jam' => $validated['durasi_jam'],
                'status_logbook' => $status,
            ]);
        }

        return redirect()->route('mahasiswa.logbook.index')
            ->with('success', $status === 'submitted' ? 'Logbook berhasil diajukan ke mentor.' : 'Draft logbook disimpan.');
    }

    /**
     * Export logbook as PDF with filter support.
     */
    public function exportPdf(Request $request): Response
    {
        $mahasiswa = auth()->user()->mahasiswa->load(['bidang', 'user']);

        $query = Tanggalan::where('mahasiswa_id', $mahasiswa->id)
            ->whereNotNull('aktivitas_utama')
            ->orderBy('tanggal');

        $filter = $request->filter ?? 'monthly';

        $query = match ($filter) {
            'daily' => $query->whereDate('tanggal', $request->tanggal ?? today()),
            'weekly' => $query->whereBetween('tanggal', [
                now()->startOfWeek(), now()->endOfWeek(),
            ]),
            default => $query->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year),
        };

        $logbooks = $query->get();
        $totalJam = $logbooks->sum('durasi_jam');

        $pdf = Pdf::loadView('mahasiswa.logbook.pdf', compact('mahasiswa', 'logbooks', 'totalJam', 'filter'))
            ->setPaper('a4', 'portrait');

        $filename = 'Logbook_'.$mahasiswa->nama.'_'.now()->format('Ymd').'.pdf';

        return $pdf->download($filename);
    }
}

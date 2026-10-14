<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pesan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesanController extends Controller
{
    /**
     * Show the message/report form.
     */
    public function index(): View
    {
        $mahasiswa = auth()->user()->mahasiswa;
        $pesanTerkirim = Pesan::where('pengirim_id', auth()->id())
            ->with(['penerima'])
            ->latest('waktu_kirim')
            ->take(10)
            ->get();

        // Admin users for system issue reports
        $admins = User::where('role', 'admin')->where('status', 'active')->get();

        // Mentor in same bidang for sick leave
        $mentorUser = $mahasiswa->bidang
            ? User::whereHas('mentor', fn ($q) => $q->where('bidang_id', $mahasiswa->bidang_id))->first()
            : null;

        return view('mahasiswa.pesan.index', compact('mahasiswa', 'pesanTerkirim', 'admins', 'mentorUser'));
    }

    /**
     * Send a message/report/izin.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'penerima_id' => ['required', 'exists:users,id'],
            'isi_pesan' => ['required', 'string', 'max:2000'],
            'tipe' => ['required', 'in:direct,laporan_sistem,izin_sakit'],
        ]);

        Pesan::create([
            ...$validated,
            'pengirim_id' => auth()->id(),
            'waktu_kirim' => now(),
        ]);

        $label = match ($validated['tipe']) {
            'laporan_sistem' => 'Laporan sistem berhasil dikirim ke admin.',
            'izin_sakit' => 'Permohonan izin/sakit berhasil dikirim ke mentor.',
            default => 'Pesan berhasil dikirim.',
        };

        return back()->with('success', $label);
    }
}

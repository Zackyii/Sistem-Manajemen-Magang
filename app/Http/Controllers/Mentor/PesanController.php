<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Pesan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesanController extends Controller
{
    /**
     * Show inbox + send form for mentor.
     */
    public function index(): View
    {
        $mentor = auth()->user()->mentor;

        // Messages received by this mentor
        $pesanMasuk = Pesan::where('penerima_id', auth()->id())
            ->with(['pengirim'])
            ->latest('waktu_kirim')
            ->paginate(15);

        // Mahasiswas in same bidang for sending messages
        $mahasiswas = $mentor->bidang
            ? $mentor->bidang->mahasiswas()->where('status_verifikasi', 'disetujui')->with('user')->get()
            : collect();

        // Admin users for escalation
        $admins = User::where('role', 'admin')->where('status', 'active')->get();

        return view('mentor.pesan.index', compact('mentor', 'pesanMasuk', 'mahasiswas', 'admins'));
    }

    /**
     * Send a message from mentor.
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

        return back()->with('success', 'Pesan berhasil dikirim.');
    }

    /**
     * Mark a message as read.
     */
    public function markRead(Pesan $pesan): RedirectResponse
    {
        abort_unless($pesan->penerima_id === auth()->id(), 403);
        $pesan->update(['is_read' => true]);

        return back()->with('success', 'Pesan ditandai sudah dibaca.');
    }
}

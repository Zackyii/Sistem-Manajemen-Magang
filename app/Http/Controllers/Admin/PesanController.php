<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PesanController extends Controller
{
    /**
     * Show all messages received by admin (issue reports & system messages).
     */
    public function index(): View
    {
        $pesanMasuk = Pesan::where('penerima_id', auth()->id())
            ->with(['pengirim.mahasiswa', 'pengirim.mentor'])
            ->latest('waktu_kirim')
            ->paginate(20);

        $totalUnread = Pesan::where('penerima_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return view('admin.pesan.index', compact('pesanMasuk', 'totalUnread'));
    }

    /**
     * Mark a single message as read.
     */
    public function markRead(Pesan $pesan): RedirectResponse
    {
        abort_unless($pesan->penerima_id === auth()->id(), 403);
        $pesan->update(['is_read' => true]);

        return back()->with('success', 'Pesan ditandai sudah dibaca.');
    }
}

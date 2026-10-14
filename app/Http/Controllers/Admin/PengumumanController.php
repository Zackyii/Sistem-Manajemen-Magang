<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    /**
     * List all pengumuman.
     */
    public function index(): View
    {
        $pengumuman = Pengumuman::with(['admin', 'bidang'])->latest('tanggal_publikasi')->paginate(15);
        $bidangs = Bidang::all();

        return view('admin.pengumuman.index', compact('pengumuman', 'bidangs'));
    }

    /**
     * Store new pengumuman.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'isi_pengumuman' => ['required', 'string'],
            'tipe' => ['required', 'in:penting,info,sistem'],
            'bidang_id' => ['nullable', 'exists:bidang,id'],
        ]);

        $admin = $request->user()->admin;

        Pengumuman::create([
            ...$validated,
            'admin_id' => $admin->id,
            'tanggal_publikasi' => now(),
        ]);

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dipublikasikan.');
    }

    /**
     * Delete pengumuman.
     */
    public function destroy(Pengumuman $pengumuman): RedirectResponse
    {
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}

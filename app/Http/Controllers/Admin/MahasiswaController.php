<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MahasiswaController extends Controller
{
    /**
     * List all mahasiswa with filter support.
     */
    public function index(Request $request): View
    {
        $query = Mahasiswa::with(['user', 'bidang'])
            ->when($request->filled('status'), fn ($q) => $q->where('status_verifikasi', $request->status))
            ->when($request->filled('search'), fn ($q) => $q->where('nama', 'ilike', '%'.$request->search.'%'))
            ->latest();

        $mahasiswas = $query->paginate(15)->withQueryString();
        $bidangs = Bidang::all();

        return view('admin.mahasiswa.index', compact('mahasiswas', 'bidangs'));
    }

    /**
     * Show mahasiswa registration detail.
     */
    public function show(Mahasiswa $mahasiswa): View
    {
        $mahasiswa->load(['user', 'bidang']);

        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Approve mahasiswa account.
     */
    public function approve(Request $request, Mahasiswa $mahasiswa): RedirectResponse
    {
        $request->validate([
            'bidang_id' => ['required', 'exists:bidang,id'],
        ]);

        $mahasiswa->update([
            'status_verifikasi' => 'disetujui',
            'bidang_id' => $request->bidang_id,
        ]);

        $mahasiswa->user->update(['status' => 'active']);

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', "Mahasiswa {$mahasiswa->nama} berhasil disetujui.");
    }

    /**
     * Reject mahasiswa account.
     */
    public function reject(Request $request, Mahasiswa $mahasiswa): RedirectResponse
    {
        $request->validate([
            'catatan_verifikasi' => ['required', 'string', 'max:500'],
        ]);

        $mahasiswa->update([
            'status_verifikasi' => 'ditolak',
            'catatan_verifikasi' => $request->catatan_verifikasi,
        ]);

        $mahasiswa->user->update(['status' => 'rejected']);

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', "Mahasiswa {$mahasiswa->nama} telah ditolak.");
    }

    /**
     * Show registration form for creating mahasiswa by admin.
     */
    public function create(): View
    {
        $bidangs = Bidang::all();

        return view('admin.mahasiswa.create', compact('bidangs'));
    }

    /**
     * Store a new mahasiswa created by admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8'],
            'nama' => ['required', 'string', 'max:255'],
            'nim' => ['nullable', 'string', 'max:50'],
            'asal_universitas' => ['required', 'string', 'max:255'],
            'program_studi' => ['required', 'string', 'max:255'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'bidang_id' => ['required', 'exists:bidang,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'password' => $validated['password'],
            'role' => 'mahasiswa',
            'status' => 'active',
        ]);

        Mahasiswa::create([
            'user_id' => $user->id,
            'bidang_id' => $validated['bidang_id'],
            'nama' => $validated['nama'],
            'nim' => $validated['nim'],
            'asal_universitas' => $validated['asal_universitas'],
            'program_studi' => $validated['program_studi'],
            'no_telepon' => $validated['no_telepon'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'status_verifikasi' => 'disetujui',
        ]);

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }
}

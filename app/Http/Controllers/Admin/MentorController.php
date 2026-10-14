<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\Mentor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MentorController extends Controller
{
    /**
     * List all mentors.
     */
    public function index(): View
    {
        $mentors = Mentor::with(['user', 'bidang'])->latest()->paginate(15);
        $bidangs = Bidang::all();

        return view('admin.mentor.index', compact('mentors', 'bidangs'));
    }

    /**
     * Store a new mentor.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8'],
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'unique:mentors,nip'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'bidang_id' => ['nullable', 'exists:bidang,id'],
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'password' => $validated['password'],
            'role' => 'mentor',
            'status' => 'active',
        ]);

        Mentor::create([
            'user_id' => $user->id,
            'bidang_id' => $validated['bidang_id'],
            'nama' => $validated['nama'],
            'nip' => $validated['nip'],
            'no_telepon' => $validated['no_telepon'] ?? null,
        ]);

        return redirect()->route('admin.mentor.index')
            ->with('success', 'Mentor berhasil ditambahkan.');
    }

    /**
     * Assign a mentor to a bidang.
     */
    public function assignBidang(Request $request, Mentor $mentor): RedirectResponse
    {
        $request->validate([
            'bidang_id' => ['required', 'exists:bidang,id'],
        ]);

        $mentor->update(['bidang_id' => $request->bidang_id]);

        return redirect()->route('admin.mentor.index')
            ->with('success', "Mentor {$mentor->nama} berhasil ditetapkan ke bidang.");
    }

    /**
     * Remove mentor.
     */
    public function destroy(Mentor $mentor): RedirectResponse
    {
        $mentor->user->delete();

        return redirect()->route('admin.mentor.index')
            ->with('success', 'Mentor berhasil dihapus.');
    }
}

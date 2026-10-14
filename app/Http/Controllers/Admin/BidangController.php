<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BidangController extends Controller
{
    /**
     * List all bidang.
     */
    public function index(): View
    {
        $bidangs = Bidang::withCount(['mentors', 'mahasiswas'])->latest()->paginate(15);

        return view('admin.bidang.index', compact('bidangs'));
    }

    /**
     * Store a new bidang.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_bidang' => ['required', 'string', 'max:255', 'unique:bidang,nama_bidang'],
            'kuota' => ['required', 'integer', 'min:1'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        Bidang::create($validated);

        return redirect()->route('admin.bidang.index')
            ->with('success', 'Bidang berhasil ditambahkan.');
    }

    /**
     * Update bidang.
     */
    public function update(Request $request, Bidang $bidang): RedirectResponse
    {
        $validated = $request->validate([
            'nama_bidang' => ['required', 'string', 'max:255', 'unique:bidang,nama_bidang,'.$bidang->id],
            'kuota' => ['required', 'integer', 'min:1'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $bidang->update($validated);

        return redirect()->route('admin.bidang.index')
            ->with('success', 'Bidang berhasil diperbarui.');
    }

    /**
     * Delete bidang.
     */
    public function destroy(Bidang $bidang): RedirectResponse
    {
        $bidang->delete();

        return redirect()->route('admin.bidang.index')
            ->with('success', 'Bidang berhasil dihapus.');
    }
}

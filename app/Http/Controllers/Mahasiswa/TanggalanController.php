<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Tanggalan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TanggalanController extends Controller
{
    /**
     * Show the calendar view.
     */
    public function index(Request $request): View
    {
        $mahasiswa = auth()->user()->mahasiswa;

        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        $tanggalan = Tanggalan::where('mahasiswa_id', $mahasiswa->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get()
            ->keyBy(fn ($t) => $t->tanggal->format('Y-m-d'));

        return view('mahasiswa.tanggalan.index', compact('mahasiswa', 'tanggalan', 'bulan', 'tahun'));
    }

    /**
     * Get a single date's data as JSON for modal/detail.
     */
    public function show(Request $request): JsonResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;

        $tanggal = $request->validate(['tanggal' => ['required', 'date']])['tanggal'];

        $entry = Tanggalan::with(['kehadiran', 'mentor'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('tanggal', $tanggal)
            ->first();

        return response()->json($entry);
    }
}

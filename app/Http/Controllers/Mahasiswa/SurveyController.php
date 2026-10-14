<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SurveyController extends Controller
{
    /**
     * Show the survey form.
     */
    public function index(): View
    {
        return view('mahasiswa.survey.index');
    }

    /**
     * Store anonymous survey response.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'rating_fasilitas' => ['required', 'integer', 'between:1,5'],
            'rating_pembimbing' => ['required', 'integer', 'between:1,5'],
            'rating_pekerjaan' => ['required', 'integer', 'between:1,5'],
            'rating_lingkungan' => ['required', 'integer', 'between:1,5'],
            'saran' => ['nullable', 'string', 'max:2000'],
            'kesan' => ['nullable', 'string', 'max:2000'],
        ]);

        $mahasiswa = auth()->user()->mahasiswa;

        Survey::create([
            ...$validated,
            'bidang_id' => $mahasiswa->bidang_id,
            'is_anonymous' => true,
        ]);

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Terima kasih atas masukan Anda. Survey berhasil dikirim secara anonim.');
    }
}

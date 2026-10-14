@extends('layouts.mentor')

@section('page-title', 'Penilaian Akhir Mahasiswa Magang')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 font-heading">Evaluasi & Penilaian Akhir</h1>
            <p class="text-xs text-slate-500 mt-0.5">Berikan evaluasi angka dan catatan kualitatif sebagai nilai akhir sertifikat magang</p>
        </div>
    </div>

    <!-- Student Grading Cards / Table -->
    <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200/80 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-600 font-semibold uppercase tracking-wider">
                        <th class="p-3">Nama Mahasiswa</th>
                        <th class="p-3 text-center">Kehadiran (30%)</th>
                        <th class="p-3 text-center">Tugas (40%)</th>
                        <th class="p-3 text-center">Sikap (30%)</th>
                        <th class="p-3 text-center">Nilai Akhir</th>
                        <th class="p-3 text-center">Predikat</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($mahasiswas as $m)
                        @php
                            $nilai = $m->penilaian;
                        @endphp
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-3">
                                <p class="font-bold text-slate-900">{{ $m->nama }}</p>
                                <p class="text-slate-400 text-[11px]">{{ $m->program_studi }} &bull; {{ $m->asal_universitas }}</p>
                            </td>
                            <td class="p-3 text-center font-mono font-medium text-slate-700">
                                {{ $nilai->nilai_kehadiran ?? '-' }}
                            </td>
                            <td class="p-3 text-center font-mono font-medium text-slate-700">
                                {{ $nilai->nilai_tugas ?? '-' }}
                            </td>
                            <td class="p-3 text-center font-mono font-medium text-slate-700">
                                {{ $nilai->nilai_sikap ?? '-' }}
                            </td>
                            <td class="p-3 text-center font-mono font-bold text-blue-700 text-sm">
                                {{ $nilai->nilai_akhir ?? '-' }}
                            </td>
                            <td class="p-3 text-center">
                                @if ($nilai && $nilai->grade)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $nilai->grade === 'A' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                        Grade {{ $nilai->grade }}
                                    </span>
                                @else
                                    <span class="text-slate-400">Belum Dinilai</span>
                                @endif
                            </td>
                            <td class="p-3 text-right whitespace-nowrap">
                                <button type="button" onclick='openGradingModal(@json($m), @json($nilai))' class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 font-bold hover:bg-blue-100 transition-colors">
                                    {{ $nilai ? 'Ubah Nilai' : 'Input Nilai' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">Belum ada mahasiswa dalam bidang Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Grading Modal -->
<div id="grading-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-base text-slate-900">Formulir Penilaian Akhir</h3>
                <p id="grading-student-name" class="text-xs text-slate-500"></p>
            </div>
            <button onclick="closeGradingModal()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('mentor.penilaian.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <input type="hidden" name="mahasiswa_id" id="input-mhs-id">

            <div class="grid grid-cols-3 gap-2">
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Kehadiran (30%)</label>
                    <input type="number" min="0" max="100" step="0.1" id="input-kehadiran" name="nilai_kehadiran" oninput="calcFinalGrade()" required placeholder="0-100" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-blue-600 focus:outline-none font-mono">
                </div>
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Tugas (40%)</label>
                    <input type="number" min="0" max="100" step="0.1" id="input-tugas" name="nilai_tugas" oninput="calcFinalGrade()" required placeholder="0-100" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-blue-600 focus:outline-none font-mono">
                </div>
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Sikap (30%)</label>
                    <input type="number" min="0" max="100" step="0.1" id="input-sikap" name="nilai_sikap" oninput="calcFinalGrade()" required placeholder="0-100" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-blue-600 focus:outline-none font-mono">
                </div>
            </div>

            <!-- Auto calculated summary -->
            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Kalkulasi Nilai Akhir</span>
                    <span id="label-nilai-akhir" class="text-lg font-extrabold text-blue-700 font-mono">0.0</span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Predikat / Huruf</span>
                    <span id="label-grade" class="text-sm font-bold px-3 py-1 rounded-full bg-blue-100 text-blue-800">Grade -</span>
                </div>
            </div>

            <div>
                <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Catatan Evaluasi / Rekomendasi</label>
                <textarea id="input-catatan" name="catatan_evaluasi" rows="3" placeholder="Uraikan dedikasi, keterampilan problem solving, etika kerja, dan potensi peserta..." class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:border-blue-600 focus:outline-none leading-relaxed"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="closeGradingModal()" class="px-4 py-2 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/20">Simpan Penilaian</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openGradingModal(mhs, nilai) {
        document.getElementById('input-mhs-id').value = mhs.id;
        document.getElementById('grading-student-name').innerText = mhs.nama + ' (' + mhs.program_studi + ')';

        if (nilai) {
            document.getElementById('input-kehadiran').value = nilai.nilai_kehadiran || '';
            document.getElementById('input-tugas').value = nilai.nilai_tugas || '';
            document.getElementById('input-sikap').value = nilai.nilai_sikap || '';
            document.getElementById('input-catatan').value = nilai.catatan_evaluasi || '';
        } else {
            document.getElementById('input-kehadiran').value = '';
            document.getElementById('input-tugas').value = '';
            document.getElementById('input-sikap').value = '';
            document.getElementById('input-catatan').value = '';
        }

        calcFinalGrade();
        document.getElementById('grading-modal').classList.remove('hidden');
    }

    function calcFinalGrade() {
        const k = parseFloat(document.getElementById('input-kehadiran').value) || 0;
        const t = parseFloat(document.getElementById('input-tugas').value) || 0;
        const s = parseFloat(document.getElementById('input-sikap').value) || 0;

        const akhir = ((k * 0.3) + (t * 0.4) + (s * 0.3)).toFixed(1);
        document.getElementById('label-nilai-akhir').innerText = akhir;

        let grade = 'E';
        if (akhir >= 85) grade = 'A';
        else if (akhir >= 78) grade = 'B+';
        else if (akhir >= 70) grade = 'B';
        else if (akhir >= 60) grade = 'C';
        else if (akhir >= 50) grade = 'D';

        document.getElementById('label-grade').innerText = 'Grade ' + grade;
    }

    function closeGradingModal() {
        document.getElementById('grading-modal').classList.add('hidden');
    }
</script>
@endpush
@endsection

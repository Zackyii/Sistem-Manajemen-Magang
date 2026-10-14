@extends('layouts.mentor')

@section('page-title', 'Penugasan Mahasiswa Magang')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 font-heading">Penugasan & Proyek Magang</h1>
            <p class="text-xs text-slate-500 mt-0.5">Tugaskan proyek atau pekerjaan terstruktur yang terintegrasi langsung ke kalender mahasiswa</p>
        </div>
    </div>

    <!-- Create Task Form Card -->
    <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200/80 space-y-4">
        <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Berikan Penugasan Baru
        </h2>

        <form action="{{ route('mentor.tugas.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="mahasiswa_id" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Target Mahasiswa</label>
                    <select id="mahasiswa_id" name="mahasiswa_id" required class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none">
                        <option value="">Pilih Mahasiswa Bimbingan...</option>
                        @foreach ($mahasiswas as $m)
                            <option value="{{ $m->id }}">{{ $m->nama }} ({{ $m->program_studi }} - {{ $m->asal_universitas }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="deadline_tugas" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Tenggat Waktu (Deadline)</label>
                    <input type="date" id="deadline_tugas" name="deadline_tugas" required value="{{ today()->addDays(3)->toDateString() }}" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none">
                </div>
            </div>

            <div>
                <label for="info_tugas" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Instruksi & Judul Penugasan</label>
                <textarea id="info_tugas" name="info_tugas" rows="3" required placeholder="Contoh: Slicing UI Dashboard Pelayanan Publik Diskominfo menggunakan Tailwind CSS dan pengujian responsif pada smartphone..." class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 leading-relaxed"></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Kirim Penugasan ke Mahasiswa
                </button>
            </div>
        </form>
    </div>

    <!-- Task List Table Card -->
    <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200/80 space-y-4">
        <h2 class="text-sm font-bold text-slate-900">Riwayat Penugasan</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-600 font-semibold uppercase tracking-wider">
                        <th class="p-3">Mahasiswa</th>
                        <th class="p-3">Deskripsi Tugas</th>
                        <th class="p-3">Deadline</th>
                        <th class="p-3 text-right">Status Logbook</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($tugas as $t)
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-3 font-bold text-slate-800 whitespace-nowrap">
                                {{ $t->mahasiswa->nama ?? '-' }}
                            </td>
                            <td class="p-3 max-w-md">
                                <p class="font-medium text-slate-800 leading-relaxed">{{ $t->info_tugas }}</p>
                            </td>
                            <td class="p-3 whitespace-nowrap font-mono text-slate-600">
                                {{ $t->deadline_tugas ? \Carbon\Carbon::parse($t->deadline_tugas)->translatedFormat('d M Y') : 'Fleksibel' }}
                            </td>
                            <td class="p-3 text-right whitespace-nowrap">
                                <x-badge :status="$t->status_logbook ?? 'draft'" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400">Belum ada penugasan yang diberikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tugas->hasPages())
            <div class="pt-4 border-t border-slate-100">
                {{ $tugas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

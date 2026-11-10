@extends('layouts.mentor')

@section('page-title', 'Detail Logbook: ' . ($tanggalan->mahasiswa->nama ?? 'Mahasiswa'))

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('mentor.logbook.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Daftar Logbook
        </a>
        <x-badge :status="$tanggalan->status_logbook" class="text-xs px-3 py-1" />
    </div>

    <!-- Detail Content Card -->
    <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 sm:p-8 shadow-sm border border-slate-200/80 dark:border-slate-700 space-y-6 text-xs">
        <div class="flex items-start justify-between pb-4 border-b border-slate-100 dark:border-slate-700">
            <div>
                <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Mahasiswa</span>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5">{{ $tanggalan->mahasiswa->nama }}</h2>
                <p class="text-slate-500 dark:text-slate-400">{{ $tanggalan->mahasiswa->program_studi }} &bull; {{ $tanggalan->mahasiswa->asal_universitas }}</p>
            </div>
            <div class="text-right">
                <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Tanggal Kegiatan</span>
                <span class="text-sm font-bold text-slate-800 dark:text-slate-200 mt-0.5 block">{{ \Carbon\Carbon::parse($tanggalan->tanggal)->translatedFormat('l, d F Y') }}</span>
                <span class="text-slate-500 dark:text-slate-400 font-mono">{{ $tanggalan->durasi_jam }} Jam Kerja</span>
            </div>
        </div>

        <div>
            <span class="font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block text-[10px] mb-1">Aktivitas Utama / Judul</span>
            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $tanggalan->aktivitas_utama }}</p>
        </div>

        <div>
            <span class="font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block text-[10px] mb-1.5">Deskripsi Lengkap Kegiatan</span>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-700 border border-slate-100 dark:border-slate-600 text-slate-700 dark:text-slate-300 leading-relaxed text-xs">
                {{ $tanggalan->deskripsi_kegiatan }}
            </div>
        </div>

        @if ($tanggalan->file_lampiran)
            <div class="p-4 rounded-2xl bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-800 text-blue-600 dark:text-blue-300 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-blue-900 dark:text-blue-300">Lampiran Dokumen</p>
                    <a href="{{ route('files.logbook', $tanggalan) }}" target="_blank"
                        class="text-xs text-blue-700 dark:text-blue-300 hover:underline font-semibold flex items-center gap-1 mt-0.5">
                        📎 Lihat / Unduh Lampiran
                    </a>
                </div>
            </div>
        @endif

        @if ($tanggalan->catatan_mentor)
            <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 space-y-1 text-amber-900 dark:text-amber-300">
                <span class="font-bold block">Catatan Mentor Sebelumnya:</span>
                <p>{{ $tanggalan->catatan_mentor }}</p>
            </div>
        @endif

        <!-- Review & Approval Form -->
        <div class="pt-6 border-t border-slate-100 dark:border-slate-700 space-y-4">
            <h3 class="font-bold text-sm text-slate-900 dark:text-white">Umpan Balik &amp; Validasi Pembimbing</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Approve Form -->
                <form action="{{ route('mentor.logbook.approve', $tanggalan) }}" method="POST" class="p-4 rounded-2xl bg-emerald-50/60 dark:bg-emerald-900/20 border border-emerald-200/80 dark:border-emerald-800 space-y-3">
                    @csrf
                    <div>
                        <label for="catatan_approve" class="block font-bold text-emerald-900 dark:text-emerald-300 mb-1">Catatan Apresiasi / Evaluasi:</label>
                        <textarea id="catatan_approve" name="catatan_mentor" rows="2" placeholder="Contoh: Pekerjaan rapi dan sesuai instruksi..." class="w-full rounded-xl border border-emerald-300 dark:border-emerald-700 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:border-emerald-600 focus:outline-none"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Setujui Logbook Ini
                    </button>
                </form>

                <!-- Reject Form -->
                <form action="{{ route('mentor.logbook.reject', $tanggalan) }}" method="POST" class="p-4 rounded-2xl bg-rose-50/60 dark:bg-rose-900/20 border border-rose-200/80 dark:border-rose-800 space-y-3">
                    @csrf
                    <div>
                        <label for="catatan_reject" class="block font-bold text-rose-900 dark:text-rose-300 mb-1">Alasan Penolakan / Revisi:</label>
                        <textarea id="catatan_reject" name="catatan_mentor" rows="2" required placeholder="Contoh: Harap lengkapi uraian capaian teknis..." class="w-full rounded-xl border border-rose-300 dark:border-rose-700 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:border-rose-600 focus:outline-none"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-md shadow-rose-600/20 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Tolak / Minta Revisi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

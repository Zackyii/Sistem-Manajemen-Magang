@extends('layouts.mahasiswa')

@section('title', 'Isi Logbook Magang - SIMANG')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 dark:text-white font-heading">Catat Aktivitas Harian</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Laporkan detail pekerjaan, tantangan, dan hasil kerja harian Anda</p>
        </div>
        <a href="{{ route('mahasiswa.logbook.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
            &larr; Kembali
        </a>
    </div>

    @if ($existing && $existing->catatan_mentor)
        <div class="rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 p-4 text-xs text-amber-900 dark:text-amber-300 space-y-1">
            <span class="font-bold flex items-center gap-1 text-amber-800 dark:text-amber-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                Catatan Review Mentor:
            </span>
            <p>{{ $existing->catatan_mentor }}</p>
        </div>
    @endif

    <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200/90 dark:border-slate-700">
        <form action="{{ route('mahasiswa.logbook.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Tanggal Aktivitas -->
            <div>
                <label for="tanggal" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Tanggal Aktivitas</label>
                <input type="date" id="tanggal" name="tanggal" required value="{{ old('tanggal', $tanggal) }}" class="mt-1.5 block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
            </div>

            <!-- Aktivitas Utama -->
            <div>
                <label for="aktivitas_utama" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Aktivitas Utama / Judul Tugas</label>
                <input type="text" id="aktivitas_utama" name="aktivitas_utama" required placeholder="Contoh: Mengembangkan Modul Integrasi Satu Data" value="{{ old('aktivitas_utama', $existing->aktivitas_utama ?? '') }}" class="mt-1.5 block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
            </div>

            <!-- Durasi Jam -->
            <div>
                <label for="durasi_jam" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Durasi Jam Kerja</label>
                <div class="mt-1.5 relative">
                    <input type="number" step="0.5" min="0.5" max="24" id="durasi_jam" name="durasi_jam" required value="{{ old('durasi_jam', $existing->durasi_jam ?? 8) }}" class="block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                    <span class="absolute right-4 top-2.5 text-xs text-slate-400 dark:text-slate-500 font-semibold">Jam</span>
                </div>
            </div>

            <!-- Deskripsi Kegiatan -->
            <div>
                <label for="deskripsi_kegiatan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Deskripsi Lengkap Kegiatan</label>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-1.5">Jelaskan langkah kerja, alat/teknologi yang digunakan, serta capaian tugas hari ini.</p>
                <textarea id="deskripsi_kegiatan" name="deskripsi_kegiatan" rows="6" required placeholder="Tuliskan detail pekerjaan secara terstruktur..." class="block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-3 text-sm text-slate-800 dark:text-slate-200 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 leading-relaxed">{{ old('deskripsi_kegiatan', $existing->deskripsi_kegiatan ?? '') }}</textarea>
            </div>

            <!-- Lampiran (Opsional) -->
            <div>
                <label for="file_lampiran" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Lampiran Dokumen <span class="text-slate-400 dark:text-slate-500 font-normal normal-case">(Opsional)</span></label>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-1.5">Unggah file pendukung kegiatan (laporan, hasil kerja, foto, dll). Format: JPG, PNG, PDF. Maks 5 MB.</p>
                <input type="file" id="file_lampiran" name="file_lampiran" accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 dark:file:bg-blue-900/40 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-900/60 border border-slate-200 dark:border-slate-600 rounded-xl p-2 bg-white dark:bg-slate-900">
                @if ($existing?->file_lampiran)
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">
                        Sudah ada lampiran:
                        <a href="{{ route('files.logbook', $existing) }}" target="_blank"
                            class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">Lihat file sebelumnya</a>
                        (Mengunggah file baru akan menggantikannya.)
                    </p>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row gap-3">
                <button type="submit" name="status_logbook" value="draft" class="flex-1 py-3 px-4 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                    Simpan sebagai Draf
                </button>
                <button type="submit" name="status_logbook" value="submitted" class="flex-1 py-3 px-4 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-md shadow-blue-700/20 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Kirim untuk Review Mentor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

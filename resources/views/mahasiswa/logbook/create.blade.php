@extends('layouts.mahasiswa')

@section('title', 'Isi Logbook Magang - SIMANG')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 font-heading">Catat Aktivitas Harian</h1>
            <p class="text-xs text-slate-500 mt-0.5">Laporkan detail pekerjaan, tantangan, dan hasil kerja harian Anda</p>
        </div>
        <a href="{{ route('mahasiswa.logbook.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
            &larr; Kembali
        </a>
    </div>

    @if ($existing && $existing->catatan_mentor)
        <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 text-xs text-amber-900 space-y-1">
            <span class="font-bold flex items-center gap-1 text-amber-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                Catatan Review Mentor:
            </span>
            <p>{{ $existing->catatan_mentor }}</p>
        </div>
    @endif

    <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200/90">
        <form action="{{ route('mahasiswa.logbook.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Tanggal Aktivitas -->
            <div>
                <label for="tanggal" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal Aktivitas</label>
                <input type="date" id="tanggal" name="tanggal" required value="{{ old('tanggal', $tanggal) }}" class="mt-1.5 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
            </div>

            <!-- Aktivitas Utama -->
            <div>
                <label for="aktivitas_utama" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Aktivitas Utama / Judul Tugas</label>
                <input type="text" id="aktivitas_utama" name="aktivitas_utama" required placeholder="Contoh: Mengembangkan Modul Integrasi Satu Data" value="{{ old('aktivitas_utama', $existing->aktivitas_utama ?? '') }}" class="mt-1.5 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
            </div>

            <!-- Durasi Jam -->
            <div>
                <label for="durasi_jam" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Durasi Jam Kerja</label>
                <div class="mt-1.5 relative">
                    <input type="number" step="0.5" min="0.5" max="24" id="durasi_jam" name="durasi_jam" required value="{{ old('durasi_jam', $existing->durasi_jam ?? 8) }}" class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                    <span class="absolute right-4 top-2.5 text-xs text-slate-400 font-semibold">Jam</span>
                </div>
            </div>

            <!-- Deskripsi Kegiatan -->
            <div>
                <label for="deskripsi_kegiatan" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Deskripsi Lengkap Kegiatan</label>
                <p class="text-[11px] text-slate-500 mb-1.5">Jelaskan langkah kerja, alat/teknologi yang digunakan, serta capaian tugas hari ini.</p>
                <textarea id="deskripsi_kegiatan" name="deskripsi_kegiatan" rows="6" required placeholder="Tuliskan detail pekerjaan secara terstruktur..." class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 leading-relaxed">{{ old('deskripsi_kegiatan', $existing->deskripsi_kegiatan ?? '') }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row gap-3">
                <button type="submit" name="status_logbook" value="draft" class="flex-1 py-3 px-4 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
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

@extends('layouts.admin')

@section('page-title', 'Detail Mahasiswa: ' . $mahasiswa->nama)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.mahasiswa.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Daftar Mahasiswa
        </a>
        <x-badge :status="$mahasiswa->status_verifikasi" class="text-xs px-3 py-1" />
    </div>

    <!-- Main Dossier Card -->
    <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-6">
        <!-- Header Info -->
        <div class="flex items-start justify-between pb-6 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center font-extrabold text-2xl border border-blue-100">
                    {{ substr($mahasiswa->nama, 0, 2) }}
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 font-heading">{{ $mahasiswa->nama }}</h1>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $mahasiswa->program_studi }} &bull; {{ $mahasiswa->asal_universitas }}</p>
                    <p class="text-[11px] font-mono text-blue-600 mt-1">Username: {{ $mahasiswa->user->username ?? '-' }} | NIM: {{ $mahasiswa->nim ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Detail Data Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">Bidang Penempatan Saat Ini</span>
                <span class="font-bold text-slate-900 text-sm block">{{ $mahasiswa->bidang->nama_bidang ?? 'Belum ada bidang' }}</span>
                <span class="text-slate-500 text-[11px]">Kuota: {{ $mahasiswa->bidang->kuota ?? '-' }} orang</span>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">Durasi Waktu Magang</span>
                <span class="font-bold text-slate-900 text-sm block">
                    {{ \Carbon\Carbon::parse($mahasiswa->tanggal_mulai)->translatedFormat('d M Y') }} s.d. {{ \Carbon\Carbon::parse($mahasiswa->tanggal_selesai)->translatedFormat('d M Y') }}
                </span>
                <span class="text-slate-500 text-[11px]">No. HP: {{ $mahasiswa->no_telepon ?? '-' }}</span>
            </div>
        </div>

        @if ($mahasiswa->catatan_verifikasi)
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-900 space-y-1">
                <span class="font-bold block">Catatan Verifikasi / Alasan Penolakan:</span>
                <p>{{ $mahasiswa->catatan_verifikasi }}</p>
            </div>
        @endif

        <!-- Action Box: Approve / Change Bidang / Reject -->
        <div class="pt-6 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Approval Form -->
            <div class="p-5 rounded-2xl bg-blue-50/50 border border-blue-100 space-y-3">
                <h3 class="font-bold text-xs text-blue-900 uppercase tracking-wider">Setujui Mahasiswa & Tetapkan Bidang</h3>
                <form action="{{ route('admin.mahasiswa.approve', $mahasiswa) }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label for="bidang_id" class="block font-semibold text-slate-700 mb-1">Pilih Bidang Penempatan:</label>
                        <select id="bidang_id" name="bidang_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-blue-600 focus:outline-none bg-white">
                            @foreach (\App\Models\Bidang::all() as $bidang)
                                <option value="{{ $bidang->id }}" {{ $mahasiswa->bidang_id == $bidang->id ? 'selected' : '' }}>
                                    {{ $bidang->nama_bidang }} (Sisa: {{ max(0, $bidang->kuota - $bidang->mahasiswas()->where('status_verifikasi', 'disetujui')->count()) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Setujui & Aktifkan Akun
                    </button>
                </form>
            </div>

            <!-- Rejection Form -->
            <div class="p-5 rounded-2xl bg-rose-50/50 border border-rose-100 space-y-3">
                <h3 class="font-bold text-xs text-rose-900 uppercase tracking-wider">Tolak Pendaftaran Mahasiswa</h3>
                <form action="{{ route('admin.mahasiswa.reject', $mahasiswa) }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label for="catatan_verifikasi" class="block font-semibold text-slate-700 mb-1">Alasan Penolakan:</label>
                        <textarea id="catatan_verifikasi" name="catatan_verifikasi" rows="2" required placeholder="Contoh: Kuota bidang penuh atau berkas belum lengkap..." class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-rose-600 focus:outline-none bg-white"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-md shadow-rose-600/20 transition-all flex items-center justify-center gap-2" onclick="return confirm('Apakah Anda yakin ingin menolak mahasiswa ini?')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Tolak Pendaftaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

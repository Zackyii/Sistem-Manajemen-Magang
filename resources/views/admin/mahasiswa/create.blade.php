@extends('layouts.admin')

@section('page-title', 'Tambah Mahasiswa Magang')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 font-heading">Tambah Mahasiswa Baru</h1>
            <p class="text-xs text-slate-500 mt-0.5">Daftarkan akun peserta magang secara manual oleh administrator</p>
        </div>
        <a href="{{ route('admin.mahasiswa.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
            &larr; Kembali
        </a>
    </div>

    <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <form action="{{ route('admin.mahasiswa.store') }}" method="POST" class="space-y-6 text-xs">
            @csrf

            <!-- Section 1: Akun Pengguna -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">1</span>
                    Informasi Akun SIMANG
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                    <div>
                        <label for="username" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Username Login</label>
                        <input type="text" id="username" name="username" required value="{{ old('username') }}" placeholder="Contoh: mhs_zacky" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                    </div>
                    <div>
                        <label for="password" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Password</label>
                        <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Pribadi Mahasiswa -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">2</span>
                    Biodata & Asal Kampus
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                    <div>
                        <label for="nama" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap Mahasiswa</label>
                        <input type="text" id="nama" name="nama" required value="{{ old('nama') }}" placeholder="Nama lengkap sesuai KTM" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                    </div>
                    <div>
                        <label for="nim" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">NIM / Nomor Mahasiswa</label>
                        <input type="text" id="nim" name="nim" value="{{ old('nim') }}" placeholder="Contoh: 21081010150" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                    </div>
                    <div>
                        <label for="asal_universitas" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Perguruan Tinggi / Universitas</label>
                        <input type="text" id="asal_universitas" name="asal_universitas" required value="{{ old('asal_universitas') }}" placeholder="Contoh: Universitas Airlangga" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                    </div>
                    <div>
                        <label for="program_studi" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Program Studi / Jurusan</label>
                        <input type="text" id="program_studi" name="program_studi" required value="{{ old('program_studi') }}" placeholder="Contoh: Sistem Informasi" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="no_telepon" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">No. WhatsApp / Telepon</label>
                        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="Contoh: 08123456789" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                    </div>
                </div>
            </div>

            <!-- Section 3: Penempatan Bidang & Durasi -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">3</span>
                    Penempatan Bidang Dinas & Durasi Magang
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-3">
                    <div class="sm:col-span-3">
                        <label for="bidang_id" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Bidang Penempatan</label>
                        <select id="bidang_id" name="bidang_id" required class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                            <option value="">Pilih Bidang Dinas...</option>
                            @foreach ($bidangs as $b)
                                <option value="{{ $b->id }}" {{ old('bidang_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->nama_bidang }} (Tersedia: {{ max(0, $b->kuota - $b->mahasiswas()->count()) }} Kuota)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-1">
                        <label for="tanggal_mulai" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Tanggal Mulai</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" required value="{{ old('tanggal_mulai', today()->toDateString()) }}" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="tanggal_selesai" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Tanggal Selesai</label>
                        <input type="date" id="tanggal_selesai" name="tanggal_selesai" required value="{{ old('tanggal_selesai', today()->addMonths(3)->toDateString()) }}" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.mahasiswa.index') }}" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/20 transition-all">
                    Simpan Mahasiswa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

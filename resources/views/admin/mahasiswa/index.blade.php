@extends('layouts.admin')

@section('page-title', 'Data Peserta Magang')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 dark:text-white font-heading">Daftar Mahasiswa Magang</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kelola data verifikasi, bidang penempatan, dan status keaktifan mahasiswa</p>
        </div>
        <a href="{{ route('admin.mahasiswa.create') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/30 transition-all flex items-center gap-2 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Mahasiswa Manual
        </a>
    </div>

    <!-- Filters Bar -->
    <div class="rounded-2xl bg-white dark:bg-slate-800 p-4 shadow-sm border border-slate-200/80 dark:border-slate-700">
        <form method="GET" action="{{ route('admin.mahasiswa.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama mahasiswa..." class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-3.5 py-2 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-white dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500">
            </div>

            <div>
                <select name="status" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-3.5 py-2 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-white dark:bg-slate-900 dark:text-slate-200">
                    <option value="">Semua Status Verifikasi</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu Persetujuan)</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui (Aktif)</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 rounded-xl text-xs font-bold text-white bg-slate-800 dark:bg-slate-600 hover:bg-slate-900 dark:hover:bg-slate-500 transition-colors">
                    Filter Data
                </button>
                @if (request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.mahasiswa.index') }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200/80 dark:border-slate-700 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/50 text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wider">
                        <th class="p-3">Nama / Username</th>
                        <th class="p-3">Universitas &amp; Jurusan</th>
                        <th class="p-3">Bidang Dinas</th>
                        <th class="p-3">Periode Magang</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse ($mahasiswas as $mhs)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30">
                            <td class="p-3">
                                <p class="font-bold text-slate-900 dark:text-white">{{ $mhs->nama }}</p>
                                <p class="text-slate-400 font-mono text-[11px]">{{ $mhs->user->username ?? '-' }} (NIM: {{ $mhs->nim ?? '-' }})</p>
                            </td>
                            <td class="p-3">
                                <p class="font-medium text-slate-700 dark:text-slate-300">{{ $mhs->asal_universitas }}</p>
                                <p class="text-slate-400 text-[11px]">{{ $mhs->program_studi }}</p>
                            </td>
                            <td class="p-3">
                                <span class="font-semibold text-blue-700 dark:text-blue-400">{{ $mhs->bidang->nama_bidang ?? 'Belum Ditentukan' }}</span>
                            </td>
                            <td class="p-3 text-slate-600 dark:text-slate-400">
                                {{ \Carbon\Carbon::parse($mhs->tanggal_mulai)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($mhs->tanggal_selesai)->format('d/m/y') }}
                            </td>
                            <td class="p-3 text-center">
                                <x-badge :status="$mhs->status_verifikasi" />
                            </td>
                            <td class="p-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.mahasiswa.show', $mhs) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 font-bold hover:bg-blue-100 dark:hover:bg-blue-900/60 transition-colors">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Tidak ada data mahasiswa yang cocok dengan filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($mahasiswas->hasPages())
            <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                {{ $mahasiswas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

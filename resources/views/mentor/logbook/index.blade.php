@extends('layouts.mentor')

@section('page-title', 'Review Logbook Mahasiswa')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 font-heading">Review & Validasi Logbook</h1>
            <p class="text-xs text-slate-500 mt-0.5">Tinjau laporan progres kerja dan aktivitas harian peserta bimbingan Anda</p>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="rounded-2xl bg-white p-4 shadow-sm border border-slate-200/80">
        <form method="GET" action="{{ route('mentor.logbook.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            <div>
                <select name="mahasiswa_id" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:border-blue-600 focus:outline-none">
                    <option value="">Semua Mahasiswa Bimbingan</option>
                    @foreach ($mahasiswas as $m)
                        <option value="{{ $m->id }}" {{ request('mahasiswa_id') == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="status" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:border-blue-600 focus:outline-none">
                    <option value="">Semua Status Logbook</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Diajukan (Menunggu Review)</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 rounded-xl font-bold text-white bg-slate-800 hover:bg-slate-900 transition-colors">
                    Filter
                </button>
                @if (request()->hasAny(['mahasiswa_id', 'status']))
                    <a href="{{ route('mentor.logbook.index') }}" class="px-3 py-2 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200/80 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-600 font-semibold uppercase tracking-wider">
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Mahasiswa</th>
                        <th class="p-3">Aktivitas Utama</th>
                        <th class="p-3">Durasi</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logbooks as $entry)
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-3 font-semibold text-slate-700 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($entry->tanggal)->format('d/m/Y') }}
                            </td>
                            <td class="p-3 font-bold text-slate-800 whitespace-nowrap">
                                {{ $entry->mahasiswa->nama ?? '-' }}
                            </td>
                            <td class="p-3 max-w-sm">
                                <p class="font-bold text-slate-900">{{ $entry->aktivitas_utama }}</p>
                                <p class="text-slate-500 line-clamp-1 mt-0.5">{{ $entry->deskripsi_kegiatan }}</p>
                            </td>
                            <td class="p-3 font-mono text-slate-600 whitespace-nowrap">
                                {{ $entry->durasi_jam }} jam
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                <x-badge :status="$entry->status_logbook" />
                            </td>
                            <td class="p-3 text-right whitespace-nowrap">
                                <a href="{{ route('mentor.logbook.show', $entry) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 font-bold hover:bg-blue-100 transition-colors">
                                    Tinjau & Validasi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Tidak ada data logbook yang cocok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logbooks->hasPages())
            <div class="pt-4 border-t border-slate-100">
                {{ $logbooks->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

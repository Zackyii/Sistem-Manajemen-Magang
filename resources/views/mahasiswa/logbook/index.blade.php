@extends('layouts.mahasiswa')

@section('title', 'Logbook Harian - SIMANG')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 font-heading">Logbook Harian Magang</h1>
            <p class="text-xs text-slate-500 mt-0.5">Dokumentasi Aktivitas, Jam Kerja, dan Capaian Kompetensi</p>
        </div>
        <div class="flex items-center gap-2">
            <!-- Export PDF Dropdown / Button -->
            <div class="relative inline-block text-left" id="export-container">
                <button type="button" onclick="toggleExportMenu()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 shadow-xs transition-colors">
                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Unduh PDF
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="export-menu" class="hidden absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-xl border border-slate-100 py-1 z-20 text-xs">
                    <a href="{{ route('mahasiswa.logbook.export-pdf', ['filter' => 'daily']) }}" class="block px-4 py-2 text-slate-700 hover:bg-blue-50 hover:text-blue-700 font-medium">Hari Ini (Harian)</a>
                    <a href="{{ route('mahasiswa.logbook.export-pdf', ['filter' => 'weekly']) }}" class="block px-4 py-2 text-slate-700 hover:bg-blue-50 hover:text-blue-700 font-medium">Minggu Ini (Mingguan)</a>
                    <a href="{{ route('mahasiswa.logbook.export-pdf', ['filter' => 'monthly']) }}" class="block px-4 py-2 text-slate-700 hover:bg-blue-50 hover:text-blue-700 font-medium">Bulan Ini (Bulanan)</a>
                </div>
            </div>

            <a href="{{ route('mahasiswa.logbook.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-md shadow-blue-700/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Logbook
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="rounded-2xl bg-white p-4 shadow-sm border border-slate-100">
            <span class="text-xs font-semibold text-slate-500 block">Total Jam Kerja</span>
            <span class="text-2xl font-bold text-blue-700 mt-1 block">{{ $stats['total_jam'] }} <span class="text-xs font-normal text-slate-400">Jam</span></span>
        </div>
        <div class="rounded-2xl bg-white p-4 shadow-sm border border-slate-100">
            <span class="text-xs font-semibold text-slate-500 block">Aktivitas Dicatat</span>
            <span class="text-2xl font-bold text-slate-800 mt-1 block">{{ $stats['total_kegiatan'] }}</span>
        </div>
        <div class="rounded-2xl bg-white p-4 shadow-sm border border-slate-100">
            <span class="text-xs font-semibold text-slate-500 block">Disetujui Mentor</span>
            <span class="text-2xl font-bold text-emerald-600 mt-1 block">{{ $stats['disetujui'] }}</span>
        </div>
        <div class="rounded-2xl bg-white p-4 shadow-sm border border-slate-100">
            <span class="text-xs font-semibold text-slate-500 block">Menunggu Review</span>
            <span class="text-2xl font-bold text-amber-500 mt-1 block">{{ $stats['pending'] }}</span>
        </div>
    </div>

    <!-- Table of Logbooks -->
    <div class="rounded-3xl bg-white p-5 shadow-sm border border-slate-200/90 space-y-4">
        <div class="overflow-x-auto -mx-5 sm:mx-0">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-600 font-semibold uppercase tracking-wider">
                        <th class="p-3 pl-5 sm:pl-3">Tanggal</th>
                        <th class="p-3">Aktivitas Utama</th>
                        <th class="p-3">Durasi</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Catatan Mentor</th>
                        <th class="p-3 pr-5 sm:pr-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logbooks as $entry)
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-3 pl-5 sm:pl-3 font-semibold text-slate-800 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($entry->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="p-3 max-w-xs">
                                <p class="font-bold text-slate-800">{{ $entry->aktivitas_utama }}</p>
                                <p class="text-slate-500 line-clamp-1 mt-0.5">{{ $entry->deskripsi_kegiatan }}</p>
                            </td>
                            <td class="p-3 whitespace-nowrap font-mono text-slate-600">
                                {{ $entry->durasi_jam }} jam
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                <x-badge :status="$entry->status_logbook" />
                            </td>
                            <td class="p-3 text-slate-600 max-w-xs">
                                {{ $entry->catatan_mentor ?? '-' }}
                            </td>
                            <td class="p-3 pr-5 sm:pr-3 text-right whitespace-nowrap">
                                <a href="{{ route('mahasiswa.logbook.create', ['tanggal' => $entry->tanggal->format('Y-m-d')]) }}" class="text-blue-600 hover:text-blue-800 font-bold">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">
                                Belum ada catatan logbook. Klik tombol <strong>Tambah Logbook</strong> untuk mulai mencatat.
                            </td>
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

@push('scripts')
<script>
    function toggleExportMenu() {
        const menu = document.getElementById('export-menu');
        menu.classList.toggle('hidden');
    }
    // Close on click outside
    window.addEventListener('click', (e) => {
        const container = document.getElementById('export-container');
        const menu = document.getElementById('export-menu');
        if (container && !container.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection

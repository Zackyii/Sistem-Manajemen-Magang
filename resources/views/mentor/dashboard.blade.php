@extends('layouts.mentor')

@section('page-title', 'Dashboard Pembimbing Magang')

@section('content')
<div class="space-y-6">
    {{-- Header Banner (no decorative dot) --}}
    <div class="rounded-3xl bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 p-6 text-white shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-400/20 text-amber-300 border border-amber-400/30">
                Bidang: {{ $mentor->bidang->nama_bidang ?? 'Belum Ditugaskan' }}
            </span>
            <h1 class="text-2xl font-extrabold tracking-tight mt-2 font-heading">Selamat Bertugas, {{ $mentor->nama }}!</h1>
            <p class="text-xs text-blue-100 mt-0.5">NIP: {{ $mentor->nip ?? '-' }} &bull; Pantau bimbingan, logbook, dan presensi mahasiswa Anda</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('mentor.tugas.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/30 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Berikan Tugas Baru
            </a>
            <a href="{{ route('mentor.penilaian.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-900 bg-amber-400 hover:bg-amber-300 shadow-md shadow-amber-400/20 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Penilaian Akhir
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat-card title="Mahasiswa Bimbingan" :value="$stats['total_mahasiswa']" trend="Dalam bidang Anda" />
        <x-stat-card title="Presensi Hari Ini" :value="$stats['kehadiran_hari_ini']" trend="Semua jenis kehadiran" />
        <x-stat-card title="Hadir (Tepat/Terlambat)" :value="$stats['hadir_hari_ini']" trend="Dari total presensi hari ini" />
        <x-stat-card title="Logbook Perlu Review" :value="$stats['logbook_pending']" trend="Catatan kegiatan mahasiswa" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Today's Attendance with Location --}}
        <div class="lg:col-span-2 rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200/80 dark:border-slate-700 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white font-heading">Presensi Hari Ini</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Data lokasi dan kehadiran peserta magang</p>
                </div>
                <a href="{{ route('mentor.presensi.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/50 text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wider">
                            <th class="p-3">Mahasiswa</th>
                            <th class="p-3">Masuk / Pulang</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Titik & Nama Lokasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse ($kehadiranHariIni as $presensi)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 align-top">
                                <td class="p-3 font-bold text-slate-800 dark:text-slate-200 whitespace-nowrap">
                                    {{ $presensi->mahasiswa->nama }}
                                </td>
                                <td class="p-3 font-mono text-slate-600 dark:text-slate-300 whitespace-nowrap">
                                    {{ $presensi->waktu_masuk ? \Carbon\Carbon::parse($presensi->waktu_masuk)->format('H:i') : '--:--' }}
                                    /
                                    {{ $presensi->waktu_keluar ? \Carbon\Carbon::parse($presensi->waktu_keluar)->format('H:i') : '--:--' }}
                                </td>
                                <td class="p-3">
                                    <x-badge :status="$presensi->status" />
                                </td>
                                <td class="p-3 min-w-[180px]">
                                    @if ($presensi->koordinat_lat)
                                        @if ($presensi->nama_lokasi)
                                            <p class="text-[11px] text-slate-700 dark:text-slate-300 mb-0.5 truncate max-w-[170px]">📍 {{ $presensi->nama_lokasi }}</p>
                                        @endif
                                        <a href="https://maps.google.com/?q={{ $presensi->koordinat_lat }},{{ $presensi->koordinat_lng }}" target="_blank"
                                            class="text-[11px] font-mono text-blue-600 dark:text-blue-400 hover:underline">
                                            {{ $presensi->koordinat_lat }}, {{ $presensi->koordinat_lng }}
                                        </a>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500 text-[11px]">Lokasi tidak tersedia</span>
                                    @endif
                                    @if ($presensi->koordinat_lat_checkout)
                                        <div class="mt-1">
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400">◀ Checkout:</p>
                                            <a href="https://maps.google.com/?q={{ $presensi->koordinat_lat_checkout }},{{ $presensi->koordinat_lng_checkout }}" target="_blank"
                                                class="text-[11px] font-mono text-emerald-600 dark:text-emerald-400 hover:underline">
                                                {{ $presensi->koordinat_lat_checkout }}, {{ $presensi->koordinat_lng_checkout }}
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-400 dark:text-slate-500">
                                    Belum ada presensi hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Supervised Students Quick List --}}
        <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200/80 dark:border-slate-700 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900 dark:text-white font-heading">Mahasiswa Bimbingan</h2>
                <a href="{{ route('mentor.presensi.export') }}" class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
                    Export CSV
                </a>
            </div>

            <div class="space-y-3">
                @forelse ($mahasiswas as $mhs)
                    <div class="p-3.5 rounded-2xl border border-slate-100 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40 flex items-center justify-between">
                        <div>
                            <p class="font-bold text-xs text-slate-800 dark:text-slate-200">{{ $mhs->nama }}</p>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500">{{ $mhs->program_studi }} &bull; {{ $mhs->asal_universitas }}</p>
                        </div>
                        <a href="{{ route('mentor.logbook.index') }}?mahasiswa_id={{ $mhs->id }}" class="px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-bold text-[11px] hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors">
                            Logbook
                        </a>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 dark:text-slate-500 text-xs">
                        Belum ada mahasiswa yang ditugaskan ke bidang Anda.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

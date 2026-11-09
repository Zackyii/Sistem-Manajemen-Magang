@extends('layouts.mentor')

@section('page-title', 'Pemantauan Presensi Mahasiswa')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 dark:text-white font-heading">Pemantauan Presensi</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pantau kehadiran dan lokasi peserta magang bidang Anda</p>
        </div>
        {{-- Export Button --}}
        <button onclick="document.getElementById('export-modal').classList.remove('hidden')"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/20 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export CSV
        </button>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl bg-white dark:bg-slate-800 p-4 shadow-sm border border-slate-200/80 dark:border-slate-700">
        <form method="GET" action="{{ route('mentor.presensi.index') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3 text-xs">
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 dark:text-slate-400 mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal', today()->toDateString()) }}"
                    class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:border-blue-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 dark:text-slate-400 mb-1">Mahasiswa</label>
                <select name="mahasiswa_id" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:border-blue-600 focus:outline-none">
                    <option value="">Semua Mahasiswa</option>
                    @foreach ($mahasiswas as $m)
                        <option value="{{ $m->id }}" {{ request('mahasiswa_id') == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 dark:text-slate-400 mb-1">Universitas</label>
                <select name="universitas" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:border-blue-600 focus:outline-none">
                    <option value="">Semua Universitas</option>
                    @foreach ($universitas as $univ)
                        <option value="{{ $univ }}" {{ request('universitas') === $univ ? 'selected' : '' }}>{{ $univ }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 dark:text-slate-400 mb-1">Filter Periode</label>
                <select name="filter" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:border-blue-600 focus:outline-none">
                    <option value="">Tanggal di atas</option>
                    <option value="daily" {{ request('filter') === 'daily' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="weekly" {{ request('filter') === 'weekly' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="monthly" {{ request('filter') === 'monthly' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 rounded-xl font-bold text-white bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 dark:hover:bg-slate-600 transition-colors">Filter</button>
                @if (request()->hasAny(['tanggal', 'mahasiswa_id', 'universitas', 'filter']))
                    <a href="{{ route('mentor.presensi.index') }}" class="px-3 py-2 rounded-xl font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Attendance Table with Location --}}
    <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200/80 dark:border-slate-700 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Data Kehadiran</h2>
            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $kehadirans->total() }} record ditemukan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/50 text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wider">
                        <th class="p-3">Mahasiswa</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Masuk / Pulang</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Foto</th>
                        <th class="p-3">Titik & Nama Lokasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse ($kehadirans as $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 align-top">
                            <td class="p-3">
                                <p class="font-bold text-slate-800 dark:text-slate-200">{{ $item->mahasiswa->nama }}</p>
                                <p class="text-slate-400 dark:text-slate-500 text-[11px]">{{ $item->mahasiswa->asal_universitas }}</p>
                            </td>
                            <td class="p-3 text-slate-600 dark:text-slate-300 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                            </td>
                            <td class="p-3 font-mono text-slate-600 dark:text-slate-300 whitespace-nowrap">
                                {{ $item->waktu_masuk ? \Carbon\Carbon::parse($item->waktu_masuk)->format('H:i') : '-' }}
                                /
                                {{ $item->waktu_keluar ? \Carbon\Carbon::parse($item->waktu_keluar)->format('H:i') : '-' }}
                            </td>
                            <td class="p-3 whitespace-nowrap"><x-badge :status="$item->status" /></td>
                            <td class="p-3 whitespace-nowrap space-y-1">
                                @if ($item->foto_selfie)
                                    <a href="{{ route('files.presensi', [$item, 'selfie']) }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                        📷 Selfie
                                    </a>
                                @endif
                                @if ($item->file_bukti)
                                    <a href="{{ route('files.presensi', [$item, 'bukti']) }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-600 dark:text-amber-400 hover:underline">
                                        📎 Bukti
                                    </a>
                                @endif
                                @if ($item->foto_checkout)
                                    <a href="{{ route('files.presensi', [$item, 'checkout']) }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                                        📷 Checkout
                                    </a>
                                @endif
                                @if (!$item->foto_selfie && !$item->file_bukti && !$item->foto_checkout)
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="p-3 min-w-[220px]">
                                {{-- Check-in location --}}
                                <div class="space-y-0.5">
                                    @if ($item->koordinat_lat)
                                        <p class="text-[11px] font-semibold text-slate-600 dark:text-slate-300">
                                            <span class="text-emerald-600 dark:text-emerald-400">▶ Check-in</span>
                                            {{ $item->waktu_masuk ? \Carbon\Carbon::parse($item->waktu_masuk)->format('H:i') : '' }}
                                        </p>
                                        @if ($item->nama_lokasi)
                                            <p class="text-[11px] text-slate-600 dark:text-slate-400 truncate max-w-[200px]">📍 {{ $item->nama_lokasi }}</p>
                                        @endif
                                        <a href="https://maps.google.com/?q={{ $item->koordinat_lat }},{{ $item->koordinat_lng }}" target="_blank"
                                            class="text-[11px] text-blue-600 dark:text-blue-400 hover:underline font-mono">
                                            {{ $item->koordinat_lat }}, {{ $item->koordinat_lng }}
                                        </a>
                                    @else
                                        <p class="text-[11px] text-slate-400 dark:text-slate-500">Check-in: lokasi tidak tersedia</p>
                                    @endif

                                    @if ($item->koordinat_lat_checkout)
                                        <p class="text-[11px] font-semibold text-slate-600 dark:text-slate-300 mt-1">
                                            <span class="text-amber-600 dark:text-amber-400">◀ Check-out</span>
                                            {{ $item->waktu_keluar ? \Carbon\Carbon::parse($item->waktu_keluar)->format('H:i') : '' }}
                                        </p>
                                        @if ($item->nama_lokasi_checkout)
                                            <p class="text-[11px] text-slate-600 dark:text-slate-400 truncate max-w-[200px]">📍 {{ $item->nama_lokasi_checkout }}</p>
                                        @endif
                                        <a href="https://maps.google.com/?q={{ $item->koordinat_lat_checkout }},{{ $item->koordinat_lng_checkout }}" target="_blank"
                                            class="text-[11px] text-blue-600 dark:text-blue-400 hover:underline font-mono">
                                            {{ $item->koordinat_lat_checkout }}, {{ $item->koordinat_lng_checkout }}
                                        </a>
                                    @elseif ($item->waktu_keluar)
                                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Check-out: lokasi tidak tersedia</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 dark:text-slate-500">Tidak ada data kehadiran yang sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($kehadirans->hasPages())
            <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                {{ $kehadirans->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Export Modal --}}
<div id="export-modal" class="hidden fixed inset-0 bg-black/50 dark:bg-black/70 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl p-6 w-full max-w-md space-y-5">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Export Data Presensi (CSV)</h3>
            <button onclick="document.getElementById('export-modal').classList.add('hidden')"
                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('mentor.presensi.export') }}" method="GET" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Periode</label>
                <select name="period" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:border-blue-600 focus:outline-none">
                    <option value="daily">Harian (hari ini)</option>
                    <option value="weekly">Mingguan (minggu ini)</option>
                    <option value="monthly" selected>Bulanan (bulan ini)</option>
                    <option value="all">Seluruh Periode Magang</option>
                </select>
            </div>
            <div id="tanggal-export-wrap">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tanggal Spesifik (hanya untuk Harian)</label>
                <input type="date" name="tanggal" value="{{ today()->toDateString() }}"
                    class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:border-blue-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Filter Mahasiswa</label>
                <select name="mahasiswa_id" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:border-blue-600 focus:outline-none">
                    <option value="">Semua Mahasiswa Bidang</option>
                    @foreach ($mahasiswas as $m)
                        <option value="{{ $m->id }}">{{ $m->nama }} ({{ $m->asal_universitas }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Filter Universitas</label>
                <select name="universitas" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:border-blue-600 focus:outline-none">
                    <option value="">Semua Universitas dalam Bidang</option>
                    @foreach ($universitas as $univ)
                        <option value="{{ $univ }}">{{ $univ }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-2.5 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 text-sm transition-all">
                    Download CSV
                </button>
                <button type="button" onclick="document.getElementById('export-modal').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-xl font-semibold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-sm transition-all">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

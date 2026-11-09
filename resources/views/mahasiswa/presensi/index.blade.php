@extends('layouts.mahasiswa')

@section('title', 'Presensi Digital - SIMANG')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 dark:text-white font-heading">Presensi Digital</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Catat kehadiran harian Anda</p>
        </div>
        <a href="{{ route('mahasiswa.dashboard') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 dark:text-blue-400">
            &larr; Dashboard
        </a>
    </div>

    {{-- Main Action Card --}}
    <div class="rounded-3xl bg-white dark:bg-slate-800 p-5 shadow-sm border border-slate-200/90 dark:border-slate-700 overflow-hidden">
        @if (!$kehadiranHariIni)
            {{-- ── STEP 1: Choose attendance type ───────────────────────────────── --}}
            <div class="space-y-5">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300">
                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                        Presensi Masuk Hari Ini
                    </span>
                    <span id="current-clock" class="text-xs font-mono font-bold text-slate-700 dark:text-slate-300">--:--:--</span>
                </div>

                {{-- Jenis Presensi Selector --}}
                <div>
                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Pilih Jenis Presensi:</p>
                    <div class="grid grid-cols-3 gap-2" id="jenis-selector">
                        <button type="button" onclick="selectJenis('hadir')"
                            id="btn-jenis-hadir"
                            class="jenis-btn flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 border-blue-600 bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 font-bold text-xs transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Hadir
                        </button>
                        <button type="button" onclick="selectJenis('izin')"
                            id="btn-jenis-izin"
                            class="jenis-btn flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 border-slate-200 dark:border-slate-600 text-slate-500 dark:text-slate-400 font-bold text-xs transition-all hover:border-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 hover:text-amber-700 dark:hover:text-amber-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Izin
                        </button>
                        <button type="button" onclick="selectJenis('sakit')"
                            id="btn-jenis-sakit"
                            class="jenis-btn flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 border-slate-200 dark:border-slate-600 text-slate-500 dark:text-slate-400 font-bold text-xs transition-all hover:border-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:text-rose-700 dark:hover:text-rose-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            Izin Sakit
                        </button>
                    </div>
                </div>

                {{-- ── Form: HADIR ──────────────────────────────────────────── --}}
                <div id="form-hadir" class="space-y-4">
                    {{-- Camera Viewport --}}
                    <div class="relative w-full aspect-4/3 sm:aspect-video rounded-2xl bg-slate-900 overflow-hidden flex items-center justify-center border-2 border-dashed border-slate-300 dark:border-slate-600">
                        <video id="webcam" autoplay playsinline class="w-full h-full object-cover mirror"></video>
                        <canvas id="canvas" class="hidden"></canvas>
                        <img id="photo-preview" class="hidden w-full h-full object-cover" alt="Hasil Foto">
                        <div id="camera-overlay" class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center p-4">
                            <div class="w-48 h-56 border-2 border-white/60 rounded-full flex items-center justify-center">
                                <span class="text-[11px] text-white/80 bg-slate-900/60 px-2 py-0.5 rounded-full">Posisikan Wajah</span>
                            </div>
                        </div>
                        <div id="camera-loading" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900 text-white p-4 text-center">
                            <svg class="w-8 h-8 text-blue-400 animate-spin mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <p class="text-xs text-slate-300">Menghubungkan kamera...</p>
                            <button type="button" onclick="startCamera()" class="mt-3 px-3 py-1.5 bg-blue-600 text-xs rounded-lg font-semibold">Aktifkan Kamera</button>
                        </div>
                    </div>

                    {{-- GPS Info --}}
                    <div class="rounded-xl bg-slate-50 dark:bg-slate-900/50 p-3.5 border border-slate-200/70 dark:border-slate-700 text-xs space-y-1.5">
                        <div class="flex items-center justify-between font-semibold text-slate-700 dark:text-slate-300">
                            <div class="flex items-center gap-1.5 text-blue-700 dark:text-blue-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Lokasi GPS (dilaporkan perangkat)
                            </div>
                            <span id="gps-status" class="text-[11px] text-amber-600 dark:text-amber-400 font-bold">Mencari sinyal...</span>
                        </div>
                        <p id="gps-coords" class="text-slate-500 dark:text-slate-400 font-mono text-[11px]">Lat: - , Lng: -</p>
                        <p id="gps-address" class="text-slate-600 dark:text-slate-300 text-[11px]">Mendeteksi lokasi...</p>
                    </div>

                    {{-- Late Notice --}}
                    <div id="late-warning" class="hidden rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 p-3 text-xs text-amber-800 dark:text-amber-300 flex items-center gap-2 font-medium">
                        <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Presensi melewati pukul 08:00 WIB tercatat sebagai <strong>Terlambat</strong>.</span>
                    </div>

                    <form id="checkin-form-hadir" action="{{ route('mahasiswa.presensi.check-in') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="jenis_presensi" value="hadir">
                        <input type="hidden" name="foto_selfie" id="input-foto-selfie">
                        <input type="hidden" name="koordinat_lat" id="input-lat">
                        <input type="hidden" name="koordinat_lng" id="input-lng">
                        <input type="hidden" name="nama_lokasi" id="input-lokasi">

                        <div class="flex gap-2">
                            <button type="button" id="btn-snap" onclick="takeSnapshot()"
                                class="flex-1 py-3 px-4 rounded-xl text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-lg shadow-blue-700/20 flex items-center justify-center gap-2 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Jepret Foto Selfie
                            </button>
                            <button type="button" id="btn-retake" onclick="retakePhoto()" class="hidden py-3 px-4 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-all">
                                Ulangi
                            </button>
                        </div>
                        <button type="submit" id="btn-submit-hadir" disabled
                            class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Kirim Presensi Masuk (Hadir)
                        </button>
                    </form>
                </div>

                {{-- ── Form: IZIN ────────────────────────────────────────────── --}}
                <div id="form-izin" class="hidden space-y-4">
                    <div class="rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 p-3.5 text-xs text-amber-800 dark:text-amber-300">
                        <p class="font-bold mb-1">Presensi Izin</p>
                        <p>Unggah dokumen pendukung (surat izin, screenshot, atau dokumen relevan).</p>
                    </div>
                    <form action="{{ route('mahasiswa.presensi.check-in') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <input type="hidden" name="jenis_presensi" value="izin">
                        <input type="hidden" name="koordinat_lat" id="input-lat-izin">
                        <input type="hidden" name="koordinat_lng" id="input-lng-izin">
                        <input type="hidden" name="nama_lokasi" id="input-lokasi-izin">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Dokumen Pendukung <span class="text-rose-500">*</span></label>
                            <input type="file" name="file_bukti" accept=".jpg,.jpeg,.png,.pdf" required
                                class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-50 dark:file:bg-amber-900/30 file:text-amber-700 dark:file:text-amber-300 hover:file:bg-amber-100 dark:hover:file:bg-amber-900/50 border border-slate-200 dark:border-slate-600 rounded-xl p-2 bg-white dark:bg-slate-900">
                            <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, PDF. Maks 5 MB.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Keterangan (Opsional)</label>
                            <textarea name="keterangan" rows="2" maxlength="500"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 focus:border-blue-600 focus:outline-none resize-none"
                                placeholder="Alasan izin..."></textarea>
                        </div>
                        <button type="submit" class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-amber-600 hover:bg-amber-700 shadow-lg shadow-amber-600/20 flex items-center justify-center gap-2 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Kirim Presensi Izin
                        </button>
                    </form>
                </div>

                {{-- ── Form: IZIN SAKIT ──────────────────────────────────────── --}}
                <div id="form-sakit" class="hidden space-y-4">
                    <div class="rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 p-3.5 text-xs text-rose-800 dark:text-rose-300">
                        <p class="font-bold mb-1">Presensi Izin Sakit</p>
                        <p>Unggah surat keterangan dokter atau bukti sakit yang relevan.</p>
                    </div>
                    <form action="{{ route('mahasiswa.presensi.check-in') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <input type="hidden" name="jenis_presensi" value="sakit">
                        <input type="hidden" name="koordinat_lat" id="input-lat-sakit">
                        <input type="hidden" name="koordinat_lng" id="input-lng-sakit">
                        <input type="hidden" name="nama_lokasi" id="input-lokasi-sakit">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Surat Keterangan Sakit / Bukti <span class="text-rose-500">*</span></label>
                            <input type="file" name="file_bukti" accept=".jpg,.jpeg,.png,.pdf" required
                                class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-rose-50 dark:file:bg-rose-900/30 file:text-rose-700 dark:file:text-rose-300 hover:file:bg-rose-100 dark:hover:file:bg-rose-900/50 border border-slate-200 dark:border-slate-600 rounded-xl p-2 bg-white dark:bg-slate-900">
                            <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, PDF. Maks 5 MB.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Keterangan (Opsional)</label>
                            <textarea name="keterangan" rows="2" maxlength="500"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 focus:border-blue-600 focus:outline-none resize-none"
                                placeholder="Keterangan kondisi..."></textarea>
                        </div>
                        <button type="submit" class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-lg shadow-rose-600/20 flex items-center justify-center gap-2 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Kirim Presensi Sakit
                        </button>
                    </form>
                </div>
            </div>

        @elseif ($kehadiranHariIni && !$kehadiranHariIni->waktu_keluar && in_array($kehadiranHariIni->status, ['hadir', 'terlambat']))
            {{-- ── CHECK-OUT SECTION ─────────────────────────────────────────── --}}
            <div class="space-y-4">
                <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-300">
                    <div class="flex items-center gap-2 font-bold text-sm">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Presensi Masuk Berhasil Dicatat
                    </div>
                    <p class="text-xs mt-1">
                        Pukul <strong>{{ \Carbon\Carbon::parse($kehadiranHariIni->waktu_masuk)->format('H:i:s') }} WIB</strong>
                        — Status: <x-badge :status="$kehadiranHariIni->status" />
                    </p>
                    @if($kehadiranHariIni->nama_lokasi)
                        <p class="text-xs mt-0.5 opacity-80">📍 {{ $kehadiranHariIni->nama_lokasi }}</p>
                    @endif
                </div>

                <div class="rounded-xl bg-slate-50 dark:bg-slate-900/50 p-3.5 border border-slate-200/70 dark:border-slate-700 text-xs space-y-1.5">
                    <div class="flex items-center justify-between font-semibold text-slate-700 dark:text-slate-300">
                        <div class="flex items-center gap-1.5 text-blue-700 dark:text-blue-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Lokasi Check-out (dilaporkan perangkat)
                        </div>
                        <span id="gps-checkout-status" class="text-[11px] text-amber-600 dark:text-amber-400 font-bold">Mencari sinyal...</span>
                    </div>
                    <p id="gps-checkout-coords" class="text-slate-500 dark:text-slate-400 font-mono text-[11px]">Lat: - , Lng: -</p>
                    <p id="gps-checkout-address" class="text-slate-600 dark:text-slate-300 text-[11px]">Mendeteksi lokasi...</p>
                </div>

                <form action="{{ route('mahasiswa.presensi.check-out') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="koordinat_lat_checkout" id="input-lat-checkout">
                    <input type="hidden" name="koordinat_lng_checkout" id="input-lng-checkout">
                    <input type="hidden" name="nama_lokasi_checkout" id="input-lokasi-checkout">
                    <button type="submit"
                        class="w-full mt-2 py-3 px-4 rounded-xl text-sm font-bold text-white bg-amber-600 hover:bg-amber-700 shadow-md shadow-amber-600/20 transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Presensi Pulang (Check-out)
                    </button>
                </form>
            </div>

        @elseif ($kehadiranHariIni && !$kehadiranHariIni->waktu_keluar)
            {{-- Izin/Sakit — no checkout needed --}}
            <div class="p-6 text-center space-y-2 bg-blue-50/50 dark:bg-blue-900/20 rounded-2xl border border-blue-100 dark:border-blue-800">
                <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-300 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Presensi Hari Ini Tercatat</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Status: <x-badge :status="$kehadiranHariIni->status" /></p>
                @if($kehadiranHariIni->file_bukti)
                    <a href="{{ route('files.presensi', [$kehadiranHariIni, 'bukti']) }}" target="_blank"
                        class="inline-flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400 hover:underline">
                        📎 Lihat Bukti yang Diunggah
                    </a>
                @endif
            </div>

        @else
            {{-- COMPLETED TODAY --}}
            <div class="p-6 text-center space-y-2 bg-emerald-50/50 dark:bg-emerald-900/20 rounded-2xl border border-emerald-100 dark:border-emerald-800">
                <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-800 text-emerald-700 dark:text-emerald-300 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Presensi Hari Ini Telah Lengkap</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Masuk: <strong>{{ \Carbon\Carbon::parse($kehadiranHariIni->waktu_masuk)->format('H:i') }} WIB</strong> |
                    Pulang: <strong>{{ \Carbon\Carbon::parse($kehadiranHariIni->waktu_keluar)->format('H:i') }} WIB</strong>
                </p>
                <div class="pt-2">
                    <a href="{{ route('mahasiswa.logbook.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800">
                        Isi Logbook Aktivitas Hari Ini &rarr;
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- Attendance History Table --}}
    <div class="rounded-3xl bg-white dark:bg-slate-800 p-5 shadow-sm border border-slate-200/90 dark:border-slate-700 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Riwayat Kehadiran Terakhir</h2>
            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $persentaseKehadiran }}% Kehadiran Bulan Ini</span>
        </div>

        <div class="overflow-x-auto -mx-5 sm:mx-0">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/50 text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wider">
                        <th class="p-3 pl-5 sm:pl-3">Tanggal</th>
                        <th class="p-3">Jam Masuk</th>
                        <th class="p-3">Jam Pulang</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Bukti / Selfie</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse ($riwayatKehadiran as $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30">
                            <td class="p-3 pl-5 sm:pl-3 font-semibold text-slate-800 dark:text-slate-200">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="p-3 font-mono text-slate-600 dark:text-slate-300">
                                {{ $item->waktu_masuk ? \Carbon\Carbon::parse($item->waktu_masuk)->format('H:i') : '-' }}
                            </td>
                            <td class="p-3 font-mono text-slate-600 dark:text-slate-300">
                                {{ $item->waktu_keluar ? \Carbon\Carbon::parse($item->waktu_keluar)->format('H:i') : '-' }}
                            </td>
                            <td class="p-3"><x-badge :status="$item->status" /></td>
                            <td class="p-3 text-right">
                                @if ($item->foto_selfie)
                                    <a href="{{ route('files.presensi', [$item, 'selfie']) }}" target="_blank"
                                        class="text-blue-600 dark:text-blue-400 hover:underline font-semibold text-[11px]">Foto</a>
                                @endif
                                @if ($item->file_bukti)
                                    <a href="{{ route('files.presensi', [$item, 'bukti']) }}" target="_blank"
                                        class="text-amber-600 dark:text-amber-400 hover:underline font-semibold text-[11px] ml-1">Bukti</a>
                                @endif
                                @if (!$item->foto_selfie && !$item->file_bukti)
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-6 text-center text-slate-400 dark:text-slate-500">Belum ada riwayat kehadiran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
let videoStream = null;
let selectedJenis = 'hadir';

// Clock
function updateClock() {
    const el = document.getElementById('current-clock');
    if (!el) return;
    const now = new Date();
    el.innerText = now.toLocaleTimeString('id-ID') + ' WIB';
    const warn = document.getElementById('late-warning');
    if (warn && now.getHours() >= 8) warn.classList.remove('hidden');
}
setInterval(updateClock, 1000);
updateClock();

// Select jenis presensi
function selectJenis(jenis) {
    selectedJenis = jenis;
    ['hadir','izin','sakit'].forEach(j => {
        const btn = document.getElementById('btn-jenis-' + j);
        const frm = document.getElementById('form-' + j);
        if (!btn || !frm) return;
        if (j === jenis) {
            btn.classList.remove('border-slate-200','dark:border-slate-600','text-slate-500','dark:text-slate-400');
            if (j === 'hadir') btn.classList.add('border-blue-600','bg-blue-50','dark:bg-blue-900/40','text-blue-700','dark:text-blue-300');
            if (j === 'izin') btn.classList.add('border-amber-500','bg-amber-50','dark:bg-amber-900/40','text-amber-700','dark:text-amber-300');
            if (j === 'sakit') btn.classList.add('border-rose-500','bg-rose-50','dark:bg-rose-900/40','text-rose-700','dark:text-rose-300');
            frm.classList.remove('hidden');
        } else {
            btn.classList.add('border-slate-200','dark:border-slate-600','text-slate-500','dark:text-slate-400');
            btn.classList.remove('border-blue-600','bg-blue-50','dark:bg-blue-900/40','text-blue-700','dark:text-blue-300','border-amber-500','bg-amber-50','dark:bg-amber-900/40','text-amber-700','dark:text-amber-300','border-rose-500','bg-rose-50','dark:bg-rose-900/40','text-rose-700','dark:text-rose-300');
            frm.classList.add('hidden');
        }
    });
    if (jenis === 'hadir') startCamera();
}

// Camera
async function startCamera() {
    const video = document.getElementById('webcam');
    const loading = document.getElementById('camera-loading');
    if (!video) return;
    try {
        videoStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } }, audio: false });
        video.srcObject = videoStream;
        loading.classList.add('hidden');
    } catch (err) {
        loading.innerHTML = `<p class="text-xs text-amber-300 mb-2">Kamera tidak aktif (HTTP atau izin ditolak).</p>`;
    }
}

function takeSnapshot() {
    const video = document.getElementById('webcam');
    const canvas = document.getElementById('canvas');
    const preview = document.getElementById('photo-preview');
    const inputSelfie = document.getElementById('input-foto-selfie');
    const btnSnap = document.getElementById('btn-snap');
    const btnRetake = document.getElementById('btn-retake');
    const btnSubmit = document.getElementById('btn-submit-hadir');
    const overlay = document.getElementById('camera-overlay');
    if (!video || !canvas) return;
    canvas.width = video.videoWidth || 640;
    canvas.height = video.videoHeight || 480;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
    preview.src = dataUrl;
    preview.classList.remove('hidden');
    video.classList.add('hidden');
    if (overlay) overlay.classList.add('hidden');
    inputSelfie.value = dataUrl;
    btnSnap.classList.add('hidden');
    btnRetake.classList.remove('hidden');
    btnSubmit.disabled = false;
}

function retakePhoto() {
    const video = document.getElementById('webcam');
    const preview = document.getElementById('photo-preview');
    const btnSnap = document.getElementById('btn-snap');
    const btnRetake = document.getElementById('btn-retake');
    const btnSubmit = document.getElementById('btn-submit-hadir');
    const overlay = document.getElementById('camera-overlay');
    preview.classList.add('hidden');
    video.classList.remove('hidden');
    if (overlay) overlay.classList.remove('hidden');
    document.getElementById('input-foto-selfie').value = '';
    btnSnap.classList.remove('hidden');
    btnRetake.classList.add('hidden');
    btnSubmit.disabled = true;
}

// Geolocation — generic, fills multiple input sets
function detectLocation(latId, lngId, statusId, coordsId, addrId, lokasiId) {
    const statusEl = document.getElementById(statusId);
    const coordsEl = document.getElementById(coordsId);
    if (!navigator.geolocation) {
        if (statusEl) statusEl.innerText = 'GPS tidak didukung';
        return;
    }
    navigator.geolocation.getCurrentPosition(
        (pos) => {
            const lat = pos.coords.latitude.toFixed(6);
            const lng = pos.coords.longitude.toFixed(6);
            const acc = Math.round(pos.coords.accuracy);
            if (statusEl) { statusEl.innerText = 'GPS Terkunci'; statusEl.className = 'text-[11px] text-emerald-600 dark:text-emerald-400 font-bold'; }
            if (coordsEl) coordsEl.innerText = `Lat: ${lat}, Lng: ${lng} (±${acc}m)`;
            const latEl = document.getElementById(latId); if (latEl) latEl.value = lat;
            const lngEl = document.getElementById(lngId); if (lngEl) lngEl.value = lng;
            // Copy to sibling forms
            ['izin','sakit'].forEach(j => {
                const la = document.getElementById('input-lat-'+j); if(la) la.value = lat;
                const lo = document.getElementById('input-lng-'+j); if(lo) lo.value = lng;
                const lk = document.getElementById('input-lokasi-'+j); if(lk) lk.value = 'Koordinat GPS: '+lat+','+lng;
            });
            if (lokasiId) { const lkEl = document.getElementById(lokasiId); if(lkEl) lkEl.value = 'Koordinat GPS: '+lat+','+lng; }
            if (addrId) { const adEl = document.getElementById(addrId); if(adEl) adEl.innerText = 'Koordinat: '+lat+', '+lng; }
        },
        (err) => {
            if (statusEl) { statusEl.innerText = 'Lokasi tidak tersedia'; statusEl.className = 'text-[11px] text-slate-400 font-bold'; }
            if (coordsEl) coordsEl.innerText = 'Izin lokasi ditolak atau perangkat tidak mendukung.';
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
}

window.addEventListener('DOMContentLoaded', () => {
    selectJenis('hadir');
    detectLocation('input-lat','input-lng','gps-status','gps-coords','gps-address','input-lokasi');
    detectLocation('input-lat-checkout','input-lng-checkout','gps-checkout-status','gps-checkout-coords','gps-checkout-address','input-lokasi-checkout');
});
</script>
<style>.mirror { transform: scaleX(-1); }</style>
@endpush
@endsection

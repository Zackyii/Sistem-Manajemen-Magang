@extends('layouts.mahasiswa')

@section('title', 'Presensi & Selfie GPS - SIMANG')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 font-heading">Presensi Digital</h1>
            <p class="text-xs text-slate-500 mt-0.5">Validasi Kehadiran melalui Kamera Langsung & Koordinat GPS</p>
        </div>
        <a href="{{ route('mahasiswa.dashboard') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
            &larr; Dashboard
        </a>
    </div>

    <!-- Camera / Action Card -->
    <div class="rounded-3xl bg-white p-5 shadow-sm border border-slate-200/90 overflow-hidden">
        @if (!$kehadiranHariIni)
            <!-- CHECK-IN SECTION -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                        Ambil Presensi Masuk
                    </span>
                    <span id="current-clock" class="text-xs font-mono font-bold text-slate-700">--:--:-- WIB</span>
                </div>

                <!-- Camera Viewport Box -->
                <div class="relative w-full aspect-4/3 sm:aspect-video rounded-2xl bg-slate-900 overflow-hidden flex items-center justify-center border-2 border-dashed border-slate-300">
                    <video id="webcam" autoplay playsinline class="w-full h-full object-cover mirror"></video>
                    <canvas id="canvas" class="hidden"></canvas>
                    <img id="photo-preview" class="hidden w-full h-full object-cover" alt="Hasil Foto">

                    <!-- Camera Overlay Guidelines -->
                    <div id="camera-overlay" class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center p-4">
                        <div class="w-48 h-56 border-2 border-white/60 rounded-full flex items-center justify-center">
                            <span class="text-[11px] text-white/80 bg-slate-900/60 px-2 py-0.5 rounded-full">Posisikan Wajah</span>
                        </div>
                    </div>

                    <!-- Loading / Fallback Indicator -->
                    <div id="camera-loading" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900 text-white p-4 text-center">
                        <svg class="w-8 h-8 text-blue-400 animate-spin mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <p class="text-xs text-slate-300">Menghubungkan ke kamera...</p>
                        <button type="button" onclick="startCamera()" class="mt-3 px-3 py-1.5 bg-blue-600 text-xs rounded-lg font-semibold">Aktifkan Kamera</button>
                    </div>
                </div>

                <!-- GPS Location Information -->
                <div class="rounded-xl bg-slate-50 p-3.5 border border-slate-200/70 text-xs space-y-1.5">
                    <div class="flex items-center justify-between font-semibold text-slate-700">
                        <div class="flex items-center gap-1.5 text-blue-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Lokasi GPS Anda
                        </div>
                        <span id="gps-status" class="text-[11px] text-amber-600 font-bold">Mencari sinyal GPS...</span>
                    </div>
                    <p id="gps-coords" class="text-slate-500 font-mono text-[11px]">Lat: - , Lng: - (Akurasi: - m)</p>
                    <p id="gps-address" class="text-slate-600 text-[11px]">Kantor Diskominfo Prov. Jawa Timur (Jl. Ahmad Yani No.242-244, Gayungan, Surabaya)</p>
                </div>

                <!-- Late Notice -->
                <div id="late-warning" class="hidden rounded-xl bg-amber-50 border border-amber-200 p-3 text-xs text-amber-800 flex items-center gap-2 font-medium">
                    <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>Presensi melewati pukul 08:00 WIB akan tercatat sebagai status <strong>Terlambat</strong>.</span>
                </div>

                <!-- Form to Submit Check-in -->
                <form id="checkin-form" action="{{ route('mahasiswa.presensi.check-in') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="foto_selfie" id="input-foto-selfie">
                    <input type="hidden" name="koordinat_lat" id="input-lat">
                    <input type="hidden" name="koordinat_lng" id="input-lng">
                    <input type="hidden" name="nama_lokasi" id="input-lokasi" value="Diskominfo Prov. Jatim">

                    <div class="flex gap-2">
                        <button type="button" id="btn-snap" onclick="takeSnapshot()" class="flex-1 py-3 px-4 rounded-xl text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-lg shadow-blue-700/20 flex items-center justify-center gap-2 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Jepret Foto Selfie
                        </button>
                        <button type="button" id="btn-retake" onclick="retakePhoto()" class="hidden py-3 px-4 rounded-xl text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-all">
                            Ulangi
                        </button>
                    </div>

                    <button type="submit" id="btn-submit" disabled class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Kirim Presensi Masuk
                    </button>
                </form>
            </div>
        @elseif ($kehadiranHariIni && !$kehadiranHariIni->waktu_keluar)
            <!-- CHECK-OUT SECTION -->
            <div class="space-y-4">
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900">
                    <div class="flex items-center gap-2 font-bold text-sm">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Presensi Masuk Berhasil Dicatat
                    </div>
                    <p class="text-xs text-emerald-700 mt-1">
                        Tercatat pada pukul <strong>{{ \Carbon\Carbon::parse($kehadiranHariIni->waktu_masuk)->format('H:i:s') }} WIB</strong> dengan status <x-badge :status="$kehadiranHariIni->status" />.
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center space-y-2">
                    <h3 class="text-sm font-bold text-slate-800">Selesai Beraktivitas Hari Ini?</h3>
                    <p class="text-xs text-slate-500">Lakukan presensi pulang untuk menyelesaikan catatan kehadiran harian Anda.</p>
                    
                    <form action="{{ route('mahasiswa.presensi.check-out') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full mt-2 py-3 px-4 rounded-xl text-sm font-bold text-white bg-amber-600 hover:bg-amber-700 shadow-md shadow-amber-600/20 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Presensi Pulang (Check-out) Sekarang
                        </button>
                    </form>
                </div>
            </div>
        @else
            <!-- COMPLETED TODAY -->
            <div class="p-6 text-center space-y-2 bg-emerald-50/50 rounded-2xl border border-emerald-100">
                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Presensi Hari Ini Telah Lengkap</h3>
                <p class="text-xs text-slate-500">
                    Masuk: <strong>{{ \Carbon\Carbon::parse($kehadiranHariIni->waktu_masuk)->format('H:i') }} WIB</strong> | 
                    Pulang: <strong>{{ \Carbon\Carbon::parse($kehadiranHariIni->waktu_keluar)->format('H:i') }} WIB</strong>
                </p>
                <div class="pt-2">
                    <a href="{{ route('mahasiswa.logbook.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800">
                        Isi Logbook Aktivitas Hari Ini &rarr;
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Attendance History Table -->
    <div class="rounded-3xl bg-white p-5 shadow-sm border border-slate-200/90 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900">Riwayat Kehadiran Terakhir</h2>
            <span class="text-xs text-slate-500">{{ $persentaseKehadiran }}% Kehadiran Bulan Ini</span>
        </div>

        <div class="overflow-x-auto -mx-5 sm:mx-0">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-600 font-semibold uppercase tracking-wider">
                        <th class="p-3 pl-5 sm:pl-3">Tanggal</th>
                        <th class="p-3">Jam Masuk</th>
                        <th class="p-3">Jam Pulang</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Validasi Mentor</th>
                        <th class="p-3 pr-5 sm:pr-3 text-right">Selfie</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($riwayatKehadiran as $item)
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-3 pl-5 sm:pl-3 font-semibold text-slate-800">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="p-3 font-mono text-slate-600">
                                {{ $item->waktu_masuk ? \Carbon\Carbon::parse($item->waktu_masuk)->format('H:i') : '-' }}
                            </td>
                            <td class="p-3 font-mono text-slate-600">
                                {{ $item->waktu_keluar ? \Carbon\Carbon::parse($item->waktu_keluar)->format('H:i') : '-' }}
                            </td>
                            <td class="p-3">
                                <x-badge :status="$item->status" />
                            </td>
                            <td class="p-3">
                                <x-badge :status="$item->status_mentor" />
                            </td>
                            <td class="p-3 pr-5 sm:pr-3 text-right">
                                @if ($item->foto_selfie)
                                    <a href="{{ asset('storage/'.$item->foto_selfie) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline font-semibold text-[11px]">
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400">Belum ada riwayat kehadiran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let videoStream = null;

    // Realtime Clock
    function updateClock() {
        const el = document.getElementById('current-clock');
        if (el) {
            const now = new Date();
            el.innerText = now.toLocaleTimeString('id-ID') + ' WIB';
            
            // Check if past 08:00
            const warningEl = document.getElementById('late-warning');
            if (warningEl) {
                if (now.getHours() >= 8 && now.getMinutes() > 0) {
                    warningEl.classList.remove('hidden');
                }
            }
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Start Webcam
    async function startCamera() {
        const video = document.getElementById('webcam');
        const loading = document.getElementById('camera-loading');
        if (!video) return;

        try {
            videoStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
                audio: false
            });
            video.srcObject = videoStream;
            loading.classList.add('hidden');
        } catch (err) {
            console.warn("Kamera tidak dapat diakses langsung, gunakan mode demo:", err);
            loading.innerHTML = `
                <p class="text-xs text-amber-300 mb-2">Akses kamera tidak aktif (HTTP atau izin ditolak).</p>
                <button type="button" onclick="generateDemoSnapshot()" class="px-3 py-1.5 bg-amber-600 text-white font-bold text-xs rounded-lg">Gunakan Foto Simulasi</button>
            `;
        }
    }

    // Capture Snapshot to Canvas & Base64
    function takeSnapshot() {
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        const preview = document.getElementById('photo-preview');
        const inputSelfie = document.getElementById('input-foto-selfie');
        const btnSnap = document.getElementById('btn-snap');
        const btnRetake = document.getElementById('btn-retake');
        const btnSubmit = document.getElementById('btn-submit');
        const overlay = document.getElementById('camera-overlay');

        if (!video || !canvas) return;

        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

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

    function generateDemoSnapshot() {
        const canvas = document.getElementById('canvas');
        const preview = document.getElementById('photo-preview');
        const inputSelfie = document.getElementById('input-foto-selfie');
        const btnSubmit = document.getElementById('btn-submit');
        const loading = document.getElementById('camera-loading');

        canvas.width = 400;
        canvas.height = 300;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#1E40AF';
        ctx.fillRect(0, 0, 400, 300);
        ctx.fillStyle = '#FFFFFF';
        ctx.font = 'bold 16px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('PRESENSI SIMANG DISKOMINFO', 200, 140);
        ctx.font = '12px Inter, sans-serif';
        ctx.fillText(new Date().toLocaleString('id-ID'), 200, 170);

        const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
        preview.src = dataUrl;
        preview.classList.remove('hidden');
        loading.classList.add('hidden');
        inputSelfie.value = dataUrl;
        btnSubmit.disabled = false;
    }

    function retakePhoto() {
        const video = document.getElementById('webcam');
        const preview = document.getElementById('photo-preview');
        const inputSelfie = document.getElementById('input-foto-selfie');
        const btnSnap = document.getElementById('btn-snap');
        const btnRetake = document.getElementById('btn-retake');
        const btnSubmit = document.getElementById('btn-submit');
        const overlay = document.getElementById('camera-overlay');

        preview.classList.add('hidden');
        video.classList.remove('hidden');
        if (overlay) overlay.classList.remove('hidden');
        inputSelfie.value = '';
        btnSnap.classList.remove('hidden');
        btnRetake.classList.add('hidden');
        btnSubmit.disabled = true;
    }

    // Geolocation API
    function detectLocation() {
        const statusEl = document.getElementById('gps-status');
        const coordsEl = document.getElementById('gps-coords');
        const inputLat = document.getElementById('input-lat');
        const inputLng = document.getElementById('input-lng');

        if (!navigator.geolocation) {
            if (statusEl) statusEl.innerText = "GPS tidak didukung";
            // Fallback to Diskominfo Jatim coords
            if (inputLat) inputLat.value = "-7.3156";
            if (inputLng) inputLng.value = "112.7291";
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const lat = pos.coords.latitude.toFixed(6);
                const lng = pos.coords.longitude.toFixed(6);
                const acc = Math.round(pos.coords.accuracy);

                if (statusEl) {
                    statusEl.innerText = "GPS Terkunci (Akurat)";
                    statusEl.className = "text-[11px] text-emerald-600 font-bold";
                }
                if (coordsEl) coordsEl.innerText = `Lat: ${lat}, Lng: ${lng} (Akurasi: ±${acc}m)`;
                if (inputLat) inputLat.value = lat;
                if (inputLng) inputLng.value = lng;
            },
            (err) => {
                if (statusEl) {
                    statusEl.innerText = "Koordinat Default Diskominfo Jatim";
                    statusEl.className = "text-[11px] text-blue-600 font-bold";
                }
                if (coordsEl) coordsEl.innerText = "Lat: -7.315600, Lng: 112.729100";
                if (inputLat) inputLat.value = "-7.315600";
                if (inputLng) inputLng.value = "112.729100";
            },
            { enableHighAccuracy: true, timeout: 8000 }
        );
    }

    window.addEventListener('DOMContentLoaded', () => {
        startCamera();
        detectLocation();
    });
</script>
<style>
    .mirror { transform: scaleX(-1); }
</style>
@endpush
@endsection

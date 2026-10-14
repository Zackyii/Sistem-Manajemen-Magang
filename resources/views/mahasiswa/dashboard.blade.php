@extends('layouts.mahasiswa')

@section('title', 'Dashboard Mahasiswa - SIMANG')

@section('content')
<div class="space-y-6">
    <!-- Student Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 p-6 text-white shadow-xl">
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-amber-300 backdrop-blur-md border border-white/10">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-ping"></span>
                    Magang Aktif
                </span>
                <span class="text-xs text-blue-200 font-medium">{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <div class="mt-4">
                <h1 class="text-2xl font-extrabold tracking-tight font-heading">Halo, {{ $mahasiswa->nama }}! 👋</h1>
                <p class="mt-1 text-sm text-blue-100/90 font-medium">
                    {{ $mahasiswa->program_studi }} &bull; {{ $mahasiswa->asal_universitas }}
                </p>
                <div class="mt-2 inline-flex items-center gap-2 rounded-lg bg-blue-950/40 px-3 py-1 text-xs text-blue-200 border border-white/10">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Bidang: <strong class="text-white">{{ $mahasiswa->bidang->nama_bidang ?? 'Belum Ditentukan' }}</strong>
                </div>
            </div>
        </div>
        <!-- Decorative Glow -->
        <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-amber-400/20 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    <!-- Attendance Today Status Card -->
    <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-200/80">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Presensi Hari Ini</h2>
                    <p class="text-xs text-slate-500">Jam Kerja: 07.30 - 16.00 WIB</p>
                </div>
            </div>

            @if ($kehadiranHariIni)
                <x-badge :status="$kehadiranHariIni->status" class="text-xs px-2.5 py-1" />
            @else
                <span class="inline-flex items-center rounded-md bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-600/20">
                    Belum Presensi
                </span>
            @endif
        </div>

        <div class="mt-4 grid grid-cols-2 gap-3 text-center">
            <div class="rounded-xl bg-slate-50 p-3">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Jam Masuk</span>
                <span class="text-base font-bold text-slate-800 mt-1 block">
                    {{ $kehadiranHariIni && $kehadiranHariIni->waktu_masuk ? \Carbon\Carbon::parse($kehadiranHariIni->waktu_masuk)->format('H:i') . ' WIB' : '--:--' }}
                </span>
            </div>
            <div class="rounded-xl bg-slate-50 p-3">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Jam Pulang</span>
                <span class="text-base font-bold text-slate-800 mt-1 block">
                    {{ $kehadiranHariIni && $kehadiranHariIni->waktu_keluar ? \Carbon\Carbon::parse($kehadiranHariIni->waktu_keluar)->format('H:i') . ' WIB' : '--:--' }}
                </span>
            </div>
        </div>

        <div class="mt-4">
            @if (!$kehadiranHariIni)
                <a href="{{ route('mahasiswa.presensi.index') }}" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-md shadow-blue-700/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Ambil Presensi Masuk (Selfie + GPS)
                </a>
            @elseif ($kehadiranHariIni && !$kehadiranHariIni->waktu_keluar)
                <a href="{{ route('mahasiswa.presensi.index') }}" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-sm font-bold text-white bg-amber-600 hover:bg-amber-700 shadow-md shadow-amber-600/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Presensi Pulang (Check-out)
                </a>
            @else
                <div class="rounded-xl bg-emerald-50 p-3 text-center text-xs font-semibold text-emerald-700">
                    ✅ Presensi hari ini telah lengkap (Masuk & Pulang tercatat).
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-3 gap-3">
        <div class="rounded-2xl bg-white p-4 shadow-sm border border-slate-100 text-center">
            <span class="text-xs font-semibold text-slate-500 block">Hadir</span>
            <span class="text-xl font-bold text-emerald-600 mt-1 block">{{ $ringkasanBulan['hadir'] }}</span>
            <span class="text-[10px] text-slate-400">Bulan ini</span>
        </div>
        <div class="rounded-2xl bg-white p-4 shadow-sm border border-slate-100 text-center">
            <span class="text-xs font-semibold text-slate-500 block">Izin/Sakit</span>
            <span class="text-xl font-bold text-blue-600 mt-1 block">{{ $ringkasanBulan['izin_sakit'] }}</span>
            <span class="text-[10px] text-slate-400">Bulan ini</span>
        </div>
        <div class="rounded-2xl bg-white p-4 shadow-sm border border-slate-100 text-center">
            <span class="text-xs font-semibold text-slate-500 block">Logbook</span>
            <span class="text-xl font-bold text-amber-500 mt-1 block">{{ $logbookIsi }}</span>
            <span class="text-[10px] text-slate-400">Tervalidasi</span>
        </div>
    </div>

    <!-- Upcoming Tasks Section -->
    <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-200/80">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <h2 class="text-sm font-bold text-slate-900">Tugas & Proyek Magang</h2>
            </div>
            <a href="{{ route('mahasiswa.tanggalan.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Lihat Kalender &rarr;</a>
        </div>

        @if ($tugasMendatang->isEmpty())
            <div class="p-6 text-center rounded-xl bg-slate-50 text-slate-400 text-xs">
                Tidak ada tenggat tugas dalam waktu dekat.
            </div>
        @else
            <div class="space-y-3">
                @foreach ($tugasMendatang as $tugas)
                    <div class="flex items-start justify-between p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-slate-50 transition-colors">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">{{ $tugas->info_tugas }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Deadline: {{ $tugas->deadline_tugas ? \Carbon\Carbon::parse($tugas->deadline_tugas)->translatedFormat('d M Y') : 'Fleksibel' }}
                            </p>
                        </div>
                        <a href="{{ route('mahasiswa.logbook.create', ['tanggal' => $tugas->tanggal->format('Y-m-d')]) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 font-semibold text-xs hover:bg-blue-100 transition-colors shrink-0">
                            Catat Logbook
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Announcements Section -->
    <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-200/80">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                <h2 class="text-sm font-bold text-slate-900">Pengumuman Terkini</h2>
            </div>
        </div>

        @if ($pengumuman->isEmpty())
            <div class="p-6 text-center rounded-xl bg-slate-50 text-slate-400 text-xs">
                Belum ada pengumuman baru.
            </div>
        @else
            <div class="space-y-3">
                @foreach ($pengumuman as $p)
                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <span class="text-xs font-bold text-slate-800">{{ $p->judul }}</span>
                            <x-badge :status="$p->tipe" />
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $p->isi_pengumuman }}</p>
                        <span class="text-[10px] text-slate-400 mt-2 block">{{ \Carbon\Carbon::parse($p->tanggal_publikasi)->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

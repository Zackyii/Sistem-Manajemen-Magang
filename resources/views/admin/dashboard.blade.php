@extends('layouts.admin')

@section('page-title', 'Dashboard Administrator')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="rounded-3xl bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 p-6 text-white shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-sky-500/20 text-sky-300 border border-sky-500/30">
                Pusat Kendali SIMANG
            </span>
            <h1 class="text-2xl font-extrabold tracking-tight mt-2 font-heading">Selamat Datang, Administrator!</h1>
            <p class="text-xs text-slate-300 mt-0.5">Pantau data peserta magang, kuota bidang, dan verifikasi akun kedinasan.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.mahasiswa.create') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/30 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Mahasiswa
            </a>
            <a href="{{ route('admin.pengumuman.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-900 bg-amber-400 hover:bg-amber-300 shadow-md shadow-amber-400/20 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                Buat Pengumuman
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat-card title="Total Mahasiswa" :value="$stats['total_mahasiswa']" trend="Terdaftar dalam sistem" />
        <x-stat-card title="Mahasiswa Aktif" :value="$stats['mahasiswa_aktif']" trend="Sedang menjalani magang" />
        <x-stat-card title="Verifikasi Pending" :value="$stats['mahasiswa_pending']" trend="Menunggu persetujuan admin" />
        <x-stat-card title="Pembimbing (Mentor)" :value="$stats['total_mentor']" trend="ASN pendamping magang" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pending Students Table -->
        <div class="lg:col-span-2 rounded-3xl bg-white p-6 shadow-sm border border-slate-200/80 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900 font-heading">Pendaftaran Mahasiswa Baru (Pending)</h2>
                    <p class="text-xs text-slate-500">Periksa dan setujui penempatan bidang peserta magang</p>
                </div>
                <a href="{{ route('admin.mahasiswa.index', ['status' => 'pending']) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-600 font-semibold uppercase tracking-wider">
                            <th class="p-3">Nama</th>
                            <th class="p-3">Universitas & Prodi</th>
                            <th class="p-3">Bidang Diminta</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($mahasiswaPending as $mhs)
                            <tr class="hover:bg-slate-50/50">
                                <td class="p-3">
                                    <p class="font-bold text-slate-800">{{ $mhs->nama }}</p>
                                    <p class="text-slate-400 font-mono text-[11px]">{{ $mhs->user->username ?? '-' }}</p>
                                </td>
                                <td class="p-3">
                                    <p class="font-medium text-slate-700">{{ $mhs->asal_universitas }}</p>
                                    <p class="text-slate-400 text-[11px]">{{ $mhs->program_studi }}</p>
                                </td>
                                <td class="p-3">
                                    <span class="font-semibold text-blue-700">{{ $mhs->bidang->nama_bidang ?? 'Belum ada' }}</span>
                                </td>
                                <td class="p-3 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.mahasiswa.show', $mhs) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 font-bold hover:bg-blue-100 transition-colors">
                                        Verifikasi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-400">
                                    Tidak ada pendaftaran mahasiswa yang pending saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Latest Announcements Card -->
        <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200/80 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900 font-heading">Pengumuman Kedinasan</h2>
                <a href="{{ route('admin.pengumuman.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">Kelola</a>
            </div>

            <div class="space-y-3">
                @forelse ($pengumumanTerbaru as $pengumuman)
                    <div class="p-3.5 rounded-2xl border border-slate-100 bg-slate-50/60 space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-xs text-slate-800 line-clamp-1">{{ $pengumuman->judul }}</span>
                            <x-badge :status="$pengumuman->tipe" />
                        </div>
                        <p class="text-xs text-slate-500 line-clamp-2">{{ $pengumuman->isi_pengumuman }}</p>
                        <span class="text-[10px] text-slate-400 block">{{ \Carbon\Carbon::parse($pengumuman->tanggal_publikasi)->translatedFormat('d M Y') }}</span>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 text-xs">
                        Belum ada pengumuman yang diterbitkan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

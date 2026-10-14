@extends('layouts.admin')

@section('page-title', 'Pengumuman Kedinasan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 font-heading">Publikasi Pengumuman Magang</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kirimkan instruksi, agenda dinas, atau informasi penting kepada peserta magang</p>
        </div>
    </div>

    <!-- Create Announcement Form Card -->
    <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200/80 space-y-4">
        <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tulis Pengumuman Baru
        </h2>

        <form action="{{ route('admin.pengumuman.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label for="judul" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Judul Pengumuman</label>
                    <input type="text" id="judul" name="judul" required placeholder="Contoh: Petunjuk Pelaksanaan Apel Pagi dan Seragam Magang" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                </div>

                <div>
                    <label for="tipe" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Kategori / Tingkat</label>
                    <select id="tipe" name="tipe" required class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none">
                        <option value="info">Info Umum</option>
                        <option value="penting">Penting (Mendesak)</option>
                        <option value="sistem">Pemberitahuan Sistem</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="bidang_id" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Sasaran Pengumuman</label>
                <select id="bidang_id" name="bidang_id" class="w-full sm:w-1/2 rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none">
                    <option value="">Semua Mahasiswa (Seluruh Bidang)</option>
                    @foreach ($bidangs as $bidang)
                        <option value="{{ $bidang->id }}">Khusus Bidang: {{ $bidang->nama_bidang }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="isi_pengumuman" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Isi Pesan Pengumuman</label>
                <textarea id="isi_pengumuman" name="isi_pengumuman" rows="4" required placeholder="Tuliskan detail pengumuman resmi di sini..." class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 leading-relaxed"></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    Terbitkan Pengumuman
                </button>
            </div>
        </form>
    </div>

    <!-- Announcement History Table -->
    <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200/80 space-y-4">
        <h2 class="text-sm font-bold text-slate-900">Riwayat Pengumuman Diterbitkan</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-600 font-semibold uppercase tracking-wider">
                        <th class="p-3">Judul & Isi</th>
                        <th class="p-3">Target Sasaran</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Tanggal Diterbitkan</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pengumumans as $item)
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-3 max-w-md">
                                <p class="font-bold text-slate-900 text-sm">{{ $item->judul }}</p>
                                <p class="text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $item->isi_pengumuman }}</p>
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                @if ($item->bidang)
                                    <span class="font-semibold text-blue-700">{{ $item->bidang->nama_bidang }}</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">Semua Bidang</span>
                                @endif
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                <x-badge :status="$item->tipe" />
                            </td>
                            <td class="p-3 whitespace-nowrap text-slate-600">
                                {{ \Carbon\Carbon::parse($item->tanggal_publikasi)->translatedFormat('d M Y, H:i') }} WIB
                            </td>
                            <td class="p-3 text-right whitespace-nowrap">
                                <form action="{{ route('admin.pengumuman.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 font-bold text-rose-600">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">Belum ada riwayat pengumuman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pengumumans->hasPages())
            <div class="pt-4 border-t border-slate-100">
                {{ $pengumumans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

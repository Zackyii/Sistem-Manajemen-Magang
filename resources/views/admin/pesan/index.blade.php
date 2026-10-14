@extends('layouts.admin')

@section('page-title', 'Kotak Masuk Laporan & Pesan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 font-heading">Kotak Masuk Admin</h1>
            <p class="text-xs text-slate-500 mt-0.5">Laporan kendala sistem, permohonan izin, dan pesan dari mahasiswa dan mentor</p>
        </div>
        @if ($totalUnread > 0)
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse inline-block"></span>
                {{ $totalUnread }} Pesan Belum Dibaca
            </span>
        @endif
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200/80 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-600 font-semibold uppercase tracking-wider">
                        <th class="p-3">Pengirim</th>
                        <th class="p-3">Jenis Laporan</th>
                        <th class="p-3 max-w-sm">Isi Pesan</th>
                        <th class="p-3 whitespace-nowrap">Waktu Kirim</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pesanMasuk as $pesan)
                        <tr class="hover:bg-slate-50/50 {{ $pesan->is_read ? '' : 'bg-blue-50/30' }}">
                            <td class="p-3">
                                <p class="font-bold text-slate-900">
                                    {{ $pesan->pengirim->mahasiswa->nama ?? ($pesan->pengirim->mentor->nama ?? $pesan->pengirim->username) }}
                                </p>
                                <p class="text-[11px] text-slate-400 capitalize">{{ $pesan->pengirim->role }}</p>
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold
                                    {{ $pesan->tipe === 'laporan_sistem' ? 'bg-rose-100 text-rose-700' :
                                       ($pesan->tipe === 'izin_sakit' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ $pesan->tipe === 'laporan_sistem' ? 'Kendala Sistem' : ($pesan->tipe === 'izin_sakit' ? 'Izin / Sakit' : 'Pesan Langsung') }}
                                </span>
                            </td>
                            <td class="p-3 max-w-sm">
                                <p class="text-slate-700 line-clamp-2 leading-relaxed">{{ $pesan->isi_pesan }}</p>
                            </td>
                            <td class="p-3 whitespace-nowrap text-slate-500 font-mono">
                                {{ \Carbon\Carbon::parse($pesan->waktu_kirim)->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="p-3 text-center">
                                @if ($pesan->is_read)
                                    <span class="text-[11px] font-medium text-slate-400">Dibaca</span>
                                @else
                                    <span class="flex items-center justify-center gap-1 text-[11px] font-bold text-blue-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                        Baru
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 text-right whitespace-nowrap">
                                @if (!$pesan->is_read)
                                    <form action="{{ route('admin.pesan.mark-read', $pesan) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 font-bold hover:bg-blue-100 transition-colors">
                                            Tandai Dibaca
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-slate-300 font-medium">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Belum ada pesan atau laporan yang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pesanMasuk->hasPages())
            <div class="pt-4 border-t border-slate-100">
                {{ $pesanMasuk->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

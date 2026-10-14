@extends('layouts.mentor')

@section('page-title', 'Verifikasi Presensi Mahasiswa')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 font-heading">Verifikasi Presensi Harian</h1>
            <p class="text-xs text-slate-500 mt-0.5">Tinjau kehadiran, jam kedatangan, dan swafoto (selfie) peserta magang</p>
        </div>
    </div>

    <!-- Filters & Bulk Action Card -->
    <div class="rounded-2xl bg-white p-4 shadow-sm border border-slate-200/80">
        <form method="GET" action="{{ route('mentor.presensi.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <input type="date" name="tanggal" value="{{ request('tanggal', today()->toDateString()) }}" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:border-blue-600 focus:outline-none">
            </div>

            <div>
                <select name="status" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:border-blue-600 focus:outline-none">
                    <option value="">Semua Status Mentor</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Perlu Persetujuan)</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div>
                <select name="mahasiswa_id" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:border-blue-600 focus:outline-none">
                    <option value="">Semua Mahasiswa</option>
                    @foreach ($mahasiswas as $m)
                        <option value="{{ $m->id }}" {{ request('mahasiswa_id') == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 rounded-xl font-bold text-white bg-slate-800 hover:bg-slate-900 transition-colors">
                    Filter
                </button>
                @if (request()->hasAny(['tanggal', 'status', 'mahasiswa_id']))
                    <a href="{{ route('mentor.presensi.index') }}" class="px-3 py-2 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bulk Action Form & Table -->
    <form action="{{ route('mentor.presensi.bulk-approve') }}" method="POST">
        @csrf

        <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200/80 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-900">Daftar Kehadiran</h2>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/20 transition-all flex items-center gap-1.5" onclick="return confirm('Setujui semua presensi yang dicentang?')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Setujui yang Dicentang (Bulk)
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-600 font-semibold uppercase tracking-wider">
                            <th class="p-3 w-8">
                                <input type="checkbox" onclick="toggleSelectAll(this)" class="rounded border-slate-300 text-blue-600">
                            </th>
                            <th class="p-3">Mahasiswa</th>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Masuk / Pulang</th>
                            <th class="p-3">Status Masuk</th>
                            <th class="p-3">Swafoto</th>
                            <th class="p-3">Status Mentor</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($kehadirans as $item)
                            <tr class="hover:bg-slate-50/50">
                                <td class="p-3">
                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="item-checkbox rounded border-slate-300 text-blue-600">
                                </td>
                                <td class="p-3 font-bold text-slate-800">
                                    {{ $item->mahasiswa->nama }}
                                </td>
                                <td class="p-3 text-slate-600 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="p-3 font-mono text-slate-600 whitespace-nowrap">
                                    {{ $item->waktu_masuk ? \Carbon\Carbon::parse($item->waktu_masuk)->format('H:i') : '-' }} / 
                                    {{ $item->waktu_keluar ? \Carbon\Carbon::parse($item->waktu_keluar)->format('H:i') : '-' }}
                                </td>
                                <td class="p-3 whitespace-nowrap">
                                    <x-badge :status="$item->status" />
                                </td>
                                <td class="p-3 whitespace-nowrap">
                                    @if ($item->foto_selfie)
                                        <a href="{{ asset('storage/'.$item->foto_selfie) }}" target="_blank" class="inline-flex items-center gap-1 font-bold text-blue-600 hover:text-blue-800 underline">
                                            Lihat Foto
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="p-3 whitespace-nowrap">
                                    <x-badge :status="$item->status_mentor" />
                                </td>
                                <td class="p-3 text-right whitespace-nowrap">
                                    @if ($item->status_mentor === 'pending')
                                        <div class="inline-flex items-center gap-1.5">
                                            <button type="submit" formaction="{{ route('mentor.presensi.approve', $item) }}" class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-bold hover:bg-emerald-100">
                                                Setujui
                                            </button>
                                            <button type="submit" formaction="{{ route('mentor.presensi.reject', $item) }}" class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 font-bold hover:bg-rose-100">
                                                Tolak
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-[11px] text-slate-400 font-medium">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400">Tidak ada data kehadiran yang sesuai filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($kehadirans->hasPages())
                <div class="pt-4 border-t border-slate-100">
                    {{ $kehadirans->links() }}
                </div>
            @endif
        </div>
    </form>
</div>

@push('scripts')
<script>
    function toggleSelectAll(master) {
        document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = master.checked);
    }
</script>
@endpush
@endsection

@extends('layouts.mentor')

@section('page-title', 'Review Logbook Mahasiswa')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 dark:text-white font-heading">Review & Validasi Logbook</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tinjau laporan progres kerja peserta bimbingan Anda</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl bg-white dark:bg-slate-800 p-4 shadow-sm border border-slate-200/80 dark:border-slate-700">
        <form method="GET" action="{{ route('mentor.logbook.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
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
                <label class="block text-[11px] font-semibold text-slate-500 dark:text-slate-400 mb-1">Status</label>
                <select name="status" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:border-blue-600 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Diajukan (Menunggu Review)</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 dark:text-slate-400 mb-1">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                    class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:border-blue-600 focus:outline-none">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 rounded-xl font-bold text-white bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 dark:hover:bg-slate-600 transition-colors">Filter</button>
                @if (request()->hasAny(['mahasiswa_id', 'status', 'tanggal_dari', 'tanggal_sampai']))
                    <a href="{{ route('mentor.logbook.index') }}" class="px-3 py-2 rounded-xl font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Bulk Approve Form + Table --}}
    <form id="bulk-form" action="{{ route('mentor.logbook.bulk-approve') }}" method="POST">
        @csrf
        <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200/80 dark:border-slate-700 space-y-4">
            {{-- Bulk Action Bar --}}
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span id="selected-count" class="text-xs font-bold text-slate-500 dark:text-slate-400">0 dipilih</span>
                </div>
                <button type="button" onclick="confirmBulkApprove()"
                    id="btn-bulk-approve"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/20 transition-all disabled:opacity-40 disabled:cursor-not-allowed"
                    disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Approve yang Dipilih
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/50 text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wider">
                            <th class="p-3 w-8">
                                <input type="checkbox" id="check-all" onclick="toggleAll(this)"
                                    class="rounded border-slate-300 dark:border-slate-600 text-blue-600">
                            </th>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Mahasiswa</th>
                            <th class="p-3">Aktivitas Utama</th>
                            <th class="p-3">Durasi</th>
                            <th class="p-3">Lampiran</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse ($logbooks as $entry)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30">
                                <td class="p-3">
                                    @if ($entry->status_logbook === 'submitted')
                                        <input type="checkbox" name="ids[]" value="{{ $entry->id }}"
                                            class="item-checkbox rounded border-slate-300 dark:border-slate-600 text-blue-600"
                                            onchange="updateCount()">
                                    @endif
                                </td>
                                <td class="p-3 font-semibold text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($entry->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="p-3 font-bold text-slate-800 dark:text-slate-200 whitespace-nowrap">
                                    {{ $entry->mahasiswa->nama ?? '-' }}
                                </td>
                                <td class="p-3 max-w-xs">
                                    <p class="font-bold text-slate-900 dark:text-white line-clamp-1">{{ $entry->aktivitas_utama }}</p>
                                    <p class="text-slate-500 dark:text-slate-400 line-clamp-1 mt-0.5">{{ $entry->deskripsi_kegiatan }}</p>
                                </td>
                                <td class="p-3 font-mono text-slate-600 dark:text-slate-300 whitespace-nowrap">
                                    {{ $entry->durasi_jam }} jam
                                </td>
                                <td class="p-3 whitespace-nowrap">
                                    @if ($entry->file_lampiran)
                                        <a href="{{ route('files.logbook', $entry) }}" target="_blank"
                                            class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                            📎 Lihat
                                        </a>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500">-</span>
                                    @endif
                                </td>
                                <td class="p-3 whitespace-nowrap"><x-badge :status="$entry->status_logbook" /></td>
                                <td class="p-3 text-right whitespace-nowrap">
                                    <a href="{{ route('mentor.logbook.show', $entry) }}"
                                        class="px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-bold hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors">
                                        Tinjau
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400 dark:text-slate-500">Tidak ada data logbook yang cocok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logbooks->hasPages())
                <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $logbooks->links() }}
                </div>
            @endif
        </div>
    </form>
</div>

@push('scripts')
<script>
function toggleAll(master) {
    document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = master.checked);
    updateCount();
}

function updateCount() {
    const checked = document.querySelectorAll('.item-checkbox:checked').length;
    document.getElementById('selected-count').innerText = checked + ' dipilih';
    document.getElementById('btn-bulk-approve').disabled = checked === 0;
    document.getElementById('check-all').checked = checked > 0 &&
        checked === document.querySelectorAll('.item-checkbox').length;
    document.getElementById('check-all').indeterminate = checked > 0 &&
        checked < document.querySelectorAll('.item-checkbox').length;
}

function confirmBulkApprove() {
    const count = document.querySelectorAll('.item-checkbox:checked').length;
    if (count === 0) return;
    if (confirm(`Setujui ${count} logbook yang dipilih? Tindakan ini tidak dapat dibatalkan.`)) {
        document.getElementById('bulk-form').submit();
    }
}
</script>
@endpush
@endsection

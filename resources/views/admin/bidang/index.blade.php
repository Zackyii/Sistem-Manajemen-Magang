@extends('layouts.admin')

@section('page-title', 'Manajemen Bidang & Kuota')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 dark:text-white font-heading">Bidang Dinas &amp; Kuota Magang</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kelola divisi penempatan mahasiswa magang di Diskominfo Jawa Timur</p>
        </div>
        <button type="button" onclick="openAddModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/30 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Bidang Baru
        </button>
    </div>

    <!-- Table Card -->
    <div class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-200/80 dark:border-slate-700">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/50 text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wider">
                        <th class="p-3">Nama Bidang</th>
                        <th class="p-3">Deskripsi</th>
                        <th class="p-3 text-center">Kuota</th>
                        <th class="p-3 text-center">Mahasiswa Terdaftar</th>
                        <th class="p-3 text-center">Mentor ASN</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse ($bidangs as $bidang)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30">
                            <td class="p-3 font-bold text-slate-800 dark:text-slate-200">
                                {{ $bidang->nama_bidang }}
                            </td>
                            <td class="p-3 text-slate-500 dark:text-slate-400 max-w-sm">
                                {{ $bidang->deskripsi ?? '-' }}
                            </td>
                            <td class="p-3 text-center font-bold text-blue-700 dark:text-blue-400">
                                {{ $bidang->kuota }} Orang
                            </td>
                            <td class="p-3 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $bidang->mahasiswas_count >= $bidang->kuota ? 'bg-rose-100 dark:bg-rose-900/40 text-rose-800 dark:text-rose-300' : 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300' }}">
                                    {{ $bidang->mahasiswas_count }} / {{ $bidang->kuota }}
                                </span>
                            </td>
                            <td class="p-3 text-center font-medium text-slate-700 dark:text-slate-300">
                                {{ $bidang->mentors_count }} Pembimbing
                            </td>
                            <td class="p-3 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" onclick='openEditModal(@json($bidang))' class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 font-bold text-slate-700 dark:text-slate-300">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.bidang.destroy', $bidang) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bidang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-900/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 font-bold text-rose-600 dark:text-rose-400">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Belum ada bidang yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Create / Edit Bidang -->
<div id="bidang-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs">
    <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-100 dark:border-slate-700">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700">
            <h3 id="modal-title" class="font-bold text-base text-slate-900 dark:text-white">Tambah Bidang Baru</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="bidang-form" action="{{ route('admin.bidang.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div id="method-field"></div>

            <div>
                <label for="nama_bidang" class="block font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Nama Bidang</label>
                <input type="text" id="nama_bidang" name="nama_bidang" required placeholder="Contoh: Aplikasi Informatika (APTIKA)" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-white dark:bg-slate-900 dark:text-slate-200">
            </div>

            <div>
                <label for="kuota" class="block font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Kuota Mahasiswa</label>
                <input type="number" min="1" max="100" id="kuota" name="kuota" required placeholder="Contoh: 10" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-white dark:bg-slate-900 dark:text-slate-200">
            </div>

            <div>
                <label for="deskripsi" class="block font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Deskripsi Tugas &amp; Fungsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Uraian ruang lingkup tugas bidang..." class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-3.5 py-2 text-xs focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-white dark:bg-slate-900 dark:text-slate-200"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-xl font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/20">Simpan Bidang</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAddModal() {
        document.getElementById('modal-title').innerText = 'Tambah Bidang Baru';
        document.getElementById('bidang-form').action = "{{ route('admin.bidang.store') }}";
        document.getElementById('method-field').innerHTML = '';
        document.getElementById('nama_bidang').value = '';
        document.getElementById('kuota').value = '';
        document.getElementById('deskripsi').value = '';
        document.getElementById('bidang-modal').classList.remove('hidden');
    }

    function openEditModal(bidang) {
        document.getElementById('modal-title').innerText = 'Edit Bidang: ' + bidang.nama_bidang;
        document.getElementById('bidang-form').action = "/admin/bidang/" + bidang.id;
        document.getElementById('method-field').innerHTML = '@method("PUT")';
        document.getElementById('nama_bidang').value = bidang.nama_bidang;
        document.getElementById('kuota').value = bidang.kuota;
        document.getElementById('deskripsi').value = bidang.deskripsi || '';
        document.getElementById('bidang-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('bidang-modal').classList.add('hidden');
    }
</script>
@endpush
@endsection

@extends('layouts.admin')

@section('page-title', 'Manajemen Pembimbing (Mentor)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 font-heading">Daftar Pembimbing Lapangan (Mentor ASN)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data pembimbing magang dan penugasan bidang di Diskominfo Jawa Timur</p>
        </div>
        <button type="button" onclick="openAddMentorModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/30 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Pembimbing Baru
        </button>
    </div>

    <!-- Table Card -->
    <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200/80">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-600 font-semibold uppercase tracking-wider">
                        <th class="p-3">Nama Pembimbing</th>
                        <th class="p-3">NIP Pegawai</th>
                        <th class="p-3">Bidang Penugasan</th>
                        <th class="p-3">Kontak</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($mentors as $mentor)
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-3">
                                <p class="font-bold text-slate-900">{{ $mentor->nama }}</p>
                                <p class="text-slate-400 font-mono text-[11px]">{{ $mentor->user->username ?? '-' }}</p>
                            </td>
                            <td class="p-3 font-mono text-slate-700">
                                {{ $mentor->nip ?? '-' }}
                            </td>
                            <td class="p-3">
                                <span class="font-semibold text-blue-700">{{ $mentor->bidang->nama_bidang ?? 'Belum Ditugaskan' }}</span>
                            </td>
                            <td class="p-3 text-slate-600">
                                {{ $mentor->no_telepon ?? '-' }}
                            </td>
                            <td class="p-3 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Assign Bidang Modal trigger -->
                                    <button type="button" onclick="openAssignModal('{{ $mentor->id }}', '{{ $mentor->nama }}', '{{ $mentor->bidang_id }}')" class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 font-bold text-blue-700">
                                        Atur Bidang
                                    </button>
                                    <form action="{{ route('admin.mentor.destroy', $mentor) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pembimbing ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 font-bold text-rose-600">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">Belum ada pembimbing (mentor) yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Create Mentor -->
<div id="mentor-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="font-bold text-base text-slate-900">Tambah Pembimbing Baru</h3>
            <button onclick="closeModal('mentor-modal')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('admin.mentor.store') }}" method="POST" class="space-y-3.5 text-xs">
            @csrf

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Username</label>
                    <input type="text" name="username" required placeholder="mentor_nama" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-blue-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-blue-600 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap & Gelar</label>
                <input type="text" name="nama" required placeholder="Contoh: Rahmat Hidayat, S.T., M.Kom." class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-blue-600 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">NIP Pegawai</label>
                    <input type="text" name="nip" placeholder="19870923..." class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-blue-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">No. WhatsApp</label>
                    <input type="text" name="no_telepon" placeholder="081234567..." class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-blue-600 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Bidang Penugasan</label>
                <select name="bidang_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-blue-600 focus:outline-none">
                    <option value="">Pilih Bidang Dinas...</option>
                    @foreach ($bidangs as $b)
                        <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="closeModal('mentor-modal')" class="px-4 py-2 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/20">Simpan Pembimbing</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Assign Bidang -->
<div id="assign-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl space-y-4 border border-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="font-bold text-base text-slate-900">Atur Penugasan Bidang</h3>
            <button onclick="closeModal('assign-modal')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="assign-form" method="POST" class="space-y-4 text-xs">
            @csrf
            <p id="assign-mentor-name" class="font-semibold text-slate-700"></p>

            <div>
                <label for="assign_bidang_id" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Pilih Bidang Baru</label>
                <select id="assign_bidang_id" name="bidang_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:border-blue-600 focus:outline-none">
                    @foreach ($bidangs as $b)
                        <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="closeModal('assign-modal')" class="px-4 py-2 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/20">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAddMentorModal() {
        document.getElementById('mentor-modal').classList.remove('hidden');
    }

    function openAssignModal(mentorId, mentorName, currentBidangId) {
        document.getElementById('assign-mentor-name').innerText = 'Pembimbing: ' + mentorName;
        document.getElementById('assign-form').action = '/admin/mentor/' + mentorId + '/assign-bidang';
        document.getElementById('assign_bidang_id').value = currentBidangId;
        document.getElementById('assign-modal').classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
@endpush
@endsection

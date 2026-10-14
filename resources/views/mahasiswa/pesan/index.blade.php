@extends('layouts.mahasiswa')

@section('title', 'Pesan & Laporan - SIMANG')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-xl font-extrabold text-slate-900 font-heading">Pusat Komunikasi & Layanan</h1>
        <p class="text-xs text-slate-500 mt-0.5">Kirim Permohonan Izin/Sakit ke Pembimbing atau Laporan Kendala Sistem ke Admin</p>
    </div>

    <!-- Message Form Card -->
    <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200/90 space-y-5">
        <form action="{{ route('mahasiswa.pesan.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Message Type Selector -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Jenis Pengajuan / Pesan</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="relative flex flex-col items-center justify-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-all text-center has-checked:border-blue-600 has-checked:bg-blue-50/50 has-checked:text-blue-900">
                        <input type="radio" name="tipe" value="izin_sakit" checked onchange="updateRecipient('izin_sakit')" class="sr-only">
                        <span class="text-lg mb-1">🏥</span>
                        <span class="text-xs font-bold">Izin / Sakit</span>
                        <span class="text-[10px] text-slate-500 mt-0.5">Ke Pembimbing</span>
                    </label>

                    <label class="relative flex flex-col items-center justify-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-all text-center has-checked:border-blue-600 has-checked:bg-blue-50/50 has-checked:text-blue-900">
                        <input type="radio" name="tipe" value="laporan_sistem" onchange="updateRecipient('laporan_sistem')" class="sr-only">
                        <span class="text-lg mb-1">🛠️</span>
                        <span class="text-xs font-bold">Kendala Sistem</span>
                        <span class="text-[10px] text-slate-500 mt-0.5">Ke Admin</span>
                    </label>

                    <label class="relative flex flex-col items-center justify-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-all text-center has-checked:border-blue-600 has-checked:bg-blue-50/50 has-checked:text-blue-900">
                        <input type="radio" name="tipe" value="direct" onchange="updateRecipient('direct')" class="sr-only">
                        <span class="text-lg mb-1">💬</span>
                        <span class="text-xs font-bold">Pesan Bebas</span>
                        <span class="text-[10px] text-slate-500 mt-0.5">Pilih Tujuan</span>
                    </label>
                </div>
            </div>

            <!-- Recipient Select -->
            <div>
                <label for="penerima_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Penerima Pesan</label>
                <select id="penerima_id" name="penerima_id" required class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                    @if ($mentorUser)
                        <option value="{{ $mentorUser->id }}" selected>Pembimbing: {{ $mentorUser->mentor->nama ?? $mentorUser->username }} ({{ $mahasiswa->bidang->nama_bidang ?? 'Bidang' }})</option>
                    @endif
                    @foreach ($admins as $admin)
                        <option value="{{ $admin->id }}">Admin: {{ $admin->admin->nama ?? $admin->username }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Message Body -->
            <div>
                <label for="isi_pesan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Isi Pesan / Keterangan</label>
                <textarea id="isi_pesan" name="isi_pesan" rows="4" required placeholder="Tuliskan alasan izin/sakit, rincian kendala teknis, atau pesan yang ingin disampaikan..." class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 leading-relaxed"></textarea>
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-md shadow-blue-700/20 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                Kirimkan Pesan
            </button>
        </form>
    </div>

    <!-- Sent Message History -->
    <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200/90 space-y-4">
        <h2 class="text-sm font-bold text-slate-900">Riwayat Pesan Terkirim</h2>

        <div class="space-y-3">
            @forelse ($pesanTerkirim as $pesan)
                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/60 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-800">Tujuan: {{ $pesan->penerima->username ?? 'User' }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $pesan->tipe === 'izin_sakit' ? 'bg-amber-100 text-amber-800' : ($pesan->tipe === 'laporan_sistem' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $pesan->tipe)) }}
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400">{{ \Carbon\Carbon::parse($pesan->waktu_kirim)->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $pesan->isi_pesan }}</p>
                </div>
            @empty
                <div class="p-6 text-center text-slate-400 text-xs">
                    Belum ada riwayat pesan yang dikirimkan.
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateRecipient(type) {
        const select = document.getElementById('penerima_id');
        if (!select) return;

        if (type === 'izin_sakit') {
            @if ($mentorUser)
                select.value = '{{ $mentorUser->id }}';
            @endif
        } else if (type === 'laporan_sistem') {
            @if ($admins->first())
                select.value = '{{ $admins->first()->id }}';
            @endif
        }
    }
</script>
@endpush
@endsection

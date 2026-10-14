@extends('layouts.mentor')

@section('page-title', 'Kotak Masuk & Pesan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 font-heading">Kotak Masuk & Komunikasi</h1>
            <p class="text-xs text-slate-500 mt-0.5">Baca laporan dan pesan dari mahasiswa bimbingan, atau kirim instruksi langsung</p>
        </div>
        @php
            $unreadCount = $pesanMasuk->where('is_read', false)->count();
        @endphp
        @if ($unreadCount > 0)
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse inline-block"></span>
                {{ $unreadCount }} Pesan Belum Dibaca
            </span>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        {{-- Left: Inbox --}}
        <div class="lg:col-span-3 space-y-4">
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200/80 space-y-4">
                <h2 class="text-sm font-bold text-slate-900">Pesan Masuk dari Mahasiswa</h2>

                <div class="space-y-3">
                    @forelse ($pesanMasuk as $pesan)
                        <div class="p-4 rounded-2xl border {{ $pesan->is_read ? 'border-slate-100 bg-slate-50/40' : 'border-blue-200/70 bg-blue-50/40' }} space-y-2 transition-colors">
                            <div class="flex items-center justify-between gap-2 text-xs">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 font-bold text-[10px] shrink-0">
                                        {{ substr($pesan->pengirim->mahasiswa->nama ?? $pesan->pengirim->username, 0, 2) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-800">
                                            {{ $pesan->pengirim->mahasiswa->nama ?? $pesan->pengirim->username }}
                                        </span>
                                        <span class="ml-1.5 px-1.5 py-0.5 rounded text-[10px] font-bold
                                            {{ $pesan->tipe === 'izin_sakit' ? 'bg-amber-100 text-amber-800' : ($pesan->tipe === 'laporan_sistem' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ $pesan->tipe === 'izin_sakit' ? 'Izin/Sakit' : ($pesan->tipe === 'laporan_sistem' ? 'Kendala Sistem' : 'Pesan Langsung') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    @if (!$pesan->is_read)
                                        <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                                    @endif
                                    <span class="text-[11px] text-slate-400">
                                        {{ \Carbon\Carbon::parse($pesan->waktu_kirim)->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed pl-9">{{ $pesan->isi_pesan }}</p>
                            @if (!$pesan->is_read)
                                <div class="pl-9">
                                    <form action="{{ route('mentor.pesan.mark-read', $pesan) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-[11px] font-bold text-blue-600 hover:text-blue-800">
                                            Tandai Sudah Dibaca →
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="py-10 text-center text-slate-400 text-xs">
                            <svg class="w-10 h-10 mx-auto mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Belum ada pesan masuk dari mahasiswa.
                        </div>
                    @endforelse
                </div>

                @if ($pesanMasuk->hasPages())
                    <div class="pt-4 border-t border-slate-100">
                        {{ $pesanMasuk->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Send Message --}}
        <div class="lg:col-span-2">
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200/80 space-y-4 sticky top-20">
                <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Kirim Pesan ke Mahasiswa
                </h2>

                <form action="{{ route('mentor.pesan.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf

                    <div>
                        <label for="penerima_id" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Tujuan Pesan</label>
                        <select id="penerima_id" name="penerima_id" required
                                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none">
                            <optgroup label="Mahasiswa Bimbingan">
                                @foreach ($mahasiswas as $m)
                                    <option value="{{ $m->user_id }}">{{ $m->nama }}</option>
                                @endforeach
                            </optgroup>
                            @if ($admins->count())
                                <optgroup label="Admin SIMANG">
                                    @foreach ($admins as $admin)
                                        <option value="{{ $admin->id }}">Admin: {{ $admin->admin->nama ?? $admin->username }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label for="tipe" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Jenis Pesan</label>
                        <select id="tipe" name="tipe" required
                                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none">
                            <option value="direct">Pesan / Instruksi Langsung</option>
                            <option value="izin_sakit">Konfirmasi Izin / Sakit</option>
                            <option value="laporan_sistem">Laporan Sistem ke Admin</option>
                        </select>
                    </div>

                    <div>
                        <label for="isi_pesan" class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Isi Pesan</label>
                        <textarea id="isi_pesan" name="isi_pesan" rows="4" required
                                  placeholder="Tulis instruksi, pengingat tugas, atau konfirmasi izin mahasiswa..."
                                  class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs focus:border-blue-600 focus:outline-none leading-relaxed"></textarea>
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 px-4 rounded-xl font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-md shadow-blue-700/20 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.mahasiswa')

@section('title', 'Survey Evaluasi Magang - SIMANG')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-xl font-extrabold text-slate-900 font-heading">Survey & Evaluasi Magang</h1>
        <p class="text-xs text-slate-500 mt-0.5">Umpan balik 360 derajat untuk peningkatan mutu program magang Diskominfo Jatim</p>
    </div>

    <!-- Anonymous Assurance Banner -->
    <div class="rounded-2xl bg-emerald-50 border border-emerald-200/80 p-4 flex items-center gap-3 text-emerald-900 text-xs font-medium">
        <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
        <div>
            <strong>Kerahasiaan Terjamin:</strong> Survey ini bersifat sepenuhnya <strong>anonim</strong>. Nama dan identitas Anda tidak akan dicatat atau dikaitkan dengan hasil respon yang dikirimkan.
        </div>
    </div>

    <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-sm border border-slate-200/90 space-y-6">
        <form action="{{ route('mahasiswa.survey.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- 1. Rating Fasilitas -->
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">1. Fasilitas dan Sarana Kerja di Kantor Diskominfo</label>
                <p class="text-[11px] text-slate-500">Kenyamanan ruangan, koneksi internet/WiFi, meja kerja, dan kelengkapan fasilitas.</p>
                <div class="flex items-center gap-3">
                    @for ($i = 1; $i <= 5; $i++)
                        <label class="flex-1 text-center cursor-pointer">
                            <input type="radio" name="rating_fasilitas" value="{{ $i }}" required class="sr-only peer">
                            <div class="py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 peer-checked:bg-blue-700 peer-checked:text-white peer-checked:border-blue-700 transition-all">
                                {{ $i }} ⭐
                            </div>
                        </label>
                    @endfor
                </div>
            </div>

            <!-- 2. Rating Pembimbing -->
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">2. Bimbingan & Mentoring oleh Pembimbing Lapangan</label>
                <p class="text-[11px] text-slate-500">Ketersediaan waktu mentor, kejelasan arahan tugas, serta transfer ilmu dan bimbingan.</p>
                <div class="flex items-center gap-3">
                    @for ($i = 1; $i <= 5; $i++)
                        <label class="flex-1 text-center cursor-pointer">
                            <input type="radio" name="rating_pembimbing" value="{{ $i }}" required class="sr-only peer">
                            <div class="py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 peer-checked:bg-blue-700 peer-checked:text-white peer-checked:border-blue-700 transition-all">
                                {{ $i }} ⭐
                            </div>
                        </label>
                    @endfor
                </div>
            </div>

            <!-- 3. Rating Pekerjaan -->
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">3. Relevansi dan Beban Tugas yang Diberikan</label>
                <p class="text-[11px] text-slate-500">Kesesuaian tugas dengan bidang keilmuan kuliah dan keterlibatan pada proyek nyata.</p>
                <div class="flex items-center gap-3">
                    @for ($i = 1; $i <= 5; $i++)
                        <label class="flex-1 text-center cursor-pointer">
                            <input type="radio" name="rating_pekerjaan" value="{{ $i }}" required class="sr-only peer">
                            <div class="py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 peer-checked:bg-blue-700 peer-checked:text-white peer-checked:border-blue-700 transition-all">
                                {{ $i }} ⭐
                            </div>
                        </label>
                    @endfor
                </div>
            </div>

            <!-- 4. Rating Lingkungan -->
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">4. Lingkungan dan Budaya Kerja Organisasi</label>
                <p class="text-[11px] text-slate-500">Kenyamanan komunikasi dengan staf ASN, inklusivitas, dan suasana profesional dinas.</p>
                <div class="flex items-center gap-3">
                    @for ($i = 1; $i <= 5; $i++)
                        <label class="flex-1 text-center cursor-pointer">
                            <input type="radio" name="rating_lingkungan" value="{{ $i }}" required class="sr-only peer">
                            <div class="py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 peer-checked:bg-blue-700 peer-checked:text-white peer-checked:border-blue-700 transition-all">
                                {{ $i }} ⭐
                            </div>
                        </label>
                    @endfor
                </div>
            </div>

            <!-- Saran & Masukan -->
            <div>
                <label for="saran" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Saran Konstruktif untuk Diskominfo Jatim</label>
                <textarea id="saran" name="saran" rows="3" placeholder="Saran terkait alur kerja, fasilitas pendukung, atau program pembekalan..." class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20"></textarea>
            </div>

            <!-- Kesan & Pengalaman -->
            <div>
                <label for="kesan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Kesan dan Pengalaman Selama Magang</label>
                <textarea id="kesan" name="kesan" rows="3" placeholder="Bagikan hal positif, pembelajaran paling berkesan, atau pengalaman terbaik Anda..." class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20"></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-md shadow-blue-700/20 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Kirimkan Evaluasi Anonim
            </button>
        </form>
    </div>
</div>
@endsection

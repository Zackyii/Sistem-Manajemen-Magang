@extends('layouts.app')

@section('title', 'Panduan & Tata Tertib Magang - SIMANG')

@section('body')
<div class="min-h-screen bg-slate-100/70 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Brand Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-800 text-white font-extrabold text-2xl shadow-lg shadow-blue-800/20">
                S
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 font-heading">Tata Tertib & Pakta Integritas Magang</h1>
            <p class="text-xs text-slate-500 font-medium">Dinas Komunikasi dan Informatika Provinsi Jawa Timur</p>
        </div>

        <x-alert />

        <!-- Rules Document Card -->
        <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-sm border border-slate-200 text-slate-700 space-y-6 text-sm leading-relaxed">
            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-100 flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="text-xs text-blue-900">
                    <strong>Pemberitahuan Wajib:</strong> Seluruh peserta magang di lingkungan Diskominfo Provinsi Jawa Timur wajib membaca dan menyetujui pakta integritas serta tata tertib kedinasan berikut sebelum dapat mengakses seluruh fitur SIMANG.
                </div>
            </div>

            <!-- Rule Items -->
            <div class="space-y-4">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-extrabold">1</span>
                        Jam Kerja dan Presensi Kedinasan
                    </h3>
                    <p class="text-xs text-slate-600 mt-2 pl-8">
                        Hari kerja adalah Senin s.d. Jumat pukul 07.30 - 16.00 WIB. Presensi masuk wajib dilakukan menggunakan fitur kamera selfie langsung dan deteksi lokasi GPS SIMANG sebelum pukul 08.00 WIB. Presensi pulang dilakukan minimal pukul 16.00 WIB.
                    </p>
                </div>

                <div class="border-b border-slate-100 pb-4">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-extrabold">2</span>
                        Etika Berpakaian dan Kerapian
                    </h3>
                    <p class="text-xs text-slate-600 mt-2 pl-8">
                        Mengenakan kemeja rapi berkerah (putih/batik sesuai instruksi dinas), celana/rok bahan warna gelap (dilarang mengenakan jeans, kaos oblong, atau sandal), serta wajib mengenakan tanda pengenal/ID Card resmi selama berada di kantor Diskominfo.
                    </p>
                </div>

                <div class="border-b border-slate-100 pb-4">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-extrabold">3</span>
                        Kewajiban Pengisian Logbook Harian
                    </h3>
                    <p class="text-xs text-slate-600 mt-2 pl-8">
                        Mahasiswa wajib mencatat uraian tugas dan progres pekerjaan setiap hari pada fitur Logbook. Catatan ini menjadi salah satu komponen penilaian utama yang diverifikasi secara berkala oleh Pembimbing (Mentor) masing-masing bidang.
                    </p>
                </div>

                <div class="border-b border-slate-100 pb-4">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-extrabold">4</span>
                        Kerahasiaan Informasi dan Keamanan Data Pemprov
                    </h3>
                    <p class="text-xs text-slate-600 mt-2 pl-8">
                        Dilarang keras menyebarluaskan dokumen internal, data kependudukan, credential sistem, source code aplikasi pemerintah, atau informasi rahasia dinas kepada pihak luar tanpa izin tertulis dari Kepala Dinas/Pembimbing.
                    </p>
                </div>

                <div>
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-extrabold">5</span>
                        Izin dan Ketidakhadiran
                    </h3>
                    <p class="text-xs text-slate-600 mt-2 pl-8">
                        Apabila berhalangan hadir karena sakit atau urusan akademik mendesak, mahasiswa wajib mengirimkan pemberitahuan resmi dan surat keterangan dokter/kampus melalui menu Pesan & Permohonan Izin pada aplikasi SIMANG.
                    </p>
                </div>
            </div>

            <!-- Agreement Form -->
            <form action="{{ route('mahasiswa.panduan.agree') }}" method="POST" class="pt-6 border-t border-slate-200 space-y-5">
                @csrf

                <label class="flex items-start gap-3 p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 cursor-pointer hover:bg-amber-50 transition-colors">
                    <input type="checkbox" id="check-agree" onchange="toggleAgreeButton(this)" class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-xs font-semibold text-slate-800 leading-relaxed">
                        Saya telah membaca, memahami, dan berjanji akan mematuhi seluruh tata tertib kedinasan serta pakta integritas magang di Dinas Komunikasi dan Informatika Provinsi Jawa Timur.
                    </span>
                </label>

                <div class="flex items-center justify-between gap-4">
                    @if (auth()->user()->has_agreed_to_rules)
                        <a href="{{ route('mahasiswa.dashboard') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                            &larr; Kembali ke Dashboard
                        </a>
                    @else
                        <div></div>
                    @endif

                    <button type="submit" id="btn-agree" {{ auth()->user()->has_agreed_to_rules ? '' : 'disabled' }} class="px-6 py-3 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 disabled:opacity-50 disabled:cursor-not-allowed shadow-md shadow-blue-700/20 transition-all flex items-center gap-2">
                        <span>Setujui & Lanjutkan ke Dashboard</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleAgreeButton(checkbox) {
        const btn = document.getElementById('btn-agree');
        if (btn) {
            btn.disabled = !checkbox.checked;
        }
    }
</script>
@endpush
@endsection

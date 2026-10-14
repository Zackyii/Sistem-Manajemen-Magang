@extends('layouts.mahasiswa')

@section('title', 'Kalender Magang - SIMANG')

@section('content')
@php
    $currentDate = \Carbon\Carbon::createFromDate($tahun, $bulan, 1);
    $prevMonth = $currentDate->copy()->subMonth();
    $nextMonth = $currentDate->copy()->addMonth();
    $daysInMonth = $currentDate->daysInMonth;
    $firstDayOfWeek = $currentDate->dayOfWeekIso; // 1 = Monday, 7 = Sunday
@endphp

<div class="space-y-6">
    <!-- Header with Month Switcher -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 font-heading">Kalender Aktivitas</h1>
            <p class="text-xs text-slate-500 mt-0.5">Pantau Jadwal Tugas, Kehadiran, dan Event Magang</p>
        </div>
        <div class="flex items-center gap-1 bg-white p-1 rounded-2xl shadow-xs border border-slate-200">
            <a href="{{ route('mahasiswa.tanggalan.index', ['bulan' => $prevMonth->month, 'tahun' => $prevMonth->year]) }}" class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <span class="px-3 py-1 text-xs font-bold text-slate-800">
                {{ $currentDate->translatedFormat('F Y') }}
            </span>
            <a href="{{ route('mahasiswa.tanggalan.index', ['bulan' => $nextMonth->month, 'tahun' => $nextMonth->year]) }}" class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>

    <!-- Calendar Legend -->
    <div class="flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-600 bg-white p-3 rounded-2xl border border-slate-200/80">
        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Hadir</span>
        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Terlambat</span>
        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> Ada Tugas</span>
        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span> Logbook Terisi</span>
    </div>

    <!-- Calendar Grid Card -->
    <div class="rounded-3xl bg-white p-5 shadow-sm border border-slate-200/90 overflow-hidden">
        <!-- Day Names -->
        <div class="grid grid-cols-7 gap-1 text-center font-bold text-xs text-slate-400 mb-2 uppercase tracking-wider">
            <div class="py-2">Sen</div>
            <div class="py-2">Sel</div>
            <div class="py-2">Rab</div>
            <div class="py-2">Kam</div>
            <div class="py-2">Jum</div>
            <div class="py-2 text-rose-500">Sab</div>
            <div class="py-2 text-rose-500">Min</div>
        </div>

        <!-- Days Cells -->
        <div class="grid grid-cols-7 gap-1 sm:gap-2">
            <!-- Empty offset days -->
            @for ($i = 1; $i < $firstDayOfWeek; $i++)
                <div class="h-20 sm:h-24 rounded-xl bg-slate-50/40 opacity-40"></div>
            @endfor

            <!-- Month Days -->
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $loopDate = \Carbon\Carbon::createFromDate($tahun, $bulan, $day);
                    $dateStr = $loopDate->format('Y-m-d');
                    $item = $tanggalan->get($dateStr);
                    $isToday = $loopDate->isToday();
                    $isWeekend = $loopDate->isWeekend();
                @endphp

                <div onclick="openDateDetail('{{ $dateStr }}', '{{ $loopDate->translatedFormat('l, d F Y') }}')" class="h-20 sm:h-24 p-2 rounded-xl border border-slate-100 hover:border-blue-400 hover:shadow-md transition-all cursor-pointer flex flex-col justify-between {{ $isToday ? 'bg-blue-50/60 border-blue-300 ring-2 ring-blue-600/20' : ($isWeekend ? 'bg-slate-50/80 text-slate-400' : 'bg-white') }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold {{ $isToday ? 'text-blue-700 font-extrabold' : ($isWeekend ? 'text-rose-500' : 'text-slate-800') }}">
                            {{ $day }}
                        </span>
                        @if ($isToday)
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                        @endif
                    </div>

                    <!-- Indicators -->
                    <div class="space-y-1">
                        @if ($item && $item->info_tugas)
                            <div class="truncate text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-100 text-blue-800">
                                📌 {{ $item->info_tugas }}
                            </div>
                        @endif

                        @if ($item && $item->aktivitas_utama)
                            <div class="truncate text-[9px] font-medium px-1 rounded bg-purple-50 text-purple-700">
                                📝 Logbook
                            </div>
                        @endif
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>

<!-- Modal Detail Tanggal -->
<div id="date-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 id="modal-date-title" class="font-bold text-base text-slate-900">Detail Aktivitas</h3>
                <p id="modal-date-subtitle" class="text-xs text-slate-500"></p>
            </div>
            <button onclick="closeDateModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div id="modal-loading" class="py-8 text-center text-xs text-slate-500">
            <svg class="w-6 h-6 animate-spin mx-auto text-blue-600 mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
            Memuat data...
        </div>

        <div id="modal-content" class="hidden space-y-3 text-xs">
            <!-- Dynamic Content Injected by JS -->
        </div>

        <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
            <button type="button" onclick="closeDateModal()" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200">
                Tutup
            </button>
            <a id="btn-add-logbook" href="#" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800">
                Tulis Logbook
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    async function openDateDetail(dateStr, formattedDate) {
        const modal = document.getElementById('date-modal');
        const title = document.getElementById('modal-date-title');
        const subtitle = document.getElementById('modal-date-subtitle');
        const loading = document.getElementById('modal-loading');
        const content = document.getElementById('modal-content');
        const btnLogbook = document.getElementById('btn-add-logbook');

        modal.classList.remove('hidden');
        title.innerText = formattedDate;
        subtitle.innerText = dateStr;
        loading.classList.remove('hidden');
        content.classList.add('hidden');
        btnLogbook.href = `{{ route('mahasiswa.logbook.create') }}?tanggal=${dateStr}`;

        try {
            const res = await fetch(`{{ route('mahasiswa.tanggalan.show') }}?tanggal=${dateStr}`);
            const data = await res.json();
            loading.classList.add('hidden');
            content.classList.remove('hidden');

            if (!data) {
                content.innerHTML = `
                    <div class="p-4 rounded-xl bg-slate-50 text-center text-slate-500">
                        Belum ada aktivitas atau tugas yang tercatat pada tanggal ini.
                    </div>
                `;
                return;
            }

            let html = '';
            if (data.info_tugas) {
                html += `
                    <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-100">
                        <span class="font-bold text-blue-900 block">📌 Tugas: ${data.info_tugas}</span>
                        <span class="text-blue-700 text-[11px]">Tenggat: ${data.deadline_tugas || 'Fleksibel'}</span>
                    </div>
                `;
            }

            if (data.aktivitas_utama) {
                html += `
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                        <span class="font-bold text-slate-900 block">📝 Logbook: ${data.aktivitas_utama}</span>
                        <p class="text-slate-600 mt-1 leading-relaxed">${data.deskripsi_kegiatan || ''}</p>
                        <div class="mt-2 flex items-center justify-between text-[11px] text-slate-500">
                            <span>Durasi: <strong>${data.durasi_jam} Jam</strong></span>
                            <span class="font-bold text-emerald-600">Status: ${data.status_logbook}</span>
                        </div>
                    </div>
                `;
            }

            content.innerHTML = html;
        } catch (err) {
            loading.classList.add('hidden');
            content.classList.remove('hidden');
            content.innerHTML = `<p class="text-rose-500">Gagal memuat detail data.</p>`;
        }
    }

    function closeDateModal() {
        document.getElementById('date-modal').classList.add('hidden');
    }
</script>
@endpush
@endsection

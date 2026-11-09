@extends('layouts.app')

@section('body')
<div class="min-h-screen bg-slate-100/70 dark:bg-slate-950 md:flex">

    {{-- ═══════════════════════════════════════════════════════
         DESKTOP FIXED SIDEBAR (md and above)
    ═══════════════════════════════════════════════════════ --}}
    <aside class="hidden md:flex md:flex-col md:fixed md:inset-y-0 md:left-0 md:w-64 md:z-40
                  bg-slate-900 text-slate-300 border-r border-slate-800">
        {{-- Brand Header --}}
        <div class="p-6 border-b border-slate-800 flex items-center gap-3 shrink-0">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-700 to-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-lg shadow-blue-500/20">S</div>
            <div>
                <h1 class="text-lg font-bold text-white tracking-tight">SIMANG</h1>
                <p class="text-[11px] font-semibold text-amber-400 uppercase tracking-wider">Portal Pembimbing</p>
            </div>
        </div>

        {{-- Navigation Links --}}
        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
            <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Utama</p>

            <a href="{{ route('mentor.dashboard') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('mentor.dashboard') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>

            <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-6 mb-2">Bimbingan Magang</p>

            <a href="{{ route('mentor.presensi.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('mentor.presensi.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Presensi Mahasiswa
            </a>

            <a href="{{ route('mentor.logbook.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('mentor.logbook.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Review Logbook
            </a>

            <a href="{{ route('mentor.tugas.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('mentor.tugas.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Penugasan (Tugas)
            </a>

            <a href="{{ route('mentor.penilaian.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('mentor.penilaian.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Penilaian Akhir
            </a>
        </nav>

        {{-- Mentor Profile & Logout Footer --}}
        <div class="p-4 border-t border-slate-800 bg-slate-950/40 shrink-0">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center font-bold text-amber-400 text-sm border border-slate-700 shrink-0">
                    {{ substr(auth()->user()->mentor->nama ?? 'MT', 0, 2) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->mentor->nama ?? auth()->user()->username }}</p>
                    <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->mentor->bidang->nama_bidang ?? 'Pembimbing' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold
                               text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 transition-colors border border-rose-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar Akun
                </button>
            </form>
        </div>
    </aside>

    {{-- ═══════════════════════════════════════════════════════
         MAIN CONTENT AREA (offset by sidebar on md+)
    ═══════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 md:pl-64">

        {{-- Topbar --}}
        <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800
                       px-4 md:px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-4">
                {{-- Mobile hamburger (hidden on md+) --}}
                <button type="button" onclick="toggleMobileSidebar()"
                        class="md:hidden p-2 -ml-2 rounded-xl text-slate-600 dark:text-slate-300
                               hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h2 class="text-base font-bold text-slate-800 dark:text-white">@yield('page-title', 'Dashboard Pembimbing')</h2>
            </div>
            <div class="flex items-center gap-3 text-xs text-slate-500">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-slate-700 text-blue-800 dark:text-blue-300 font-semibold border border-blue-100 dark:border-slate-600 text-xs">
                    {{ auth()->user()->mentor->bidang->nama_bidang ?? 'Pembimbing Magang' }}
                </div>
                {{-- Theme Toggle --}}
                <button data-theme-toggle onclick="toggleTheme()" title="Ganti Tema"
                        class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <svg class="icon-sun w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14A7 7 0 0012 5z"/></svg>
                    <svg class="icon-moon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
            </div>
        </header>

        <main class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto">
            <x-alert />
            @yield('content')
        </main>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         MOBILE SLIDE-OVER DRAWER (md: hidden)
    ═══════════════════════════════════════════════════════ --}}
    <div id="mentor-mobile-drawer"
         class="md:hidden fixed inset-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out pointer-events-none">
        <div id="mentor-mobile-backdrop"
             onclick="toggleMobileSidebar()"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs opacity-0 transition-opacity duration-300"></div>

        <div class="relative w-72 max-w-[80vw] h-full bg-slate-900 shadow-2xl flex flex-col pointer-events-auto">
            {{-- Header --}}
            <div class="p-5 border-b border-slate-800 flex items-center justify-between bg-blue-900">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center font-bold text-amber-300 text-lg border border-white/20">S</div>
                    <div>
                        <h2 class="font-bold text-base text-white">SIMANG</h2>
                        <p class="text-xs text-blue-200">Portal Pembimbing</p>
                    </div>
                </div>
                <button onclick="toggleMobileSidebar()" class="text-white/80 hover:text-white p-1 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- User Info --}}
            <div class="px-4 py-3 border-b border-slate-800 bg-slate-950/40">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Pembimbing Magang</p>
                <p class="font-bold text-sm text-white truncate mt-0.5">{{ auth()->user()->mentor->nama ?? auth()->user()->username }}</p>
                <p class="text-xs text-slate-400 truncate">{{ auth()->user()->mentor->bidang->nama_bidang ?? 'Pembimbing' }}</p>
            </div>

            <nav class="flex-1 overflow-y-auto p-4 space-y-1.5">
                <a href="{{ route('mentor.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('mentor.dashboard') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('mentor.presensi.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('mentor.presensi.*') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Presensi Mahasiswa
                </a>
                <a href="{{ route('mentor.logbook.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('mentor.logbook.*') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Review Logbook
                </a>
                <a href="{{ route('mentor.tugas.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('mentor.tugas.*') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Penugasan (Tugas)
                </a>
                <a href="{{ route('mentor.penilaian.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('mentor.penilaian.*') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Penilaian Akhir
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold
                                   text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 transition-colors border border-rose-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar Akun
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    function toggleMobileSidebar() {
        const drawer = document.getElementById('mentor-mobile-drawer');
        const backdrop = document.getElementById('mentor-mobile-backdrop');
        const isClosed = drawer.classList.contains('-translate-x-full');

        if (isClosed) {
            drawer.classList.remove('-translate-x-full');
            drawer.classList.remove('pointer-events-none');
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
        } else {
            drawer.classList.add('-translate-x-full');
            setTimeout(() => drawer.classList.add('pointer-events-none'), 300);
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
        }
    }
</script>
@endsection

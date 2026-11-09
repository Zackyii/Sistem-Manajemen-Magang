@extends('layouts.app')

@section('body')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 md:flex">

    {{-- ═══════════════════════════════════════════════════════
         DESKTOP FIXED SIDEBAR (md and above)
    ═══════════════════════════════════════════════════════ --}}
    <aside class="hidden md:flex md:flex-col md:fixed md:inset-y-0 md:left-0 md:w-64 md:z-40
                  bg-slate-900 dark:bg-slate-900 text-slate-300 border-r border-slate-800">

        {{-- Brand Header --}}
        <div class="p-6 border-b border-slate-800 flex items-center gap-3 shrink-0">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-700 to-sky-500 flex items-center justify-center text-white font-extrabold text-lg shadow-lg shadow-blue-500/20">
                S
            </div>
            <div>
                <h1 class="text-lg font-bold text-white tracking-tight">SIMANG</h1>
                <p class="text-[11px] font-semibold text-amber-400 uppercase tracking-wider">Portal Mahasiswa</p>
            </div>
        </div>

        {{-- User Info --}}
        <div class="px-4 py-3 border-b border-slate-800 bg-slate-950/40 shrink-0">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Mahasiswa Magang</p>
            <p class="font-bold text-sm text-white truncate mt-0.5">{{ auth()->user()->mahasiswa->nama ?? auth()->user()->username }}</p>
            <p class="text-xs text-slate-400 truncate">{{ auth()->user()->mahasiswa->bidang->nama_bidang ?? 'Belum ditentukan' }}</p>
        </div>

        {{-- Navigation Links --}}
        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
            <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Utama</p>

            <a href="{{ route('mahasiswa.dashboard') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>

            <a href="{{ route('mahasiswa.presensi.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('mahasiswa.presensi.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Presensi &amp; Selfie GPS
            </a>

            <a href="{{ route('mahasiswa.logbook.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('mahasiswa.logbook.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Logbook Harian
            </a>

            <a href="{{ route('mahasiswa.tanggalan.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('mahasiswa.tanggalan.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Kalender Magang
            </a>

            {{-- FITUR CHAT DINONAKTIFKAN SEMENTARA (Comment-Out Only)
            <a href="{{ route('mahasiswa.pesan.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('mahasiswa.pesan.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                Pesan &amp; Laporan
            </a>
            --}}

            <div class="pt-4 mt-4 border-t border-slate-800">
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Informasi &amp; Bantuan</p>

                <a href="{{ route('mahasiswa.panduan.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('mahasiswa.panduan.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Panduan &amp; Tata Tertib
                </a>

                <a href="{{ route('mahasiswa.survey.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('mahasiswa.survey.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    Survey Evaluasi Magang
                </a>
            </div>
        </nav>

        {{-- Logout Footer --}}
        <div class="p-4 border-t border-slate-800 shrink-0">
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

        {{-- ── MOBILE / TABLET TOPBAR (hidden on md+) ── --}}
        <header class="sticky top-0 z-30 md:hidden
                       bg-white/95 dark:bg-slate-900/95 backdrop-blur
                       border-b border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    {{-- Mobile hamburger --}}
                    <button type="button" onclick="toggleSidebar()"
                            class="p-2 -ml-2 rounded-xl text-slate-600 dark:text-slate-300
                                   hover:bg-slate-100 dark:hover:bg-slate-800
                                   focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-700 flex items-center justify-center text-white font-bold text-sm shadow-sm">S</div>
                        <div>
                            <h1 class="text-base font-extrabold tracking-tight text-blue-900 dark:text-white leading-none">SIMANG</h1>
                            <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Diskominfo Jatim</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Theme Toggle --}}
                    <button data-theme-toggle onclick="toggleTheme()" title="Ganti Tema"
                            class="p-2 rounded-xl text-slate-500 dark:text-slate-400
                                   hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <svg class="icon-sun w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14A7 7 0 0012 5z"/></svg>
                        <svg class="icon-moon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    </button>
                    {{-- Avatar --}}
                    <div class="flex items-center gap-2 pl-2 border-l border-slate-200 dark:border-slate-700">
                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 border border-blue-200 dark:border-blue-700
                                    flex items-center justify-center text-blue-800 dark:text-blue-300 font-bold text-xs">
                            {{ substr(auth()->user()->mahasiswa->nama ?? auth()->user()->username, 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- ── DESKTOP TOPBAR (md+): slim bar for theme toggle & user info ── --}}
        <header class="hidden md:flex h-14 shrink-0 sticky top-0 z-30
                       bg-white/95 dark:bg-slate-900/95 backdrop-blur
                       border-b border-slate-200/80 dark:border-slate-800
                       px-6 items-center justify-between shadow-xs">
            <h2 class="text-sm font-bold text-slate-700 dark:text-slate-200">@yield('page-title', 'Portal Mahasiswa')</h2>
            <div class="flex items-center gap-3">
                {{-- Theme Toggle --}}
                <button data-theme-toggle onclick="toggleTheme()" title="Ganti Tema"
                        class="p-2 rounded-lg text-slate-500 dark:text-slate-400
                               hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <svg class="icon-sun w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14A7 7 0 0012 5z"/></svg>
                    <svg class="icon-moon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
                {{-- Avatar --}}
                <div class="flex items-center gap-2 pl-2 border-l border-slate-200 dark:border-slate-700">
                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 border border-blue-200 dark:border-blue-700
                                flex items-center justify-center text-blue-800 dark:text-blue-300 font-bold text-xs">
                        {{ substr(auth()->user()->mahasiswa->nama ?? auth()->user()->username, 0, 2) }}
                    </div>
                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 hidden lg:inline truncate max-w-[140px]">
                        {{ auth()->user()->mahasiswa->nama ?? auth()->user()->username }}
                    </span>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="flex-1 px-4 sm:px-6 py-5 pb-24 md:pb-8">
            <x-alert />
            @yield('content')
        </main>

        {{-- ── BOTTOM NAVIGATION BAR (mobile only) ── --}}
        <nav class="fixed bottom-0 inset-x-0 z-30 md:hidden
                    bg-white/95 dark:bg-slate-900/95 backdrop-blur
                    border-t border-slate-200/90 dark:border-slate-800
                    py-1 px-3 shadow-lg">
            <div class="flex items-center justify-around">
                <a href="{{ route('mahasiswa.dashboard') }}"
                   class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg text-xs font-medium
                          {{ request()->routeIs('mahasiswa.dashboard') ? 'text-blue-700 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Home</span>
                </a>

                <a href="{{ route('mahasiswa.presensi.index') }}"
                   class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg text-xs font-medium
                          {{ request()->routeIs('mahasiswa.presensi.*') ? 'text-blue-700 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">
                    <div class="-mt-4 w-10 h-10 rounded-full bg-blue-700 text-white flex items-center justify-center shadow-md shadow-blue-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                    </div>
                    <span>Presensi</span>
                </a>

                <a href="{{ route('mahasiswa.logbook.index') }}"
                   class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg text-xs font-medium
                          {{ request()->routeIs('mahasiswa.logbook.*') ? 'text-blue-700 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Logbook</span>
                </a>

                <a href="{{ route('mahasiswa.tanggalan.index') }}"
                   class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg text-xs font-medium
                          {{ request()->routeIs('mahasiswa.tanggalan.*') ? 'text-blue-700 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Kalender</span>
                </a>

                {{-- FITUR CHAT DINONAKTIFKAN SEMENTARA (Comment-Out Only)
                <a href="{{ route('mahasiswa.pesan.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg text-xs font-medium {{ request()->routeIs('mahasiswa.pesan.*') ? 'text-blue-700 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    <span>Pesan</span>
                </a>
                --}}
            </div>
        </nav>
    </div>{{-- /main content --}}

    {{-- ═══════════════════════════════════════════════════════
         MOBILE SLIDE-OVER DRAWER (hidden on md+)
    ═══════════════════════════════════════════════════════ --}}
    <div id="sidebar-drawer"
         class="md:hidden fixed inset-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out pointer-events-none">
        {{-- Backdrop --}}
        <div id="sidebar-backdrop"
             onclick="toggleSidebar()"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs opacity-0 transition-opacity duration-300">
        </div>

        {{-- Drawer Panel --}}
        <div class="relative w-72 max-w-[80vw] h-full bg-slate-900 shadow-2xl flex flex-col pointer-events-auto">
            {{-- Header --}}
            <div class="p-5 border-b border-slate-800 flex items-center justify-between bg-blue-900">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center font-bold text-amber-300 text-lg border border-white/20">S</div>
                    <div>
                        <h2 class="font-bold text-base text-white">SIMANG</h2>
                        <p class="text-xs text-blue-200">Portal Mahasiswa</p>
                    </div>
                </div>
                <button onclick="toggleSidebar()" class="text-white/80 hover:text-white p-1 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- User Info --}}
            <div class="px-4 py-3 border-b border-slate-800 bg-slate-950/40">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Mahasiswa Magang</p>
                <p class="font-bold text-sm text-white truncate mt-0.5">{{ auth()->user()->mahasiswa->nama ?? auth()->user()->username }}</p>
                <p class="text-xs text-slate-400 truncate">{{ auth()->user()->mahasiswa->bidang->nama_bidang ?? 'Belum ditentukan' }}</p>
            </div>

            {{-- Mobile Nav Links --}}
            <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                <a href="{{ route('mahasiswa.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('mahasiswa.presensi.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('mahasiswa.presensi.*') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Presensi &amp; Selfie GPS
                </a>
                <a href="{{ route('mahasiswa.logbook.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('mahasiswa.logbook.*') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Logbook Harian
                </a>
                <a href="{{ route('mahasiswa.tanggalan.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('mahasiswa.tanggalan.*') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Kalender Magang
                </a>

                <div class="pt-4 mt-4 border-t border-slate-800">
                    <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Informasi &amp; Bantuan</p>
                    <a href="{{ route('mahasiswa.panduan.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                              {{ request()->routeIs('mahasiswa.panduan.*') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Panduan &amp; Tata Tertib
                    </a>
                    <a href="{{ route('mahasiswa.survey.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                              {{ request()->routeIs('mahasiswa.survey.*') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        Survey Evaluasi Magang
                    </a>
                </div>
            </nav>

            {{-- Mobile Logout --}}
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

</div>{{-- /min-h-screen --}}

<script>
    function toggleSidebar() {
        const drawer = document.getElementById('sidebar-drawer');
        const backdrop = document.getElementById('sidebar-backdrop');
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

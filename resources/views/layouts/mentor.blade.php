@extends('layouts.app')

@section('body')
<div class="min-h-screen flex bg-slate-100/70">
    <!-- Sidebar Desktop -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 hidden md:flex min-h-screen border-r border-slate-800">
        <!-- Brand Header -->
        <div class="p-6 border-b border-slate-800 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-700 to-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-lg shadow-blue-500/20">
                S
            </div>
            <div>
                <h1 class="text-lg font-bold text-white tracking-tight">SIMANG</h1>
                <p class="text-[11px] font-semibold text-amber-400 uppercase tracking-wider">Portal Pembimbing</p>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
            <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Utama</p>
            
            <a href="{{ route('mentor.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('mentor.dashboard') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>

            <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-6 mb-2">Bimbingan Magang</p>

            <a href="{{ route('mentor.presensi.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('mentor.presensi.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Verifikasi Presensi
                </div>
            </a>

            <a href="{{ route('mentor.logbook.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('mentor.logbook.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Review Logbook
                </div>
            </a>

            <a href="{{ route('mentor.tugas.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('mentor.tugas.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Penugasan (Tugas)
            </a>

            <a href="{{ route('mentor.penilaian.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('mentor.penilaian.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Penilaian Akhir
            </a>
        </nav>

        <!-- Mentor Profile footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center font-bold text-amber-400 text-sm border border-slate-700">
                        {{ substr(auth()->user()->mentor->nama ?? 'MT', 0, 2) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->mentor->nama ?? auth()->user()->username }}</p>
                        <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->mentor->bidang->nama_bidang ?? 'Pembimbing' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar -->
        <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-20 shadow-2xs">
            <div class="flex items-center gap-4">
                <h2 class="text-base font-bold text-slate-800">@yield('page-title', 'Dashboard Pembimbing')</h2>
            </div>
            <div class="flex items-center gap-4 text-xs text-slate-500">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-800 font-semibold border border-blue-100">
                    <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                    {{ auth()->user()->mentor->bidang->nama_bidang ?? 'Pembimbing Magang' }}
                </div>
            </div>
        </header>

        <main class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto">
            <x-alert />
            @yield('content')
        </main>
    </div>
</div>
@endsection

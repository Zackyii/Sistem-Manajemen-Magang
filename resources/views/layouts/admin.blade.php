@extends('layouts.app')

@section('body')
<div class="min-h-screen flex bg-slate-100/70">
    <!-- Sidebar Desktop -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 hidden md:flex min-h-screen border-r border-slate-800">
        <!-- Brand Header -->
        <div class="p-6 border-b border-slate-800 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-700 to-sky-500 flex items-center justify-center text-white font-extrabold text-lg shadow-lg shadow-blue-500/20">
                S
            </div>
            <div>
                <h1 class="text-lg font-bold text-white tracking-tight">SIMANG</h1>
                <p class="text-[11px] font-semibold text-sky-400 uppercase tracking-wider">Admin Panel Diskominfo</p>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
            <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Utama</p>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>

            <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-6 mb-2">Manajemen</p>

            <a href="{{ route('admin.bidang.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.bidang.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Bidang & Kuota
            </a>

            <a href="{{ route('admin.mahasiswa.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.mahasiswa.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Mahasiswa
                </div>
                @php
                    $pendingMhsCount = \App\Models\Mahasiswa::where('status_verifikasi', 'pending')->count();
                @endphp
                @if ($pendingMhsCount > 0)
                    <span class="px-2 py-0.5 text-xs font-bold bg-amber-500 text-slate-900 rounded-full">{{ $pendingMhsCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.mentor.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.mentor.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Pembimbing (Mentor)
            </a>

            <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-6 mb-2">Komunikasi</p>

            <a href="{{ route('admin.pengumuman.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.pengumuman.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                Pengumuman
            </a>
        </nav>

        <!-- Admin Profile footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center font-bold text-sky-400 text-sm border border-slate-700">
                        {{ substr(auth()->user()->admin->nama ?? 'AD', 0, 2) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->admin->nama ?? auth()->user()->username }}</p>
                        <p class="text-[10px] text-slate-400 truncate">Administrator</p>
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
                <h2 class="text-base font-bold text-slate-800">@yield('page-title', 'Dashboard')</h2>
            </div>
            <div class="flex items-center gap-4 text-xs text-slate-500">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-100 font-medium">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ now()->translatedFormat('l, d F Y') }}
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

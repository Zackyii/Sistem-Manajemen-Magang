@extends('layouts.app')

@section('body')
<div class="min-h-screen flex flex-col bg-slate-50 pb-20 md:pb-6">
    <!-- Top Header Navigation -->
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-slate-200/80 shadow-xs">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleSidebar()" class="p-2 -ml-2 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-700 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        S
                    </div>
                    <div>
                        <h1 class="text-base font-extrabold tracking-tight text-blue-900 leading-none">SIMANG</h1>
                        <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Diskominfo Jatim</p>
                    </div>
                </div>
            </div>

            <!-- Profile & Notification -->
            <div class="flex items-center gap-2">
                <a href="{{ route('mahasiswa.pesan.index') }}" class="relative p-2 rounded-xl text-slate-500 hover:bg-slate-100 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </a>
                <div class="flex items-center gap-2 pl-2 border-l border-slate-200">
                    <div class="w-8 h-8 rounded-full bg-blue-100 border border-blue-200 flex items-center justify-center text-blue-800 font-bold text-xs">
                        {{ substr(auth()->user()->mahasiswa->nama ?? auth()->user()->username, 0, 2) }}
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Slide-over Navigation Drawer for Mobile & Desktop -->
    <div id="sidebar-drawer" class="fixed inset-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out pointer-events-none">
        <!-- Backdrop -->
        <div id="sidebar-backdrop" onclick="toggleSidebar()" class="absolute inset-0 bg-slate-900/50 backdrop-blur-xs opacity-0 transition-opacity duration-300"></div>

        <!-- Drawer Content -->
        <div class="relative w-72 max-w-[80vw] h-full bg-white shadow-2xl flex flex-col pointer-events-auto">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-blue-900 text-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center font-bold text-amber-300 text-lg border border-white/20">
                        S
                    </div>
                    <div>
                        <h2 class="font-bold text-base">SIMANG</h2>
                        <p class="text-xs text-blue-200">Portal Mahasiswa</p>
                    </div>
                </div>
                <button onclick="toggleSidebar()" class="text-white/80 hover:text-white p-1 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-4 border-b border-slate-100 bg-slate-50/70">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Mahasiswa Magang</p>
                <p class="font-bold text-sm text-slate-800 truncate mt-0.5">{{ auth()->user()->mahasiswa->nama ?? auth()->user()->username }}</p>
                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->mahasiswa->bidang->nama_bidang ?? 'Belum ditentukan' }}</p>
            </div>

            <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>

                <a href="{{ route('mahasiswa.presensi.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('mahasiswa.presensi.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Presensi & Selfie GPS
                </a>

                <a href="{{ route('mahasiswa.logbook.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('mahasiswa.logbook.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Logbook Harian
                </a>

                <a href="{{ route('mahasiswa.tanggalan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('mahasiswa.tanggalan.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Kalender Magang
                </a>

                <a href="{{ route('mahasiswa.pesan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('mahasiswa.pesan.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    Pesan & Laporan
                </a>

                <div class="pt-4 mt-4 border-t border-slate-100">
                    <p class="px-3 text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-1">Informasi & Bantuan</p>
                    
                    <a href="{{ route('mahasiswa.panduan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Panduan & Tata Tertib
                    </a>

                    <a href="{{ route('mahasiswa.survey.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        Survey Evaluasi Magang
                    </a>
                </div>
            </nav>

            <div class="p-4 border-t border-slate-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar Akun
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content Container (Mobile-first max-w-4xl) -->
    <main class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 py-4">
        <x-alert />
        @yield('content')
    </main>

    <!-- Bottom Navigation Bar (Mobile-first sticky bottom) -->
    <nav class="fixed bottom-0 inset-x-0 z-30 bg-white/95 backdrop-blur border-t border-slate-200/90 py-1 px-3 md:hidden shadow-lg">
        <div class="flex items-center justify-around">
            <a href="{{ route('mahasiswa.dashboard') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg text-xs font-medium {{ request()->routeIs('mahasiswa.dashboard') ? 'text-blue-700 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>Home</span>
            </a>

            <a href="{{ route('mahasiswa.presensi.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg text-xs font-medium {{ request()->routeIs('mahasiswa.presensi.*') ? 'text-blue-700 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                <div class="-mt-4 w-10 h-10 rounded-full bg-blue-700 text-white flex items-center justify-center shadow-md shadow-blue-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                </div>
                <span>Presensi</span>
            </a>

            <a href="{{ route('mahasiswa.logbook.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg text-xs font-medium {{ request()->routeIs('mahasiswa.logbook.*') ? 'text-blue-700 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Logbook</span>
            </a>

            <a href="{{ route('mahasiswa.tanggalan.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg text-xs font-medium {{ request()->routeIs('mahasiswa.tanggalan.*') ? 'text-blue-700 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Kalender</span>
            </a>

            <a href="{{ route('mahasiswa.pesan.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg text-xs font-medium {{ request()->routeIs('mahasiswa.pesan.*') ? 'text-blue-700 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                <span>Pesan</span>
            </a>
        </div>
    </nav>
</div>

<script>
    function toggleSidebar() {
        const drawer = document.getElementById('sidebar-drawer');
        const backdrop = document.getElementById('sidebar-backdrop');
        const isClosed = drawer.classList.contains('-translate-x-full');
        
        if (isClosed) {
            drawer.classList.remove('-translate-x-full');
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
        } else {
            drawer.classList.add('-translate-x-full');
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
        }
    }
</script>
@endsection

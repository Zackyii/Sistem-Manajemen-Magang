@extends('layouts.app')

@section('title', 'Login - SIMANG')

@section('body')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 text-slate-100 relative overflow-hidden">
    <!-- Subtle Background Glows -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4">
        <!-- Logo Brand -->
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-700 to-sky-500 shadow-xl shadow-blue-500/30 text-white font-extrabold text-3xl border border-white/20 mb-4">
                S
            </div>
            <h2 class="text-2xl font-extrabold tracking-tight text-white font-heading">SIMANG</h2>
            <p class="text-xs font-semibold uppercase tracking-widest text-amber-400 mt-1">Dinas Komunikasi dan Informatika Provinsi Jawa Timur</p>
            <p class="mt-2 text-sm text-slate-300">Sistem Informasi Manajemen Magang & Praktik Kerja</p>
        </div>

        <!-- Card Container -->
        <div class="mt-8 bg-white/95 backdrop-blur-md py-8 px-6 shadow-2xl rounded-3xl sm:px-10 border border-white/20 text-slate-800">
            <x-alert />

            <form class="space-y-5" action="{{ route('login.post') }}" method="POST">
                @csrf

                <div>
                    <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Username</label>
                    <div class="mt-1.5 relative">
                        <input id="username" name="username" type="text" autocomplete="username" required value="{{ old('username') }}" placeholder="Masukkan username Anda" class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Password</label>
                    <div class="mt-1.5 relative">
                        <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="••••••••" class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <label for="remember" class="ml-2 block text-xs text-slate-600">Ingat saya</label>
                    </div>
                    <span class="text-xs text-slate-400">Hubungi admin jika lupa password</span>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-700/30 text-sm font-bold text-white bg-gradient-to-r from-blue-700 to-blue-800 hover:from-blue-800 hover:to-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-all active:scale-[0.99]">
                        Masuk ke SIMANG
                    </button>
                </div>
            </form>

            <!-- Quick Demo Credentials Box for Evaluation -->
            <div class="mt-6 pt-6 border-t border-slate-200/80">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center mb-3">Pilihan Akun Demo (Klik untuk mengisi):</p>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="fillCredentials('admin', 'admin123')" class="px-2 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold text-center transition-colors">
                        Admin
                    </button>
                    <button type="button" onclick="fillCredentials('mentor_aptika', 'password123')" class="px-2 py-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold text-center transition-colors">
                        Mentor
                    </button>
                    <button type="button" onclick="fillCredentials('mahasiswa', 'mahasiswa123')" class="px-2 py-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold text-center transition-colors">
                        Mahasiswa
                    </button>
                </div>
            </div>
        </div>

        <p class="mt-8 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Dinas Komunikasi dan Informatika Provinsi Jawa Timur.
        </p>
    </div>
</div>

<script>
    function fillCredentials(user, pass) {
        document.getElementById('username').value = user;
        document.getElementById('password').value = pass;
    }
</script>
@endsection

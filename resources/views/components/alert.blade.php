@if (session('success'))
    <div class="mb-4 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50/90 p-4 text-emerald-800 shadow-sm transition-all dark:border-emerald-800/40 dark:bg-emerald-950/40 dark:text-emerald-300">
        <svg class="h-5 w-5 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-sm font-medium">{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="mb-4 flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50/90 p-4 text-rose-800 shadow-sm transition-all dark:border-rose-800/40 dark:bg-rose-950/40 dark:text-rose-300">
        <svg class="h-5 w-5 shrink-0 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
        </svg>
        <p class="text-sm font-medium">{{ session('error') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50/90 p-4 text-rose-800 shadow-sm dark:border-rose-800/40 dark:bg-rose-950/40 dark:text-rose-300">
        <div class="flex items-center gap-2 mb-2 font-medium text-sm">
            <svg class="h-5 w-5 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            Terdapat beberapa kesalahan:
        </div>
        <ul class="list-disc list-inside text-xs space-y-1 ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

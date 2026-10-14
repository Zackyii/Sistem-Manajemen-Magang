@props(['title', 'value', 'icon' => null, 'trend' => null, 'badge' => null])

<div {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm border border-slate-100 transition-all hover:shadow-md dark:bg-slate-900 dark:border-slate-800']) }}>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $title }}</p>
            <h3 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $value }}</h3>
            @if ($trend)
                <p class="mt-1 text-xs text-slate-500 flex items-center gap-1">
                    {{ $trend }}
                </p>
            @endif
        </div>
        @if ($icon)
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>

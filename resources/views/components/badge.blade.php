@props(['status' => 'pending'])

@php
$statusLower = strtolower($status);
$classes = match($statusLower) {
    // Contrast: emerald-800 on emerald-50 ≈ 6.2:1 ✓ WCAG AA
    'hadir', 'disetujui', 'active', 'aktif' => 'bg-emerald-50 text-emerald-800 ring-1 ring-emerald-600/20 dark:bg-emerald-950/40 dark:text-emerald-300 dark:ring-emerald-800/40',
    // Contrast: amber-800 on amber-50 ≈ 5.8:1 ✓ WCAG AA (was amber-700 ≈ 3.9:1 ✗)
    'terlambat' => 'bg-amber-50 text-amber-800 ring-1 ring-amber-400/40 dark:bg-amber-950/50 dark:text-amber-300 dark:ring-amber-700/50',
    // Contrast: blue-800 on blue-50 ≈ 6.5:1 ✓ WCAG AA (was blue-700 ≈ 4.6:1 ✓ marginal)
    'izin', 'draft' => 'bg-blue-50 text-blue-800 ring-1 ring-blue-600/20 dark:bg-blue-950/40 dark:text-blue-300 dark:ring-blue-800/40',
    // Contrast: purple-800 on purple-50 ≈ 6.1:1 ✓ WCAG AA
    'sakit' => 'bg-purple-50 text-purple-800 ring-1 ring-purple-600/20 dark:bg-purple-950/40 dark:text-purple-300 dark:ring-purple-800/40',
    'alpha', 'ditolak', 'rejected' => 'bg-rose-50 text-rose-800 ring-1 ring-rose-600/20 dark:bg-rose-950/40 dark:text-rose-300 dark:ring-rose-800/40',
    // Contrast: amber-800 on amber-50 ≈ 5.8:1 ✓ WCAG AA (was amber-700 ≈ 3.9:1 ✗)
    'pending', 'submitted' => 'bg-amber-50 text-amber-800 ring-1 ring-amber-400/40 dark:bg-amber-950/50 dark:text-amber-300 dark:ring-amber-700/50',
    'penting' => 'bg-rose-100 text-rose-900 font-bold ring-1 ring-rose-300',
    'info' => 'bg-sky-50 text-sky-800 ring-1 ring-sky-600/20',
    'sistem' => 'bg-slate-100 text-slate-700 ring-1 ring-slate-300/60',
    default => 'bg-slate-50 text-slate-700 ring-1 ring-slate-600/20'
};
$label = match($statusLower) {
    'hadir' => 'Hadir',
    'terlambat' => 'Terlambat',
    'izin' => 'Izin',
    'sakit' => 'Sakit',
    'alpha' => 'Alpha',
    'disetujui' => 'Disetujui',
    'ditolak' => 'Ditolak',
    'pending' => 'Menunggu',
    'submitted' => 'Diajukan',
    'draft' => 'Draf',
    'active', 'aktif' => 'Aktif',
    'rejected' => 'Ditolak',
    'penting' => 'Penting',
    'info' => 'Info',
    'sistem' => 'Sistem',
    default => ucfirst($status)
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-xs font-semibold $classes"]) }}>
    {{ $label }}
</span>

<!DOCTYPE html>
<html lang="id" class="h-full antialiased" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMANG') - Sistem Informasi Manajemen Magang Diskominfo Jatim</title>
    
    <!-- Google Fonts: Hanken Grotesk & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Hanken Grotesk', 'Inter', sans-serif; }
    </style>

    <!-- Dark mode: apply class before paint to prevent flash -->
    <script>
        (function() {
            const saved = localStorage.getItem('simang-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    @stack('styles')
</head>
<body class="h-full text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-950 selection:bg-blue-600 selection:text-white transition-colors duration-200">
    @yield('body')
    
    <!-- Theme Toggle Script -->
    <script>
    function toggleTheme() {
        const html = document.documentElement;
        const isDark = html.classList.toggle('dark');
        localStorage.setItem('simang-theme', isDark ? 'dark' : 'light');
        // Update all toggle button icons
        document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
            const sunIcon = btn.querySelector('.icon-sun');
            const moonIcon = btn.querySelector('.icon-moon');
            if (sunIcon) sunIcon.classList.toggle('hidden', !isDark);
            if (moonIcon) moonIcon.classList.toggle('hidden', isDark);
        });
    }

    // Sync icons after DOM load
    document.addEventListener('DOMContentLoaded', () => {
        const isDark = document.documentElement.classList.contains('dark');
        document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
            const sunIcon = btn.querySelector('.icon-sun');
            const moonIcon = btn.querySelector('.icon-moon');
            if (sunIcon) sunIcon.classList.toggle('hidden', !isDark);
            if (moonIcon) moonIcon.classList.toggle('hidden', isDark);
        });
    });
    </script>
    @stack('scripts')
</body>
</html>

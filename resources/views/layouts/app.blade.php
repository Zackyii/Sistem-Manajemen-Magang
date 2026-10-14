<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 antialiased">
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
    
    <!-- Fallback Tailwind CSS via CDN if Vite dev server is not active -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        secondary: {
                            400: '#38bdf8',
                            500: '#0ea5e9',
                        },
                        tertiary: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                        },
                        brand: {
                            navy: '#1E40AF',
                            sky: '#3BBDFB',
                            gold: '#FBBF24',
                            gray: '#76767F'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        heading: ['Hanken Grotesk', 'Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Hanken Grotesk', 'Inter', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="h-full text-slate-800 bg-slate-50 selection:bg-blue-600 selection:text-white">
    @yield('body')
    @stack('scripts')
</body>
</html>

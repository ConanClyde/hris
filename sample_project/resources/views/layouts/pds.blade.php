<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Personal Data Sheet' }} — {{ config('app.name', 'HRIS') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function () {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    @stack('styles')
</head>
<body class="h-screen overflow-hidden bg-[#FDFDFD] dark:bg-neutral-950 text-gray-900 dark:text-gray-100 antialiased font-sans">
    <div class="h-screen flex overflow-hidden">
        @include('partials.sidebar')

        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 lg:hidden hidden" aria-hidden="true"></div>

        <div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">
            @include('partials.navbar')

            <main class="flex-1 p-4 lg:p-6 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>

    @include('partials.dashboard-scripts')
    @stack('scripts')
</body>
</html>

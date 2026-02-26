<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-theme">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'HRIS') }}</title>

        {{-- Fonts --}}
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
    </head>
<body class="min-h-screen bg-[#FDFDFD] dark:bg-neutral-950 text-gray-900 dark:text-gray-100 antialiased font-sans">
    <div class="min-h-screen flex flex-col">
        <header class="flex items-center justify-end gap-4 p-4 lg:p-6 border-b border-gray-200 dark:border-neutral-800">
            <button type="button" id="landing-dark-toggle" class="p-2 rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors" aria-label="Toggle dark mode">
                <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
            </button>
            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-md border border-gray-300 dark:border-neutral-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-neutral-900 transition-colors">
                            Dashboard
                        </a>
                    @else
                            <a href="{{ route('login') }}" class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-md bg-[#013CFC] text-white text-sm font-medium hover:bg-[#0031BC] transition-colors">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
        <main class="flex-1 flex items-center justify-center p-6 lg:p-8">
            <div class="text-center max-w-2xl">
                <div class="w-14 h-14 mx-auto mb-6 rounded-md bg-[#013CFC] flex items-center justify-center">
                    <span class="text-white font-semibold text-xl">{{ substr(config('app.name', 'HRIS'), 0, 1) }}</span>
                </div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ config('app.name', 'HRIS') }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">Human Resource Information System. Manage employees, attendance, and payroll in one place.</p>
                @guest
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3 rounded-md bg-[#013CFC] text-white text-sm font-medium hover:bg-[#0031BC] transition-colors text-center">
                        Sign in
                    </a>
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3 rounded-md border border-gray-300 dark:border-neutral-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-neutral-900 transition-colors text-center">
                        Register
                    </a>
                </div>
                @else
                <a href="{{ url('/dashboard') }}" class="inline-block px-6 py-3 rounded-md bg-[#013CFC] text-white text-sm font-medium hover:bg-[#0031BC] transition-colors">
                    Go to Dashboard
                </a>
                @endguest
                </div>
            </main>
        </div>
        <script>
            document.getElementById('landing-dark-toggle')?.addEventListener('click', function() {
                const html = document.documentElement;
                const isDark = html.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });
        </script>
    </body>
</html>

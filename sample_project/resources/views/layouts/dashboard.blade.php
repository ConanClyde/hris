<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-theme">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — {{ config('app.name', 'HRIS') }}</title>

    {{-- Fonts: preconnect + display=swap so text is visible immediately while fonts load --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">

    <script>
        window.HRIS_USER_ID = @json(session('user_id'));
        window.HRIS_ROLE = @json(session('role'));
    </script>
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
<body class="h-screen overflow-hidden bg-[#FDFDFD] dark:bg-neutral-950 text-gray-900 dark:text-gray-100 antialiased font-sans">
    {{-- Frappe Charts: load only when page has charts (lazy, non-blocking); must run before chart inits in @stack('scripts') --}}
    @push('scripts')
    <script>
    (function(){
        window.__frappeChartQueue = window.__frappeChartQueue || [];
        if (!document.querySelector('.frappe-chart-wrapper')) return;
        var s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/frappe-charts@1.6.2/dist/frappe-charts.min.umd.min.js';
        s.async = true;
        s.onload = function() {
            (window.__frappeChartQueue || []).forEach(function(fn) { try { fn(); } catch (e) {} });
            window.__frappeChartQueue = [];
        };
        document.head.appendChild(s);
    })();
    </script>
    @endpush
    <div class="h-screen flex overflow-hidden">
        @include('partials.sidebar')

        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 lg:hidden hidden" aria-hidden="true"></div>

        <div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">
            @include('partials.navbar')

            <main class="flex-1 p-4 lg:p-6 overflow-auto">
                @php
                    $globalNotices = $globalNotices ?? collect();
                @endphp
                @foreach($globalNotices as $notice)
                    <div class="mb-6 rounded-md p-4 flex items-start gap-3
                        {{ $notice->type === 'info' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : '' }}
                        {{ $notice->type === 'success' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : '' }}
                        {{ $notice->type === 'warning' ? 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300' : '' }}
                        {{ $notice->type === 'danger' ? 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300' : '' }}">
                        <div class="shrink-0 mt-0.5">
                            @if($notice->type === 'info')
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            @elseif($notice->type === 'success')
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            @elseif($notice->type === 'warning')
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            @else
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium">{{ $notice->title }}</h3>
                            <div class="mt-1 text-sm opacity-90">
                                {{ $notice->message }}
                            </div>
                        </div>
                        <button type="button" onclick="this.closest('.mb-6').remove()" class="shrink-0 -mr-1 -mt-1 p-1 rounded-md hover:bg-black/10 dark:hover:bg-white/10 focus:outline-none transition-colors">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @endforeach
                @yield('content')
            </main>
        </div>
    </div>

    @include('partials.dashboard-scripts')
    @stack('scripts')
</body>
</html>

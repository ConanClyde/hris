<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDS Preview — {{ config('app.name', 'HRIS') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function(){var s=localStorage.getItem('theme');var p=window.matchMedia('(prefers-color-scheme: dark)').matches;if(s==='dark'||(!s&&p))document.documentElement.classList.add('dark');else document.documentElement.classList.remove('dark');})();
    </script>
</head>
<body class="bg-white dark:bg-neutral-950 text-gray-900 dark:text-gray-100 p-6 font-sans text-sm">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="border-b border-gray-200 dark:border-neutral-800 pb-4">
            <h1 class="text-lg font-semibold">Personal Data Sheet</h1>
            <p class="text-gray-500 dark:text-gray-400">CS Form No. 212 (Revised 2025)</p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-gray-50/50 dark:bg-neutral-900/50 p-8 text-center">
            <p class="text-gray-500 dark:text-gray-400">PDS preview content will be rendered here. Connect backend to populate with employee PDS data.</p>
        </div>
    </div>
</body>
</html>

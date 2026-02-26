@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex flex-col lg:flex-row">
    {{-- Left: Branding --}}
    <aside class="relative flex-1 bg-gradient-to-br from-[#013CFC] via-[#0031BC] to-[#60C8FC] border-r border-gray-200 p-6 lg:p-10 flex flex-col justify-between">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm text-white/90 hover:text-white transition-colors w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Back to home
        </a>

        <div class="flex-1 flex flex-col justify-center mt-8 lg:mt-0">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-md bg-white/20 flex items-center justify-center">
                    <span class="text-white font-semibold text-lg">{{ substr(config('app.name', 'HRIS'), 0, 1) }}</span>
                </div>
                <span class="text-xl font-semibold text-white">{{ config('app.name', 'HRIS') }}</span>
            </div>
            <p class="text-white/80 text-sm leading-relaxed max-w-sm">
                {{ config('app.name', 'HRIS') }} is your complete people platform designed to simplify HR and enhance productivity. It supports growing teams from onboarding to payroll. With {{ config('app.name', 'HRIS') }}, you can lead more efficiently.
            </p>
        </div>

        <div class="flex gap-6 text-sm text-white/80 mt-8 lg:mt-0">
            <a href="#" class="hover:text-white transition-colors">About</a>
            <a href="#" class="hover:text-white transition-colors">FAQ</a>
            <a href="#" class="hover:text-white transition-colors">Support</a>
        </div>
    </aside>

    {{-- Right: Form --}}
    <main class="flex-1 flex items-center justify-center p-6 lg:p-12 bg-[#FDFDFD] dark:bg-neutral-950 relative">
        <button type="button" id="auth-dark-toggle" class="absolute top-4 right-4 p-2 rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors" aria-label="Toggle dark mode">
            <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
            <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
        </button>
        <div class="w-full max-w-md">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-1">Sign in</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Enter your credentials to continue.</p>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-md bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-sm text-red-700 dark:text-red-400">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-1.5">User ID</label>
                    <input type="text" id="user_id" name="user_id" placeholder="Enter your user ID" autocomplete="username"
                        class="w-full px-4 py-3 bg-white dark:bg-neutral-900 border border-gray-300 dark:border-neutral-700 rounded-md text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950 focus:border-transparent">
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium text-gray-900 dark:text-gray-100">Password</label>
                        <a href="{{ route('password.request') }}" class="text-sm text-[#013CFC] hover:text-[#0031BC]">Forgot Password</a>
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password"
                            class="w-full px-4 py-3 bg-white dark:bg-neutral-900 border border-gray-300 dark:border-neutral-700 rounded-md text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950 focus:border-transparent pr-10">
                        <button type="button" id="toggle-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 p-1" aria-label="Toggle password visibility">
                            <svg class="w-4 h-4 eye-open" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg class="w-4 h-4 eye-closed hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-[#013CFC] text-white text-sm font-medium rounded-md hover:bg-[#0031BC] transition-colors focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                    Sign in
                </button>
            </form>

            <p class="text-sm text-gray-600 dark:text-gray-400 mt-6 text-center">
                Don't have an account? <a href="{{ route('register') }}" class="text-[#013CFC] font-medium hover:text-[#0031BC]">Register</a>
            </p>

            <p class="text-xs text-gray-400 dark:text-gray-500 mt-8 text-center">
                {{ config('app.name', 'HRIS') }} — All rights reserved.
            </p>
        </div>
    </main>
</div>

<script>
document.getElementById('toggle-password')?.addEventListener('click', function() {
    const input = document.getElementById('password');
    const open = document.querySelector('.eye-open');
    const closed = document.querySelector('.eye-closed');
    if (input.type === 'password') {
        input.type = 'text';
        open?.classList.add('hidden');
        closed?.classList.remove('hidden');
    } else {
        input.type = 'password';
        open?.classList.remove('hidden');
        closed?.classList.add('hidden');
    }
});

</script>
@endsection

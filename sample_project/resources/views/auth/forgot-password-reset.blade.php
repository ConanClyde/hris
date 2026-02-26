@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex flex-col lg:flex-row">
    {{-- Left: Branding --}}
    <aside class="relative flex-1 bg-gradient-to-br from-[#013CFC] via-[#0031BC] to-[#60C8FC] border-r border-gray-200 p-6 lg:p-10 flex flex-col justify-between">
        <a href="{{ route('password.verify') }}?email={{ urlencode(request('email')) }}" class="inline-flex items-center gap-2 text-sm text-white/90 hover:text-white transition-colors w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Back
        </a>

        <div class="flex-1 flex flex-col justify-center mt-8 lg:mt-0">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-md bg-white/20 flex items-center justify-center">
                    <span class="text-white font-semibold text-lg">{{ substr(config('app.name', 'HRIS'), 0, 1) }}</span>
                </div>
                <span class="text-xl font-semibold text-white">{{ config('app.name', 'HRIS') }}</span>
            </div>
            <p class="text-white/80 text-sm leading-relaxed max-w-sm">
                Create a new password. Use at least 8 characters with a mix of letters and numbers.
            </p>
        </div>

        <div class="flex gap-6 text-sm text-white/80 mt-8 lg:mt-0">
            <a href="#" class="hover:text-white transition-colors">About</a>
            <a href="#" class="hover:text-white transition-colors">FAQ</a>
            <a href="#" class="hover:text-white transition-colors">Support</a>
        </div>
    </aside>

    {{-- Right: Form --}}
    <main class="flex-1 flex items-center justify-center p-6 lg:p-12 bg-[#FDFDFD]">
        <div class="w-full max-w-md">
            <h1 class="text-xl font-semibold text-gray-900 mb-1">Reset password</h1>
            <p class="text-sm text-gray-600 mb-6">Enter your new password below.</p>

            <form action="{{ route('password.reset.done') }}" method="GET" class="space-y-4">
                <input type="hidden" name="email" value="{{ request('email') }}">
                <input type="hidden" name="code" value="{{ request('code') }}">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-900 mb-1.5">New password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="••••••••" minlength="8" autocomplete="new-password" required
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent pr-10">
                        <button type="button" id="toggle-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1" aria-label="Toggle password visibility">
                            <svg class="w-4 h-4 eye-open" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg class="w-4 h-4 eye-closed hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-1.5">Confirm password</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" minlength="8" autocomplete="new-password" required
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent pr-10">
                        <button type="button" id="toggle-password-confirmation" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1" aria-label="Toggle password visibility">
                            <svg class="w-4 h-4 eye-open-confirm" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg class="w-4 h-4 eye-closed-confirm hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-[#013CFC] text-white text-sm font-medium rounded-md hover:bg-[#0031BC] transition-colors focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2">
                    Reset password
                </button>
            </form>

            <p class="text-xs text-gray-400 mt-8 text-center">
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
document.getElementById('toggle-password-confirmation')?.addEventListener('click', function() {
    const input = document.getElementById('password_confirmation');
    const open = document.querySelector('.eye-open-confirm');
    const closed = document.querySelector('.eye-closed-confirm');
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

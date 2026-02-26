@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex flex-col lg:flex-row">
    {{-- Left: Branding --}}
    <aside class="relative flex-1 bg-gradient-to-br from-[#013CFC] via-[#0031BC] to-[#60C8FC] border-r border-gray-200 p-6 lg:p-10 flex flex-col justify-between">
        <div class="flex-1 flex flex-col justify-center mt-8 lg:mt-0">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-md bg-white/20 flex items-center justify-center">
                    <span class="text-white font-semibold text-lg">{{ substr(config('app.name', 'HRIS'), 0, 1) }}</span>
                </div>
                <span class="text-xl font-semibold text-white">{{ config('app.name', 'HRIS') }}</span>
            </div>
            <p class="text-white/80 text-sm leading-relaxed max-w-sm">
                {{ config('app.name', 'HRIS') }} is your complete people platform designed to simplify HR and enhance productivity.
            </p>
        </div>

        <div class="flex gap-6 text-sm text-white/80 mt-8 lg:mt-0">
            <a href="#" class="hover:text-white transition-colors">About</a>
            <a href="#" class="hover:text-white transition-colors">FAQ</a>
            <a href="#" class="hover:text-white transition-colors">Support</a>
        </div>
    </aside>

    {{-- Right: Success --}}
    <main class="flex-1 flex items-center justify-center p-6 lg:p-12 bg-[#FDFDFD]">
        <div class="w-full max-w-md text-center">
            <div class="w-16 h-16 mx-auto mb-6 rounded-md bg-[#013CFC]/10 flex items-center justify-center">
                <svg class="w-8 h-8 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-xl font-semibold text-gray-900 mb-1">Password reset</h1>
            <p class="text-sm text-gray-600 mb-8">Your password has been reset successfully. You can now sign in with your new password.</p>

            <a href="{{ route('login') }}" class="inline-block w-full py-3 px-4 bg-[#013CFC] text-white text-sm font-medium rounded-md hover:bg-[#0031BC] transition-colors focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 text-center">
                Sign in
            </a>

            <p class="text-xs text-gray-400 mt-8">
                {{ config('app.name', 'HRIS') }} — All rights reserved.
            </p>
        </div>
    </main>
</div>
@endsection

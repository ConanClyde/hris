@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex flex-col lg:flex-row">
    {{-- Left: Branding --}}
    <aside class="relative flex-1 bg-gradient-to-br from-[#013CFC] via-[#0031BC] to-[#60C8FC] border-r border-gray-200 p-6 lg:p-10 flex flex-col justify-between">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm text-white/90 hover:text-white transition-colors w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Back to login
        </a>

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

    {{-- Right: Form --}}
    <main class="flex-1 flex items-center justify-center p-6 lg:p-12 bg-[#FDFDFD]">
        <div class="w-full max-w-md">
            <h1 class="text-xl font-semibold text-gray-900 mb-1">Forgot password</h1>
            <p class="text-sm text-gray-600 mb-6">Enter your email address and we'll send you a 6-digit code to reset your password.</p>

            <form action="{{ route('password.verify') }}" method="GET" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900 mb-1.5">Email</label>
                    <input type="email" id="email" name="email" placeholder="name@company.com" autocomplete="email" required
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent">
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-[#013CFC] text-white text-sm font-medium rounded-md hover:bg-[#0031BC] transition-colors focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2">
                    Send reset code
                </button>
            </form>

            <p class="text-sm text-gray-600 mt-6 text-center">
                Remember your password? <a href="{{ route('login') }}" class="text-[#013CFC] font-medium hover:text-[#0031BC]">Sign in</a>
            </p>

            <p class="text-xs text-gray-400 mt-8 text-center">
                {{ config('app.name', 'HRIS') }} — All rights reserved.
            </p>
        </div>
    </main>
</div>
@endsection

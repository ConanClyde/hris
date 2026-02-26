@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex flex-col lg:flex-row">
    {{-- Left: Branding --}}
    <aside class="relative flex-1 bg-gradient-to-br from-[#013CFC] via-[#0031BC] to-[#60C8FC] border-r border-gray-200 p-6 lg:p-10 flex flex-col justify-between">
        <a href="{{ route('password.request') }}" class="inline-flex items-center gap-2 text-sm text-white/90 hover:text-white transition-colors w-fit">
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
                Check your inbox for the 6-digit verification code we sent to your email.
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
            <h1 class="text-xl font-semibold text-gray-900 mb-1">Verify code</h1>
            <p class="text-sm text-gray-600 mb-6">Enter the 6-digit code sent to <span class="font-medium text-gray-900">{{ request('email', 'your email') }}</span></p>

            <form action="{{ route('password.reset.form') }}" method="GET" class="space-y-4">
                <input type="hidden" name="email" value="{{ request('email') }}">
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-900 mb-1.5">Verification code</label>
                    <input type="text" id="code" name="code" placeholder="000000" maxlength="6" pattern="[0-9]{6}" autocomplete="one-time-code" required
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm text-center tracking-[0.5em] font-medium focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent">
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-[#013CFC] text-white text-sm font-medium rounded-md hover:bg-[#0031BC] transition-colors focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2">
                    Verify
                </button>
            </form>

            <p class="text-sm text-gray-600 mt-6 text-center">
                Didn't receive the code? <a href="{{ route('password.request') }}" class="text-[#013CFC] font-medium hover:text-[#0031BC]">Resend</a>
            </p>

            <p class="text-xs text-gray-400 mt-8 text-center">
                {{ config('app.name', 'HRIS') }} — All rights reserved.
            </p>
        </div>
    </main>
</div>
@endsection

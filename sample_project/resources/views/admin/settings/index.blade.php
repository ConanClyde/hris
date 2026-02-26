@extends('layouts.dashboard')

@section('navbarTitle', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    {{-- Header --}}
    <div>
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Settings</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your workspace preferences and security settings.</p>
    </div>

    {{-- Display Section --}}
    <section class="space-y-4">
        <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-neutral-800 pb-2">Display & Appearance</h2>

        <div class="grid gap-6">
            {{-- Dark Mode --}}
            <div class="flex items-center justify-between p-4 rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
                <div class="space-y-0.5">
                    <label class="text-sm font-medium text-gray-900 dark:text-gray-100">Dark Mode</label>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Reduce eye strain with a dark interface.</p>
                </div>
                <button type="button"
                        class="js-theme-toggle group relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 bg-gray-200 dark:bg-neutral-700 aria-[checked=true]:bg-[#013CFC] dark:aria-[checked=true]:bg-[#013CFC]"
                        role="switch"
                        aria-checked="false">
                    <span class="sr-only">Use setting</span>
                    <span aria-hidden="true"
                          class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-0 group-aria-[checked=true]:translate-x-5">
                    </span>
                </button>
            </div>


        </div>
    </section>

    {{-- Notifications Section --}}
    <section class="space-y-4">
        <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-neutral-800 pb-2">Notification Preferences</h2>

        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm divide-y divide-gray-200 dark:divide-neutral-800">
            {{-- Email Notifications --}}
            <div class="flex items-center justify-between p-4">
                <div class="space-y-0.5">
                    <label class="text-sm font-medium text-gray-900 dark:text-gray-100">Email Notifications</label>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Receive essential updates via email.</p>
                </div>
                <!-- Toggle Button Component -->
                <button type="button" onclick="toggleSwitch(this)"
                        class="group relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 bg-gray-200 dark:bg-neutral-700 aria-[checked=true]:bg-[#013CFC]" role="switch" aria-checked="true">
                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-0 group-aria-[checked=true]:translate-x-5"></span>
                </button>
            </div>

            {{-- Leave Request Updates --}}
            <div class="flex items-center justify-between p-4">
                <div class="space-y-0.5">
                    <label class="text-sm font-medium text-gray-900 dark:text-gray-100">Leave Request Updates</label>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Get notified when your leave status changes.</p>
                </div>
                <button type="button" onclick="toggleSwitch(this)"
                        class="group relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 bg-gray-200 dark:bg-neutral-700 aria-[checked=true]:bg-[#013CFC]" role="switch" aria-checked="true">
                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-0 group-aria-[checked=true]:translate-x-5"></span>
                </button>
            </div>

            {{-- Training Reminders --}}
            <div class="flex items-center justify-between p-4">
                <div class="space-y-0.5">
                    <label class="text-sm font-medium text-gray-900 dark:text-gray-100">Training Reminders</label>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Allow reminders for upcoming training sessions.</p>
                </div>
                <button type="button" onclick="toggleSwitch(this)"
                        class="group relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 bg-gray-200 dark:bg-neutral-700 aria-[checked=true]:bg-[#013CFC]" role="switch" aria-checked="false">
                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-0 group-aria-[checked=true]:translate-x-5"></span>
                </button>
            </div>
        </div>
    </section>

    {{-- Security Section --}}
    <section class="space-y-4">
        <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-neutral-800 pb-2">Security</h2>

        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm">
            <div class="p-4 border-b border-gray-200 dark:border-neutral-800">
                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Active Sessions</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Devices currently logged into your account.</p>
            </div>

            <div class="p-4 space-y-4">
                {{-- Current Session --}}
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Windows PC - Chrome <span class="ml-2 text-xs text-green-600 bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded-full border border-green-200 dark:border-green-800">Current session</span></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Quezon City, Philippines • {{ now()->format('M d, Y h:i A') }}</p>
                    </div>
                </div>

                {{-- Other Session (Mock) --}}
                <div class="flex items-start gap-3 opacity-60">
                    <div class="mt-0.5 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">iPhone 14 Pro - Safari</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Makati City, Philippines • Last active 2 hours ago</p>
                    </div>
                    <form method="POST" action="{{ route('settings.sessions.revoke') }}">
                        @csrf
                        <input type="hidden" name="session_id" value="{{ config('defaults.session.demo_session_id', 'mock-session-2') }}">
                        <button type="submit" class="text-xs text-red-600 hover:text-red-700 font-medium">Revoke</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Generic toggle handler for other settings
    function toggleSwitch(button) {
        if (button.classList.contains('js-theme-toggle')) return; // Ignore theme toggle, handled by app.js
        const isChecked = button.getAttribute('aria-checked') === 'true';
        button.setAttribute('aria-checked', !isChecked);
    }
</script>
@endsection

@extends('layouts.dashboard')

@section('navbarTitle', 'Leave Applications')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Leave Applications</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">View and manage leave requests.</p>
    </div>

    <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <div class="w-12 h-12 rounded-md bg-gray-100 dark:bg-neutral-800 flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                                </svg>
                            </div>
            <h2 class="text-base font-medium text-gray-900 dark:text-gray-100">No leave applications</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-sm">This page is under construction. Leave management features will be available soon.</p>
        </div>
    </div>
</div>
@endsection

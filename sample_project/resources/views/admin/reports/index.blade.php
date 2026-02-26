@extends('layouts.dashboard')

@section('navbarTitle', 'Reports & Analytics')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Reports & Analytics</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Organization-wide workforce analytics and reports.</p>
        </div>
        <button type="button" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-neutral-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-900 hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            Export
        </button>
    </div>

    {{-- Summary cards (Users, Leave, Training - system features) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">30</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">From Manage Users</p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pending User Approvals</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">6</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Awaiting approval</p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Leave Applications</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">—</p>
            <p class="text-xs mt-1"><a href="{{ route('admin.leave') }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">View Leave →</a></p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Activity Logs</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">—</p>
            <p class="text-xs mt-1"><a href="{{ route('admin.activity-logs.index') }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">View Logs →</a></p>
        </div>
    </div>

    {{-- Charts (Users by Role & Status - matches Manage Users) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Users by Role</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">Admin, HR, Employee distribution</p>
            <x-chart
                id="admin-report-users-role"
                type="percentage"
                :labels="['Admin', 'HR', 'Employee']"
                :datasets="[
                    ['name' => 'Users', 'values' => [1, 15, 14]],
                ]"
                :colors="['#013CFC', '#0031BC', '#60C8FC']"
                :height="260"
            />
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">User Status</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">Active, pending, inactive, rejected</p>
            <x-chart
                id="admin-report-user-status"
                type="percentage"
                :labels="['Active', 'Pending', 'Inactive', 'Rejected']"
                :datasets="[
                    ['name' => 'Users', 'values' => [18, 6, 4, 2]],
                ]"
                :colors="['#10b981', '#f59e0b', '#6b7280', '#ef4444']"
                :height="260"
            />
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Users by Division</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">From user profiles (Operations, IT, HR, Finance)</p>
            <x-chart
                id="admin-report-division"
                type="bar"
                :labels="['Operations', 'IT', 'HR', 'Finance']"
                :datasets="[
                    ['name' => 'Users', 'values' => [8, 8, 7, 7]],
                ]"
                :colors="['#013CFC']"
                :height="260"
            />
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Report Links</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">Access detailed data from each module</p>
            <div class="space-y-2 pt-4">
                <a href="{{ route('admin.users') }}" class="block p-3 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 text-sm font-medium text-gray-900 dark:text-gray-100">Manage Users →</a>
                <a href="{{ route('admin.leave') }}" class="block p-3 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 text-sm font-medium text-gray-900 dark:text-gray-100">Leave Applications →</a>
                <a href="{{ route('admin.activity-logs.index') }}" class="block p-3 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 text-sm font-medium text-gray-900 dark:text-gray-100">Activity Logs →</a>
            </div>
        </div>
    </div>

    {{-- Report sections --}}
    <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-neutral-800">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Report Categories</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Select a report to generate</p>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <a href="#" class="flex items-center gap-3 p-4 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Employee List</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Full roster with filters</p>
                </div>
            </a>
            <a href="#" class="flex items-center gap-3 p-4 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Attendance Report</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Daily/monthly attendance</p>
                </div>
            </a>
            <a href="#" class="flex items-center gap-3 p-4 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Leave Report</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Leave utilization</p>
                </div>
            </a>
            <a href="#" class="flex items-center gap-3 p-4 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Training Report</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">L&D completions</p>
                </div>
            </a>
            <a href="#" class="flex items-center gap-3 p-4 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">PDS Status Report</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">PDS completion status</p>
                </div>
            </a>
            <a href="#" class="flex items-center gap-3 p-4 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Custom Export</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Export with filters</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

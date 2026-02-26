@extends('layouts.dashboard')

@section('navbarTitle', 'Reports & Analytics')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Reports & Analytics</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">HR-focused analytics: leave, training, and PDS metrics.</p>
        </div>
        <button type="button" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-neutral-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-900 hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            Export
        </button>
    </div>

    {{-- Summary cards (Leave, Training, PDS - HR-managed features) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Leave Applications</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">—</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                <a href="{{ route('hr.leave-applications.index') }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">View page →</a>
            </p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Training Records</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">—</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                <a href="{{ route('hr.training.index') }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">View page →</a>
            </p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">PDS Management</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">—</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                <a href="{{ route('hr.pds.index') }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">View page →</a>
            </p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">30</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">From user management</p>
        </div>
    </div>

    {{-- Charts (HR context) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Leave Applications by Status</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">Monthly breakdown</p>
            <x-chart
                id="hr-report-leave-status"
                type="bar"
                :labels="['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']"
                :datasets="[
                    ['name' => 'Approved', 'values' => [42, 48, 55, 52, 61, 92]],
                    ['name' => 'Pending', 'values' => [8, 12, 10, 9, 11, 12]],
                    ['name' => 'Rejected', 'values' => [3, 2, 4, 5, 2, 5]],
                ]"
                :colors="['#10b981', '#f59e0b', '#ef4444']"
                :height="260"
            />
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Training Completions</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">By month</p>
            <x-chart
                id="hr-report-training"
                type="line"
                :labels="['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']"
                :datasets="[
                    ['name' => 'Completed', 'values' => [28, 32, 35, 30, 38, 23]],
                ]"
                :colors="['#013CFC']"
                :height="260"
            />
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Leave by Type</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">This month</p>
            <x-chart
                id="hr-report-leave-type"
                type="percentage"
                :labels="['Vacation', 'Sick', 'Emergency', 'Others']"
                :datasets="[
                    ['name' => 'Days', 'values' => [95, 48, 18, 10]],
                ]"
                :colors="['#013CFC', '#60C8FC', '#0031BC', '#6b7280']"
                :height="260"
            />
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">PDS Status Distribution</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">Current snapshot</p>
            <x-chart
                id="hr-report-pds-status"
                type="percentage"
                :labels="['Approved', 'Pending', 'No PDS']"
                :datasets="[
                    ['name' => 'Employees', 'values' => [1180, 55, 15]],
                ]"
                :colors="['#10b981', '#f59e0b', '#6b7280']"
                :height="260"
            />
        </div>
    </div>

    {{-- Quick report links --}}
    <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-neutral-800">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">HR Report Categories</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Generate reports for your scope</p>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <a href="{{ route('hr.leave-applications.index') }}" class="flex items-center gap-3 p-4 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Leave Applications</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">View & export leave data</p>
                </div>
            </a>
            <a href="{{ route('hr.training.index') }}" class="flex items-center gap-3 p-4 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Training Records</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">L&D submissions & approvals</p>
                </div>
            </a>
            <a href="{{ route('hr.pds.index') }}" class="flex items-center gap-3 p-4 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">PDS Management</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Review & approve PDS</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

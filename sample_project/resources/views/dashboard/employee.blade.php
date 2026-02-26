@extends('layouts.dashboard')

@section('navbarTitle', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">My Dashboard</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Welcome, {{ session('first_name', session('user_id', 'User')) }}. Here's your overview.</p>
    </div>

    {{-- KPI Cards (personal metrics) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Vacation Leave Balance</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">12</p>
            <div class="flex items-center gap-1.5 mt-2">
                <span class="text-sm font-medium text-green-600 dark:text-green-400">days remaining</span>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                <a href="{{ route('employee.leave-applications.index') }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">View balance →</a>
            </p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Sick Leave Balance</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">15</p>
            <div class="flex items-center gap-1.5 mt-2">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">days remaining</span>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                <a href="{{ route('employee.leave-applications.index') }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">File leave →</a>
            </p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Training Completed</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">3</p>
            <div class="flex items-center gap-1.5 mt-2">
                <span class="text-sm font-medium text-green-600 dark:text-green-400">this year</span>
                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                <a href="{{ route('employee.training.index') }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">Learning & Dev →</a>
            </p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">PDS Status</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">Approved</p>
            <div class="flex items-center gap-1.5 mt-2">
                <span class="inline-flex px-2 py-0.5 rounded-sm text-xs font-medium bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400">Up to date</span>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                <a href="{{ route('employee.pds.index') }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">Edit PDS →</a>
            </p>
        </div>
    </div>

    {{-- Charts (personal context) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">My Leave History</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">Leave days used by month</p>
            <x-chart
                id="employee-leave-history"
                type="bar"
                :labels="['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']"
                :datasets="[
                    ['name' => 'Vacation', 'values' => [0, 3, 2, 5, 0, 2]],
                    ['name' => 'Sick', 'values' => [1, 0, 1, 0, 2, 0]],
                ]"
                :colors="['#013CFC', '#60C8FC']"
                :height="260"
            />
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Leave Balance</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">Remaining by type</p>
            <x-chart
                id="employee-leave-balance"
                type="percentage"
                :labels="['Vacation', 'Sick', 'Emergency']"
                :datasets="[
                    ['name' => 'Days', 'values' => [12, 15, 3]],
                ]"
                :colors="['#013CFC', '#60C8FC', '#0031BC']"
                :height="260"
            />
        </div>
    </div>

    {{-- Quick actions & upcoming --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-neutral-800">
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Quick Actions</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Frequent tasks</p>
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('employee.pds.index') }}" class="flex items-center gap-3 p-3 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                    <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Update PDS</span>
                </a>
                <a href="{{ route('employee.leave-applications.index') }}" class="flex items-center gap-3 p-3 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                    <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 2v4M16 2v4M3 10h18M3 4h18a1 1 0 011 1v14a1 1 0 01-1 1H3a1 1 0 01-1-1V5a1 1 0 011-1z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">File Leave</span>
                </a>
                <a href="{{ route('employee.training.index') }}" class="flex items-center gap-3 p-3 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                    <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Training Records</span>
                </a>
                <a href="{{ route('employee.calendar') }}" class="flex items-center gap-3 p-3 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                    <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Calendar</span>
                </a>
            </div>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-neutral-800 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">My Requests</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Recent submissions</p>
                </div>
                <a href="{{ route('employee.leave-applications.index') }}" class="text-sm font-medium text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">View all</a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-neutral-800">
                @foreach([['type' => 'Leave', 'detail' => 'Vacation Leave — Dec 20–24', 'status' => 'approved', 'date' => 'Dec 1'], ['type' => 'Training', 'detail' => 'Leadership Workshop', 'status' => 'pending', 'date' => 'Nov 28'], ['type' => 'Leave', 'detail' => 'Sick Leave — Nov 15', 'status' => 'approved', 'date' => 'Nov 10']] as $item)
                <div class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                    <div class="w-9 h-9 rounded-md {{ $item['type'] === 'Leave' ? 'bg-[#013CFC]/[0.08]' : 'bg-amber-50 dark:bg-amber-900/30' }} flex items-center justify-center shrink-0">
                        @if($item['type'] === 'Leave')
                        <svg class="w-4 h-4 text-[#013CFC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @else
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item['detail'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Submitted {{ $item['date'] }}</p>
                    </div>
                    @if($item['status'] === 'approved')
                    <span class="inline-flex px-2 py-0.5 rounded-sm text-xs font-medium bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400">Approved</span>
                    @else
                    <span class="inline-flex px-2 py-0.5 rounded-sm text-xs font-medium bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400">Pending</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

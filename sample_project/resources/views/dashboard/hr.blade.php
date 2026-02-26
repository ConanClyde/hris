@extends('layouts.dashboard')

@section('navbarTitle', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">HR Dashboard</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Welcome, {{ session('first_name', session('user_id', 'User')) }}. Overview of HR operations and pending actions.</p>
    </div>

    @php
        $totalUsers = $totalUsers ?? 30;
        $pendingLeaveCount = $pendingLeaveCount ?? 0;
        $pendingTrainingCount = $pendingTrainingCount ?? 0;
        $pdsPendingCount = $pdsPendingCount ?? 0;
    @endphp
    {{-- KPI Cards (Leave, Training, PDS - features that exist) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pending Leave Applications</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $pendingLeaveCount }}</p>
            <div class="flex items-center gap-1.5 mt-2">
                <span class="text-sm font-medium text-amber-600 dark:text-amber-400">Review required</span>
                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                <a href="{{ route('hr.leave-applications.index') }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">View all →</a>
            </p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Training Pending Approval</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $pendingTrainingCount }}</p>
            <div class="flex items-center gap-1.5 mt-2">
                <span class="text-sm font-medium text-amber-600 dark:text-amber-400">Action required</span>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                <a href="{{ route('hr.training.index') }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">View all →</a>
            </p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">PDS Awaiting Review</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $pdsPendingCount }}</p>
            <div class="flex items-center gap-1.5 mt-2">
                <span class="text-sm font-medium text-green-600 dark:text-green-400">On track</span>
                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                <a href="{{ route('hr.pds.index') }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">PDS Management →</a>
            </p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $totalUsers }}</p>
            <div class="flex items-center gap-1.5 mt-2">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Active records</span>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                <a href="{{ route('hr.pds.index') }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">View list →</a>
            </p>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Leave Applications by Status</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">Monthly breakdown</p>
            <x-chart
                id="hr-leave-status"
                type="bar"
                :labels="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']"
                :datasets="[
                    ['name' => 'Approved', 'values' => [45, 52, 48, 61, 55, 42]],
                    ['name' => 'Pending', 'values' => [12, 8, 15, 9, 11, 12]],
                    ['name' => 'Rejected', 'values' => [2, 3, 1, 4, 2, 3]],
                ]"
                :colors="['#10b981', '#f59e0b', '#ef4444']"
                :height="260"
            />
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Training Status</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">Current period</p>
            <x-chart
                id="hr-training-status"
                type="percentage"
                :labels="['Approved', 'Pending', 'Rejected']"
                :datasets="[
                    ['name' => 'Records', 'values' => [38, 5, 2]],
                ]"
                :colors="['#013CFC', '#f59e0b', '#ef4444']"
                :height="260"
            />
        </div>
    </div>

    {{-- Quick actions & recent items --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-neutral-800">
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Quick Actions</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Frequent HR tasks</p>
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('hr.leave-applications.index') }}" class="flex items-center gap-3 p-3 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                    <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 2v4M16 2v4M3 10h18M3 4h18a1 1 0 011 1v14a1 1 0 01-1 1H3a1 1 0 01-1-1V5a1 1 0 011-1z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Leave Applications</span>
                </a>
                <a href="{{ route('hr.training.index') }}" class="flex items-center gap-3 p-3 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                    <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Learning & Development</span>
                </a>
                <a href="{{ route('hr.pds.index') }}" class="flex items-center gap-3 p-3 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                    <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">PDS Management</span>
                </a>
                <a href="{{ route('hr.reports') }}" class="flex items-center gap-3 p-3 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                    <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Reports & Analytics</span>
                </a>
            </div>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-neutral-800 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Pending Items</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Awaiting your action</p>
                </div>
                <a href="{{ route('hr.leave-applications.index') }}" class="text-sm font-medium text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">View all</a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-neutral-800">
                @foreach([['type' => 'Leave', 'name' => 'Maria Santos', 'detail' => 'Vacation Leave (5 days)', 'time' => '2 hours ago'], ['type' => 'Training', 'name' => 'Juan Dela Cruz', 'detail' => 'Leadership Workshop', 'time' => '5 hours ago'], ['type' => 'PDS', 'name' => 'Antonio Luna', 'detail' => 'PDS submission', 'time' => '1 day ago']] as $item)
                <div class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                    <div class="w-9 h-9 rounded-md {{ $item['type'] === 'Leave' ? 'bg-amber-50 dark:bg-amber-900/30' : ($item['type'] === 'Training' ? 'bg-[#013CFC]/[0.08]' : 'bg-green-50 dark:bg-green-900/30') }} flex items-center justify-center shrink-0">
                        @if($item['type'] === 'Leave')
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @elseif($item['type'] === 'Training')
                        <svg class="w-4 h-4 text-[#013CFC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        @else
                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item['name'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item['detail'] }} · {{ $item['time'] }}</p>
                    </div>
                    <span class="inline-flex px-2 py-0.5 rounded-sm text-xs font-medium bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400">Pending</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

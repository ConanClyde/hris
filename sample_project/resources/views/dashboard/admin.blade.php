@extends('layouts.dashboard')

@section('navbarTitle', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Admin Dashboard</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Welcome, {{ session('first_name', session('user_id', 'User')) }}. Organization-wide overview and system metrics.</p>
    </div>

    @php
        $totalUsers = $totalUsers ?? 30;
        $pendingCount = $pendingCount ?? 6;
        $usersByRole = $usersByRole ?? ['Admin' => 1, 'HR' => 15, 'Employee' => 14];
        $usersByStatus = $usersByStatus ?? ['active' => 18, 'pending' => 6, 'inactive' => 4, 'rejected' => 2];
    @endphp
    {{-- KPI Cards (from User Management data) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $totalUsers }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Admin, HR & Employee</p>
            <p class="text-xs mt-1">
                <a href="{{ route('admin.users') }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">Manage Users →</a>
            </p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pending User Approvals</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $pendingCount }}</p>
            <div class="flex items-center gap-1.5 mt-2">
                @if($pendingCount > 0)
                <span class="text-sm font-medium text-amber-600 dark:text-amber-400">Action required</span>
                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                @else
                <span class="text-sm font-medium text-green-600 dark:text-green-400">All clear</span>
                @endif
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                <a href="{{ route('admin.users', ['status' => 'pending']) }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">Review →</a>
            </p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Leave Overview</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">View</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Managed by HR</p>
            <p class="text-xs mt-1">
                <a href="{{ route('admin.leave') }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">View Leave →</a>
            </p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Activity Logs</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">Audit</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">System audit trail</p>
            <p class="text-xs mt-1">
                <a href="{{ route('admin.activity-logs.index') }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-[#60C8FC]">View Logs →</a>
            </p>
        </div>
    </div>

    {{-- Charts (Users by Role & Status - matches Manage Users data) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Users by Role</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">Admin, HR, and Employee distribution</p>
            <x-chart
                id="admin-users-by-role"
                type="percentage"
                :labels="['Admin', 'HR', 'Employee']"
                :datasets="[
                    ['name' => 'Users', 'values' => [$usersByRole['Admin'] ?? 1, $usersByRole['HR'] ?? 15, $usersByRole['Employee'] ?? 14]],
                ]"
                :colors="['#013CFC', '#0031BC', '#60C8FC']"
                :height="260"
            />
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">User Status</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">Active, pending, inactive, rejected</p>
            <x-chart
                id="admin-user-status"
                type="percentage"
                :labels="['Active', 'Pending', 'Inactive', 'Rejected']"
                :datasets="[
                    ['name' => 'Users', 'values' => [$usersByStatus['active'] ?? 18, $usersByStatus['pending'] ?? 6, $usersByStatus['inactive'] ?? 4, $usersByStatus['rejected'] ?? 2]],
                ]"
                :colors="['#10b981', '#f59e0b', '#6b7280', '#ef4444']"
                :height="260"
            />
        </div>
    </div>

    {{-- Quick actions & recent activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-neutral-800">
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Quick Actions</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Frequent tasks</p>
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 p-3 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                    <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Manage Users</span>
                </a>
                <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center gap-3 p-3 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                    <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Activity Logs</span>
                </a>
                <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 p-3 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                    <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Reports</span>
                </a>
                <a href="{{ route('admin.backup.index') }}" class="flex items-center gap-3 p-3 rounded-md border border-gray-200 dark:border-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                    <div class="w-10 h-10 rounded-md bg-[#013CFC]/[0.08] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#013CFC]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Backup</span>
                </a>
            </div>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-neutral-800">
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Recent Activity</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Latest system events</p>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-neutral-800">
                @foreach([['user' => 'Juan Dela Cruz', 'action' => 'User registered', 'time' => '2 min ago', 'status' => 'pending'], ['user' => 'Maria Santos', 'action' => 'PDS submitted', 'time' => '15 min ago', 'status' => 'success'], ['user' => 'Antonio Luna', 'action' => 'Leave approved', 'time' => '1 hour ago', 'status' => 'success'], ['user' => 'System', 'action' => 'Scheduled backup completed', 'time' => '3 hours ago', 'status' => 'success']] as $item)
                <div class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors">
                    <div class="w-9 h-9 rounded-md bg-gray-100 dark:bg-neutral-800 flex items-center justify-center shrink-0 text-sm font-medium text-gray-600 dark:text-gray-400">{{ strtoupper(substr($item['user'], 0, 1)) }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $item['action'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item['user'] }} · {{ $item['time'] }}</p>
                    </div>
                    @if($item['status'] === 'pending')
                    <span class="inline-flex px-2 py-0.5 rounded-sm text-xs font-medium bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400">Pending</span>
                    @else
                    <span class="inline-flex px-2 py-0.5 rounded-sm text-xs font-medium bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400">Done</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

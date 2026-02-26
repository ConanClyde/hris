@extends('layouts.dashboard')

@section('navbarTitle', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Admin Dashboard</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Welcome, {{ session('user_id') }}. Manage your HRIS from here.</p>
    </div>

    {{-- KPI Cards with trend indicators --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Employees</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">1,250</p>
                    <div class="flex items-center gap-1.5 mt-2">
                        <span class="text-sm font-medium text-green-600">+12.5%</span>
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18"/></svg>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Trending up this month</p>
                </div>
            </div>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">New Hires</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">42</p>
                    <div class="flex items-center gap-1.5 mt-2">
                        <span class="text-sm font-medium text-red-600">-20%</span>
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"/></svg>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Down 20% this period</p>
                </div>
            </div>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Present Today</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">1,142</p>
                    <div class="flex items-center gap-1.5 mt-2">
                        <span class="text-sm font-medium text-green-600">+12.5%</span>
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18"/></svg>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Strong attendance rate</p>
                </div>
            </div>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Growth Rate</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">4.5%</p>
                    <div class="flex items-center gap-1.5 mt-2">
                        <span class="text-sm font-medium text-green-600">+4.5%</span>
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18"/></svg>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Meets growth projections</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Attendance Trend — Line chart (2/3 width) --}}
        <div class="lg:col-span-2 rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-2">
                <div>
                    <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Attendance Trend</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Monthly average attendance rate</p>
                </div>
            </div>
            <x-chart
                id="attendance-trend"
                type="line"
                :labels="['Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb']"
                :datasets="[
                    ['name' => 'Present', 'values' => [1080, 1120, 1095, 1050, 1130, 1142]],
                    ['name' => 'Absent',  'values' => [170, 130, 155, 200, 120, 108]],
                ]"
                :colors="['#013CFC', '#e5e7eb']"
                :height="260"
            />
        </div>

        {{-- Department Distribution — Percentage chart (1/3 width) --}}
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <div class="mb-2">
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">By Department</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Employee distribution</p>
            </div>
            <x-chart
                id="dept-distribution"
                type="percentage"
                :labels="['Engineering', 'Operations', 'HR', 'Finance', 'Admin']"
                :datasets="[
                    ['name' => 'Employees', 'values' => [420, 310, 180, 210, 130]],
                ]"
                :colors="['#013CFC', '#0031BC', '#60C8FC', '#6b7280', '#d1d5db']"
                :height="260"
            />
        </div>
    </div>

    {{-- Tabbed table --}}
    <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 border-b border-gray-200 dark:border-neutral-800">
            <div class="flex gap-1 overflow-x-auto">
                <button type="button" class="px-4 py-2 rounded-md text-sm font-medium bg-gray-100 dark:bg-neutral-800 text-gray-900 dark:text-gray-100">Outline</button>
                <button type="button" class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-neutral-800">Past Performance (3)</button>
                <button type="button" class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-neutral-800">Key Personnel (2)</button>
                <button type="button" class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-neutral-800">Focus Documents</button>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <select class="rounded-md border border-gray-300 dark:border-neutral-700 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-900 focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                    <option>Customize Columns</option>
                </select>
                <button type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[#013CFC] text-white text-sm font-medium hover:bg-[#0031BC] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add Section
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-neutral-800 bg-gray-50/80 dark:bg-neutral-900/80">
                        <th class="w-10 px-4 py-3 text-left"></th>
                        <th class="w-10 px-4 py-3 text-left"></th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900 dark:text-gray-100">Header</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900 dark:text-gray-100">Section Type</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900 dark:text-gray-100">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900 dark:text-gray-100">Target</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900 dark:text-gray-100">Limit</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900 dark:text-gray-100">Reviewer</th>
                        <th class="w-10 px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100 dark:border-neutral-900 hover:bg-gray-50/50 dark:hover:bg-neutral-900/60">
                        <td class="px-4 py-3 text-gray-400 cursor-grab"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6h2v2H8V6zm0 5h2v2H8v-2zm0 5h2v2H8v-2zm5-10h2v2h-2V6zm0 5h2v2h-2v-2zm0 5h2v2h-2v-2z"/></svg></td>
                        <td class="px-4 py-3"><input type="checkbox" class="rounded-md border-gray-300 dark:border-neutral-600 text-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">Cover page</td>
                        <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-sm text-xs font-medium bg-gray-100 dark:bg-neutral-800 text-gray-700 dark:text-gray-300">Cover page</span></td>
                        <td class="px-4 py-3"><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-sm text-xs font-medium bg-gray-100 dark:bg-neutral-800 text-gray-700 dark:text-gray-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-500 dark:bg-gray-400"></span>In Process</span></td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">—</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">—</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">—</td>
                        <td class="px-4 py-3"><button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1 rounded-md"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg></button></td>
                    </tr>
                    <tr class="border-b border-gray-100 dark:border-neutral-900 hover:bg-gray-50/50 dark:hover:bg-neutral-900/60">
                        <td class="px-4 py-3 text-gray-400 cursor-grab"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6h2v2H8V6zm0 5h2v2H8v-2zm0 5h2v2H8v-2zm5-10h2v2h-2V6zm0 5h2v2h-2v-2zm0 5h2v2h-2v-2z"/></svg></td>
                        <td class="px-4 py-3"><input type="checkbox" class="rounded-md border-gray-300 dark:border-neutral-600 text-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">Table of contents</td>
                        <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-sm text-xs font-medium bg-gray-100 dark:bg-neutral-800 text-gray-700 dark:text-gray-300">Table of contents</span></td>
                        <td class="px-4 py-3"><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-sm text-xs font-medium bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Done</span></td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">—</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">—</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">Eddie Lake</td>
                        <td class="px-4 py-3"><button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1 rounded-md"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg></button></td>
                    </tr>
                    <tr class="border-b border-gray-100 dark:border-neutral-900 hover:bg-gray-50/50 dark:hover:bg-neutral-900/60">
                        <td class="px-4 py-3 text-gray-400 cursor-grab"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6h2v2H8V6zm0 5h2v2H8v-2zm0 5h2v2H8v-2zm5-10h2v2h-2V6zm0 5h2v2h-2v-2zm0 5h2v2h-2v-2z"/></svg></td>
                        <td class="px-4 py-3"><input type="checkbox" class="rounded-md border-gray-300 dark:border-neutral-600 text-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">Executive summary</td>
                        <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-sm text-xs font-medium bg-gray-100 dark:bg-neutral-800 text-gray-700 dark:text-gray-300">Narrative</span></td>
                        <td class="px-4 py-3"><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-sm text-xs font-medium bg-gray-100 dark:bg-neutral-800 text-gray-700 dark:text-gray-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-500 dark:bg-gray-400"></span>In Process</span></td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">—</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">—</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">Jamik Tashpulatov</td>
                        <td class="px-4 py-3"><button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1 rounded-md"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg></button></td>
                    </tr>
                    <tr class="border-b border-gray-100 dark:border-neutral-900 hover:bg-gray-50/50 dark:hover:bg-neutral-900/60">
                        <td class="px-4 py-3 text-gray-400 cursor-grab"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6h2v2H8V6zm0 5h2v2H8v-2zm0 5h2v2H8v-2zm5-10h2v2h-2V6zm0 5h2v2h-2v-2zm0 5h2v2h-2v-2z"/></svg></td>
                        <td class="px-4 py-3"><input type="checkbox" class="rounded-md border-gray-300 dark:border-neutral-600 text-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">Technical approach</td>
                        <td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-sm text-xs font-medium bg-gray-100 dark:bg-neutral-800 text-gray-700 dark:text-gray-300">Narrative</span></td>
                        <td class="px-4 py-3"><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-sm text-xs font-medium bg-gray-100 dark:bg-neutral-800 text-gray-700 dark:text-gray-300"><span class="w-1.5 h-1.5 rounded-full bg-gray-500 dark:bg-gray-400"></span>In Process</span></td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">—</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">—</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">—</td>
                        <td class="px-4 py-3"><button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1 rounded-md"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg></button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

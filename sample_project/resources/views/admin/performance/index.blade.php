@extends('layouts.dashboard')

@section('navbarTitle', 'Performance Metrics')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Performance Metrics</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Client-side Web Vitals + Navigation Timing beacons.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="border border-gray-200 dark:border-neutral-800 rounded-md bg-white dark:bg-neutral-900 p-4 shadow-sm">
        <form method="GET" class="flex flex-col md:flex-row gap-4 items-center">
            <!-- Route Filter -->
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/>
                    </svg>
                </div>
                <input type="text" name="route" value="{{ request('route') }}"
                       class="block w-full h-9 pl-10 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2"
                       placeholder="Route contains...">
            </div>

            <!-- Role Filter -->
            <select name="role" onchange="this.form.submit()" class="block w-full md:w-48 h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="hr" {{ request('role') === 'hr' ? 'selected' : '' }}>HR</option>
                <option value="employee" {{ request('role') === 'employee' ? 'selected' : '' }}>Employee</option>
            </select>

            <!-- Budget Filter -->
            <select name="budget_exceeded" onchange="this.form.submit()" class="block w-full md:w-48 h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2">
                <option value="">All</option>
                <option value="1" {{ request('budget_exceeded') === '1' ? 'selected' : '' }}>Budget exceeded</option>
                <option value="0" {{ request('budget_exceeded') === '0' ? 'selected' : '' }}>Within budget</option>
            </select>

            <!-- Clear Button -->
            @php
                $filtersActive = request('route') || request('role') || request('budget_exceeded');
            @endphp
            @if($filtersActive)
                <a href="{{ route('admin.performance.index') }}" class="whitespace-nowrap inline-flex items-center justify-center h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-neutral-800 transition shadow-sm">
                    Clear Filters
                </a>
            @else
                <button type="button" disabled class="whitespace-nowrap inline-flex items-center justify-center h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-400 dark:text-gray-500 rounded-md text-sm font-medium cursor-not-allowed">
                    Clear Filters
                </button>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 rounded-md shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800">
                <thead class="bg-gray-50 dark:bg-neutral-950">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Route</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">TTFB</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">FCP</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">LCP</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">CLS</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nav</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Budget</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-neutral-800">
                    @forelse($metrics as $m)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors cursor-pointer" onclick="openViewModal({{ json_encode($m) }})">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $m->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $m->route }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px] xl:max-w-[320px]">{{ $m->user_agent }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $m->role ?? '-' }} / {{ $m->user_id ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $m->ttfb ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $m->fcp ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $m->lcp ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $m->cls ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $m->nav_transition_ms ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($m->budget_exceeded)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        Exceeded
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        OK
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button type="button" onclick="event.stopPropagation(); openViewModal({{ json_encode($m) }})" class="text-gray-400 hover:text-[#013CFC] dark:hover:text-blue-400 transition-colors" title="View Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-md bg-gray-100 dark:bg-neutral-800 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">No metrics yet</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Performance data will appear here once collected.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $metrics->links() }}
    </div>
</div>

<!-- View Performance Metric Modal -->
<div id="view-metric-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('view-metric-modal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-2xl rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 flex flex-col max-h-[80vh] pointer-events-auto">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex items-center justify-between shrink-0">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Performance Metric Details</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300" onclick="closeModal('view-metric-modal')">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="flex-1 min-h-0 overflow-y-auto p-6">
                <div class="space-y-6">
                    {{-- Basic Info --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Time</p>
                            <p id="view-time" class="text-sm font-medium text-gray-900 dark:text-gray-100"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">User</p>
                            <p id="view-user" class="text-sm font-medium text-gray-900 dark:text-gray-100"></p>
                        </div>
                    </div>

                    {{-- Route --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Route</p>
                        <p id="view-route" class="text-sm font-medium text-gray-900 dark:text-gray-100"></p>
                    </div>

                    {{-- Web Vitals --}}
                    <div class="border-t border-gray-200 dark:border-neutral-800 pt-4">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Web Vitals</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div class="p-3 rounded-md bg-gray-50 dark:bg-neutral-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">TTFB</p>
                                <p id="view-ttfb" class="text-sm font-semibold text-gray-900 dark:text-gray-100"></p>
                            </div>
                            <div class="p-3 rounded-md bg-gray-50 dark:bg-neutral-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">FCP</p>
                                <p id="view-fcp" class="text-sm font-semibold text-gray-900 dark:text-gray-100"></p>
                            </div>
                            <div class="p-3 rounded-md bg-gray-50 dark:bg-neutral-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">LCP</p>
                                <p id="view-lcp" class="text-sm font-semibold text-gray-900 dark:text-gray-100"></p>
                            </div>
                            <div class="p-3 rounded-md bg-gray-50 dark:bg-neutral-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">CLS</p>
                                <p id="view-cls" class="text-sm font-semibold text-gray-900 dark:text-gray-100"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Navigation & Budget --}}
                    <div class="border-t border-gray-200 dark:border-neutral-800 pt-4 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Navigation Time</p>
                            <p id="view-nav" class="text-sm font-medium text-gray-900 dark:text-gray-100"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Budget Status</p>
                            <div id="view-budget"></div>
                        </div>
                    </div>

                    {{-- User Agent --}}
                    <div class="border-t border-gray-200 dark:border-neutral-800 pt-4">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">User Agent</p>
                        <p id="view-user-agent" class="text-sm text-gray-700 dark:text-gray-300 break-all font-mono"></p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                <button type="button" class="h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-neutral-800 transition shadow-sm" onclick="closeModal('view-metric-modal')">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openViewModal(metric) {
        document.getElementById('view-time').textContent = metric.created_at;
        document.getElementById('view-user').textContent = (metric.role || '-') + ' / ' + (metric.user_id || '-');
        document.getElementById('view-route').textContent = metric.route;
        document.getElementById('view-ttfb').textContent = metric.ttfb || '-';
        document.getElementById('view-fcp').textContent = metric.fcp || '-';
        document.getElementById('view-lcp').textContent = metric.lcp || '-';
        document.getElementById('view-cls').textContent = metric.cls || '-';
        document.getElementById('view-nav').textContent = metric.nav_transition_ms || '-';
        document.getElementById('view-user-agent').textContent = metric.user_agent || '-';

        const budgetEl = document.getElementById('view-budget');
        if (metric.budget_exceeded) {
            budgetEl.innerHTML = '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Exceeded</span>';
        } else {
            budgetEl.innerHTML = '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">OK</span>';
        }

        document.getElementById('view-metric-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>
@endsection

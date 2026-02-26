@extends('layouts.dashboard')

@section('navbarTitle', 'Calendar')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Calendar</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage events and schedules</p>
        </div>

        {{-- Filters & Legend --}}
        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            {{-- Add Holiday Button --}}
            {{-- Add Holiday Button --}}
            <button onclick="openModal()" class="inline-flex items-center gap-2 h-9 px-3 rounded-md bg-[#013CFC] text-white text-sm font-medium hover:bg-[#013CFC]/90 transition-colors shadow-sm">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Add Holiday
            </button>

            {{-- Category Filter --}}
            <select id="category-filter" class="h-9 px-3 rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 text-sm focus:ring-2 focus:ring-[#013CFC] focus:outline-none">
                <option value="all">All Events</option>
                <option value="leave">Leaves Only</option>
                <option value="training">Training Only</option>
                <option value="holiday">Holidays Only</option>
            </select>

            {{-- Status Filter (Leave only) --}}
            <select id="status-filter" class="hidden h-9 px-3 rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 text-sm focus:ring-2 focus:ring-[#013CFC] focus:outline-none">
                <option value="all">All Status</option>
                <option value="approved">Approved</option>
                <option value="pending">Pending</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Left Column: Main Calendar (8/12) --}}
        <div id="calendar-view-content" class="lg:col-span-8">
            <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm h-[750px] flex flex-col overflow-hidden">
                {{-- Custom Calendar Header (Shadcn Style) --}}
                <div class="p-4 border-b border-gray-200 dark:border-neutral-800 grid grid-cols-1 md:grid-cols-3 items-center gap-4 shrink-0">
                    <!-- Left: Navigation (Center on mobile, Start on desktop) -->
                    <div class="flex items-center gap-2 justify-self-center md:justify-self-start">
                        <div class="inline-flex rounded-md border border-gray-200 dark:border-neutral-700 shadow-sm bg-white dark:bg-neutral-900">
                            <button id="cal-prev" class="h-10 w-10 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors border-r border-gray-200 dark:border-neutral-700 rounded-l-md">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                            </button>
                            <button id="cal-next" class="h-10 w-10 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors rounded-r-md">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </button>
                        </div>
                        <button id="cal-today" class="h-10 px-4 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors shadow-sm">
                            Today
                        </button>
                    </div>

                    <!-- Center: Title -->
                    <h2 id="cal-title" class="text-xl font-bold text-gray-900 dark:text-gray-100 uppercase tracking-tight justify-self-center"></h2>

                    <!-- Right: View Switcher (Center on mobile, End on desktop) -->
                    <div class="flex items-center gap-2 justify-self-center md:justify-self-end">
                        <div class="inline-flex rounded-md border border-gray-200 dark:border-neutral-700 shadow-sm bg-white dark:bg-neutral-900">
                            <button onclick="changeCalView('multiMonthYear')" id="view-year" class="view-btn px-5 py-2.5 text-sm font-medium transition-colors text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-neutral-800 border-r border-gray-200 dark:border-neutral-700 rounded-l-md">Year</button>
                            <button onclick="changeCalView('dayGridMonth')" id="view-month" class="view-btn px-5 py-2.5 text-sm font-medium transition-colors bg-gray-100 dark:bg-neutral-700 text-gray-900 dark:text-gray-100 border-r border-gray-200 dark:border-neutral-700">Month</button>
                            <button onclick="changeCalView('timeGridWeek')" id="view-week" class="view-btn px-5 py-2.5 text-sm font-medium transition-colors text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-neutral-800 border-r border-gray-200 dark:border-neutral-700">Week</button>
                            <button onclick="changeCalView('listMonth')" id="view-list" class="view-btn px-5 py-2.5 text-sm font-medium transition-colors text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-neutral-800 rounded-r-md">List</button>
                        </div>
                    </div>
                </div>

                <div class="flex-1 p-6 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-100 dark:scrollbar-thumb-neutral-800">
                    <div id="error-banner" class="hidden mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-900/30 text-red-600 dark:text-red-400 text-sm rounded-md flex items-center gap-2">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Failed to load calendar events. Please try again later.
                    </div>
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        {{-- Right Column: Today's Events (4/12) --}}
        <div class="lg:col-span-4 space-y-4">
            <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm p-4 overflow-hidden flex flex-col h-[750px]">
                <div class="flex items-center justify-between mb-4 shrink-0">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-tight">Custom Holidays</h3>
                </div>

                <div id="custom-holidays-list" class="flex-1 overflow-y-auto space-y-3 pr-2 scrollbar-thin scrollbar-thumb-gray-200 dark:scrollbar-thumb-neutral-800">
                    @forelse($customHolidays as $holiday)
                        <div onclick="previewHoliday({{ $holiday->id }}, '{{ addslashes($holiday->title) }}', '{{ $holiday->date->format('Y-m-d') }}', '{{ $holiday->category }}', '{{ addslashes($holiday->description) }}')"
                             class="p-3 rounded-md border border-gray-100 dark:border-neutral-700 bg-white dark:bg-neutral-800 hover:border-[#013CFC]/30 transition transition-all duration-200 shadow-sm group relative cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-md bg-gray-50 dark:bg-neutral-900 border border-gray-100 dark:border-neutral-800 flex flex-col items-center justify-center transition-colors group-hover:bg-[#013CFC]/5 group-hover:border-[#013CFC]/20">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase leading-none">{{ $holiday->date->format('M') }}</span>
                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-200 leading-tight">{{ $holiday->date->format('d') }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-semibold text-gray-900 dark:text-gray-100 truncate group-hover:text-[#013CFC] transition-colors">{{ $holiday->title }}</h4>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        {{-- Force Red Color for all Holidays --}}
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                            {{ ucfirst($holiday->category) }}
                                        </span>
                                        @if($holiday->is_recurring)
                                            <span class="text-[10px] text-gray-400 flex items-center gap-0.5">
                                                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M3 21v-5h5"/></svg>
                                                Yearly
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                {{-- Quick Actions --}}
                                <div class="flex items-center gap-1.5" onclick="event.stopPropagation()">
                                    {{-- Preview/View --}}
                                    <button onclick="previewHoliday({{ $holiday->id }}, '{{ addslashes($holiday->title) }}', '{{ $holiday->date->format('Y-m-d') }}', '{{ $holiday->category }}', '{{ addslashes($holiday->description) }}')"
                                            class="p-1.5 text-gray-500 dark:text-gray-400 hover:text-[#013CFC] dark:hover:text-[#60C8FC] hover:bg-[#013CFC]/5 dark:hover:bg-[#013CFC]/10 rounded-md transition-all duration-200" title="View Details">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>

                                    {{-- Edit --}}
                                    <button onclick="editHoliday({{ $holiday->id }}, '{{ addslashes($holiday->title) }}', '{{ $holiday->date->format('Y-m-d') }}', '{{ $holiday->category }}', '{{ addslashes($holiday->description) }}', {{ $holiday->is_recurring }})"
                                            class="p-1.5 text-gray-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10 rounded-md transition-all duration-200" title="Edit">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                    </button>

                                    {{-- Delete --}}
                                    <button type="button"
                                            onclick="openDeleteHolidayModal('{{ route('admin.custom-holidays.destroy', $holiday->id) }}', '{{ addslashes($holiday->title) }}')"
                                            class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-md transition-all duration-200" title="Delete">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-12 h-12 rounded-md bg-gray-100 dark:bg-neutral-800 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">No custom holidays added</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Add your first one to get started.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modals --}}
{{-- Date Events List Modal --}}
<div id="date-events-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 transition-opacity" onclick="closeModal('date-events-modal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-2xl rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 translate-y-0 duration-200 flex flex-col max-h-[80vh] pointer-events-auto">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex items-center justify-between shrink-0">
                <h3 id="date-modal-title" class="text-base font-semibold text-gray-900 dark:text-gray-100">Events</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300" onclick="closeModal('date-events-modal')">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="flex-1 min-h-0 overflow-y-auto p-4">
                <div id="date-events-list" class="space-y-3">
                    <!-- Events will be populated here -->
                </div>
                <div id="date-events-empty" class="hidden flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-12 h-12 rounded-md bg-gray-100 dark:bg-neutral-800 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">No events for this day</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Select another date to view events.</p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                <button type="button" class="h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-neutral-800 transition shadow-sm" onclick="closeModal('date-events-modal')">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Legend Modal REMOVED - Admin only shows holidays --}}

{{-- Event Details Modal --}}
<div id="event-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 transition-opacity" onclick="closeModal('event-modal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-4xl rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 translate-y-0 duration-200 flex flex-col max-h-[90vh] pointer-events-auto">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex items-center justify-between shrink-0">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Event Details</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300" onclick="closeModal('event-modal')">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="flex-1 min-h-0 overflow-y-auto p-6 space-y-4">
                <h2 id="modal-event-title" class="text-lg font-bold text-gray-900 dark:text-gray-100"></h2>
                <div class="grid grid-cols-2 gap-6 text-sm">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Date Range</p>
                        <p id="modal-event-date" class="font-medium text-gray-900 dark:text-gray-100"></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Category</p>
                        <p id="modal-event-category" class="font-medium"></p>
                    </div>
                    <div id="modal-status-container">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Status</p>
                        <div id="modal-event-status"></div>
                    </div>
                </div>
                <div class="border-t border-gray-200 dark:border-neutral-800 pt-4">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Description</p>
                    <p id="modal-event-desc" class="text-sm text-gray-700 dark:text-gray-300"></p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                <button type="button" class="h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-neutral-800 transition shadow-sm" onclick="closeModal('event-modal')">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- Manage Holidays Modal --}}
<div id="holiday-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 transition-opacity" onclick="closeModal('holiday-modal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-2xl rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 flex flex-col pointer-events-auto max-h-[90vh]">
            <form id="holiday-form" action="{{ route('admin.custom-holidays.store') }}" method="POST" class="flex flex-col flex-1 min-h-0">
                @csrf
                <div id="method-spoof"></div>

                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex items-center justify-between shrink-0">
                    <h3 id="holiday-modal-title" class="text-base font-semibold text-gray-900 dark:text-gray-100">Manage Custom Holidays</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors" onclick="closeModal('holiday-modal')">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="flex-1 min-h-0 overflow-y-auto p-6 scrollbar-thin scrollbar-thumb-gray-200 dark:scrollbar-thumb-neutral-800 space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                        <input type="text" name="title" id="title" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                            <input type="date" name="date" id="date" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950 dark:[color-scheme:dark]">
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                            <select name="category" id="category" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                                <option value="local">Local Holiday</option>
                                <option value="special">Special Non-Working</option>
                                <option value="regular">Regular Holiday</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea name="description" id="description" rows="3" class="w-full rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></textarea>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" name="is_recurring" id="is_recurring" value="1" class="rounded border-gray-300 dark:border-neutral-700 text-[#013CFC] accent-[#013CFC] shadow-sm focus:ring-[#013CFC] h-4 w-4">
                        <label for="is_recurring" class="text-sm text-gray-700 dark:text-gray-300 select-none cursor-pointer">Recurring annually</label>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                    <button type="button" onclick="closeModal('holiday-modal')" class="px-4 py-2 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-neutral-800 transition shadow-sm">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#013CFC] text-white rounded-md text-sm font-medium hover:bg-[#013CFC]/90 transition shadow-sm">Save Holiday</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- FullCalendar (loaded only on calendar pages) --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
    window.openModal = function(id) {
        // If no ID or 'holiday-modal', treat as Add Holiday
        if (!id || id === 'holiday-modal') {
            const modal = document.getElementById('holiday-modal');
            if (modal) {
                modal.classList.remove('hidden');
                document.getElementById('holiday-modal-title').innerText = 'Add Custom Holiday';
                const form = document.getElementById('holiday-form');
                if (form) {
                    form.action = "{{ route('admin.custom-holidays.store') }}";
                    document.getElementById('method-spoof').innerHTML = '';
                    form.reset();
                }
            }
        } else {
            const modal = document.getElementById(id);
            if (modal) modal.classList.remove('hidden');
        }
        document.body.style.overflow = 'hidden';
    };

    // Reuse window.closeModal logic if possible, but distinct id ensures clarity

    function editHoliday(id, title, date, category, description, isRecurring) {
        document.getElementById('holiday-modal').classList.remove('hidden');
        document.getElementById('holiday-modal-title').innerText = 'Edit Custom Holiday';

        let form = document.getElementById('holiday-form');
         // Construct the update route manually since we're in JS
        form.action = "{{ route('admin.custom-holidays.index') }}/" + id;

        document.getElementById('method-spoof').innerHTML = '@method("PUT")';

        document.getElementById('title').value = title;
        document.getElementById('date').value = date;
        document.getElementById('category').value = category;
        document.getElementById('description').value = description;
        document.getElementById('is_recurring').checked = isRecurring == 1;
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let calendar;
        let currentCategory = 'all';
        let currentStatus = 'all';

        const calendarEl = document.getElementById('calendar');
        const categoryFilter = document.getElementById('category-filter');
        const statusFilter = document.getElementById('status-filter');
        const legendBtn = document.getElementById('legend-btn');
        const legendPanel = document.getElementById('legend-panel');


        // Initial side panel date - removed logic since we use Static Custom Holidays List now
        // document.getElementById('sidebar-today-date').innerText = new Date().toLocaleDateString('default', { month: 'short', day: 'numeric', year: 'numeric' });

        // Legend Dropdown logic removed (Legend moved to sidebar)

        function getClassForStatus(event) {
            const props = event.extendedProps || event;
            if (!props) return 'bg-[#013CFC]';

            const category = (props.category || '').toLowerCase();
            const status = (props.status || '').toLowerCase();

            // Prioritize Category Colors
            if (category === 'holiday') return 'bg-red-500'; // Holiday = Red
            if (category.includes('training')) return 'bg-blue-600'; // Training = Blue

            // Fallback to Status Colors (mainly for Leave)
            if (status === 'approved') return 'bg-emerald-500';
            if (status === 'pending') return 'bg-amber-500';
            if (status === 'rejected') return 'bg-red-500';

            return 'bg-[#013CFC]'; // default
        }


        function initCalendar() {
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: false, // Disable default toolbar
                events: function(fetchInfo, successCallback, failureCallback) {
                    const url = new URL('{{ route("admin.calendar.events") }}', window.location.origin);
                    url.searchParams.append('start', fetchInfo.startStr);
                    url.searchParams.append('end', fetchInfo.endStr);
                    url.searchParams.append('category', currentCategory);
                    url.searchParams.append('status', currentStatus);

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('error-banner').classList.add('hidden');
                            successCallback(data);
                            // updateAgendaList(data); // Removed agenda list update
                        })
                        .catch(err => {
                            console.error('Fetch error:', err);
                            document.getElementById('error-banner').classList.remove('hidden');
                            failureCallback(err);
                        });
                },
                dateClick: function(info) {
                    showDateEvents(info.dateStr);
                },
                eventClick: function(info) {
                    showEventModal(info.event);
                },
                datesSet: function(dateInfo) {
                    // Update custom title when date set changes
                    document.getElementById('cal-title').innerText = dateInfo.view.title;
                },
                height: '100%', // Default to 100% for Month view
                fixedWeekCount: false,
                themeSystem: 'standard'
            });
            calendar.render();

            // Custom Navigation Listeners
            document.getElementById('cal-prev').addEventListener('click', () => calendar.prev());
            document.getElementById('cal-next').addEventListener('click', () => calendar.next());
            document.getElementById('cal-today').addEventListener('click', () => calendar.today());

            // ... view switcher logic ...

            // Date Click Modal Logic
            window.showDateEvents = function(dateStr) {
                // Parse date for title
                const dateObj = new Date(dateStr);
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                document.getElementById('date-modal-title').innerText = dateObj.toLocaleDateString(undefined, options);

                // Get all events
                const allEvents = calendar.getEvents();
                // Filter events strictly for this day (handling timezones by comparing ISO date part)
                const daysEvents = allEvents.filter(event => {
                    // Check if clicked date is within event range
                    // For single day events: start date matches
                    // For multi-day: start <= clicked < end
                    const clickedDate = new Date(dateStr);
                    const eventStart = event.start;
                    const eventEnd = event.end || event.start; // Fallback for single day

                    // Strip times for comparison
                    const clickedTime = clickedDate.setHours(0,0,0,0);
                    const startTime = new Date(eventStart).setHours(0,0,0,0);

                    // Simple check for now: events starting on this day
                    // FullCalendar's internal storage makes strict range comparison tricky without moment/plugins
                    // So we'll match start date string YYYY-MM-DD
                    return event.startStr.startsWith(dateStr);
                });

                const listCont = document.getElementById('date-events-list');
                const emptyCont = document.getElementById('date-events-empty');
                listCont.innerHTML = '';

                if (daysEvents.length === 0) {
                    emptyCont.classList.remove('hidden');
                } else {
                    emptyCont.classList.add('hidden');
                    daysEvents.forEach(event => {
                        const props = event.extendedProps;
                        const card = document.createElement('div');
                        card.className = 'p-3 rounded-md border border-gray-100 dark:border-neutral-700 bg-white dark:bg-neutral-800 hover:border-[#013CFC]/30 transition transition-all duration-200 cursor-pointer shadow-sm group flex items-center gap-3';
                        card.onclick = () => {
                            closeModal('date-events-modal');
                            showEventModal(event);
                        };

                        // Determine Status Text and Color
                        let statusText = props.status;
                        let statusColorClass = props.status === 'approved' ? 'text-emerald-500' : 'text-amber-500';

                        const category = (props.category || '').toLowerCase();
                        const isHoliday = category.includes('holiday') || category === 'regular' || category === 'special' || category === 'local';

                        if (isHoliday) {
                            statusText = 'Official';
                            statusColorClass = 'text-red-500'; // Holidays are usually red
                        }

                        card.innerHTML = `
                            <div class="w-1.5 self-stretch rounded-full ${getClassForStatus(event)}"></div>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate group-hover:text-[#013CFC] transition-colors">${event.title}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-gray-500 uppercase tracking-wide">${props.category || 'Event'}</span>
                                    ${statusText ? `<span class="px-1.5 py-0.5 rounded-full bg-gray-100 dark:bg-neutral-800 text-[10px] font-semibold ${statusColorClass} capitalize">${statusText}</span>` : ''}
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 group-hover:text-[#013CFC] dark:group-hover:text-[#60C8FC] transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        `;
                        listCont.appendChild(card);
                    });
                }

                document.getElementById('date-events-modal').classList.remove('hidden');
            };



            window.changeCalView = function(viewName) {
                calendar.changeView(viewName);

                // Adjust height based on view: 100% for Month to fill container, auto for scrolling views
                if (viewName === 'dayGridMonth') {
                    calendar.setOption('height', '100%');
                } else {
                    calendar.setOption('height', 'auto');
                }

                // Update button styles
                document.querySelectorAll('.view-btn').forEach(btn => {
                    btn.classList.remove('bg-gray-100', 'dark:bg-neutral-700', 'text-gray-900', 'dark:text-gray-100');
                    btn.classList.add('text-gray-600', 'dark:text-gray-300', 'hover:bg-gray-50', 'dark:hover:bg-neutral-800');
                });

                let btnId = 'view-month';
                if (viewName.includes('Year')) btnId = 'view-year';
                if (viewName.includes('Week')) btnId = 'view-week';
                if (viewName.includes('list')) btnId = 'view-list';

                const activeBtn = document.getElementById(btnId);
                if (activeBtn) {
                    activeBtn.classList.remove('text-gray-600', 'dark:text-gray-300', 'hover:bg-gray-50', 'dark:hover:bg-neutral-800');
                    activeBtn.classList.add('bg-gray-100', 'dark:bg-neutral-700', 'text-gray-900', 'dark:text-gray-100');
                }
            };

            // Fix layout on sidebar toggle
            window.addEventListener('sidebar-toggled', () => {
                if (calendar) {
                    setTimeout(() => {
                        calendar.updateSize();
                    }, 200); // Wait for transition
                }
            });

            window.addEventListener('realtime:calendar-holidays-updated', function () {
                if (calendar) {
                    calendar.refetchEvents();
                }
            });

            window.addEventListener('realtime:leave-status-updated', function () {
                if (calendar) {
                    calendar.refetchEvents();
                }
            });

            window.addEventListener('realtime:training-status-updated', function () {
                if (calendar) {
                    calendar.refetchEvents();
                }
            });
        }

        function showEventModal(event) {
            const props = event.extendedProps || event;
            document.getElementById('modal-event-title').innerText = event.title;
            // Safely handle event.start (can be Date object or string)
            const startDate = event.start instanceof Date ? event.start : new Date(event.start);
            document.getElementById('modal-event-date').innerText = startDate.toDateString();

            // Category with Color Dot
            const catContainer = document.getElementById('modal-event-category');
            catContainer.innerHTML = ''; // Clear previous

            const dot = document.createElement('span');
            // Use simple string concatenation to avoid potential SyntaxError
            const statusClass = getClassForStatus(event);
            dot.className = 'inline-block w-2.5 h-2.5 rounded-full mr-2 ' + statusClass;

            const text = document.createTextNode((props.category || 'Event').toUpperCase());

            catContainer.appendChild(dot);
            catContainer.appendChild(text);

            const statusBadge = document.getElementById('modal-event-status');
            const statusCont = document.getElementById('modal-status-container');

            if (props.status) {
                statusCont.classList.remove('hidden');

                const category = (props.category || '').toLowerCase();
                const isHoliday = category.includes('holiday') || category === 'regular' || category === 'special' || category === 'local';

                if (isHoliday) {
                    statusBadge.innerText = 'OFFICIAL';
                    statusBadge.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-500/30 dark:text-red-400';
                } else {
                    statusBadge.innerText = props.status.toUpperCase();
                    statusBadge.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold ' +
                        (props.status === 'approved'
                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/30 dark:text-emerald-400'
                            : 'bg-amber-100 text-amber-800 dark:bg-amber-500/30 dark:text-amber-400');
                }
            } else {
                statusCont.classList.add('hidden');
            }

            document.getElementById('modal-event-desc').innerText = props.description || 'No additional description.';

            document.getElementById('event-modal').classList.remove('hidden');
        }

        window.closeModal = function(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.add('hidden');

            // Check if any other modals are open before clearing overflow
            const openModals = document.querySelectorAll('[role="dialog"]:not(.hidden)');
            if (openModals.length === 0) {
                document.body.style.overflow = '';
            }
        };

        window.previewHoliday = function(id, title, dateStr, category, description) {
            const event = {
                title: title,
                start: new Date(dateStr),
                extendedProps: {
                    category: category,
                    status: 'approved',
                    description: description
                }
            };
            showEventModal(event);
        };

        window.openDeleteHolidayModal = function(actionUrl, holidayTitle) {
            const form = document.getElementById('delete-holiday-form');
            if (form) form.action = actionUrl;
            const titleEl = document.getElementById('delete-holiday-title');
            if (titleEl) titleEl.innerText = holidayTitle;
            openModal('delete-holiday-modal');
        };

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                ['add-holiday-modal', 'preview-holiday-modal', 'delete-holiday-modal', 'event-modal'].forEach(id => {
                    const modal = document.getElementById(id);
                    if (modal && !modal.classList.contains('hidden')) closeModal(id);
                });
            }
        });

        categoryFilter.addEventListener('change', function() {
            currentCategory = this.value;
            if (currentCategory === 'leave') {
                statusFilter.classList.remove('hidden');
            } else {
                statusFilter.classList.add('hidden');
                currentStatus = 'all';
                statusFilter.value = 'all';
            }
            calendar.refetchEvents();
        });

        statusFilter.addEventListener('change', function() {
            currentStatus = this.value;
            calendar.refetchEvents();
        });

        // updateAgendaList(eventsData) function removed since we rely on Blade loop for Custom Holidays

        initCalendar();
    });
</script>

<style>
    /* Shadcn-inspired FullCalendar Styling */
    :root {
        --fc-border-color: #f1f5f9; /* Slate 100 */
        --fc-daygrid-event-dot-width: 8px;
    }

    .dark {
        --fc-border-color: #404040; /* Neutral 700 - better visibility */
        --fc-page-bg-color: transparent;
        --fc-neutral-bg-color: #0a0a0a;
    }

    /* Grid & Borders */
    .fc-theme-standard .fc-scrollgrid { border: none !important; }
    .fc-theme-standard td, .fc-theme-standard th { border: 1px solid var(--fc-border-color) !important; }

    /* Make non-month days (other month) white instead of gray */
    .fc .fc-day-other,
    .fc .fc-day-disabled,
    .fc .fc-daygrid-day-other {
        background-color: transparent !important;
        background: none !important;
    }
    .dark .fc .fc-day-other,
    .dark .fc .fc-day-disabled,
    .dark .fc .fc-daygrid-day-other {
        background-color: transparent !important;
        background: none !important;
    }

    /* Day Headers */
    .fc .fc-col-header-cell { padding: 12px 0; background: transparent; }
    .fc .fc-col-header-cell-cushion {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8; /* Slate 400 - light mode */
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-decoration: none !important;
    }

    /* Day Number Styling */
    .fc .fc-daygrid-day-number {
        font-size: 12px;
        font-weight: 500;
        color: #64748b; /* Slate 500 */
        padding: 8px 12px !important;
        text-decoration: none !important;
    }

    .dark .fc .fc-daygrid-day-number { color: #a3a3a3; } /* Neutral 400 - better contrast */

    /* Remove default yellow highlight from specific classes just in case */
    .fc .fc-day-today {
        background-color: transparent !important;
    }

    /* Today Highlight - Shadcn Style (Solid Circle) */
    .fc .fc-day-today .fc-daygrid-day-number {
        position: relative;
        color: #ffffff !important;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        margin: 4px !important;
        padding: 0 !important;
    }
    .fc .fc-day-today .fc-daygrid-day-number::before {
        content: '';
        position: absolute;
        inset: 0;
        background-color: #0a0a0a; /* Neutral 950 */
        border-radius: 9999px;
        z-index: -1;
    }

    .dark .fc .fc-day-today .fc-daygrid-day-number::before {
        background-color: #ffffff;
    }

    .dark .fc .fc-day-today .fc-daygrid-day-number {
        color: #000000 !important;
    }

    /* Hover effect for calendar days */
    .fc .fc-daygrid-day {
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    /* Ensure hover effect works even with !important on background */
    .fc .fc-daygrid-day:hover {
        background-color: #f8fafc !important; /* Slate 50 */
    }
    .dark .fc .fc-daygrid-day:hover {
        background-color: #171717 !important; /* Neutral 900 */
    }

    /* Event Pill Styling - Premium Softer Colors */
    .fc-event-approved { --bg: #f0fdf4; --text: #166534; } /* Emerald 50/800 */
    .dark .fc-event-approved { --bg: #064e3b4d; --text: #34d399; } /* Emerald 900/400 with 30% alpha */

    .fc-event-pending { --bg: #fffbeb; --text: #92400e; } /* Amber 50/800 */
    .dark .fc-event-pending { --bg: #78350f4d; --text: #fbbf24; } /* Amber 900/400 with 30% alpha */

    .fc-event-holiday { --bg: #fef2f2; --text: #991b1b; } /* Red 50/800 */
    .dark .fc-event-holiday { --bg: #7f1d1d4d; --text: #f87171; } /* Red 900/400 with 30% alpha */

    /* Event Styling - Rounded-md compliance */
    .fc-daygrid-event {
        border-radius: 6px !important; /* rounded-md */
        padding: 2px 6px !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        border: none !important;
        margin: 1px 4px !important;
        cursor: pointer !important;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* shadow-sm */
    }
    .fc-daygrid-event:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* List View Styling */
    .fc-list { border: none !important; }
    .fc-list-day-cushion { background-color: #f8fafc !important; }
    .dark .fc-list-day-cushion { background-color: #262626 !important; } /* Neutral 800 - visible separation */
    .fc-list-event { cursor: pointer !important; }
    .fc-list-event:hover td { background-color: #f1f5f9 !important; }
    .dark .fc-list-event:hover td { background-color: #171717 !important; }

    /* Buttons - strict rounded-md */
    .fc .fc-button {
        height: auto;
        padding: 4px 8px;
        border-radius: 6px !important; /* rounded-md */
    }

    /* Hide the default FC header since we use a custom one */
    .fc-header-toolbar { display: none !important; }
</style>
    <!-- Delete Holiday Modal -->
    <div id="delete-holiday-modal" class="fixed inset-0 z-[60] hidden" aria-modal="true" role="dialog">
        <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('delete-holiday-modal')"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div class="relative w-full max-w-md flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Confirm Deletion</h3>
                    <button type="button" onclick="closeModal('delete-holiday-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
                    </button>
                </div>

                <div class="p-6">
                    <p class="text-sm text-gray-700 dark:text-gray-300">Are you sure you want to delete <span id="delete-holiday-title" class="font-bold text-gray-900 dark:text-gray-100"></span>?</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">This action cannot be undone. This holiday will be removed from the system calendar.</p>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                    <button type="button" onclick="closeModal('delete-holiday-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800">Cancel</button>
                    <form id="delete-holiday-form" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">Delete Holiday</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush
@endsection

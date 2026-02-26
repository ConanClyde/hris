@extends('layouts.dashboard')

@section('title', 'Learning & Development')

@section('content')
@php
    $filtersActive = (string) request('search', '') !== '' || (string) request('type', '') !== '' || (string) request('category', '') !== '';
@endphp
<div class="space-y-6">

    <!-- Success Message -->
    @if(session('success'))
        <div id="success-alert" class="bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 px-4 py-3 rounded-md text-sm font-medium flex items-center justify-between shadow-sm border border-emerald-100 dark:border-emerald-800">
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('success-alert').remove()" class="text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
            </button>
        </div>
        <script>
            setTimeout(() => {
                const alert = document.getElementById('success-alert');
                if (alert) alert.remove();
            }, 3000);
        </script>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Training Records</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage learning and development activities.</p>
        </div>
        <button onclick="openModal('add-training-modal')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-[#013CFC] text-white text-sm font-medium rounded-md hover:bg-[#0031BC] transition-all shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14"/></svg>
            Add Training
        </button>
    </div>

    <!-- Filters -->
    <div class="border border-gray-200 dark:border-neutral-800 rounded-md bg-white dark:bg-neutral-900 p-4 shadow-sm">
        <form action="{{ route('employee.training.index') }}" method="GET" id="filter-form" class="flex flex-col lg:flex-row gap-4 items-center">

            <!-- Search -->
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       oninput="debounceSearch(this)"
                       class="block w-full h-9 pl-10 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 transition-all"
                       placeholder="Search by title...">
            </div>

            <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                <!-- Type Filter -->
                <select name="type" onchange="this.form.submit()" class="block w-full sm:w-48 h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 cursor-pointer">
                    <option value="">All Types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>

                <!-- Category Filter -->
                <select name="category" onchange="this.form.submit()" class="block w-full sm:w-48 h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 cursor-pointer">
                    <option value="">All Categories</option>
                    <option value="Internal" {{ request('category') == 'Internal' ? 'selected' : '' }}>Internal</option>
                    <option value="External" {{ request('category') == 'External' ? 'selected' : '' }}>External</option>
                </select>

                <!-- Actions -->
                <div class="flex gap-2">
                    @if($filtersActive)
                        <a href="{{ route('employee.training.index') }}" class="whitespace-nowrap inline-flex items-center justify-center h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-neutral-800 transition shadow-sm">Clear Filters</a>
                    @else
                        <button type="button" disabled class="whitespace-nowrap inline-flex items-center justify-center h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-400 dark:text-gray-500 rounded-md text-sm font-medium cursor-not-allowed">Clear Filters</button>
                    @endif
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.addEventListener('realtime:training-status-updated', function () {
                window.location.reload();
            });
        });
    </script>
    @endpush

    <!-- Table -->
    <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 rounded-md shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800">
                <thead class="bg-gray-50 dark:bg-neutral-950">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title of Activity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Inclusive Dates</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hours</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Service Provider</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Attachment</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-neutral-800">
                    @forelse($paginatedTrainings as $training)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors cursor-pointer" onclick="openViewModal({{ json_encode($training) }})">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100 max-w-[200px] truncate" title="{{ $training->title }}">{{ $training->title }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            @if($training->date_from && $training->date_to)
                                {{ \Carbon\Carbon::parse($training->date_from)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($training->date_to)->format('M d, Y') }}
                            @else
                                <span class="italic text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $training->hours ?? 0 }} hrs
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded bg-gray-100 text-gray-800 dark:bg-neutral-800 dark:text-gray-300">
                                {{ $training->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 max-w-[150px] truncate" title="{{ $training->provider }}">
                            {{ $training->provider }}
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" onclick="event.stopPropagation()">
                            @if(isset($training->attachments) && count($training->attachments) > 0)
                                @if(count($training->attachments) > 1)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        +{{ count($training->attachments) }} files
                                    </span>
                                @else
                                    <a href="#" class="text-[#013CFC] hover:underline flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        View
                                    </a>
                                @endif
                            @else
                                <span class="text-gray-400 text-xs">No file</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $training->status === 'approved' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' :
                                   ($training->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' :
                                   'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300') }}">
                                {{ ucfirst($training->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" onclick="event.stopPropagation()">
                            <div class="flex justify-end gap-3 items-center">
                                <button type="button" onclick="openViewModal({{ json_encode($training) }})" class="text-gray-400 hover:text-[#013CFC] dark:hover:text-blue-400 transition-colors" title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>

                                <button type="button" onclick="openEditModal({{ json_encode($training) }})" class="text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors" title="Edit Training">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </button>

                                <form action="{{ route('employee.training.destroy', $training->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Delete Training">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-md bg-gray-100 dark:bg-neutral-800 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">No training records yet</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Start adding your learning and development activities.</p>
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
        {{ $paginatedTrainings->links() }}
    </div>

    <!-- Modals -->

    <!-- Add Training Modal -->
    <div id="add-training-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
        <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('add-training-modal')"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <form action="{{ route('employee.training.store') }}" method="POST" enctype="multipart/form-data" class="relative w-full max-w-4xl max-h-[80vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
                @csrf
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Add Training Record</h3>
                    <button type="button" onclick="closeModal('add-training-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
                    </button>
                </div>

                <!-- Content (Scrollable) -->
                <div class="flex-1 min-h-0 overflow-y-auto p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type of Activity</label>
                                <select name="type" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:ring-[#013CFC]" required>
                                    <option value="">Select Type</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                                <select name="category" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:ring-[#013CFC]">
                                    <option value="Internal">Internal</option>
                                    <option value="External">External</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Personnel Order #</label>
                                <input type="text" name="personnel_order" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]" placeholder="N/A if none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Service Provider</label>
                                <input type="text" name="provider" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title of Activity <span class="text-red-500">*</span></label>
                                <input type="text" name="title" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]" required>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date From</label>
                                    <input type="date" name="date_from" id="add_date_from" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC] dark:[color-scheme:dark]" required onchange="calculateTrainingHours('add')">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date To</label>
                                    <input type="date" name="date_to" id="add_date_to" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC] dark:[color-scheme:dark]" required onchange="calculateTrainingHours('add')">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Time From</label>
                                    <input type="time" name="time_from" id="add_time_from" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC] dark:[color-scheme:dark]" onchange="calculateTrainingHours('add')">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Time To</label>
                                    <input type="time" name="time_to" id="add_time_to" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC] dark:[color-scheme:dark]" onchange="calculateTrainingHours('add')">
                                </div>
                            </div>

                             <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hours of Activity</label>
                                <input type="number" step="0.5" name="hours" id="add_hours" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 px-3 py-2 text-sm focus:ring-[#013CFC] cursor-not-allowed" readonly placeholder="Calculated automatically">
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Registration/Training Fee</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                                    <input type="number" step="0.01" name="fee" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 pl-7 pr-3 py-2 text-sm focus:ring-[#013CFC]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">LD Provider Feedback Form</label>
                                <select name="feedback_form" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="None">None</option>
                                    <option value="Submitted">Submitted</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </div>

                             <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Workplace Application Plan</label>
                                 <select name="workplace_plan" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="None">None</option>
                                    <option value="Submitted">Submitted</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Post Training Report</label>
                                 <select name="post_report" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="None">None</option>
                                    <option value="Submitted">Submitted</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </div>

                             <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Certificate</label>
                                 <select name="certificate" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="None">None</option>
                                    <option value="Submitted">Submitted</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attachments</label>
                                <div class="space-y-3">
                                    <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:bg-neutral-900 dark:border-neutral-700 dark:hover:border-neutral-600 dark:hover:bg-neutral-800 transition-colors">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-6 h-6 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12v9"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16 16-4-4-4 4"/></svg>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Click to upload or drag files</p>
                                        </div>
                                        <input type="file" name="attachments[]" class="hidden" multiple onchange="updateFileCount(this)" />
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" id="file-count-label"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer (Fixed) -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0 bg-gray-50/50 dark:bg-neutral-900/10">
                    <button type="button" onclick="closeModal('add-training-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800">Cancel</button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#013CFC]">
                        Save Training
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Edit Training Modal -->
    <div id="edit-training-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
        <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('edit-training-modal')"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <form id="edit-training-form" method="POST" enctype="multipart/form-data" class="relative w-full max-w-4xl max-h-[80vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
                @csrf
                @method('PUT')

                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Edit Training Record</h3>
                    <button type="button" onclick="closeModal('edit-training-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
                    </button>
                </div>

                <!-- Content (Scrollable) -->
                <div class="flex-1 min-h-0 overflow-y-auto p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type of Activity</label>
                                <select id="edit_type" name="type" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:ring-[#013CFC]" required>
                                    <option value="">Select Type</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                                <select id="edit_category" name="category" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:ring-[#013CFC]">
                                    <option value="Internal">Internal</option>
                                    <option value="External">External</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Personnel Order #</label>
                                <input type="text" id="edit_personnel_order" name="personnel_order" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]" placeholder="N/A if none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Service Provider</label>
                                <input type="text" id="edit_provider" name="provider" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title of Activity <span class="text-red-500">*</span></label>
                                <input type="text" id="edit_title" name="title" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]" required>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date From</label>
                                    <input type="date" id="edit_date_from" name="date_from" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC] dark:[color-scheme:dark]" required onchange="calculateTrainingHours('edit')">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date To</label>
                                    <input type="date" id="edit_date_to" name="date_to" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC] dark:[color-scheme:dark]" required onchange="calculateTrainingHours('edit')">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Time From</label>
                                    <input type="time" name="time_from" id="edit_time_from" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC] dark:[color-scheme:dark]" onchange="calculateTrainingHours('edit')">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Time To</label>
                                    <input type="time" name="time_to" id="edit_time_to" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC] dark:[color-scheme:dark]" onchange="calculateTrainingHours('edit')">
                                </div>
                            </div>

                             <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hours of Activity</label>
                                <input type="number" step="0.5" id="edit_hours" name="hours" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 px-3 py-2 text-sm focus:ring-[#013CFC] cursor-not-allowed" readonly placeholder="Calculated automatically">
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Registration/Training Fee</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                                    <input type="number" step="0.01" id="edit_fee" name="fee" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 pl-7 pr-3 py-2 text-sm focus:ring-[#013CFC]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">LD Provider Feedback Form</label>
                                <select id="edit_feedback_form" name="feedback_form" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="None">None</option>
                                    <option value="Submitted">Submitted</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </div>

                             <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Workplace Application Plan</label>
                                 <select id="edit_workplace_plan" name="workplace_plan" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="None">None</option>
                                    <option value="Submitted">Submitted</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Post Training Report</label>
                                 <select id="edit_post_report" name="post_report" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="None">None</option>
                                    <option value="Submitted">Submitted</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </div>

                             <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Certificate</label>
                                 <select id="edit_certificate" name="certificate" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="None">None</option>
                                    <option value="Submitted">Submitted</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attachments</label>
                                <div class="space-y-3">
                                    <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:bg-neutral-900 dark:border-neutral-700 dark:hover:border-neutral-600 dark:hover:bg-neutral-800 transition-colors">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-6 h-6 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12v9"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16 16-4-4-4 4"/></svg>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Click to upload or drag files</p>
                                        </div>
                                        <input type="file" name="attachments[]" class="hidden" multiple onchange="updateEditFileCount(this)" />
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" id="edit-file-count-label"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer (Fixed) -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0 bg-gray-50/50 dark:bg-neutral-900/10">
                    <button type="button" onclick="closeModal('edit-training-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800">Cancel</button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#013CFC]">
                        Update Training
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Modal -->
    <div id="view-training-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
        <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('view-training-modal')"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div class="relative w-full max-w-2xl max-h-[80vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Training Details</h3>
                    <button type="button" onclick="closeModal('view-training-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
                    </button>
                </div>

                <!-- Content (Scrollable) -->
                <div class="flex-1 min-h-0 overflow-y-auto p-6">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-12 h-12 rounded-lg bg-[#013CFC]/10 text-[#013CFC] flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 id="view_title" class="text-lg font-bold text-gray-900 dark:text-white break-words">--</h2>
                            <div class="flex items-center gap-2 mt-1">
                                <span id="view_status" class="px-2.5 py-0.5 rounded-full text-xs font-semibold">--</span>
                                <span id="view_type" class="text-sm text-gray-500 dark:text-gray-400">--</span>
                            </div>
                        </div>
                    </div>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6 text-sm">
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400 mb-1">Inclusive Dates & Time</dt>
                            <dd id="view_dates" class="text-gray-900 dark:text-gray-100 font-medium">--</dd>
                            <dd id="view_times" class="text-xs text-gray-500 dark:text-gray-400 mt-1">--</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400 mb-1">Hours</dt>
                            <dd id="view_hours" class="text-gray-900 dark:text-gray-100">--</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400 mb-1">Service Provider</dt>
                            <dd id="view_provider" class="text-gray-900 dark:text-gray-100">--</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400 mb-1">Fee</dt>
                            <dd id="view_fee" class="text-gray-900 dark:text-gray-100">--</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400 mb-1">Category</dt>
                            <dd id="view_category" class="text-gray-900 dark:text-gray-100">--</dd>
                        </div>
                         <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400 mb-1">Personnel Order #</dt>
                            <dd id="view_personnel_order" class="text-gray-900 dark:text-gray-100">--</dd>
                        </div>
                         <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400 mb-1">Feedback Form</dt>
                            <dd id="view_feedback_form" class="text-gray-900 dark:text-gray-100">--</dd>
                        </div>
                         <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400 mb-1">Workplace App Plan</dt>
                            <dd id="view_workplace_plan" class="text-gray-900 dark:text-gray-100">--</dd>
                        </div>

                        <div class="sm:col-span-2 border-t border-gray-100 dark:border-neutral-800 pt-4 mt-2">
                            <dt class="font-medium text-gray-500 dark:text-gray-400 mb-2">Attachments</dt>
                            <dd id="view_attachments" class="space-y-2">
                                <span class="text-sm text-gray-400 italic">No attachments found.</span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Footer (Fixed) -->
                <div id="view_training_footer" class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0 bg-gray-50/50 dark:bg-neutral-900/10">
                    <button id="view_training_close_btn" onclick="closeModal('view-training-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    let timeout = null;
    function debounceSearch(input) {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            input.form.submit();
        }, 600);
    }

    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // If opening add modal, reset form
            if (id === 'add-training-modal') {
                 const form = modal.querySelector('form');
                 if (form) {
                     form.reset();
                     // Clear time fields and reset calculated values
                     const timeFrom = document.getElementById('add_time_from');
                     const timeTo = document.getElementById('add_time_to');
                     const hours = document.getElementById('add_hours');
                     if (timeFrom) timeFrom.value = '';
                     if (timeTo) timeTo.value = '';
                     if (hours) hours.value = '';
                 }
                 const countLabel = document.getElementById('file-count-label');
                 if(countLabel) countLabel.textContent = '';
            }
        }
    }

    window.calculateTrainingHours = function(prefix) {
        const dateFrom = document.getElementById(prefix + '_date_from').value;
        const dateTo = document.getElementById(prefix + '_date_to').value;
        const timeFrom = document.getElementById(prefix + '_time_from').value;
        const timeTo = document.getElementById(prefix + '_time_to').value;
        const hoursInput = document.getElementById(prefix + '_hours');

        if (!dateFrom || !dateTo) {
            hoursInput.value = '';
            return;
        }

        const startDateTime = new Date(`${dateFrom} ${timeFrom || '08:00'}`);
        const endDateTime = new Date(`${dateTo} ${timeTo || '17:00'}`);

        if (isNaN(startDateTime.getTime()) || isNaN(endDateTime.getTime())) {
            hoursInput.value = '';
            return;
        }

        const diffMs = endDateTime - startDateTime;
        if (diffMs < 0) {
            hoursInput.value = '0';
            return;
        }

        let hours = diffMs / (1000 * 60 * 60);
        const isSameDay = dateFrom === dateTo;
        if (isSameDay) {
            hoursInput.value = hours.toFixed(1);
        } else {
            const d1 = new Date(dateFrom);
            const d2 = new Date(dateTo);
            const days = Math.round((d2 - d1) / (1000 * 60 * 60 * 24)) + 1;
            hoursInput.value = (days * 8).toFixed(1);
        }
    };

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    function openEditModal(training) {
        // Populate Edit Form
        const form = document.getElementById('edit-training-form');
        form.action = `/employee/training/${training.id}`;

        const fields = [
            'title', 'type', 'date_from', 'date_to',
            'time_from', 'time_to',
            'hours', 'provider', 'category', 'fee', 'personnel_order',
            'feedback_form', 'workplace_plan', 'post_report', 'certificate'
        ];

        fields.forEach(field => {
            const el = document.getElementById('edit_' + field);
            if (el) el.value = training[field] || ''; // Handle null values safely
        });

        // Clear file input and label
        const countLabel = document.getElementById('edit-file-count-label');
        if(countLabel) countLabel.textContent = '';

        openModal('edit-training-modal');
    }

    function openViewModal(training) {
        // Populate fields
        document.getElementById('view_title').textContent = training.title || '--';
        document.getElementById('view_type').textContent = training.type || '--';
        document.getElementById('view_category').textContent = training.category || '--';
        document.getElementById('view_hours').textContent = (training.hours ? training.hours + ' hrs' : '--');
        document.getElementById('view_provider').textContent = training.provider || '--';
        document.getElementById('view_fee').textContent = training.fee ? '₱' + training.fee : 'Free';
        document.getElementById('view_personnel_order').textContent = training.personnel_order || '--';
        document.getElementById('view_feedback_form').textContent = training.feedback_form || 'None';
        document.getElementById('view_workplace_plan').textContent = training.workplace_plan || 'None';

        // Dates & Times
        const dateFromLocale = training.date_from ? new Date(training.date_from).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' }) : '';
        const dateToLocale = training.date_to ? new Date(training.date_to).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' }) : '';
        document.getElementById('view_dates').textContent = (dateFromLocale && dateToLocale) ? `${dateFromLocale} - ${dateToLocale}` : '--';

        const timeFromShow = training.time_from ? training.time_from : '08:00';
        const timeToShow = training.time_to ? training.time_to : '17:00';
        document.getElementById('view_times').textContent = `${timeFromShow} - ${timeToShow}`;

        // Status
        const statusEl = document.getElementById('view_status');
        if (statusEl) {
            statusEl.textContent = training.status ? training.status.charAt(0).toUpperCase() + training.status.slice(1) : 'Unknown';
            statusEl.className = 'px-2.5 py-0.5 rounded-full text-xs font-semibold ' +
                (training.status === 'approved' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' :
                 (training.status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' :
                  'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300'));
        }

        const closeBtn = document.getElementById('view_training_close_btn');
        if (closeBtn) closeBtn.classList.remove('hidden');

        // Attachments
        const attachmentsContainer = document.getElementById('view_attachments');
        if (training.attachments && training.attachments.length > 0) {
            let html = '';
            training.attachments.forEach(file => {
                html += `
                    <div class="flex items-center gap-3 p-2 rounded-md bg-gray-50 dark:bg-neutral-800 border border-gray-100 dark:border-neutral-700">
                        <div class="p-2 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">${file.title || file.name || 'Attachment'}</p>
                            <p class="text-xs text-gray-500">${file.size || ''}</p>
                        </div>
                    </div>
                `;
            });
            attachmentsContainer.innerHTML = html;
        } else {
            attachmentsContainer.innerHTML = '<span class="text-sm text-gray-400 italic">No attachments found.</span>';
        }

        openModal('view-training-modal');
    }

    function updateFileCount(input) {
        const countLabel = document.getElementById('file-count-label');
        if (input.files && input.files.length > 0) {
            countLabel.textContent = input.files.length + ' file(s) selected';
        } else {
            countLabel.textContent = '';
        }
    }

    function updateEditFileCount(input) {
        const countLabel = document.getElementById('edit-file-count-label');
        if (input.files && input.files.length > 0) {
            countLabel.textContent = input.files.length + ' file(s) selected';
        } else {
            countLabel.textContent = '';
        }
    }

    // Close on Escape Key
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            const modals = document.querySelectorAll('[role="dialog"]');
            modals.forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    closeModal(modal.id);
                }
            });
        }
    });
</script>
@endsection

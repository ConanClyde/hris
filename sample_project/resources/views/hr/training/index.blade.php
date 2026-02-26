@extends('layouts.dashboard')

@section('navbarTitle', 'Learning & Development')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Training Records (HR)</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage learning and development activities.</p>
        </div>
        <button onclick="openModal('add-training-modal')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-[#013CFC] text-white text-sm font-medium rounded-md hover:bg-[#0031BC] transition-colors focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14"/></svg>
            Add Training
        </button>
    </div>

    <!-- Filters -->
    @php
        $filtersActive = (string) request('search', '') !== '' || (string) request('type', '') !== '' || (string) request('category', '') !== '';
    @endphp
    <div class="border border-gray-200 dark:border-neutral-800 rounded-md bg-white dark:bg-neutral-900 p-4 shadow-sm">
        <form action="{{ route('hr.training.index') }}" method="GET" id="filter-form" class="flex flex-col md:flex-row gap-4 items-center">

            <!-- Search -->
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="block w-full h-9 pl-10 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2"
                       placeholder="Search by title, name or ID..."
                       oninput="debounceSearch(this)">
            </div>

            <!-- Type Filter -->
            <select name="type" onchange="this.form.submit()" class="block w-full md:w-48 h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2">
                <option value="">All Types</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>

            <!-- Category Filter -->
            <select name="category" onchange="this.form.submit()" class="block w-full md:w-48 h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                @endforeach
            </select>

            <!-- Clear Action -->
            @if($filtersActive)
                <a href="{{ route('hr.training.index') }}" class="whitespace-nowrap inline-flex items-center justify-center h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-neutral-800 transition shadow-sm">Clear Filters</a>
            @else
                <button type="button" disabled class="whitespace-nowrap inline-flex items-center justify-center h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-400 dark:text-gray-500 rounded-md text-sm font-medium cursor-not-allowed">Clear Filters</button>
            @endif
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

    <script>
        let timeout = null;
        function debounceSearch(input) {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                input.form.submit();
            }, 600);
        }
    </script>

    <!-- Table -->
    <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 rounded-md shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800">
                <thead class="bg-gray-50 dark:bg-neutral-950">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Employee</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title of Activity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Inclusive Dates</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hours</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ACTION</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-neutral-800">
                    @forelse($trainings as $training)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors cursor-pointer" onclick="openViewModal({{ json_encode($training) }})">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <span class="h-10 w-10 rounded-full bg-[#013CFC]/10 text-[#013CFC] flex items-center justify-center text-sm font-bold">
                                        {{ substr($training->employee_name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $training->employee_name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $training->employee_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100 max-w-[200px] truncate" title="{{ $training->title }}">{{ $training->title }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($training->date_from)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($training->date_to)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $training->hours }} hrs
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded bg-gray-100 text-gray-800 dark:bg-neutral-800 dark:text-gray-300">
                                {{ $training->type }}
                            </span>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                                <button type="button" onclick="openEditModal({{ json_encode($training) }})" class="text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors" title="Edit Training">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </button>
                                <form action="{{ route('hr.training.destroy', $training->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Delete Training">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-md bg-gray-100 dark:bg-neutral-800 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">No training records found</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Try adjusting your search or filters.</p>
                                <a href="{{ route('hr.training.index') }}" class="mt-3 text-sm font-medium text-[#013CFC] hover:text-[#0031BC]">Clear all filters</a>
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
        {{ $trainings->links() }}
    </div>
</div>

<!-- Add Training Modal -->
<div id="add-training-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('add-training-modal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <form method="POST" action="{{ route('hr.training.store') }}" class="relative w-full max-w-3xl max-h-[80vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
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
                    <!-- HR Specific: Employee Selection -->
                    <div class="mb-6">
                        <label for="add_employee_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Employee <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="hidden" name="employee_id" id="add_employee_id" value="">
                            <input id="add_employee_search" type="text" required autocomplete="off" placeholder="Search employee…" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:ring-[#013CFC]">
                            <div id="add_employee_list" class="hidden absolute z-10 mt-1 w-full max-h-56 overflow-auto rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type of Activity</label>
                                <select id="add_type" name="type" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:ring-[#013CFC]" required>
                                    <option value="">Select Type</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                                <select id="add_category" name="category" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:ring-[#013CFC]">
                                    <option value="Internal">Internal</option>
                                    <option value="External">External</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Personnel Order #</label>
                                <input type="text" name="personnel_order" id="add_personnel_order" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]" placeholder="N/A if none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Service Provider</label>
                                <input type="text" name="provider" id="add_provider" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title of Activity <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="add_title" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]" required>
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

                             <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Participants / Notes</label>
                                <textarea name="participants" id="add_participants" rows="2" class="w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 focus:ring-[#013CFC]"></textarea>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Registration/Training Fee</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                                    <input type="number" step="0.01" name="fee" id="add_fee" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 pl-7 pr-3 py-2 text-sm focus:ring-[#013CFC]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">LD Provider Feedback Form</label>
                                <select name="feedback_form" id="add_feedback_form" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="None">None</option>
                                    <option value="Submitted">Submitted</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Workplace Application Plan</label>
                                 <select name="workplace_plan" id="add_workplace_plan" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="None">None</option>
                                    <option value="Submitted">Submitted</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Post Training Report</label>
                                 <select name="post_report" id="add_post_report" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="None">None</option>
                                    <option value="Submitted">Submitted</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Certificate</label>
                                 <select name="certificate" id="add_certificate" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="None">None</option>
                                    <option value="Submitted">Submitted</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <select id="add_status" name="status" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
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
                                        <input type="file" name="attachments[]" id="add_dropzone" class="hidden" multiple />
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer (Fixed) -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0 bg-gray-50/50 dark:bg-neutral-900/10">
                    <button type="button" onclick="closeModal('add-training-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800">Cancel</button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#013CFC]">
                        Save Record
                    </button>
                </div>
            </form>
    </div>
</div>

<!-- Edit Training Modal -->
<div id="edit-training-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('edit-training-modal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <form method="POST" id="edit-training-form" class="relative w-full max-w-4xl max-h-[80vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
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
                    <!-- HR Specific: Employee Selection -->
                    <div class="mb-6">
                        <label for="edit_employee_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Employee <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="hidden" name="employee_id" id="edit_employee_id" value="">
                            <input id="edit_employee_search" type="text" required autocomplete="off" placeholder="Search employee…" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:ring-[#013CFC]">
                            <div id="edit_employee_list" class="hidden absolute z-10 mt-1 w-full max-h-56 overflow-auto rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow">
                            </div>
                        </div>
                    </div>

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
                                <input type="text" name="personnel_order" id="edit_personnel_order" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]" placeholder="N/A if none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Service Provider</label>
                                <input type="text" name="provider" id="edit_provider" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title of Activity <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="edit_title" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]" required>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date From</label>
                                    <input type="date" name="date_from" id="edit_date_from" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC] dark:[color-scheme:dark]" required onchange="calculateTrainingHours('edit')">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date To</label>
                                    <input type="date" name="date_to" id="edit_date_to" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC] dark:[color-scheme:dark]" required onchange="calculateTrainingHours('edit')">
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
                                <input type="number" step="0.5" name="hours" id="edit_hours" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 px-3 py-2 text-sm focus:ring-[#013CFC] cursor-not-allowed" readonly placeholder="Calculated automatically">
                            </div>

                             <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Participants / Notes</label>
                                <textarea name="participants" id="edit_participants" rows="2" class="w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 focus:ring-[#013CFC]"></textarea>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Registration/Training Fee</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                                    <input type="number" step="0.01" name="fee" id="edit_fee" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 pl-7 pr-3 py-2 text-sm focus:ring-[#013CFC]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">LD Provider Feedback Form</label>
                                <select name="feedback_form" id="edit_feedback_form" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="None">None</option>
                                    <option value="Submitted">Submitted</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Workplace Application Plan</label>
                                 <select name="workplace_plan" id="edit_workplace_plan" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="None">None</option>
                                    <option value="Submitted">Submitted</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Post Training Report</label>
                                 <select name="post_report" id="edit_post_report" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="None">None</option>
                                    <option value="Submitted">Submitted</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Certificate</label>
                                 <select name="certificate" id="edit_certificate" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="None">None</option>
                                    <option value="Submitted">Submitted</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <select id="edit_status" name="status" class="w-full h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:ring-[#013CFC]">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attachments</label>
                                <div id="edit-attachments-list" class="mb-3 space-y-2">
                                    <!-- Existing attachments will be loaded here via JS -->
                                </div>
                                <div class="space-y-3">
                                    <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:bg-neutral-900 dark:border-neutral-700 dark:hover:border-neutral-600 dark:hover:bg-neutral-800 transition-colors">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-6 h-6 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12v9"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16 16-4-4-4 4"/></svg>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Click to upload or drag files</p>
                                        </div>
                                        <input type="file" name="attachments[]" id="edit_dropzone" class="hidden" multiple />
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer (Fixed) -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0 bg-gray-50/50 dark:bg-neutral-900/10">
                    <button type="button" onclick="closeModal('edit-training-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800">Cancel</button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#013CFC]">
                        Update Record
                    </button>
                </div>
        </form>
    </div>
</div>

<!-- View Training Modal -->
<div id="view-training-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('view-training-modal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-2xl max-h-[80vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Training Details</h3>
                <button type="button" onclick="closeModal('view-training-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
                </button>
            </div>

            <div class="p-6 flex-1 min-h-0 overflow-y-auto">
                <div class="flex items-start gap-4 mb-8">
                    <div id="view_avatar" class="w-16 h-16 rounded-full bg-gray-100 dark:bg-neutral-800 text-gray-500 dark:text-gray-400 flex items-center justify-center text-2xl font-bold shrink-0">
                        <!-- Initials JS -->
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 id="view_title" class="text-lg font-bold text-gray-900 dark:text-white truncate"></h2>
                        <p id="view_employee_name" class="text-sm font-medium text-gray-700 dark:text-gray-300 mt-1"></p>
                        <p id="view_employee_id" class="text-sm text-gray-500 dark:text-gray-400"></p>
                        <div class="mt-2 text-xs">
                             <span id="view_status" class="px-2.5 py-0.5 rounded-full font-semibold border"></span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <dt class="text-[10px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-[0.1em] mb-1.5">Type & Category</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                <span id="view_type">--</span>
                                <span class="text-gray-300 dark:text-neutral-700">|</span>
                                <span id="view_category">--</span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-[10px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-[0.1em] mb-1.5">Personnel Order #</dt>
                            <dd id="view_personnel_order" class="text-sm font-medium text-gray-900 dark:text-gray-100">--</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-[0.1em] mb-1.5">Inclusive Dates & Time</dt>
                            <dd id="view_dates" class="text-sm font-medium text-gray-900 dark:text-gray-100">--</dd>
                            <dd id="view_times" class="text-xs text-gray-500 dark:text-gray-400 mt-1">--</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-[0.1em] mb-1.5">Service Provider</dt>
                            <dd id="view_provider" class="text-sm font-medium text-gray-900 dark:text-gray-100">--</dd>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-[0.1em] mb-1.5">Duration</dt>
                                <dd id="view_hours" class="text-sm font-medium text-gray-900 dark:text-gray-100">--</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-[0.1em] mb-1.5">Fee</dt>
                                <dd id="view_fee" class="text-sm font-medium text-gray-900 dark:text-gray-100 font-mono">--</dd>
                            </div>
                        </div>
                         <div class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-[0.1em] mb-1.5">Feedback</dt>
                                <dd id="view_feedback_form" class="text-sm font-medium text-gray-900 dark:text-gray-100">--</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-[0.1em] mb-1.5">WAP</dt>
                                <dd id="view_workplace_plan" class="text-sm font-medium text-gray-900 dark:text-gray-100">--</dd>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-[0.1em] mb-1.5">Report</dt>
                                <dd id="view_post_report" class="text-sm font-medium text-gray-900 dark:text-gray-100">--</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-[0.1em] mb-1.5">Certificate</dt>
                                <dd id="view_certificate" class="text-sm font-medium text-gray-900 dark:text-gray-100">--</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <dt class="text-[10px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-[0.1em] mb-1.5">Participants / Notes</dt>
                    <dd id="view_participants" class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap bg-gray-50 dark:bg-neutral-900/50 p-3 rounded-md border border-gray-100 dark:border-neutral-800">--</dd>
                </div>

                <div class="mt-8">
                    <dt class="text-[10px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-[0.1em] mb-3">Attachments</dt>
                    <dd id="view_attachments" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <!-- JS Loaded -->
                    </dd>
                </div>
            </div>

            <div id="view_training_footer" class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0 bg-gray-50/50 dark:bg-neutral-900/10">
                <form id="view-training-status-form" method="POST" data-action-base="/hr/training" class="hidden flex gap-3">
                    @csrf
                    @method('PUT')
                    <button type="submit" name="status" value="rejected" class="inline-flex items-center justify-center h-9 px-4 rounded-md text-sm font-medium bg-white dark:bg-neutral-900 border border-red-200 dark:border-red-500/30 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                        Reject
                    </button>
                    <button type="submit" name="status" value="approved" class="inline-flex items-center justify-center h-9 px-4 rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                        Approve
                    </button>
                </form>
                <button id="view_training_close_btn" onclick="closeModal('view-training-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800 transition-colors">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
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
                     // Clear time fields and reset readonly state if needed
                     const timeFrom = document.getElementById('add_time_from');
                     const timeTo = document.getElementById('add_time_to');
                     const hours = document.getElementById('add_hours');
                     if (timeFrom) timeFrom.value = '';
                     if (timeTo) timeTo.value = '';
                     if (hours) hours.value = '';
                 }
                 const addHidden = document.getElementById('add_employee_id');
                 const addInput = document.getElementById('add_employee_search');
                 const addList = document.getElementById('add_employee_list');
                 if (addHidden) addHidden.value = '';
                 if (addInput) addInput.value = '';
                 if (addList) addList.classList.add('hidden');
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

        // Default times if not provided
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

        // Basic calculation: Total hours difference
        let hours = diffMs / (1000 * 60 * 60);

        // If multiple days, we might want to assume 8 hours per day instead of full 24h
        // But the prompt says "depend on and locked by the time range".
        // Let's do a simple calculation first.

        // If start date and end date are same, just do the time diff.
        // If different, we might need a more complex logic.
        // For HRIS usually it's "8 hours per day" unless it's a specific time range.

        const isSameDay = dateFrom === dateTo;
        if (isSameDay) {
            // Hours = time difference
            hoursInput.value = hours.toFixed(1);
        } else {
            // Multiple days: Assume standard 8 hours per day between dates (inclusive)
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

    function initEmployeeCombobox(options) {
        const employees = Array.isArray(options.employees) ? options.employees : [];
        const input = document.getElementById(options.inputId);
        const hidden = document.getElementById(options.hiddenId);
        const list = document.getElementById(options.listId);
        if (!input || !hidden || !list) return;

        function normalize(v) {
            return String(v || '').toLowerCase().trim();
        }

        function render(query) {
            const q = normalize(query);
            list.innerHTML = '';

            const results = q
                ? employees.filter(e => normalize(`${e.name} ${e.id}`).includes(q))
                : employees;

            if (results.length === 0) {
                const empty = document.createElement('div');
                empty.className = 'px-3 py-2 text-sm text-gray-500 dark:text-gray-400';
                empty.textContent = 'No matches';
                list.appendChild(empty);
                return;
            }

            results.slice(0, 50).forEach(function (e) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'w-full text-left px-3 py-2 text-sm text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-neutral-800';
                btn.textContent = `${e.name} (${e.id})`;
                btn.addEventListener('click', function () {
                    hidden.value = e.id;
                    input.value = `${e.name} (${e.id})`;
                    list.classList.add('hidden');
                });
                list.appendChild(btn);
            });
        }

        function openList() {
            render(input.value);
            list.classList.remove('hidden');
        }

        input.addEventListener('focus', openList);
        input.addEventListener('input', function () {
            hidden.value = '';
            openList();
        });
        input.addEventListener('blur', function () {
            setTimeout(function () {
                list.classList.add('hidden');
                if (!hidden.value) input.value = '';
            }, 100);
        });

        document.addEventListener('click', function (e) {
            if (!list.contains(e.target) && e.target !== input) {
                list.classList.add('hidden');
            }
        });
    }

    function openViewModal(training) {
        // Populate fields
        document.getElementById('view_title').textContent = training.title || '--';
        document.getElementById('view_employee_name').textContent = training.employee_name || '--';
        document.getElementById('view_employee_id').textContent = training.employee_id || '--';
        document.getElementById('view_type').textContent = training.type || '--';
        document.getElementById('view_category').textContent = training.category || '--';
        document.getElementById('view_personnel_order').textContent = training.personnel_order || '--';
        document.getElementById('view_feedback_form').textContent = training.feedback_form || 'None';
        document.getElementById('view_workplace_plan').textContent = training.workplace_plan || 'None';
        document.getElementById('view_post_report').textContent = training.post_report || 'None';
        document.getElementById('view_certificate').textContent = training.certificate || 'None';
        document.getElementById('view_hours').textContent = (training.hours ? training.hours + ' hrs' : '--');
        document.getElementById('view_provider').textContent = training.provider || '--';
        document.getElementById('view_fee').textContent = training.fee ? '₱' + training.fee : 'Free';
        document.getElementById('view_participants').textContent = training.participants || 'None';

        // Dates & Times
        const dateFromLocale = training.date_from ? new Date(training.date_from).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' }) : '';
        const dateToLocale = training.date_to ? new Date(training.date_to).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' }) : '';
        document.getElementById('view_dates').textContent = (dateFromLocale && dateToLocale) ? `${dateFromLocale} - ${dateToLocale}` : '--';

        const timeFromShow = training.time_from ? training.time_from : '08:00';
        const timeToShow = training.time_to ? training.time_to : '17:00';
        document.getElementById('view_times').textContent = `${timeFromShow} - ${timeToShow}`;

        // Avatar
        document.getElementById('view_avatar').textContent = (training.employee_name || '?').charAt(0).toUpperCase();

        // Status
        const statusValue = String(training.status || '').toLowerCase();
        const statusEl = document.getElementById('view_status');
        if (statusEl) {
            statusEl.textContent = statusValue ? statusValue.charAt(0).toUpperCase() + statusValue.slice(1) : 'Unknown';
            statusEl.className = 'px-2.5 py-0.5 rounded-full font-semibold ' +
                (statusValue === 'approved' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' :
                 (statusValue === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' :
                  'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300'));
        }

        const closeBtn = document.getElementById('view_training_close_btn');
        const statusForm = document.getElementById('view-training-status-form');
        const isPending = statusValue === 'pending';
        if (closeBtn) closeBtn.classList.toggle('hidden', isPending);
        if (statusForm) {
            statusForm.classList.toggle('hidden', !isPending);
            if (isPending) {
                const base = statusForm.getAttribute('data-action-base') || '';
                statusForm.action = `${base}/${training.id}`;
            }
        }

        openModal('view-training-modal');
    }

    function openEditModal(training) {
        // Populate Edit Form
        const form = document.getElementById('edit-training-form');
        form.action = `/hr/training/${training.id}`;

        const fields = [
            'employee_id', 'title', 'type', 'date_from', 'date_to',
            'time_from', 'time_to',
            'hours', 'provider', 'category', 'fee', 'status', 'participants',
            'personnel_order', 'feedback_form', 'workplace_plan', 'post_report', 'certificate'
        ];

        fields.forEach(field => {
            const el = document.getElementById('edit_' + field);
            if (el) el.value = training[field] || '';
        });

        const editInput = document.getElementById('edit_employee_search');
        const employeeName = training.employee_name || '';
        const employeeId = training.employee_id || '';
        if (editInput) {
            editInput.value = (employeeName && employeeId) ? `${employeeName} (${employeeId})` : '';
        }

        openModal('edit-training-modal');
    }

    const employees = @json($employees->map(fn ($e) => ['id' => $e->id, 'name' => $e->name])->values());
    initEmployeeCombobox({
        employees,
        inputId: 'add_employee_search',
        hiddenId: 'add_employee_id',
        listId: 'add_employee_list',
    });
    initEmployeeCombobox({
        employees,
        inputId: 'edit_employee_search',
        hiddenId: 'edit_employee_id',
        listId: 'edit_employee_list',
    });

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

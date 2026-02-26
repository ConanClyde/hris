@extends('layouts.dashboard')

@section('navbarTitle', 'My Leave Applications')

@section('content')
@php
    $types = $types ?? [];
    $statusOptions = $statusOptions ?? ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'];
    $filtersActive = (string) request('search', '') !== '' || (string) request('type', '') !== '' || (string) request('status', '') !== '';
@endphp

<div class="space-y-6">
    @if(session('success'))
        <div id="success-alert" class="bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 px-4 py-3 rounded-md text-sm font-medium flex items-center justify-between shadow-sm border border-emerald-100 dark:border-emerald-800">
            <span>{{ session('success') }}</span>
            <button type="button" onclick="document.getElementById('success-alert').remove()" class="text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-300">
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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.addEventListener('realtime:leave-status-updated', function () {
                window.location.reload();
            });
        });
    </script>
    @endpush

    @if($errors->any())
        <div class="rounded-md border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-900/20 px-4 py-3 text-sm text-red-800 dark:text-red-300">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">My Leave Applications</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Track and manage your leave requests.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex gap-4">
                @forelse($leaveCredits as $credit)
                    <div class="flex flex-col items-end">
                        <span class="text-[10px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider">{{ $credit->leave_type }}</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ number_format($credit->balance, 2) }}</span>
                    </div>
                @empty
                    <div class="flex flex-col items-end">
                        <span class="text-[10px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider">Credits</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">0.00</span>
                    </div>
                @endforelse
            </div>
            <div class="h-8 w-px bg-gray-200 dark:bg-neutral-800"></div>
            <button type="button" onclick="openModal('add-leave-modal')" class="inline-flex items-center gap-2 px-4 py-2 bg-[#013CFC] text-white text-sm font-medium rounded-md hover:bg-[#0031BC] transition-colors focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14"/></svg>
                Apply for Leave
            </button>
        </div>
    </div>

    <div class="border border-gray-200 dark:border-neutral-800 rounded-md bg-white dark:bg-neutral-900 p-4 shadow-sm">
        <form action="{{ route('employee.leave-applications.index') }}" method="GET" id="filter-form" class="flex flex-col lg:flex-row gap-4 items-center">
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" oninput="debounceSearch(this)" class="block w-full h-9 pl-10 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 transition-all" placeholder="Search by type or reason...">
            </div>

            <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                <select name="type" onchange="this.form.submit()" class="block w-full sm:w-56 h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 cursor-pointer">
                    <option value="">All Types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" @selected(request('type') === (string) $type)>{{ $type }}</option>
                    @endforeach
                </select>

                <select name="status" onchange="this.form.submit()" class="block w-full sm:w-40 h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 cursor-pointer">
                    <option value="">All Statuses</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === (string) $value)>{{ $label }}</option>
                    @endforeach
                </select>

                <div class="flex gap-2">
                    @if($filtersActive)
                        <a href="{{ route('employee.leave-applications.index') }}" class="whitespace-nowrap inline-flex items-center justify-center h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-neutral-800 transition shadow-sm">Clear Filters</a>
                    @else
                        <button type="button" disabled class="whitespace-nowrap inline-flex items-center justify-center h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-400 dark:text-gray-500 rounded-md text-sm font-medium cursor-not-allowed">Clear Filters</button>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 rounded-md shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800">
                <thead class="bg-gray-50 dark:bg-neutral-950">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Inclusive Dates</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Days</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Attachment</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Submitted</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-neutral-800">
                    @forelse($paginatedApplications as $leave)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors cursor-pointer" onclick="openViewModal({{ json_encode($leave) }})">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 max-w-[220px] truncate" title="{{ $leave->type }}">{{ $leave->type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @php
                                    $from = $leave->date_from ? \Carbon\Carbon::parse($leave->date_from)->format('M d, Y') : null;
                                    $to = $leave->date_to ? \Carbon\Carbon::parse($leave->date_to)->format('M d, Y') : null;
                                @endphp
                                @if($from && $to)
                                    {{ $from }} - {{ $to }}
                                @elseif($from)
                                    {{ $from }}
                                @else
                                    <span class="italic text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $leave->total_days }} {{ \Illuminate\Support\Str::plural('day', $leave->total_days) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" onclick="event.stopPropagation()">
                                @if(isset($leave->attachments) && count($leave->attachments) > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-800 dark:text-gray-300">
                                        +{{ count($leave->attachments) }} file{{ count($leave->attachments) > 1 ? 's' : '' }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">No file</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = $leave->status === 'approved'
                                        ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300'
                                        : ($leave->status === 'rejected'
                                            ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'
                                            : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300');
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $leave->created_at ? \Carbon\Carbon::parse($leave->created_at)->diffForHumans() : '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" onclick="event.stopPropagation()">
                                <div class="flex justify-end gap-3 items-center">
                                    <button type="button" onclick="openViewModal({{ json_encode($leave) }})" class="text-gray-400 hover:text-[#013CFC] dark:hover:text-blue-400 transition-colors" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>

                                    @if(($leave->status ?? '') === 'pending')
                                        <button type="button" onclick="openEditModal({{ json_encode($leave) }})" class="text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors" title="Edit Application">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                        </button>

                                        <form action="{{ route('employee.leave-applications.destroy', $leave->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Delete Application">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Locked</span>
                                    @endif
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
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">No leave applications yet</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Start by applying for leave.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        @if (method_exists($paginatedApplications, 'links'))
            {{ $paginatedApplications->links() }}
        @endif
    </div>
</div>

    {{-- Modals --}}

<div id="view-leave-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60" onclick="closeModal('view-leave-modal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-2xl max-h-[80vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Leave Application Details</h3>
                <button type="button" onclick="closeModal('view-leave-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
                </button>
            </div>
            <div class="flex-1 min-h-0 overflow-y-auto p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Leave Type</div>
                        <div id="view_type" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">--</div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</div>
                        <div class="mt-1">
                            <span id="view_status" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">--</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Inclusive Dates</div>
                        <div id="view_dates" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">--</div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Days</div>
                        <div id="view_days" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">--</div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Submitted</div>
                        <div id="view_submitted" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">--</div>
                    </div>
                </div>
                <div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reason</div>
                    <div id="view_reason" class="mt-1 text-sm text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-neutral-900 p-3 rounded-md">--</div>
                </div>
                <div id="view_attachments_wrap" class="hidden">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Attachments</div>
                    <ul id="view_attachments" class="border border-gray-200 dark:border-neutral-800 rounded-md divide-y divide-gray-200 dark:divide-neutral-800"></ul>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end shrink-0">
                <button id="view_leave_close_btn" type="button" onclick="closeModal('view-leave-modal')" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-neutral-700 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-900 hover:bg-gray-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#013CFC] dark:focus:ring-offset-neutral-900 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<div id="add-leave-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('add-leave-modal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <form action="{{ route('employee.leave-applications.store') }}" method="POST" enctype="multipart/form-data" class="relative w-full max-w-2xl max-h-[80vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
            @csrf
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Apply for Leave</h3>
                <button type="button" onclick="closeModal('add-leave-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
                </button>
            </div>
            <div class="flex-1 min-h-0 overflow-y-auto p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="add_leave_type">Leave Type</label>
                        <select id="add_leave_type" name="leave_type" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                            <option value="">Select Type</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="add_date_from">Date From</label>
                            <input id="add_date_from" type="date" name="date_from" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 dark:[color-scheme:dark]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="add_date_to">Date To (optional)</label>
                            <input id="add_date_to" type="date" name="date_to" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 dark:[color-scheme:dark]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="add_total_days">Total Days</label>
                        <input id="add_total_days" type="number" step="0.5" min="0.5" name="total_days" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="add_reason">Reason</label>
                        <textarea id="add_reason" name="reason" rows="3" required class="w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900"></textarea>
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="add_attachments">Attachments (optional)</label>
                            <span id="add-file-count-label" class="text-xs text-gray-500 dark:text-gray-400"></span>
                        </div>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-neutral-700 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                    <label for="add_attachments" class="relative cursor-pointer bg-white dark:bg-neutral-950 rounded-md font-medium text-[#013CFC] hover:text-[#0031BC] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#013CFC] dark:focus-within:ring-offset-neutral-900">
                                        <span>Upload files</span>
                                        <input id="add_attachments" name="attachments[]" type="file" class="sr-only" multiple>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, PDF up to 10MB</p>
                            </div>
                        </div>
                        <ul id="add-files-list" class="mt-3 divide-y divide-gray-200 dark:divide-neutral-800 border border-gray-200 dark:border-neutral-800 rounded-md hidden"></ul>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                <button type="button" onclick="closeModal('add-leave-modal')" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-neutral-700 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-900 hover:bg-gray-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#013CFC] dark:focus:ring-offset-neutral-900 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-[#013CFC] text-white text-sm font-medium rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 transition-colors">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<div id="edit-leave-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('edit-leave-modal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <form id="edit-leave-form" data-action-base="{{ url('/employee/leave-applications') }}" method="POST" enctype="multipart/form-data" class="relative w-full max-w-2xl max-h-[80vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
            @csrf
            @method('PUT')
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Edit Application</h3>
                <button type="button" onclick="closeModal('edit-leave-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
                </button>
            </div>
            <div class="flex-1 min-h-0 overflow-y-auto p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="edit_leave_type">Leave Type</label>
                        <select id="edit_leave_type" name="leave_type" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                            @foreach($types as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="edit_date_from">Date From</label>
                            <input id="edit_date_from" type="date" name="date_from" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 dark:[color-scheme:dark]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="edit_date_to">Date To (optional)</label>
                            <input id="edit_date_to" type="date" name="date_to" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 dark:[color-scheme:dark]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="edit_total_days">Total Days</label>
                        <input id="edit_total_days" type="number" step="0.5" min="0.5" name="total_days" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="edit_reason">Reason</label>
                        <textarea id="edit_reason" name="reason" rows="3" required class="w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900"></textarea>
                    </div>

                    <div id="edit-existing-attachments-wrap" class="hidden">
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Current Attachments</div>
                        <ul id="edit-existing-attachments" class="border border-gray-200 dark:border-neutral-800 rounded-md divide-y divide-gray-200 dark:divide-neutral-800"></ul>
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="edit_attachments">Add Attachments (optional)</label>
                            <span id="edit-file-count-label" class="text-xs text-gray-500 dark:text-gray-400"></span>
                        </div>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-neutral-700 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                    <label for="edit_attachments" class="relative cursor-pointer bg-white dark:bg-neutral-950 rounded-md font-medium text-[#013CFC] hover:text-[#0031BC] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#013CFC] dark:focus-within:ring-offset-neutral-900">
                                        <span>Upload files</span>
                                        <input id="edit_attachments" name="attachments[]" type="file" class="sr-only" multiple>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, PDF up to 10MB</p>
                            </div>
                        </div>
                        <ul id="edit-files-list" class="mt-3 divide-y divide-gray-200 dark:divide-neutral-800 border border-gray-200 dark:border-neutral-800 rounded-md hidden"></ul>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                <button type="button" onclick="closeModal('edit-leave-modal')" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-neutral-700 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-900 hover:bg-gray-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#013CFC] dark:focus:ring-offset-neutral-900 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-[#013CFC] text-white text-sm font-medium rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 transition-colors">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let timeout = null;
    function debounceSearch(input) {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            input.form.submit();
        }, 600);
    }

    function openModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.add('hidden');
        document.body.style.overflow = '';
    }

    function setStatusBadge(el, status) {
        if (!el) return;
        el.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full';
        if (status === 'approved') {
            el.classList.add('bg-emerald-100', 'text-emerald-800', 'dark:bg-emerald-900/30', 'dark:text-emerald-300');
        } else if (status === 'rejected') {
            el.classList.add('bg-red-100', 'text-red-800', 'dark:bg-red-900/30', 'dark:text-red-300');
        } else {
            el.classList.add('bg-amber-100', 'text-amber-800', 'dark:bg-amber-900/30', 'dark:text-amber-300');
        }
        el.textContent = status ? (status.charAt(0).toUpperCase() + status.slice(1)) : '--';
    }

    function openViewModal(leave) {
        const typeEl = document.getElementById('view_type');
        const statusEl = document.getElementById('view_status');
        const datesEl = document.getElementById('view_dates');
        const daysEl = document.getElementById('view_days');
        const submittedEl = document.getElementById('view_submitted');
        const reasonEl = document.getElementById('view_reason');
        const closeBtn = document.getElementById('view_leave_close_btn');

        if (typeEl) typeEl.textContent = leave.type || '--';
        setStatusBadge(statusEl, leave.status || '');
        if (closeBtn) {
            closeBtn.classList.toggle('hidden', (leave.status || '') === 'pending');
        }
        if (datesEl) {
            const from = leave.date_from ? new Date(leave.date_from).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' }) : '';
            const to = leave.date_to ? new Date(leave.date_to).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' }) : '';
            datesEl.textContent = (from && to) ? `${from} - ${to}` : (from || '--');
        }
        if (daysEl) daysEl.textContent = leave.total_days ? `${leave.total_days} day${leave.total_days > 1 ? 's' : ''}` : '--';
        if (submittedEl) submittedEl.textContent = leave.created_at ? new Date(leave.created_at).toLocaleDateString() : '--';
        if (reasonEl) reasonEl.textContent = leave.reason || 'No reason provided.';

        const wrap = document.getElementById('view_attachments_wrap');
        const list = document.getElementById('view_attachments');
        if (wrap && list) {
            list.innerHTML = '';
            const attachments = Array.isArray(leave.attachments) ? leave.attachments : [];
            if (attachments.length === 0) {
                wrap.classList.add('hidden');
            } else {
                wrap.classList.remove('hidden');
                attachments.forEach(function (file) {
                    const li = document.createElement('li');
                    li.className = 'pl-3 pr-4 py-3 flex items-center justify-between text-sm';
                    const left = document.createElement('div');
                    left.className = 'w-0 flex-1 flex items-center';
                    left.innerHTML = '<svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>';
                    const name = document.createElement('span');
                    name.className = 'ml-2 flex-1 w-0 truncate';
                    name.textContent = file.name || 'Attachment';
                    left.appendChild(name);
                    li.appendChild(left);
                    list.appendChild(li);
                });
            }
        }

        openModal('view-leave-modal');
    }

    let addFiles = [];
    let editFiles = [];

    function renderFiles(listEl, files, removeHandlerName) {
        if (!listEl) return;
        listEl.innerHTML = '';
        if (files.length === 0) {
            listEl.classList.add('hidden');
            return;
        }
        listEl.classList.remove('hidden');
        files.forEach(function (file, index) {
            const li = document.createElement('li');
            li.className = 'pl-3 pr-4 py-2 flex items-center justify-between text-sm';
            const name = document.createElement('span');
            name.className = 'truncate w-0 flex-1 text-gray-500 dark:text-gray-400';
            name.textContent = file.name;
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'text-red-600 hover:text-red-800';
            btn.textContent = 'Remove';
            btn.setAttribute('onclick', `${removeHandlerName}(${index})`);
            li.appendChild(name);
            li.appendChild(btn);
            listEl.appendChild(li);
        });
    }

    function syncInputFiles(inputEl, files) {
        if (!inputEl) return;
        const dt = new DataTransfer();
        files.forEach(function (file) {
            dt.items.add(file);
        });
        inputEl.files = dt.files;
    }

    function setFileCount(labelEl, count) {
        if (!labelEl) return;
        labelEl.textContent = count ? `${count} file${count > 1 ? 's' : ''} selected` : '';
    }

    function removeAddFile(index) {
        addFiles.splice(index, 1);
        const input = document.getElementById('add_attachments');
        syncInputFiles(input, addFiles);
        renderFiles(document.getElementById('add-files-list'), addFiles, 'removeAddFile');
        setFileCount(document.getElementById('add-file-count-label'), addFiles.length);
    }

    function removeEditFile(index) {
        editFiles.splice(index, 1);
        const input = document.getElementById('edit_attachments');
        syncInputFiles(input, editFiles);
        renderFiles(document.getElementById('edit-files-list'), editFiles, 'removeEditFile');
        setFileCount(document.getElementById('edit-file-count-label'), editFiles.length);
    }

    function bindFilePicker(inputId, listId, labelId, storeName) {
        const input = document.getElementById(inputId);
        if (!input) return;
        input.addEventListener('change', function () {
            const files = Array.from(input.files || []);
            if (storeName === 'add') {
                addFiles = addFiles.concat(files);
                syncInputFiles(input, addFiles);
                renderFiles(document.getElementById(listId), addFiles, 'removeAddFile');
                setFileCount(document.getElementById(labelId), addFiles.length);
            } else {
                editFiles = editFiles.concat(files);
                syncInputFiles(input, editFiles);
                renderFiles(document.getElementById(listId), editFiles, 'removeEditFile');
                setFileCount(document.getElementById(labelId), editFiles.length);
            }
        });
    }

    function openEditModal(leave) {
        const form = document.getElementById('edit-leave-form');
        if (form) {
            const base = form.getAttribute('data-action-base') || '';
            form.action = `${base}/${leave.id}`;
        }

        const typeEl = document.getElementById('edit_leave_type');
        const fromEl = document.getElementById('edit_date_from');
        const toEl = document.getElementById('edit_date_to');
        const daysEl = document.getElementById('edit_total_days');
        const reasonEl = document.getElementById('edit_reason');

        if (typeEl) typeEl.value = leave.type || '';
        if (fromEl) fromEl.value = leave.date_from || '';
        if (toEl) toEl.value = leave.date_to || '';
        if (daysEl) daysEl.value = leave.total_days || '';
        if (reasonEl) reasonEl.value = leave.reason || '';

        editFiles = [];
        const editInput = document.getElementById('edit_attachments');
        syncInputFiles(editInput, editFiles);
        renderFiles(document.getElementById('edit-files-list'), editFiles, 'removeEditFile');
        setFileCount(document.getElementById('edit-file-count-label'), 0);

        const wrap = document.getElementById('edit-existing-attachments-wrap');
        const list = document.getElementById('edit-existing-attachments');
        if (wrap && list) {
            list.innerHTML = '';
            const attachments = Array.isArray(leave.attachments) ? leave.attachments : [];
            if (attachments.length === 0) {
                wrap.classList.add('hidden');
            } else {
                wrap.classList.remove('hidden');
                attachments.forEach(function (file) {
                    const li = document.createElement('li');
                    li.className = 'pl-3 pr-4 py-2 flex items-center justify-between text-sm';
                    const name = document.createElement('span');
                    name.className = 'truncate w-0 flex-1 text-gray-500 dark:text-gray-400';
                    name.textContent = file.name || 'Attachment';
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'text-red-600 hover:text-red-800';
                    btn.textContent = 'Delete';
                    btn.addEventListener('click', function () {
                        deleteLeaveAttachment(file.id, li);
                    });
                    li.appendChild(name);
                    li.appendChild(btn);
                    list.appendChild(li);
                });
            }
        }

        openModal('edit-leave-modal');
    }

    function deleteLeaveAttachment(attachmentId, rowEl) {
        if (!confirm('Are you sure you want to delete this attachment?')) return;
        fetch(`/employee/leave-attachments/${attachmentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
        }).then(function (response) {
            return response.json();
        }).then(function (data) {
            if (data && data.success) {
                if (rowEl) rowEl.remove();
            } else {
                alert('Failed to delete attachment.');
            }
        }).catch(function () {
            alert('An error occurred.');
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        bindFilePicker('add_attachments', 'add-files-list', 'add-file-count-label', 'add');
        bindFilePicker('edit_attachments', 'edit-files-list', 'edit-file-count-label', 'edit');

        @if($errors->any())
            openModal('add-leave-modal');
        @endif
    });
</script>
@endpush
@endsection

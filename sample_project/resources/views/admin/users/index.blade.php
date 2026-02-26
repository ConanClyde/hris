@extends('layouts.dashboard')

@section('navbarTitle', 'Manage Users')

@section('content')
@php
    $role = session('role', 'admin');
    $isHrContext = $role === 'hr';
    $usersIndexRoute = $isHrContext ? 'hr.users.index' : 'admin.users';
    $usersStoreRoute = $isHrContext ? 'hr.users.store' : 'admin.users.store';
    $usersDestroyRoute = $isHrContext ? 'hr.users.destroy' : 'admin.users.destroy';
    $usersBulkActionRoute = $isHrContext ? 'hr.users.bulk_action' : 'admin.users.bulk_action';
    $usersActionBase = $isHrContext ? '/hr/users' : '/admin/users';



    $filtersActive = (string) request('search', '') !== '' || (string) request('role', '') !== '' || (string) request('user_status', '') !== '';
@endphp
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">User Management</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">View and manage system users and their roles.</p>
        </div>
        <button onclick="openModal('add-user-modal')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-[#013CFC] text-white text-sm font-medium rounded-md hover:bg-[#0031BC] transition-colors focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14"/></svg>
            Add New User
        </button>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200 dark:border-neutral-800">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ route($usersIndexRoute) }}"
               class="{{ !request('status') || request('status') == 'all' ? 'border-[#013CFC] text-[#013CFC]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                All Users
            </a>
            <a href="{{ route($usersIndexRoute, ['status' => 'pending']) }}"
               class="{{ request('status') == 'pending' ? 'border-[#013CFC] text-[#013CFC]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                Pending Approvals
                @if($pendingCount > 0)
                <span class="bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 py-0.5 px-2 rounded-full text-xs font-semibold">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route($usersIndexRoute, ['status' => 'rejected']) }}"
               class="{{ request('status') == 'rejected' ? 'border-[#013CFC] text-[#013CFC]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Rejected
            </a>
        </nav>
    </div>

    <!-- Filters -->
    <div class="border border-gray-200 dark:border-neutral-800 rounded-md bg-white dark:bg-neutral-900 p-4 shadow-sm">
        <form action="{{ route($usersIndexRoute) }}" method="GET" id="filter-form" class="flex flex-col md:flex-row gap-4 items-center">
            <!-- Maintain active tab -->
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <!-- Search -->
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="block w-full h-9 pl-10 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2"
                       placeholder="Search by name or email..."
                       oninput="debounceSearch(this)">
            </div>

            <!-- Role Filter -->
            <select name="role" onchange="this.form.submit()" class="block w-full md:w-48 h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2">
                <option value="">All Roles</option>
                <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                <option value="HR" {{ request('role') == 'HR' ? 'selected' : '' }}>HR</option>
                <option value="Employee" {{ request('role') == 'Employee' ? 'selected' : '' }}>Employee</option>
            </select>

            <!-- Conditional Status Filter -->
            @if(!request('status') || request('status') == 'all')
            <select name="user_status" onchange="this.form.submit()" class="block w-full md:w-48 h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2">
                <option value="">All Statuses</option>
                <option value="active" {{ request('user_status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('user_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @endif

            <!-- Clear Action -->
            @if($filtersActive)
                <a href="{{ route($usersIndexRoute, ['status' => request('status')]) }}" class="whitespace-nowrap inline-flex items-center justify-center h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-neutral-800 transition shadow-sm">Clear Filters</a>
            @else
                <button type="button" disabled class="whitespace-nowrap inline-flex items-center justify-center h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-400 dark:text-gray-500 rounded-md text-sm font-medium cursor-not-allowed">Clear Filters</button>
            @endif
        </form>
    </div>

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
        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800">
            <thead class="bg-gray-50 dark:bg-neutral-950">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-10">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Role & Position</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Division</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden xl:table-cell">Date Hired</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ACTION</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-neutral-800">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors cursor-pointer" onclick="openViewModal({{ json_encode($user) }})">
                    <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                        <input type="checkbox" class="user-checkbox rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]" value="{{ $user->id }}">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                <span class="h-10 w-10 rounded-full bg-[#013CFC]/10 text-[#013CFC] flex items-center justify-center text-sm font-bold">
                                    {{ substr(($user->display_name ?? $user->name ?? 'U'), 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->display_name ?? 'User' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $user->user_id }}</div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                            {{ $user->role }}
                        </span>
                        <div class="text-xs text-gray-500 mt-1">{{ $user->position ?? '—' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden lg:table-cell">
                        {{ $user->division ?? '—' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden xl:table-cell">
                        {{ $user->date_hired ? \Carbon\Carbon::parse($user->date_hired)->format('M d, Y') : \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $user->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" onclick="event.stopPropagation()">
                        <div class="flex justify-end gap-3 items-center">
                            @if($user->status === 'pending')
                                <button type="button" onclick="openViewModal({{ json_encode($user) }})" class="text-gray-400 hover:text-[#013CFC] dark:hover:text-blue-400 transition-colors" title="View Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            @elseif($user->status === 'rejected')
                                <button type="button" onclick="openDeleteModal('{{ route($usersDestroyRoute, $user->id) }}', '{{ $user->display_name ?? $user->name ?? 'User' }}')" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Delete User">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            @else
                                <button type="button" onclick="openViewModal({{ json_encode($user) }})" class="text-gray-400 hover:text-[#013CFC] dark:hover:text-blue-400 transition-colors" title="View Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                                <button type="button" onclick="openEditModal({{ json_encode($user) }})" class="text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors" title="Edit User">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-12 h-12 rounded-md bg-gray-100 dark:bg-neutral-800 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/></svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">No users found</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Try adjusting your search or filters.</p>
                            <a href="{{ route($usersIndexRoute) }}" class="mt-3 text-sm font-medium text-[#013CFC] hover:text-[#0031BC]">Clear all filters</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>

<!-- Add User Modal -->
<div id="add-user-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('add-user-modal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-2xl max-h-[90vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
            <form method="POST" action="{{ route($usersStoreRoute) }}" class="flex flex-col flex-1 min-h-0">
                @csrf
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Add New User</h3>
                    <button type="button" onclick="closeModal('add-user-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 min-h-0 overflow-y-auto p-6 space-y-4">
                    {{-- User ID --}}
                    <div>
                        <label for="add_user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">User ID</label>
                        <input type="text" name="user_id" id="add_user_id" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="add_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
                        <input type="email" name="email" id="add_email" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                    </div>

                    {{-- Personal Information --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="add_first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">First Name</label>
                            <input type="text" name="first_name" id="add_first_name" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                        </div>
                        <div>
                            <label for="add_middle_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Middle Name</label>
                            <input type="text" name="middle_name" id="add_middle_name" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="add_last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Last Name</label>
                            <input type="text" name="last_name" id="add_last_name" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                        </div>
                        <div>
                            <label for="add_name_extension" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Name Extension</label>
                            <input type="text" name="name_extension" id="add_name_extension" placeholder="e.g. Jr., Sr." class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                        </div>
                    </div>

                    {{-- Sex and DOB --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                         <div>
                            <label for="add_sex" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Sex</label>
                            <select id="add_sex" name="sex" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                                <option value="">Select Sex</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div>
                            <label for="add_date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="add_date_of_birth" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950 dark:[color-scheme:dark]">
                        </div>
                    </div>

                    {{-- Employment Details --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="add_date_hired" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Date Hired</label>
                            <input type="date" name="date_hired" id="add_date_hired" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950 dark:[color-scheme:dark]">
                        </div>
                        <div>
                            <label for="add_position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Position</label>
                            <input type="text" name="position" id="add_position" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                        </div>
                    </div>

                    <div>
                        <label for="add_classification" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Classification</label>
                        <select id="add_classification" name="classification" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                            <option value="">Select classification</option>
                            <option value="Regular">Regular</option>
                            <option value="Detailed">Detailed</option>
                            <option value="COS">COS</option>
                        </select>
                    </div>

                     {{-- Organizational Structure --}}
                    <div>
                        <label for="add_division" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Division</label>
                        <select id="add_division" name="division" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                            <option value="">Select division</option>
                        </select>
                    </div>

                    <div id="add-subdivision-wrap" class="hidden">
                        <label for="add_subdivision" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Subdivision</label>
                        <select id="add_subdivision" name="subdivision" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                            <option value="">Select subdivision</option>
                        </select>
                    </div>

                    <div>
                        <label for="add_unit_section" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Unit / Section</label>
                        <select id="add_unit_section" name="unit_section" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                            <option value="">Select division first</option>
                        </select>
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="add_role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Role</label>
                        <select id="add_role" name="role" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                            <option value="Employee">Employee</option>
                            <option value="HR">HR</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>

                    {{-- Temporary Password --}}
                    <div class="pt-2 border-t border-gray-100 dark:border-neutral-800">
                        <label for="add_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Temporary Password</label>
                        <div class="relative">
                            <input type="text" name="password" id="add_password" value="HRIS@2024" readonly class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-800/50 text-gray-500 dark:text-gray-400 px-3 py-2 pr-10 cursor-not-allowed">
                            <button type="button" class="absolute right-1.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1" onclick="navigator.clipboard.writeText(document.getElementById('add_password').value)" title="Copy to clipboard">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">User will be prompted to change this on first login.</p>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                    <button type="button" onclick="closeModal('add-user-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#013CFC]">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="edit-user-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('edit-user-modal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-2xl max-h-[90vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
            <form method="POST" id="edit-user-form" class="flex flex-col flex-1 min-h-0">
                @csrf
                @method('PUT')
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Edit User Details</h3>
                    <button type="button" onclick="closeModal('edit-user-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 min-h-0 overflow-y-auto p-6 space-y-4">
                    {{-- User ID --}}
                    <div>
                        <label for="edit_user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">User ID</label>
                        <input type="text" name="user_id" id="edit_user_id" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="edit_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
                        <input type="email" name="email" id="edit_email" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                    </div>

                    {{-- Personal Information --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">First Name</label>
                            <input type="text" name="first_name" id="edit_first_name" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                        </div>
                        <div>
                            <label for="edit_middle_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Middle Name</label>
                            <input type="text" name="middle_name" id="edit_middle_name" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Last Name</label>
                            <input type="text" name="last_name" id="edit_last_name" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                        </div>
                        <div>
                            <label for="edit_name_extension" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Name Extension</label>
                            <input type="text" name="name_extension" id="edit_name_extension" placeholder="e.g. Jr., Sr." class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                        </div>
                    </div>

                    {{-- Sex and DOB --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                         <div>
                            <label for="edit_sex" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Sex</label>
                            <select id="edit_sex" name="sex" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                                <option value="">Select Sex</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit_date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="edit_date_of_birth" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950 dark:[color-scheme:dark]">
                        </div>
                    </div>

                    {{-- Employment Details --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_date_hired" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Date Hired</label>
                            <input type="date" name="date_hired" id="edit_date_hired" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950 dark:[color-scheme:dark]">
                        </div>
                        <div>
                            <label for="edit_position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Position</label>
                            <input type="text" name="position" id="edit_position" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                        </div>
                    </div>

                    <div>
                        <label for="edit_classification" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Classification</label>
                        <select id="edit_classification" name="classification" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                            <option value="">Select classification</option>
                            <option value="Regular">Regular</option>
                            <option value="Detailed">Detailed</option>
                            <option value="COS">COS</option>
                        </select>
                    </div>

                     {{-- Organizational Structure --}}
                    <div>
                        <label for="edit_division" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Division</label>
                        <select id="edit_division" name="division" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                            <option value="">Select division</option>
                        </select>
                    </div>

                    <div id="edit-subdivision-wrap" class="hidden">
                        <label for="edit_subdivision" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Subdivision</label>
                        <select id="edit_subdivision" name="subdivision" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                            <option value="">Select subdivision</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit_unit_section" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Unit / Section</label>
                        <select id="edit_unit_section" name="unit_section" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                            <option value="">Select division first</option>
                        </select>
                    </div>

                    {{-- Role & Status --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="edit_role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Role</label>
                            <select id="edit_role" name="role" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                                <option value="Employee">Employee</option>
                                <option value="HR">HR</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>

                        <div>
                            <label for="edit_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Status</label>
                            <select id="edit_status" name="status" required class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    {{-- Change Password --}}
                    <div class="pt-2 border-t border-gray-100 dark:border-neutral-800">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">Change Password</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">New Password</label>
                                <div class="relative">
                                    <input type="password" name="new_password" id="edit_new_password" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 pr-10 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                                    <button type="button" class="absolute right-1.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1" onclick="togglePassword('edit_new_password', 'edit-password-toggle-1')">
                                        <svg id="edit-password-toggle-1-open" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <svg id="edit-password-toggle-1-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path stroke-linecap="round" stroke-linejoin="round" d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path stroke-linecap="round" stroke-linejoin="round" d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label for="edit_confirm_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Confirm Password</label>
                                <div class="relative">
                                    <input type="password" name="confirm_password" id="edit_confirm_password" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 pr-10 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                                    <button type="button" class="absolute right-1.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1" onclick="togglePassword('edit_confirm_password', 'edit-password-toggle-2')">
                                        <svg id="edit-password-toggle-2-open" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <svg id="edit-password-toggle-2-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path stroke-linecap="round" stroke-linejoin="round" d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path stroke-linecap="round" stroke-linejoin="round" d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                    <button type="button" onclick="closeModal('edit-user-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800">Cancel</button>
                    <button type="button" id="edit-save-btn" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#013CFC]">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div id="view-user-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('view-user-modal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-2xl max-h-[90vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">User Profile</h3>
                <button type="button" onclick="closeModal('view-user-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
                </button>
            </div>

            <div class="p-6 flex-1 min-h-0 overflow-y-auto space-y-6">
                <!-- Header -->
                <div class="flex items-start gap-4">
                    <div id="view_avatar" class="w-16 h-16 rounded-full bg-[#013CFC]/10 text-[#013CFC] flex items-center justify-center text-xl font-bold shrink-0">
                        <!-- Initials JS -->
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 id="view_name" class="text-lg font-bold text-gray-900 dark:text-white truncate"></h2>
                        <p id="view_email" class="text-sm text-gray-500 dark:text-gray-400 truncate"></p>
                        <div class="mt-2 flex items-center gap-2">
                            <span id="view_role" class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300"></span>
                            <span id="view_status" class="px-2.5 py-0.5 rounded-full text-xs font-semibold"></span>
                        </div>
                    </div>
                </div>

                <!-- Account & Contact -->
                 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User ID</dt>
                        <dd id="view_employee_id" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">--</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact Number</dt>
                        <dd id="view_contact" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">--</dd>
                    </div>
                 </div>

                <!-- Employment Details -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 pb-2 border-b border-gray-100 dark:border-neutral-800 mb-3">Employment Details</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Position</dt>
                            <dd id="view_position" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">--</dd>
                        </div>
                         <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date Hired</dt>
                            <dd id="view_date_hired" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">--</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Division</dt>
                            <dd id="view_division" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">--</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subdivision</dt>
                            <dd id="view_subdivision" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">--</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Section</dt>
                            <dd id="view_section" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">--</dd>
                        </div>
                         <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Classifications</dt>
                            <dd id="view_classifications" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">--</dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                <div id="view_pending_actions" class="hidden flex gap-3">
                    <button type="button" class="inline-flex items-center justify-center h-9 px-4 rounded-md text-sm font-medium bg-white dark:bg-neutral-900 border border-red-200 dark:border-red-500/30 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">Reject</button>
                    <button type="button" class="inline-flex items-center justify-center h-9 px-4 rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">Approve</button>
                </div>

                <button id="view_close_btn" onclick="closeModal('view-user-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800">Close</button>
                <button id="view_edit_btn" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#013CFC]">Edit User</button>
            </div>
        </div>
    </div>
</div>

<script>
    const usersActionBase = @json($usersActionBase);

    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    function openViewModal(user) {
        // Populate View Modal - Simple mapping since data object keys match ID suffixes
        const mapping = {
            'name': 'name',
            'email': 'email',
            'role': 'role',
            'employee_id': 'employee_id',
            'division': 'division',
            'subdivision': 'subdivision',
            'position': 'position',
            'section': 'section',
            'classifications': 'classifications',
            'contact': 'contact_number'
        };

        for (const [idSuffix, key] of Object.entries(mapping)) {
            const el = document.getElementById('view_' + idSuffix);
            if (el) el.textContent = user[key] || '--';
        }

        // Date Hired
        const dateEl = document.getElementById('view_date_hired');
        if (dateEl) dateEl.textContent = user.date_hired ? new Date(user.date_hired).toLocaleDateString() : '--';

        // Status Colors & Text
        const statusEl = document.getElementById('view_status');
        if (statusEl) {
            statusEl.textContent = user.status ? user.status.charAt(0).toUpperCase() + user.status.slice(1) : 'Unknown';
            statusEl.className = 'px-2.5 py-0.5 rounded-full text-xs font-semibold ' +
                (user.status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' :
                 (user.status === 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300' :
                  'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300'));
        }

        // Avatar
        const avatarEl = document.getElementById('view_avatar');
        if (avatarEl) avatarEl.textContent = (user.name || '?').charAt(0).toUpperCase();

        // Pending Actions / Edit Button Toggle
        const pendingActions = document.getElementById('view_pending_actions');
        const editBtn = document.getElementById('view_edit_btn');
        const closeBtn = document.getElementById('view_close_btn');
        const isPending = user.status === 'pending';

        if (pendingActions) {
            if (isPending) {
                pendingActions.classList.remove('hidden');
            } else {
                pendingActions.classList.add('hidden');
            }
        }

        if (closeBtn) {
            if (isPending) {
                closeBtn.classList.add('hidden');
            } else {
                closeBtn.classList.remove('hidden');
            }
        }

        if (editBtn) {
            if (isPending) {
                editBtn.classList.add('hidden');
            } else {
                editBtn.classList.remove('hidden');
                editBtn.onclick = function() {
                    closeModal('view-user-modal');
                    openEditModal(user);
                };
            }
        }

        openModal('view-user-modal');
    }

    function openEditModal(user) {
        // Populate Edit Modal Form
        const form = document.getElementById('edit-user-form');
        form.action = `${usersActionBase}/${user.id}`;

        // Mapping: input ID suffix -> user object key
        const fields = {
            'user_id': 'user_id',
            'first_name': 'first_name',
            'middle_name': 'middle_name',
            'last_name': 'last_name', // Mapped from transformed 'surname' or 'last_name' in controller? Controller uses 'last_name'. View used 'surname'. Let's check controller. Controller transforms to 'last_name'. View previous map used 'surname'. I should use 'last_name' if controller sends it. 
            // Wait, previous map had 'surname': 'surname'. Controller index method: 'surname' => $employee?->last_name ?? $user->last_name ?? ''.
            // So user object has 'surname'.
            'surname': 'surname', 
            'name_extension': 'name_extension',
            'email': 'email',
            'date_hired': 'date_hired',
            'position': 'position',
            'classification': 'classification',
            'sex': 'sex',
            'date_of_birth': 'date_of_birth',
            'role': 'role',
            'status': 'status'
        };

        for (const [idSuffix, key] of Object.entries(fields)) {
            const el = document.getElementById('edit_' + idSuffix);
            if (el) el.value = user[key] || '';
        }

        // Organizational Unit Population
        const divisionSelect = document.getElementById('edit_division');
        const subdivisionSelect = document.getElementById('edit_subdivision');
        const sectionSelect = document.getElementById('edit_unit_section');

        if (divisionSelect && user.division_id) {
            divisionSelect.value = user.division_id;
            
            // Trigger change to populate subdivisions
            populateSubdivisions('edit', user.division_id).then(() => {
                if (subdivisionSelect && user.subdivision_id) {
                    subdivisionSelect.value = user.subdivision_id;
                    
                    // Trigger change to populate sections
                    populateSections('edit', user.subdivision_id).then(() => {
                        if (sectionSelect && user.section_id) {
                            sectionSelect.value = user.section_id;
                        }
                    });
                } else {
                     // No subdivision, try populating sections directly from division (if applicable? Logic usually implies subdivision->section)
                     // But if subdivision is null, maybe section is direct child of division?
                     // My structure logic: Division -> Subdivision -> Section OR Division -> Section.
                     // I need to handle both paths.
                     populateSections('edit', null, user.division_id).then(() => {
                        if (sectionSelect && user.section_id) {
                            sectionSelect.value = user.section_id;
                        }
                     });
                }
            });
        }

        openModal('edit-user-modal');
    }

    // Password toggle function
    function togglePassword(inputId, toggleId) {
        const input = document.getElementById(inputId);
        const openIcon = document.getElementById(toggleId + '-open');
        const closedIcon = document.getElementById(toggleId + '-closed');
        if (!input || !openIcon || !closedIcon) return;

        if (input.type === 'password') {
            input.type = 'text';
            openIcon.classList.add('hidden');
            closedIcon.classList.remove('hidden');
        } else {
            input.type = 'password';
            openIcon.classList.remove('hidden');
            closedIcon.classList.add('hidden');
        }
    }

    // Organizational Structure Logic
    let organizationalStructure = {};

    document.addEventListener('DOMContentLoaded', function() {
        fetch('/api/v1/organizational-structure')
            .then(response => response.json())
            .then(data => {
                organizationalStructure = data;
                populateDivisions('add');
                populateDivisions('edit');
            })
            .catch(error => console.error('Error fetching structure:', error));

        setupStructureListeners('add');
        setupStructureListeners('edit');
    });

    function setupStructureListeners(prefix) {
        const divisionSelect = document.getElementById(`${prefix}_division`);
        const subdivisionSelect = document.getElementById(`${prefix}_subdivision`);

        if (divisionSelect) {
            divisionSelect.addEventListener('change', function() {
                const divisionId = this.value;
                populateSubdivisions(prefix, divisionId);
                // Also try populating sections if division has direct sections
                populateSections(prefix, null, divisionId); 
            });
        }

        if (subdivisionSelect) {
            subdivisionSelect.addEventListener('change', function() {
                const subdivisionId = this.value;
                populateSections(prefix, subdivisionId);
            });
        }
    }

    function populateDivisions(prefix) {
        const select = document.getElementById(`${prefix}_division`);
        if (!select) return;
        
        // Keep first option
        const firstOption = select.options[0];
        select.innerHTML = '';
        select.appendChild(firstOption);

        // Sort divisions by name? API returns object or array? 
        // Registration used: `Object.values(organizationalStructure).forEach(div => ...)`
        // But the API returns array of divisions.
        
        // Let's check API response structure from Registration implementation?
        // Step 1461: StructureController returns `Division::with(...)`.
        // So it is an ARRAY of Division objects.
        
        if (Array.isArray(organizationalStructure)) {
             organizationalStructure.forEach(div => {
                const option = document.createElement('option');
                option.value = div.id;
                option.textContent = div.name;
                select.appendChild(option);
            });
        }
    }

    async function populateSubdivisions(prefix, divisionId) {
        const select = document.getElementById(`${prefix}_subdivision`);
        const wrap = document.getElementById(`${prefix}-subdivision-wrap`);
        
        if (!select) return Promise.resolve();

        select.innerHTML = '<option value="">Select subdivision</option>';
        
        if (!divisionId) {
            if (wrap) wrap.classList.add('hidden');
            return Promise.resolve();
        }

        const division = organizationalStructure.find(d => d.id == divisionId);
        if (division && division.subdivisions && division.subdivisions.length > 0) {
            if (wrap) wrap.classList.remove('hidden');
            division.subdivisions.forEach(sub => {
                const option = document.createElement('option');
                option.value = sub.id;
                option.textContent = sub.name;
                select.appendChild(option);
            });
        } else {
            if (wrap) wrap.classList.add('hidden');
        }
        return Promise.resolve();
    }

    async function populateSections(prefix, subdivisionId, divisionId = null) {
        const select = document.getElementById(`${prefix}_unit_section`);
        if (!select) return Promise.resolve();

        // Save current value if needed? No, we are repopulating.
        select.innerHTML = '<option value="">Select unit/section</option>';

        let sections = [];

        if (subdivisionId) {
            // Find by subdivision
            // We need to browse structure -> division -> subdivision
            for (const div of organizationalStructure) {
                if (div.subdivisions) {
                    const sub = div.subdivisions.find(s => s.id == subdivisionId);
                    if (sub && sub.sections) {
                        sections = sub.sections;
                        break;
                    }
                }
            }
        } else if (divisionId) {
             // Find by division (direct sections)
             const div = organizationalStructure.find(d => d.id == divisionId);
             if (div && div.sections) {
                 sections = div.sections;
             }
        }

        if (sections.length > 0) {
             sections.forEach(sec => {
                const option = document.createElement('option');
                option.value = sec.id;
                option.textContent = sec.name;
                select.appendChild(option);
            });
        }
        return Promise.resolve();
    }

    // Close Modals on Escape Key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            ['add-user-modal', 'edit-user-modal', 'view-user-modal'].forEach(id => closeModal(id));
        }
    });

    // Bulk Actions Logic
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const rowCheckboxes = document.querySelectorAll('.user-checkbox');
        const bulkActionsBar = document.getElementById('bulk-actions-bar');
        const selectedCountSpan = document.getElementById('selected-count');
        const bulkActionForm = document.getElementById('bulk-action-form');
        const bulkActionInput = document.getElementById('bulk-action-input');
        const bulkUserIdsInput = document.getElementById('bulk-user-ids');

        function updateBulkActions() {
            const selectedCheckboxArray = Array.from(document.querySelectorAll('.user-checkbox:checked'));
            const selectedCount = selectedCheckboxArray.length;
            const selectedIds = selectedCheckboxArray.map(cb => cb.value);

            if (bulkActionsBar) {
            if (bulkActionsBar) {
                if (selectedCount > 0) {
                    bulkActionsBar.classList.remove('translate-y-[200%]', 'opacity-0');
                    selectedCountSpan.textContent = selectedCount + ' selected';
                    if(bulkUserIdsInput) bulkUserIdsInput.value = JSON.stringify(selectedIds);
                } else {
                    bulkActionsBar.classList.add('translate-y-[200%]', 'opacity-0');
                    if(bulkUserIdsInput) bulkUserIdsInput.value = '';
                }
            }
            }
        }

        if(selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                rowCheckboxes.forEach(cb => cb.checked = this.checked);
                updateBulkActions();
            });
        }

        rowCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                updateBulkActions();
                if(selectAllCheckbox) {
                    selectAllCheckbox.checked = document.querySelectorAll('.user-checkbox:checked').length === rowCheckboxes.length;
                }
            });
        });

        // Bulk Action Buttons
        window.submitBulkAction = function(action) {
            if (!bulkActionForm || !bulkActionInput) return;

            if (action === 'delete') {
                if (!confirm('Are you sure you want to delete the selected users? This action cannot be undone.')) {
                    return;
                }
            }

            bulkActionInput.value = action;
            // In a real app, you'd submit the form here.
            // bulkActionForm.submit();
            // For now, let's just show an alert or reload since backend isn't ready
            alert('Bulk action "' + action + '" triggered for selected users (Mock).');
            // location.reload();
        };
    });
</script>


<!-- Bulk Actions Bar -->
<!-- Bulk Actions Bar -->
<div id="bulk-actions-bar" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 px-4 py-3 rounded-md shadow-lg flex items-center gap-4 transition-all duration-300 translate-y-[200%] opacity-0 z-50">
    <span id="selected-count" class="font-medium text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">0 selected</span>
    <div class="h-6 w-px bg-gray-200 dark:bg-neutral-800"></div>
    <div class="flex gap-2 items-center">
        <button type="button" onclick="submitBulkAction('activate')" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white shadow-sm hover:bg-gray-100 hover:text-gray-900 dark:border-neutral-800 dark:bg-neutral-950 dark:hover:bg-neutral-800 dark:hover:text-gray-50 h-9 px-4 py-2 text-gray-900 dark:text-gray-100">
            Activate
        </button>
        <button type="button" onclick="submitBulkAction('deactivate')" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white shadow-sm hover:bg-gray-100 hover:text-gray-900 dark:border-neutral-800 dark:bg-neutral-950 dark:hover:bg-neutral-800 dark:hover:text-gray-50 h-9 px-4 py-2 text-gray-900 dark:text-gray-100">
            Deactivate
        </button>
        <button type="button" onclick="submitBulkAction('delete')" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-red-600 text-white shadow hover:bg-red-600/90 h-9 px-4 py-2">
            Delete
        </button>
    </div>

    <!-- Hidden Form for Bulk Actions -->
    <form id="bulk-action-form" action="{{ route($usersBulkActionRoute) }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="action" id="bulk-action-input">
        <input type="hidden" name="user_ids" id="bulk-user-ids">
    </form>
</div>

<!-- Delete User Modal -->
<div id="delete-user-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('delete-user-modal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-md flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Confirm Deletion</h3>
                <button type="button" onclick="closeModal('delete-user-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6">
                <p class="text-sm text-gray-700 dark:text-gray-300">Are you sure you want to delete <span id="delete-user-name" class="font-bold text-gray-900 dark:text-gray-100"></span>?</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">This action cannot be undone. The user will be permanently removed from the system.</p>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                <button type="button" onclick="closeModal('delete-user-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800">Cancel</button>
                <form id="delete-user-form" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(actionUrl, userName) {
        const form = document.getElementById('delete-user-form');
        form.action = actionUrl;
        document.getElementById('delete-user-name').textContent = userName;
        openModal('delete-user-modal');
    }
</script>
@endsection

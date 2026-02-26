@extends('layouts.dashboard')

@section('navbarTitle', 'PDS Management')

@section('content')
@php
    $indexRoute = 'hr.pds.index';
    $pdsPreviewRoute = 'hr.pds.preview';

    // Frontend-only defaults so the view works before backend wiring
    $employees = $employees ?? collect();

    // Adapter: If controller passes $pdsList (backend ready), map to $employees structure
    if (isset($pdsList)) {
        $employees = $pdsList->map(function($pds) {
            $emp = $pds->employee;
            return (object) [
                'id' => $emp->id,
                'userid' => $emp->user?->user_id ?? '—',
                'name' => trim($emp->first_name . ' ' . $emp->last_name),
                'full_name' => trim($emp->first_name . ' ' . $emp->last_name),
                'employee_id' => $emp->id,
                'email' => $emp->email,
                'position' => $emp->position,
                'division' => $emp->division,
                'subdivision' => $emp->subdivision,
                'unit_section' => $emp->section,
                'status' => $emp->status,
                'pds_status' => $pds->status,
                'created_at' => $pds->created_at,
                'pds_id' => $pds->id,
            ];
        });
    }

    // Organizational Structure (Sync with register.blade.php)
    $structure = [
        'Chief of Hospital Offices Division' => [
            'subdivisions' => [],
            'sections' => [
                'Legal Unit',
                'Planning Unit',
                'Information and Communications Technology Unit',
                'Public Health Unit',
                'Quality Strategic Management Office',
                'Health Information Management Section/TRAIS',
            ],
        ],
        'Treatment and Rehabilitation Division' => [
            'subdivisions' => [
                'Non-Residential Treatment & Rehabilitation' => [
                    'Medical Section',
                    'Nursing Section',
                    'Medical Social Work Section',
                    'Psychological Section',
                    'Dormitory Management Section',
                ],
                'Residential Treatment & Rehabilitation' => [
                    'Medical Section',
                    'Nursing Section',
                    'Medical Social Work Section',
                    'Psychological Section',
                    'Dormitory Management Section',
                ],
                'Ancillary Services' => [
                    'Nutrition and Dietetics Section',
                    'Clinical Laboratory Section',
                ],
            ],
            'sections' => [],
        ],
        'Finance and Administrative Division' => [
            'subdivisions' => [],
            'sections' => [
                'Human Resource Management Section',
                'Procurement Section',
                'Materials Management Section',
                'General Services Section',
                'Accounting Section',
                'Budget Section',
                'Cash, Billing, & Claim Section',
            ],
        ],
    ];

    // Sample Data Generation if empty
    if($employees->isEmpty()) {
        $sampleData = [
            [
                'id' => 101,
                'userid' => 'EMP-2024-001',
                'name' => 'Juan Dela Cruz',
                'full_name' => 'Juan Dela Cruz',
                'employee_id' => 'EMP-2024-001',
                'email' => 'juan.delacruz@example.com',
                'position' => 'Senior Developer',
                'division' => 'Finance and Administrative Division',
                'subdivision' => '',
                'unit_section' => 'Information and Communications Technology Unit',
                'status' => 'active',
                'pds_status' => 'approved',
                'created_at' => '2024-01-15',
            ],
            [
                'id' => 102,
                'userid' => 'EMP-2024-002',
                'name' => 'Maria Santos',
                'full_name' => 'Maria Santos',
                'employee_id' => 'EMP-2024-002',
                'email' => 'maria.santos@example.com',
                'position' => 'HR Specialist',
                'division' => 'Finance and Administrative Division',
                'subdivision' => '',
                'unit_section' => 'Human Resource Management Section',
                'status' => 'active',
                'pds_status' => 'pending',
                'created_at' => '2024-02-01',
            ],
        ];

        // Convert array to collection of objects
        $employees = collect(array_map(function($item) {
            return (object) $item;
        }, $sampleData));
    }

    $statusCounts = $statusCounts ?? ['active' => 3, 'pending' => 1, 'inactive' => 1];
    $pdsCounts = $pdsCounts ?? ['approved' => 3, 'no_pds' => 1, 'pending' => 1];
    $statuses = $statuses ?? ['active' => 'Active', 'pending' => 'Pending', 'inactive' => 'Inactive'];
    $pdsStatuses = $pdsStatuses ?? ['approved' => 'Approved', 'pending' => 'Pending', 'no_pds' => 'No PDS'];

    $totalEmployees = method_exists($employees, 'total')
                    ? $employees->total()
                    : $employees->count();
@endphp

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">PDS Management</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Review and manage Personal Data Sheets.</p>
        </div>
        <div class="flex items-center gap-2">
            {{-- Optional Action Button if needed, mirroring User Mgmt "Add User" --}}
            {{-- <button class="inline-flex items-center gap-2 px-4 py-2 bg-[#013CFC] text-white text-sm font-medium rounded-md hover:bg-[#0031BC] transition-colors focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Employee
            </button> --}}
        </div>
    </div>

    {{-- Stats cards (Optional but useful, kept consistent style) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Employees</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                {{ $totalEmployees ?? 0 }}
            </p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">No PDS Submitted</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                {{ $pdsCounts['no_pds'] ?? 0 }}
            </p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pending PDS</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                {{ $pdsCounts['pending'] ?? 0 }}
            </p>
        </div>
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Approved PDS</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                {{ $pdsCounts['approved'] ?? 0 }}
            </p>
        </div>
    </div>

    {{-- Filters Container --}}
    @php
        $filtersActive = (string) request('search', '') !== ''
            || (string) request('division', '') !== ''
            || (string) request('subdivision', '') !== ''
            || (string) request('unit_section', '') !== ''
            || (string) request('pds_status', '') !== '';
    @endphp
    <div class="border border-gray-200 dark:border-neutral-800 rounded-md bg-white dark:bg-neutral-900 p-4 shadow-sm">
        <form id="employee-filters" action="{{ route($indexRoute) }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">

            {{-- Search --}}
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name or ID..."
                    class="block w-full h-9 pl-10 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2"
                    oninput="debounceSearch(this)"
                >
            </div>

            {{-- Division --}}
            <select
                name="division"
                id="filter-division"
                class="block w-full md:w-40 h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2"
            >
                <option value="">All Divisions</option>
                @foreach (array_keys($structure) as $div)
                    <option value="{{ $div }}" @selected(request('division') === (string) $div)>{{ $div }}</option>
                @endforeach
            </select>

            {{-- Subdivision --}}
            <select
                name="subdivision"
                id="filter-subdivision"
                class="hidden w-full md:w-40 h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2"
            >
                <option value="">All Subdivisions</option>
            </select>

            {{-- Section --}}
            <select
                name="unit_section"
                id="filter-unit-section"
                class="hidden w-full md:w-40 h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2"
            >
                <option value="">All Sections</option>
            </select>

            {{-- PDS Status --}}
            <select
                name="pds_status"
                onchange="this.form.submit()"
                class="block w-full md:w-40 h-9 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2"
            >
                <option value="">All PDS Status</option>
                @foreach(($pdsStatuses ?? []) as $value => $label)
                    <option value="{{ $value }}" @selected(request('pds_status') === (string) $value)>{{ $label }}</option>
                @endforeach
            </select>

            {{-- Actions --}}
            @if($filtersActive)
                <a href="{{ route($indexRoute) }}" class="whitespace-nowrap inline-flex items-center justify-center h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-neutral-800 transition shadow-sm">Clear Filters</a>
            @else
                <button type="button" disabled class="whitespace-nowrap inline-flex items-center justify-center h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-400 dark:text-gray-500 rounded-md text-sm font-medium cursor-not-allowed">Clear Filters</button>
            @endif
        </form>
    </div>

    <script>
        const orgStructure = {!! json_encode($structure) !!};

        let timeout = null;
        function debounceSearch(input) {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                input.form.submit();
            }, 600);
        }

        const divSelect = document.getElementById('filter-division');
        const subWrap = document.getElementById('filter-subdivision');
        const unitSelect = document.getElementById('filter-unit-section');

        const initialDiv = "{{ request('division') }}";
        const initialSub = "{{ request('subdivision') }}";
        const initialUnit = "{{ request('unit_section') }}";

        function updateFilters(division, subdivision = '', unit = '') {
            subWrap.innerHTML = '<option value="">All Subdivisions</option>';
            unitSelect.innerHTML = '<option value="">All Sections</option>';
            subWrap.classList.add('hidden');
            unitSelect.classList.add('hidden');

            if (!division || !orgStructure[division]) return;

            const data = orgStructure[division];
            const subs = Object.keys(data.subdivisions || {});

            if (subs.length > 0) {
                subWrap.classList.remove('hidden');
                subs.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s;
                    opt.textContent = s;
                    opt.selected = (s === subdivision);
                    subWrap.appendChild(opt);
                });

                if (subdivision && data.subdivisions[subdivision]) {
                    unitSelect.classList.remove('hidden');
                    data.subdivisions[subdivision].forEach(sec => {
                        const opt = document.createElement('option');
                        opt.value = sec;
                        opt.textContent = sec;
                        opt.selected = (sec === unit);
                        unitSelect.appendChild(opt);
                    });
                }
            } else if (data.sections && data.sections.length > 0) {
                unitSelect.classList.remove('hidden');
                data.sections.forEach(sec => {
                    const opt = document.createElement('option');
                    opt.value = sec;
                    opt.textContent = sec;
                    opt.selected = (sec === unit);
                    unitSelect.appendChild(opt);
                });
            }
        }

        divSelect?.addEventListener('change', function() {
            updateFilters(this.value);
            this.form.submit();
        });

        subWrap?.addEventListener('change', function() {
            const div = divSelect.value;
            updateFilters(div, this.value);
            this.form.submit();
        });

        unitSelect?.addEventListener('change', function() {
            this.form.submit();
        });

        // Initialize on load
        if (initialDiv) {
            updateFilters(initialDiv, initialSub, initialUnit);
        }
    </script>

    {{-- Employees table --}}
    <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800">
            <thead class="bg-gray-50 dark:bg-neutral-950">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-10">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Employee</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Position</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Division</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">PDS Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-neutral-800">
                @forelse($employees as $employee)
                    @php
                        $status = $employee->status ?? 'unknown';
                        $pdsStatus = $employee->pds_status ?? 'no_pds';
                    @endphp
                    <tr
                        class="hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors cursor-pointer"
                        data-employee-id="{{ $employee->userid ?? $employee->id }}"
                        data-db-id="{{ $employee->id }}"
                        data-pds-id="{{ $employee->pds_id ?? '' }}"
                        data-pds-status="{{ $pdsStatus }}"
                        onclick="openPdsPreview(this)"
                    >
                        {{-- Checkbox --}}
                        <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                            <input type="checkbox" class="employee-checkbox rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]" value="{{ $employee->id }}">
                        </td>

                        {{-- Employee --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#013CFC]/10 text-[#013CFC] flex items-center justify-center text-sm font-bold shrink-0">
                                    {{ strtoupper(substr($employee->name ?? $employee->full_name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                        {{ $employee->name ?? $employee->full_name ?? 'Unknown' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        {{ $employee->userid ?? $employee->employee_id ?? '—' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- Position --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm text-gray-700 dark:text-gray-300 font-medium">
                                {{ $employee->position ?? '—' }}
                            </p>
                        </td>

                        {{-- Division --}}
                        <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                {{ $employee->division ?? '—' }}
                            </p>
                            <div class="flex flex-col gap-0.5 mt-0.5">
                                @if(!empty($employee->subdivision))
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $employee->subdivision }}
                                    </p>
                                @endif
                                @if(!empty($employee->unit_section))
                                    <p class="text-xs text-gray-400 dark:text-neutral-500 italic">
                                        {{ $employee->unit_section }}
                                    </p>
                                @endif
                            </div>
                        </td>



                        {{-- PDS badge --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $pdsLabelMap = [
                                    'approved' => 'Approved',
                                    'pending' => 'Pending',
                                    'no_pds' => 'No PDS',
                                ];
                                $pdsLabel = $pdsLabelMap[$pdsStatus] ?? ucfirst($pdsStatus);
                                $pdsClasses = match ($pdsStatus) {
                                    'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                    'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $pdsClasses }}">
                                {{ $pdsLabel }}
                            </span>
                        </td>

                        {{-- Action --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" onclick="event.stopPropagation()">
                            <div class="flex justify-end gap-3 items-center">
                                <button
                                    type="button"
                                    class="text-gray-400 hover:text-[#013CFC] dark:hover:text-blue-400 transition-colors"
                                    onclick="openPdsPreviewFromButton(this)"
                                    data-employee-id="{{ $employee->userid ?? $employee->id }}"
                                    data-db-id="{{ $employee->id }}"
                                    data-pds-id="{{ $employee->pds_id ?? '' }}"
                                    data-pds-status="{{ $pdsStatus }}"
                                    title="View PDS"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-md bg-gray-100 dark:bg-neutral-800 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">No employees found</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Try adjusting your search or filters.</p>
                                <a
                                    href="{{ route($indexRoute, ['status' => request('status')]) }}"
                                    class="mt-3 text-sm font-medium text-[#013CFC] hover:text-[#0031BC]"
                                >
                                    Clear all filters
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        @if (method_exists($employees, 'links'))
            {{ $employees->appends(request()->query())->links() }}
        @endif
    </div>
</div>

{{-- PDS Preview Modal --}}
<div
    id="pds-preview-modal"
    class="fixed inset-0 z-50 hidden"
    aria-modal="true"
    role="dialog"
>
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 transition-opacity" onclick="closePdsPreview()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-5xl max-h-[90vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex items-center justify-between shrink-0">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Personal Data Sheet Preview</h3>
                    <p id="pds-preview-subtitle" class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        Viewing record for ID: —
                    </p>
                </div>
                <button
                    type="button"
                    class="p-2 rounded-md text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"
                    onclick="closePdsPreview()"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-h-0 flex flex-col">
                {{-- Toolbar --}}
                <div class="px-6 py-2 border-b border-gray-100 dark:border-neutral-800 flex items-center justify-center gap-4 text-gray-500 dark:text-gray-400 shrink-0">
                    <button type="button" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-neutral-900" onclick="pdsZoomOut()" title="Zoom out">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/></svg>
                    </button>
                    <span id="pds-zoom-label" class="text-sm font-medium w-12 text-center">100%</span>
                    <button type="button" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-neutral-900" onclick="pdsZoomIn()" title="Zoom in">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/></svg>
                    </button>
                    <div class="w-px h-4 bg-gray-200 dark:bg-neutral-700 mx-2"></div>
                    <button type="button" class="text-xs hover:text-[#013CFC]" onclick="pdsZoomReset()">Reset</button>
                </div>

                {{-- Iframe Container --}}
                <div class="flex-1 bg-gray-100 dark:bg-neutral-900 overflow-hidden relative">
                    <div class="absolute inset-0 overflow-auto flex items-center justify-center p-8">
                         <iframe
                            id="pds-preview-frame"
                            class="bg-white shadow-lg transition-transform origin-top"
                            style="width: 8.5in; height: 13in; min-width: 8.5in; min-height: 13in;"
                            frameborder="0"
                        ></iframe>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                    <form id="pds-status-form" method="POST" action="{{ route('hr.pds.status') }}" class="hidden flex gap-3">
                        @csrf
                        <input type="hidden" name="user_id" id="pds-status-user-id">
                        <button type="submit" name="status" value="rejected" class="inline-flex items-center justify-center h-9 px-4 rounded-md text-sm font-medium bg-white dark:bg-neutral-900 border border-red-200 dark:border-red-500/30 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                            Reject PDS
                        </button>
                        <button type="submit" name="status" value="approved" class="inline-flex items-center justify-center h-9 px-4 rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                            Approve PDS
                        </button>
                    </form>
                    <button id="pds-footer-close-btn" type="button" onclick="closePdsPreview()" class="hidden inline-flex items-center justify-center h-9 px-4 border border-gray-300 dark:border-neutral-700 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-900 hover:bg-gray-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#013CFC] dark:focus:ring-offset-neutral-950 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let pdsZoom = 1;
    let cropper; // Keep for reference if needed, though not used here

    function openPdsPreview(row) {
        const userId = row.getAttribute('data-employee-id');
        const dbId = row.getAttribute('data-db-id');
        const pdsId = row.getAttribute('data-pds-id');
        const pdsStatus = row.getAttribute('data-pds-status');
        if (!userId) return;
        showPdsPreview(userId, pdsStatus, pdsId, dbId);
    }

    function openPdsPreviewFromButton(btn) {
        const userId = btn.getAttribute('data-employee-id');
        const dbId = btn.getAttribute('data-db-id');
        const pdsId = btn.getAttribute('data-pds-id');
        const pdsStatus = btn.getAttribute('data-pds-status');
        if (!userId) return;
        showPdsPreview(userId, pdsStatus, pdsId, dbId);
        event.stopPropagation();
    }

    function showPdsPreview(userId, pdsStatus, pdsId, dbId) {
        const modal = document.getElementById('pds-preview-modal');
        const frame = document.getElementById('pds-preview-frame');
        const subtitle = document.getElementById('pds-preview-subtitle');
        const statusUserId = document.getElementById('pds-status-user-id');
        const statusForm = document.getElementById('pds-status-form');
        const closeBtn = document.getElementById('pds-footer-close-btn');
        //const row = document.querySelector(`[data-employee-id="${userId}"]`);
        //const resolvedStatus = String(pdsStatus || row?.getAttribute('data-pds-status') || '').toLowerCase().trim();
        const resolvedStatus = String(pdsStatus || '').toLowerCase().trim();
        pdsZoom = 1;
        updatePdsZoom();

        // Set user ID for status form (this is the employee_id/pds_id needed for backend?)
        // The backend expects 'user_id' in status form for redirect message.
        if (statusUserId) statusUserId.value = userId;

        if (statusForm) {
            if (resolvedStatus === 'pending' || resolvedStatus === 'under_review') {
                statusForm.classList.remove('hidden');
                statusForm.style.display = 'flex';
            } else {
                statusForm.classList.add('hidden');
                statusForm.style.display = 'none';
            }
        }

        if (closeBtn) {
            if (resolvedStatus === 'pending' || resolvedStatus === 'under_review') {
                closeBtn.classList.add('hidden');
                closeBtn.style.display = 'none';
            } else {
                closeBtn.classList.remove('hidden');
                closeBtn.style.display = 'inline-flex';
            }
        }

        // Use the route generated in PHP or build it dynamically
        let baseUrl = {!! json_encode(route($pdsPreviewRoute)) !!};
        if (pdsId) {
            baseUrl += '?pds_id=' + encodeURIComponent(pdsId);
        } else if (dbId) {
            baseUrl += '?employee_id=' + encodeURIComponent(dbId);
        } else {
            // Fallback for mock data
            baseUrl += '?id=' + encodeURIComponent(userId);
        }

        frame.src = baseUrl;

        subtitle.textContent = 'Viewing record for ID: ' + userId;

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePdsPreview() {
        const modal = document.getElementById('pds-preview-modal');
        const frame = document.getElementById('pds-preview-frame');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        setTimeout(() => { frame.src = ''; }, 200); // clear after close animation
    }

    function updatePdsZoom() {
        const frame = document.getElementById('pds-preview-frame');
        const label = document.getElementById('pds-zoom-label');
        frame.style.transform = 'scale(' + pdsZoom + ')';
        label.textContent = Math.round(pdsZoom * 100) + '%';
    }

    function pdsZoomIn() {
        pdsZoom = Math.min(1.5, pdsZoom + 0.1);
        updatePdsZoom();
    }

    function pdsZoomOut() {
        pdsZoom = Math.max(0.5, pdsZoom - 0.1);
        updatePdsZoom();
    }

    function pdsZoomReset() {
        pdsZoom = 1;
        updatePdsZoom();
    }
</script>
@endsection

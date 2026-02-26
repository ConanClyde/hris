<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDS Preview — {{ config('app.name', 'HRIS') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function(){var s=localStorage.getItem('theme');var p=window.matchMedia('(prefers-color-scheme: dark)').matches;if(s==='dark'||(!s&&p))document.documentElement.classList.add('dark');else document.documentElement.classList.remove('dark');})();
    </script>
</head>
<body class="bg-white dark:bg-neutral-950 text-gray-900 dark:text-gray-100 p-6 font-sans text-sm">
    @php
        $employeeName = trim(($employee->first_name ?? '') . ' ' . ($employee->last_name ?? '')) ?: 'Employee';
    @endphp

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-neutral-800 pb-4">
            <div>
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Personal Data Sheet</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">CS Form No. 212 (Revised 2025) — Preview for {{ $employeeName }}</p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pds->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300' }}">
                    {{ ucfirst($pds->status ?? 'Draft') }}
                </span>
            </div>
        </div>

        {{-- Preview Content --}}
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Personal Information</h3>
                        <dl class="space-y-3">
                            <div><dt class="text-xs text-gray-500">Full Name</dt><dd class="text-sm font-medium">{{ $employeeName }}</dd></div>
                            <div><dt class="text-xs text-gray-500">Date of Birth</dt><dd class="text-sm">{{ $pds->personal?->dob ? \Carbon\Carbon::parse($pds->personal->dob)->format('F d, Y') : '—' }}</dd></div>
                            <div><dt class="text-xs text-gray-500">Place of Birth</dt><dd class="text-sm">{{ $pds->personal?->place_of_birth ?? '—' }}</dd></div>
                            <div><dt class="text-xs text-gray-500">Civil Status</dt><dd class="text-sm">{{ ucfirst($pds->personal?->civil_status ?? '—') }}</dd></div>
                            <div><dt class="text-xs text-gray-500">Citizenship</dt><dd class="text-sm">{{ $pds->personal?->citizenship_type ?? '—' }}</dd></div>
                        </dl>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Contact Details</h3>
                        <dl class="space-y-3">
                            <div><dt class="text-xs text-gray-500">Email Address</dt><dd class="text-sm">{{ $pds->personal?->email ?? '—' }}</dd></div>
                            <div><dt class="text-xs text-gray-500">Mobile No.</dt><dd class="text-sm">{{ $pds->personal?->mobile ?? '—' }}</dd></div>
                            <div><dt class="text-xs text-gray-500">Telephone No.</dt><dd class="text-sm">{{ $pds->personal?->phone ?? '—' }}</dd></div>
                        </dl>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-100 dark:border-neutral-800">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Address</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-xs text-gray-500 mb-1">Residential Address</dt>
                            <dd class="text-sm">
                                {{ implode(', ', array_filter([
                                    $pds->personal?->residential_house_block_lot,
                                    $pds->personal?->residential_street,
                                    $pds->personal?->residential_subdivision,
                                    $pds->personal?->residential_barangay,
                                    $pds->personal?->residential_city_municipality,
                                    $pds->personal?->residential_province,
                                    $pds->personal?->residential_zip
                                ])) ?: '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 mb-1">Permanent Address</dt>
                            <dd class="text-sm">
                                {{ implode(', ', array_filter([
                                    $pds->personal?->permanent_house_block_lot,
                                    $pds->personal?->permanent_street,
                                    $pds->personal?->permanent_subdivision,
                                    $pds->personal?->permanent_barangay,
                                    $pds->personal?->permanent_city_municipality,
                                    $pds->personal?->permanent_province,
                                    $pds->personal?->permanent_zip
                                ])) ?: '—' }}
                            </dd>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-100 dark:border-neutral-800">
                    <p class="text-xs text-center text-gray-400 dark:text-gray-500">
                        This is a preview of the Personal Data Sheet. Complete details are available in the printed form.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

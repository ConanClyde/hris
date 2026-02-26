@extends('layouts.dashboard')

@section('navbarTitle', 'Employee Leave Credits')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Employee Leave Credits</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage and view employee leave balances.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 rounded-md shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800">
                <thead class="bg-gray-50 dark:bg-neutral-950">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Employee</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Position/Unit</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Credits</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-neutral-800">
                    @forelse($employees as $employee)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $employee->full_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $employee->position ?? '--' }}</div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">{{ $employee->organizational_unit }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <ul class="space-y-1">
                                    @forelse($employee->leaveCredits as $credit)
                                        <li class="flex items-center text-sm">
                                            <span class="w-24 text-gray-500 dark:text-gray-400 text-xs uppercase font-semibold">{{ $credit->leave_type }}:</span>
                                            <span class="font-medium text-gray-900 dark:text-gray-100">{{ number_format($credit->balance, 2) }}</span>
                                        </li>
                                    @empty
                                        <li class="text-sm text-gray-400 italic">No credits recorded</li>
                                    @endforelse
                                </ul>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('hr.leave-credits.show', $employee->id) }}" class="text-[#013CFC] hover:text-[#0031BC] dark:text-blue-400 dark:hover:text-blue-300">
                                    View Ledger
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                No employees found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">
        {{ $employees->links() }}
    </div>
</div>
@endsection

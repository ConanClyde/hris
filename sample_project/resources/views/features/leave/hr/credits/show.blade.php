@extends('layouts.dashboard')

@section('navbarTitle', 'Leave Ledger')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">{{ $employee->full_name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $employee->position ?? 'No Position' }} — {{ $employee->organizational_unit }}</p>
        </div>
        <a href="{{ route('hr.leave-credits.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
            &larr; Back to List
        </a>
    </div>

    <!-- Balance Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($employee->leaveCredits as $credit)
        <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 rounded-md p-4 shadow-sm">
            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $credit->leave_type }}</div>
            <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($credit->balance, 2) }}</div>
            <div class="mt-1 text-xs text-green-600 dark:text-green-400 font-medium">Available</div>
        </div>
        @endforeach
    </div>

    <!-- Transaction History (All Types Mixed, sorted by date) -->
    <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 rounded-md shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800">
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Transaction History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800">
                <thead class="bg-gray-50 dark:bg-neutral-950">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Leave Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Particulars</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Adjustment</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Authorized By</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-neutral-800">
                    @php
                        // Collect all adjustments from all credits
                        $allAdjustments = collect();
                        foreach($employee->leaveCredits as $credit) {
                            foreach($credit->adjustments as $adj) {
                                $adj->leave_type_name = $credit->leave_type;
                                $allAdjustments->push($adj);
                            }
                        }
                        $sortedAdjustments = $allAdjustments->sortByDesc('created_at');
                    @endphp

                    @forelse($sortedAdjustments as $adj)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $adj->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ $adj->leave_type_name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate" title="{{ $adj->reason }}">
                                {{ $adj->reason }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium {{ $adj->amount >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $adj->amount > 0 ? '+' : '' }}{{ number_format($adj->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $adj->creator->name ?? 'System' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                No history found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

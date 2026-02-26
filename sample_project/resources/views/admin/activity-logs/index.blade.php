@extends('layouts.dashboard')

@section('navbarTitle', 'Activity Logs')

@section('content')
@php
    $logs = $logs ?? collect();
    $actions = $actions ?? [];
    $filtersActive = (string) request('user', '') !== '' || (string) request('action', '') !== '';

    $badgeClass = function (string $action): string {
        $a = strtolower(trim($action));
        if ($a === '') {
            return 'bg-gray-100 text-gray-700 dark:bg-neutral-800 dark:text-gray-200';
        }
        if (str_contains($a, 'create')) {
            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/30 dark:text-emerald-400';
        }
        if (str_contains($a, 'update') || str_contains($a, 'edit')) {
            return 'bg-amber-100 text-amber-800 dark:bg-amber-500/30 dark:text-amber-400';
        }
        if (str_contains($a, 'delete') || str_contains($a, 'remove')) {
            return 'bg-red-100 text-red-800 dark:bg-red-500/30 dark:text-red-400';
        }
        if (str_contains($a, 'login') || str_contains($a, 'sign in')) {
            return 'bg-blue-100 text-blue-800 dark:bg-blue-500/30 dark:text-blue-400';
        }
        if (str_contains($a, 'logout') || str_contains($a, 'sign out')) {
            return 'bg-gray-100 text-gray-700 dark:bg-neutral-800 dark:text-gray-200';
        }

        return 'bg-gray-100 text-gray-700 dark:bg-neutral-800 dark:text-gray-200';
    };
@endphp

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Activity Logs</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Audit trail of system activities and user actions.</p>
        </div>


    </div>

    <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4 shadow-sm">
        <form id="filters-form" action="{{ route('admin.activity-logs.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                <input id="filter-user" type="text" name="user" value="{{ request('user') }}" placeholder="Search user..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-neutral-800 rounded-md bg-white dark:bg-neutral-950 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC]">
            </div>

            <div class="w-full md:w-64">
                <select id="filter-action" name="action" class="w-full px-3 py-2 border border-gray-200 dark:border-neutral-800 rounded-md bg-white dark:bg-neutral-950 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC]">
                    <option value="">All actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>{{ $action }}</option>
                    @endforeach
                </select>
            </div>

            @if($filtersActive)
                <a href="{{ route('admin.activity-logs.index') }}" class="w-full md:w-auto inline-flex items-center justify-center h-10 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-neutral-800 transition shadow-sm">Clear Filters</a>
            @else
                <button type="button" disabled class="w-full md:w-auto inline-flex items-center justify-center h-10 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-400 dark:text-gray-500 rounded-md text-sm font-medium cursor-not-allowed">Clear Filters</button>
            @endif
        </form>
    </div>

    <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 rounded-md shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800">
                <thead class="bg-gray-50 dark:bg-neutral-800/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">IP Address</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date &amp; Time</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-neutral-800">
                    @forelse($logs as $log)
                        @php
                            $userAvatar = data_get($log, 'user.avatar');
                            $userName = data_get($log, 'user.display_name') ?? data_get($log, 'user.name');
                            $userCode = data_get($log, 'user.user_id');
                            $rowPayload = [
                                'id' => $log->id,
                                'user' => $log->user,
                                'action' => $log->action,
                                'action_badge_class' => $badgeClass($log->action),
                                'description' => $log->description,
                                'ip_address' => $log->ip_address,
                                'created_at' => $log->created_at_iso,
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/40 cursor-pointer" data-log='@json($rowPayload)'>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    @if(!empty($userAvatar))
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/'.$userAvatar) }}" alt="{{ $userName ?: 'User' }}">
                                    @else
                                        <span class="h-8 w-8 rounded-full bg-gray-100 dark:bg-neutral-800 text-gray-600 dark:text-gray-400 flex items-center justify-center text-xs font-medium">
                                            {{ strtoupper(substr($userName ?: 'U', 0, 1)) }}
                                        </span>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $userName ?: '—' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $userCode ?: '—' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass($log->action) }}">{{ $log->action ?: '—' }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                <div class="max-w-[360px] truncate" title="{{ $log->description }}">{{ $log->description ?: '—' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $log->ip_address ?: '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $log->created_at ? $log->created_at->format('M d, Y g:i A') : '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-md bg-gray-100 dark:bg-neutral-800 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m5.25 0a9.75 9.75 0 11-19.5 0 9.75 9.75 0 0119.5 0z"/></svg>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">No activity logs found</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Try adjusting filters or clearing them.</p>
                                    <a href="{{ route('admin.activity-logs.index') }}" class="mt-3 text-sm font-medium text-[#013CFC] hover:text-[#0031BC]">Clear all filters</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        @if (method_exists($logs, 'links'))
            {{ $logs->links() }}
        @endif
    </div>
</div>

<div id="modal-view-activity-log" class="fixed inset-0 z-50 hidden" data-modal="view-activity-log" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" data-modal-backdrop></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-2xl rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 translate-y-0 duration-200 flex flex-col max-h-[90vh] pointer-events-auto">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex items-center justify-between shrink-0">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Activity Log Details</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" data-modal-close="view-activity-log">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
                </button>
            </div>
            <div class="flex-1 min-h-0 overflow-y-auto p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <span id="view_user_avatar" class="h-9 w-9 rounded-full bg-gray-100 dark:bg-neutral-800 text-gray-600 dark:text-gray-400 flex items-center justify-center text-xs font-medium shrink-0">U</span>
                    <div class="min-w-0">
                        <div id="view_user_name" class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">—</div>
                        <div id="view_user_id" class="text-xs text-gray-500 dark:text-gray-400 truncate">—</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Action</p>
                        <span id="view_action_badge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-neutral-800 dark:text-gray-200">—</span>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Date &amp; Time</p>
                        <p id="view_datetime" class="font-medium text-gray-900 dark:text-gray-100">—</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">IP Address</p>
                        <p id="view_ip" class="font-medium text-gray-900 dark:text-gray-100">—</p>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-neutral-800 pt-4">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Description</p>
                    <p id="view_description" class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">—</p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                <button type="button" class="h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-neutral-800 transition shadow-sm" data-modal-close="view-activity-log">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        function openModal(id) {
            const modal = document.getElementById('modal-' + id);
            if (!modal) return;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            const modal = document.getElementById('modal-' + id);
            if (!modal) return;
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        function closeAllModals() {
            document.querySelectorAll('[data-modal]').forEach(function (m) {
                if (m.id && m.id.startsWith('modal-')) m.classList.add('hidden');
            });
            document.body.style.overflow = '';
        }

        function badgeClass(action) {
            const a = String(action || '').toLowerCase().trim();
            if (!a) return 'bg-gray-100 text-gray-700 dark:bg-neutral-800 dark:text-gray-200';
            if (a.includes('create')) return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/30 dark:text-emerald-400';
            if (a.includes('update') || a.includes('edit')) return 'bg-amber-100 text-amber-800 dark:bg-amber-500/30 dark:text-amber-400';
            if (a.includes('delete') || a.includes('remove')) return 'bg-red-100 text-red-800 dark:bg-red-500/30 dark:text-red-400';
            if (a.includes('login') || a.includes('sign in')) return 'bg-blue-100 text-blue-800 dark:bg-blue-500/30 dark:text-blue-400';
            if (a.includes('logout') || a.includes('sign out')) return 'bg-gray-100 text-gray-700 dark:bg-neutral-800 dark:text-gray-200';
            return 'bg-gray-100 text-gray-700 dark:bg-neutral-800 dark:text-gray-200';
        }

        document.querySelectorAll('[data-modal-close]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = btn.getAttribute('data-modal-close');
                if (id) closeModal(id);
            });
        });

        document.querySelectorAll('[data-modal-backdrop]').forEach(function (bd) {
            bd.addEventListener('click', function () {
                const modal = bd.closest('[data-modal]');
                const id = modal?.getAttribute('data-modal');
                if (id) closeModal(id);
            });
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeAllModals();
        });



        const filtersForm = document.getElementById('filters-form');
        const filterUser = document.getElementById('filter-user');
        const filterAction = document.getElementById('filter-action');
        let debounceTimer = null;

        if (filtersForm && filterUser) {
            filterUser.addEventListener('input', function () {
                window.clearTimeout(debounceTimer);
                debounceTimer = window.setTimeout(function () {
                    filtersForm.submit();
                }, 500);
            });
        }

        if (filtersForm && filterAction) {
            filterAction.addEventListener('change', function () {
                filtersForm.submit();
            });
        }

        function openViewModal(log) {
            const avatar = document.getElementById('view_user_avatar');
            const name = document.getElementById('view_user_name');
            const userId = document.getElementById('view_user_id');
            const actionBadge = document.getElementById('view_action_badge');
            const dt = document.getElementById('view_datetime');
            const ip = document.getElementById('view_ip');
            const desc = document.getElementById('view_description');

            const user = log?.user || {};

            if (avatar) avatar.textContent = String(user.name || 'U').charAt(0).toUpperCase();
            if (name) name.textContent = user.name || '—';
            if (userId) userId.textContent = user.user_id || '—';

            if (actionBadge) {
                actionBadge.textContent = log.action || '—';
                actionBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold ' + badgeClass(log.action);
            }

            if (dt) {
                const date = log.created_at ? new Date(log.created_at) : null;
                dt.textContent = date && !isNaN(date.getTime()) ? date.toLocaleString() : '—';
            }

            if (ip) ip.textContent = log.ip_address || '—';
            if (desc) desc.textContent = log.description || '—';

            openModal('view-activity-log');
        }

        document.querySelectorAll('tr[data-log]').forEach(function (row) {
            row.addEventListener('click', function (e) {
                if (e.target.closest('a,button,input,select,textarea,label')) return;
                let data = {};
                try {
                    data = JSON.parse(row.getAttribute('data-log') || '{}');
                } catch (_) {
                    data = {};
                }
                openViewModal(data);
            });
        });
    })();
</script>
@endpush
@endsection

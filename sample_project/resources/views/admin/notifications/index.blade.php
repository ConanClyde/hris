@extends('layouts.dashboard')

@section('navbarTitle', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Notifications</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Stay updated with the latest alerts and activities.</p>
        </div>
        <button id="page-mark-all-read" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 dark:bg-neutral-800 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-700 transition-colors">
            Mark all as read
        </button>
    </div>

    {{-- Notifications List --}}
    <div class="space-y-6">
        @forelse($notices as $notice)
        @php
            $typeClasses = [
                'info' => 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400',
                'success' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400',
                'warning' => 'bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400',
                'danger' => 'bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400',
            ];
            $iconPaths = [
                'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                'danger' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            ];
        @endphp
        <div class="bg-white dark:bg-neutral-900 shadow-sm border border-gray-200 dark:border-neutral-800 rounded-md p-4 hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors duration-150 flex items-start gap-4">
            <div class="shrink-0 w-10 h-10 rounded-full {{ $typeClasses[$notice->type] ?? $typeClasses['info'] }} flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $iconPaths[$notice->type] ?? $iconPaths['info'] }}" /></svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $notice->title }}</p>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $notice->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $notice->message }}</p>
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center py-12 text-center border-2 border-dashed border-gray-200 dark:border-neutral-800 rounded-lg">
            <div class="w-12 h-12 rounded-md bg-gray-100 dark:bg-neutral-800 flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
            </div>
            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">No new notifications</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">We'll notify you when something important happens.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $notices->links() }}
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mark all as read on the full notifications page
        const markBtn = document.getElementById('page-mark-all-read');
        if (markBtn) {
            markBtn.addEventListener('click', function () {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                markBtn.disabled = true;
                markBtn.textContent = 'Marking…';
                fetch('/api/notifications/mark-read', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken ? csrfToken.content : '',
                    },
                })
                .then(function () {
                    markBtn.textContent = 'All marked as read';
                    setTimeout(function () {
                        markBtn.textContent = 'Mark all as read';
                        markBtn.disabled = false;
                    }, 2000);
                })
                .catch(function () {
                    markBtn.textContent = 'Mark all as read';
                    markBtn.disabled = false;
                });
            });
        }

        // Realtime: prepend new notice instead of full reload
        window.addEventListener('realtime:notice-published', function (e) {
            var detail = e.detail || {};
            var listEl = document.querySelector('.space-y-6');
            var emptyEl = listEl ? listEl.querySelector('.border-dashed') : null;

            if (emptyEl) emptyEl.remove();

            var typeClasses = {
                info: 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400',
                success: 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400',
                warning: 'bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400',
                danger: 'bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400',
            };
            var iconPaths = {
                info: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                success: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                warning: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                danger: 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            };

            var type = detail.type || 'info';
            var div = document.createElement('div');
            div.className = 'bg-white dark:bg-neutral-900 shadow-sm border border-gray-200 dark:border-neutral-800 rounded-md p-4 hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors duration-150 flex items-start gap-4 ring-2 ring-blue-200 dark:ring-blue-800';
            div.innerHTML = '<div class="shrink-0 w-10 h-10 rounded-full ' + (typeClasses[type] || typeClasses.info) + ' flex items-center justify-center">' +
                '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="' + (iconPaths[type] || iconPaths.info) + '" /></svg>' +
                '</div><div class="flex-1 min-w-0"><div class="flex items-center justify-between mb-1">' +
                '<p class="text-sm font-medium text-gray-900 dark:text-gray-100">' + (detail.title || 'New Notice') + '</p>' +
                '<span class="text-xs text-gray-500 dark:text-gray-400">Just now</span></div>' +
                '<p class="text-sm text-gray-600 dark:text-gray-400">' + (detail.message || '') + '</p></div>';

            if (listEl && listEl.firstChild) {
                listEl.insertBefore(div, listEl.firstChild);
            } else if (listEl) {
                listEl.appendChild(div);
            }

            // Fade out highlight after 3s
            setTimeout(function () {
                div.classList.remove('ring-2', 'ring-blue-200', 'dark:ring-blue-800');
            }, 3000);
        });
    });
</script>
@endpush
@endsection

@extends('layouts.dashboard')

@section('navbarTitle', 'Global Notices')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Global Notices</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage system-wide announcements and alerts.</p>
        </div>
        <button onclick="openModal('create-notice-modal')"
           class="inline-flex items-center gap-2 px-4 py-2 bg-[#013CFC] text-white text-sm font-medium rounded-md hover:bg-[#0031BC] transition-all shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Create Notice
        </button>
    </div>

    @if(session('success'))
        <div id="success-alert" class="bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 px-4 py-3 rounded-md text-sm font-medium flex items-center justify-between shadow-sm border border-emerald-100 dark:border-emerald-800">
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('success-alert').remove()" class="text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
            </button>
        </div>
    @endif

    <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 rounded-md shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800">
                <thead class="bg-gray-50 dark:bg-neutral-950">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Expires At</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-neutral-800">
                    @forelse($notices as $notice)
                    @php
                        $noticeData = [
                            'id' => $notice->id,
                            'title' => $notice->title,
                            'message' => $notice->message,
                            'type' => $notice->type,
                            'is_active' => $notice->is_active,
                            'expires_at' => $notice->expires_at?->format('Y-m-d'),
                            'expires_at_formatted' => $notice->expires_at ? $notice->expires_at->format('M d, Y') : 'Never',
                            'created_at' => $notice->created_at?->format('M d, Y'),
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors cursor-pointer" onclick="openViewModal({{ json_encode($noticeData) }})">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $notice->title }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ Str::limit($notice->message, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $typeClasses = [
                                    'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                    'success' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
                                    'warning' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
                                    'danger' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeClasses[$notice->type] ?? $typeClasses['info'] }}">
                                {{ ucfirst($notice->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $notice->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300' }}">
                                {{ $notice->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $notice->expires_at ? $notice->expires_at->format('M d, Y') : 'Never' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" onclick="event.stopPropagation()">
                            <div class="flex justify-end gap-3 items-center">
                                <button type="button" onclick="openViewModal({{ json_encode($noticeData) }})" class="text-gray-400 hover:text-[#013CFC] dark:hover:text-blue-400 transition-colors" title="View Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                                <button type="button" onclick="openEditModal({{ json_encode($noticeData) }})" class="text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </button>
                                <button type="button" onclick="openDeleteModal('{{ route('hr.notices.destroy', $notice->id) }}', {{ json_encode($notice->title) }})" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-md bg-gray-100 dark:bg-neutral-800 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">No notices found</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create one to get started.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $notices->links() }}
    </div>

    <!-- Create Notice Modal -->
    <div id="create-notice-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
        <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('create-notice-modal')"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <form action="{{ route('hr.notices.store') }}" method="POST" class="relative w-full max-w-2xl flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
                @csrf

                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Create New Notice</h3>
                    <button type="button" onclick="closeModal('create-notice-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="flex-1 min-h-0 overflow-y-auto p-6 space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Title</label>
                        <input type="text" name="title" id="title" required class="block w-full h-9 rounded-md border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:border-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 transition-all">
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Message</label>
                        <textarea name="message" id="message" rows="4" required class="block w-full rounded-md border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:border-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 transition-all"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Type</label>
                            <select name="type" id="type" required class="block w-full h-9 rounded-md border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 cursor-pointer transition-all">
                                <option value="info">Info (Blue)</option>
                                <option value="success">Success (Green)</option>
                                <option value="warning">Warning (Yellow)</option>
                                <option value="danger">Danger (Red)</option>
                            </select>
                        </div>

                        <div>
                            <label for="expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Expires At (Optional)</label>
                            <input type="date" name="expires_at" id="expires_at" class="block w-full h-9 rounded-md border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 transition-all">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave blank for no expiration.</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                    <button type="button" onclick="closeModal('create-notice-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#013CFC] dark:bg-neutral-800 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-700">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] border border-transparent rounded-md shadow-sm hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#013CFC] dark:focus:ring-offset-neutral-900">
                        Create Notice
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Notice Modal -->
    <div id="view-notice-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
        <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('view-notice-modal')"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div class="relative w-full max-w-2xl max-h-[90vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Notice Details</h3>
                    <button type="button" onclick="closeModal('view-notice-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
                    </button>
                </div>
                <div class="flex-1 min-h-0 overflow-y-auto p-6 space-y-6">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</dt>
                        <dd id="view_notice_title" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100"></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Message</dt>
                        <dd id="view_notice_message" class="mt-1 text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap bg-gray-50 dark:bg-neutral-900/50 p-3 rounded-md border border-gray-100 dark:border-neutral-800"></dd>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</dt>
                            <dd id="view_notice_type" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100"></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</dt>
                            <dd id="view_notice_status" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100"></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Expires At</dt>
                            <dd id="view_notice_expires_at" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100"></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</dt>
                            <dd id="view_notice_created_at" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100"></dd>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                    <button type="button" onclick="closeModal('view-notice-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800">Close</button>
                    <button type="button" id="view_notice_edit_btn" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#013CFC]">Edit Notice</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Notice Modal -->
    <div id="edit-notice-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
        <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('edit-notice-modal')"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <form id="edit-notice-form" action="" method="POST" class="relative w-full max-w-2xl flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto max-h-[90vh] flex flex-col">
                @csrf
                @method('PUT')
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Edit Notice</h3>
                    <button type="button" onclick="closeModal('edit-notice-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
                    </button>
                </div>
                <div class="flex-1 min-h-0 overflow-y-auto p-6 space-y-6">
                    <div>
                        <label for="edit_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Title</label>
                        <input type="text" name="title" id="edit_title" required class="block w-full h-9 rounded-md border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                    </div>
                    <div>
                        <label for="edit_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Message</label>
                        <textarea name="message" id="edit_message" rows="4" required class="block w-full rounded-md border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900"></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="edit_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Type</label>
                            <select name="type" id="edit_type" required class="block w-full h-9 rounded-md border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 cursor-pointer">
                                <option value="info">Info (Blue)</option>
                                <option value="success">Success (Green)</option>
                                <option value="warning">Warning (Yellow)</option>
                                <option value="danger">Danger (Red)</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit_expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Expires At (Optional)</label>
                            <input type="date" name="expires_at" id="edit_expires_at" class="block w-full h-9 rounded-md border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave blank for no expiration.</p>
                        </div>
                    </div>
                    <div>
                        <label for="edit_is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Status</label>
                        <select name="is_active" id="edit_is_active" required class="block w-full h-9 rounded-md border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 cursor-pointer">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                    <button type="button" onclick="closeModal('edit-notice-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-700">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] border border-transparent rounded-md shadow-sm hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#013CFC] dark:focus:ring-offset-neutral-900">Update Notice</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Notice Modal -->
    <div id="delete-notice-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
        <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer transition-opacity" onclick="closeModal('delete-notice-modal')"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div class="relative w-full max-w-md flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Confirm Deletion</h3>
                    <button type="button" onclick="closeModal('delete-notice-modal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/></svg>
                    </button>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-700 dark:text-gray-300">Are you sure you want to delete <span id="delete-notice-title" class="font-bold text-gray-900 dark:text-gray-100"></span>?</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">This action cannot be undone. The notice will be permanently removed from the system.</p>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3 shrink-0">
                    <button type="button" onclick="closeModal('delete-notice-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800">Cancel</button>
                    <form id="delete-notice-form" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">Delete Notice</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const noticesUpdateBase = @json(route('hr.notices.index'));

    function openModal(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    function openViewModal(notice) {
        if (!notice) return;
        document.getElementById('view_notice_title').textContent = notice.title || '—';
        document.getElementById('view_notice_message').textContent = notice.message || '—';
        document.getElementById('view_notice_type').textContent = notice.type ? (notice.type.charAt(0).toUpperCase() + notice.type.slice(1)) : '—';
        document.getElementById('view_notice_status').textContent = notice.is_active ? 'Active' : 'Inactive';
        document.getElementById('view_notice_expires_at').textContent = notice.expires_at_formatted || 'Never';
        document.getElementById('view_notice_created_at').textContent = notice.created_at || '—';

        const editBtn = document.getElementById('view_notice_edit_btn');
        if (editBtn) {
            editBtn.onclick = function() {
                closeModal('view-notice-modal');
                openEditModal(notice);
            };
        }
        openModal('view-notice-modal');
    }

    function openEditModal(notice) {
        if (!notice) return;
        const form = document.getElementById('edit-notice-form');
        form.action = noticesUpdateBase.replace(/\/$/, '') + '/' + notice.id;

        document.getElementById('edit_title').value = notice.title || '';
        document.getElementById('edit_message').value = notice.message || '';
        document.getElementById('edit_type').value = notice.type || 'info';
        document.getElementById('edit_expires_at').value = notice.expires_at || '';
        document.getElementById('edit_is_active').value = notice.is_active ? '1' : '0';

        openModal('edit-notice-modal');
    }

    function openDeleteModal(actionUrl, noticeTitle) {
        const form = document.getElementById('delete-notice-form');
        if (form) form.action = actionUrl;
        const span = document.getElementById('delete-notice-title');
        if (span) span.textContent = noticeTitle || 'this notice';
        openModal('delete-notice-modal');
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            ['create-notice-modal', 'view-notice-modal', 'edit-notice-modal', 'delete-notice-modal'].forEach(closeModal);
        }
    });
</script>
@endpush
@endsection

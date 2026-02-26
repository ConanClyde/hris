@extends('layouts.dashboard')

@section('navbarTitle', 'Backup')

@section('content')
@php
    $files = $files ?? [];
    $backupRoutePrefix = $backupRoutePrefix ?? 'admin';
    $canManageBackup = $canManageBackup ?? true;
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Backup</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage backup files stored on the server.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-md border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 px-4 py-3 text-sm text-green-700 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
        @if($canManageBackup)
            <form method="POST" action="{{ route($backupRoutePrefix . '.backup.run') }}" class="flex items-center gap-3">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center h-9 px-4 rounded-md bg-[#013CFC] text-white text-sm font-medium hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Run Backup
                </button>
            </form>
            <form method="POST" action="{{ route($backupRoutePrefix . '.backup.upload') }}" enctype="multipart/form-data" class="flex items-center gap-3">
                @csrf
                <input type="file" name="backup" class="block w-full text-sm text-gray-700 dark:text-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 dark:file:bg-neutral-800 dark:file:text-gray-300 dark:hover:file:bg-neutral-700" required>
                <button type="submit" class="inline-flex items-center justify-center h-9 px-4 rounded-md bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-700 dark:text-gray-300 text-sm font-medium hover:bg-gray-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950 transition-colors">
                    Upload
                </button>
            </form>
        @endif
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 rounded-md shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800">
                <thead class="bg-gray-50 dark:bg-neutral-950">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Filename</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-neutral-800">
                    @forelse($files as $path)
                        @php($id = sha1($path))
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ basename($path) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-3 items-center">
                                    <a href="{{ route($backupRoutePrefix . '.backup.download', ['id' => $id]) }}" class="text-gray-400 hover:text-[#013CFC] dark:hover:text-blue-400 transition-colors" title="Download">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12"/></svg>
                                    </a>
                                    @if($canManageBackup)
                                        <form method="POST" action="{{ route($backupRoutePrefix . '.backup.restore', ['id' => $id]) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors" title="Restore">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route($backupRoutePrefix . '.backup.destroy', ['id' => $id]) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this backup?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-md bg-gray-100 dark:bg-neutral-800 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">No backups found</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Run a backup or upload an existing file.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

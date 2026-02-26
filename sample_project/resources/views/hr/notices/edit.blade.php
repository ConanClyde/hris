@extends('layouts.dashboard')

@section('navbarTitle', 'Edit Notice')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Edit Notice</h1>
        <a href="{{ route('hr.notices.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            &larr; Back to Notices
        </a>
    </div>

    <form action="{{ route('hr.notices.update', $notice->id) }}" method="POST" class="bg-white dark:bg-neutral-900 shadow-sm border border-gray-200 dark:border-neutral-800 rounded-md p-6 space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $notice->title) }}" required class="mt-1 block w-full rounded-md border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:text-gray-100 shadow-sm focus:border-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 sm:text-sm">
            @error('title')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
            <textarea name="message" id="message" rows="4" required class="mt-1 block w-full rounded-md border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:text-gray-100 shadow-sm focus:border-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 sm:text-sm">{{ old('message', $notice->message) }}</textarea>
            @error('message')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                <select name="type" id="type" required class="mt-1 block w-full rounded-md border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:text-gray-100 shadow-sm focus:border-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 sm:text-sm">
                    <option value="info" {{ old('type', $notice->type) == 'info' ? 'selected' : '' }}>Info (Blue)</option>
                    <option value="success" {{ old('type', $notice->type) == 'success' ? 'selected' : '' }}>Success (Green)</option>
                    <option value="warning" {{ old('type', $notice->type) == 'warning' ? 'selected' : '' }}>Warning (Yellow)</option>
                    <option value="danger" {{ old('type', $notice->type) == 'danger' ? 'selected' : '' }}>Danger (Red)</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expires At (Optional)</label>
                <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at', $notice->expires_at ? $notice->expires_at->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:text-gray-100 shadow-sm focus:border-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 sm:text-sm">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave blank for no expiration.</p>
                @error('expires_at')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
            <select name="is_active" id="is_active" required class="mt-1 block w-full rounded-md border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:text-gray-100 shadow-sm focus:border-[#013CFC] focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-900 sm:text-sm">
                <option value="1" {{ old('is_active', $notice->is_active) ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !old('is_active', $notice->is_active) ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('is_active')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#013CFC] hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#013CFC] dark:focus:ring-offset-neutral-900">
                Update Notice
            </button>
        </div>
    </form>
</div>
@endsection

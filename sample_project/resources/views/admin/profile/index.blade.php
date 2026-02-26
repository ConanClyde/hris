@extends('layouts.dashboard')

@section('navbarTitle', 'Profile')

@section('content')
@php
    $role = session('role', 'admin');
    $roleTitle = $role === 'hr' ? 'HR' : ($role === 'employee' ? 'Employee' : 'Administrator');
    $profileUpdateRoute = $role === 'hr' ? 'hr.profile.update' : ($role === 'employee' ? 'employee.profile.update' : 'admin.profile.update');
    $profilePasswordRoute = $role === 'hr' ? 'hr.profile.password' : ($role === 'employee' ? 'employee.profile.password' : 'admin.profile.password');
    $profileDeleteRoute = $role === 'hr' ? 'hr.profile.delete' : ($role === 'employee' ? 'employee.profile.delete' : 'admin.profile.delete');

    // Source of truth: Database (User model)
    $user = Auth::user();
    $employee = $user?->employee;

    // Get name components from database, fallback to session for newly updated data
    $dbFirstName = $employee?->first_name ?? explode(' ', $user?->name ?? '')[0] ?? '';
    $dbMiddleName = $employee?->middle_name ?? '';
    $dbSurname = $employee?->last_name ?? explode(' ', $user?->name ?? '')[1] ?? '';
    $dbNameExt = $employee?->name_extension ?? '';
    $dbEmail = $user?->email ?? '';

    // Use session for recently updated values (form repopulation), fallback to database
    $firstName = old('first_name', session('first_name', $dbFirstName));
    $middleName = old('middle_name', session('middle_name', $dbMiddleName));
    $surname = old('surname', session('surname', $dbSurname));
    $nameExt = old('name_extension', session('name_extension', $dbNameExt));
    $email = old('email', session('email', $dbEmail));

    $middlePart = $middleName ? substr($middleName, 0, 1) . '. ' : '';
    $extPart = $nameExt ? ' ' . $nameExt : '';
    $fullName = trim($firstName . ' ' . $middlePart . $surname . $extPart) ?: ($user?->name ?? 'User');

    // Member since from database created_at
    $memberSince = $user?->created_at?->format('F Y') ?? config('defaults.user.member_since', 'January 2024');
@endphp

<div class="max-w-6xl mx-auto space-y-8">
    {{-- Header --}}
    <div>
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Profile</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage your account settings.</p>
    </div>

    @if (session('success'))
        <div class="rounded-md border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 px-4 py-3 text-sm text-green-700 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">
        {{-- Big container: Info display (left) --}}
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
            <div class="p-6 lg:p-8">
                <div class="flex flex-col sm:flex-row sm:items-start gap-6">
                    <div class="w-20 h-20 rounded-md bg-[#013CFC] flex items-center justify-center shrink-0 overflow-hidden border border-gray-200 dark:border-gray-600 relative group {{ session('avatar') ? 'cursor-pointer' : '' }}"
                        {{ session('avatar') ? 'data-modal-open=view-avatar' : '' }}>
                        @if(session('avatar'))
                            <img src="{{ asset('storage/' . session('avatar')) }}" alt="Profile" class="w-full h-full object-cover" />
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                                <span class="sr-only">View</span>
                            </div>
                        @else
                            <span class="text-white font-semibold text-2xl">{{ strtoupper(substr($firstName ?: ($fullName ?: 'U'), 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $fullName ?: session('user_id', 'User') }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $roleTitle }}</p>
                        <span class="inline-flex items-center gap-1.5 mt-3 px-2.5 py-0.5 rounded-sm text-xs font-medium bg-green-50 dark:bg-green-900/40 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            Active
                        </span>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-200 dark:border-neutral-800">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-4">Account information</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">User ID</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $user?->user_id ?? session('user_id', '—') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Email</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $email }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Role</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $roleTitle }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Member since</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $memberSince }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Full name</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $fullName ?: session('user_id', 'User') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Right container: Action buttons --}}
        <div class="lg:order-last">
            <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Actions</h3>
                <button type="button" data-modal-open="edit-profile"
                    class="w-full flex items-center justify-center gap-2 h-9 px-4 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 transition-colors">
                    Edit profile
                </button>
                <button type="button" data-modal-open="change-password"
                    class="w-full flex items-center justify-center gap-2 h-9 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-md hover:bg-gray-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 transition-colors">
                    Change password
                </button>
                <button type="button" data-modal-open="activity-logs"
                    class="w-full flex items-center justify-center gap-2 h-9 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-md hover:bg-gray-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors">
                    Activity logs
                </button>
                <button type="button" data-modal-open="delete-account"
                    class="w-full flex items-center justify-center gap-2 h-9 px-4 text-sm font-medium text-red-600 dark:text-red-400 bg-white dark:bg-neutral-900 border border-red-200 dark:border-red-800 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                    Delete account
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Profile Modal --}}
<div id="modal-edit-profile" class="fixed inset-0 z-50 hidden" aria-modal="true" data-modal="edit-profile">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer" data-modal-backdrop role="button" tabindex="0" aria-label="Close modal"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-2xl max-h-[90vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto" data-modal-content>
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 shrink-0">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Edit profile</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Update your personal details.</p>
            </div>
            <form method="POST" action="{{ route($profileUpdateRoute) }}" enctype="multipart/form-data" class="flex flex-col flex-1 min-h-0" id="edit-profile-form">
                @csrf
                @method('PUT')
                <div class="flex-1 min-h-0 overflow-y-auto p-6 space-y-6">
                    <div class="flex flex-col items-center gap-3">
                        <div class="relative group">
                            <div id="avatar-preview" class="w-24 h-24 rounded-md bg-[#013CFC] flex items-center justify-center shrink-0 overflow-hidden border border-gray-200">
                                <img src="{{ session('avatar') ? asset('storage/' . session('avatar')) : '' }}" alt="Profile" class="w-full h-full object-cover {{ session('avatar') ? '' : 'hidden' }}" id="avatar-img" />
                                <span class="text-white font-semibold text-3xl {{ session('avatar') ? 'hidden' : '' }}" id="avatar-initials">{{ strtoupper(substr($firstName ?: ($fullName ?: 'U'), 0, 1)) }}</span>
                            </div>
                            <label for="avatar" class="absolute inset-0 flex items-center justify-center cursor-pointer rounded-md bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-xs font-medium">Upload</span>
                            </label>
                            <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp" class="sr-only" />
                            <input type="hidden" name="remove_avatar" id="remove_avatar" value="0">
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <p class="text-xs text-gray-500">JPG, PNG or WebP. Max 10MB.</p>
                            <button type="button" id="remove-avatar-btn" class="text-xs text-red-600 hover:text-red-700 font-medium {{ session('avatar') ? '' : 'hidden' }}">Remove photo</button>
                        </div>
                        @error('avatar')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1.5">First name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ $firstName }}"
                                class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2" />
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1.5">Middle name</label>
                            <input type="text" name="middle_name" id="middle_name" value="{{ $middleName }}"
                                class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2" />
                            @error('middle_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="surname" class="block text-sm font-medium text-gray-700 mb-1.5">Surname</label>
                            <input type="text" name="surname" id="surname" value="{{ $surname }}"
                                class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2" />
                            @error('surname')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="name_extension" class="block text-sm font-medium text-gray-700 mb-1.5">Name extension</label>
                            <input type="text" name="name_extension" id="name_extension" value="{{ $nameExt }}"
                                class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2"
                                placeholder="Jr., Sr., III" />
                            @error('name_extension')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="modal_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email address</label>
                            <input type="email" name="email" id="modal_email" value="{{ $email }}"
                                class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2" />
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 shrink-0 flex gap-3 justify-end">
                    <button type="button" data-modal-close="edit-profile"
                        class="h-9 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-md hover:bg-gray-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2">
                        Cancel
                    </button>
                    <button type="submit"
                        class="h-9 px-4 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2">
                        Save changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Change Password Modal --}}
<div id="modal-change-password" class="fixed inset-0 z-50 hidden" aria-modal="true" data-modal="change-password">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer" data-modal-backdrop role="button" tabindex="0" aria-label="Close modal"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-md max-h-[80vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto" data-modal-content>
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 shrink-0">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Change password</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Enter your current password and choose a new one.</p>
            </div>
            <form method="POST" action="{{ route($profilePasswordRoute) }}" class="flex flex-col flex-1 min-h-0">
                @csrf
                <div class="flex-1 min-h-0 overflow-y-auto p-6 space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">Current password</label>
                        <div class="relative">
                            <input type="password" name="current_password" id="current_password"
                                class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 pr-10 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2"
                                placeholder="Enter current password" autocomplete="current-password" />
                            <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1" data-password-toggle="current_password" aria-label="Toggle password visibility">
                                <svg class="w-4 h-4" data-eye-open fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <svg class="w-4 h-4 hidden" data-eye-closed fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="change_pw_password" class="block text-sm font-medium text-gray-700 mb-1.5">New password</label>
                        <div class="relative">
                            <input type="password" name="password" id="change_pw_password"
                                class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 pr-10 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2"
                                placeholder="Enter new password" autocomplete="new-password" />
                            <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1" data-password-toggle="change_pw_password" aria-label="Toggle password visibility">
                                <svg class="w-4 h-4" data-eye-open fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <svg class="w-4 h-4 hidden" data-eye-closed fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="change_pw_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirm new password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="change_pw_password_confirmation"
                                class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 pr-10 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2"
                                placeholder="Confirm new password" autocomplete="new-password" />
                            <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1" data-password-toggle="change_pw_password_confirmation" aria-label="Toggle password visibility">
                                <svg class="w-4 h-4" data-eye-open fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <svg class="w-4 h-4 hidden" data-eye-closed fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 shrink-0 flex gap-3 justify-end">
                    <button type="button" data-modal-close="change-password"
                        class="h-9 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-md hover:bg-gray-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2">
                        Cancel
                    </button>
                    <button type="submit"
                        class="h-9 px-4 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2">
                        Change password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Activity Logs Modal --}}
<div id="modal-activity-logs" class="fixed inset-0 z-50 hidden" aria-modal="true" data-modal="activity-logs">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer" data-modal-backdrop role="button" tabindex="0" aria-label="Close modal"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-md max-h-[80vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto" data-modal-content>
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 shrink-0">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Activity logs</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Your recent activity.</p>
            </div>
            <div class="flex-1 min-h-0 overflow-y-auto p-6">
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start gap-3 pb-4 border-b border-gray-100 dark:border-neutral-800 last:border-0 last:pb-0">
                        <span class="w-2 h-2 mt-1.5 rounded-full bg-[#013CFC] shrink-0"></span>
                        <div>
                            <p class="text-gray-900 dark:text-gray-100 font-medium">Logged in</p>
                            <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">2 hours ago</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 pb-4 border-b border-gray-100 dark:border-neutral-800 last:border-0 last:pb-0">
                        <span class="w-2 h-2 mt-1.5 rounded-full bg-green-500 shrink-0"></span>
                        <div>
                            <p class="text-gray-900 dark:text-gray-100 font-medium">Updated profile</p>
                            <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">1 day ago</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 pb-4 border-b border-gray-100 dark:border-neutral-800 last:border-0 last:pb-0">
                        <span class="w-2 h-2 mt-1.5 rounded-full bg-gray-400 dark:bg-gray-500 shrink-0"></span>
                        <div>
                            <p class="text-gray-900 dark:text-gray-100 font-medium">Viewed dashboard</p>
                            <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">2 days ago</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 pb-4 border-b border-gray-100 dark:border-neutral-800 last:border-0 last:pb-0">
                        <span class="w-2 h-2 mt-1.5 rounded-full bg-gray-400 dark:bg-gray-500 shrink-0"></span>
                        <div>
                            <p class="text-gray-900 dark:text-gray-100 font-medium">Changed password</p>
                            <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">5 days ago</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 pb-4 border-b border-gray-100 dark:border-neutral-800 last:border-0 last:pb-0">
                        <span class="w-2 h-2 mt-1.5 rounded-full bg-gray-400 dark:bg-gray-500 shrink-0"></span>
                        <div>
                            <p class="text-gray-900 dark:text-gray-100 font-medium">Logged in</p>
                            <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">1 week ago</p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 shrink-0">
                <button type="button" data-modal-close="activity-logs"
                    class="w-full h-9 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-md hover:bg-gray-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Delete Account Modal --}}
<div id="modal-delete-account" class="fixed inset-0 z-50 hidden" aria-modal="true" data-modal="delete-account">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer" data-modal-backdrop role="button" tabindex="0" aria-label="Close modal"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-md max-h-[80vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto" data-modal-content>
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 shrink-0">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Delete account</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">This action cannot be undone. All your data will be permanently removed.</p>
            </div>
            <form method="POST" action="{{ route($profileDeleteRoute) }}" class="flex flex-col flex-1 min-h-0">
                @csrf
                @method('DELETE')
                <div class="flex-1 min-h-0 overflow-y-auto p-6">
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">Are you sure you want to delete your account? Enter your password to confirm.</p>
                <div class="mb-0">
                    <label for="delete_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Enter your password</label>
                    <div class="relative">
                        <input type="password" name="password" id="delete_password"
                            class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 pr-10 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-[#013CFC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2"
                            placeholder="Enter password to confirm" required />
                        <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1" data-password-toggle="delete_password" aria-label="Toggle password visibility">
                            <svg class="w-4 h-4" data-eye-open fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg class="w-4 h-4 hidden" data-eye-closed fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 shrink-0 flex gap-3 justify-end">
                    <button type="button" data-modal-close="delete-account"
                        class="h-9 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-md hover:bg-gray-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2">
                        Cancel
                    </button>
                    <button type="submit"
                        class="h-9 px-4 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Delete account
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>

{{-- View Avatar Modal --}}
<div id="modal-view-avatar" class="fixed inset-0 z-50 hidden" aria-modal="true" data-modal="view-avatar">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer" data-modal-backdrop role="button" tabindex="0" aria-label="Close modal"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative inline-block max-h-[90vh] pointer-events-auto" data-modal-content>
            <button type="button" class="absolute -top-12 right-0 text-white/70 hover:text-white focus:outline-none rounded-full p-2 hover:bg-white/10 transition-colors" data-modal-close="view-avatar">
                <span class="sr-only">Close</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="flex items-center justify-center">
                @if(session('avatar'))
                    <img src="{{ asset('storage/' . session('avatar')) }}" alt="Profile" class="h-[80vh] w-auto max-w-[90vw] object-contain rounded-md shadow-2xl" />
                @else
                    {{-- Fallback if no avatar (shouldn't really happen since trigger is conditional, but safe to keep) --}}
                    <div class="w-32 h-32 rounded-md bg-[#013CFC] flex items-center justify-center">
                        <span class="text-white font-semibold text-4xl">{{ strtoupper(substr($firstName ?: ($fullName ?: 'U'), 0, 1)) }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Crop Avatar Modal (Facebook-style: square, cover, min zoom = cover) --}}
<div id="modal-crop-avatar" class="fixed inset-0 z-[60] hidden" aria-modal="true" data-modal="crop-avatar">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60 cursor-pointer" role="button" tabindex="0" aria-label="Close modal"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-[532px] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 shrink-0 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Crop Your Photo</h3>
                <button type="button" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950 rounded-md p-1" id="btn-close-crop">
                    <span class="sr-only">Close</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            {{-- 1:1 square cropper container (fills card width, 1:1) --}}
            <div id="crop-container-wrapper" class="bg-gray-50 dark:bg-neutral-900 flex items-center justify-center shrink-0 w-full" style="aspect-ratio: 1 / 1;">
                <div id="crop-container" class="w-full h-full overflow-hidden">
                    <img id="crop-image" src="" alt="Image to crop" class="block">
                </div>
            </div>

            {{-- Toolbar --}}
            <div class="px-6 py-2 border-t border-gray-100 dark:border-neutral-800 shrink-0 flex items-center justify-center gap-4 text-gray-500 dark:text-gray-400">
                <button type="button" id="btn-zoom-out" class="p-2 hover:bg-gray-100 dark:hover:bg-neutral-800 rounded-md transition-colors" title="Zoom Out">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path></svg>
                </button>
                <button type="button" id="btn-zoom-in" class="p-2 hover:bg-gray-100 dark:hover:bg-neutral-800 rounded-md transition-colors" title="Zoom In">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path></svg>
                </button>
                <button type="button" id="btn-rotate" class="p-2 hover:bg-gray-100 dark:hover:bg-neutral-800 rounded-md transition-colors" title="Rotate">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </button>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 shrink-0 flex gap-3 justify-end items-center">
                <button type="button" id="btn-cancel-crop"
                    class="h-9 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-md hover:bg-gray-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                    Cancel
                </button>
                <button type="button" id="btn-save-crop"
                    class="h-9 px-4 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                    Save Photo
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js" referrerpolicy="no-referrer"></script>
<script>
(function () {
    const openButtons = document.querySelectorAll('[data-modal-open]');
    const closeButtons = document.querySelectorAll('[data-modal-close]');
    const backdrops = document.querySelectorAll('[data-modal-backdrop]');

    function openModal(id) {
        const modal = document.getElementById('modal-' + id);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(id) {
        const modal = document.getElementById('modal-' + id);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    function closeAllModals() {
        document.querySelectorAll('[data-modal]').forEach(function (m) {
            if (m.id && m.id.startsWith('modal-')) m.classList.add('hidden');
        });
        document.body.style.overflow = '';
    }

    openButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            openModal(this.getAttribute('data-modal-open'));
        });
    });

    closeButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            closeModal(this.getAttribute('data-modal-close'));
        });
    });

    backdrops.forEach(function (bd) {
        bd.addEventListener('click', function () {
            const modal = this.closest('[data-modal]');
            if (modal) closeModal(modal.getAttribute('data-modal'));
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAllModals();
    });

    // Avatar Upload & Cropping Logic
    const avatarInput = document.getElementById('avatar');
    const removeAvatarInput = document.getElementById('remove_avatar');
    const removeAvatarBtn = document.getElementById('remove-avatar-btn');
    const avatarImg = document.getElementById('avatar-img');
    const avatarInitials = document.getElementById('avatar-initials');

    // Crop Modal Elements
    const cropModal = document.getElementById('modal-crop-avatar');
    const cropImage = document.getElementById('crop-image');
    const btnCloseCrop = document.getElementById('btn-close-crop');
    const btnCancelCrop = document.getElementById('btn-cancel-crop');
    const btnSaveCrop = document.getElementById('btn-save-crop');
    // Toolbar Elements
    const btnZoomIn = document.getElementById('btn-zoom-in');
    const btnZoomOut = document.getElementById('btn-zoom-out');
    const btnRotate = document.getElementById('btn-rotate');

    let cropper = null;
    let cropperMinZoom = 1;

    function openCropModal() {
        if (cropModal) {
            cropModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeCropModal() {
        if (cropModal) {
            cropModal.classList.add('hidden');
            document.body.style.overflow = '';
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            cropImage.src = '';
        }
    }

    if (avatarInput) {
        avatarInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file || !file.type.startsWith('image/')) return;

            // Load image into crop modal
            const reader = new FileReader();
            reader.onload = function(evt) {
                // Set the src, which will trigger the onload event below
                cropImage.src = evt.target.result;
            };

            // Initialize Cropper when image is ready
            cropImage.onload = function() {
                openCropModal();
                if (cropper) cropper.destroy();

                // Wait a tick so the modal and container have layout
                setTimeout(function() {
                    const containerEl = document.getElementById('crop-container');
                    if (!containerEl) return;

                    cropper = new Cropper(cropImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 1,
                        restore: false,
                        guides: false,
                        center: true,
                        highlight: false,
                        cropBoxMovable: false,
                        cropBoxResizable: false,
                        toggleDragModeOnDblclick: false,
                        background: false,
                        modal: true,
                        ready: function () {
                            const containerData = cropper.getContainerData();
                            const imageData = cropper.getImageData();

                            // Minimum zoom so image always COVERS the square container
                            const minZoom = Math.max(
                                containerData.width / imageData.naturalWidth,
                                containerData.height / imageData.naturalHeight
                            );

                            cropperMinZoom = minZoom;
                            cropper.zoomTo(minZoom);

                            // Make the crop box fill the entire container (no inner white frame)
                            setTimeout(function () {
                                const c = cropper.getContainerData();
                                cropper.setCropBoxData({
                                    left: 0,
                                    top: 0,
                                    width: c.width,
                                    height: c.height,
                                });
                            }, 0);
                        },
                        zoom: function (event) {
                            if (event.detail.ratio < cropperMinZoom) {
                                event.preventDefault();
                                cropper.zoomTo(cropperMinZoom);
                            }
                        },
                    });
                }, 0);
            };

            reader.readAsDataURL(file);
        });
    }

    // Toolbar Logic (zoom out clamped to cover minimum)
    if (btnZoomIn) {
        btnZoomIn.addEventListener('click', function() {
            if (cropper) cropper.zoom(0.1);
        });
    }
    if (btnZoomOut) {
        btnZoomOut.addEventListener('click', function() {
            if (!cropper) return;
            cropper.zoom(-0.1);
        });
    }
    if (btnRotate) {
        btnRotate.addEventListener('click', function() {
            if (!cropper) return;
            cropper.rotate(90);
            // Re-apply minimum zoom and full-size crop box after rotation
            setTimeout(function () {
                if (!cropper) return;
                if (cropperMinZoom) {
                    cropper.zoomTo(cropperMinZoom);
                }
                const c = cropper.getContainerData();
                cropper.setCropBoxData({
                    left: 0,
                    top: 0,
                    width: c.width,
                    height: c.height,
                });
            }, 0);
        });
    }

    // Close/Cancel Crop
    [btnCloseCrop, btnCancelCrop].forEach(btn => {
        if(btn) {
            btn.addEventListener('click', function() {
                closeCropModal();
                if (avatarInput) avatarInput.value = ''; // Clear selection on cancel
            });
        }
    });

    // Save Crop
    if (btnSaveCrop) {
        btnSaveCrop.addEventListener('click', function() {
            if (!cropper) return;

            // Get cropped canvas at full crop resolution (no downscaling)
            cropper.getCroppedCanvas({
                fillColor: '#ffffff',
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            }).toBlob(function(blob) {
                // Create new file from blob
                const fileName = 'cropped-avatar.png';
                const file = new File([blob], fileName, { type: 'image/png' });

                // Update file input with new file
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                avatarInput.files = dataTransfer.files;

                // Update Preview
                const url = URL.createObjectURL(blob);
                if (avatarImg && avatarInitials) {
                    avatarImg.src = url;
                    avatarImg.classList.remove('hidden');
                    avatarInitials.classList.add('hidden');
                }

                // Update remove button state
                if (removeAvatarInput) removeAvatarInput.value = '0';
                if (removeAvatarBtn) removeAvatarBtn.classList.remove('hidden');

                closeCropModal();
            }, 'image/png');
        });
    }

    // Remove Avatar Logic
    if (removeAvatarBtn) {
        removeAvatarBtn.addEventListener('click', function () {
            if (avatarInput) avatarInput.value = ''; // Clear file input
            if (removeAvatarInput) removeAvatarInput.value = '1'; // Set remove flag

            if (avatarImg) {
                avatarImg.src = '';
                avatarImg.classList.add('hidden');
            }
            if (avatarInitials) avatarInitials.classList.remove('hidden');

            removeAvatarBtn.classList.add('hidden');
        });
    }

    // Password toggles (event delegation - works for all modals)
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-password-toggle]');
        if (!btn) return;
        e.preventDefault();
        const inputId = btn.getAttribute('data-password-toggle');
        const input = document.getElementById(inputId);
        const open = btn.querySelector('[data-eye-open]');
        const closed = btn.querySelector('[data-eye-closed]');
        if (input && open && closed) {
            if (input.type === 'password') {
                input.type = 'text';
                open.classList.add('hidden');
                closed.classList.remove('hidden');
            } else {
                input.type = 'password';
                open.classList.remove('hidden');
                closed.classList.add('hidden');
            }
        }
    });
})();
</script>
@endpush
@endsection

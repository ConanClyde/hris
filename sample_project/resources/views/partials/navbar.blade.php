<header class="sticky top-0 z-20 flex h-16 items-center justify-between gap-4 border-b border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 px-4 shrink-0">
    <button type="button" id="sidebar-toggle" class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-[#013CFC]/[0.04] dark:hover:bg-neutral-900 rounded-md shrink-0" aria-label="Toggle sidebar">
        <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <rect x="3.5" y="4.5" width="17" height="15" rx="2" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15" />
        </svg>
    </button>
    <div class="flex items-center gap-2 hidden sm:flex">
        <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">@yield('navbarTitle', 'Dashboard')</span>
    </div>
    <div class="flex-1 min-w-0"></div>

    <div class="flex items-center gap-3">
        <button type="button" id="dark-mode-toggle" class="p-2.5 rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-[#013CFC]/[0.04] dark:hover:bg-neutral-900 transition-colors focus:outline-none" aria-label="Toggle dark mode">
            {{-- Sun icon (for light mode) --}}
            <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
            <svg class="w-[22px] h-[22px] hidden dark:block" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
        </button>
        <div class="relative" data-notifications>
            <button type="button" class="relative p-2.5 rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-[#013CFC]/[0.04] dark:hover:bg-neutral-900 transition-colors" aria-label="Notifications" data-notifications-button>
                <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9a6 6 0 10-12 0v.05-.05v.7c0 2.023-.622 3.996-2.311 5.022a23.848 23.848 0 005.454 1.31m5.714 0a3 3 0 11-5.714 0m5.714 0a24.255 24.255 0 01-5.714 0" />
                </svg>
                {{-- Unread badge (data-notifications-badge for real-time count updates) --}}
                <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-neutral-900" data-notifications-badge data-count="0" aria-hidden="true"></span>
            </button>

            <div class="hidden absolute right-0 mt-2 w-[22rem] rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 focus:outline-none z-50" role="menu" aria-label="Notifications" data-notifications-panel>
                <div class="px-5 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-[15px] font-semibold text-gray-900 dark:text-gray-100">Notifications</p>
                        <button type="button" class="text-sm font-medium text-[#013CFC] hover:text-[#0031BC] whitespace-nowrap" data-notifications-markread>
                            Mark all as read
                        </button>
                    </div>
                </div>

                <div class="max-h-96 overflow-auto" data-notifications-list>
                    {{-- Loading skeleton (shown until JS replaces content) --}}
                    <div class="px-5 py-4 space-y-3" data-notifications-skeleton>
                        @for ($i = 0; $i < 2; $i++)
                        <div class="flex gap-3.5 animate-pulse">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 dark:bg-neutral-800"></div>
                            <div class="flex-1 space-y-2 py-1">
                                <div class="h-3 bg-gray-200 dark:bg-neutral-800 rounded w-3/4"></div>
                                <div class="h-3 bg-gray-200 dark:bg-neutral-800 rounded w-1/2"></div>
                            </div>
                        </div>
                        @endfor
                    </div>
                    {{-- Empty state (hidden by default) --}}
                    <div class="hidden px-5 py-8 text-center" data-notifications-empty>
                        <svg class="w-8 h-8 text-gray-300 dark:text-neutral-600 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No notifications yet</p>
                    </div>
                </div>

                <div class="px-5 py-3 border-t border-gray-200 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-800 rounded-b-md">
                    @php
                        $navRole = session('role', 'employee');
                        $notificationsRoute = $navRole === 'admin' ? 'admin.notifications' : ($navRole === 'hr' ? 'hr.notifications' : 'employee.notifications');
                    @endphp
                    <a href="{{ route($notificationsRoute) }}" class="block text-center text-sm font-medium text-[#013CFC] hover:text-[#0031BC] transition-colors">View all notifications</a>
                </div>
            </div>
        </div>
    </div>
</header>

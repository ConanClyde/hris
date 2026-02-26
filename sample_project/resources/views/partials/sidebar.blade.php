<aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-40 flex flex-col h-screen max-h-screen w-72 lg:w-72 shrink-0 bg-white dark:bg-neutral-950 border-r border-gray-200 dark:border-neutral-800 transform -translate-x-full lg:translate-x-0 transition-all duration-200 overflow-visible">
    {{-- Header: Logo + Toggle --}}
    <div id="sidebar-header" class="flex items-center justify-between h-16 px-5 border-b border-gray-200 dark:border-neutral-800 shrink-0 min-w-0 transition-all duration-200">
        <a href="{{ route($dashboardRoute ?? 'admin.dashboard') }}" class="flex items-center gap-2.5 min-w-0 shrink-0">
            <div class="w-9 h-9 rounded-md bg-[#013CFC] flex items-center justify-center shrink-0">
                <span class="text-white font-semibold text-base">{{ substr(config('app.name', 'HRIS'), 0, 1) }}</span>
            </div>
            <span id="sidebar-brand" class="font-semibold text-base text-gray-900 dark:text-gray-100 truncate">{{ config('app.name', 'HRIS') }}</span>
        </a>
    </div>

    <nav class="flex-1 min-h-0 p-5 space-y-1.5 overflow-y-auto overflow-x-hidden overscroll-contain">
        {{-- Menu section --}}
        <p class="sidebar-label transition-all duration-200 origin-left px-3 py-1 text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Menu</p>

        @php
        $role = session('role', 'employee'); // Default to employee if role is not set
        $menuItems = [];

        if ($role === 'admin') {
            $dashboardRoute = 'admin.dashboard';
            $menuItems = [
                ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => '<rect width="7" height="9" x="3" y="3" rx="1" /><rect width="7" height="5" x="14" y="3" rx="1" /><rect width="7" height="9" x="14" y="12" rx="1" /><rect width="7" height="5" x="3" y="16" rx="1" />'],
                ['route' => 'admin.calendar', 'label' => 'Calendar', 'icon' => '<rect width="18" height="18" x="3" y="4" rx="2" ry="2" /><line x1="16" x2="16" y1="2" y2="6" /><line x1="8" x2="8" y1="2" y2="6" /><line x1="3" x2="21" y1="10" y2="10" />'],
                ['route' => 'admin.users', 'label' => 'Manage Users', 'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M22 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />'],
                ['route' => 'admin.activity-logs.index', 'label' => 'Activity Logs', 'icon' => '<path d="M8 21h12a2 2 0 0 0 2-2v-2H10v2a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v3h4" /><path d="M19 17V5a2 2 0 0 0-2-2H4" /><path d="M15 8h-5" /><path d="M15 12h-5" />'],
                ['route' => 'admin.performance.index', 'label' => 'Performance', 'icon' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />'],
                ['route' => 'admin.reports', 'label' => 'Reports & Analytics', 'icon' => '<line x1="12" x2="12" y1="20" y2="10" /><line x1="18" x2="18" y1="20" y2="4" /><line x1="6" x2="6" y1="20" y2="16" />'],
                ['route' => 'admin.backup.index', 'label' => 'Backup', 'icon' => '<ellipse cx="12" cy="5" rx="9" ry="3" /><path d="M3 5V19A9 3 0 0 0 21 19V5" /><path d="M3 12A9 3 0 0 0 21 12" />'],
                ['route' => 'admin.notices.index', 'label' => 'Global Notices', 'icon' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" /><path d="M12 8v4" /><path d="M12 16h.01" />'],
                ['route' => 'admin.notifications', 'label' => 'Notifications', 'icon' => '<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" /><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />'],
            ];
        } elseif ($role === 'hr') {
            $dashboardRoute = 'hr.dashboard';
            $menuItems = [
                ['route' => 'hr.dashboard', 'label' => 'Dashboard', 'icon' => '<rect width="7" height="9" x="3" y="3" rx="1" /><rect width="7" height="5" x="14" y="3" rx="1" /><rect width="7" height="9" x="14" y="12" rx="1" /><rect width="7" height="5" x="3" y="16" rx="1" />'],
                ['route' => 'hr.calendar', 'label' => 'Calendar', 'icon' => '<rect width="18" height="18" x="3" y="4" rx="2" ry="2" /><line x1="16" x2="16" y1="2" y2="6" /><line x1="8" x2="8" y1="2" y2="6" /><line x1="3" x2="21" y1="10" y2="10" />'],
                ['route' => 'hr.users.index', 'label' => 'Manage Users', 'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M22 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />'],
                ['route' => 'hr.pds.index', 'label' => 'PDS Management', 'icon' => '<path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" /><polyline points="14 2 14 8 20 8" /><line x1="16" x2="8" y1="13" y2="13" /><line x1="16" x2="8" y1="17" y2="17" /><line x1="10" x2="8" y1="9" y2="9" />'],
                ['route' => 'hr.leave-applications.index', 'label' => 'Leave Applications', 'icon' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M10 16h4"/><path d="M12 14v4"/>'],
                ['route' => 'hr.training.index', 'label' => 'Learning & Development', 'icon' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z" /><path d="M6 12v5c3 3 9 3 12 0v-5" />'],
                ['route' => 'hr.reports', 'label' => 'Reports & Analytics', 'icon' => '<line x1="12" x2="12" y1="20" y2="10" /><line x1="18" x2="18" y1="20" y2="4" /><line x1="6" x2="6" y1="20" y2="16" />'],
                ['route' => 'hr.notices.index', 'label' => 'Global Notices', 'icon' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" /><path d="M12 8v4" /><path d="M12 16h.01" />'],
                ['route' => 'hr.notifications', 'label' => 'Notifications', 'icon' => '<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" /><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />'],
            ];
        } else {
            // Employee
            $dashboardRoute = 'employee.dashboard';
            $menuItems = [
                ['route' => 'employee.dashboard', 'label' => 'Dashboard', 'icon' => '<rect width="7" height="9" x="3" y="3" rx="1" /><rect width="7" height="5" x="14" y="3" rx="1" /><rect width="7" height="9" x="14" y="12" rx="1" /><rect width="7" height="5" x="3" y="16" rx="1" />'],
                ['route' => 'employee.pds.index', 'label' => 'PDS', 'icon' => '<path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" /><polyline points="14 2 14 8 20 8" /><line x1="16" x2="8" y1="13" y2="13" /><line x1="16" x2="8" y1="17" y2="17" /><line x1="10" x2="8" y1="9" y2="9" />'],
                ['route' => 'employee.calendar', 'label' => 'Calendar', 'icon' => '<rect width="18" height="18" x="3" y="4" rx="2" ry="2" /><line x1="16" x2="16" y1="2" y2="6" /><line x1="8" x2="8" y1="2" y2="6" /><line x1="3" x2="21" y1="10" y2="10" />'],
                ['route' => 'employee.leave-applications.index', 'label' => 'Leave Applications', 'icon' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M10 16h4"/><path d="M12 14v4"/>'],
                ['route' => 'employee.training.index', 'label' => 'Learning & Development', 'icon' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z" /><path d="M6 12v5c3 3 9 3 12 0v-5" />'],
                ['route' => 'employee.notifications', 'label' => 'Notifications', 'icon' => '<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" /><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />'],
            ];
        }

        if ($role === 'admin') {
            $profileRoute = 'admin.profile';
            $settingsRoute = 'admin.settings';
        } elseif ($role === 'hr') {
            $profileRoute = 'hr.profile';
            $settingsRoute = 'hr.settings';
        } else {
            $profileRoute = 'employee.profile';
            $settingsRoute = 'employee.settings';
        }
        @endphp

        @foreach($menuItems as $item)
            <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-[15px] font-medium transition-all duration-200 ease-out sidebar-nav-link {{ request()->routeIs($item['route']) ? 'bg-[#013CFC]/[0.08] text-[#013CFC] dark:bg-neutral-900 dark:text-[#60C8FC]' : 'text-gray-600 dark:text-gray-400 hover:bg-[#013CFC]/[0.04] dark:hover:bg-neutral-900 hover:text-gray-900 dark:hover:text-gray-100' }}" data-sidebar-link data-route="{{ $item['route'] }}">
                <svg class="w-[22px] h-[22px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">{!! $item['icon'] !!}</svg>
                <span class="sidebar-label transition-all duration-200 origin-left">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- Footer: Profile + Logout (data attributes allow real-time JS updates) --}}
    @php
        $sidebarProfile = $sidebarProfile ?? [
        'display_name' => session('first_name', session('user_id', 'User')),
        'email' => session('email', ''),
        'initial' => strtoupper(substr(session('first_name', session('user_id', 'U')), 0, 1)),
        'avatar_url' => null,
    ];
    @endphp
    <div id="sidebar-footer" class="p-5 border-t border-gray-200 dark:border-neutral-800 shrink-0 bg-white dark:bg-neutral-950 transition-all duration-200 space-y-1">
        <a href="{{ route($profileRoute) }}" class="block p-3 rounded-md border transition-all duration-200 cursor-pointer mb-2 sidebar-profile-card {{ request()->routeIs($profileRoute) ? 'bg-[#013CFC]/[0.08] border-[#013CFC]/20 dark:bg-neutral-900 dark:border-neutral-800' : 'border-gray-200 dark:border-neutral-800 bg-gray-50/50 dark:bg-neutral-900/60 hover:bg-[#013CFC]/[0.04] dark:hover:bg-neutral-900 hover:border-[#013CFC]/20' }}" data-profile-card>
            <div class="flex items-center gap-3 sidebar-user-row">
                <div class="w-9 h-9 rounded-md bg-[#013CFC] flex items-center justify-center shrink-0 overflow-hidden sidebar-user-avatar" data-profile-avatar-wrap>
                    @if(!empty($sidebarProfile['avatar_url']))
                        <img src="{{ $sidebarProfile['avatar_url'] }}" alt="" class="w-full h-full object-cover" data-profile-avatar-img />
                        <span class="text-white font-semibold text-base hidden" data-profile-initial>{{ $sidebarProfile['initial'] }}</span>
                    @else
                        <img src="" alt="" class="hidden w-full h-full object-cover" data-profile-avatar-img />
                        <span class="text-white font-semibold text-base" data-profile-initial>{{ $sidebarProfile['initial'] }}</span>
                    @endif
                </div>
                <div class="min-w-0 flex-1 sidebar-label sidebar-user-info transition-all duration-200 origin-left">
                    <p class="text-[15px] font-medium text-gray-900 dark:text-gray-100 truncate" data-profile-name>{{ $sidebarProfile['display_name'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate" data-profile-email>{{ $sidebarProfile['email'] }}</p>
                </div>
            </div>
        </a>
        <a href="{{ route($settingsRoute) }}" class="sidebar-footer-link flex items-center gap-3 px-3 py-2.5 rounded-md text-[15px] font-medium transition-colors {{ request()->routeIs($settingsRoute) ? 'bg-[#013CFC]/[0.08] text-[#013CFC] dark:bg-neutral-900 dark:text-[#60C8FC]' : 'text-gray-700 dark:text-gray-300 hover:bg-[#013CFC]/[0.04] dark:hover:bg-neutral-900 hover:text-gray-900 dark:hover:text-gray-100' }}">
            <svg class="w-[22px] h-[22px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.47a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
            <span class="sidebar-footer-link-text">Settings</span>
        </a>
        <a href="{{ route('logout') }}" class="sidebar-logout transition-all duration-200 w-full flex items-center gap-3 px-3 py-2.5 rounded-md text-[15px] font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-700 dark:hover:text-red-300 transition-colors">
            <svg class="w-[22px] h-[22px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
            <span class="sidebar-logout-text">Log out</span>
        </a>
    </div>
</aside>

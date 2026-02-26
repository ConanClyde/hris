<script>
// Sidebar navigation: instant feedback (100–200ms) and prefetch on hover for faster transitions
(function() {
    var navLinks = document.querySelectorAll('a[data-sidebar-link]');
    var prefetched = {};
    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            navLinks.forEach(function(l) { l.classList.remove('sidebar-nav-link--loading'); });
            this.classList.add('sidebar-nav-link--loading');
            window.__sidebarNavClickTime = window.__sidebarNavClickTime || Date.now();
        });
        link.addEventListener('mouseenter', function() {
            var href = this.getAttribute('href');
            if (href && !prefetched[href]) {
                prefetched[href] = true;
                var rel = document.createElement('link');
                rel.rel = 'prefetch';
                rel.href = href;
                document.head.appendChild(rel);
            }
        });
    });
    if (window.performance && performance.getEntriesByType) {
        window.addEventListener('load', function() {
            var nav = performance.getEntriesByType('navigation')[0];
            if (nav && window.__sidebarNavClickTime) {
                var transitionMs = nav.responseStart > 0 ? (nav.loadEventEnd - window.__sidebarNavClickTime) : 0;
                window.__lastNavTransitionMs = Math.round(transitionMs);
            }
        });
    }
})();

// Dark mode toggle handled in app.js

(function() {
    const SIDEBAR_COLLAPSED = 'sidebar-collapsed';
    const collapsed = localStorage.getItem(SIDEBAR_COLLAPSED) === 'true';

    function setCollapsed(isCollapsed) {
        const sidebar = document.getElementById('sidebar');
        const labels = document.querySelectorAll('.sidebar-label');
        const userInfo = document.querySelector('.sidebar-user-info');
        const profileCard = document.querySelector('.sidebar-profile-card');
        const userRow = document.querySelector('.sidebar-user-row');
        const userAvatar = document.querySelector('.sidebar-user-avatar');
        const brand = document.getElementById('sidebar-brand');
        const sidebarHeader = document.getElementById('sidebar-header');
        const sidebarFooter = document.getElementById('sidebar-footer');
        const navLinks = document.querySelectorAll('.sidebar-nav-link');
        const logoutForm = document.querySelector('.sidebar-logout');
        const logoutText = document.querySelector('.sidebar-logout-text');
        const footerLinks = document.querySelectorAll('.sidebar-footer-link');
        const footerLinkTexts = document.querySelectorAll('.sidebar-footer-link-text');

        if (isCollapsed) {
            sidebar.classList.remove('lg:w-72');
            sidebar.classList.add('lg:w-[72px]');
            document.body.classList.add('sidebar-collapsed');

            // Adjust Header
            if (sidebarHeader) {
                sidebarHeader.classList.remove('px-5', 'justify-between');
                sidebarHeader.classList.add('justify-center', 'px-0');
            }

            // Adjust Footer
            if (sidebarFooter) {
                sidebarFooter.classList.remove('p-5', 'space-y-1');
                sidebarFooter.classList.add('p-3', 'flex', 'flex-col', 'items-center', 'space-y-1');
            }

            // Center profile section (avatar) like nav items
            if (userRow) userRow.classList.add('lg:justify-center', 'lg:gap-0');
            if (userAvatar) userAvatar.classList.add('lg:w-10', 'lg:h-10', 'lg:aspect-square');

            labels.forEach(el => {
                el.classList.add('lg:opacity-0', 'lg:w-0', 'lg:scale-95', 'lg:overflow-hidden', 'lg:ml-0');
                el.classList.remove('lg:opacity-100', 'lg:w-auto', 'lg:scale-100');
            });
            if (brand) brand.classList.add('lg:hidden');
            if (userInfo) userInfo.classList.add('lg:hidden');
            if (profileCard) {
                profileCard.classList.remove('p-3', 'rounded-md', 'border', 'border-gray-200', 'bg-gray-50/50');
                profileCard.classList.add('p-0', 'border-0', 'bg-transparent');
            }
            navLinks.forEach(a => {
                a.classList.add('lg:justify-center', 'lg:p-0', 'lg:w-10', 'lg:h-10', 'lg:aspect-square', 'lg:gap-0', 'lg:mx-auto');
                a.classList.remove('gap-3', 'px-3');
            });

            // Center nav container
            const nav = sidebar.querySelector('nav');
            if (nav) {
                nav.classList.add('lg:items-center', 'lg:px-0');
                nav.classList.remove('p-5');
            }

            // Logout: center icon, hide text
            if (logoutText) logoutText.classList.add('lg:hidden');
            const logoutBtn = logoutForm && logoutForm.tagName === 'FORM' ? logoutForm.querySelector('button') : logoutForm;
            if (logoutBtn) {
                logoutBtn.classList.add('lg:justify-center', 'lg:p-0', 'lg:w-10', 'lg:h-10', 'lg:aspect-square', 'lg:gap-0');
                logoutBtn.classList.remove('gap-3', 'px-3');
            }

            // Footer links: center icon, hide text
            footerLinkTexts.forEach(t => t.classList.add('lg:hidden'));
            footerLinks.forEach(a => {
                a.classList.add('lg:justify-center', 'lg:p-0', 'lg:w-10', 'lg:h-10', 'lg:aspect-square', 'lg:gap-0', 'lg:mx-auto');
                a.classList.remove('gap-3', 'px-3');
            });
        } else {
            sidebar.classList.add('lg:w-72');
            sidebar.classList.remove('lg:w-[72px]');
            document.body.classList.remove('sidebar-collapsed');

            // Restore Header
            if (sidebarHeader) {
                sidebarHeader.classList.add('px-5', 'justify-between');
                sidebarHeader.classList.remove('justify-center', 'px-0');
            }

            // Restore Footer
            if (sidebarFooter) {
                sidebarFooter.classList.add('p-5', 'space-y-1');
                sidebarFooter.classList.remove('p-3', 'flex', 'flex-col', 'items-center', 'space-y-1');
            }

            // Restore profile section layout
            if (userRow) userRow.classList.remove('lg:justify-center', 'lg:gap-0');
            if (userAvatar) userAvatar.classList.remove('lg:w-10', 'lg:h-10', 'lg:aspect-square');

            labels.forEach(el => {
                el.classList.remove('lg:opacity-0', 'lg:w-0', 'lg:scale-95', 'lg:overflow-hidden', 'lg:ml-0');
                el.classList.add('lg:opacity-100', 'lg:w-auto', 'lg:scale-100');
            });
            if (brand) brand.classList.remove('lg:hidden');
            if (userInfo) userInfo.classList.remove('lg:hidden');
            if (profileCard) {
                profileCard.classList.add('p-3', 'rounded-md', 'border', 'border-gray-200', 'bg-gray-50/50');
                profileCard.classList.remove('p-0', 'border-0', 'bg-transparent');
            }
            navLinks.forEach(a => {
                a.classList.remove('lg:justify-center', 'lg:p-0', 'lg:w-10', 'lg:h-10', 'lg:aspect-square', 'lg:gap-0', 'lg:mx-auto');
                a.classList.add('gap-3', 'px-3');
            });

            // Restore nav container
            const nav = sidebar.querySelector('nav');
            if (nav) {
                nav.classList.remove('lg:items-center', 'lg:px-0');
                nav.classList.add('p-5');
            }

            // Restore logout
            if (logoutText) logoutText.classList.remove('lg:hidden');
            const logoutBtn = logoutForm && logoutForm.tagName === 'FORM' ? logoutForm.querySelector('button') : logoutForm;
            if (logoutBtn) {
                logoutBtn.classList.remove('lg:justify-center', 'lg:p-0', 'lg:w-10', 'lg:h-10', 'lg:aspect-square', 'lg:gap-0');
                logoutBtn.classList.add('gap-3', 'px-3');
            }

            // Restore footer links
            footerLinkTexts.forEach(t => t.classList.remove('lg:hidden'));
            footerLinks.forEach(a => {
                a.classList.remove('lg:justify-center', 'lg:p-0', 'lg:w-10', 'lg:h-10', 'lg:aspect-square', 'lg:gap-0', 'lg:mx-auto');
                a.classList.add('gap-3', 'px-3');
            });
        }
        localStorage.setItem(SIDEBAR_COLLAPSED, isCollapsed);

        // Notify components that the layout has changed (e.g. for FullCalendar resizing)
        window.dispatchEvent(new CustomEvent('sidebar-toggled', {
            detail: { collapsed: isCollapsed }
        }));
    }

    if (collapsed) setCollapsed(true);

    const sidebarToggle = document.getElementById('sidebar-toggle');
    if (!sidebarToggle) return;

    sidebarToggle.addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const isDesktop = window.matchMedia('(min-width: 1024px)').matches;

        if (isDesktop) {
            const isCollapsed = !document.body.classList.contains('sidebar-collapsed');
            setCollapsed(isCollapsed);
            return;
        }

        const isOpen = !sidebar.classList.contains('-translate-x-full');
        if (isOpen) {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        } else {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }
    });
})();
const sidebarOverlay = document.getElementById('sidebar-overlay');
if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) sidebar.classList.add('-translate-x-full');
        this.classList.add('hidden');
    });
}

// Notifications dropdown (data-driven from API)
(function () {
    const root = document.querySelector('[data-notifications]');
    if (!root) return;

    const button = root.querySelector('[data-notifications-button]');
    const panel = root.querySelector('[data-notifications-panel]');
    const badge = root.querySelector('[data-notifications-badge]');
    const markRead = root.querySelector('[data-notifications-markread]');
    const list = root.querySelector('[data-notifications-list]');
    const skeleton = root.querySelector('[data-notifications-skeleton]');
    const emptyState = root.querySelector('[data-notifications-empty]');

    const TYPE_STYLES = {
        info:    { bg: 'bg-blue-100 dark:bg-blue-900/40', text: 'text-blue-600 dark:text-blue-400', icon: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
        success: { bg: 'bg-emerald-100 dark:bg-emerald-900/40', text: 'text-emerald-600 dark:text-emerald-400', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
        warning: { bg: 'bg-amber-100 dark:bg-amber-900/40', text: 'text-amber-600 dark:text-amber-400', icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' },
        danger:  { bg: 'bg-red-100 dark:bg-red-900/40', text: 'text-red-600 dark:text-red-400', icon: 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
    };

    function closePanel() {
        if (panel) panel.classList.add('hidden');
    }

    function togglePanel() {
        if (panel) panel.classList.toggle('hidden');
    }

    function updateBadge(count) {
        if (!badge) return;
        const n = Math.max(0, count);
        badge.dataset.count = String(n);

        if (n > 0) {
            badge.textContent = n > 99 ? '99+' : String(n);
            badge.classList.add('!w-4', '!h-4', 'text-[10px]', 'flex', 'items-center', 'justify-center');
            badge.style.display = '';
        } else {
            badge.textContent = '';
            badge.classList.remove('!w-4', '!h-4', 'text-[10px]', 'flex', 'items-center', 'justify-center');
            badge.style.display = 'none';
        }
    }

    function createNotificationEl(item) {
        const style = TYPE_STYLES[item.type] || TYPE_STYLES.info;
        const el = document.createElement('div');
        el.className = 'block px-5 py-4 hover:bg-[#013CFC]/[0.03] dark:hover:bg-neutral-900 transition-colors border-b border-gray-100 dark:border-neutral-900 last:border-0' + (item.is_read ? '' : ' bg-blue-50/30 dark:bg-blue-900/5');
        el.setAttribute('role', 'menuitem');
        el.dataset.notificationId = item.id;
        el.innerHTML = `
            <div class="flex gap-3.5">
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full ${style.bg} ${style.text}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="${style.icon}"></path></svg>
                    </span>
                </div>
                <div class="min-w-0">
                    <p class="text-[15px] text-gray-900 dark:text-gray-100 font-medium truncate">${escapeHtml(item.title)}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">${escapeHtml(item.message)}</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">${escapeHtml(item.created_at)}</p>
                </div>
            </div>`;
        return el;
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str || '';
        return div.innerHTML;
    }

    function renderItems(items) {
        if (!list) return;
        if (skeleton) skeleton.remove();
        // Remove any existing notification items
        list.querySelectorAll('[data-notification-id]').forEach(el => el.remove());

        if (items.length === 0) {
            if (emptyState) emptyState.classList.remove('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');
        items.forEach(item => {
            list.appendChild(createNotificationEl(item));
        });
    }

    function prependItem(item) {
        if (!list) return;
        if (emptyState) emptyState.classList.add('hidden');
        const el = createNotificationEl(item);
        if (list.firstChild) {
            list.insertBefore(el, list.firstChild);
        } else {
            list.appendChild(el);
        }
    }

    // Fetch notifications on load
    function fetchNotifications() {
        fetch('/api/notifications', { credentials: 'same-origin' })
            .then(res => res.ok ? res.json() : Promise.reject(res))
            .then(data => {
                renderItems(data.items || []);
                updateBadge(data.unread_count || 0);
            })
            .catch(() => {
                if (skeleton) skeleton.remove();
                if (emptyState) emptyState.classList.remove('hidden');
            });
    }

    // Toggle
    if (button) {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            togglePanel();
        });
    }

    // Mark all as read
    if (markRead) {
        markRead.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            fetch('/api/notifications/mark-read', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken ? csrfToken.content : '',
                },
            })
            .then(res => res.ok ? res.json() : Promise.reject(res))
            .then(() => {
                updateBadge(0);
                // Remove unread highlight
                if (list) list.querySelectorAll('[data-notification-id]').forEach(el => {
                    el.classList.remove('bg-blue-50/30', 'dark:bg-blue-900/5');
                });
                closePanel();
            })
            .catch(() => {
                // Fallback: still visually clear
                updateBadge(0);
                closePanel();
            });
        });
    }

    // Close on outside click or Escape
    document.addEventListener('click', closePanel);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closePanel();
    });

    // Real-time: prepend new notice
    window.addEventListener('realtime:notice-published', function (e) {
        const detail = e.detail || {};
        prependItem({
            id: detail.id || Date.now(),
            title: detail.title || 'New Notice',
            message: detail.message || '',
            type: detail.type || 'info',
            is_read: false,
            created_at: 'Just now',
        });

        const current = parseInt(badge?.dataset?.count || '0', 10) || 0;
        updateBadge(current + 1);
    });

    // Init
    fetchNotifications();
})();

</script>

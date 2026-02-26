import './bootstrap';
import './perf-metrics';

// Centralized Dark Mode Logic
window.toggleTheme = function() {
    const html = document.documentElement;
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    
    // Dispatch event for other components to react
    window.dispatchEvent(new CustomEvent('theme-changed', { 
        detail: { isDark } 
    }));
};

// Initialize and Listen
document.addEventListener('DOMContentLoaded', () => {
    // 1. Set initial state for toggles based on current generic theme
    const isDark = document.documentElement.classList.contains('dark');
    updateToggles(isDark);

    // 2. Listen for theme changes (from other tabs or components)
    window.addEventListener('theme-changed', (e) => {
        updateToggles(e.detail.isDark);
        if (e.detail.isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    });

    // 3. Handle Global Toggles (Navbar, Auth, Settings)
    const toggles = document.querySelectorAll('#dark-mode-toggle, #auth-dark-toggle, .js-theme-toggle');
    toggles.forEach(btn => {
        // Prevent multiple listeners if id selector overlaps with class
        if (btn.dataset.themeListener) return;
        btn.dataset.themeListener = 'true';
        
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            window.toggleTheme();
        });
    });

    // 4. Lazy-load Echo (code-split) after first paint to reduce initial parse/network
    if (typeof requestIdleCallback !== 'undefined') {
        requestIdleCallback(() => import('./echo').catch(() => {}), { timeout: 3000 });
    } else {
        setTimeout(() => import('./echo').catch(() => {}), 500);
    }
});

function updateToggles(isDark) {
    // Update generic aria-checked attributes for theme toggles
    const toggles = document.querySelectorAll('#dark-mode-toggle, #auth-dark-toggle, .js-theme-toggle');
    toggles.forEach(el => {
        el.setAttribute('aria-checked', isDark);
    });
}

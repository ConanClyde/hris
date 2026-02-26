/**
 * Client-Side Performance Monitoring
 * Collects Navigation Timing and Web Vitals (FCP, LCP) and sends to backend.
 */

(function () {
    // Guard: Run only once per page load
    if (window.__perfMetricsInitialized) return;
    window.__perfMetricsInitialized = true;

    const metrics = {
        fcp: null,
        lcp: null,
        cls: 0,
    };

    // Observe First Contentful Paint (FCP)
    new PerformanceObserver((entryList) => {
        for (const entry of entryList.getEntriesByName('first-contentful-paint')) {
            metrics.fcp = Math.round(entry.startTime);
        }
    }).observe({ type: 'paint', buffered: true });

    // Observe Largest Contentful Paint (LCP)
    new PerformanceObserver((entryList) => {
        const entries = entryList.getEntries();
        const lastEntry = entries[entries.length - 1];
        if (lastEntry) {
            metrics.lcp = Math.round(lastEntry.startTime);
        }
    }).observe({ type: 'largest-contentful-paint', buffered: true });

    // Observe Cumulative Layout Shift (CLS)
    new PerformanceObserver((entryList) => {
        for (const entry of entryList.getEntries()) {
            if (!entry.hadRecentInput) {
                metrics.cls += entry.value;
            }
        }
    }).observe({ type: 'layout-shift', buffered: true });

    // Send metrics when the page is unloaded or hidden
    function sendMetrics() {
        if (window.__perfMetricsSent) return;
        window.__perfMetricsSent = true;

        // Collect Navigation Timing
        const navEntry = performance.getEntriesByType('navigation')[0];

        const payload = {
            route: window.location.pathname,
            timestamp: Date.now(),
            fcp: metrics.fcp,
            lcp: metrics.lcp,
            cls: Math.round(metrics.cls * 1000) / 1000,
            // Navigation Timing (all in ms)
            ttfb: navEntry ? Math.round(navEntry.responseStart - navEntry.requestStart) : null,
            dom_ready: navEntry ? Math.round(navEntry.domContentLoadedEventEnd - navEntry.startTime) : null,
            page_load: navEntry ? Math.round(navEntry.loadEventEnd - navEntry.startTime) : null,
            // Sidebar click-to-load transition (if available)
            nav_transition_ms: typeof window.__lastNavTransitionMs === 'number' ? window.__lastNavTransitionMs : null,
            // Context
            user_id: window.HRIS_USER_ID || null,
            role: window.HRIS_ROLE || null,
        };

        // Use sendBeacon for reliable delivery during unload
        const blob = new Blob([JSON.stringify(payload)], { type: 'application/json' });
        const ok = navigator.sendBeacon('/api/perf-metrics', blob);
        if (!ok) {
            fetch('/api/perf-metrics', {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
                keepalive: true,
            }).catch(() => {});
        }
    }

    // Send on visibility change (desktop/mobile compatible) or unload
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden') {
            sendMetrics();
        }
    });

    // Also try on pagehide for older browser compatibility
    window.addEventListener('pagehide', sendMetrics);

})();

# Performance Optimization Guide

This document describes the performance work done to keep page loads under **3 seconds** on standard broadband and how to measure and verify it.

---

## Summary of Changes

| Area | Change |
|------|--------|
| **Blocking scripts** | Frappe Charts removed from global layout; loaded only on pages that render charts (dashboard, reports), via a lazy loader and queue. |
| **Fonts** | Google Fonts use `display=swap`; preconnect retained. No render-blocking font CSS. |
| **JavaScript** | Echo (Laravel Echo + Pusher) is code-split and loaded after first paint via `requestIdleCallback` / `setTimeout`. |
| **Profile sync** | `/api/me` and SSE `/sse/profile` are deferred (requestIdleCallback / 800ms timeout) so they don’t compete with initial paint. |
| **Response size** | `CompressResponse` middleware gzips HTML/JSON when the client sends `Accept-Encoding: gzip`. |
| **Caching** | `CacheControl` middleware sets `Cache-Control` and `Vary` on HTML so caches and CDNs behave correctly. |
| **Sidebar navigation** | Instant visual feedback (100–200 ms): click adds `sidebar-nav-link--loading`; prefetch on hover via `<link rel="prefetch">` for sidebar links so next page loads faster. |
| **Backend / Reverb** | Global notices cached 60s (`global_notices_active`); cache invalidated on notice create/update/delete. Users table indexed on `role` and `is_active`. All broadcast events use `ShouldBroadcast` (queued) so HTTP response returns under ~500 ms; run `php artisan queue:work` for delivery. |

---

## Sidebar Navigation Targets

- **Click feedback**: Sidebar menu item shows loading state within **100–200 ms** (CSS class + optional pulse).
- **Page transition**: Full navigation (click → load complete) target **1–2 s** on standard broadband; prefetch on hover improves repeat visits.
- **Monitoring**: After a full page load following a sidebar click, `window.__lastNavTransitionMs` holds the approximate transition time (ms). In local env, `Server-Timing` response header and `PERF request` log include `duration_ms` and `db_query_count`.

---

## Backend / Reverb Response Time

- **Target**: Mutating actions (save and return) complete in **under 500 ms**.
- **Measures**: Broadcast events are queued (`ShouldBroadcast`); run a queue worker so broadcasts don’t block the HTTP response. Database: `users.role` and `users.is_active` indexed for fast filtering. Global notices are cached and invalidated on write.
- **Queue**: For real-time delivery, run `php artisan queue:work` (or your scheduler); see `laravel.reverb.echo-pusher.md`.

---

## Before / After Metrics (How to Measure)

Use the same conditions for before/after: same route, same network (e.g. “Fast 3G” or “Broadband” in DevTools), no cache for HTML, cache allowed for static assets.

### 1. Chrome DevTools (Lighthouse + Network)

- **Lighthouse** (Performance): run on a dashboard page (e.g. `/admin/dashboard` or `/employee/dashboard`). Compare:
  - **Before**: Note “First Contentful Paint (FCP)”, “Largest Contentful Paint (LCP)”, “Time to Interactive (TTI)”, “Total Blocking Time (TBT)”.
  - **After**: Same metrics; target LCP and TTI within 3s on broadband.
- **Network**:
  - Throttle to “Fast 3G” or “Slow 4G” if you want to simulate weaker links.
  - Reload the page; check “Load” time and number of requests.
  - **Before**: Frappe script was on every page; Echo in main bundle.
  - **After**: Frappe only on chart pages; Echo in a separate chunk loaded after idle; fewer/cheaper requests at parse time.

### 2. Suggested Targets (Standard Broadband)

- **LCP**: &lt; 2.5 s  
- **FCP**: &lt; 1.5 s  
- **TTI**: &lt; 3 s  
- **Total load (Network “Load”)**: &lt; 3 s for main dashboard/list pages

### 3. Example Before/After (Placeholder)

Fill these in after measuring on your target URL (e.g. `/admin/dashboard`):

| Metric | Before | After | Target |
|--------|--------|-------|--------|
| LCP | — | — | &lt; 2.5 s |
| FCP | — | — | &lt; 1.5 s |
| TTI | — | — | &lt; 3 s |
| DOMContentLoaded | — | — | — |
| Full load (Network) | — | — | &lt; 3 s |

---

## Verification Checklist

- [ ] **Lighthouse**  
  Run Performance audit on 2–3 key routes (e.g. dashboard, users list, calendar). No major regressions; LCP/TTI within targets above.

- [ ] **Network**  
  - No unnecessary render-blocking script on pages that don’t need it (e.g. no Frappe on login or simple list pages).  
  - Echo chunk loads after main app bundle (check “Network” by name or size).  
  - HTML (or API) response has `Content-Encoding: gzip` when client sends `Accept-Encoding: gzip`.

- [ ] **Functionality**  
  - Charts still render on dashboard and reports (Frappe loads and queue runs).  
  - Real-time (Echo) still works after a few seconds (notifications, profile updates if used).  
  - Theme toggle and sidebar behave the same.  
  - Profile sidebar and SSE profile updates still work (may appear shortly after load).

- [ ] **Browsers / devices**  
  Test on Chrome, Firefox, Safari, and one mobile device (or emulated). Confirm no console errors and that key pages load within the 3s target.

---

## Files Touched (Reference)

- **Layout / views**  
  `resources/views/layouts/dashboard.blade.php` (fonts, Frappe loader push),  
  `resources/views/components/chart.blade.php` (queue-based chart init).

- **JS**  
  `resources/js/bootstrap.js` (Echo removed from main bundle),  
  `resources/js/app.js` (lazy `import('./echo')`),  
  `resources/views/partials/dashboard-scripts.blade.php` (deferred profile sync: `/api/me`, SSE).

- **Middleware**  
  `app/Http/Middleware/CompressResponse.php`,  
  `app/Http/Middleware/CacheControl.php`,  
  `bootstrap/app.php` (web group append).

- **Sidebar & navigation**  
  `resources/views/partials/sidebar.blade.php` (`data-sidebar-link`, `data-route`),  
  `resources/views/partials/dashboard-scripts.blade.php` (prefetch + click feedback + `__lastNavTransitionMs`),  
  `resources/css/app.css` (`.sidebar-nav-link--loading`).

- **Backend**  
  `app/Providers/AppServiceProvider.php` (Cache for global notices),  
  `app/Features/Notices/Http/Controllers/*` (Cache::forget on write),  
  `database/migrations/2026_02_20_100000_add_indexes_to_users_for_performance.php`,  
  `app/Features/*/Events/*` (ShouldBroadcast for queued delivery).

---

## Server / Hosting Notes

- **Gzip**  
  If your server (e.g. nginx, Apache) already compresses responses, you can disable `CompressResponse` to avoid double work: remove it from the `web` group in `bootstrap/app.php`.

- **Static assets**  
  Vite-built assets are hashed; use long-lived cache (e.g. 1 year) and ensure `Cache-Control` is set by the server for `/build/*` (or your asset path).

- **OPcache**  
  Enable PHP OPcache in production to reduce PHP parse/compile time and improve TTFB.

- **Queue workers (broadcasting)**  
  Events use `ShouldBroadcast` (queued). Run `php artisan queue:work` so broadcasts are sent without blocking the HTTP response; this keeps action response times under the 500 ms target.

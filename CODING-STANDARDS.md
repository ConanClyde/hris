# Coding Standards (HRIS App)

This project is a **Laravel 12** application (PHP **8.2**) with **Inertia v2 + Vue 3**, **Tailwind CSS v4**, **Pest 3**, **Pint**, **ESLint 9**, and **Prettier 3**.

## Backend (Laravel 12 / PHP 8.2)

- **Routes & access control**
  - All sensitive routes must be behind `auth` and the appropriate authorization layer:
    - Prefer route middleware groups (e.g. `role:admin|hr|employee`) for page-level access.
    - Use policies/gates for resource-level access decisions.
- **Validation**
  - Prefer **Form Requests** for non-trivial validation; inline `$request->validate()` is acceptable for small, single-action endpoints.
  - Always validate query parameters for API-like JSON endpoints (e.g. calendar `start/end`).
- **Controllers**
  - Keep controllers thin; move heavy work to services and queued jobs.
  - Use explicit return types where practical (e.g. `Response|RedirectResponse`, `JsonResponse`).
- **Eloquent & queries**
  - Prefer Eloquent relationships and eager load (`with`, `loadMissing`) to avoid N+1 queries.
  - Avoid long transactions and heavy synchronous work in request/response paths.
- **Queues**
  - Any expensive I/O (emails to many users, exports, AI calls, heavy reports) must be queued.
- **Broadcasting (Reverb / Pusher protocol)**
  - Use **private channels** with explicit authorization in `routes/channels.php`.
  - Broadcast payloads must contain **only what the UI needs** (no secrets/tokens).
  - Prefer **queue-driven broadcasting** (`ShouldBroadcast`, not `ShouldBroadcastNow`) for scale.
- **Config**
  - Use `config()` everywhere; only use `env()` inside config files.

## Frontend (Vue 3 / Inertia v2 / Tailwind v4)

- **Inertia patterns**
  - Use `@inertiajs/vue3` `router.get/post/put/delete` and Wayfinder route helpers for navigation and actions.
  - Use `preserveState` only when it does not cause stale UI; if local state mirrors props, watch props changes.
- **Component structure**
  - Use `<script setup>` and typed props for pages in `resources/js/pages/**`.
  - Provide clear loading, empty, and error states (especially for deferred props and async fetch pages like calendars).
- **Styling**
  - Use Tailwind utilities consistently. Prefer shared UI components under `resources/js/components/ui/**`.

## Testing (Pest)

- **Default**: feature tests under `tests/Feature` for application behavior.
- **Factories**: Use factories where available; if a model has no factory, create minimal records via `Model::create()` in tests.
- **Broadcasting tests**: Verify `broadcastOn`, `broadcastAs`, and `broadcastWith` shapes rather than requiring a live Reverb server.

## Formatting & Linting

- **PHP**: `vendor/bin/pint --dirty`
- **Frontend**: `npm run format:check` and `npm run lint:check` in CI

## CI expectations

- CI must **not** auto-format or auto-fix code; it should only **verify** formatting and lint rules, and run tests.


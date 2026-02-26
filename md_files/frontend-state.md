# Frontend State Management

The frontend of the HRIS application is a Vue 3 Single Page Application (SPA) driven by Inertia.js. State management is purposefully decentralized, relying on Vue's Composition API reactivity (`ref`, `computed`) rather than a heavy, monolithic store like Pinia or Vuex.

---

## 1. Global Application State (Inertia Shared Data)

The primary source of truth for "Backend-to-Frontend" global state is injected via Inertia.js.
In the backend `App\Http\Middleware\HandleInertiaRequests.php`, crucial session data is shared globally to all Vue pages on initial load and subsequent visits.

**Shared Data Includes:**
*   `auth.user`: The authenticated user object (id, name, avatar, role, status).
*   `auth.user.two_factor_enabled`: Boolean flag for 2FA status.
*   `ziggy`: The Ziggy routing configuration (allows calling `route('name')` in Vue).

Components access this state via the `@inertiajs/vue3` `usePage()` hook:
```typescript
import { usePage } from '@inertiajs/vue3';
const page = usePage();
const user = computed(() => page.props.auth.user);
```

---

## 2. Core Composables (`resources/js/composables/`)

To manage encapsulated, reusable state and logic, the application utilizes Vue Composables.

### A. `useBroadcasting.ts`
**Purpose:** Manages the global state of real-time notifications received via Laravel Echo/Reverb.
**State Pattern:**
*   Uses a module-scope (singleton-like) `ref<RealTimeNotification[]>([])` to store the notification feed.
*   Provides `addNotification`, `markAsRead`, `removeNotification`, and `clearAll` mutators.
*   Exposes `setupUserListeners()`, `setupAdminListeners()`, etc., which define channel subscriptions based on the user's role.

### B. `useAppearance.ts`
**Purpose:** Manages the application's visual theme (light, dark, system).
**State Pattern:**
*   Uses a module-scope `ref<Appearance>('system')` to store the user's preference.
*   `updateAppearance(value)` mutates the `ref`, writes to `localStorage` (for immediate client-side persistence), and sets a `cookie` (for SSR/backend awareness).
*   Registers an event listener on `window.matchMedia('(prefers-color-scheme: dark)')` to reactively toggle classes when set to 'system'.

### C. `useCurrentUrl.ts`
**Purpose:** A utility to deterministically check the active route, crucial for highlighting active sidebar/navbar links.
**State Pattern:**
*   Wraps the Inertia `usePage().url` in a `computed` ref.
*   Exposes `isCurrentUrl(urlToCheck)` and `whenCurrentUrl(urlToCheck, ifTrue, ifFalse)` helpers.

### D. `useTwoFactorAuth.ts`
**Purpose:** Manages the complex, multi-step state required to enable/disable and verify Two-Factor Authentication.
**State Pattern:**
*   Encapsulates multiple `ref`s: `qrCodeSvg`, `manualSetupKey`, `recoveryCodesList`, and `errors`.
*   Provides async fetchers (`fetchQrCode`, `fetchRecoveryCodes`) that call backend internal API endpoints and update the internal reactive state.

---

## 3. Local Component State

For state that does not need to be shared across the entire application (e.g., whether a specific modal is open, or the current value of a form input), the application strictly adheres to standard Vue 3 Composition API practices within the `<script setup>` block of individual components.

```vue
<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

// Simple UI state
const isModalOpen = ref(false);

// Form state (provided by Inertia)
const form = useForm({
    title: '',
    description: ''
});
</script>
```

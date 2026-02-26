# Roles and Permissions Matrix

The HRIS application utilizes a structured Role-Based Access Control (RBAC) system. A user's role dictates their capability to view certain UI sections, fetch data, and perform destructive actions.

There are **three primary roles** in the system:
1. `admin` (System Administrator)
2. `hr` (Human Resources Personnel)
3. `employee` (Standard Staff Member)

---

## 1. Middleware Enforcement (`EnsureRole.php`)

All authenticated routes are wrapped in Laravel Middleware (`App\Http\Middleware\EnsureRole`).
The router groups endpoints using the `role:{role_name}` definition (e.g., `->middleware(['auth', 'role:admin'])`).

**The Admin Override:**
```php
if ((string) $currentRole !== $role) {
    if ($currentRole === 'admin') {
        return $next($request); // Admins bypass all role checks
    }
    // ... abort 403 or redirect
}
```
This fundamental design means the `admin` role has implicit access to **all** `hr` and `employee` designated routes without explicit policy grants.

---

## 2. Role Capability Matrix

### `admin` (System Administrator)
The highest privilege level. Designed for IT personnel or top-level HR directors who manage the platform infrastructure itself.
*   **Access Level:** Global (Bypasses all `EnsureRole` middleware restrictions).
*   **Key Capabilities:**
    *   Full User CRUD (Create, Read, Update, Delete, Toggle Status) and Bulk Actions.
    *   System Activity Logs viewing and exporting.
    *   System Backup generation, download, restore, and deletion.
    *   Global Performance Metrics monitoring.
    *   Full Calendar & Custom Holiday CRUD.
    *   Global Notice creation/deletion.
    *   Full read access to all Leave Applications.

### `hr` (Human Resources)
The operational privilege level. Designed for HR staff who process applications and manage employee records.
*   **Access Level:** Restricted to `/hr/*` and `/employee/*` routes.
*   **Key Capabilities:**
    *   **Leave Management:** Approve/Reject Leave Applications, manage Leave Credits (add/deduct balances), view HR Leave ledger.
    *   **PDS (Personal Data Sheet):** Review submitted PDS records, mark them as 'Approved', 'Rejected', or 'Under Review'.
    *   **Training Management:** Create new training records and assign them to employees, approve/reject training submissions.
    *   **Notices & Calendar:** Create system-wide Custom Holidays and Notices.
    *   **Users:** View Employee records and manipulate their basic HR data (cannot perform structural system tasks like bulk deletion).

### `employee` (Staff Member)
The self-service privilege level. Designed for individual staff members to manage their own records.
*   **Access Level:** Strictly restricted to `/employee/*` routes. Queries are scoped to `auth()->id()`.
*   **Key Capabilities:**
    *   **PDS:** Draft, edit, and submit their Personal Data Sheet. Generate PDS PDF exports.
    *   **Leave:** Apply for leave, upload attachments, cancel pending leaves, view personal leave balances.
    *   **Training:** Submit external training accomplishments, upload certificates, view personal training history.
    *   **Calendar & Notices:** Read-only access to holidays and system notices.
    *   **Profile/Settings:** Update personal account settings, avatar, change password, manage 2FA.

---

## 3. Frontend Navigation Guards

While the backend enforces security via Middleware, the frontend Vue application intelligently hides/shows elements based on the authenticated user's role (`auth.user.role`) injected via Inertia.js shared data (`HandleInertiaRequests.php`).

**Sidebar Rendering (`AppSidebar.vue` & `AppSidebarHeader.vue`):**
Sidebar navigation items dynamically generate based on the returned role. For instance, the System Backups link is exclusively pushed to the navigation array if `role === 'admin'`, preventing standard employees from even attempting to navigate to forbidden routes.

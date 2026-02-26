# Realtime Broadcasting — Laravel Reverb, Echo & Pusher

> This document defines how to implement realtime features (notifications, dashboards, calendars, etc.) using **Laravel broadcasting** with **Reverb** (preferred) and **Pusher-compatible** frontends.
> It applies to BOTH backend and frontend code in this HRIS project.

---

## 1. High-Level Design

### 1.1 Goals
- Deliver realtime updates for:
  - Notifications (new notices, alerts).
  - Status changes (leave/training/PDS approvals).
  - Dashboard metrics (counts, activity logs).
  - Optional: calendar events created/updated by admins/HR.
- Keep implementation:
  - **Feature-first** (events per feature).
  - **Role-aware** (broadcast to the correct users).
  - **Transport-agnostic** (Reverb by default, Pusher-compatible if needed).

### 1.2 Core Building Blocks
- **Backend**:
  - Laravel events implementing `ShouldBroadcast` or `ShouldBroadcastNow`.
  - Broadcast routes (`routes/channels.php`) defining private/presence channels.
  - Broadcasting config (`config/broadcasting.php`, `.env`).
  - Reverb server (or Pusher-compatible provider).
- **Frontend**:
  - Laravel Echo instance configured once (in `resources/js`).
  - Subscriptions to channels based on:
    - User ID (per-user).
    - Role (admin, HR, employee).
    - Feature (calendar, leave, training, notifications).
  - Handlers that update Alpine/Vue/Livewire/vanilla state (here: vanilla JS + Blade).

---

## 2. Backend: Broadcasting with Laravel Reverb

> Use **Reverb** as the default broadcasting driver for local + production when possible.  
> Pusher (or a compatible provider) is treated as an alternative driver without changing event semantics.

### 2.1 Configuration
- `.env`:
  - `BROADCAST_CONNECTION=reverb` (or `pusher` if using Pusher).
  - Reverb-specific keys if using Laravel Reverb (not hard-coded here; follow official docs).
- `config/broadcasting.php`:
  - Ensure a `reverb` connection is configured.
  - Keep the `pusher` connection configured for compatibility if desired.

### 2.2 Channels (`routes/channels.php`)
- Define channels by feature and scope:
  - **Per-user private channel** (strongest contract):
    - `private('users.{userId}')` → used for user-specific toasts/alerts (e.g. “Your leave was approved”).
  - **Role-level channels**:
    - `private('role.admin')`, `private('role.hr')`, `private('role.employee')` for role dashboards/notifications.
  - **Feature or room-level channels** (optional):
    - `private('leave.management')`, `private('training.management')`, etc.
- Authorization callbacks:
  - Use the current session user:
    - Confirm `auth()->check()` or `session('user_id')` is set.
    - Ensure the `{userId}` path param matches the logged-in user for `users.{userId}`.
    - Check `session('role')` when subscribing to role-based channels.

### 2.3 Events per Feature

#### 2.3.1 Leave
- Example events:
  - `App\Features\Leave\Events\LeaveStatusUpdated`
  - `App\Features\Leave\Events\LeaveCreated`
- Payload guidelines:
  - Minimal but sufficient:
    - `leave_id`, `employee_id`, `employee_name`.
    - `status` (`pending`, `approved`, `rejected`).
    - `type`, `date_from`, `total_days`.
    - Optional message like `status_label`, `reason_snippet`.
- Channels:
  - `private("users.{$employeeId}")` for personal notification.
  - `private('role.hr')` and/or `private('leave.management')` for HR dashboards.

#### 2.3.2 Training
- Example events:
  - `App\Features\Training\Events\TrainingStatusUpdated`
  - `App\Features\Training\Events\TrainingAssigned`
- Similar payload structure:
  - `training_id`, `employee_id`, `status`, `title`, relevant dates, hours.
- Channels:
  - `private("users.{$employeeId}")` and `private('role.hr')`.

#### 2.3.3 Notices & Notifications
- Example events:
  - `App\Features\Notices\Events\NoticePublished`
  - `App\Features\Notices\Events\NoticeUpdated`
- Payload:
  - `notice_id`, `title`, `type`, `excerpt`, `url` (if needed).
- Channels:
  - `private('role.admin')` / `private('role.hr')` / `private('role.employee')` depending on target audience.
  - Or per-user if notices can be scoped individually.

#### 2.3.4 Calendar & Holidays
- Example events:
  - `App\Features\Calendar\Events\CustomHolidayCreated`
  - `App\Features\Calendar\Events\CustomHolidayUpdated`
  - `App\Features\Calendar\Events\CustomHolidayDeleted`
- Channels:
  - `private('calendar.holidays')` or role channels if only admins/HR should see changes.
- On event:
  - Frontend can either:
    - Trigger a refetch of events from `/calendar/events`.
    - Or optimistically update in-memory events list.

---

## 3. Frontend: Laravel Echo Usage

### 3.1 Echo Bootstrap
- Centralize Echo setup in **one JS file** (e.g. `resources/js/bootstrap.js` or `resources/js/app.js`):
  - Configure Echo to use Reverb or Pusher based on `BROADCAST_CONNECTION`.
  - Use `import Echo from 'laravel-echo';` (and `pusher-js` only if using Pusher).
- Do **not** configure Echo directly from Blade; Blade should only:
  - Provide the current `user_id` and `role` (via `window.HRIS_USER_ID`, `window.HRIS_ROLE` or a `data-*` attribute).
  - Let JS read those globals and subscribe accordingly.

### 3.2 Channel Subscriptions
- On page load (after Echo is created):
  - Subscribe to the **per-user channel**:
    - `Echo.private('users.' + HRIS_USER_ID)`
  - Subscribe to **role-level channel**:
    - `Echo.private('role.' + HRIS_ROLE.toLowerCase())`
  - For feature-specific pages (optional):
    - `Echo.private('leave.management')` on HR Leave pages.
    - `Echo.private('training.management')` on HR Training pages.
    - `Echo.private('calendar.holidays')` on admin/HR calendars.

### 3.3 Handling Events in UI
- Use **small JS handlers** that:
  - Update counters/badges (e.g., pending counts, notifications count).
  - Insert new rows or update statuses in tables.
  - Show inline banners or minimal toasts using the alert patterns from `ai.rules.md`.
- Never mutate the DOM in a “random” style:
  - Reuse existing markup templates for rows/cards.
  - Keep DOM selectors predictable (`data-*` attributes or IDs).

---

## 4. Security & Authorization

### 4.1 Private vs Public Channels
- Always prefer **private** channels for user/role data.
- Public channels are only acceptable for:
  - System-wide, non-sensitive metrics or logs.
  - Data that is already public (rare in HRIS).

### 4.2 Channel Authorization
- `Broadcast::channel('users.{userId}', function ($user, $userId) { ... })`:
  - Ensure `$userId` matches the logged-in user or mapped employee record.
- Role channels:
  - Ensure the session role matches the role for channels like `role.admin`, `role.hr`, `role.employee`.

### 4.3 Payload Hygiene
- Do not broadcast:
  - PII beyond what’s needed (e.g., don’t send full addresses, full emails, or personal notes).
  - Internal IDs that aren’t needed for the UI.
  - Sensitive fields like hashes, tokens, or credentials.

---

## 5. Environment & Deployment Notes

### 5.1 Local Development
- Reverb:
  - Run the Reverb server (e.g., `php artisan reverb:start` or as documented).
  - Use `BROADCAST_CONNECTION=reverb`.
- Pusher:
  - If using Pusher during development, ensure `.env` contains correct keys and cluster.

### 5.2 Production
- Pick **one** primary driver (Reverb recommended for self-hosted, Pusher for managed).
- Ensure:
  - SSL and proper CORS/config for websocket endpoints.
  - Broadcasting queues are configured (use `ShouldBroadcast` + queued workers for heavy traffic).

---

## 6. Feature Examples (Recommended Patterns)

> These are patterns, not exact code, meant to guide future implementations.

### 6.1 Leave Status Update Flow
1. HR approves/rejects a leave application.
2. Controller updates status (mock or DB).
3. Fire `LeaveStatusUpdated` event with:
   - `leave_id`, `employee_id`, `status`, `type`, `date_from`, `total_days`.
4. Event broadcasts on:
   - `private("users.{$employeeId}")` → employee sees toast “Your leave was approved.”
   - `private('role.hr')` → other HR users see updated counts in their dashboard.

### 6.2 New Notice Published
1. Admin/HR creates a new notice.
2. Controller saves `Notice`.
3. Fire `NoticePublished` event with:
   - `notice_id`, `title`, `type`, and a short `excerpt`.
4. Event broadcasts on channels for affected roles:
   - `private('role.employee')`, `private('role.hr')`, etc.
5. Frontend:
   - In the dashboard layout, listen for `NoticePublished` and:
     - Increment notification counters.
     - Optionally insert a new row in “Latest Notices”.

### 6.3 Calendar Holiday Created
1. Admin creates a custom holiday.
2. `CustomHoliday` is stored.
3. Fire `CustomHolidayCreated` event with holiday metadata.
4. Frontend calendars:
   - On `calendar.holidays` channel, refetch or patch events source.
   - Show a subtle inline banner like “Calendar updated — new holiday added” near the calendar header.

---

## 7. Implementation Checklist

When adding a new realtime feature:

1. **Event design**
   - [ ] Define event class under `app/Features/{Feature}/Events`.
   - [ ] Implement `ShouldBroadcast` or `ShouldBroadcastNow`.
   - [ ] Decide on channels (`users.{id}`, `role.{role}`, feature channels).
   - [ ] Keep payload minimal and safe.

2. **Channel & Auth**
   - [ ] Add channel definitions in `routes/channels.php`.
   - [ ] Ensure proper authorization based on session user + role.

3. **Backend wiring**
   - [ ] Fire the event from the correct controller/service.
   - [ ] Wrap any heavy/frequent events in queues if necessary.

4. **Frontend wiring**
   - [ ] Configure Echo once in a shared bootstrap file.
   - [ ] Subscribe to necessary channels on page load (using current user/role).
   - [ ] Implement UI handlers consistent with `ai.rules.md` (alerts, counters, cards).


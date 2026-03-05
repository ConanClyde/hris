# Reverb & Email Notification Infrastructure

This document outlines the real-time broadcasting setup (via Laravel Reverb and Echo) and the email notification layouts currently established in the HRIS application.

---

## 1. List of Core Reverb Events
The backend dispatches real-time events that implement the `ShouldBroadcastNow` interface for immediate broadcasting. Some of the primary events located in `app/Events/` include:

1. `HolidayAdded`
2. `HolidayUpdated`
3. `LeaveApproved`
4. `LeaveCancelled`
5. `LeaveRejected`
6. `LeaveSubmitted`
7. `NoticeCreated`
8. `NoticeDeleted`
9. `NoticeUpdated`
10. `NotificationsUnreadCountUpdated`
11. `TrainingAssigned`
12. `TrainingCompleted`
13. `UserApproved`
14. `UserRegistered`

These events encapsulate data (e.g., `$holidayData`, `$leaveData`, `$message`) and specify the `broadcastOn` channels and `broadcastAs` aliases (e.g., `holiday.added`).

---

## 2. Realtime Broadcasting Backend
The backend utilizes Laravel Reverb as its WebSocket server.
*   **Broadcaster Configuration:** Set to `reverb` in the environment configuration and initialized in the internal broadcasting configuration files.
*   **Channel Authorization:** Handled in `routes/channels.php`. It restricts access to specific data streams based on user roles and authentication state. Key channels include:
    *   `App.Models.User.{id}` (Private channel for individual user notifications)
    *   `employees` (Broadcasts to all authenticated employees)
    *   `admin.dashboard` (Restricted to 'admin' role)
    *   `hr.dashboard` (Restricted to 'admin' or 'hr' roles)
    *   `leave.management`, `training.management`, `calendar.holidays` (Feature-specific channels)

---

## 3. Realtime Broadcasting Frontend
The frontend listens for real-time WebSocket traffic using Laravel Echo Vue (`@laravel/echo-vue`).
*   **Global Connection:** Echo is initialized in `resources/js/app.ts` using `configureEcho({ broadcaster: 'reverb' ...})` to connect to the backend WebSocket server.
*   **Listeners & State Management:** The `resources/js/composables/useBroadcasting.ts` composable manages the real-time state. It establishes connections via specific functions:
    *   `setupUserListeners(uid)`: Subscribes to `App.Models.User.{uid}` for personal alerts (Leave updates, generic notifications).
    *   `setupAdminListeners()`: Subscribes to `admin.dashboard` (New users, holiday updates).
    *   `setupHrListeners()`: Subscribes to `hr.dashboard` (New leave applications, new users).
    *   `setupEmployeeListeners()`: Subscribes to the global `employees` channel (Global notices, new holidays).
*   Incoming payloads are formatted and pushed to a reactive `notifications` array acting as an in-memory application store that drives the UI badge counters.

---

## 4. Email Notifications
Every major system action that triggers a Reverb event is now paired with a matching Mailable class in `app/Mail/` and a Blade email template in `resources/views/emails/`:
*   `HolidayAddedMail` → `emails.holiday-added`
*   `HolidayUpdatedMail` → `emails.holiday-updated`
*   `LeaveApprovedMail` → `emails.leave-approved`
*   `LeaveCancelledMail` → `emails.leave-cancelled`
*   `LeaveRejectedMail` → `emails.leave-rejected`
*   `LeaveSubmittedMail` → `emails.leave-submitted`
*   `NoticeCreatedMail` → `emails.notice-created`
*   `NoticeDeletedMail` → `emails.notice-deleted`
*   `NoticeUpdatedMail` → `emails.notice-updated`
*   `TrainingAssignedMail` → `emails.training-assigned`
*   `TrainingCompletedMail` → `emails.training-completed`
*   `UserApprovedMail` → `emails.user-approved`
*   `UserRegisteredMail` → `emails.user-registered`

---

## 5. Email Notifications Layout
The email templates utilize raw inline-HTML/CSS structured inside traditional Blade views (`.blade.php`), built specifically to be robust across different email clients.

**General Anatomy & Styling:**
*   **Global Wrapper:** A clean, `#f5f7fa` neutral background with an centered max-width 600px container.
*   **Header:** Features a vibrant linear-gradient block spanning from teal (`#17a2b8`) to dark cyan (`#138496`) with a bold white H1 title indicating the system action.
*   **Context Area (`.info` box):** A specialized highlight box with a light-blue tinted background (`#f0faff`) and a firm teal left border (`4px solid #17a2b8`). It extracts and lists the core dynamic variables supplied to the view (e.g., Leave Dates, Holiday Types, User details).
*   **Footer & Signature:** Concludes with an automated signature ("HR Department - HRIS System") and a muted grey footer containing the copyright year and boilerplate text ("This is an automated email...").
*   **Badges:** Certain templates incorporate reusable `.holiday-badge` inline pills for rendering statuses or labels neatly inside the email body.

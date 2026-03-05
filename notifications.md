# System Notifications & Indicators Guidelines

This document serves as the absolute source of truth for how notifications, unread counters, and pending action counts must be implemented across the DB, API, and frontend in the HRIS system.

## Real-time Broadcasting Architecture (Backend & Frontend)
To ensure all 5 UI components update seamlessly without page reloads, **all notifications must be implemented using Laravel's Event Broadcasting and must work across all browsers in real-time.**

**Backend Requirements (Laravel):**
- All notification classes (`Illuminate\Notifications\Notification`) **MUST** implement the `ShouldBroadcastNow` interface to ensure immediate delivery. Do not use deferred broadcasting (`ShouldBroadcast`).
- They must define the `toBroadcast($notifiable)` method to return a `BroadcastMessage`.
- They must specify the correct channel via the `broadcastOn()` method.
- Custom state-change events (like `LeaveStatusUpdated` or `PdsStatusUpdated`) must dispatch an `Event` that also implements `ShouldBroadcastNow`.

**Frontend Requirements (Vue/Echo):**
- All 5 UI components rely on real-time data fed from `useBroadcasting.ts`.
- The frontend must use `useEcho` to listen to the exact channels broadcasted by the backend.
- The broadcasting implementation must ensure **cross-browser capability**. Notifications triggered on one browser/device must be instantaneously received on any other active browser session looking at the HRIS system.
- When an Echo event is received, the frontend must dynamically increment/decrement refs (`usersPendingCount`, `notificationsUnreadCount`, etc.) so Vue reactivity updates the Sidebar Badge, Navbar Badge, and Navbar Card instantly.

---

## The 5 Mandatory UI Components

**CRITICAL RULE:** Every single system event/action (Registration, Leaves, Training, PDS, Calendar, Announcements) **MUST** trigger updates across ALL 5 of these components simultaneously. There are no exceptions.

### 1. Toast Notification (`vue-sonner`)
- **What it is:** The pop-up notification that slides in on the screen when an event occurs in real-time.
- **Behavior:** Triggered immediately upon receiving the WebSocket event. Must use the appropriate color/type (`info`, `success`, `warning`, `error`).

### 2. Sidebar Notification Menu Number Badge
- **What it is:** The small pill/badge next to menu items in the left sidebar (e.g., `Leaves [3]`).
- **Data Source:** Fed down from `HandleInertiaRequests` middleware via `page.props.auth.counts` on initial load, and dynamically updated via WebSocket events (`useBroadcasting.ts`).
- **Meaning:** Represents the absolute number of *Pending* database records requiring the active user's approval or action.
- **Behavior Rules:**
  - **Never clears on view:** It does NOT decrement just because a user clicks the sidebar link or looks at the page.
  - **Decrements ONLY on action:** The counter goes down only when the underlying record's status changes from `pending` to `approved`/`rejected`/`completed`.

### 3. Navbar Number Badge
- **What it is:** The red notification bell counter at the top right of the application layout.
- **Data Source:** Fetched via Axios/Fetch on load (e.g., `GET /employee/notifications/unread-count`), updated dynamically by Echo.
- **Meaning:** Total sum of standard `DatabaseNotification` records for the logged-in user where `read_at` is `null`.
- **Behavior Rules:**
  - **Increments** any time a `Illuminate\Notifications\Notification` class is fired to the user.
  - **Decrements** dynamically exactly by 1 when the user opens the notification dropdown card and clicks a specific notification.
  - **Resets to 0** if the user clicks a "Mark All as Read" button.

### 4. Navbar Notification Card (Dropdown)
- **What it is:** The popover that appears when clicking the notification bell. Shows the 5-10 most recent notifications.
- **Data Source:** `GET /[role]/notifications?only=notifications` returning the latest Notification models.
- **Meaning:** Gives immediate context over recent system changes.
- **Behavior Rules:**
  - **Unread Styling:** Unread items must have a distinct visual background (e.g., light blue tint) and a dot indicator.
  - **Click Action:** Clicking an item must fire a request to mark it as read (`read_at` timestamp).
  - **Redirect Action:** Notifications must contain a `data` JSON payload. When clicked, it should route the user to that specific record (e.g., `/hr/leaves/{id}`).

### 5. Notification Page (`/notifications`)
- **What it is:** The dedicated `/notifications` table/feed view.
- **Data Source:** An Inertia page load passing down a paginated `LengthAwarePaginator` of the user's notifications.
- **Meaning:** The canonical history of all alerts.
- **Behavior Rules:**
  - **Filtering:** Must provide a toggle to switch between viewing "All Notifications" and only "Unread".
  - **Bulk Actions:** Must contain a "Mark All as Read" button (`POST /[role]/notifications/mark-all-read`).
  - **Clear History:** Must contain a "Delete All" or individual "Delete" trash buttons.

---

## Action and Event Integrations

For every action below, the associated Vue component/controller **MUST** implement the Toast, modify the Sidebar Badge, modify the Navbar Badge, generate the Navbar Card, and log to the Notification Page.

### 1. User Management (Registrations)
**Action:** New Employee Registers.
- **Sidebar Badge:** HR/Admin `users_pending` count goes `+1`.
- **Navbar/Card/Page/Toast:** `admin.dashboard` and `hr.dashboard` channels receive the real-time event. Database notification sent to HR/Admin.
- **Notification Payload:** 
  ```json
  {
    "type": "info",
    "title": "New User Registered",
    "message": "John Doe has applied for an account.",
    "data": { "user_id": 5, "redirect_url": "/hr/users/pending" }
  }
  ```

### 2. Leave Management
**Action:** HR Approves Employee Leave.
- **Sidebar Badge:** HR/Admin `leaves_pending` count goes `-1`. 
- **Navbar/Card/Page/Toast:** Employee receives a `success` DatabaseNotification. Event fired to `App.Models.User.{employee_id}`.
- **Notification Payload:**
  ```json
  {
    "type": "success",
    "title": "Leave Approved",
    "message": "Your leave request for Oct 12-14 has been approved.",
    "data": { "leave_id": 12, "redirect_url": "/employee/leaves?id=12" }
  }
  ```

### 3. Training & Development
**Action:** HR Assigns Training.
- **Sidebar Badge:** Employee `trainings_assigned` count goes `+1`.
- **Navbar/Card/Page/Toast:** Employee receives an `info` DatabaseNotification. Event fired to `App.Models.User.{employee_id}`.
- **Notification Payload:**
  ```json
  {
    "type": "info",
    "title": "Training Assigned",
    "message": "You have been assigned: Title IX Compliance.",
    "data": { "training_id": 8, "redirect_url": "/employee/training" }
  }
  ```

### 4. Personal Data Sheet (PDS)
**Action:** Employee requests address update.
- **Sidebar Badge:** HR/Admin `pds_pending` badge goes `+1`.
- **Navbar/Card/Page/Toast:** HR/Admin receive a `warning` DatabaseNotification. Event fired to `pds.management` channel.
- **Notification Payload:**
  ```json
  {
    "type": "warning",
    "title": "PDS Update Request",
    "message": "Jane Smith requested a PDS change.",
    "data": { "pds_id": 22, "redirect_url": "/hr/pds/approvals/22" }
  }
  ```

### 5. Announcements / Posts
**Action:** General announcement is posted.
- **Sidebar Badge:** Announcements indicator/pending count goes `+1` (if applicable) for targeted employees.
- **Navbar/Card/Page/Toast:** All targeted Employees receive an `info` DatabaseNotification and real-time toast.
- **Notification Payload:**
  ```json
  {
    "type": "info",
    "title": "New Announcement",
    "message": "Townhall Meeting tomorrow at 10 AM.",
    "data": { "post_id": 45, "redirect_url": "/employee/posts" }
  }
  ```

### 6. Holidays
**Action:** Admin adds a Holiday.
- **Sidebar Badge:** Calendar/Holidays pending count goes `+1` (if applicable) across targeted roles.
- **Navbar/Card/Page/Toast:** All targeted roles receive an `info` DatabaseNotification.
- **Notification Payload:**
  ```json
  {
    "type": "info",
    "title": "New Holiday",
    "message": "Christmas Break has been added to the calendar.",
    "data": { "redirect_url": "/employee/calendar" }
  }
  ```

# HRIS Manual Testing Checklist

Complete manual testing checklist for all system processes and functions, organized by user role.

---

## Table of Contents
1. [Authentication & General](#1-authentication--general)
2. [Admin Features](#2-admin-features)
3. [HR Features](#3-hr-features)
4. [Employee Features](#4-employee-features)
5. [Shared Features (All Roles)](#5-shared-features-all-roles)
6. [Cross-Role Workflows](#6-cross-role-workflows)

---

## 1. Authentication & General

### 1.1 Authentication
- [ ] User registration (if enabled)
- [ ] Login with valid credentials
- [ ] Login with invalid credentials (error handling)
- [ ] Password reset flow
- [ ] Force password change on first login
- [ ] Logout functionality
- [ ] Session timeout handling

### 1.2 Role-Based Access Control
- [ ] Admin can access all routes
- [ ] HR cannot access Admin-only routes
- [ ] Employee cannot access Admin/HR routes
- [ ] Admin bypass role checks
- [ ] Unauthorized access redirects to dashboard

### 1.3 Profile Management (All Roles)
- [ ] View profile page
- [ ] Update profile information
- [ ] Change password
- [ ] Profile avatar upload/update
- [ ] Delete account (if applicable)

### 1.4 Settings (All Roles)
- [ ] Access settings page
- [ ] Update preferences
- [ ] Theme/light-dark mode (if applicable)

---

## 2. Admin Features

### 2.1 Dashboard
- [ ] View Admin dashboard
- [ ] Display total users count
- [ ] Display pending approvals count
- [ ] Users by role statistics
- [ ] Users by status statistics
- [ ] Total employees count
- [ ] Sex distribution display
- [ ] Unassigned counts
- [ ] Recommendations display
- [ ] Quick actions functionality

### 2.2 User Management
- [ ] View users list (all status tabs)
  - [ ] All users tab
  - [ ] Pending tab
  - [ ] Rejected tab
  - [ ] Active tab
  - [ ] Inactive tab
- [ ] Search users functionality
- [ ] Filter by role
- [ ] Pagination (per page: 5, 10, 15, 20, 25)
- [ ] Create new user (all roles: Admin, HR, Employee)
- [ ] Edit user details
- [ ] Delete user
- [ ] Approve pending user
- [ ] Reject pending user
- [ ] Toggle user status (active/inactive)
- [ ] Bulk actions on users
- [ ] View user avatar with cache-busting

### 2.3 User Creation Validation
- [ ] Required fields validation
- [ ] Unique username validation
- [ ] Unique email validation
- [ ] Role selection validation
- [ ] Employee-specific fields (when role=employee):
  - [ ] Date hired required
  - [ ] Division required
  - [ ] Position required
  - [ ] Classification required
- [ ] Auto-approve on creation by Admin
- [ ] Default password generation
- [ ] Email notification sent to new user
- [ ] Email notification sent to HR/Admin

### 2.4 Activity Logs
- [ ] View activity logs
- [ ] Export activity logs
- [ ] Filter/search logs

### 2.5 Performance Metrics
- [ ] View performance dashboard
- [ ] Run diagnostics
- [ ] View system health metrics

### 2.6 Backup Management
- [ ] View backup list
- [ ] Create new backup
- [ ] Upload backup file
- [ ] Download backup
- [ ] Restore from backup
- [ ] Update backup notes
- [ ] Delete backup

### 2.7 Reports
- [ ] View reports dashboard
- [ ] Export analytics (CSV)
- [ ] Date range filters
- [ ] Organization breakdown
- [ ] Sex distribution charts
- [ ] Hire trends
- [ ] Leave trends
- [ ] Training trends
- [ ] Heatmaps display

### 2.8 Calendar Management
- [ ] View admin calendar
- [ ] View events
- [ ] Custom holidays management:
  - [ ] Add custom holiday
  - [ ] Edit custom holiday
  - [ ] Delete custom holiday

### 2.9 Posts/Announcements (Admin)
- [ ] View posts list
- [ ] Create new post
- [ ] Edit post
- [ ] Delete post
- [ ] React to post
- [ ] Add comment to post

### 2.10 Notifications (Admin)
- [ ] View notifications
- [ ] Mark single notification as read
- [ ] Mark all notifications as read
- [ ] Delete single notification
- [ ] Delete all notifications
- [ ] Unread count badge

### 2.11 AI Chatbot (Admin Access)
- [ ] Access AI chatbot
- [ ] Send chat message
- [ ] Stream chat response
- [ ] View chat history/conversations
- [ ] Create new conversation
- [ ] Update conversation
- [ ] Delete conversation
- [ ] Archive conversation
- [ ] Restore conversation
- [ ] Store message in conversation
- [ ] View recent messages

### 2.12 AI Chatbot Admin Features
- [ ] View activity logs
- [ ] Ingest data
- [ ] View ingest logs
- [ ] Export feedback
- [ ] View feedback summary
- [ ] View analytics
- [ ] Export metrics
- [ ] View policy coverage
- [ ] View enhancement framework

---

## 3. HR Features

### 3.1 Dashboard
- [ ] View HR dashboard
- [ ] Display total users (excluding admins)
- [ ] Pending leave count
- [ ] Pending training count
- [ ] PDS pending count
- [ ] "Who's out today" widget
- [ ] Total employees count
- [ ] Sex distribution
- [ ] Unassigned counts
- [ ] Mini leave trend chart
- [ ] Mini training trend chart
- [ ] Recommendations

### 3.2 User Management (HR)
- [ ] View users list (employees only)
- [ ] All tabs: all, pending, rejected, active, inactive
- [ ] Search users
- [ ] Filter functionality
- [ ] Create new user (employees only)
- [ ] Edit user
- [ ] Delete user
- [ ] Approve pending user
- [ ] Reject pending user
- [ ] Toggle status
- [ ] Bulk actions
- [ ] Cannot create Admin/HR users (validation)

### 3.3 Employee Bulk Operations
- [ ] Download employee template
- [ ] Import employees from file
- [ ] Export employees to file

### 3.4 Attendance/DTR Management
- [ ] View attendance list
- [ ] Edit employee attendance
- [ ] Update attendance records
- [ ] Verify attendance (supervisor verification)

### 3.5 DTR Submissions
- [ ] View DTR submissions
- [ ] Approve DTR submission
- [ ] View payroll excluded list

### 3.6 Overtime Management
- [ ] View overtime requests
- [ ] Filter by status (all, pending, approved, rejected)
- [ ] Approve overtime request
- [ ] Reject overtime request

### 3.7 Leave Management
- [ ] View leave applications
- [ ] Create leave application (for employee)
- [ ] Edit leave application
- [ ] Delete leave application
- [ ] Export leave applications
- [ ] Delete leave attachment

### 3.8 Leave Credits
- [ ] View leave credits list
- [ ] View individual leave credit details

### 3.9 Leave Reports
- [ ] View leave reports
- [ ] Export leave reports

### 3.10 Training Management
- [ ] View training list
- [ ] Create training record
- [ ] Edit training record
- [ ] Delete training record

### 3.11 PDS Management
- [ ] View PDS list
- [ ] Preview PDS
- [ ] Preview PDS (JSON format)
- [ ] Update PDS status
- [ ] Approve PDS revision
- [ ] Reject PDS revision
- [ ] Export PDS

### 3.12 Reports
- [ ] View reports dashboard
- [ ] Tardiness report
- [ ] Attendance report
- [ ] Export attendance report
- [ ] Custom reports
- [ ] Export custom reports

### 3.13 Calendar
- [ ] View HR calendar
- [ ] View calendar events

### 3.14 Posts/Announcements (HR)
- [ ] View posts
- [ ] Create post
- [ ] Edit post
- [ ] Delete post
- [ ] React to post
- [ ] Comment on post

### 3.15 Notifications (HR)
- [ ] View notifications
- [ ] Mark as read
- [ ] Mark all as read
- [ ] Delete notification
- [ ] Delete all notifications

### 3.16 Activity Logs (HR)
- [ ] View personal activity logs

### 3.17 Organizational Chart
- [ ] View org chart
- [ ] Division structure display
- [ ] Subdivision structure
- [ ] Section structure
- [ ] Employee counts by unit
- [ ] Employee assignments

### 3.18 Onboarding
- [ ] View onboarding list
- [ ] Init onboarding checklist for employee
- [ ] Toggle onboarding items

### 3.19 Offboarding
- [ ] View offboarding list
- [ ] Init clearance for employee
- [ ] Update clearance status

### 3.20 AI Chatbot (HR)
- [ ] Access chatbot
- [ ] Send messages
- [ ] Stream responses
- [ ] View conversation history
- [ ] Manage conversations

---

## 4. Employee Features

### 4.1 Dashboard
- [ ] View employee dashboard
- [ ] Personal leave count
- [ ] Personal training count
- [ ] PDS status display
- [ ] Status badge
- [ ] "Who's out today" widget

### 4.2 Attendance/DTR
- [ ] View daily time record
- [ ] Clock in (AM/PM)
- [ ] Clock out (AM/PM)
- [ ] View attendance history
- [ ] Export DTR (Form 48 PDF)
- [ ] View today's attendance status
- [ ] AM/PM session detection

### 4.3 DTR Submissions
- [ ] View DTR submissions page
- [ ] Select year/month
- [ ] View submission status
- [ ] Download Form 48 PDF
- [ ] Certify and submit DTR
- [ ] View released months

### 4.4 Overtime
- [ ] View overtime requests
- [ ] Create overtime request
- [ ] Form validation (date, hours, type)
- [ ] View overtime status

### 4.5 Leave Applications
- [ ] View leave applications
- [ ] Create leave application
- [ ] Edit leave application
- [ ] Delete leave application
- [ ] Export leave applications
- [ ] Upload attachments
- [ ] Delete attachments

### 4.6 Training
- [ ] View training records
- [ ] Add training record
- [ ] Edit training record
- [ ] Delete training record
- [ ] Export training records
- [ ] Upload training certificates
- [ ] Delete attachments

### 4.7 PDS (Personal Data Sheet)
- [ ] View PDS form
- [ ] Fill personal information (C1)
- [ ] Fill family information (C1)
- [ ] Fill education (C2)
- [ ] Fill CSC eligibility (C2)
- [ ] Fill work experience (C2)
- [ ] Fill voluntary work (C3)
- [ ] Fill training (C3)
- [ ] Fill other info (C3)
- [ ] Fill references (C4)
- [ ] Save draft
- [ ] Submit for review
- [ ] Preview PDS
- [ ] Export PDS (CS Form 212)
- [ ] AI Auto-fill from PDF upload
- [ ] View revision status
- [ ] Locked fields when pending revision

### 4.8 Calendar
- [ ] View personal calendar
- [ ] View calendar events
- [ ] Holidays display

### 4.9 Posts/Announcements
- [ ] View posts feed
- [ ] React to posts
- [ ] Add comments

### 4.10 Notifications
- [ ] View notifications
- [ ] Mark as read
- [ ] Mark all as read
- [ ] Delete notification
- [ ] Delete all notifications
- [ ] Unread count badge

### 4.11 Activity Logs
- [ ] View personal activity logs

### 4.12 Profile
- [ ] View profile
- [ ] Update profile
- [ ] Change password

### 4.13 Settings
- [ ] View settings page

### 4.14 AI Chatbot
- [ ] Access AI chatbot
- [ ] Send messages
- [ ] Stream responses
- [ ] View personal insights
- [ ] View personal feedback
- [ ] Manage conversations
- [ ] View context

---

## 5. Shared Features (All Roles)

### 5.1 Clock In/Out (Shared Routes)
- [ ] Clock in functionality
- [ ] Clock out functionality
- [ ] Attendance history access

### 5.2 AI Chatbot (Shared)
- [ ] Access chatbot page
- [ ] Send chat messages
- [ ] Stream responses
- [ ] View context data
- [ ] View suggestions
- [ ] Submit feedback
- [ ] Conversation management
- [ ] View policy documents
- [ ] Rate limiting enforcement

### 5.3 Notifications System
- [ ] Real-time notifications
- [ ] Notification badges
- [ ] Toast notifications
- [ ] Email notifications

### 5.4 Export Features
- [ ] PDF exports
- [ ] CSV exports
- [ ] Excel exports (if applicable)

---

## 6. Cross-Role Workflows

### 6.1 User Registration & Approval Workflow
1. [ ] New user registers
2. [ ] Admin/HR receives notification
3. [ ] Admin/HR views pending users
4. [ ] Admin/HR approves user
5. [ ] User receives approval email
6. [ ] User logs in with default password
7. [ ] User forced to change password

### 6.2 Leave Application Workflow
1. [ ] Employee creates leave application
2. [ ] HR receives notification
3. [ ] HR reviews leave application
4. [ ] HR approves/rejects application
5. [ ] Employee receives notification
6. [ ] Leave reflected in calendar

### 6.3 Training Workflow
1. [ ] Employee submits training record
2. [ ] HR reviews training
3. [ ] HR approves/rejects
4. [ ] Employee receives notification

### 6.4 PDS Submission Workflow
1. [ ] Employee fills PDS
2. [ ] Employee saves draft
3. [ ] Employee submits for review
4. [ ] HR receives notification
5. [ ] HR reviews PDS
6. [ ] HR approves/rejects PDS
7. [ ] Employee receives notification
8. [ ] If rejected, employee edits and resubmits

### 6.5 PDS Revision Workflow
1. [ ] Approved PDS needs update
2. [ ] Employee creates revision request
3. [ ] HR receives notification
4. [ ] HR reviews revision
5. [ ] HR approves/rejects revision
6. [ ] PDS updated if approved

### 6.6 DTR Submission Workflow
1. [ ] HR releases DTR (1st working day)
2. [ ] Employee notified
3. [ ] Employee downloads Form 48
4. [ ] Employee reviews entries
5. [ ] Employee certifies and submits
6. [ ] HR reviews submission
7. [ ] HR approves DTR
8. [ ] Payroll processing

### 6.7 Overtime Workflow
1. [ ] Employee requests overtime
2. [ ] HR receives notification
3. [ ] HR reviews overtime request
4. [ ] HR approves/rejects
5. [ ] Employee receives notification
6. [ ] Approved overtime reflected in DTR

### 6.8 Post/Announcement Workflow
1. [ ] Admin/HR creates post
2. [ ] All users receive notification
3. [ ] Users view post
4. [ ] Users react to post
5. [ ] Users comment on post

### 6.9 Attendance Verification Workflow
1. [ ] Employee clocks in/out
2. [ ] Supervisor reviews attendance
3. [ ] Supervisor verifies attendance
4. [ ] Verification shown on DTR export

---

## 7. Edge Cases & Error Handling

### 7.1 Form Validation
- [ ] Required field validation
- [ ] Email format validation
- [ ] Date format validation
- [ ] Numeric field validation
- [ ] File upload validation (size, type)
- [ ] Unique constraint validation

### 7.2 Access Control
- [ ] 403 Forbidden pages
- [ ] 404 Not found pages
- [ ] 500 Server error handling
- [ ] Unauthorized API access

### 7.3 Data Integrity
- [ ] Concurrent edit handling
- [ ] Deleted user references
- [ ] Orphaned records

### 7.4 File Operations
- [ ] Large file upload handling
- [ ] Invalid file type handling
- [ ] File not found handling

### 7.5 Session Management
- [ ] Session expiration
- [ ] Multiple tab handling
- [ ] Back button after logout

---

## 8. Performance & Security

### 8.1 Performance
- [ ] Page load times < 3 seconds
- [ ] Pagination works smoothly
- [ ] Search responds quickly
- [ ] Export operations complete
- [ ] PDF generation works

### 8.2 Security
- [ ] CSRF protection on forms
- [ ] XSS prevention
- [ ] SQL injection prevention
- [ ] File upload security
- [ ] Password hashing
- [ ] Rate limiting on APIs

---

## 9. Real-time Broadcasting & Reverb/Echo

### 9.1 Removed Real-time Features

- **Broadcast channel** `users.{userId}` (routes/channels.php)
  - **Reason**: All real-time per-user flows use `App.Models.User.{id}`; there were no listeners or events on `users.{userId}`.
  - **Impact**: None – legacy alias removed to simplify the channel surface.
- **Broadcast channel** `calendar` (routes/channels.php)
  - **Reason**: All calendar/holiday events and listeners use `calendar.holidays`, `admin.dashboard`, `hr.dashboard`, and `employees`.
  - **Impact**: None – no events or Echo listeners referenced `calendar`.
- **Event** `App\Events\NotificationEvent`
  - **Reason**: Marked deprecated, fully superseded by `App\Notifications\SystemNotification` (database + broadcast notifications).
  - **Impact**: None – no references remained in the main HRIS app.

### 9.2 Retained Real-time Use-cases

- **Per-user notifications and unread badge**
  - **Backend**: `SystemNotification`, `NotificationsUnreadCountUpdated`.
  - **Channels**: `private-App.Models.User.{id}`.
  - **Frontend**: `useBroadcasting.setupUserListeners`, generic `.notification(...)` handler, `.notifications.unread.updated` listener, unread badges in header/sidebar.
  - **Value**: Keeps notification dropdown and badges in sync across all tabs/devices for a user.
- **Leave lifecycle (submit/approve/reject/cancel + queue counts)**
  - **Backend**: `LeaveSubmitted`, `LeaveApproved`, `LeaveRejected`, `LeaveCancelled`, `LeaveStatusUpdated`.
  - **Channels**: `private-hr.dashboard`, `private-App.Models.User.{id}`, `private-leave.management`, `private-role.hr`, `private-role.employee`.
  - **Frontend**: `useBroadcasting.setupUserListeners`, HR Leave index page listeners.
  - **Value**: HR and employees on different browsers see leave queues and statuses update without refresh.
- **Training status**
  - **Backend**: `TrainingStatusUpdated`.
  - **Channels**: `private-training.management`, `private-role.hr`, `private-role.employee`.
  - **Frontend**: HR Training index page and `useBroadcasting.setupHrListeners`.
  - **Value**: Shared training queues and completion status update in real time.
- **PDS status**
  - **Backend**: `PdsStatusUpdated`.
  - **Channels**: `private-pds.management`, `private-role.hr`, `private-role.employee`.
  - **Frontend**: HR PDS index page and `useBroadcasting.setupHrListeners`.
  - **Value**: HR and employees see PDS review/approval states update across sessions.
- **Announcements / posts**
  - **Backend**: `PostCreated`, `PostReactionUpdated`, `PostCommentCreated`.
  - **Channels**: `private-posts.all`, `private-posts.hr`, `private-posts.employee` (depending on `role_scope`).
  - **Frontend**: `useBroadcasting.setupPostListeners`, posts pages using `lastPostEvent`.
  - **Value**: Real-time noticeboard (new posts, reactions, comments) across many users.
- **User identity / profile updates**
  - **Backend**: `UserIdentityUpdated`.
  - **Channels**: `private-App.Models.User.{id}`, `private-admin.dashboard`, `private-hr.dashboard`, `private-employees`.
  - **Frontend**: `useRealtimeUserIdentity`, `useBroadcasting` identity listeners.
  - **Value**: Name/role/status/avatar changes propagate to all open sessions quickly.
- **Avatar updates**
  - **Backend**: `AvatarUpdated`.
  - **Channels**: `private-avatar-updates`.
  - **Frontend**: `useRealtimeAvatar` (Echo + localStorage cache + Inertia reload).
  - **Value**: Avatars stay visually consistent across dashboards, logs, and posts.
- **Holiday/calendar updates**
  - **Backend**: `HolidayAdded`, `HolidayUpdated`, `CustomHolidayCreated`, `CustomHolidayUpdated`, `CustomHolidayDeleted`.
  - **Channels**: `private-calendar.holidays`, `private-admin.dashboard`, `private-hr.dashboard`, `private-employees`.
  - **Frontend**: `useBroadcasting` (admin/HR/employee listeners) and calendar/dashboard pages.
  - **Value**: Shared holiday changes visible to all roles without refresh.

### 9.3 Best-practice Real-time Patterns

- **Private channels & auth**
  - All retained channels are private and defined in `routes/channels.php` with explicit authorization callbacks using `App\Models\User` helpers (`isAdmin`, `isHr`, `isAdminOrHr`) and id checks.
  - Examples: `App.Models.User.{id}`, `role.{role}`, `leave.management`, `training.management`, `pds.management`, `calendar.holidays`, `admin.dashboard`, `hr.dashboard`, `employees`, `posts.*`, `avatar-updates`.
- **Minimal, non-sensitive payloads**
  - Each event’s `broadcastWith()` (and `SystemNotification::toBroadcast()`) sends only business fields needed by the UI (ids, names, titles, status, dates, counts, avatar URLs).
  - No secrets, tokens, or internal-only fields are broadcast; clients can always fetch additional data via authenticated HTTP when needed.
- **Queue-driven broadcasting**
  - All real-time events and `SystemNotification` now implement `ShouldBroadcast` (not `ShouldBroadcastNow`).
  - Recommended `.env` configuration:
    - `QUEUE_CONNECTION=redis`
    - `BROADCAST_CONNECTION=reverb`
  - Recommended queue worker:
    - `php artisan queue:work --queue=default,broadcast`
  - This keeps UI latency extremely low in practice while improving throughput and resilience under load.
- **Reverb / Redis tuning (infrastructure)**
  - `config/reverb.php` is ready for scaling via env:
    - `REVERB_SCALING_ENABLED=true`
    - `REVERB_SCALING_CHANNEL=reverb`
  - Backed by Redis (`REDIS_URL` or `REDIS_HOST`/`REDIS_PORT`/`REDIS_DB`/`REDIS_TIMEOUT`) to handle fan-out for ~1,000 concurrent connections.
  - TLS is enabled in production via:
    - `REVERB_SCHEME=https`, `PUSHER_SCHEME=https`, `useTLS=true`.

### 9.4 Automated Tests – Backend (Pest)

- **File** `tests/Feature/BroadcastingTest.php`
  - Verifies `SystemNotification` implements `ShouldBroadcast`.
  - Asserts leave events (`LeaveSubmitted`, `LeaveApproved`, `LeaveRejected`, `LeaveCancelled`) implement `ShouldBroadcast` and target:
    - `private-hr.dashboard`
    - `private-App.Models.User.{id}` when the employee is linked to a user.
  - Asserts `NotificationsUnreadCountUpdated`:
    - Implements `ShouldBroadcast`
    - Broadcasts on `private-App.Models.User.{id}`
    - Uses event name `notifications.unread.updated` with payload `['count' => N]`.
  - Asserts `UserIdentityUpdated`:
    - Implements `ShouldBroadcast`
    - Broadcasts to `private-App.Models.User.{id}`, `private-admin.dashboard`, `private-hr.dashboard`, `private-employees`
    - Payload includes only expected identity fields.
  - Asserts post events (`PostCreated`, `PostReactionUpdated`, `PostCommentCreated`) broadcast correctly to `private-posts.*` channels with the expected event names.
  - Asserts `AvatarUpdated` broadcasts on `private-avatar-updates` with `{ user_id, avatar, action }`.
  - Asserts `HolidayAdded`, `HolidayUpdated`, `CustomHolidayCreated`, `CustomHolidayUpdated`, `CustomHolidayDeleted` broadcast on `private-calendar.holidays`.
  - Asserts `PdsStatusUpdated` and `TrainingStatusUpdated` broadcast on `private-role.hr`, `private-role.employee`, and their respective management channels.
- **File** `tests/Feature/NotificationsTest.php`
  - Keeps functional coverage for notifications CRUD (index, markAsRead, unreadCount, dropdown JSON, markAllRead, delete).
  - Confirms `SystemNotification` implements `ShouldBroadcast`.

### 9.5 Dusk & Cross-browser Real-time Scenarios (Skeletons)

> Note: These are **designs/examples** for Laravel Dusk tests. Actual Dusk setup (installation, `DuskTestCase`, Selenium/Grid, Chrome/Firefox/Safari/Edge coverage) must be done in your environment.

- **NotificationsRealtimeTest (concept)**
  - Browser A (Chrome): Admin logs in, opens notifications dropdown.
  - Browser B (Firefox): Same user logs in, keeps another tab open.
  - Action: Trigger `SystemNotification` + `NotificationsUnreadCountUpdated`.
  - Expectation: Both browsers update their notification badge and dropdown entries without manual reload.
- **LeaveRealtimeTest**
  - Browser A: Employee submits a leave request.
  - Browser B: HR leave index open, observing `leavesPendingCount` and table.
  - Action: Submit, then approve/reject from B.
  - Expectation: Employee sees status change; HR queue count and row update in real time.
- **TrainingRealtimeTest & PdsRealtimeTest**
  - Similar pattern: HR vs employee views, verifying `TrainingStatusUpdated` and `PdsStatusUpdated` events update grids and pending counters.
- **PostsRealtimeTest**
  - Multiple browsers on different roles:
    - Admin creates posts (`PostCreated`), reacts (`PostReactionUpdated`), and comments (`PostCommentCreated`).
  - Expectation: Relevant role-scoped feeds show new posts and updated reaction/comment counts in real time.
- **IdentityAvatarRealtimeTest**
  - Browser A (admin): updates another user’s profile/role/avatar.
  - Browsers B–D (Chrome/Firefox/Safari/Edge) logged in as that user.
  - Expectation: All sessions update displayed name, role-based navigation, and avatar via `UserIdentityUpdated` and `AvatarUpdated`, without reload.
- **HolidaysRealtimeTest**
  - Browser A (admin or HR): adds/updates a custom holiday.
  - Browsers B–D (employee/HR/admin dashboards and calendars).
  - Expectation: Holiday lists and stats reflect the change as soon as events fire.

You can encode these as Dusk tests under `tests/Browser/` (e.g. `NotificationsRealtimeTest.php`, `LeaveRealtimeTest.php`, etc.) once Dusk is installed, mapping each scenario to actual selectors and routes.

### 9.6 Performance & Load Testing Guidance

- **Target**: p95 < 200 ms from action to Echo callback under ~1,000 concurrent Reverb connections.
- **Recommended approach (example)**:
  - Use a tool like **k6** or **Artillery** to:
    - Open N WebSocket connections to Reverb using the Pusher protocol (matching your `REVERB_APP_KEY` etc.).
    - Repeatedly perform HTTP actions (e.g. create posts, submit/approve leaves, send notifications) that dispatch broadcast events.
    - Measure time between:
      - Server-side timestamp in event payload (`timestamp` fields), and
      - Client-side receipt time on the WebSocket.
  - Run against a staging environment with:
    - `QUEUE_CONNECTION=redis`
    - `BROADCAST_CONNECTION=reverb`
    - `REVERB_SCALING_ENABLED=true`
    - Redis sized for 1,000+ concurrent connections.
- **Artifacts to archive**:
  - Raw test result JSON/CSV (p95/p99 latency, error rates).
  - HTML or dashboard exports (Grafana/Prometheus if used).
  - Brief markdown summary per run (date, config, key metrics) under e.g. `storage/perf/realtime/`.

---

## Testing Notes Template

| Date | Tester | Feature | Status | Notes |
|------|--------|---------|--------|-------|
| | | | Pass/Fail/Bug | |

---

## Bug Report Template

**Bug ID:** BUG-XXX
**Severity:** High/Medium/Low
**Feature:** 
**Steps to Reproduce:**
1. 
2. 
3. 

**Expected Result:**
**Actual Result:**
**Screenshots:**
**Environment:** Browser, OS, Screen Size

---

*Last Updated: March 6, 2026*

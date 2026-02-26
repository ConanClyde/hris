# Real-Time Profile Sync

Profile changes are reflected across the app in real time: sidebar, profile page, and user management.

## How It Works

1. **Sidebar profile data** – No longer hardcoded. The sidebar gets display name, email, avatar, and initial from:
   - View composer: `AppServiceProvider` passes `$sidebarProfile` to `partials.sidebar` (from `User`, `Employee`, and session).
   - Optional API: on load, the frontend can call `GET /api/me` to hydrate the sidebar.

2. **Persistence** – Profile updates (profile page) are saved to:
   - **User**: `name`, `email`
   - **Employee** (if present): `first_name`, `middle_name`, `last_name`, `name_extension`, `email`
   - **Session**: all profile fields used by the UI

3. **Real-time** – Two mechanisms:
   - **Server-Sent Events (SSE)** – `GET /sse/profile` (session auth). When a profile is updated (profile page or user management), the server pushes the new profile over this stream so every open tab updates the sidebar without refresh.
   - **Laravel Broadcasting** – `ProfileUpdated` is broadcast on the private channel `users.{userId}`. If you enable a WebSocket driver (e.g. Reverb) and Laravel Echo, the same updates can be received over WebSockets.

4. **Where updates are triggered**
   - **Profile page** – `ProfileController::update()` saves to User/Employee/session, then dispatches `ProfileUpdated` and pushes to the SSE cache.
   - **User management (admin/HR)** – `UserController::update()`: if the updated user is the current user, session is synced and `ProfileUpdated` is dispatched + SSE cache updated.

5. **Frontend**
   - **Sidebar** – Elements use `data-profile-name`, `data-profile-email`, `data-profile-initial`, `data-profile-avatar-img` so JS can update them when SSE (or Echo) delivers a new profile.
   - **Script** – In `partials/dashboard-scripts.blade.php`: connects to `/sse/profile`, on `profile` event updates the sidebar DOM and dispatches a custom `profile-updated` event so other components can react (e.g. user table refresh).

6. **Notifications**
   - Navbar notification badge has `data-notifications-badge` and `data-count`. The existing `realtime:notice-published` listener increments the count. “View all notifications” links to the role-based notifications page.

## Testing

- **Profile page** – Edit name/email/avatar and save; sidebar and profile page should show the new data; open another tab and confirm the sidebar there updates within a few seconds (SSE poll interval is 2s).
- **User management** – As admin/HR, edit the *current* user’s name/email/role; save; confirm sidebar and session reflect the change in all open tabs.
- **Notifications** – When a notice is published (and your app broadcasts `NoticePublished`), the navbar badge count should increase (if the frontend subscribes to that event).

## Configuration

- **SSE** – No extra config. Uses default cache (file/redis/database) for `sse_profile_{userId}`.
- **Broadcasting** – Optional. Set `BROADCAST_CONNECTION` (e.g. `reverb`) and run the broadcast auth route so Echo can subscribe to `private-users.{id}` for the same `ProfileUpdated` events.

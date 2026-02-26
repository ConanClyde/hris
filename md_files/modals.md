# Modals (Dialogs)

This document lists the modal dialogs currently implemented in the system.

## Shared modal primitive

- **Dialog components**
  - **Location**: `resources/js/components/ui/dialog/*`
  - **Purpose**: Shared dialog/modal UI primitive (used across pages via `<Dialog>` / `<DialogContent>` etc.).

## Related overlays (not included)

- **Sheet components**
  - **Location**: `resources/js/components/ui/sheet/*`
  - **Purpose**: Drawer/side-panel overlay (not a modal dialog, but similar UI pattern).

## Auth / Security

- **Two-Factor Setup Modal**
  - **Location**: `resources/js/components/TwoFactorSetupModal.vue`
  - **Purpose**: Enable / confirm two-factor authentication (QR code + verification step).
  - **Trigger**: Controlled via `v-model` `isOpen`.

## Admin

### Admin - Users

- **View User modal**
  - **Location**: `resources/js/pages/Admin/Users/Index.vue`
  - **Purpose**: View selected user details.
  - **Trigger**: `openView(u)` sets `viewModalOpen = true`.

- **Edit User modal**
  - **Location**: `resources/js/pages/Admin/Users/Index.vue`
  - **Purpose**: Edit selected user details.
  - **Trigger**: `openEdit(u)` sets `editModalOpen = true`.

- **Delete User modal**
  - **Location**: `resources/js/pages/Admin/Users/Index.vue`
  - **Purpose**: Confirm deletion of selected user.
  - **Trigger**: `openDelete(u)` sets `deleteModalOpen = true`.

- **Add New User modal**
  - **Location**: `resources/js/pages/Admin/Users/Index.vue`
  - **Purpose**: Create a new user.
  - **Trigger**: button `Add New User` sets `addModalOpen = true`.

### Admin - Profile

- **Edit profile modal**
  - **Location**: `resources/js/pages/Admin/Profile/Index.vue`
  - **Purpose**: Update admin profile details.
  - **Trigger**: `editModalOpen = true`.

- **Crop photo modal**
  - **Location**: `resources/js/pages/Admin/Profile/Index.vue`
  - **Purpose**: Crop uploaded avatar image.
  - **Trigger**: `openCropModal(file)` sets `cropModalOpen = true`.

- **Change password modal**
  - **Location**: `resources/js/pages/Admin/Profile/Index.vue`
  - **Purpose**: Change account password.
  - **Trigger**: `passwordModalOpen = true`.

- **Delete account modal**
  - **Location**: `resources/js/pages/Admin/Profile/Index.vue`
  - **Purpose**: Confirm account deletion (password required).
  - **Trigger**: `deleteModalOpen = true`.

### Admin - Notices

- **Create Notice modal**
  - **Location**: `resources/js/pages/Admin/Notices/Index.vue`
  - **Purpose**: Create a new global notice.
  - **Trigger**: `createModalOpen = true`.

- **Edit Notice modal**
  - **Location**: `resources/js/pages/Admin/Notices/Index.vue`
  - **Purpose**: Edit an existing notice.
  - **Trigger**: `openEdit(n)` sets `editModalOpen = true`.

- **View Notice modal**
  - **Location**: `resources/js/pages/Admin/Notices/Index.vue`
  - **Purpose**: View notice details.
  - **Trigger**: `openView(n)` sets `viewModalOpen = true`.

- **Delete Notice modal**
  - **Location**: `resources/js/pages/Admin/Notices/Index.vue`
  - **Purpose**: Confirm deletion of a notice.
  - **Trigger**: `openDeleteNotice(n)` sets `deleteModalOpen = true`.

### Admin - Backup

- **Run Backup modal**
  - **Location**: `resources/js/pages/Admin/Backup/Index.vue`
  - **Purpose**: Confirm running a backup.
  - **Trigger**: `openRunBackupModal()` sets `runBackupModalOpen = true`.

- **Restore Backup modal**
  - **Location**: `resources/js/pages/Admin/Backup/Index.vue`
  - **Purpose**: Confirm restoring from a backup.
  - **Trigger**: `openRestoreModal(backup)` sets `restoreModalOpen = true`.

- **Delete Backup modal**
  - **Location**: `resources/js/pages/Admin/Backup/Index.vue`
  - **Purpose**: Confirm deletion of a backup.
  - **Trigger**: `openDeleteBackup(backup)` sets `deleteModalOpen = true`.

- **Backup Detail modal**
  - **Location**: `resources/js/pages/Admin/Backup/Index.vue`
  - **Purpose**: View backup metadata/details.
  - **Trigger**: `openDetailModal(backup)` sets `detailModalOpen = true`.

### Admin - Calendar

- **Create Holiday modal**
  - **Location**: `resources/js/pages/Admin/Calendar/Index.vue`
  - **Purpose**: Add a custom holiday.
  - **Trigger**: `openCreateModal()` sets `createModalOpen = true`.

- **Edit Holiday modal**
  - **Location**: `resources/js/pages/Admin/Calendar/Index.vue`
  - **Purpose**: Edit an existing holiday.
  - **Trigger**: `openEditModal(holiday)` sets `editModalOpen = true`.

- **Delete Holiday modal**
  - **Location**: `resources/js/pages/Admin/Calendar/Index.vue`
  - **Purpose**: Confirm deletion of a holiday.
  - **Trigger**: `openDeleteHoliday(holiday)` sets `deleteHolidayModalOpen = true`.

- **Date Events List modal**
  - **Location**: `resources/js/pages/Admin/Calendar/Index.vue`
  - **Purpose**: List events for the clicked date.
  - **Trigger**: `onDateClick(...)` sets `dateEventsModalOpen = true`.

- **Event Detail modal**
  - **Location**: `resources/js/pages/Admin/Calendar/Index.vue`
  - **Purpose**: View details for a selected event.
  - **Trigger**: `openEventDetail(ev)` or `onEventClick(...)` sets `eventDetailModalOpen = true`.

### Admin - Activity Logs

- **View Activity Log modal**
  - **Location**: `resources/js/pages/Admin/ActivityLogs/Index.vue`
  - **Purpose**: View details for a selected activity log entry.
  - **Trigger**: `openViewLog(log)` sets `viewLogModalOpen = true`.

## HR

### HR - Users

- **View User modal**
  - **Location**: `resources/js/pages/HR/Users/Index.vue`
  - **Purpose**: View selected user details.
  - **Trigger**: `openView(u)` sets `viewModalOpen = true`.

- **Edit User modal**
  - **Location**: `resources/js/pages/HR/Users/Index.vue`
  - **Purpose**: Edit selected user details.
  - **Trigger**: `openEdit(u)` sets `editModalOpen = true`.

- **Delete User modal**
  - **Location**: `resources/js/pages/HR/Users/Index.vue`
  - **Purpose**: Confirm deletion of selected user.
  - **Trigger**: `openDelete(u)` sets `deleteModalOpen = true`.

### HR - Profile

- **Edit profile modal**
  - **Location**: `resources/js/pages/HR/Profile/Index.vue`
  - **Purpose**: Update HR profile details.
  - **Trigger**: `editModalOpen = true`.

- **Crop avatar modal**
  - **Location**: `resources/js/pages/HR/Profile/Index.vue`
  - **Purpose**: Crop uploaded avatar image.
  - **Trigger**: `openCropModal(file)` sets `cropModalOpen = true`.

- **Change password modal**
  - **Location**: `resources/js/pages/HR/Profile/Index.vue`
  - **Purpose**: Change account password.
  - **Trigger**: `passwordModalOpen = true`.

- **Delete account modal**
  - **Location**: `resources/js/pages/HR/Profile/Index.vue`
  - **Purpose**: Confirm account deletion.
  - **Trigger**: `deleteModalOpen = true`.

### HR - Notices

- **Create notice modal**
  - **Location**: `resources/js/pages/HR/Notices/Index.vue`
  - **Purpose**: Create a new notice.
  - **Trigger**: `createModalOpen = true`.

- **Edit notice modal**
  - **Location**: `resources/js/pages/HR/Notices/Index.vue`
  - **Purpose**: Edit an existing notice.
  - **Trigger**: `openEdit(n)` sets `editModalOpen = true`.

- **View notice modal**
  - **Location**: `resources/js/pages/HR/Notices/Index.vue`
  - **Purpose**: View notice details.
  - **Trigger**: `openView(n)` sets `viewModalOpen = true`.

- **Delete notice modal**
  - **Location**: `resources/js/pages/HR/Notices/Index.vue`
  - **Purpose**: Confirm deletion.
  - **Trigger**: `openDeleteNotice(n)` sets `deleteModalOpen = true`.

### HR - Leave Applications

- **View Leave application dialog**
  - **Location**: `resources/js/pages/HR/Leave/Index.vue`
  - **Purpose**: View leave application details (also approve/reject inside modal if pending).
  - **Trigger**: `openView(app)` sets `viewDialogOpen = true`.

- **Apply for leave dialog**
  - **Location**: `resources/js/pages/HR/Leave/Index.vue`
  - **Purpose**: Create a leave application.
  - **Trigger**: `openAdd()` sets `addDialogOpen = true`.

- **Edit leave application dialog**
  - **Location**: `resources/js/pages/HR/Leave/Index.vue`
  - **Purpose**: Edit a leave application.
  - **Trigger**: `openEdit(app)` sets `editDialogOpen = true`.

- **Delete leave application modal**
  - **Location**: `resources/js/pages/HR/Leave/Index.vue`
  - **Purpose**: Confirm deletion.
  - **Trigger**: `openDeleteApplication(app)` sets `deleteModalOpen = true`.

### HR - Training

- **Delete Training Record modal**
  - **Location**: `resources/js/pages/HR/Training/Index.vue`
  - **Purpose**: Confirm deletion of a training record.
  - **Trigger**: `openDeleteTraining(t)` sets `deleteModalOpen = true`.

### HR - Leave Credits

- **View Leave Credit modal**
  - **Location**: `resources/js/pages/HR/LeaveCredits/Index.vue`
  - **Purpose**: View leave credit balance and adjustment history for a selected employee/credit.
  - **Trigger**: Opening uses query param `view=<id>` (server returns `creditDetail`); modal is shown when `Boolean(creditDetail)`.

### HR - Calendar

- **Events modal**
  - **Location**: `resources/js/pages/HR/Calendar/Index.vue`
  - **Purpose**: List events for a selected date.
  - **Trigger**: date click sets `dateEventsModalOpen = true`.

- **Event Detail modal**
  - **Location**: `resources/js/pages/HR/Calendar/Index.vue`
  - **Purpose**: Show details for selected event.
  - **Trigger**: `openEventDetail(ev)` sets `eventDetailModalOpen = true`.

### HR - PDS (Personal Data Sheet)

- **PDS Preview modal**
  - **Location**: `resources/js/pages/HR/PDS/Index.vue`
  - **Purpose**: Preview submitted PDS data for review.
  - **Trigger**: Opening uses query param `preview_id=<id>` (server returns `pdsDetail`); modal is shown when `Boolean(pdsDetail)`.

## Employee

### Employee - Profile

- **Edit profile modal**
  - **Location**: `resources/js/pages/Employee/Profile/Index.vue`
  - **Purpose**: Update employee profile.
  - **Trigger**: `editModalOpen = true`.

- **Crop photo modal**
  - **Location**: `resources/js/pages/Employee/Profile/Index.vue`
  - **Purpose**: Crop uploaded avatar.
  - **Trigger**: `openCropModal(file)` sets `cropModalOpen = true`.

- **Change password modal**
  - **Location**: `resources/js/pages/Employee/Profile/Index.vue`
  - **Purpose**: Change password.
  - **Trigger**: `passwordModalOpen = true`.

- **Delete account modal**
  - **Location**: `resources/js/pages/Employee/Profile/Index.vue`
  - **Purpose**: Confirm account deletion.
  - **Trigger**: `deleteModalOpen = true`.

### Employee - Leave Applications

- **View Leave Application modal**
  - **Location**: `resources/js/pages/Employee/Leave/Index.vue`
  - **Purpose**: View leave application details.
  - **Trigger**: `openView(app)` sets `viewModalOpen = true`.

- **Apply for Leave modal**
  - **Location**: `resources/js/pages/Employee/Leave/Index.vue`
  - **Purpose**: Create leave application.
  - **Trigger**: `openAdd()` sets `addModalOpen = true`.

- **Edit Leave Application modal**
  - **Location**: `resources/js/pages/Employee/Leave/Index.vue`
  - **Purpose**: Edit a pending leave application.
  - **Trigger**: `openEdit(app)` sets `editModalOpen = true`.

### Employee - Training

- **View Training modal**
  - **Location**: `resources/js/pages/Employee/Training/Index.vue`
  - **Purpose**: View training record details.
  - **Trigger**: `openView(t)` sets `viewModalOpen = true`.

- **Add Training modal**
  - **Location**: `resources/js/pages/Employee/Training/Index.vue`
  - **Purpose**: Create a training record.
  - **Trigger**: `openAdd()` sets `addModalOpen = true`.

- **Edit Training modal**
  - **Location**: `resources/js/pages/Employee/Training/Index.vue`
  - **Purpose**: Edit a pending training record.
  - **Trigger**: `openEdit(t)` sets `editModalOpen = true`.

### Employee - Calendar

- **Date Events List modal**
  - **Location**: `resources/js/pages/Employee/Calendar/Index.vue`
  - **Purpose**: List events for the clicked date.
  - **Trigger**: `onDateClick(...)` sets `dateEventsModalOpen = true`.

- **Event Detail modal**
  - **Location**: `resources/js/pages/Employee/Calendar/Index.vue`
  - **Purpose**: View details for a selected event.
  - **Trigger**: `openEventDetail(ev)` or `onEventClick(...)` sets `eventDetailModalOpen = true`.

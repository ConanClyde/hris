# Application Routing & API Endpoints

This document catalogs all registered routes within the system, dividing them by their primary modules.

Key:
- **URI:** The endpoint path
- **Methods:** HTTP Verbs allowed
- **Name:** The internal route identifier (used for `route()` helpers)
- **Controller/Action:** The backend handling logic
- **Middleware:** Guards and filters applied to the route

---

## API Endpoints (v1)

| Methods | URI | Name | Middleware | Action |
|---------|-----|------|------------|--------|
| `GET|HEAD` | `/api/v1/leave-applications` | `-` | `api, integration, throttle:api` | `App\Features\Leave\Http\Controllers\Api\LeaveApiController@index` |
| `POST` | `/api/v1/leave-applications` | `-` | `api, integration, throttle:api` | `App\Features\Leave\Http\Controllers\Api\LeaveApiController@store` |
| `GET|HEAD` | `/api/v1/leave-applications/{id}` | `-` | `api, integration, throttle:api` | `App\Features\Leave\Http\Controllers\Api\LeaveApiController@show` |
| `PUT` | `/api/v1/leave-applications/{id}/status` | `-` | `api, integration, throttle:api` | `App\Features\Leave\Http\Controllers\Api\LeaveApiController@updateStatus` |
| `GET|HEAD` | `/api/v1/notices/active` | `-` | `api, integration, throttle:api` | `App\Features\Notices\Http\Controllers\Api\NoticeApiController@active` |
| `GET|HEAD` | `/api/v1/organizational-structure` | `-` | `api, integration, throttle:api` | `App\Features\Employees\Http\Controllers\Api\StructureController@index` |
| `GET|HEAD` | `/api/v1/trainings` | `-` | `api, integration, throttle:api` | `App\Features\Training\Http\Controllers\Api\TrainingApiController@index` |
| `PUT` | `/api/v1/trainings/{id}/status` | `-` | `api, integration, throttle:api` | `App\Features\Training\Http\Controllers\Api\TrainingApiController@updateStatus` |

## Admin Module

| Methods | URI | Name | Middleware | Action |
|---------|-----|------|------------|--------|
| `GET|HEAD` | `/admin/activity-logs` | `admin.activity-logs.index` | `web, auth, role:admin` | `App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController@index` |
| `GET|HEAD` | `/admin/activity-logs/export` | `admin.activity-logs.export` | `web, auth, role:admin` | `App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController@export` |
| `GET|HEAD` | `/admin/backup` | `admin.backup.index` | `web, auth, role:admin` | `App\Features\Backup\Http\Controllers\Admin\BackupController@index` |
| `POST` | `/admin/backup/run` | `admin.backup.run` | `web, auth, role:admin` | `App\Features\Backup\Http\Controllers\Admin\BackupController@run` |
| `POST` | `/admin/backup/upload` | `admin.backup.upload` | `web, auth, role:admin` | `App\Features\Backup\Http\Controllers\Admin\BackupController@upload` |
| `PUT` | `/admin/backup/{id}` | `admin.backup.update` | `web, auth, role:admin` | `App\Features\Backup\Http\Controllers\Admin\BackupController@update` |
| `DELETE` | `/admin/backup/{id}` | `admin.backup.destroy` | `web, auth, role:admin` | `App\Features\Backup\Http\Controllers\Admin\BackupController@destroy` |
| `GET|HEAD` | `/admin/backup/{id}/download` | `admin.backup.download` | `web, auth, role:admin` | `App\Features\Backup\Http\Controllers\Admin\BackupController@download` |
| `POST` | `/admin/backup/{id}/restore` | `admin.backup.restore` | `web, auth, role:admin` | `App\Features\Backup\Http\Controllers\Admin\BackupController@restore` |
| `GET|HEAD` | `/admin/calendar` | `admin.calendar` | `web, auth, role:admin` | `App\Features\Calendar\Http\Controllers\Admin\CalendarController@index` |
| `GET|HEAD` | `/admin/calendar/events` | `admin.calendar.events` | `web, auth, role:admin` | `App\Features\Calendar\Http\Controllers\Admin\CalendarController@events` |
| `GET|HEAD` | `/admin/custom-holidays` | `admin.custom-holidays.index` | `web, auth, role:admin` | `App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController@index` |
| `POST` | `/admin/custom-holidays` | `admin.custom-holidays.store` | `web, auth, role:admin` | `App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController@store` |
| `PUT|PATCH` | `/admin/custom-holidays/{custom_holiday}` | `admin.custom-holidays.update` | `web, auth, role:admin` | `App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController@update` |
| `DELETE` | `/admin/custom-holidays/{custom_holiday}` | `admin.custom-holidays.destroy` | `web, auth, role:admin` | `App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController@destroy` |
| `GET|HEAD` | `/admin/dashboard` | `admin.dashboard` | `web, auth, role:admin` | `App\Features\Dashboard\Http\Controllers\DashboardController@admin` |
| `GET|HEAD` | `/admin/notices` | `admin.notices.index` | `web, auth, role:admin` | `App\Features\Notices\Http\Controllers\Admin\NoticeController@index` |
| `POST` | `/admin/notices` | `admin.notices.store` | `web, auth, role:admin` | `App\Features\Notices\Http\Controllers\Admin\NoticeController@store` |
| `GET|HEAD` | `/admin/notices/create` | `admin.notices.create` | `web, auth, role:admin` | `App\Features\Notices\Http\Controllers\Admin\NoticeController@create` |
| `PUT` | `/admin/notices/{id}` | `admin.notices.update` | `web, auth, role:admin` | `App\Features\Notices\Http\Controllers\Admin\NoticeController@update` |
| `DELETE` | `/admin/notices/{id}` | `admin.notices.destroy` | `web, auth, role:admin` | `App\Features\Notices\Http\Controllers\Admin\NoticeController@destroy` |
| `GET|HEAD` | `/admin/notices/{id}/edit` | `admin.notices.edit` | `web, auth, role:admin` | `App\Features\Notices\Http\Controllers\Admin\NoticeController@edit` |
| `GET|HEAD` | `/admin/notifications` | `admin.notifications` | `web, auth, role:admin` | `App\Features\Notifications\Http\Controllers\NotificationController@index` |
| `GET|HEAD` | `/admin/notifications/unread-count` | `admin.notifications.unread-count` | `web, auth, role:admin` | `App\Features\Notifications\Http\Controllers\NotificationController@unreadCount` |
| `POST` | `/admin/notifications/{noticeId}/mark-as-read` | `admin.notifications.mark-as-read` | `web, auth, role:admin` | `App\Features\Notifications\Http\Controllers\NotificationController@markAsRead` |
| `GET|HEAD` | `/admin/performance` | `admin.performance.index` | `web, auth, role:admin` | `App\Features\Dashboard\Http\Controllers\PerformanceController@index` |
| `GET|HEAD` | `/admin/profile` | `admin.profile` | `web, auth, role:admin` | `App\Features\Auth\Http\Controllers\ProfileController@show` |
| `PUT|PATCH` | `/admin/profile` | `admin.profile.update` | `web, auth, role:admin` | `App\Features\Auth\Http\Controllers\ProfileController@update` |
| `DELETE` | `/admin/profile` | `admin.profile.delete` | `web, auth, role:admin` | `App\Features\Auth\Http\Controllers\ProfileController@destroy` |
| `POST` | `/admin/profile/password` | `admin.profile.password` | `web, auth, role:admin` | `App\Features\Auth\Http\Controllers\ProfileController@changePassword` |
| `GET|HEAD` | `/admin/reports` | `admin.reports` | `web, auth, role:admin` | `App\Features\Dashboard\Http\Controllers\ReportsController@index` |
| `GET|HEAD` | `/admin/settings` | `admin.settings` | `web, auth, role:admin` | `*(Closure)*` |
| `POST` | `/admin/users` | `admin.users.store` | `web, auth, role:admin` | `App\Features\Users\Http\Controllers\Admin\UserController@store` |
| `POST` | `/admin/users/bulk-action` | `admin.users.bulk_action` | `web, auth, role:admin` | `App\Features\Users\Http\Controllers\Admin\UserController@bulkAction` |
| `PUT` | `/admin/users/{id}` | `admin.users.update` | `web, auth, role:admin` | `App\Features\Users\Http\Controllers\Admin\UserController@update` |
| `DELETE` | `/admin/users/{id}` | `admin.users.destroy` | `web, auth, role:admin` | `App\Features\Users\Http\Controllers\Admin\UserController@destroy` |
| `PATCH` | `/admin/users/{id}/toggle-status` | `admin.users.toggle-status` | `web, auth, role:admin` | `App\Features\Users\Http\Controllers\Admin\UserController@toggleStatus` |
| `GET|HEAD` | `/admin/users/{status?}` | `admin.users` | `web, auth, role:admin` | `App\Features\Users\Http\Controllers\Admin\UserController@index` |

## HR Module

| Methods | URI | Name | Middleware | Action |
|---------|-----|------|------------|--------|
| `GET|HEAD` | `/hr/calendar` | `hr.calendar` | `web, auth, role:hr` | `App\Features\Calendar\Http\Controllers\HR\CalendarController@index` |
| `GET|HEAD` | `/hr/calendar/events` | `hr.calendar.events` | `web, auth, role:hr` | `App\Features\Calendar\Http\Controllers\HR\CalendarController@events` |
| `GET|HEAD` | `/hr/dashboard` | `hr.dashboard` | `web, auth, role:hr` | `App\Features\Dashboard\Http\Controllers\DashboardController@hr` |
| `GET|HEAD` | `/hr/activity-logs` | `hr.activity-logs.index` | `web, auth, role:hr` | `App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController@userIndex` |
| `GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS` | `/hr/employees` | `-` | `web, auth, role:hr` | `Illuminate\Routing\RedirectController` |
| `GET|HEAD` | `/hr/leave-applications` | `hr.leave-applications.index` | `web, auth, role:hr` | `App\Features\Leave\Http\Controllers\HR\LeaveController@index` |
| `POST` | `/hr/leave-applications` | `hr.leave-applications.store` | `web, auth, role:hr` | `App\Features\Leave\Http\Controllers\HR\LeaveController@store` |
| `GET|HEAD` | `/hr/leave-applications/export` | `hr.leave-applications.export` | `web, auth, role:hr` | `App\Features\Leave\Http\Controllers\HR\LeaveController@export` |
| `PUT` | `/hr/leave-applications/{id}` | `hr.leave-applications.update` | `web, auth, role:hr` | `App\Features\Leave\Http\Controllers\HR\LeaveController@update` |
| `DELETE` | `/hr/leave-applications/{id}` | `hr.leave-applications.destroy` | `web, auth, role:hr` | `App\Features\Leave\Http\Controllers\HR\LeaveController@destroy` |
| `DELETE` | `/hr/leave-attachments/{id}` | `hr.leave-attachments.destroy` | `web, auth, role:hr` | `App\Features\Leave\Http\Controllers\HR\LeaveController@destroyAttachment` |
| `GET|HEAD` | `/hr/leave-credits` | `hr.leave-credits.index` | `web, auth, role:hr` | `App\Features\Leave\Http\Controllers\HR\LeaveCreditController@index` |
| `GET|HEAD` | `/hr/leave-credits/{id}` | `hr.leave-credits.show` | `web, auth, role:hr` | `App\Features\Leave\Http\Controllers\HR\LeaveCreditController@show` |
| `GET|HEAD` | `/hr/notices` | `hr.notices.index` | `web, auth, role:hr` | `App\Features\Notices\Http\Controllers\HR\NoticeController@index` |
| `POST` | `/hr/notices` | `hr.notices.store` | `web, auth, role:hr` | `App\Features\Notices\Http\Controllers\HR\NoticeController@store` |
| `GET|HEAD` | `/hr/notices/create` | `hr.notices.create` | `web, auth, role:hr` | `App\Features\Notices\Http\Controllers\HR\NoticeController@create` |
| `PUT` | `/hr/notices/{id}` | `hr.notices.update` | `web, auth, role:hr` | `App\Features\Notices\Http\Controllers\HR\NoticeController@update` |
| `DELETE` | `/hr/notices/{id}` | `hr.notices.destroy` | `web, auth, role:hr` | `App\Features\Notices\Http\Controllers\HR\NoticeController@destroy` |
| `GET|HEAD` | `/hr/notices/{id}/edit` | `hr.notices.edit` | `web, auth, role:hr` | `App\Features\Notices\Http\Controllers\HR\NoticeController@edit` |
| `GET|HEAD` | `/hr/notifications` | `hr.notifications` | `web, auth, role:hr` | `App\Features\Notifications\Http\Controllers\NotificationController@index` |
| `GET|HEAD` | `/hr/notifications/unread-count` | `hr.notifications.unread-count` | `web, auth, role:hr` | `App\Features\Notifications\Http\Controllers\NotificationController@unreadCount` |
| `POST` | `/hr/notifications/{noticeId}/mark-as-read` | `hr.notifications.mark-as-read` | `web, auth, role:hr` | `App\Features\Notifications\Http\Controllers\NotificationController@markAsRead` |
| `GET|HEAD` | `/hr/pds` | `hr.pds.index` | `web, auth, role:hr` | `App\Features\Pds\Http\Controllers\HR\PdsController@index` |
| `GET|HEAD` | `/hr/pds/preview` | `hr.pds.preview` | `web, auth, role:hr` | `App\Features\Pds\Http\Controllers\HR\PdsController@preview` |
| `POST` | `/hr/pds/status` | `hr.pds.status` | `web, auth, role:hr` | `App\Features\Pds\Http\Controllers\HR\PdsController@updateStatus` |
| `GET|HEAD` | `/hr/profile` | `hr.profile` | `web, auth, role:hr` | `App\Features\Auth\Http\Controllers\ProfileController@show` |
| `PUT|PATCH` | `/hr/profile` | `hr.profile.update` | `web, auth, role:hr` | `App\Features\Auth\Http\Controllers\ProfileController@update` |
| `DELETE` | `/hr/profile` | `hr.profile.delete` | `web, auth, role:hr` | `App\Features\Auth\Http\Controllers\ProfileController@destroy` |
| `POST` | `/hr/profile/password` | `hr.profile.password` | `web, auth, role:hr` | `App\Features\Auth\Http\Controllers\ProfileController@changePassword` |
| `GET|HEAD` | `/hr/reports` | `hr.reports` | `web, auth, role:hr` | `*(Closure)*` |
| `GET|HEAD` | `/hr/settings` | `hr.settings` | `web, auth, role:hr` | `*(Closure)*` |
| `GET|HEAD` | `/hr/training` | `hr.training.index` | `web, auth, role:hr` | `App\Features\Training\Http\Controllers\HR\TrainingController@index` |
| `POST` | `/hr/training` | `hr.training.store` | `web, auth, role:hr` | `App\Features\Training\Http\Controllers\HR\TrainingController@store` |
| `PUT` | `/hr/training/{id}` | `hr.training.update` | `web, auth, role:hr` | `App\Features\Training\Http\Controllers\HR\TrainingController@update` |
| `DELETE` | `/hr/training/{id}` | `hr.training.destroy` | `web, auth, role:hr` | `App\Features\Training\Http\Controllers\HR\TrainingController@destroy` |
| `POST` | `/hr/users` | `hr.users.store` | `web, auth, role:hr` | `App\Features\Users\Http\Controllers\Admin\UserController@store` |
| `POST` | `/hr/users/bulk-action` | `hr.users.bulk_action` | `web, auth, role:hr` | `App\Features\Users\Http\Controllers\Admin\UserController@bulkAction` |
| `PUT` | `/hr/users/{id}` | `hr.users.update` | `web, auth, role:hr` | `App\Features\Users\Http\Controllers\Admin\UserController@update` |
| `DELETE` | `/hr/users/{id}` | `hr.users.destroy` | `web, auth, role:hr` | `App\Features\Users\Http\Controllers\Admin\UserController@destroy` |
| `PATCH` | `/hr/users/{id}/toggle-status` | `hr.users.toggle-status` | `web, auth, role:hr` | `App\Features\Users\Http\Controllers\Admin\UserController@toggleStatus` |
| `GET|HEAD` | `/hr/users/{status?}` | `hr.users.index` | `web, auth, role:hr` | `App\Features\Users\Http\Controllers\Admin\UserController@index` |

## Employee Module

| Methods | URI | Name | Middleware | Action |
|---------|-----|------|------------|--------|
| `GET|HEAD` | `/employee/calendar` | `employee.calendar` | `web, auth, role:employee` | `App\Features\Calendar\Http\Controllers\Employee\CalendarController@index` |
| `GET|HEAD` | `/employee/calendar/events` | `employee.calendar.events` | `web, auth, role:employee` | `App\Features\Calendar\Http\Controllers\Employee\CalendarController@events` |
| `GET|HEAD` | `/employee/dashboard` | `employee.dashboard` | `web, auth, role:employee` | `App\Features\Dashboard\Http\Controllers\DashboardController@employee` |
| `GET|HEAD` | `/employee/activity-logs` | `employee.activity-logs.index` | `web, auth, role:employee` | `App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController@userIndex` |
| `GET|HEAD` | `/employee/leave-applications` | `employee.leave-applications.index` | `web, auth, role:employee` | `App\Features\Leave\Http\Controllers\Employee\LeaveController@index` |
| `POST` | `/employee/leave-applications` | `employee.leave-applications.store` | `web, auth, role:employee` | `App\Features\Leave\Http\Controllers\Employee\LeaveController@store` |
| `GET|HEAD` | `/employee/leave-applications/export` | `employee.leave-applications.export` | `web, auth, role:employee` | `App\Features\Leave\Http\Controllers\Employee\LeaveController@export` |
| `PUT` | `/employee/leave-applications/{id}` | `employee.leave-applications.update` | `web, auth, role:employee` | `App\Features\Leave\Http\Controllers\Employee\LeaveController@update` |
| `DELETE` | `/employee/leave-applications/{id}` | `employee.leave-applications.destroy` | `web, auth, role:employee` | `App\Features\Leave\Http\Controllers\Employee\LeaveController@destroy` |
| `DELETE` | `/employee/leave-attachments/{id}` | `employee.leave-attachments.destroy` | `web, auth, role:employee` | `App\Features\Leave\Http\Controllers\Employee\LeaveController@destroyAttachment` |
| `GET|HEAD` | `/employee/notifications` | `employee.notifications` | `web, auth, role:employee` | `App\Features\Notifications\Http\Controllers\NotificationController@index` |
| `GET|HEAD` | `/employee/notifications/unread-count` | `employee.notifications.unread-count` | `web, auth, role:employee` | `App\Features\Notifications\Http\Controllers\NotificationController@unreadCount` |
| `POST` | `/employee/notifications/{noticeId}/mark-as-read` | `employee.notifications.mark-as-read` | `web, auth, role:employee` | `App\Features\Notifications\Http\Controllers\NotificationController@markAsRead` |
| `GET|HEAD` | `/employee/pds` | `employee.pds.index` | `web, auth, role:employee` | `App\Features\Pds\Http\Controllers\Employee\PdsController@index` |
| `POST` | `/employee/pds` | `employee.pds.store` | `web, auth, role:employee` | `App\Features\Pds\Http\Controllers\Employee\PdsController@store` |
| `GET|HEAD` | `/employee/pds/preview` | `employee.pds.preview` | `web, auth, role:employee` | `App\Features\Pds\Http\Controllers\Employee\PdsController@preview` |
| `GET|HEAD` | `/employee/profile` | `employee.profile` | `web, auth, role:employee` | `App\Features\Auth\Http\Controllers\ProfileController@show` |
| `PUT|PATCH` | `/employee/profile` | `employee.profile.update` | `web, auth, role:employee` | `App\Features\Auth\Http\Controllers\ProfileController@update` |
| `DELETE` | `/employee/profile` | `employee.profile.delete` | `web, auth, role:employee` | `App\Features\Auth\Http\Controllers\ProfileController@destroy` |
| `POST` | `/employee/profile/password` | `employee.profile.password` | `web, auth, role:employee` | `App\Features\Auth\Http\Controllers\ProfileController@changePassword` |
| `GET|HEAD` | `/employee/settings` | `employee.settings` | `web, auth, role:employee` | `*(Closure)*` |
| `GET|HEAD` | `/employee/training` | `employee.training.index` | `web, auth, role:employee` | `App\Features\Training\Http\Controllers\Employee\TrainingController@index` |
| `POST` | `/employee/training` | `employee.training.store` | `web, auth, role:employee` | `App\Features\Training\Http\Controllers\Employee\TrainingController@store` |
| `DELETE` | `/employee/training/attachment/{id}` | `employee.training.attachment.delete` | `web, auth, role:employee` | `App\Features\Training\Http\Controllers\Employee\TrainingController@deleteAttachment` |
| `GET|HEAD` | `/employee/training/export` | `employee.training.export` | `web, auth, role:employee` | `App\Features\Training\Http\Controllers\Employee\TrainingController@export` |
| `PUT` | `/employee/training/{id}` | `employee.training.update` | `web, auth, role:employee` | `App\Features\Training\Http\Controllers\Employee\TrainingController@update` |
| `DELETE` | `/employee/training/{id}` | `employee.training.destroy` | `web, auth, role:employee` | `App\Features\Training\Http\Controllers\Employee\TrainingController@destroy` |

## Application Settings

| Methods | URI | Name | Middleware | Action |
|---------|-----|------|------------|--------|
| `GET|HEAD` | `/settings/appearance` | `appearance.edit` | `web, auth, verified` | `*(Closure)*` |
| `GET|HEAD` | `/settings/password` | `user-password.edit` | `web, auth, verified` | `App\Http\Controllers\Settings\PasswordController@edit` |
| `PUT` | `/settings/password` | `user-password.update` | `web, auth, verified, throttle:6,1` | `App\Http\Controllers\Settings\PasswordController@update` |
| `GET|HEAD` | `/settings/profile` | `profile.edit` | `web, auth` | `App\Http\Controllers\Settings\ProfileController@edit` |
| `PATCH` | `/settings/profile` | `profile.update` | `web, auth` | `App\Http\Controllers\Settings\ProfileController@update` |
| `DELETE` | `/settings/profile` | `profile.destroy` | `web, auth, verified` | `App\Http\Controllers\Settings\ProfileController@destroy` |
| `GET|HEAD` | `/settings/two-factor` | `two-factor.show` | `web, auth, verified, password.confirm` | `App\Http\Controllers\Settings\TwoFactorAuthenticationController@show` |

## User Auth/Fortify

| Methods | URI | Name | Middleware | Action |
|---------|-----|------|------------|--------|
| `GET|HEAD` | `/user/confirm-password` | `password.confirm` | `web, auth:web` | `Laravel\Fortify\Http\Controllers\ConfirmablePasswordController@show` |
| `POST` | `/user/confirm-password` | `password.confirm.store` | `web, auth:web` | `Laravel\Fortify\Http\Controllers\ConfirmablePasswordController@store` |
| `GET|HEAD` | `/user/confirmed-password-status` | `password.confirmation` | `web, auth:web` | `Laravel\Fortify\Http\Controllers\ConfirmedPasswordStatusController@show` |
| `POST` | `/user/confirmed-two-factor-authentication` | `two-factor.confirm` | `web, auth:web, password.confirm` | `Laravel\Fortify\Http\Controllers\ConfirmedTwoFactorAuthenticationController@store` |
| `POST` | `/user/two-factor-authentication` | `two-factor.enable` | `web, auth:web, password.confirm` | `Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController@store` |
| `DELETE` | `/user/two-factor-authentication` | `two-factor.disable` | `web, auth:web, password.confirm` | `Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController@destroy` |
| `GET|HEAD` | `/user/two-factor-qr-code` | `two-factor.qr-code` | `web, auth:web, password.confirm` | `Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController@show` |
| `GET|HEAD` | `/user/two-factor-recovery-codes` | `two-factor.recovery-codes` | `web, auth:web, password.confirm` | `Laravel\Fortify\Http\Controllers\RecoveryCodeController@index` |
| `POST` | `/user/two-factor-recovery-codes` | `two-factor.regenerate-recovery-codes` | `web, auth:web, password.confirm` | `Laravel\Fortify\Http\Controllers\RecoveryCodeController@store` |
| `GET|HEAD` | `/user/two-factor-secret-key` | `two-factor.secret-key` | `web, auth:web, password.confirm` | `Laravel\Fortify\Http\Controllers\TwoFactorSecretKeyController@show` |

## Authentication & Verification

| Methods | URI | Name | Middleware | Action |
|---------|-----|------|------------|--------|
| `POST` | `/email/verification-notification` | `verification.send` | `web, auth:web, throttle:6,1` | `Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController@store` |
| `GET|HEAD` | `/email/verify` | `verification.notice` | `web, auth:web` | `Laravel\Fortify\Http\Controllers\EmailVerificationPromptController@__invoke` |
| `GET|HEAD` | `/email/verify/{id}/{hash}` | `verification.verify` | `web, auth:web, signed, throttle:6,1` | `Laravel\Fortify\Http\Controllers\VerifyEmailController@__invoke` |
| `GET|HEAD` | `/forgot-password` | `password.request` | `web, guest:web` | `Laravel\Fortify\Http\Controllers\PasswordResetLinkController@create` |
| `POST` | `/forgot-password` | `password.email` | `web, guest:web, throttle:password-reset` | `Laravel\Fortify\Http\Controllers\PasswordResetLinkController@store` |
| `GET|HEAD` | `/login` | `login` | `web, guest:web` | `Laravel\Fortify\Http\Controllers\AuthenticatedSessionController@create` |
| `POST` | `/login` | `login.store` | `web, guest:web, throttle:login` | `Laravel\Fortify\Http\Controllers\AuthenticatedSessionController@store` |
| `POST` | `/logout` | `logout` | `web, auth:web` | `Laravel\Fortify\Http\Controllers\AuthenticatedSessionController@destroy` |
| `GET|HEAD` | `/register` | `register` | `web, guest` | `App\Http\Controllers\Auth\RegisterController@create` |
| `POST` | `/register` | `register.store` | `web, guest` | `App\Http\Controllers\Auth\RegisterController@store` |
| `POST` | `/reset-password` | `password.update` | `web, guest:web, throttle:password-reset` | `Laravel\Fortify\Http\Controllers\NewPasswordController@store` |
| `GET|HEAD` | `/reset-password/{token}` | `password.reset` | `web, guest:web` | `Laravel\Fortify\Http\Controllers\NewPasswordController@create` |
| `GET|HEAD` | `/two-factor-challenge` | `two-factor.login` | `web, guest:web` | `Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController@create` |
| `POST` | `/two-factor-challenge` | `two-factor.login.store` | `web, guest:web, throttle:two-factor` | `Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController@store` |

## Other Base Routes

| Methods | URI | Name | Middleware | Action |
|---------|-----|------|------------|--------|
| `POST` | `/_boost/browser-logs` | `boost.browser-logs` | `` | `*(Closure)*` |
| `GET|POST|HEAD` | `/broadcasting/auth` | `-` | `web` | `Illuminate\Broadcasting\BroadcastController@authenticate` |
| `POST` | `/email/verification-notification` | `verification.send` | `web, auth:web, throttle:6,1` | `Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController@store` |
| `GET|HEAD` | `/email/verify` | `verification.notice` | `web, auth:web` | `Laravel\Fortify\Http\Controllers\EmailVerificationPromptController@__invoke` |
| `GET|HEAD` | `/email/verify/{id}/{hash}` | `verification.verify` | `web, auth:web, signed, throttle:6,1` | `Laravel\Fortify\Http\Controllers\VerifyEmailController@__invoke` |
| `GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS` | `/settings` | `-` | `web, auth` | `Illuminate\Routing\RedirectController` |
| `GET|HEAD` | `/storage/{path}` | `storage.local` | `` | `*(Closure)*` |
| `PUT` | `/storage/{path}` | `storage.local.upload` | `` | `*(Closure)*` |

## Core System & Boot Routes

| Methods | URI | Name | Middleware | Action |
|---------|-----|------|------------|--------|
| `GET|HEAD` | `//` | `home` | `web` | `*(Closure)*` |
| `GET|HEAD` | `/dashboard` | `dashboard` | `web, auth, verified` | `*(Closure)*` |
| `GET|HEAD` | `/up` | `-` | `` | `*(Closure)*` |

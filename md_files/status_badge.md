# Status Badges Inventory

The following list maps all the Vue components where a `<Badge>` component is used to display a status (e.g., active/inactive, leave status, training status, notice types), highlighting their context and styling variants.

## Administration Module
- `resources/js/pages/Admin/Users/Index.vue`
  - User Status (`getStatusVariant(u)` or `viewingUser.is_active`)
  - User Role (`variant="outline"`)
- `resources/js/pages/Admin/Notices/Index.vue`
  - Notice Active Status (`is_active ? 'default' : 'secondary'`)
  - Notice Type (`typeVariant(notice.type)`)
- `resources/js/pages/Admin/Profile/Index.vue`
  - User Active Status (Custom green styles using `variant="secondary"`)
- `resources/js/pages/Admin/Notifications/Index.vue`
  - "New" Notification Status (`variant="default"`)
  - Notification Type (`typeVariant(n.type)`)
- `resources/js/pages/Admin/Calendar/Index.vue`
  - Event Category (`variant="secondary"`)
  - Event Status (`variant="outline"`)
- `resources/js/pages/Admin/ActivityLogs/Index.vue`
  - Log Action (`variant="outline"`)

## HR Module
- `resources/js/pages/HR/Users/Index.vue`
  - User Status (`getStatusVariant(u)`)
  - User Role (`variant="outline"`)
- `resources/js/pages/HR/Training/Index.vue`
  - Training Status (`statusVariant(t.status)`)
- `resources/js/pages/HR/PDS/Index.vue` (and `Preview.vue`)
  - PDS Submission Status (`statusVariant(item.status)`)
- `resources/js/pages/HR/Notices/Index.vue`
  - Notice Active Status (`is_active ? 'default' : 'secondary'`)
  - Notice Type (`typeVariant(n.type)`)
- `resources/js/pages/HR/Leave/Index.vue`
  - Leave Application Status (`statusVariant(app.status)`)
- `resources/js/pages/HR/Profile/Index.vue`
  - User Active Status (Custom green styles using `variant="secondary"`)
- `resources/js/pages/HR/Notifications/Index.vue`
  - "New" Notification Status (`variant="default"`)
  - Notification Type (`typeVariant(n.type)`)
- `resources/js/pages/HR/Calendar/Index.vue`
  - Event Category (`variant="secondary"`)
  - Event Status (`variant="outline"`)

## Employee Module
- `resources/js/pages/Employee/Training/Index.vue`
  - Training Status (`statusVariant(t.status)`)
- `resources/js/pages/Employee/PDS/Index.vue`
  - PDS Submission Status (`statusVariant(status)`)
- `resources/js/pages/Employee/Leave/Index.vue`
  - Leave Application Status (`statusVariant(app.status)`)
- `resources/js/pages/Employee/Profile/Index.vue`
  - User Active Status (Custom green styles using `variant="secondary"`)
- `resources/js/pages/Employee/Notifications/Index.vue`
  - "New" Notification Status (`variant="default"`)
  - Notification Type (`typeVariant(n.type)`)
- `resources/js/pages/Employee/Calendar/Index.vue`
  - Event Category (`variant="secondary"`)
  - Event Status (`variant="outline"`)

## Global / Settings Module
- `resources/js/pages/settings/TwoFactor.vue`
  - 2FA Status (`variant="destructive"` for Disabled, `variant="default"` for Enabled)
- `resources/js/pages/Notifications/Index.vue`
  - "New" Notification Status (`variant="default"`)
  - Notification Type (`typeVariant(n.type)`)

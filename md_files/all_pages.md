# System Pages Catalog

This document is a comprehensive inventory of all page components (`.vue` files within `resources/js/pages/`), categorized by the roles and modules they belong to. 

## 1. Admin Module
Pages accessible to Administrators for global system management:
- `Admin/Dashboard.vue` (Admin Dashboard)
- `Admin/ActivityLogs/Index.vue` (System Activity Logs)
- `Admin/Backup/Index.vue` (Database & System Backups)
- `Admin/Calendar/Index.vue` (Global Calendar & Holidays)
- `Admin/Leave/Index.vue` (All Leave Applications Overview)
- `Admin/Notices/Index.vue`
- `Admin/Notices/Create.vue`
- `Admin/Notices/Edit.vue`
- `Admin/Notifications/Index.vue`
- `Admin/PDS/Index.vue` (Global PDS Records)
- `Admin/Performance/Index.vue`
- `Admin/Profile/Index.vue` (Admin's User Profile)
- `Admin/Reports/Index.vue` (System Reports)
- `Admin/Settings/Index.vue` (Global System Settings)
- `Admin/Users/Index.vue` (User Management)

## 2. HR Module
Pages accessible to HR personnel for employee and personnel management:
- `HR/Dashboard.vue` (HR Dashboard)
- `HR/Calendar/Index.vue`
- `HR/Leave/Index.vue` (Leave Management)
- `HR/LeaveCredits/Index.vue` (Employee Leave Credits)
- `HR/LeaveCredits/Show.vue` (Individual Leave Credit Ledger)
- `HR/Notices/Index.vue`
- `HR/Notices/Create.vue`
- `HR/Notices/Edit.vue`
- `HR/Notifications/Index.vue`
- `HR/PDS/Index.vue` (Employee PDS Management)
- `HR/PDS/Preview.vue` (PDS Details Review)
- `HR/Profile/Index.vue` (HR's User Profile)
- `HR/Reports/Index.vue` (HR Specific Reports)
- `HR/Settings/Index.vue` (HR Settings)
- `HR/Training/Index.vue` (Training Management)
- `HR/Users/Index.vue` (Employee Accounts View)

## 3. Employee Module
Pages accessible to standard Employees for personal self-service:
- `Employee/Dashboard.vue` (Employee Dashboard)
- `Employee/Calendar/Index.vue` (Personal Calendar View)
- `Employee/Leave/Index.vue` (My Leave Applications)
- `Employee/Notifications/Index.vue`
- `Employee/PDS/Index.vue` (My Personal Data Sheet)
- `Employee/PDS/Preview.vue` (My PDS Preview/Export)
- `Employee/Profile/Index.vue` (My Account Profile)
- `Employee/Settings/Index.vue` (My Account Settings)
- `Employee/Training/Index.vue` (My Training Records)

## 4. Authentication Pages
Pages handling user sessions, registration, and security:
- `auth/Login.vue`
- `auth/Register.vue`
- `auth/ForgotPassword.vue`
- `auth/ResetPassword.vue`
- `auth/ConfirmPassword.vue`
- `auth/TwoFactorChallenge.vue`
- `auth/VerifyEmail.vue`

## 5. Global / Shared Pages
General pages and shared account settings:
- `Welcome.vue` (Landing / Splash Page)
- `Dashboard.vue` (Default Fallback Dashboard)
- `Notifications/Index.vue` (Shared generic notification view)
- `settings/Appearance.vue` (Theme Settings)
- `settings/Password.vue` (Password Change Modal Component)
- `settings/Profile.vue` (Profile Update Modal Component)
- `settings/TwoFactor.vue` (2FA Setup Component)

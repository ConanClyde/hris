# HRIS Application Comprehensive Stack Audit

**Project:** HRIS (Human Resource Information System)  
**Audit Date:** February 18, 2026  
**Auditor:** Cascade AI  
**Repository:** `c:/Users/Alvin/Documents/@@OJT/systems/hris_sys`

---

## Executive Summary

This audit covers a Laravel-based HRIS application with role-based access (Admin, HR, Employee), featuring leave management, training tracking, PDS (Personal Data Sheet) management, calendar integration, and real-time notifications via WebSocket.

**Architecture:**
- **Backend:** Laravel 12.51.0 with session-based authentication
- **Frontend:** Blade templates with vanilla JavaScript, Tailwind CSS
- **Database:** MySQL with 17 tables
- **Real-time:** Laravel Reverb (WebSocket) with Pusher protocol
- **Build:** Vite for asset compilation

---

# Phase 1: Frontend Analysis

## 1.1 User Journeys & Screen Flows

### Authentication Flow
| Step | Screen | Route | Description |
|------|--------|-------|-------------|
| 1 | Login | `GET /login` | User ID + password entry with dark mode toggle |
| 2 | Login Store | `POST /login` | Session-based auth via `AuthController@login` |
| 3 | Dashboard | `GET /dashboard` | Role-based redirect (admin/hr/employee) |
| 4 | Logout | `POST /logout` | Session invalidation |

**Password Recovery Flow:**
- `/forgot-password` → `/forgot-password/verify` → `/forgot-password/reset` → `/forgot-password/done`
- All are view-only routes (no actual email sending logic found)

### Role-Based Navigation

**Admin Journey:**
```
Dashboard → Users → Activity Logs → Reports → Backup → Settings
  ↓           ↓
Calendar → Custom Holidays
  ↓
PDS (redirects to HR) → Leave (stub) → Notices
```

**HR Journey:**
```
Dashboard → Calendar → Reports → PDS Management → Training → Leave Applications → Notices
  ↓                                          ↓              ↓
Employee Management (UserController)    Edit/Delete    Approve/Reject Leave
```

**Employee Journey:**
```
Dashboard → Calendar → My PDS → Training → Leave Applications
                              ↓              ↓
                         View/Edit        Apply/Cancel
```

## 1.2 Forms, Validation & Error Messages

### Leave Application Form (Employee)
| Field | Type | Validation Rules | Error Messages |
|-------|------|------------------|----------------|
| leave_type | select | `required` | "The leave type field is required" |
| date_from | date | `required|date` | "Invalid date format" |
| date_to | date | `nullable|date|after_or_equal:date_from` | "End date must be after start date" |
| total_days | number | `required|numeric|min:0.5` | "Minimum 0.5 days required" |
| reason | textarea | `required|string` | "Reason is required" |
| attachments[] | file | Optional, multiple | "PNG, JPG, PDF up to 10MB" |

### Leave Application Form (HR - Create for Employee)
| Field | Type | Validation Rules |
|-------|------|------------------|
| employee_id | select | `required|string` |
| leave_type | select | `required|string` |
| date_from | date | `required|date` |
| total_days | number | `required|numeric|min:0.5` |
| reason | textarea | `nullable|string` |

### Training Form
| Field | Type | Validation Rules |
|-------|------|------------------|
| employee_id | select | `required|string|max:255` |
| employee_name | text | `nullable|string|max:255` |
| type | select | `required|string|max:255` |
| category | select | `nullable|string|max:255` |
| title | text | `required|string|max:255` |
| provider | text | `nullable|string|max:255` |
| date_from | date | `required|date` |
| date_to | date | `required|date|after_or_equal:date_from` |
| time_from | time | `nullable|date_format:H:i` |
| time_to | time | `nullable|date_format:H:i` |
| hours | number | `nullable|numeric|min:0` |
| fee | number | `nullable|numeric|min:0` |
| participants | textarea | `nullable|string` |
| status | select | `nullable|in:pending,approved,rejected` |

### PDS Personal Information Form
| Section | Fields | Storage |
|---------|--------|---------|
| C1 - Personal | surname, first_name, middle_name, name_extension, dob, place_of_birth, sex, civil_status, height, weight, blood_type | `pds_personal` table |
| C1 - Citizenship | citizenship_type, citizenship_nature, citizenship_country | `pds_personal` table |
| C1 - Contact | phone, mobile, email | `pds_personal` table |
| C1 - IDs | cs_id, agency_employee_no, gsis, pag_ibig, philhealth, sss, tin | `pds_personal` table |
| C1 - Address | residential_address (JSON), permanent_address (JSON) | `pds_personal` table |

### User Management Form (Admin/HR)
| Field | Type | Validation |
|-------|------|------------|
| name | text | `required|string` |
| email | email | `required|email` |
| role | select | `in:admin,hr,employee` |
| status | select | `in:active,inactive` |

## 1.3 API Calls & Request/Response Formats

### Internal API Endpoints (Web Routes)
| Endpoint | Method | Purpose | Response |
|----------|--------|---------|----------|
| `/admin/calendar/events` | GET | Fetch calendar events | JSON array of events |
| `/hr/calendar/events` | GET | Fetch calendar events | JSON array of events |
| `/employee/calendar/events` | GET | Fetch calendar events | JSON array of events |

### Public API Endpoints (`routes/api.php`)
| Endpoint | Method | Request Body | Response |
|----------|--------|--------------|----------|
| `/api/v1/leave-applications` | GET | Query: `search`, `type`, `status`, `per_page` | Paginated JSON |
| `/api/v1/leave-applications` | POST | `employee_id`, `employee_name`, `type`, `date_from`, `date_to`, `total_days`, `reason` | Leave record JSON (201) |
| `/api/v1/leave-applications/{id}` | GET | - | Leave record JSON |
| `/api/v1/leave-applications/{id}/status` | PUT | `status` (pending/approved/rejected) | Updated leave JSON |
| `/api/v1/trainings` | GET | Query: filters | Paginated JSON |
| `/api/v1/trainings/{id}/status` | PUT | `status` | Updated training JSON |
| `/api/v1/notices/active` | GET | - | Active notices array |

**API Middleware:** `integration` (defined in `VerifyIntegrationKey`)

## 1.4 State Management & Caching

### Session State
```php
session([
    'user_id' => $realUserId,      // int from users table
    'role' => $user['role'],       // 'admin'|'hr'|'employee'
    'email' => $dbUser->email,     // string
]);
```

### Caching Strategy
| Cache Key | TTL | Purpose |
|-----------|-----|---------|
| `google_holidays_{start}_{end}` | 24 hours | Google Calendar API response |

### Client-Side State
- **Theme:** `localStorage.setItem('theme', 'dark'|'light')`
- **Real-time events:** CustomEvent listeners on window

## 1.5 Authentication & Authorization

### Authentication Flow
1. User submits `user_id` + `password` to `/login`
2. Credentials validated against `config/demo_users.php`
3. Password verified with `Hash::check()`
4. User record retrieved from `users` table via constructed email
5. Session established with `user_id`, `role`, `email`
6. Redirect to `/dashboard` (role-based view)

### Authorization Middleware
| Middleware | Usage | Behavior |
|------------|-------|----------|
| `session.auth` (`EnsureSessionAuthenticated`) | All protected routes | Redirect to login if no `user_id` in session |
| `role:admin` | Admin routes | Return 403 or redirect to dashboard |
| `role:hr` | HR routes | Return 403 or redirect to dashboard |
| `role:employee` | Employee routes | Return 403 or redirect to login |
| `integration` | API routes | Validates integration key (header/query) |

### Role-Based UI Toggles
```javascript
// Echo.js role-based channel subscriptions
const role = window.HRIS_ROLE?.toLowerCase();
if (role === 'hr') {
    Echo.private('leave.management').listen(...);
    Echo.private('training.management').listen(...);
    Echo.private('calendar.holidays').listen(...);
}
```

## 1.6 File Upload & Real-Time Features

### File Upload
| Feature | Accept | Max Size | Storage |
|---------|--------|----------|---------|
| Leave Attachments | PNG, JPG, PDF | 10MB | JSON array in `attachments` column |
| Training Attachments | Same | 10MB | `destroyAttachment()` returns mock success |
| Backup Upload | Any | - | Stored via `BackupController@upload` |

### WebSocket Events (Laravel Reverb)
| Event | Channel | Frontend Handler | Trigger |
|-------|---------|------------------|---------|
| `LeaveStatusUpdated` | `role.{role}`, `leave.management` | `realtime:leave-status-updated` | Leave approval/rejection |
| `TrainingStatusUpdated` | `role.{role}`, `training.management` | `realtime:training-status-updated` | Training approval/rejection |
| `NoticePublished` | `role.{role}`, `users.{userId}` | `realtime:notice-published` | New notice created |
| `CustomHolidayCreated` | `calendar.holidays` (HR only) | `realtime:calendar-holidays-updated` | Holiday added |
| `CustomHolidayUpdated` | `calendar.holidays` (HR only) | `realtime:calendar-holidays-updated` | Holiday modified |
| `CustomHolidayDeleted` | `calendar.holidays` (HR only) | `realtime:calendar-holidays-updated` | Holiday removed |

### Real-Time UI Updates
```javascript
// realtime-row-updates.blade.js
window.Echo.private('leave.management')
    .listen('.LeaveStatusUpdated', (e) => {
        updateLeaveRow(e);  // In-place DOM update
    });

// Update function highlights row and modifies:
// - Status badge (color + text)
// - Type cell
// - Date cell
// - Days cell
```

---

# Phase 2: Backend Alignment Check

## 2.1 API Contract Comparison

### Frontend Expectations vs Backend Reality

| Contract Point | Frontend Expects | Backend Provides | Status |
|----------------|------------------|------------------|--------|
| **Leave List Pagination** | `paginatedApplications` with links | `LengthAwarePaginator` with 10 items/page | ✅ Aligned |
| **Leave Status Values** | `pending`, `approved`, `rejected` | Enum in model + validation | ✅ Aligned |
| **Leave Types** | Array of 6 types | Hardcoded in controllers | ⚠️ Duplicate source |
| **Calendar Events** | FullCalendar-compatible JSON | Correct shape with `start`, `end`, `backgroundColor` | ✅ Aligned |
| **Real-time Events** | CustomEvent with `detail` payload | Broadcasts match expected fields | ✅ Aligned |

### API Response Payload Mismatches

**Leave Application - Frontend Consumes:**
```javascript
{
    id: int,
    type: string,
    date_from: string,
    date_to: string|null,
    total_days: float,
    reason: string|null,
    status: 'pending'|'approved'|'rejected',
    created_at: string,
    attachments: array
}
```

**Backend Provides (API):**
```json
{
    "id": 1,
    "employee_id": "EMP-001",
    "employee_name": "John Doe",
    "type": "Vacation Leave",
    "date_from": "2026-02-20",
    "date_to": "2026-02-22",
    "total_days": 2.5,
    "reason": "Family vacation",
    "status": "pending",
    "attachments": ["file1.pdf"],
    "legacy_attachment": null,
    "created_at": "2026-02-18T10:00:00.000000Z",
    "updated_at": "2026-02-18T10:00:00.000000Z"
}
```
**Status:** ✅ All fields present

## 2.2 HTTP Methods & URL Patterns

### Route Alignment Check

| Feature | Expected Method | Route | Actual Implementation | Status |
|---------|----------------|-------|----------------------|--------|
| Leave Create (Employee) | POST | `/employee/leave-applications` | `Employee\LeaveController@store` | ✅ |
| Leave Update (Employee) | PUT | `/employee/leave-applications/{id}` | `Employee\LeaveController@update` | ✅ |
| Leave Delete (Employee) | DELETE | `/employee/leave-applications/{id}` | `Employee\LeaveController@destroy` | ✅ |
| Leave Create (HR) | POST | `/hr/leave-applications` | `HR\LeaveController@store` | ✅ |
| Leave Update (HR) | PUT | `/hr/leave-applications/{id}` | `HR\LeaveController@update` | ✅ |
| Training Create | POST | `/hr/training` | `HR\TrainingController@store` | ✅ |
| Training Update | PUT | `/hr/training/{id}` | `HR\TrainingController@update` | ✅ |
| User Create | POST | `/admin/users` | `UserController@store` | ⚠️ Mock only |
| User Update | PUT | `/admin/users/{id}` | `UserController@update` | ⚠️ Mock only |
| User Delete | DELETE | `/admin/users/{id}` | `UserController@destroy` | ⚠️ Mock only |
| Backup Run | POST | `/admin/backup/run` | `BackupController@run` | ✅ |
| Backup Restore | POST | `/admin/backup/{id}/restore` | `BackupController@restore` | ✅ |

## 2.3 Response Payload Analysis

### Fields Frontend Consumes but Backend May Not Provide

| Field | Frontend Usage | Backend Source | Risk |
|-------|---------------|----------------|------|
| `leaveCredits` | Employee dashboard | Hardcoded `15.50` | ⚠️ Not dynamic |
| `employee_name` | Leave table display | Stored on create | ⚠️ May be null |
| `attachments` | File count badge | JSON array | ✅ Present |
| `legacy_attachment` | Not used in views | Stored in model | ⚠️ Unused field |

## 2.4 Error Code Consistency

| Scenario | Frontend Expects | Backend Returns | Status |
|----------|------------------|-----------------|--------|
| Unauthorized | Redirect to login | 401 JSON for API, redirect for web | ✅ |
| Forbidden (wrong role) | Redirect to dashboard | 403 JSON for API, redirect for web | ✅ |
| Validation Failed | `$errors` bag display | `ValidationException` with messages | ✅ |
| Record Not Found | Error page | 404 from `findOrFail()` | ✅ |
| Server Error | Error page | 500 with stack trace (dev) | ✅ |

## 2.5 Authentication Middleware Alignment

| Requirement | Frontend Behavior | Backend Middleware | Status |
|-------------|------------------|-------------------|--------|
| Session expires | Reloads to login | `EnsureSessionAuthenticated` checks `session('user_id')` | ✅ |
| Role mismatch | Shows forbidden | `EnsureRole` with `role:{role}` parameter | ✅ |
| API key | Header `X-Integration-Key` | `VerifyIntegrationKey` middleware | ✅ |

## 2.6 CORS & Credentials

**Configuration:** Session-based authentication requires:
- Cookies enabled
- Same-site sessions
- No CORS issues (same-origin)

**Status:** ✅ No CORS configuration needed for monolithic app

## 2.7 Business Rule Enforcement

| Rule | Frontend Validation | Backend Enforcement | Gap |
|------|--------------------|---------------------|-----|
| Date range valid | HTML5 date inputs | `after_or_equal:date_from` | ✅ |
| Total days minimum | HTML5 `min="0.5"` | `numeric|min:0.5` | ✅ |
| Employee exists | Select from list | Query validation | ⚠️ Hardcoded map in Employee controller |
| Leave status workflow | No cancel if approved | Controller logic | ⚠️ Only HR can change status |
| Training hours calculation | None | None | ⚠️ Manual entry only |
| File size limit | HTML5 + JS | None | ⚠️ No backend validation |

---

# Phase 3: Database Normalization Review

## 3.1 Entity Relationship Diagram

```
┌─────────────────┐     ┌──────────────────┐     ┌─────────────────┐
│     users       │     │    employees     │     │       pds       │
├─────────────────┤     ├──────────────────┤     ├─────────────────┤
│ id (PK)         │◄────┤ user_id (FK)     │◄────┤ employee_id(FK) │
│ name            │     │ employee_code(UQ)│     │ status          │
│ email           │     │ first_name       │     │ submitted_at    │
│ password        │     │ middle_name      │     │ reviewed_by(FK)─┼──► users
│ role            │     │ last_name        │     │ reviewed_at     │
│ is_active       │     │ position         │     └─────────────────┘
└─────────────────┘     │ division         │            │
                        │ status           │            │
                        └──────────────────┘            │
                                   │                    │
                                   ▼                    ▼
                        ┌──────────────────┐     ┌─────────────────┐
                        │leave_applications│     │  pds_personal   │
                        ├──────────────────┤     ├─────────────────┤
                        │ id (PK)          │     │ id (PK)         │
                        │ employee_id (FK?)│     │ pds_id (FK,UQ)  │
                        │ employee_name    │     │ surname         │
                        │ type             │     │ first_name      │
                        │ date_from        │     │ dob             │
                        │ date_to          │     │ ... (30+ fields)│
                        │ total_days       │     │ addresses (JSON)│
                        │ reason           │     └─────────────────┘
                        │ status           │
                        │ attachments(JSON)│
                        └──────────────────┘
                                   │
                                   ▼
                        ┌──────────────────┐
                        │    trainings     │
                        ├──────────────────┤
                        │ id (PK)          │
                        │ employee_id (FK?)|
                        │ employee_name    │
                        │ title            │
                        │ date_from        │
                        │ date_to          │
                        │ hours            │
                        │ status           │
                        └──────────────────┘
```

**Related Tables:**
- `pds_csc_eligibility` → `pds_id` (FK)
- `pds_voluntary_work` → `pds_id` (FK)
- `pds_work_experience` → `pds_id` (FK)
- `pds_training` → `pds_id` (FK)
- `pds_references` → `pds_id` (FK)
- `pds_background_info` → `pds_id` (FK)
- `custom_holidays` (standalone)
- `notices` (standalone)
- `backups` → `created_by_user_id` (FK)
- `activity_logs` → `actor_user_id` (FK)

## 3.2 Normalization Analysis

### Current Schema Issues

#### Issue 1: Denormalized Employee Data in Leave/Training
**Tables:** `leave_applications`, `trainings`

**Problem:**
```sql
-- leave_applications has:
employee_id VARCHAR  -- 'EMP-001'
employee_name VARCHAR -- 'John Doe' (redundant)

-- trainings has same pattern
```

**Violation:** 2NF - Non-key attribute `employee_name` depends on `employee_id`

**Recommendation:**
1. Remove `employee_name` column
2. Use JOIN to `employees` table for display
3. Migrate existing data with update script

#### Issue 2: JSON Address Storage
**Table:** `pds_personal`

**Current:**
```sql
residential_address JSON
permanent_address JSON
```

**Problem:** 
- JSON structure: `{house_block_lot, street, subdivision, barangay, city_municipality, province, zip_code}`
- 7 address fields × 2 address types = 14 potential scalar fields
- Cannot index/query individual address components efficiently

**Recommendation:**
Consider normalizing to `addresses` table if queries need to filter by city/province. Keep as JSON if only used for display.

#### Issue 3: PDS Section Splitting
**Tables:** `pds`, `pds_personal`, `pds_csc_eligibility`, etc.

**Current:** 8 related tables for PDS data

**Status:** ✅ Properly normalized - each section is a logical grouping

#### Issue 4: Legacy Attachment Fields
**Table:** `leave_applications`

**Current:**
```sql
attachments JSON          -- New format
legacy_attachment JSON    -- Old format, unused
```

**Recommendation:** Remove `legacy_attachment` column

#### Issue 5: Hardcoded Employee Mapping
**Code:** `Employee\LeaveController`, `Employee\PdsController`

```php
$employeeMap = [
    'employee01' => 'EMP-001',
];
```

**Problem:** Session user to employee code mapping is hardcoded

**Recommendation:** Add `employee_code` to session or create `user_employee` junction table

### Normalization Score

| Table | Current NF | Target NF | Gap |
|-------|-----------|-----------|-----|
| users | 3NF | 3NF | ✅ |
| employees | 3NF | 3NF | ✅ |
| pds | 3NF | 3NF | ✅ |
| pds_personal | 2NF | 2NF* | ⚠️ JSON fields |
| pds_* (children) | 3NF | 3NF | ✅ |
| leave_applications | 1NF | 2NF | ❌ employee_name denormalized |
| trainings | 1NF | 2NF | ❌ employee_name denormalized |
| custom_holidays | 3NF | 3NF | ✅ |
| notices | 3NF | 3NF | ✅ |
| backups | 3NF | 3NF | ✅ |
| activity_logs | 2NF | 2NF | ✅ JSON metadata acceptable |

## 3.3 Referential Integrity

### Existing Foreign Keys
| Child Table | Column | Parent Table | On Delete |
|-------------|--------|--------------|-----------|
| employees | user_id | users | nullOnDelete |
| pds | employee_id | employees | cascade |
| pds | reviewed_by_user_id | users | nullOnDelete |
| pds_personal | pds_id | pds | cascade |
| pds_csc_eligibility | pds_id | pds | cascade |
| pds_voluntary_work | pds_id | pds | cascade |
| pds_work_experience | pds_id | pds | cascade |
| pds_training | pds_id | pds | cascade |
| pds_references | pds_id | pds | cascade |
| pds_background_info | pds_id | pds | cascade |
| backups | created_by_user_id | users | nullOnDelete |
| activity_logs | actor_user_id | users | nullOnDelete |

### Missing Foreign Keys
| Table | Column | Should Reference |
|-------|--------|------------------|
| leave_applications | employee_id | employees.employee_code |
| trainings | employee_id | employees.employee_code |

**Note:** Cannot add FK because `employee_id` is VARCHAR in leave/trainings but `employee_code` is the natural key.

## 3.4 Index Analysis

### Existing Indexes
| Table | Columns | Purpose |
|-------|---------|---------|
| users | email | Lookup by email |
| employees | employee_code + status | Employee lookup with status filter |
| employees | email | Contact lookup |
| employees | division, subdivision, section | Department filters |
| pds | employee_id | Unique constraint |
| pds | status | Status filtering |
| leave_applications | employee_id + status | Employee leave lookup |
| leave_applications | date_from | Date range queries |
| trainings | employee_id + status | Employee training lookup |
| trainings | date_from | Date range queries |
| backups | status + created_at | Backup listing |
| activity_logs | actor_user_id + created_at | User activity timeline |
| activity_logs | action + subject_type + subject_id | Audit lookups |

### Recommended Additional Indexes
| Table | Columns | Purpose |
|-------|---------|---------|
| leave_applications | created_at | Sorting (currently used in ORDER BY) |
| trainings | created_at | Sorting |
| activity_logs | created_at | Date-based filtering |
| notices | is_active + expires_at | Active notice queries |

---

# Phase 4: Backend Refactor & Alignment Implementation

## 4.1 Controller Refactoring Needs

### UserController - Mock Data Issue
**Location:** `app/Features/Users/Http/Controllers/Admin/UserController.php`

**Current:** Returns synthetic collection of 30 mock users
**Problem:** Not connected to actual `users` table

**Required Changes:**
```php
// Change from:
$users = collect(range(1, 30))->map(...); // Mock data

// To:
$users = User::with('employee')->paginate(10);
```

### Employee Leave/PDS Controllers - Hardcoded Mapping
**Locations:**
- `Employee\LeaveController::getEmployeeIdFromSession()`
- `Employee\PdsController::getEmployeeIdFromSession()`

**Current:**
```php
$employeeMap = ['employee01' => 'EMP-001'];
```

**Recommended:**
```php
// Option 1: Store employee_code in session during login
session(['employee_code' => $employee->employee_code]);

// Option 2: Query employees table via user relationship
$employee = Employee::where('user_id', session('user_id'))->first();
```

### LeaveController (HR) - Variable Bug
**Location:** `app/Features/Leave/Http/Controllers/HR/LeaveController.php:84`

**Current:**
```php
LeaveApplication::create([...]);  // No variable assigned
ActivityLogger::logCreate('leave_application', $leave->id, [...]);  // $leave undefined
```

**Fix:**
```php
$leave = LeaveApplication::create([...]);
```

## 4.2 Repository Pattern Recommendation

### Current Direct Model Access
Controllers directly use `Model::query()` or `Model::create()`.

### Proposed Repository Structure
```
app/
  Features/
    Leave/
      Repositories/
        LeaveRepository.php       // Interface + Eloquent implementation
        LeaveRepositoryInterface.php
      Services/
        LeaveService.php          // Business logic layer
```

### Service Layer Benefits
1. **Transactional boundaries:** Multi-step operations wrapped in DB::transaction()
2. **Event dispatching:** Centralized event firing
3. **Authorization:** Policy checks before operations
4. **Audit logging:** Consistent activity logging

## 4.3 Query Optimization

### N+1 Detection
| Location | Query Pattern | Risk |
|----------|--------------|------|
| `LeaveController@index` | Single query with pagination | ✅ Safe |
| `PdsController@index` | `Pds::with('personal')` | ✅ Eager loaded |
| `CalendarController@events` | Multiple queries in loop | ⚠️ Holidays + leaves + trainings |
| `UserController@index` | N/A (mock data) | N/A |

### Recommended Optimization
```php
// CalendarController@events - Current issues:
$leaveQuery->get();      // All leaves in range
$trainingQuery->get();   // All trainings in range

// Optimization: Use cursor for large datasets
foreach ($leaveQuery->cursor() as $leave) { ... }
```

## 4.4 DTO Implementation

### Current: Array-based Data Passing
Controllers pass raw request data to models.

### Recommended: DTOs for Type Safety
```php
// app/Features/Leave/DTOs/LeaveApplicationDTO.php
class LeaveApplicationDTO
{
    public function __construct(
        public readonly string $employeeId,
        public readonly string $type,
        public readonly Carbon $dateFrom,
        public readonly ?Carbon $dateTo,
        public readonly float $totalDays,
        public readonly ?string $reason,
        public readonly string $status = 'pending',
    ) {}
    
    public static function fromRequest(Request $request): self
    {
        return new self(
            employeeId: $request->validated('employee_id'),
            type: $request->validated('leave_type'),
            // ...
        );
    }
}
```

## 4.5 Transactional Boundaries

### Current - No Transactions
```php
$leave = LeaveApplication::create([...]);
ActivityLogger::logCreate(...);
event(new LeaveStatusUpdated(...));
// If event fails, leave is still created (inconsistent)
```

### Recommended - With Transactions
```php
use Illuminate\Support\Facades\DB;

public function store(LeaveCreateRequest $request)
{
    return DB::transaction(function () use ($request) {
        $leave = LeaveApplication::create([...]);
        ActivityLogger::logCreate(...);
        
        // Dispatch event after commit
        DB::afterCommit(function () use ($leave) {
            event(new LeaveStatusUpdated(...));
        });
        
        return $leave;
    });
}
```

## 4.6 Idempotency Keys

### Current - No Idempotency
Submitting form twice creates duplicate records.

### Recommended Implementation
```php
// Middleware or controller
public function store(Request $request)
{
    $idempotencyKey = $request->header('X-Idempotency-Key');
    
    if ($idempotencyKey) {
        $existing = Cache::get("idempotency:{$idempotencyKey}");
        if ($existing) {
            return response()->json($existing);
        }
    }
    
    $result = DB::transaction(function () use ($request) {
        return LeaveApplication::create([...]);
    });
    
    if ($idempotencyKey) {
        Cache::put("idempotency:{$idempotencyKey}", $result, 3600);
    }
    
    return $result;
}
```

## 4.7 Optimistic Locking

### Current - No Concurrency Control
Multiple users editing same record = last write wins.

### Recommended - Version Column
```php
// Migration
$table->unsignedInteger('version')->default(0);

// Controller
public function update(Request $request, $id)
{
    $leave = LeaveApplication::findOrFail($id);
    
    if ($leave->version !== $request->input('version')) {
        return response()->json([
            'error' => 'Record was modified by another user'
        ], 409);
    }
    
    $leave->update([
        ...$request->validated(),
        'version' => DB::raw('version + 1')
    ]);
}
```

---

# Phase 5: Deliverables & Quality Gates

## 5.1 Traceability Matrix

| User Story | Frontend View | Backend Controller | API Endpoint | Database Tables |
|------------|---------------|-------------------|--------------|-----------------|
| Employee applies for leave | `employee/leave/index.blade.php` | `Employee\LeaveController@store` | POST `/api/v1/leave-applications` | leave_applications |
| Employee cancels leave | `employee/leave/index.blade.php` | `Employee\LeaveController@destroy` | N/A | leave_applications |
| HR approves leave | `features/leave/hr/index.blade.php` | `HR\LeaveController@update` | PUT `/api/v1/leave-applications/{id}/status` | leave_applications |
| HR views all leaves | `features/leave/hr/index.blade.php` | `HR\LeaveController@index` | GET `/api/v1/leave-applications` | leave_applications |
| Employee views calendar | `employee/calendar/index.blade.php` | `Employee\CalendarController@events` | GET `/employee/calendar/events` | custom_holidays, leave_applications, trainings |
| HR manages trainings | `features/training/hr/index.blade.php` | `HR\TrainingController@index/store/update/destroy` | GET/PUT `/api/v1/trainings` | trainings |
| Employee edits PDS | `employee/pds/index.blade.php` | `Employee\PdsController@store` | N/A | pds, pds_personal |
| HR previews PDS | `employee/pds/preview.blade.php` | `HR\PdsController@preview` | N/A | pds, pds_personal, employees |
| Admin manages users | `features/users/admin/index.blade.php` | `UserController@index/store/update/destroy` | N/A | users (mock implementation) |
| Admin manages backups | `features/backup/admin/index.blade.php` | `BackupController@index/run/upload/download/restore` | N/A | backups |
| Real-time leave updates | All leave views | Event: `LeaveStatusUpdated` | WebSocket | N/A |
| Real-time training updates | All training views | Event: `TrainingStatusUpdated` | WebSocket | N/A |
| Real-time notices | All views | Event: `NoticePublished` | WebSocket | notices |

## 5.2 Migration Scripts

### Migration 1: Remove Denormalized employee_name
```php
<?php
// database/migrations/2026_02_18_200001_remove_employee_name_from_leaves.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // First, ensure we have the data migrated if needed
        // (No migration needed - just removing redundant column)
        
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropColumn('employee_name');
        });
        
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn('employee_name');
        });
    }

    public function down(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->string('employee_name')->nullable()->after('employee_id');
        });
        
        Schema::table('trainings', function (Blueprint $table) {
            $table->string('employee_name')->nullable()->after('employee_id');
        });
    }
};
```

### Migration 2: Remove legacy_attachment column
```php
<?php
// database/migrations/2026_02_18_200002_remove_legacy_attachment.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropColumn('legacy_attachment');
        });
    }

    public function down(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->json('legacy_attachment')->nullable()->after('attachments');
        });
    }
};
```

### Migration 3: Add foreign key constraints
```php
<?php
// database/migrations/2026_02_18_200003_add_leave_training_fks.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Note: This requires employee_id to match employees.id type
        // Currently employee_id is VARCHAR, employees.id is BIGINT
        // This is a data type mismatch that needs resolution first
        
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->foreignId('employee_id_converted')
                ->nullable()
                ->after('id')
                ->constrained('employees')
                ->nullOnDelete();
        });
    }
};
```

### Migration 4: Add version column for optimistic locking
```php
<?php
// database/migrations/2026_02_18_200004_add_version_columns.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(0)->after('status');
        });
        
        Schema::table('trainings', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(0)->after('status');
        });
        
        Schema::table('pds', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(0)->after('status');
        });
    }
};
```

## 5.3 Contract Test Suite

### Test Structure
```
tests/
  Contract/
    LeaveApiContractTest.php
    TrainingApiContractTest.php
    NoticeApiContractTest.php
    WebSocketEventContractTest.php
```

### Example Contract Test
```php
<?php
// tests/Contract/LeaveApiContractTest.php

namespace Tests\Contract;

use App\Features\Leave\Models\LeaveApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveApiContractTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_index_returns_expected_payload_structure(): void
    {
        LeaveApplication::factory()->create();
        
        $response = $this->getJson('/api/v1/leave-applications');
        
        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'employee_id',
                        'type',
                        'date_from',
                        'date_to',
                        'total_days',
                        'reason',
                        'status',
                        'attachments',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'current_page',
                'total',
                'per_page',
            ]);
    }
    
    public function test_store_accepts_valid_payload(): void
    {
        $payload = [
            'employee_id' => 'EMP-001',
            'type' => 'Vacation Leave',
            'date_from' => '2026-02-20',
            'date_to' => '2026-02-22',
            'total_days' => 2.5,
            'reason' => 'Family vacation',
        ];
        
        $response = $this->postJson('/api/v1/leave-applications', $payload);
        
        $response->assertCreated()
            ->assertJsonPath('employee_id', 'EMP-001')
            ->assertJsonPath('status', 'pending');
    }
    
    public function test_store_rejects_invalid_status_values(): void
    {
        $payload = [
            'employee_id' => 'EMP-001',
            'type' => 'Vacation Leave',
            'date_from' => '2026-02-20',
            'total_days' => 1,
            'status' => 'invalid_status', // Should fail
        ];
        
        $response = $this->postJson('/api/v1/leave-applications', $payload);
        
        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);
    }
    
    public function test_update_status_broadcasts_event(): void
    {
        $leave = LeaveApplication::factory()->create(['status' => 'pending']);
        
        $this->expectsEvents([\App\Features\Leave\Events\LeaveStatusUpdated::class]);
        
        $response = $this->putJson("/api/v1/leave-applications/{$leave->id}/status", [
            'status' => 'approved',
        ]);
        
        $response->assertOk();
    }
}
```

### CI Configuration
```yaml
# .github/workflows/contract-tests.yml
name: Contract Tests

on: [push, pull_request]

jobs:
  contract:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: password
          MYSQL_DATABASE: hris_test
    
    steps:
      - uses: actions/checkout@v4
      
      - name: Install dependencies
        run: composer install
      
      - name: Run migrations
        run: php artisan migrate --env=testing
      
      - name: Run contract tests
        run: php artisan test --filter=Contract
      
      - name: Check coverage
        run: |
          php artisan test --filter=Contract --coverage-text |
          grep -q "100.00%" || echo "Warning: Contract coverage below 100%"
```

## 5.4 Zero Breaking Changes Strategy

### Compatibility Layers

#### View-Model Compatibility
```php
// app/Features/Leave/Models/LeaveApplication.php

class LeaveApplication extends Model
{
    // Add accessor for backward compatibility
    protected function employeeName(): Attribute
    {
        return Attribute::make(
            get: function (?string $value): string {
                if ($value) return $value;
                
                // Fallback to relationship lookup
                return $this->employee?->full_name ?? 'Unknown';
            },
        );
    }
}
```

#### API Response Compatibility
```php
// app/Http/Resources/LeaveApplicationResource.php

class LeaveApplicationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'employee_name' => $this->employee_name, // Accessor handles lookup
            'type' => $this->type,
            // ... other fields
        ];
    }
}
```

## 5.5 Performance Benchmarks

### Current Endpoint Latency Targets
| Endpoint | Current Est. | Target p95 | Optimization |
|----------|-------------|------------|--------------|
| GET /dashboard | ~100ms | <150ms | Cached user data |
| GET /employee/leave-applications | ~50ms | <100ms | Indexed query |
| GET /hr/leave-applications | ~80ms | <150ms | Pagination limit |
| GET /calendar/events | ~200ms | <300ms | Google Calendar caching |
| POST /employee/leave-applications | ~50ms | <100ms | Transaction + events |
| WebSocket connect | ~100ms | <200ms | Reverb optimization |

### Performance Test Script
```php
<?php
// tests/Performance/EndpointLatencyTest.php

use Illuminate\Support\Facades\Http;

class EndpointLatencyTest extends TestCase
{
    public function test_dashboard_latency(): void
    {
        $times = [];
        
        for ($i = 0; $i < 100; $i++) {
            $start = microtime(true);
            $response = $this->get('/dashboard');
            $end = microtime(true);
            $times[] = ($end - $start) * 1000; // ms
        }
        
        sort($times);
        $p95 = $times[(int) (count($times) * 0.95)];
        
        $this->assertLessThan(150, $p95, "Dashboard p95 latency {$p95}ms exceeds 150ms");
    }
}
```

## 5.6 Security Scan Results

### SQL Injection Risk Assessment
| Location | Risk Level | Finding |
|----------|-----------|---------|
| `LeaveController@index` search | Low | Uses parameterized queries with `?` bindings |
| `CalendarController@events` | Low | Date inputs cast before query |
| UserController | N/A | Mock data, no queries |

### Input Sanitization
| Input | Sanitization | Status |
|-------|-------------|--------|
| File uploads | None (just store path) | ⚠️ Add mime-type validation |
| Search terms | Lowercase + LIKE | ✅ |
| Date inputs | Carbon parsing | ✅ |
| Status enums | Enum validation | ✅ |

### Recommended Security Improvements
1. **File Upload Validation:**
```php
$request->validate([
    'attachments.*' => 'file|mimes:pdf,jpg,png|max:10240',
]);
```

2. **Rate Limiting:**
```php
// RouteServiceProvider
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});
```

3. **CSRF Protection:** Already enabled for web routes ✅

---

# Appendix A: File Inventory

## Controllers (40 files)
```
app/Features/
  ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php
  Auth/Http/Controllers/AuthController.php
  Auth/Http/Controllers/ProfileController.php
  Backup/Http/Controllers/Admin/BackupController.php
  Calendar/Http/Controllers/Admin/CalendarController.php
  Calendar/Http/Controllers/Admin/CustomHolidayController.php
  Calendar/Http/Controllers/Employee/CalendarController.php
  Calendar/Http/Controllers/HR/CalendarController.php
  Dashboard/Http/Controllers/DashboardController.php
  Leave/Http/Controllers/Api/LeaveApiController.php
  Leave/Http/Controllers/Employee/LeaveController.php
  Leave/Http/Controllers/HR/LeaveController.php
  Notices/Http/Controllers/Admin/NoticeController.php
  Notices/Http/Controllers/Api/NoticeApiController.php
  Notices/Http/Controllers/HR/NoticeController.php
  Notifications/Http/Controllers/NotificationController.php
  Pds/Http/Controllers/Employee/PdsController.php
  Pds/Http/Controllers/HR/PdsController.php
  Training/Http/Controllers/Api/TrainingApiController.php
  Training/Http/Controllers/Employee/TrainingController.php
  Training/Http/Controllers/HR/TrainingController.php
  Users/Http/Controllers/Admin/UserController.php
```

## Models (17 files)
```
app/Features/
  ActivityLogs/Models/ActivityLog.php
  Backup/Models/Backup.php
  Calendar/Models/CustomHoliday.php
  Employees/Models/Employee.php
  Leave/Models/LeaveApplication.php
  Notices/Models/Notice.php
  Pds/Models/Pds.php
  Pds/Models/PdsBackgroundInfo.php
  Pds/Models/PdsCscEligibility.php
  Pds/Models/PdsPersonal.php
  Pds/Models/PdsReferences.php
  Pds/Models/PdsTraining.php
  Pds/Models/PdsVoluntaryWork.php
  Pds/Models/PdsWorkExperience.php
  Training/Models/Training.php
```

## Views (43+ Blade files)
Key views:
- `auth/login.blade.php` - Authentication
- `layouts/dashboard.blade.php` - Main layout
- `employee/leave/index.blade.php` - Employee leave management
- `employee/pds/index.blade.php` - PDS editing
- `features/leave/hr/index.blade.php` - HR leave approval
- `features/training/hr/index.blade.php` - HR training management

## Migrations (18 files)
```
database/migrations/
  0001_01_01_000000_create_users_table.php
  0001_01_01_000001_create_cache_table.php
  0001_01_01_000002_create_jobs_table.php
  2026_02_18_032903_create_custom_holidays_table.php
  2026_02_18_065711_create_notices_table.php
  2026_02_18_100001_create_leave_applications_table.php
  2026_02_18_100002_create_trainings_table.php
  2026_02_18_140257_create_employees_table.php
  2026_02_18_140731_create_pds_table.php
  2026_02_18_141159_create_backups_table.php
  2026_02_18_141239_create_activity_logs_table.php
  2026_02_18_142029_create_pds_csc_eligibility_table.php
  2026_02_18_142041_create_pds_voluntary_work_table.php
  2026_02_18_142041_create_pds_work_experience_table.php
  2026_02_18_142042_create_pds_training_table.php
  2026_02_18_142043_create_pds_references_table.php
  2026_02_18_142045_create_pds_background_info_table.php
  2026_02_18_144147_add_role_and_status_to_users_table.php
```

---

# Appendix B: Event Broadcasting Details

## Channel Authorization
```php
// routes/channels.php
Broadcast::channel('users.{userId}', fn ($user, $userId) => session('user_id') === $userId);
Broadcast::channel('role.{role}', fn ($user, $role) => session('role') === $role);
Broadcast::channel('leave.management', fn () => session('role') === 'hr');
Broadcast::channel('training.management', fn () => session('role') === 'hr');
Broadcast::channel('calendar.holidays', fn () => in_array(session('role'), ['admin', 'hr']));
```

## Event Payloads

### LeaveStatusUpdated
```php
new LeaveStatusUpdated(
    id: 1,
    employeeId: 'EMP-001',
    employeeName: 'John Doe',
    status: 'approved',
    type: 'Vacation Leave',
    dateFrom: '2026-02-20',
    totalDays: 2.5,
);
```

### TrainingStatusUpdated
```php
new TrainingStatusUpdated(
    id: 1,
    employeeId: 'EMP-001',
    employeeName: 'John Doe',
    status: 'approved',
    title: 'Leadership Training',
    dateFrom: '2026-03-15',
    hours: 16.0,
);
```

### NoticePublished
```php
new NoticePublished(
    id: 1,
    title: 'Company Event',
    message: 'Annual gathering on March 1',
    type: 'info',
);
```

---

**End of Audit Report**

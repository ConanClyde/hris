# HRIS API & WebSocket Documentation

## REST API Endpoints

### Authentication
All API requests require session-based authentication. The system uses Laravel's session authentication with role-based access control.

### Leave Applications API

#### List Leave Applications
```
GET /api/v1/leave-applications
```
**Query Parameters:**
- `search` (string) - Search by employee name, ID, type, or reason
- `type` (string) - Filter by leave type
- `status` (string) - Filter by status: pending, approved, rejected
- `per_page` (integer) - Items per page (default: 10)

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "employee_id": "EMP-001",
      "employee_name": "John Doe",
      "type": "Vacation",
      "date_from": "2025-02-20",
      "date_to": "2025-02-25",
      "total_days": 5,
      "reason": "Family vacation",
      "status": "pending",
      "created_at": "2025-02-15T10:30:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 25,
    "per_page": 10
  }
}
```

#### Create Leave Application
```
POST /api/v1/leave-applications
```
**Body:**
```json
{
  "employee_id": "EMP-001",
  "employee_name": "John Doe",
  "type": "Vacation",
  "date_from": "2025-02-20",
  "date_to": "2025-02-25",
  "total_days": 5,
  "reason": "Family vacation"
}
```

#### Update Leave Status
```
PUT /api/v1/leave-applications/{id}/status
```
**Body:**
```json
{
  "status": "approved"
}
```

### Training API

#### List Trainings
```
GET /api/v1/trainings
```
**Query Parameters:**
- `search` (string) - Search by title, employee name, or provider
- `type` (string) - Filter by training type
- `category` (string) - Filter by category: Internal, External
- `status` (string) - Filter by status: pending, approved, rejected
- `per_page` (integer) - Items per page (default: 10)

#### Update Training Status
```
PUT /api/v1/trainings/{id}/status
```
**Body:**
```json
{
  "status": "approved"
}
```

### Notices API

#### Get Active Notices
```
GET /api/v1/notices/active
```
**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "System Maintenance",
      "message": "Scheduled maintenance on Feb 20",
      "type": "info",
      "expires_at": "2025-02-20"
    }
  ]
}
```

## WebSocket Events

### Connection Setup
The system uses Laravel Reverb with Laravel Echo for real-time communication.

```javascript
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
```

### Channels

#### Private User Channel
```
private('users.{userId}')
```
**Authorization:** Authenticated users can only access their own user channel.

#### Private Role Channels
```
private('role.admin')
private('role.hr')
private('role.employee')
```
**Authorization:** Users can access channels matching their session role.

#### Management Channels
```
private('leave.management')      // HR only
private('training.management')     // HR only
private('calendar.holidays')       // Admin and HR only
```

### Broadcast Events

#### LeaveStatusUpdated
**Channels:**
- `private('role.hr')`
- `private('role.employee')`
- `private('leave.management')`

**Payload:**
```json
{
  "id": 1,
  "employee_id": "EMP-001",
  "employee_name": "John Doe",
  "status": "approved",
  "type": "Vacation",
  "date_from": "2025-02-20",
  "total_days": 5
}
```

**Frontend Handler:**
```javascript
window.addEventListener('realtime:leave-status-updated', function (e) {
    // Reload page to reflect changes
    window.location.reload();
});
```

#### TrainingStatusUpdated
**Channels:**
- `private('role.hr')`
- `private('role.employee')`
- `private('training.management')`

**Payload:**
```json
{
  "id": 1,
  "employee_id": "EMP-001",
  "employee_name": "John Doe",
  "status": "approved",
  "title": "Advanced Laravel",
  "date_from": "2025-02-20",
  "hours": 8
}
```

#### NoticePublished
**Channels:**
- `private('role.admin')`
- `private('role.hr')`
- `private('role.employee')`

**Payload:**
```json
{
  "id": 1,
  "title": "New Policy Update",
  "type": "info",
  "message": "Please review the updated HR policies",
  "expires_at": "2025-03-01"
}
```

#### CustomHolidayCreated/Updated/Deleted
**Channels:**
- `private('calendar.holidays')`

**Payload:**
```json
{
  "id": 1,
  "title": "National Holiday",
  "date": "2025-02-25",
  "category": "regular",
  "is_recurring": true
}
```

## Channel Authorization

Channel authorization is handled in `routes/channels.php`:

```php
Broadcast::channel('users.{userId}', function ($user, string $userId) {
    return session('user_id') === $userId;
});

Broadcast::channel('role.{role}', function ($user, string $role) {
    return session('role') === $role;
});

Broadcast::channel('leave.management', function () {
    return session('role') === 'hr';
});

Broadcast::channel('training.management', function () {
    return session('role') === 'hr';
});

Broadcast::channel('calendar.holidays', function () {
    return in_array(session('role'), ['admin', 'hr'], true);
});
```

## Security Considerations

1. **Session-based authentication** required for all private channels
2. **Role-based access control** enforced at channel level
3. **CSRF tokens** required for all state-changing HTTP requests
4. **Input validation** on all API endpoints
5. **SQL injection protection** via Eloquent query builder
6. **XSS protection** via Blade's automatic escaping

## Rate Limiting

API endpoints are protected by Laravel's default rate limiting:
- 60 requests per minute per user
- Exceeding limit returns 429 Too Many Requests

## Error Responses

**401 Unauthorized:**
```json
{
  "message": "Unauthorized"
}
```

**403 Forbidden:**
```json
{
  "message": "Forbidden"
}
```

**404 Not Found:**
```json
{
  "message": "Resource not found"
}
```

**422 Validation Error:**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field": ["Error message"]
  }
}
```

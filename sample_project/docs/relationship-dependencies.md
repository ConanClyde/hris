# HRIS Relationship Dependencies

This document describes model relationships in the HRIS system and guidelines for safe access.

## User ↔ Employee

| Model  | Relationship | Type     | Foreign Key | Description                                         |
|--------|--------------|----------|-------------|-----------------------------------------------------|
| User   | `employee()` | HasOne   | employees.user_id | A user may have zero or one employee record.        |
| Employee | `user()`   | BelongsTo | employees.user_id | Employee optionally belongs to a user account.      |

### Access Patterns

- **Eager load when listing users with employee data:**
  ```php
  User::with(['employee' => fn ($q) => $q->select(...)])->paginate(10);
  ```

- **Use optional() or null-safe operator for safe access:**
  ```php
  $employee = optional($user->employee);
  $division = $user->employee?->division ?? '—';
  ```

- **Validate existence before access when needed:**
  ```php
  if ($user->relationLoaded('employee') || $user->employee) {
      // Safe to use employee data
  }
  ```

## Coding Standards for Relationships

1. **Define both sides** – When adding a relationship, define it on both models (e.g. User hasOne Employee, Employee belongsTo User).

2. **Use optional() or null-safe operator** – When a relationship can be null (e.g. user without employee), use `optional($model->relationship)` or `$model->relationship?->field` to avoid errors.

3. **Eager load in controllers** – Use `with()` when you know the relationship will be accessed to avoid N+1 queries.

4. **Document nullable relationships** – In model docblocks, note when a relationship may be null (e.g. "A user may have zero or one employee record").

# Security Guidelines — HRIS System

> This document defines security expectations for this project.  
> All new features must respect these rules to avoid problems in production.

---

## 1. General Principles

- Treat all HRIS data as **sensitive** (even in mock form).
- Default stance: **deny by default, explicitly allow**.
- Never trust client input; always validate and authorize on the server.
- Avoid leaking implementation details or stack traces to end users.

---

## 2. Authentication, Session & Authorization

### 2.1 Authentication & Sessions
- Login:
  - Always use `Hash::check` (or equivalent) for password verification.
  - Regenerate session on login and logout.
- Sessions:
  - Use Laravel’s session handling; do not invent custom session stores in raw PHP.
  - Store only what’s needed (`user_id`, `role`, basic profile info).
  - Never store secrets (tokens, passwords) in session.

### 2.2 Role-Based Access
- Use `session('role')` only as a **hint**; server logic must still restrict:
  - Admin-only routes and actions.
  - HR-only routes and actions.
  - Employee-specific actions (e.g., editing only their own PDS or leave).
- For new sensitive features, prefer:
  - Middleware or policies to enforce roles, rather than ad-hoc `if (session('role') === ...)` scattered everywhere.

### 2.3 Route Protection
- All admin, HR, and employee routes must:
  - Check that `session('user_id')` exists.
  - Redirect to `route('login')` or return `401` JSON when not authenticated.
- Do not expose sensitive endpoints via `GET` that mutate state; use `POST/PUT/PATCH/DELETE` with CSRF protection.

---

## 3. Input Validation & Output Encoding

### 3.1 Validation
- Validate **every** incoming request that contains user data:
  - Use `$request->validate([...])` in controllers.
  - For complex requests, consider Form Request classes.
- Validate:
  - Types (string, integer, boolean).
  - Ranges and lengths.
  - Allowed values (`in:pending,approved,rejected`).
  - Dates (`date`, `after:today`, etc.).

### 3.2 Output Escaping
- Rely on Blade’s `{{ }}` escaping by default.
- Only use `{!! !!}` when:
  - You are sure the content is sanitized or from a trusted, static source.
  - You have considered XSS risk.

---

## 4. CSRF, File Uploads & Destructive Actions

### 4.1 CSRF
- All state-changing requests from the browser must:
  - Use forms with `@csrf`.
  - Use `@method('PUT')`, `@method('PATCH')`, or `@method('DELETE')` where appropriate.
- Disable CSRF only for API routes intended for non-browser clients, and only with strong authentication in place.

### 4.2 File Uploads (Avatars, Attachments, Backups)
- Always:
  - Validate mime types and max file size.
  - Store user uploads under `storage/app/public` or a configured disk.
  - Use generated filenames (e.g., UUIDs) to avoid collisions and path traversal.
  - Never trust the original filename for anything except display.
- On replace:
  - Delete old files if they exist.
- On delete:
  - Ensure only the owner or an allowed role (HR/admin) can delete attachments.

### 4.3 Destructive Actions
- No destructive action via plain `GET` links.
- Use confirmed forms and, when possible, a **confirmation modal** in the UI.
- Log significant destructive operations (e.g., backup delete, user delete, PDS purge) for auditability.

---

## 5. Broadcasting & Realtime

- Treat broadcast payloads like API responses:
  - Do not include secrets, tokens, or full personal records.
  - Keep payloads minimal and purpose-specific.
- Channel authorization in `routes/channels.php` must:
  - Ensure the authenticated user is allowed to listen on that channel.
  - For `users.{id}` channels, require that `{id}` matches the logged-in user (or mapped HRIS employee record).
- Avoid broadcasting:
  - Raw error messages, stack traces, or debug info.

---

## 6. Configuration & Secrets

- Use `.env` for:
  - Database credentials.
  - API keys (Google Calendar, Pusher, etc.).
  - Broadcasting and mail configuration.
- Never:
  - Commit `.env` or secrets to version control.
  - Hardcode secrets in PHP, JS, or Blade.
- For new integrations:
  - Add configuration keys to `config/*.php` and read values from `env(...)`.

---

## 7. Logging & Error Handling

- Log:
  - Unexpected exceptions with `Log::error` and a clear message.
  - Failed external API calls (without dumping entire responses if they contain sensitive data).
  - Important security-related events (e.g., repeated failed login attempts) when implemented.
- Do not:
  - Show raw exception messages to users.
  - Dump request bodies or secrets into logs.

---

## 8. Database & Migrations (Future-Proofing)

- When moving from mock data to real tables:
  - Use proper migration types (`unsignedBigInteger` for IDs, `string` lengths, `enum` or `check` where appropriate).
  - Add necessary indexes for foreign keys and frequent queries.
  - Consider soft deletes for HR records that may require audit trails (e.g., users, employees, PDS).

---

## 9. Frontend Security Considerations

- Avoid inline `<script>` tags that build JS from unsanitized user data.
- When using `window.*` globals for Echo or config:
  - Only expose non-sensitive identifiers (e.g., current user ID, role, channel names).
- Do not trust any client-only checks for security; always back them with server-side rules.


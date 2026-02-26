<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## HRIS System

## Development commands

### Backend

Run automated tests:

```bash
php artisan test
```

Regenerate autoload (after adding/moving classes):

```bash
composer dump-autoload
```

Clear compiled Blade views (useful after view recursion / memory exhaustion issues):

```bash
php artisan view:clear
```

### Frontend

Build assets:

```bash
npm run build
```

## Recent stability fixes

- **Profile routes**
  - Fixed role-scoped route controller resolution (HR/Employee import for `ProfileController`).
  - Implemented real password change + account deletion endpoints in `ProfileController` and wired role routes.
- **Backup feature**
  - Implemented missing `BackupController`.
  - Fixed backup view recursion that could cause PHP memory exhaustion.
- **Activity logs**
  - Hardened activity logs UI to avoid null/array offset errors when log actor/user data is missing.
  - Aligned controller filtering with the UI (`user` query param).
- **Settings**
  - Dark mode toggle persists via `localStorage`.
  - Added a minimal POST-backed “Revoke session” action for the Active Sessions UI.

## Hardcoded Values Refactoring

All previously hardcoded credentials, configuration values, and theming constants have been externalized to environment variables and configuration files:

### New Configuration Files
- `config/seeder.php` - Database seeder credentials and demo user data
- `config/theme.php` - Brand colors and theming configuration
- `config/defaults.php` - Default values for UI placeholders and fallback data

### Environment Variables Added
All seeder credentials, authentication settings, theme colors, and default values are now configurable via `.env`:

```bash
# Seeder credentials (change in production!)
SEEDER_ADMIN_EMAIL=admin@hris.local
SEEDER_ADMIN_PASSWORD=admin123
SEEDER_HR_MANAGER_EMAIL=hr@hris.local
SEEDER_HR_MANAGER_PASSWORD=hr123
SEEDER_EMPLOYEE_EMAIL=employee@hris.local
SEEDER_EMPLOYEE_PASSWORD=employee123

# Authentication
AUTH_LEGACY_DOMAIN="@hris.local"

# Theming
THEME_PRIMARY="#013CFC"
THEME_PRIMARY_DARK="#0031BC"
THEME_PRIMARY_LIGHT="#60C8FC"

# Defaults
DEFAULT_USER_FIRST_NAME="User"
DEFAULT_MEMBER_SINCE="January 2024"
```

### Migration Steps
1. Copy `.env.example` to `.env` and customize values
2. Run `php artisan config:clear` to clear config cache
3. Run `php artisan db:seed` with new seeder config
4. Run `php artisan test tests/Unit/HardcodedValuesTest.php` to verify

### Security Improvements
- No hardcoded passwords in source code
- No hardcoded email addresses in seeders
- Authentication domain suffix configurable
- All sensitive values via environment variables

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

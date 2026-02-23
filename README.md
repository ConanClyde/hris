# HRIS - Human Resource Information System

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red?style=flat-square)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=flat-square&logo=vue.js)](https://vuejs.org)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.x-3178C6?style=flat-square&logo=typescript)](https://www.typescriptlang.org)
[![License](https://img.shields.io/github/license/ConanClyde/hris?style=flat-square)](LICENSE)

A modern, feature-rich Human Resource Information System built with Laravel and Vue 3, designed for efficient HR management with real-time capabilities.

## 🏗️ Architecture Overview

### Technology Stack
- **Backend**: Laravel 12.x with PHP 8.2+
- **Frontend**: Vue 3 + Inertia.js + TypeScript
- **Styling**: Tailwind CSS v4
- **Build System**: Vite
- **Database**: MySQL 8.0+ (with SQLite support)
- **Real-time**: Laravel Reverb (WebSocket)
- **Authentication**: Laravel Fortify
- **Testing**: PestPHP

### Project Structure
```
app/Features/                 # Feature-based architecture
├── ActivityLogs/            # Audit logging
├── Auth/                    # Authentication system
├── Backup/                  # System backup management
├── Calendar/                # Calendar and holidays
├── Dashboard/               # Role-based dashboards
├── Employees/               # Employee management
├── Leave/                   # Leave application system
├── Notices/                 # Notice board system
├── Notifications/           # Notification system
├── Pds/                     # Personal Data Sheet
├── Training/                # Training management
└── Users/                   # User management
```

## 🚀 Getting Started

### Prerequisites
- PHP 8.2 or higher
- Composer 2.5+
- Node.js 18+ and npm 9+
- MySQL 8.0+ (or SQLite for development)
- Git

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/ConanClyde/hris.git
cd hris
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node.js dependencies**
```bash
npm install
```

4. **Environment Setup**
```bash
cp .env.example .env
```

5. **Generate application key**
```bash
php artisan key:generate
```

6. **Configure Database**
   - Update `.env` with your database credentials
   - Default uses SQLite (no configuration needed for quick start)

7. **Run Migrations**
```bash
php artisan migrate
```

8. **Seed Database** (Optional)
```bash
php artisan db:seed
```

9. **Build Frontend Assets**
```bash
npm run build
```

### Development

Start the development server with all services:

```bash
# Run all services (backend, queue, frontend)
composer dev

# Or run individually:
php artisan serve           # Backend server
npm run dev                 # Frontend development
php artisan queue:listen    # Queue worker
```

### Available Scripts

```bash
# Development
npm run dev                 # Frontend development server
composer dev                # All services with concurrently

# Build
npm run build              # Production build
npm run build:ssr          # SSR build

# Code Quality
npm run lint               # ESLint + Prettier
npm run format             # Format code
composer lint              # PHP code style (Pint)

# Testing
php artisan test           # Run all tests
php artisan test --filter=Feature  # Feature tests
php artisan test --filter=Unit     # Unit tests
```

## 🎯 Core Features

### Role-Based Access Control
- **Admin**: Full system access, user management, backups
- **HR**: Employee management, leave approval, training coordination
- **Employee**: Self-service portal for leaves, PDS, training

### HR Modules

#### 📅 Leave Management
- Apply for different leave types (Vacation, Sick, etc.)
- Approval workflow with real-time status updates
- Leave credits tracking and management
- Attachment support for leave applications

#### 🎓 Training Management
- Training program creation and scheduling
- Employee enrollment and tracking
- Training completion certificates
- Status management (Pending/Approved/Completed)

#### 📋 Personal Data Sheet (PDS)
- Comprehensive employee profile management
- Government ID integration
- Family background, education, work experience
- CSC eligibility and other government requirements

#### 📅 Calendar Integration
- Google Calendar holidays synchronization
- Custom holiday management
- Leave and training event visualization
- Role-based calendar views

#### 📢 Notice Board
- Company announcements and communications
- Targeted notice delivery by role/department
- Read status tracking
- Expiration dates and scheduling

#### 🔒 Activity Logs
- Comprehensive audit trail
- User action tracking
- Security monitoring
- Performance metrics

## 🛠️ Development Guidelines

### Code Standards
- **PHP**: Follow Laravel conventions and PSR standards
- **Vue**: Composition API with TypeScript
- **CSS**: Tailwind CSS utility classes
- **Naming**: Descriptive, consistent naming conventions

### Feature Development
1. Follow the feature-first architecture pattern
2. Place new features in `app/Features/{FeatureName}/`
3. Use Inertia.js for SPA navigation
4. Implement proper validation and error handling

### Testing
```bash
# Run specific test suites
php artisan test --filter=Feature
php artisan test --filter=Unit

# Run individual tests
php artisan test tests/Feature/LeaveApplicationTest.php
```

### Git Workflow
```bash
# Create feature branch
git checkout -b feature/new-feature

# Commit with conventional commits
git commit -m "feat: add new leave type"

# Push and create PR
git push origin feature/new-feature
```

## 📡 Real-time Features

The system uses Laravel Reverb for WebSocket communication:

### Events Broadcasted
- Leave status updates
- Training status changes
- New notices published
- Calendar updates
- Custom holiday changes

### WebSocket Configuration
```env
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
```

## 🔧 Configuration

### Environment Variables
Key configuration options in `.env`:

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hris
DB_USERNAME=root
DB_PASSWORD=

# Mail (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password

# Google Calendar API
GOOGLE_CALENDAR_API_KEY=your-api-key

# Reverb (WebSocket)
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
```

### Customization
- **Theme**: Modify `config/theme.php`
- **Defaults**: Update `config/defaults.php`
- **Seeder Data**: Configure `config/seeder.php`

## 📊 Database Schema

### Core Tables
- `users` - User authentication and profiles
- `employees` - Employee master data
- `leave_applications` - Leave requests and approvals
- `trainings` - Training programs and enrollments
- `pds` - Personal Data Sheets
- `notices` - Company announcements
- `activity_logs` - Audit trail
- `custom_holidays` - Organization-specific holidays

### Relationships
- Users ↔ Employees (one-to-one)
- Employees → Leave Applications (one-to-many)
- Employees → Trainings (one-to-many)
- PDS → Employee (one-to-one)
- Various PDS sub-tables for detailed information

## 🚨 Troubleshooting

### Common Issues

**Database Connection Failed**
```bash
# Check .env database configuration
php artisan migrate:fresh --seed
```

**Frontend Build Issues**
```bash
npm run build -- --debug
npm cache clean --force
```

**WebSocket Not Working**
```bash
# Start Reverb server
php artisan reverb:start

# Check .env configuration
php artisan config:clear
```

**Permission Issues**
```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
```

### Debug Commands
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## 📈 Performance Monitoring

The system includes performance metrics tracking:
- Response time monitoring
- Database query optimization
- Memory usage tracking
- Real-time performance dashboards

## 🔒 Security

### Authentication
- Laravel Fortify for secure authentication
- Session-based authentication
- Role-based authorization
- Password reset functionality

### Data Protection
- Input validation and sanitization
- SQL injection prevention
- XSS protection
- CSRF protection
- Rate limiting

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Code Review Process
- All PRs require review
- Tests must pass
- Code must follow established standards
- Documentation updates required for new features

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - PHP Framework
- [Vue.js](https://vuejs.org) - Progressive JavaScript Framework
- [Inertia.js](https://inertiajs.com) - Server-driven SPAs
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework
- [Laravel Reverb](https://laravel.com/docs/reverb) - WebSocket server

## 📞 Support

For support, please open an issue on GitHub or contact the development team.

---

<p align="center">
  Built with ❤️ using Laravel and Vue
</p>
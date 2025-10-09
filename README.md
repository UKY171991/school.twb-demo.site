# Multi-School Management System

A comprehensive Laravel-based school management system supporting multiple schools with role-based access control, inspired by the [Global Multi School Management System Express](https://codecanyon.net/item/global-multi-school-management-system-express/21975378).

## 🎓 Features

### Multi-School Support
- Manage multiple schools from a single platform
- School-specific data isolation
- Centralized super admin control

### Role-Based Access Control
- **Super Admin**: Full system access across all schools
- **School Admin**: Complete school management
- **Teacher**: Class and student management
- **Student**: Personal dashboard with grades, attendance, and fees
- **Guardian**: Monitor children's progress
- **Accountant**: Financial management
- **Librarian**: Library operations
- **Receptionist**: Front desk operations
- **Staff**: General staff access

### Core Modules
- 📊 **Dashboard**: Role-specific dashboards with key metrics
- 👥 **User Management**: Multi-role user system
- 🏫 **School Management**: School profiles and settings
- 👨‍🎓 **Student Management**: Enrollment, profiles, classes
- 👨‍🏫 **Teacher Management**: Staff profiles and assignments
- 📚 **Class Management**: Classes and sections
- ✅ **Attendance**: Daily attendance tracking
- 💰 **Fee Management**: Fee collection and tracking
- 📝 **Exam Management**: Examinations and results
- 📖 **Library Management**: Book inventory and lending

## 🚀 Installation

### Requirements
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/SQLite

### Setup

1. **Clone the repository**
```bash
git clone <your-repo-url>
cd school.twb-demo.site
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment configuration**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database setup**
```bash
php artisan migrate:fresh --seed
```

5. **Build assets**
```bash
npm run build
```

6. **Start development server**
```bash
php artisan serve
```

Visit: `http://localhost:8000`

## 🔐 Demo Credentials

All demo accounts use password: **`password`**

### Super Admin
- **Email**: superadmin@school.com
- **Access**: Full system control

### Windsor Park School

| Role | Email |
|------|-------|
| Admin | admin.windsor@school.com |
| Teacher | teacher.windsor@school.com |
| Student | student.windsor@school.com |
| Guardian | guardian.windsor@school.com |
| Accountant | accountant.windsor@school.com |
| Librarian | librarian.windsor@school.com |
| Receptionist | receptionist.windsor@school.com |
| Staff | staff.windsor@school.com |

### Ideal Stevenson School

| Role | Email |
|------|-------|
| Admin | admin.stevenson@school.com |
| Teacher | teacher.stevenson@school.com |
| Student | student.stevenson@school.com |
| Guardian | guardian.stevenson@school.com |
| Accountant | accountant.stevenson@school.com |
| Librarian | librarian.stevenson@school.com |
| Receptionist | receptionist.stevenson@school.com |
| Staff | staff.stevenson@school.com |

## 📁 Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── SuperAdmin/
│   │   │   ├── Admin/
│   │   │   ├── Teacher/
│   │   │   ├── Student/
│   │   │   └── Auth/
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── School.php
│       ├── Role.php
│       ├── Student.php
│       ├── Teacher.php
│       └── ClassModel.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login.blade.php
│       ├── layouts/
│       │   └── admin.blade.php
│       ├── superadmin/
│       ├── admin/
│       ├── teacher/
│       ├── student/
│       ├── guardian/
│       ├── accountant/
│       ├── librarian/
│       ├── receptionist/
│       └── staff/
└── routes/
    └── web.php
```

## 🎨 Technology Stack

- **Backend**: Laravel 12.x
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: MySQL/SQLite
- **Authentication**: Laravel Breeze
- **Build Tool**: Vite

## 🔧 Database Schema

### Core Tables
- `users` - System users with role and school associations
- `roles` - User roles and permissions
- `schools` - School profiles and settings
- `students` - Student records
- `teachers` - Teacher profiles
- `classes` - Class definitions
- `sections` - Class sections
- `subjects` - Subject definitions
- `attendance` - Daily attendance records
- `fees` - Fee management
- `exams` - Examination records
- `exam_results` - Student results
- `library_books` - Library inventory
- `book_issues` - Book lending records

## 🌐 Routes

### Public Routes
- `GET /` - Redirects to login
- `GET /login` - Login page
- `POST /login` - Authentication
- `POST /logout` - Logout

### Protected Routes (Role-based)
- `/superadmin/*` - Super Admin routes
- `/admin/*` - School Admin routes
- `/teacher/*` - Teacher routes
- `/student/*` - Student routes
- `/guardian/*` - Guardian routes
- `/accountant/*` - Accountant routes
- `/librarian/*` - Librarian routes
- `/receptionist/*` - Receptionist routes
- `/staff/*` - Staff routes

## 🎯 Key Features Implementation

### Role-Based Middleware
```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin routes
});
```

### User Helper Methods
```php
$user->isSuperAdmin()
$user->isAdmin()
$user->isTeacher()
$user->isStudent()
$user->hasRole('role-slug')
```

### Multi-School Data Isolation
Each user is associated with a school (except Super Admin), ensuring data isolation between schools.

## 📝 Development Notes

### Adding New Modules
1. Create migration for database tables
2. Create model with relationships
3. Create controller in appropriate directory
4. Add routes with proper middleware
5. Create views extending the admin layout
6. Update sidebar navigation

### Extending Roles
1. Add role in `RoleSeeder.php`
2. Add route in `AuthenticatedSessionController.php`
3. Create controller and views
4. Add navigation items

## 🐛 Common Issues

### Migration Error
```bash
php artisan migrate:fresh --seed
```

### Asset Build Error
```bash
npm run build
```

### Permission Denied
Ensure storage and cache directories are writable:
```bash
chmod -R 775 storage bootstrap/cache
```

## 📚 Future Enhancements

- [ ] Advanced attendance reports
- [ ] Fee payment gateway integration
- [ ] SMS/Email notifications
- [ ] Online exam module
- [ ] Parent-teacher communication
- [ ] Mobile app API
- [ ] Advanced reporting system
- [ ] Hostel management
- [ ] Transport management
- [ ] HR & Payroll

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📞 Support

For support, email support@example.com or create an issue in the repository.

---

**Built with ❤️ using Laravel**

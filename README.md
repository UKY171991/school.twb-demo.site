# School Management System

A comprehensive school management system built with Laravel 12 and AdminLTE 3.

## Features

### Admin Panel (AdminLTE 3)
- **Dashboard**: Overview with statistics cards showing total students, teachers, grades, and subjects
- **Teacher Management**: ✅ Complete CRUD operations (Add, Edit, Delete, View)
- **Student Management**: ✅ Complete CRUD operations with grade assignment and detailed profile view
- **Grades/Classes Management**: ✅ Complete CRUD operations with student count
- **Subject Management**: ✅ Complete CRUD operations with teacher and grade assignments
- **Attendance Tracking**: Database ready (Views pending)
- **Marks/Exam Management**: Database ready (Views pending)
- **Quick Actions**: Dashboard buttons for quick access to create forms
- **Success Notifications**: Flash messages for all CRUD operations

### Front-End
- Modern, responsive landing page with gradient hero section
- Feature showcase with 6 key features
- Statistics section
- About section
- User authentication (Login/Register)
- Bootstrap 5 styling
- Mobile-friendly design

## Technology Stack

- **Backend**: Laravel 12
- **Frontend**: Bootstrap 5
- **Admin Template**: AdminLTE 3
- **Database**: SQLite (can be changed to MySQL/PostgreSQL)
- **Authentication**: Laravel Breeze/UI

## Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Copy environment file:
   ```bash
   cp .env.example .env
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Run migrations:
   ```bash
   php artisan migrate
   ```

6. Seed the database (optional):
   ```bash
   php artisan db:seed
   ```

7. Build assets:
   ```bash
   npm run build
   ```

8. Start the development server:
   ```bash
   php artisan serve
   ```

## Default Credentials

After seeding the database, you can login with:
- **Email**: admin@school.com
- **Password**: password

## Database Structure

### Tables
- **users**: System users (admin, teachers, etc.)
- **grades**: Grade/Class information (Grade 1, Grade 2, etc.)
- **teachers**: Teacher profiles and information
- **students**: Student records with grade assignment
- **subjects**: Subject details with teacher and grade linkage
- **attendances**: Daily attendance records
- **marks**: Exam marks and grades

### Relationships
- Students belong to Grades
- Subjects belong to Grades and Teachers
- Attendance records belong to Students
- Marks belong to Students and Subjects

## Usage

### Managing Teachers
1. Navigate to **Teachers** from the sidebar
2. Click **Add New Teacher**
3. Fill in the required information
4. Submit the form

### Managing Students
1. Navigate to **Students** from the sidebar
2. Click **Add New Student**
3. Select the grade/class
4. Fill in student details
5. Submit the form

### Managing Grades
1. Navigate to **Grades/Classes**
2. Add new grades as needed
3. Assign sections (A, B, C, etc.)

### Managing Subjects
1. Navigate to **Subjects**
2. Create subjects
3. Assign to specific grades
4. Assign teachers to subjects

## Customization

### Changing the Logo
Edit `config/adminlte.php`:
```php
'logo' => '<b>Your</b>School',
'logo_img' => 'path/to/your/logo.png',
```

### Changing Colors
AdminLTE uses Bootstrap 4 color schemes. You can customize in:
- `config/adminlte.php` for sidebar and navbar classes
- Custom CSS in `public/css/` directory

### Adding New Modules
1. Create a new model and migration
2. Create a controller with resource methods
3. Add routes in `routes/web.php`
4. Create views in `resources/views/`
5. Add menu item in `config/adminlte.php`

## Security

- All routes are protected with authentication middleware
- CSRF protection enabled on all forms
- Password hashing using bcrypt
- Input validation on all forms

## Future Enhancements

- [ ] Parent portal
- [ ] Fee management
- [ ] Timetable management
- [ ] Library management
- [ ] Transport management
- [ ] SMS/Email notifications
- [ ] Report card generation
- [ ] Online exam system
- [ ] Assignment submission
- [ ] Multi-language support

## License

This project is open-sourced software licensed under the MIT license.

## Support

For support, email support@schoolms.com or create an issue in the repository.

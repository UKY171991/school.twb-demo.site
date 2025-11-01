# Multi-School Management System Design Document

## Overview

The Multi-School Management System is a comprehensive Laravel-based application that enables management of multiple educational institutions through a unified platform. The system leverages AdminLTE3 for the user interface, implements role-based access control using Spatie Laravel Permission, and provides seamless user experience through AJAX interactions with toaster notifications.

The system builds upon the existing Laravel foundation with established models (User, School, Student, Teacher, etc.) and extends functionality to create a complete school management solution.

## Architecture

### System Architecture Pattern
- **MVC Architecture**: Laravel's Model-View-Controller pattern
- **Repository Pattern**: For data access abstraction
- **Service Layer Pattern**: For business logic encapsulation
- **Observer Pattern**: For model events and notifications
- **Middleware Pattern**: For authentication and authorization

### Technology Stack
- **Backend**: Laravel 12.x with PHP 8.2+
- **Frontend**: AdminLTE3 with Bootstrap 4/5
- **Database**: SQLite (configurable to MySQL/PostgreSQL)
- **JavaScript**: jQuery with AJAX for dynamic interactions
- **Notifications**: Toastr.js for user feedback
- **Tables**: DataTables for interactive data presentation
- **Permissions**: Spatie Laravel Permission package

### Multi-Tenancy Approach
- **Shared Database, Shared Schema**: Single database with school_id foreign keys
- **Row-Level Security**: Data isolation through school_id filtering
- **Middleware-Based Filtering**: Automatic school context application

## Components and Interfaces

### Core Models (Existing - Enhanced)

#### User Model
```php
// Enhanced with additional relationships and methods
class User extends Authenticatable
{
    use HasRoles, HasFactory, Notifiable;
    
    // Additional methods for dashboard customization
    public function getDashboardRoute(): string
    public function getMenuItems(): array
    public function hasSchoolAccess(int $schoolId): bool
}
```

#### School Model
```php
// Enhanced with configuration and statistics
class School extends Model
{
    // Additional methods
    public function getStatistics(): array
    public function getActiveStudentsCount(): int
    public function getActiveTeachersCount(): int
    public function getConfiguration(): array
}
```

### New Controller Structure

#### Base Controllers
- **BaseController**: Common functionality for all controllers
- **BaseAjaxController**: AJAX response handling and validation
- **BaseDashboardController**: Dashboard-specific functionality

#### Role-Specific Controllers
- **SuperAdmin\DashboardController**: Multi-school overview
- **SuperAdmin\SchoolController**: School management
- **Admin\DashboardController**: Single school administration
- **Teacher\DashboardController**: Teacher-specific functionality
- **Student\DashboardController**: Student portal
- **Parent\DashboardController**: Parent portal

### Service Layer

#### Core Services
- **SchoolService**: School management operations
- **UserService**: User management and role assignment
- **DashboardService**: Dashboard data aggregation
- **NotificationService**: System notifications and alerts
- **ReportService**: Academic and administrative reports

#### AJAX Services
- **AjaxResponseService**: Standardized AJAX responses
- **ValidationService**: Client and server-side validation
- **DataTableService**: DataTables integration and processing

### Frontend Components

#### AdminLTE3 Integration
- **Layout Templates**: Master layouts for different user roles
- **Component Library**: Reusable UI components (cards, forms, tables)
- **Theme Customization**: School-specific branding and colors
- **Responsive Design**: Mobile-first approach

#### JavaScript Architecture
- **AJAX Handler**: Centralized AJAX request management
- **Form Validator**: Client-side validation with real-time feedback
- **DataTable Manager**: Dynamic table initialization and management
- **Toaster Manager**: Notification system integration
- **Modal Manager**: Dynamic modal handling

## Data Models

### Enhanced Database Schema

#### Schools Table
```sql
CREATE TABLE schools (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(255),
    website VARCHAR(255),
    logo VARCHAR(255),
    principal_name VARCHAR(255),
    principal_phone VARCHAR(20),
    principal_email VARCHAR(255),
    configuration JSON, -- School-specific settings
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### Users Table (Enhanced)
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    school_id BIGINT REFERENCES schools(id),
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('super_admin', 'admin', 'teacher', 'student', 'parent'),
    profile_photo VARCHAR(255),
    phone VARCHAR(20),
    is_active BOOLEAN DEFAULT true,
    last_login_at TIMESTAMP,
    preferences JSON, -- User-specific settings
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### New Tables

#### Dashboard_Widgets Table
```sql
CREATE TABLE dashboard_widgets (
    id BIGINT PRIMARY KEY,
    user_id BIGINT REFERENCES users(id),
    widget_type VARCHAR(50),
    position INTEGER,
    configuration JSON,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### System_Notifications Table
```sql
CREATE TABLE system_notifications (
    id BIGINT PRIMARY KEY,
    school_id BIGINT REFERENCES schools(id),
    user_id BIGINT REFERENCES users(id),
    title VARCHAR(255),
    message TEXT,
    type ENUM('info', 'success', 'warning', 'error'),
    is_read BOOLEAN DEFAULT false,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Data Relationships

#### Multi-School Hierarchy
```
Super Admin
├── School A
│   ├── School Admin A
│   ├── Teachers A
│   ├── Students A
│   └── Parents A
└── School B
    ├── School Admin B
    ├── Teachers B
    ├── Students B
    └── Parents B
```

## Error Handling

### Exception Handling Strategy
- **Custom Exception Classes**: SchoolNotFoundException, UnauthorizedSchoolAccessException
- **Global Exception Handler**: Centralized error processing and logging
- **AJAX Error Responses**: Standardized error format for AJAX requests
- **User-Friendly Messages**: Translated error messages with appropriate severity levels

### Validation Framework
- **Form Request Classes**: Server-side validation with custom rules
- **JavaScript Validation**: Client-side validation with real-time feedback
- **Multi-Language Support**: Validation messages in multiple languages
- **Custom Validation Rules**: School-specific validation requirements

### Error Response Format
```json
{
    "success": false,
    "message": "User-friendly error message",
    "errors": {
        "field_name": ["Specific field error"]
    },
    "code": "ERROR_CODE",
    "data": null
}
```

## Testing Strategy

### Testing Pyramid

#### Unit Tests
- **Model Tests**: Relationships, accessors, mutators, and business logic
- **Service Tests**: Business logic validation and data processing
- **Helper Tests**: Utility functions and custom classes

#### Integration Tests
- **Controller Tests**: HTTP requests, responses, and middleware integration
- **Database Tests**: Model interactions and query optimization
- **AJAX Tests**: API endpoints and response validation

#### Feature Tests
- **Authentication Tests**: Login, logout, and role-based access
- **Dashboard Tests**: Role-specific dashboard functionality
- **CRUD Operations**: Complete create, read, update, delete workflows
- **Multi-School Tests**: School isolation and data security

#### Browser Tests (Optional)
- **User Journey Tests**: Complete user workflows using Laravel Dusk
- **JavaScript Tests**: AJAX interactions and UI responsiveness
- **Cross-Browser Tests**: Compatibility across different browsers

### Test Data Management
- **Database Factories**: Realistic test data generation
- **Seeders**: Consistent test environment setup
- **Test Traits**: Reusable test functionality for multi-school scenarios

### Performance Testing
- **Load Testing**: System performance under concurrent users
- **Database Optimization**: Query performance and indexing
- **AJAX Performance**: Response times and payload optimization

## Implementation Phases

### Phase 1: Foundation and Authentication
- Enhanced user authentication with role-based dashboards
- AdminLTE3 integration and base layout templates
- AJAX infrastructure and toaster notification system

### Phase 2: Super Admin Functionality
- Multi-school management interface
- School creation, configuration, and administration
- User management across schools

### Phase 3: School Administration
- School-specific admin dashboards
- Student and teacher management
- Class and subject administration

### Phase 4: Academic Management
- Attendance tracking system
- Grade management and reporting
- Academic calendar and scheduling

### Phase 5: User Portals
- Teacher dashboard and functionality
- Student portal and academic tracking
- Parent portal and communication tools

### Phase 6: Advanced Features
- Reporting and analytics
- Communication system
- Mobile responsiveness optimization
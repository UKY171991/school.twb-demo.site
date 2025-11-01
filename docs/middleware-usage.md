# Middleware Usage Guide

## Overview

The multi-school management system includes several middleware components for security, access control, and school context management.

## Available Middleware

### 1. SchoolContextMiddleware (`school.context`)

**Purpose**: Automatically sets school context for all authenticated users and shares it with views.

**Features**:
- Sets school context based on user type
- Validates school access for requests
- Shares school data with all views
- Handles super admin school switching

**Usage**:
```php
Route::middleware(['auth', 'school.context'])->group(function () {
    // Routes that need school context
});
```

**What it provides to views**:
- `$currentSchool` - Current active school
- `$accessibleSchools` - Collection of schools user can access
- `$userCanAccessAllSchools` - Boolean for super admin check
- `$schoolContext` - Array with school context data

### 2. RoleBasedAccessMiddleware (`role`)

**Purpose**: Restricts access based on user roles.

**Usage**:
```php
// Single role
Route::middleware(['role:admin'])->group(function () {
    // Admin only routes
});

// Multiple roles
Route::middleware(['role:admin,teacher'])->group(function () {
    // Admin or teacher routes
});
```

**Available roles**:
- `super_admin`
- `admin`
- `teacher`
- `student`
- `parent`

### 3. PermissionMiddleware (`permission`)

**Purpose**: Checks specific permissions using Spatie Laravel Permission.

**Usage**:
```php
Route::middleware(['permission:manage-students'])->group(function () {
    // Routes requiring specific permission
});
```

### 4. EnsureActiveSchoolMiddleware (`school.active`)

**Purpose**: Ensures users have an active school assignment (except super admins).

**Features**:
- Validates school assignment exists
- Checks if assigned school is active
- Verifies user account is active
- Handles graceful logout for invalid assignments

**Usage**:
```php
Route::middleware(['school.active'])->group(function () {
    // Routes requiring active school assignment
});
```

### 5. SchoolSwitchMiddleware (`school.switch`)

**Purpose**: Handles school context switching for super admins.

**Features**:
- Processes school switch requests
- Validates school access
- Manages session state
- Supports AJAX switching

**Usage**:
```php
Route::middleware(['school.switch'])->group(function () {
    // Routes that support school switching
});
```

### 6. CheckUserType (`user.type`) - Enhanced

**Purpose**: Legacy middleware enhanced with better error handling and logging.

**Usage**:
```php
Route::middleware(['user.type:admin,teacher'])->group(function () {
    // Routes for admin or teacher
});
```

## Middleware Groups

### Standard Authentication Group
```php
Route::middleware(['auth', 'school.context'])->group(function () {
    // Most authenticated routes should use this
});
```

### School Admin Group
```php
Route::middleware(['auth', 'school.context', 'role:admin', 'school.active'])->group(function () {
    // School admin routes
});
```

### Super Admin Group
```php
Route::middleware(['auth', 'school.context', 'role:super_admin', 'school.switch'])->group(function () {
    // Super admin routes with school switching
});
```

### Teacher Group
```php
Route::middleware(['auth', 'school.context', 'role:teacher', 'school.active'])->group(function () {
    // Teacher routes
});
```

## SchoolContextService

The `SchoolContextService` provides programmatic access to school context functionality:

### Key Methods

```php
// Get current active school
$school = SchoolContextService::getCurrentSchool();

// Get accessible schools
$schools = SchoolContextService::getAccessibleSchools();

// Check school access
$canAccess = SchoolContextService::canAccessSchool($schoolId);

// Switch school (super admin only)
$success = SchoolContextService::switchSchool($schoolId);

// Apply school filter to queries
$query = SchoolContextService::applySchoolFilter($query, 'school_id');

// Get school statistics
$stats = SchoolContextService::getSchoolStatistics($schoolId);
```

## Error Handling

All middleware provide consistent error handling:

### AJAX Requests
```json
{
    "success": false,
    "message": "Error message",
    "code": "ERROR_CODE",
    "redirect": "/appropriate/route"
}
```

### Regular Requests
- Redirects to appropriate dashboard
- Flash error messages
- Proper HTTP status codes

## Security Features

1. **Automatic School Filtering**: Prevents cross-school data access
2. **Permission Logging**: Logs all unauthorized access attempts
3. **Session Management**: Secure school context switching
4. **Input Validation**: Validates all school-related parameters
5. **Active Status Checks**: Ensures users and schools are active

## Best Practices

1. **Always use `school.context`** for authenticated routes
2. **Combine with role middleware** for proper access control
3. **Use `school.active`** for non-super-admin routes
4. **Apply `school.switch`** for super admin functionality
5. **Check permissions programmatically** when needed
6. **Handle AJAX and regular requests** appropriately

## Example Route Definitions

```php
// Super Admin Routes
Route::middleware(['auth', 'school.context', 'role:super_admin', 'school.switch'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'index']);
        Route::post('/switch-school', [SuperAdminController::class, 'switchSchool']);
    });

// School Admin Routes
Route::middleware(['auth', 'school.context', 'role:admin', 'school.active'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('students', StudentController::class);
        Route::resource('teachers', TeacherController::class);
    });

// Teacher Routes
Route::middleware(['auth', 'school.context', 'role:teacher', 'school.active'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/classes', [TeacherController::class, 'classes']);
        Route::post('/attendance', [TeacherController::class, 'markAttendance']);
    });

// AJAX Routes
Route::middleware(['auth', 'school.context'])
    ->prefix('ajax')
    ->name('ajax.')
    ->group(function () {
        Route::get('/students', [AjaxController::class, 'getStudents']);
        Route::get('/teachers', [AjaxController::class, 'getTeachers']);
    });
```

## Migration from Legacy Middleware

To update existing routes:

1. Replace `user.type` with `role` where appropriate
2. Add `school.context` to all authenticated routes
3. Add `school.active` for non-super-admin routes
4. Use `SchoolContextService` in controllers instead of manual checks
5. Update error handling to use new response formats
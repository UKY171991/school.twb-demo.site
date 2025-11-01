# Base Controllers Usage Guide

## Overview

The base controller infrastructure provides a standardized foundation for all controllers in the multi-school management system. It includes three main base controllers:

1. **BaseController** - Common functionality for all controllers
2. **BaseAjaxController** - Standardized AJAX response handling
3. **BaseDashboardController** - Dashboard-specific functionality

## BaseController

### Features
- Automatic user and school context setup
- Role-based access control methods
- School filtering and authorization
- Common view data preparation
- Menu generation based on user roles

### Usage Example
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;

class StudentController extends BaseController
{
    public function index()
    {
        // Get current user's school context
        $school = $this->getCurrentSchool();
        
        // Apply school filtering to query
        $query = Student::query();
        $students = $this->applySchoolContext($query)->get();
        
        // Get common view data
        $viewData = $this->getCommonViewData();
        $viewData['students'] = $students;
        
        return view('admin.students.index', $viewData);
    }
}
```

### Key Methods
- `getCurrentUser()` - Get authenticated user
- `getCurrentSchool()` - Get user's school
- `hasSchoolAccess($schoolId)` - Check school access permission
- `applySchoolContext($query)` - Apply school filtering to queries
- `getMenuItems()` - Get role-based menu items
- `getCommonViewData()` - Get standard view data

## BaseAjaxController

### Features
- Standardized JSON response formats
- Automatic error handling
- DataTables integration
- Select2 dropdown support
- File upload handling

### Usage Example
```php
<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\BaseAjaxController;

class StudentController extends BaseAjaxController
{
    public function store(Request $request)
    {
        return $this->handleAjaxRequest(function() use ($request) {
            $data = $this->validateAjaxRequest($request, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users'
            ]);
            
            $student = Student::create($data);
            
            return $this->successResponse('Student created successfully', $student);
        });
    }
    
    public function datatableData(Request $request)
    {
        $query = Student::with(['user', 'class']);
        $columns = ['first_name', 'last_name', 'email', 'student_id'];
        
        return $this->datatableResponse($query, $request, $columns);
    }
}
```

### Key Methods
- `successResponse($message, $data)` - Return success response
- `errorResponse($message, $errors)` - Return error response
- `handleAjaxRequest($callback)` - Handle request with automatic error handling
- `datatableResponse($query, $request, $columns)` - DataTables response
- `select2Response($query, $request)` - Select2 dropdown response

## BaseDashboardController

### Features
- Role-based dashboard statistics
- Widget management
- Common dashboard data preparation
- Attendance and grade calculations

### Usage Example
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseDashboardController;

class DashboardController extends BaseDashboardController
{
    public function index()
    {
        // Get base dashboard data
        $viewData = $this->getDashboardViewData();
        
        // Add custom data
        $viewData['customData'] = $this->getCustomData();
        
        return view('admin.dashboard', $viewData);
    }
    
    public function getStats(Request $request)
    {
        return $this->handleAjaxRequest(function() {
            return $this->getDashboardStatistics();
        });
    }
}
```

### Key Methods
- `getDashboardStatistics()` - Get role-based statistics
- `getDashboardViewData()` - Get complete dashboard data
- `renderDashboard($view)` - Render dashboard with standard data

## Response Formats

### Success Response
```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": {...},
    "code": "SUCCESS",
    "timestamp": "2024-01-01T12:00:00.000Z"
}
```

### Error Response
```json
{
    "success": false,
    "message": "An error occurred",
    "errors": {...},
    "code": "ERROR_CODE",
    "data": null,
    "timestamp": "2024-01-01T12:00:00.000Z"
}
```

### DataTables Response
```json
{
    "draw": 1,
    "recordsTotal": 100,
    "recordsFiltered": 50,
    "data": [...]
}
```

## Best Practices

1. **Always extend from appropriate base controller**
2. **Use handleAjaxRequest() for all AJAX endpoints**
3. **Apply school context filtering for data security**
4. **Use standardized response methods**
5. **Leverage common view data methods**
6. **Follow role-based access patterns**

## Migration Guide

To update existing controllers:

1. Change parent class from `Controller` to appropriate base controller
2. Replace manual response formatting with base methods
3. Use `handleAjaxRequest()` for AJAX endpoints
4. Apply school context filtering
5. Use common view data methods

Example migration:
```php
// Before
class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return view('students.index', compact('students'));
    }
}

// After
class StudentController extends BaseController
{
    public function index()
    {
        $query = Student::query();
        $students = $this->applySchoolContext($query)->get();
        
        $viewData = $this->getCommonViewData();
        $viewData['students'] = $students;
        
        return view('students.index', $viewData);
    }
}
```
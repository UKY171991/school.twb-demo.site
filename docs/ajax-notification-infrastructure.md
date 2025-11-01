# AJAX and Notification Infrastructure

## Overview

The multi-school management system includes comprehensive AJAX and notification infrastructure for seamless user experience and real-time communication.

## AJAX Infrastructure

### Core Components

#### 1. AjaxHandler Class (`public/js/app.js`)

**Features:**
- Automatic CSRF token handling
- Request retry logic with exponential backoff
- Global error handling and user feedback
- Loading state management
- Response validation and processing

**Usage:**
```javascript
// Simple request
const response = await window.App.ajax.request({
    url: '/api/endpoint',
    method: 'POST',
    data: { key: 'value' }
});

// With loading indicator
const response = await window.App.ajax.request({
    url: '/api/endpoint',
    loadingTarget: '#my-form',
    showNotifications: true
});
```

#### 2. FormHandler Class

**Features:**
- Automatic AJAX form submission
- Form validation integration
- Success/error handling
- Modal integration
- DataTable refresh

**Usage:**
```html
<form data-ajax="true" data-reset-on-success="true" data-refresh-table="users-table">
    <!-- form fields -->
</form>
```

#### 3. BaseAjaxController

**Features:**
- Standardized response formats
- Automatic error handling
- DataTables integration
- Select2 dropdown support
- School context filtering

**Usage:**
```php
class MyController extends BaseAjaxController
{
    public function store(Request $request)
    {
        return $this->handleAjaxRequest(function() use ($request) {
            // Your logic here
            return $this->successResponse('Success message', $data);
        });
    }
}
```

### Response Formats

#### Success Response
```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": {...},
    "code": "SUCCESS",
    "timestamp": "2024-01-01T12:00:00.000Z"
}
```

#### Error Response
```json
{
    "success": false,
    "message": "Error message",
    "errors": {...},
    "code": "ERROR_CODE",
    "data": null,
    "timestamp": "2024-01-01T12:00:00.000Z"
}
```

#### Validation Error Response
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "field_name": ["Error message"]
    },
    "code": "VALIDATION_ERROR"
}
```

## Notification System

### Database Structure

#### SystemNotification Model

**Fields:**
- `id` - Primary key
- `school_id` - Foreign key to schools (nullable)
- `user_id` - Foreign key to users (nullable)
- `title` - Notification title
- `message` - Notification content
- `type` - Notification type (info, success, warning, error)
- `data` - Additional JSON data
- `is_read` - Read status
- `read_at` - Read timestamp
- `action_url` - URL to navigate when clicked
- `icon` - Custom icon class

### NotificationService

#### Core Methods

```php
// Send to specific user
NotificationService::sendToUser(
    $userId, 
    'Title', 
    'Message', 
    'success'
);

// Send to all users in school
NotificationService::sendToSchool(
    $schoolId, 
    'Title', 
    'Message'
);

// Send to users by role
NotificationService::sendToRole(
    'teacher', 
    'Title', 
    'Message', 
    'info',
    $schoolId
);

// Get notifications for user
$notifications = NotificationService::getForUser($userId, 10);

// Mark as read
NotificationService::markAsRead($notificationId, $userId);
```

#### System Event Notifications

**Automatic notifications for:**
- Student enrollment
- Teacher assignments
- Grade entries
- Attendance marking
- System maintenance
- School announcements

### Frontend Integration

#### Notification Manager (`public/js/app.js`)

**Features:**
- Real-time notification loading
- Automatic UI updates
- Toastr integration
- Periodic refresh
- Badge count management

**Usage:**
```javascript
// Load notifications
window.App.notifications.loadNotifications();

// Update UI
window.App.notifications.updateNotificationUI(notifications);
```

#### Toastr Configuration

**Default settings:**
- Position: top-right
- Timeout: 5 seconds
- Progress bar: enabled
- Close button: enabled
- Prevent duplicates: enabled

## Validation Infrastructure

### ValidationService

#### Server-Side Validation

**Features:**
- Pre-defined validation rules for all entities
- Common validation patterns
- AJAX-friendly error responses
- Multi-language support

**Usage:**
```php
// Validate request
$validated = ValidationService::validateRequest(
    $request, 
    ValidationService::getStudentRules()
);

// Validate data array
$validated = ValidationService::validateData(
    $data, 
    ValidationService::getUserRules()
);
```

#### Client-Side Validation

**Features:**
- jQuery Validation Plugin integration
- Real-time validation
- Custom validation methods
- Bootstrap 4 styling
- Select2 integration

**Usage:**
```html
<form data-validate="true" data-rules="student">
    <!-- form fields -->
</form>
```

**Custom validation methods:**
- `phone` - Phone number validation
- `strongPassword` - Strong password requirements
- `notFuture` - Date not in future
- `notPast` - Date not in past
- `filesize` - File size validation
- `extension` - File extension validation
- `unique` - AJAX unique field validation

### Form Validation Integration

#### Automatic Validation

```javascript
// Initialize validation
window.FormValidator.initializeForm('#my-form', rules, messages);

// Validate programmatically
const isValid = window.FormValidator.validateForm('#my-form');

// Handle server errors
window.FormValidator.handleServerErrors(errors, '#my-form');
```

## DataTables Integration

### DataTable Component

**Features:**
- Server-side processing
- AJAX data loading
- Export buttons (Excel, PDF, CSV)
- Responsive design
- Search and filtering
- Custom action buttons

**Usage:**
```blade
<x-datatable 
    id="users-table"
    ajax-url="{{ route('ajax.users.datatable') }}"
    :columns="$columns"
    :buttons="true"
    :responsive="true"
/>
```

### DataTable Helper Class

**Features:**
- Automatic initialization
- Action button handling
- Refresh functionality
- Loading states

**Usage:**
```javascript
// Refresh table
window.refreshDataTable('users-table');

// Handle action buttons
<button data-action="delete" data-url="/users/1" data-method="DELETE" data-confirm="Are you sure?">
    Delete
</button>
```

## Error Handling

### Global Error Handling

**AJAX Errors:**
- Network errors
- Authentication errors (401, 419)
- Authorization errors (403)
- Validation errors (422)
- Server errors (500+)

**User Feedback:**
- Toastr notifications
- Form field highlighting
- Loading state management
- Retry mechanisms

### Logging

**Client-Side:**
- Console logging for debugging
- Error tracking for production

**Server-Side:**
- Laravel logging integration
- Error context preservation
- User action tracking

## Performance Optimization

### AJAX Optimization

**Features:**
- Request caching
- Debounced requests
- Connection pooling
- Timeout management
- Retry logic

### Notification Optimization

**Features:**
- Periodic loading (30 seconds)
- Efficient database queries
- Indexed database fields
- Automatic cleanup of old notifications

### Validation Optimization

**Features:**
- Client-side validation first
- Debounced real-time validation
- Cached validation rules
- Minimal server requests

## Security Considerations

### CSRF Protection
- Automatic token inclusion
- Token refresh on expiry
- Secure token storage

### Input Validation
- Server-side validation always enforced
- Client-side validation for UX only
- SQL injection prevention
- XSS protection

### Authorization
- Role-based access control
- School context validation
- Permission checking
- Audit logging

## Best Practices

### AJAX Requests
1. Always use the AjaxHandler class
2. Provide loading indicators
3. Handle all error cases
4. Use appropriate HTTP methods
5. Include proper error messages

### Notifications
1. Use appropriate notification types
2. Keep messages concise and clear
3. Include action URLs when relevant
4. Clean up old notifications regularly
5. Respect user preferences

### Validation
1. Validate on both client and server
2. Use consistent validation rules
3. Provide clear error messages
4. Handle edge cases gracefully
5. Test with various input types

### Performance
1. Minimize AJAX requests
2. Use caching where appropriate
3. Implement proper loading states
4. Optimize database queries
5. Monitor performance metrics
#!/usr/bin/env php
<?php

/**
 * Model Generator Script
 * This script generates all the missing models for the school management system
 */

$models = [
    // Administrator Module
    ['name' => 'PaymentSetting', 'table' => 'payment_settings', 'relations' => ['school']],
    ['name' => 'SmsSetting', 'table' => 'sms_settings', 'relations' => ['school']],
    ['name' => 'EmailSetting', 'table' => 'email_settings', 'relations' => ['school']],
    ['name' => 'UserCredentialsLog', 'table' => 'user_credentials_log', 'relations' => ['user', 'changedBy']],
    ['name' => 'BackupLog', 'table' => 'backup_logs', 'relations' => ['createdBy']],
    ['name' => 'OpeningHour', 'table' => 'opening_hours', 'relations' => ['school']],
    
    // Template Module
    ['name' => 'SmsTemplate', 'table' => 'sms_templates', 'relations' => ['school', 'logs']],
    ['name' => 'EmailTemplate', 'table' => 'email_templates', 'relations' => ['school', 'logs']],
    ['name' => 'SmsLog', 'table' => 'sms_logs', 'relations' => ['school', 'user', 'template']],
    ['name' => 'EmailLog', 'table' => 'email_logs', 'relations' => ['school', 'user', 'template']],
    
    // Front Office Module
    ['name' => 'VisitorPurpose', 'table' => 'visitor_purposes', 'relations' => ['school', 'visitors']],
    ['name' => 'Visitor', 'table' => 'visitors', 'relations' => ['school', 'purpose', 'createdBy']],
    ['name' => 'CallLog', 'table' => 'call_logs', 'relations' => ['school', 'createdBy']],
    ['name' => 'PostalDispatch', 'table' => 'postal_dispatches', 'relations' => ['school', 'createdBy']],
    ['name' => 'PostalReceive', 'table' => 'postal_receives', 'relations' => ['school', 'createdBy']],
    
    // Human Resource Module
    ['name' => 'Designation', 'table' => 'designations', 'relations' => ['school', 'employees']],
    ['name' => 'Employee', 'table' => 'employees', 'relations' => ['school', 'user', 'designation', 'attendance']],
    ['name' => 'EmployeeAttendance', 'table' => 'employee_attendance', 'relations' => ['school', 'employee', 'markedBy']],
    ['name' => 'Department', 'table' => 'departments', 'relations' => ['school', 'head', 'teachers']],
    
    // Leave Module
    ['name' => 'LeaveType', 'table' => 'leave_types', 'relations' => ['school', 'applications']],
    ['name' => 'LeaveApplication', 'table' => 'leave_applications', 'relations' => ['school', 'user', 'leaveType', 'approvedBy']],
    
    // Teacher Module
    ['name' => 'ClassLecture', 'table' => 'class_lectures', 'relations' => ['school', 'class', 'subject', 'teacher']],
    ['name' => 'TeacherRating', 'table' => 'teacher_ratings', 'relations' => ['school', 'teacher', 'student', 'class', 'subject', 'ratedBy']],
    
    // Academic Module
    ['name' => 'CourseMaterial', 'table' => 'course_materials', 'relations' => ['school', 'class', 'subject', 'teacher']],
    ['name' => 'LiveClass', 'table' => 'live_classes', 'relations' => ['school', 'class', 'subject', 'teacher']],
    ['name' => 'Syllabus', 'table' => 'syllabi', 'relations' => ['school', 'class', 'subject', 'academicYear', 'createdBy']],
    ['name' => 'AssignmentSubmission', 'table' => 'assignment_submissions', 'relations' => ['assignment', 'student', 'gradedBy']],
    ['name' => 'StudentType', 'table' => 'student_types', 'relations' => ['school', 'students']],
    ['name' => 'OnlineAdmission', 'table' => 'online_admissions', 'relations' => ['school', 'class', 'reviewedBy']],
    
    // Exam Module
    ['name' => 'ExamType', 'table' => 'exam_types', 'relations' => ['school', 'exams']],
    ['name' => 'Exam', 'table' => 'exams', 'relations' => ['school', 'examType', 'academicYear', 'schedules']],
    ['name' => 'ExamSchedule', 'table' => 'exam_schedules', 'relations' => ['exam', 'class', 'subject', 'marks']],
    ['name' => 'ExamHall', 'table' => 'exam_halls', 'relations' => ['school']],
    ['name' => 'ExamMark', 'table' => 'exam_marks', 'relations' => ['examSchedule', 'student', 'enteredBy']],
    ['name' => 'MarksGrade', 'table' => 'marks_grades', 'relations' => ['school']],
    ['name' => 'QuestionBank', 'table' => 'question_banks', 'relations' => ['school', 'subject', 'class', 'createdBy']],
    ['name' => 'OnlineExam', 'table' => 'online_exams', 'relations' => ['school', 'class', 'subject', 'createdBy', 'questions', 'results']],
    ['name' => 'OnlineExamQuestion', 'table' => 'online_exam_questions', 'relations' => ['onlineExam', 'questionBank']],
    ['name' => 'OnlineExamResult', 'table' => 'online_exam_results', 'relations' => ['onlineExam', 'student']],
    
    // Library Module
    ['name' => 'BookCategory', 'table' => 'book_categories', 'relations' => ['school', 'books']],
    ['name' => 'Book', 'table' => 'books', 'relations' => ['school', 'category', 'issues']],
    ['name' => 'BookIssue', 'table' => 'book_issues', 'relations' => ['book', 'issuedBy', 'returnedTo']],
    
    // Transport Module
    ['name' => 'TransportRoute', 'table' => 'transport_routes', 'relations' => ['school', 'vehicles', 'students']],
    ['name' => 'Vehicle', 'table' => 'vehicles', 'relations' => ['school', 'routes']],
    ['name' => 'RouteVehicle', 'table' => 'route_vehicles', 'relations' => ['route', 'vehicle']],
    ['name' => 'StudentTransport', 'table' => 'student_transport', 'relations' => ['student', 'route']],
    
    // Inventory Module
    ['name' => 'InventoryCategory', 'table' => 'inventory_categories', 'relations' => ['school', 'items']],
    ['name' => 'InventoryItem', 'table' => 'inventory_items', 'relations' => ['school', 'category', 'stock', 'issues']],
    ['name' => 'InventoryStock', 'table' => 'inventory_stock', 'relations' => ['item', 'createdBy']],
    ['name' => 'InventoryIssue', 'table' => 'inventory_issues', 'relations' => ['item', 'issuedBy']],
    
    // Accounting Module
    ['name' => 'ChartOfAccount', 'table' => 'chart_of_accounts', 'relations' => ['school', 'parent', 'children', 'incomes', 'expenses']],
    ['name' => 'Income', 'table' => 'incomes', 'relations' => ['school', 'account', 'createdBy']],
    ['name' => 'Expense', 'table' => 'expenses', 'relations' => ['school', 'account', 'createdBy']],
    ['name' => 'Invoice', 'table' => 'invoices', 'relations' => ['school', 'student', 'items', 'payments', 'createdBy']],
    ['name' => 'InvoiceItem', 'table' => 'invoice_items', 'relations' => ['invoice']],
    ['name' => 'Payment', 'table' => 'payments', 'relations' => ['school', 'invoice', 'receivedBy']],
    
    // Payroll Module
    ['name' => 'SalaryTemplate', 'table' => 'salary_templates', 'relations' => ['school']],
    ['name' => 'SalaryPayment', 'table' => 'salary_payments', 'relations' => ['school', 'processedBy']],
    
    // Other Modules
    ['name' => 'ComplainType', 'table' => 'complain_types', 'relations' => ['school', 'complains']],
    ['name' => 'Complain', 'table' => 'complains', 'relations' => ['school', 'complainType', 'assignedTo']],
    ['name' => 'Certificate', 'table' => 'certificates', 'relations' => ['school', 'student', 'issuedBy']],
];

echo "Total models to generate: " . count($models) . "\n\n";

foreach ($models as $model) {
    echo "Generating model: {$model['name']}\n";
    exec("php artisan make:model {$model['name']} 2>&1", $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "  ✓ Created successfully\n";
    } else {
        echo "  ✗ Failed: " . implode("\n", $output) . "\n";
    }
}

echo "\n✅ Model generation complete!\n";
echo "\nNext steps:\n";
echo "1. Add relationships to each model\n";
echo "2. Add fillable properties\n";
echo "3. Add casts where needed\n";
echo "4. Add scopes for common queries\n";

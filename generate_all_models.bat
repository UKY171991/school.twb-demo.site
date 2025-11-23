@echo off
echo Generating all missing models...
echo.

REM Administrator Module
php artisan make:model PaymentSetting
php artisan make:model SmsSetting
php artisan make:model EmailSetting
php artisan make:model UserCredentialsLog
php artisan make:model BackupLog
php artisan make:model OpeningHour

REM Template Module
php artisan make:model SmsTemplate
php artisan make:model EmailTemplate
php artisan make:model SmsLog
php artisan make:model EmailLog

REM Front Office Module
php artisan make:model VisitorPurpose
php artisan make:model Visitor
php artisan make:model CallLog
php artisan make:model PostalDispatch
php artisan make:model PostalReceive

REM Human Resource Module
php artisan make:model Designation
php artisan make:model Employee
php artisan make:model EmployeeAttendance
php artisan make:model Department

REM Leave Module
php artisan make:model LeaveType
php artisan make:model LeaveApplication

REM Teacher Module
php artisan make:model ClassLecture
php artisan make:model TeacherRating

REM Academic Module
php artisan make:model CourseMaterial
php artisan make:model LiveClass
php artisan make:model Syllabus
php artisan make:model AssignmentSubmission
php artisan make:model StudentType
php artisan make:model OnlineAdmission

REM Exam Module
php artisan make:model ExamType
php artisan make:model Exam
php artisan make:model ExamSchedule
php artisan make:model ExamHall
php artisan make:model ExamMark
php artisan make:model MarksGrade
php artisan make:model QuestionBank
php artisan make:model OnlineExam
php artisan make:model OnlineExamQuestion
php artisan make:model OnlineExamResult

REM Library Module
php artisan make:model BookCategory
php artisan make:model Book
php artisan make:model BookIssue

REM Transport Module
php artisan make:model TransportRoute
php artisan make:model Vehicle
php artisan make:model RouteVehicle
php artisan make:model StudentTransport

REM Inventory Module
php artisan make:model InventoryCategory
php artisan make:model InventoryItem
php artisan make:model InventoryStock
php artisan make:model InventoryIssue

REM Accounting Module
php artisan make:model ChartOfAccount
php artisan make:model Income
php artisan make:model Expense
php artisan make:model Invoice
php artisan make:model InvoiceItem
php artisan make:model Payment

REM Payroll Module
php artisan make:model SalaryTemplate
php artisan make:model SalaryPayment

REM Other Modules
php artisan make:model ComplainType
php artisan make:model Complain
php artisan make:model Certificate

echo.
echo ✅ All models generated successfully!
echo.
echo Next: Update each model with fillable properties and relationships

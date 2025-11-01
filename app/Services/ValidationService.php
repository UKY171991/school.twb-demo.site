<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidationService
{
    /**
     * Common validation rules for the system
     */
    const RULES = [
        'email' => 'required|email|max:255',
        'password' => 'required|min:8|max:255',
        'password_confirmation' => 'required|same:password',
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
        'date' => 'required|date',
        'datetime' => 'required|date_format:Y-m-d H:i:s',
        'boolean' => 'required|boolean',
        'integer' => 'required|integer',
        'decimal' => 'required|numeric',
        'file_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'file_document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
    ];

    /**
     * School-specific validation rules
     */
    public static function getSchoolRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schools,code',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'principal_name' => 'nullable|string|max:255',
            'principal_phone' => 'nullable|string|max:20',
            'principal_email' => 'nullable|email|max:255',
            'is_active' => 'required|boolean',
        ];
    }

    /**
     * User validation rules
     */
    public static function getUserRules(bool $isUpdate = false, ?int $userId = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email' . ($isUpdate && $userId ? ",$userId" : ''),
            'user_type' => 'required|in:super_admin,admin,teacher,student,parent',
            'school_id' => 'nullable|exists:schools,id',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'required|boolean',
        ];

        if (!$isUpdate) {
            $rules['password'] = 'required|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|min:8|confirmed';
        }

        return $rules;
    }

    /**
     * Student validation rules
     */
    public static function getStudentRules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'class_id' => 'nullable|exists:classes,id',
            'parent_id' => 'nullable|exists:parents,id',
            'student_id' => 'required|string|max:50|unique:students,student_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive,graduated,transferred',
            'admission_date' => 'required|date',
        ];
    }

    /**
     * Teacher validation rules
     */
    public static function getTeacherRules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'user_id' => 'required|exists:users,id',
            'employee_id' => 'required|string|max:50|unique:teachers,employee_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'qualification' => 'nullable|string|max:500',
            'experience' => 'nullable|integer|min:0',
            'joining_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive,terminated',
        ];
    }

    /**
     * Class validation rules
     */
    public static function getClassRules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:10',
            'teacher_id' => 'nullable|exists:teachers,id',
            'room_number' => 'nullable|string|max:50',
            'capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
        ];
    }

    /**
     * Subject validation rules
     */
    public static function getSubjectRules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'credits' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
        ];
    }

    /**
     * Attendance validation rules
     */
    public static function getAttendanceRules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string|max:500',
        ];
    }

    /**
     * Grade validation rules
     */
    public static function getGradeRules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'exam_type' => 'required|string|max:100',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
            'grade' => 'nullable|string|max:5',
            'remarks' => 'nullable|string|max:500',
            'exam_date' => 'required|date',
        ];
    }

    /**
     * Validate request data
     */
    public static function validateRequest(Request $request, array $rules, array $messages = []): array
    {
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Validate data array
     */
    public static function validateData(array $data, array $rules, array $messages = []): array
    {
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Get validation messages for AJAX responses
     */
    public static function getValidationMessages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'email' => 'The :attribute must be a valid email address.',
            'unique' => 'The :attribute has already been taken.',
            'exists' => 'The selected :attribute is invalid.',
            'min' => 'The :attribute must be at least :min characters.',
            'max' => 'The :attribute may not be greater than :max characters.',
            'confirmed' => 'The :attribute confirmation does not match.',
            'date' => 'The :attribute is not a valid date.',
            'before' => 'The :attribute must be a date before :date.',
            'after' => 'The :attribute must be a date after :date.',
            'boolean' => 'The :attribute field must be true or false.',
            'integer' => 'The :attribute must be an integer.',
            'numeric' => 'The :attribute must be a number.',
            'in' => 'The selected :attribute is invalid.',
            'image' => 'The :attribute must be an image.',
            'mimes' => 'The :attribute must be a file of type: :values.',
            'file' => 'The :attribute must be a file.',
            'url' => 'The :attribute format is invalid.',
        ];
    }

    /**
     * Get client-side validation rules for JavaScript
     */
    public static function getClientSideRules(string $type): array
    {
        $rules = [];

        switch ($type) {
            case 'school':
                $rules = [
                    'name' => ['required', 'maxlength' => 255],
                    'code' => ['required', 'maxlength' => 50],
                    'email' => ['email', 'maxlength' => 255],
                    'website' => ['url', 'maxlength' => 255],
                    'phone' => ['maxlength' => 20],
                ];
                break;

            case 'user':
                $rules = [
                    'name' => ['required', 'maxlength' => 255],
                    'email' => ['required', 'email', 'maxlength' => 255],
                    'password' => ['required', 'minlength' => 8],
                    'password_confirmation' => ['required', 'equalTo' => '#password'],
                    'phone' => ['maxlength' => 20],
                ];
                break;

            case 'student':
                $rules = [
                    'student_id' => ['required', 'maxlength' => 50],
                    'first_name' => ['required', 'maxlength' => 255],
                    'last_name' => ['required', 'maxlength' => 255],
                    'email' => ['email', 'maxlength' => 255],
                    'date_of_birth' => ['required', 'date'],
                    'admission_date' => ['required', 'date'],
                    'phone' => ['maxlength' => 20],
                ];
                break;

            case 'teacher':
                $rules = [
                    'employee_id' => ['required', 'maxlength' => 50],
                    'first_name' => ['required', 'maxlength' => 255],
                    'last_name' => ['required', 'maxlength' => 255],
                    'email' => ['email', 'maxlength' => 255],
                    'date_of_birth' => ['required', 'date'],
                    'joining_date' => ['required', 'date'],
                    'phone' => ['maxlength' => 20],
                ];
                break;
        }

        return $rules;
    }

    /**
     * Generate JavaScript validation code
     */
    public static function generateJavaScriptValidation(string $formId, array $rules): string
    {
        $jsRules = json_encode($rules, JSON_PRETTY_PRINT);
        
        return "
        $(document).ready(function() {
            $('#{$formId}').validate({
                rules: {$jsRules},
                messages: " . json_encode(self::getValidationMessages(), JSON_PRETTY_PRINT) . ",
                errorElement: 'div',
                errorClass: 'invalid-feedback',
                validClass: 'is-valid',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).addClass('is-valid').removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    if ($(form).data('ajax') === 'true') {
                        // Let AJAX handler take over
                        return false;
                    }
                    form.submit();
                }
            });
        });
        ";
    }
}
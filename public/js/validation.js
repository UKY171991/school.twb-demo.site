/**
 * Form Validation Helper
 */

class FormValidator {
    constructor() {
        this.setupDefaults();
        this.setupValidators();
    }

    setupDefaults() {
        // jQuery Validation Plugin defaults
        if (typeof $.validator !== 'undefined') {
            $.validator.setDefaults({
                errorElement: 'div',
                errorClass: 'invalid-feedback',
                validClass: 'is-valid',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    
                    // Handle different input types
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else if (element.hasClass('select2-hidden-accessible')) {
                        error.insertAfter(element.next('.select2-container'));
                    } else if (element.attr('type') === 'checkbox' || element.attr('type') === 'radio') {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                    
                    // Handle Select2
                    if ($(element).hasClass('select2-hidden-accessible')) {
                        $(element).next('.select2-container').find('.select2-selection').addClass('is-invalid');
                    }
                },
                unhighlight: function(element) {
                    $(element).addClass('is-valid').removeClass('is-invalid');
                    
                    // Handle Select2
                    if ($(element).hasClass('select2-hidden-accessible')) {
                        $(element).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
                    }
                },
                submitHandler: function(form) {
                    // Let AJAX handler take over for AJAX forms
                    if ($(form).data('ajax') === 'true') {
                        return false;
                    }
                    form.submit();
                }
            });
        }
    }

    setupValidators() {
        // Custom validation methods
        this.addCustomMethods();
        this.setupRealTimeValidation();
    }

    addCustomMethods() {
        if (typeof $.validator === 'undefined') return;

        // Phone number validation
        $.validator.addMethod('phone', function(value, element) {
            return this.optional(element) || /^[\+]?[0-9\s\-\(\)]{10,}$/.test(value);
        }, 'Please enter a valid phone number');

        // Strong password validation
        $.validator.addMethod('strongPassword', function(value, element) {
            return this.optional(element) || 
                   /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/.test(value);
        }, 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character');

        // Date not in future
        $.validator.addMethod('notFuture', function(value, element) {
            if (this.optional(element)) return true;
            const inputDate = new Date(value);
            const today = new Date();
            today.setHours(23, 59, 59, 999);
            return inputDate <= today;
        }, 'Date cannot be in the future');

        // Date not in past
        $.validator.addMethod('notPast', function(value, element) {
            if (this.optional(element)) return true;
            const inputDate = new Date(value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            return inputDate >= today;
        }, 'Date cannot be in the past');

        // File size validation
        $.validator.addMethod('filesize', function(value, element, param) {
            if (this.optional(element)) return true;
            if (!element.files || !element.files[0]) return true;
            return element.files[0].size <= param;
        }, 'File size must be less than {0} bytes');

        // File extension validation
        $.validator.addMethod('extension', function(value, element, param) {
            if (this.optional(element)) return true;
            const extensions = param.split('|');
            const extension = value.split('.').pop().toLowerCase();
            return extensions.includes(extension);
        }, 'Please select a file with a valid extension');

        // Unique field validation (AJAX)
        $.validator.addMethod('unique', function(value, element, param) {
            if (this.optional(element)) return true;
            
            let result = false;
            const data = {
                field: param.field || element.name,
                value: value,
                table: param.table,
                except: param.except || null
            };

            $.ajax({
                url: param.url || '/ajax/validate-unique',
                type: 'POST',
                data: data,
                async: false,
                success: function(response) {
                    result = response.valid;
                }
            });

            return result;
        }, 'This value is already taken');
    }

    setupRealTimeValidation() {
        // Real-time validation on blur
        $(document).on('blur', 'input, select, textarea', function() {
            const $form = $(this).closest('form');
            if ($form.data('validate') === 'true' && $form.valid) {
                $(this).valid();
            }
        });

        // Clear validation on focus
        $(document).on('focus', 'input.is-invalid, select.is-invalid, textarea.is-invalid', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });
    }

    /**
     * Initialize validation for a form
     */
    initializeForm(formSelector, rules = {}, messages = {}) {
        const $form = $(formSelector);
        
        if (!$form.length || typeof $.fn.validate === 'undefined') {
            return false;
        }

        const validator = $form.validate({
            rules: rules,
            messages: messages
        });

        // Store validator instance
        $form.data('validator', validator);
        
        return validator;
    }

    /**
     * Validate form programmatically
     */
    validateForm(formSelector) {
        const $form = $(formSelector);
        const validator = $form.data('validator');
        
        if (validator) {
            return validator.form();
        }
        
        return $form.valid ? $form.valid() : true;
    }

    /**
     * Clear form validation
     */
    clearValidation(formSelector) {
        const $form = $(formSelector);
        const validator = $form.data('validator');
        
        if (validator) {
            validator.resetForm();
        }
        
        $form.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
        $form.find('.invalid-feedback').remove();
    }

    /**
     * Add validation error to field
     */
    addFieldError(fieldName, message, formSelector = null) {
        let $field;
        
        if (formSelector) {
            $field = $(formSelector).find(`[name="${fieldName}"]`);
        } else {
            $field = $(`[name="${fieldName}"]`);
        }
        
        if ($field.length) {
            $field.addClass('is-invalid');
            
            // Remove existing error
            $field.next('.invalid-feedback').remove();
            
            // Add new error
            $field.after(`<div class="invalid-feedback">${message}</div>`);
        }
    }

    /**
     * Handle server validation errors
     */
    handleServerErrors(errors, formSelector = null) {
        Object.keys(errors).forEach(field => {
            const messages = Array.isArray(errors[field]) ? errors[field] : [errors[field]];
            this.addFieldError(field, messages[0], formSelector);
        });
    }

    /**
     * Get common validation rules
     */
    getCommonRules() {
        return {
            school: {
                name: { required: true, maxlength: 255 },
                code: { required: true, maxlength: 50 },
                email: { email: true, maxlength: 255 },
                website: { url: true, maxlength: 255 },
                phone: { phone: true, maxlength: 20 }
            },
            user: {
                name: { required: true, maxlength: 255 },
                email: { required: true, email: true, maxlength: 255 },
                password: { required: true, minlength: 8 },
                password_confirmation: { required: true, equalTo: '#password' },
                phone: { phone: true, maxlength: 20 }
            },
            student: {
                student_id: { required: true, maxlength: 50 },
                first_name: { required: true, maxlength: 255 },
                last_name: { required: true, maxlength: 255 },
                email: { email: true, maxlength: 255 },
                date_of_birth: { required: true, date: true, notFuture: true },
                admission_date: { required: true, date: true },
                phone: { phone: true, maxlength: 20 }
            },
            teacher: {
                employee_id: { required: true, maxlength: 50 },
                first_name: { required: true, maxlength: 255 },
                last_name: { required: true, maxlength: 255 },
                email: { email: true, maxlength: 255 },
                date_of_birth: { required: true, date: true, notFuture: true },
                joining_date: { required: true, date: true },
                phone: { phone: true, maxlength: 20 }
            }
        };
    }

    /**
     * Get common validation messages
     */
    getCommonMessages() {
        return {
            required: 'This field is required',
            email: 'Please enter a valid email address',
            minlength: 'Please enter at least {0} characters',
            maxlength: 'Please enter no more than {0} characters',
            equalTo: 'Please enter the same value again',
            date: 'Please enter a valid date',
            url: 'Please enter a valid URL',
            number: 'Please enter a valid number',
            digits: 'Please enter only digits',
            phone: 'Please enter a valid phone number'
        };
    }
}

// Auto-initialize validation for forms with data-validate="true"
$(document).ready(function() {
    window.FormValidator = new FormValidator();
    
    // Initialize forms with validation
    $('form[data-validate="true"]').each(function() {
        const $form = $(this);
        const rulesType = $form.data('rules');
        
        if (rulesType && window.FormValidator.getCommonRules()[rulesType]) {
            window.FormValidator.initializeForm(
                $form,
                window.FormValidator.getCommonRules()[rulesType],
                window.FormValidator.getCommonMessages()
            );
        }
    });
});

// Export for global access
window.FormValidator = window.FormValidator || FormValidator;
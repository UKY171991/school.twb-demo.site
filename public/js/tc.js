// Teacher (TC) JavaScript for AJAX functionality and notifications

$(document).ready(function() {
    // Initialize DataTables
    $('.datatable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "pageLength": 25,
        "language": {
            "search": "Search:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(filtered from _MAX_ total entries)",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        }
    });

    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Initialize date picker
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });

    // CSRF token setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Global AJAX error handler
    $(document).ajaxError(function(event, xhr, settings, thrownError) {
        if (xhr.status === 422) {
            // Validation errors
            const errors = xhr.responseJSON.errors;
            let errorMessage = 'Validation failed:\n';
            for (let field in errors) {
                errorMessage += errors[field][0] + '\n';
            }
            showToast('error', errorMessage);
        } else if (xhr.status === 403) {
            showToast('error', 'Access denied');
        } else if (xhr.status === 404) {
            showToast('error', 'Resource not found');
        } else if (xhr.status >= 500) {
            showToast('error', 'Server error occurred');
        } else {
            showToast('error', 'An error occurred: ' + thrownError);
        }
    });

    // Mark attendance functionality
    $(document).on('click', '.mark-attendance', function(e) {
        e.preventDefault();
        const classId = $(this).data('class-id');
        const date = $(this).data('date');
        
        // Show attendance modal or redirect to attendance page
        if (classId && date) {
            window.location.href = `/teacher/attendance/create?class_id=${classId}&date=${date}`;
        }
    });

    // Save attendance via AJAX
    $(document).on('submit', '.attendance-form', function(e) {
        e.preventDefault();
        const form = $(this);
        const formData = new FormData(this);
        
        $.ajax({
            url: '/ajax/teacher/attendance/mark',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                form.find('button[type="submit"]').prop('disabled', true).text('Saving...');
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    setTimeout(() => {
                        window.location.href = '/teacher/attendance';
                    }, 1000);
                } else {
                    showToast('error', response.message);
                }
            },
            complete: function() {
                form.find('button[type="submit"]').prop('disabled', false).text('Save Attendance');
            }
        });
    });

    // Save grade via AJAX
    $(document).on('submit', '.grade-form', function(e) {
        e.preventDefault();
        const form = $(this);
        const formData = new FormData(this);
        
        $.ajax({
            url: '/ajax/teacher/grades/save',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                form.find('button[type="submit"]').prop('disabled', true).text('Saving...');
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    setTimeout(() => {
                        window.location.href = '/teacher/grades';
                    }, 1000);
                } else {
                    showToast('error', response.message);
                }
            },
            complete: function() {
                form.find('button[type="submit"]').prop('disabled', false).text('Save Grade');
            }
        });
    });

    // Get students for a class
    $(document).on('change', '#class_id', function() {
        const classId = $(this).val();
        const date = $('#date').val();
        
        if (classId) {
            $.ajax({
                url: '/ajax/teacher/attendance/class-students',
                type: 'GET',
                data: { class_id: classId, date: date },
                dataType: 'json',
                beforeSend: function() {
                    $('#students-container').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading students...</div>');
                },
                success: function(response) {
                    if (response.success) {
                        $('#students-container').html(response.data);
                    } else {
                        showToast('error', response.message);
                        $('#students-container').html('<div class="alert alert-warning">No students found for this class.</div>');
                    }
                },
                error: function() {
                    showToast('error', 'Failed to load students');
                    $('#students-container').html('<div class="alert alert-danger">Failed to load students.</div>');
                }
            });
        } else {
            $('#students-container').empty();
        }
    });

    // Update attendance status
    $(document).on('change', '.attendance-status', function() {
        const attendanceId = $(this).data('attendance-id');
        const status = $(this).val();
        const remarks = $(this).closest('tr').find('.attendance-remarks').val();
        
        if (attendanceId && status) {
            $.ajax({
                url: '/ajax/teacher/attendance/update',
                type: 'POST',
                data: {
                    attendance_id: attendanceId,
                    status: status,
                    remarks: remarks
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                    } else {
                        showToast('error', response.message);
                    }
                }
            });
        }
    });

    // Schedule functionality
    $('.schedule-date').on('change', function() {
        const date = $(this).val();
        
        $.ajax({
            url: '/ajax/teacher/schedule/today',
            type: 'GET',
            data: { date: date },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('.schedule-container').html(response.data);
                }
            }
        });
    });

    // Profile update
    $(document).on('submit', '.profile-form', function(e) {
        e.preventDefault();
        const form = $(this);
        const formData = new FormData(this);
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                form.find('button[type="submit"]').prop('disabled', true).text('Updating...');
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showToast('error', response.message);
                }
            },
            complete: function() {
                form.find('button[type="submit"]').prop('disabled', false).text('Update Profile');
            }
        });
    });

    // Auto-save draft functionality
    let autoSaveTimer;
    $(document).on('input', '.auto-save', function() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(() => {
            const form = $(this).closest('form');
            const formData = new FormData(form[0]);
            
            $.ajax({
                url: form.attr('action') + '/draft',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showToast('info', 'Draft saved', '', 2000);
                    }
                }
            });
        }, 2000);
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Initialize popovers
    $('[data-toggle="popover"]').popover();

    // Real-time notifications
    setInterval(function() {
        $.ajax({
            url: '/ajax/teacher/notifications',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    response.data.forEach(notification => {
                        showToast('info', notification.message, notification.title);
                    });
                }
            }
        });
    }, 60000); // Check every minute
});

// Toast notification function
function showToast(type, message, title = '', timeout = 5000) {
    const options = {
        closeButton: true,
        debug: false,
        newestOnTop: false,
        progressBar: true,
        positionClass: "toast-top-right",
        preventDuplicates: false,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: timeout,
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
    };

    switch(type) {
        case 'success':
            toastr.success(message, title, options);
            break;
        case 'error':
            toastr.error(message, title, options);
            break;
        case 'warning':
            toastr.warning(message, title, options);
            break;
        case 'info':
            toastr.info(message, title, options);
            break;
        default:
            toastr.info(message, title, options);
    }
}

// Utility functions
function formatDate(date) {
    return new Date(date).toLocaleDateString();
}

function formatDateTime(date) {
    return new Date(date).toLocaleString();
}

function calculateGrade(obtained, total) {
    const percentage = (obtained / total) * 100;
    if (percentage >= 90) return 'A+';
    if (percentage >= 80) return 'A';
    if (percentage >= 70) return 'B';
    if (percentage >= 60) return 'C';
    if (percentage >= 50) return 'D';
    return 'F';
}

// Export functions for use in other scripts
window.TCJS = {
    showToast: showToast,
    formatDate: formatDate,
    formatDateTime: formatDateTime,
    calculateGrade: calculateGrade
};

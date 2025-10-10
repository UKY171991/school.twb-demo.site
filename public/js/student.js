// Student JavaScript for AJAX functionality and notifications

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

    // Load dashboard stats
    loadDashboardStats();

    // Load today's attendance status
    loadTodayAttendance();

    // Filter functionality
    $('.filter-select').on('change', function() {
        const filter = $(this).val();
        const target = $(this).data('target');
        
        if (filter) {
            $(target).find('.item').hide();
            $(target).find(`.item[data-${$(this).data('filter')}="${filter}"]`).show();
        } else {
            $(target).find('.item').show();
        }
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

    // Load attendance chart
    loadAttendanceChart();

    // Load grades chart
    loadGradesChart();

    // Subject details modal
    $(document).on('click', '.subject-details', function(e) {
        e.preventDefault();
        const subjectId = $(this).data('subject-id');
        
        $.ajax({
            url: '/ajax/student/subject-details',
            type: 'GET',
            data: { subject_id: subjectId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#subjectModal .modal-body').html(response.data);
                    $('#subjectModal').modal('show');
                } else {
                    showToast('error', response.message);
                }
            }
        });
    });

    // Refresh data every 5 minutes
    setInterval(function() {
        loadDashboardStats();
        loadTodayAttendance();
    }, 300000);

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Initialize popovers
    $('[data-toggle="popover"]').popover();
});

// Load dashboard statistics
function loadDashboardStats() {
    $.ajax({
        url: '/ajax/student/dashboard/stats',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const stats = response.data;
                $('#attendance-rate').text(stats.attendance_rate + '%');
                $('#total-subjects').text(stats.total_subjects);
                $('#average-grade').text(stats.average_grade);
                $('#total-assignments').text(stats.total_assignments);
            }
        }
    });
}

// Load today's attendance status
function loadTodayAttendance() {
    $.ajax({
        url: '/ajax/student/attendance/today',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                const statusBadge = $('#today-attendance-status');
                statusBadge.removeClass('badge-success badge-danger badge-warning badge-info')
                    .addClass(getStatusBadgeClass(data.status))
                    .text(data.status.toUpperCase());
                
                if (data.remarks) {
                    statusBadge.attr('title', data.remarks);
                }
            }
        }
    });
}

// Load attendance chart
function loadAttendanceChart() {
    $.ajax({
        url: '/ajax/student/attendance/monthly',
        type: 'GET',
        data: { year: new Date().getFullYear() },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                const labels = data.map(item => getMonthName(item.month));
                const attendanceRates = data.map(item => item.percentage);
                
                const ctx = document.getElementById('attendanceChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Attendance Rate (%)',
                            data: attendanceRates,
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
            }
        }
    });
}

// Load grades chart
function loadGradesChart() {
    $.ajax({
        url: '/ajax/student/grades/statistics',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                const monthlyData = data.monthly_data;
                const labels = monthlyData.map(item => getMonthName(item.month));
                const averageMarks = monthlyData.map(item => item.average_marks);
                
                const ctx = document.getElementById('gradesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Average Marks',
                            data: averageMarks,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }
    });
}

// Get status badge class
function getStatusBadgeClass(status) {
    switch(status) {
        case 'present':
            return 'badge-success';
        case 'absent':
            return 'badge-danger';
        case 'late':
            return 'badge-warning';
        case 'excused':
            return 'badge-info';
        default:
            return 'badge-secondary';
    }
}

// Get month name
function getMonthName(monthNumber) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return months[monthNumber - 1];
}

// Toast notification function
function showToast(type, message, title = '') {
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
        timeOut: "5000",
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

function getGradeColor(grade) {
    switch(grade) {
        case 'A+':
        case 'A':
            return 'text-success';
        case 'B':
            return 'text-primary';
        case 'C':
            return 'text-warning';
        case 'D':
        case 'F':
            return 'text-danger';
        default:
            return 'text-secondary';
    }
}

// Export functions for use in other scripts
window.StudentJS = {
    showToast: showToast,
    formatDate: formatDate,
    formatDateTime: formatDateTime,
    getGradeColor: getGradeColor,
    getStatusBadgeClass: getStatusBadgeClass
};

// Parent JavaScript for AJAX functionality and notifications

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

    // Child selector
    $('.child-selector').on('change', function() {
        const childId = $(this).val();
        if (childId) {
            loadChildStats(childId);
            updateCharts(childId);
        }
    });

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

    // Load attendance chart
    loadAttendanceChart();

    // Load grades chart
    loadGradesChart();

    // Child details modal
    $(document).on('click', '.child-details', function(e) {
        e.preventDefault();
        const childId = $(this).data('child-id');
        
        window.location.href = `/parent/children/${childId}`;
    });

    // Attendance details
    $(document).on('click', '.attendance-details', function(e) {
        e.preventDefault();
        const childId = $(this).data('child-id');
        
        $.ajax({
            url: '/ajax/parent/attendance/child-stats',
            type: 'GET',
            data: { student_id: childId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#attendanceModal .modal-body').html(response.data);
                    $('#attendanceModal').modal('show');
                } else {
                    showToast('error', response.message);
                }
            }
        });
    });

    // Grade details
    $(document).on('click', '.grade-details', function(e) {
        e.preventDefault();
        const childId = $(this).data('child-id');
        
        $.ajax({
            url: '/ajax/parent/grades/child-stats',
            type: 'GET',
            data: { student_id: childId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#gradeModal .modal-body').html(response.data);
                    $('#gradeModal').modal('show');
                } else {
                    showToast('error', response.message);
                }
            }
        });
    });

    // Refresh data every 5 minutes
    setInterval(function() {
        loadDashboardStats();
        const childId = $('.child-selector').val();
        if (childId) {
            loadChildStats(childId);
        }
    }, 300000);

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Initialize popovers
    $('[data-toggle="popover"]').popover();
});

// Load dashboard statistics
function loadDashboardStats() {
    $.ajax({
        url: '/ajax/parent/dashboard/stats',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const stats = response.data;
                $('#total-children').text(stats.total_children);
                $('#average-attendance').text(stats.average_attendance + '%');
                $('#average-grade').text(stats.average_grade);
                $('#total-activities').text(stats.total_activities);
            }
        }
    });
}

// Load child statistics
function loadChildStats(childId) {
    $.ajax({
        url: '/ajax/parent/child-stats',
        type: 'GET',
        data: { child_id: childId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const stats = response.data;
                $('#child-attendance-rate').text(stats.attendance_rate + '%');
                $('#child-average-grade').text(stats.average_grade);
                $('#child-total-grades').text(stats.total_grades);
                $('#child-highest-grade').text(stats.highest_grade);
                $('#child-lowest-grade').text(stats.lowest_grade);
            }
        }
    });
}

// Update charts based on selected child
function updateCharts(childId) {
    loadChildAttendanceChart(childId);
    loadChildGradesChart(childId);
}

// Load attendance chart
function loadAttendanceChart() {
    $.ajax({
        url: '/ajax/parent/attendance/monthly',
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
                            label: 'Average Attendance Rate (%)',
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

// Load child attendance chart
function loadChildAttendanceChart(childId) {
    $.ajax({
        url: '/ajax/parent/attendance/child-monthly',
        type: 'GET',
        data: { student_id: childId, year: new Date().getFullYear() },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                const labels = data.map(item => getMonthName(item.month));
                const attendanceRates = data.map(item => item.percentage);
                
                const ctx = document.getElementById('childAttendanceChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Attendance Rate (%)',
                            data: attendanceRates,
                            borderColor: 'rgb(255, 99, 132)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
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
        url: '/ajax/parent/grades/monthly',
        type: 'GET',
        data: { year: new Date().getFullYear() },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                const labels = data.map(item => getMonthName(item.month));
                const averageMarks = data.map(item => item.average_marks);
                
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

// Load child grades chart
function loadChildGradesChart(childId) {
    $.ajax({
        url: '/ajax/parent/grades/child-monthly',
        type: 'GET',
        data: { student_id: childId, year: new Date().getFullYear() },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                const labels = data.map(item => getMonthName(item.month));
                const averageMarks = data.map(item => item.average_marks);
                
                const ctx = document.getElementById('childGradesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Average Marks',
                            data: averageMarks,
                            backgroundColor: 'rgba(255, 206, 86, 0.2)',
                            borderColor: 'rgba(255, 206, 86, 1)',
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

// Get month name
function getMonthName(monthNumber) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return months[monthNumber - 1];
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
window.ParentJS = {
    showToast: showToast,
    formatDate: formatDate,
    formatDateTime: formatDateTime,
    getGradeColor: getGradeColor,
    getStatusBadgeClass: getStatusBadgeClass
};

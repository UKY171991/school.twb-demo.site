// Admin JavaScript for AJAX functionality and notifications

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

    // Toggle status functionality
    $(document).on('click', '.toggle-status', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        const button = $(this);
        
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                button.prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    // Update button text and icon
                    const isActive = response.is_active;
                    button.find('i').removeClass('fa-toggle-off fa-toggle-on')
                        .addClass(isActive ? 'fa-toggle-on' : 'fa-toggle-off');
                    button.find('span').text(isActive ? 'Active' : 'Inactive');
                    button.removeClass('btn-success btn-danger')
                        .addClass(isActive ? 'btn-success' : 'btn-danger');
                } else {
                    showToast('error', response.message);
                }
            },
            error: function() {
                showToast('error', 'Failed to update status');
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    });

    // Delete confirmation
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        const name = $(this).data('name') || 'item';
        
        if (confirm(`Are you sure you want to delete this ${name}?`)) {
            $.ajax({
                url: url,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                        // Reload the page or remove the row
                        location.reload();
                    } else {
                        showToast('error', response.message);
                    }
                }
            });
        }
    });

    // Form submission with AJAX
    $(document).on('submit', '.ajax-form', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const method = form.find('input[name="_method"]').val() || 'POST';
        const formData = new FormData(this);
        
        // Add method override for PUT/PATCH/DELETE
        if (method !== 'POST') {
            formData.append('_method', method);
        }
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                form.find('button[type="submit"]').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        // Reset form or redirect
                        form[0].reset();
                        location.reload();
                    }
                } else {
                    showToast('error', response.message);
                }
            },
            complete: function() {
                form.find('button[type="submit"]').prop('disabled', false);
            }
        });
    });

    // Search functionality
    $('.search-input').on('input', debounce(function() {
        const query = $(this).val();
        const url = $(this).data('url');
        const target = $(this).data('target');
        
        if (query.length >= 2) {
            $.ajax({
                url: url,
                type: 'GET',
                data: { query: query },
                success: function(response) {
                    if (response.success) {
                        $(target).html(response.data);
                    }
                }
            });
        }
    }, 300));

    // File upload with progress
    $(document).on('change', '.file-input', function() {
        const file = this.files[0];
        const uploadUrl = $(this).data('upload-url');
        const preview = $(this).data('preview');
        
        if (file && uploadUrl) {
            const formData = new FormData();
            formData.append('file', file);
            
            $.ajax({
                url: uploadUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                xhr: function() {
                    const xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            const percentComplete = evt.loaded / evt.total * 100;
                            $('.progress-bar').css('width', percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                        if (preview && response.data.url) {
                            $(preview).attr('src', response.data.url).show();
                        }
                    } else {
                        showToast('error', response.message);
                    }
                }
            });
        }
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Initialize popovers
    $('[data-toggle="popover"]').popover();

    // Auto-refresh data every 30 seconds
    setInterval(function() {
        $('.auto-refresh').each(function() {
            const url = $(this).data('url');
            if (url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('.auto-refresh').html(response.data);
                        }
                    }
                });
            }
        });
    }, 30000);
});

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

// Debounce function for search
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            timeout = null;
            if (!immediate) func(...args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func(...args);
    };
}

// Utility functions
function formatDate(date) {
    return new Date(date).toLocaleDateString();
}

function formatDateTime(date) {
    return new Date(date).toLocaleString();
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

// Export functions for use in other scripts
window.AdminJS = {
    showToast: showToast,
    formatDate: formatDate,
    formatDateTime: formatDateTime,
    formatCurrency: formatCurrency
};

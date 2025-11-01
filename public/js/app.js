/**
 * Multi-School Management System - Main JavaScript Application
 */

// Global App Configuration
window.App = window.App || {};

// Extend App configuration
Object.assign(window.App, {
    // AJAX Configuration
    ajax: {
        timeout: 30000,
        retryAttempts: 3,
        retryDelay: 1000
    },
    
    // UI Configuration
    ui: {
        loadingClass: 'ajax-loading',
        disabledClass: 'disabled',
        fadeSpeed: 300
    },
    
    // Notification Configuration
    notifications: {
        position: 'toast-top-right',
        timeout: 5000,
        showProgress: true
    }
});

/**
 * AJAX Handler Class
 */
class AjaxHandler {
    constructor() {
        this.setupDefaults();
        this.setupInterceptors();
    }

    setupDefaults() {
        // Set default AJAX settings
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': window.App.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            timeout: window.App.ajax.timeout,
            cache: false
        });
    }

    setupInterceptors() {
        // Global AJAX start handler
        $(document).ajaxStart(() => {
            this.showGlobalLoading();
        });

        // Global AJAX stop handler
        $(document).ajaxStop(() => {
            this.hideGlobalLoading();
        });

        // Global AJAX error handler
        $(document).ajaxError((event, xhr, settings, thrownError) => {
            this.handleGlobalError(xhr, settings, thrownError);
        });
    }

    /**
     * Make an AJAX request with enhanced error handling and retry logic
     */
    async request(options) {
        const defaults = {
            method: 'GET',
            dataType: 'json',
            retries: window.App.ajax.retryAttempts,
            showLoading: true,
            showNotifications: true,
            validateResponse: true
        };

        const config = Object.assign({}, defaults, options);
        
        if (config.showLoading && config.loadingTarget) {
            this.showLoading(config.loadingTarget);
        }

        try {
            const response = await this.makeRequest(config);
            
            if (config.validateResponse) {
                this.validateResponse(response);
            }

            if (config.showNotifications && response.message) {
                this.showNotification(response.success ? 'success' : 'error', response.message);
            }

            return response;
        } catch (error) {
            if (config.showNotifications) {
                this.handleError(error);
            }
            throw error;
        } finally {
            if (config.showLoading && config.loadingTarget) {
                this.hideLoading(config.loadingTarget);
            }
        }
    }

    /**
     * Make the actual AJAX request with retry logic
     */
    async makeRequest(config, attempt = 1) {
        try {
            return await $.ajax({
                url: config.url,
                method: config.method,
                data: config.data,
                dataType: config.dataType,
                processData: config.processData !== false,
                contentType: config.contentType || 'application/x-www-form-urlencoded; charset=UTF-8',
                timeout: config.timeout || window.App.ajax.timeout
            });
        } catch (error) {
            if (attempt < config.retries && this.isRetryableError(error)) {
                await this.delay(window.App.ajax.retryDelay * attempt);
                return this.makeRequest(config, attempt + 1);
            }
            throw error;
        }
    }

    /**
     * Validate AJAX response structure
     */
    validateResponse(response) {
        if (typeof response !== 'object') {
            throw new Error('Invalid response format');
        }

        if (response.redirect) {
            window.location.href = response.redirect;
            return;
        }

        if (response.reload) {
            window.location.reload();
            return;
        }
    }

    /**
     * Check if error is retryable
     */
    isRetryableError(error) {
        const retryableStatuses = [0, 408, 429, 500, 502, 503, 504];
        return retryableStatuses.includes(error.status);
    }

    /**
     * Handle AJAX errors
     */
    handleError(error) {
        let message = 'An unexpected error occurred';
        
        if (error.responseJSON) {
            message = error.responseJSON.message || message;
        } else if (error.status === 0) {
            message = 'Network connection error. Please check your internet connection.';
        } else if (error.status === 401) {
            message = 'Session expired. Please log in again.';
            setTimeout(() => window.location.href = '/login', 2000);
        } else if (error.status === 403) {
            message = 'Access denied. You do not have permission to perform this action.';
        } else if (error.status === 404) {
            message = 'The requested resource was not found.';
        } else if (error.status === 419) {
            message = 'Session expired. Please refresh the page.';
            setTimeout(() => window.location.reload(), 2000);
        } else if (error.status === 422) {
            message = 'Validation failed. Please check your input.';
            if (error.responseJSON && error.responseJSON.errors) {
                this.handleValidationErrors(error.responseJSON.errors);
                return;
            }
        } else if (error.status >= 500) {
            message = 'Server error. Please try again later.';
        }

        this.showNotification('error', message);
    }

    /**
     * Handle global AJAX errors
     */
    handleGlobalError(xhr, settings, thrownError) {
        // Only handle if not already handled by specific request
        if (!settings.handled) {
            console.error('Global AJAX Error:', {
                url: settings.url,
                status: xhr.status,
                error: thrownError,
                response: xhr.responseText
            });
        }
    }

    /**
     * Handle validation errors
     */
    handleValidationErrors(errors) {
        // Clear previous validation errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Show field-specific errors
        Object.keys(errors).forEach(field => {
            const $field = $(`[name="${field}"]`);
            if ($field.length) {
                $field.addClass('is-invalid');
                $field.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
            } else {
                // Show as notification if field not found
                this.showNotification('error', `${field}: ${errors[field][0]}`);
            }
        });
    }

    /**
     * Show loading state
     */
    showLoading(target) {
        const $target = $(target);
        $target.addClass(window.App.ui.loadingClass);
        
        if (!$target.find('.loading-overlay').length) {
            $target.append(`
                <div class="loading-overlay">
                    <div class="loading-spinner"></div>
                </div>
            `);
        }
    }

    /**
     * Hide loading state
     */
    hideLoading(target) {
        const $target = $(target);
        $target.removeClass(window.App.ui.loadingClass);
        $target.find('.loading-overlay').remove();
    }

    /**
     * Show global loading indicator
     */
    showGlobalLoading() {
        if (!$('#global-loading').length) {
            $('body').append(`
                <div id="global-loading" class="position-fixed" style="top: 10px; right: 10px; z-index: 9999;">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            `);
        }
    }

    /**
     * Hide global loading indicator
     */
    hideGlobalLoading() {
        $('#global-loading').remove();
    }

    /**
     * Show notification
     */
    showNotification(type, message, title = null) {
        if (typeof toastr !== 'undefined') {
            toastr[type](message, title);
        } else {
            // Fallback to alert if toastr not available
            alert(`${type.toUpperCase()}: ${message}`);
        }
    }

    /**
     * Utility delay function
     */
    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
}

/**
 * Form Handler Class
 */
class FormHandler {
    constructor() {
        this.setupFormHandlers();
    }

    setupFormHandlers() {
        // Handle AJAX forms
        $(document).on('submit', 'form[data-ajax="true"]', (e) => {
            e.preventDefault();
            this.handleAjaxForm(e.target);
        });

        // Handle confirmation forms
        $(document).on('submit', 'form[data-confirm]', (e) => {
            const message = $(e.target).data('confirm');
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    }

    async handleAjaxForm(form) {
        const $form = $(form);
        const url = $form.attr('action');
        const method = $form.attr('method') || 'POST';
        const formData = new FormData(form);

        try {
            const response = await window.App.ajax.request({
                url: url,
                method: method,
                data: formData,
                processData: false,
                contentType: false,
                loadingTarget: form
            });

            // Handle successful response
            if (response.success) {
                // Reset form if specified
                if ($form.data('reset-on-success')) {
                    form.reset();
                }

                // Redirect if specified
                if (response.redirect) {
                    window.location.href = response.redirect;
                    return;
                }

                // Reload if specified
                if (response.reload) {
                    window.location.reload();
                    return;
                }

                // Refresh DataTable if specified
                const tableId = $form.data('refresh-table');
                if (tableId && window.refreshDataTable) {
                    window.refreshDataTable(tableId);
                }

                // Close modal if form is in modal
                const $modal = $form.closest('.modal');
                if ($modal.length) {
                    $modal.modal('hide');
                }
            }
        } catch (error) {
            // Error handling is done by AjaxHandler
        }
    }
}

/**
 * DataTable Helper Class
 */
class DataTableHelper {
    constructor() {
        this.setupDataTableDefaults();
        this.setupDataTableHandlers();
    }

    setupDataTableDefaults() {
        // Set DataTables defaults
        if (typeof $.fn.DataTable !== 'undefined') {
            $.extend(true, $.fn.dataTable.defaults, {
                responsive: true,
                processing: true,
                language: {
                    processing: '<i class="fas fa-spinner fa-spin"></i> Loading...',
                    emptyTable: 'No data available',
                    zeroRecords: 'No matching records found'
                },
                drawCallback: function() {
                    // Reinitialize tooltips and other UI elements
                    $('[data-toggle="tooltip"]').tooltip();
                    $('[data-toggle="popover"]').popover();
                }
            });
        }
    }

    setupDataTableHandlers() {
        // Handle DataTable action buttons
        $(document).on('click', '[data-action]', (e) => {
            e.preventDefault();
            this.handleDataTableAction(e.target);
        });
    }

    async handleDataTableAction(element) {
        const $element = $(element);
        const action = $element.data('action');
        const url = $element.attr('href') || $element.data('url');
        const method = $element.data('method') || 'GET';
        const confirm = $element.data('confirm');

        if (confirm && !window.confirm(confirm)) {
            return;
        }

        try {
            const response = await window.App.ajax.request({
                url: url,
                method: method,
                loadingTarget: element
            });

            // Refresh the DataTable
            const tableId = $element.data('table') || $element.closest('.dataTables_wrapper').find('table').attr('id');
            if (tableId && window.refreshDataTable) {
                window.refreshDataTable(tableId);
            }
        } catch (error) {
            // Error handling is done by AjaxHandler
        }
    }
}

/**
 * Notification Manager Class
 */
class NotificationManager {
    constructor() {
        this.setupToastr();
        this.loadNotifications();
        this.setupNotificationHandlers();
    }

    setupToastr() {
        if (typeof toastr !== 'undefined') {
            toastr.options = {
                closeButton: true,
                debug: false,
                newestOnTop: true,
                progressBar: window.App.notifications.showProgress,
                positionClass: window.App.notifications.position,
                preventDuplicates: true,
                onclick: null,
                showDuration: '300',
                hideDuration: '1000',
                timeOut: window.App.notifications.timeout.toString(),
                extendedTimeOut: '1000',
                showEasing: 'swing',
                hideEasing: 'linear',
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut'
            };
        }
    }

    async loadNotifications() {
        try {
            const response = await window.App.ajax.request({
                url: window.App.routes.ajax.notifications,
                showNotifications: false,
                showLoading: false
            });

            if (response.success) {
                this.updateNotificationUI(response.data);
            }
        } catch (error) {
            // Silently fail for notifications
        }
    }

    updateNotificationUI(notifications) {
        const unreadCount = notifications.filter(n => !n.is_read).length;
        const $badge = $('.notification-count');
        const $dropdown = $('#notifications-dropdown');

        // Update badge
        if (unreadCount > 0) {
            $badge.text(unreadCount).show();
        } else {
            $badge.hide();
        }

        // Update dropdown
        this.renderNotifications(notifications);
    }

    renderNotifications(notifications) {
        const $list = $('#notifications-list');
        
        if (notifications.length === 0) {
            $list.html('<div class="dropdown-item text-center text-muted">No notifications</div>');
            return;
        }

        let html = '';
        notifications.slice(0, 5).forEach(notification => {
            const icon = this.getNotificationIcon(notification.type);
            const time = moment(notification.created_at).fromNow();
            const readClass = notification.is_read ? 'text-muted' : '';
            
            html += `
                <a href="#" class="dropdown-item ${readClass}" data-notification-id="${notification.id}">
                    <i class="${icon} mr-2"></i> ${notification.title}
                    <span class="float-right text-muted text-sm">${time}</span>
                </a>
            `;
        });
        
        $list.html(html);
    }

    getNotificationIcon(type) {
        const icons = {
            success: 'fas fa-check text-success',
            warning: 'fas fa-exclamation-triangle text-warning',
            error: 'fas fa-times text-danger',
            info: 'fas fa-info-circle text-info'
        };
        return icons[type] || icons.info;
    }

    setupNotificationHandlers() {
        // Mark notification as read when clicked
        $(document).on('click', '[data-notification-id]', async (e) => {
            e.preventDefault();
            const notificationId = $(e.target).data('notification-id');
            
            try {
                await window.App.ajax.request({
                    url: window.App.routes.ajax.notifications + '/' + notificationId + '/read',
                    method: 'POST',
                    showNotifications: false,
                    showLoading: false
                });
                
                // Reload notifications
                this.loadNotifications();
            } catch (error) {
                // Silently fail
            }
        });

        // Refresh notifications periodically
        setInterval(() => {
            this.loadNotifications();
        }, 30000); // Every 30 seconds
    }
}

/**
 * Initialize Application
 */
$(document).ready(function() {
    // Initialize core classes
    window.App.ajax = new AjaxHandler();
    window.App.forms = new FormHandler();
    window.App.datatables = new DataTableHelper();
    window.App.notifications = new NotificationManager();

    // Initialize UI components
    initializeUIComponents();
    
    // Setup global event handlers
    setupGlobalHandlers();
});

/**
 * Initialize UI Components
 */
function initializeUIComponents() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Initialize popovers
    $('[data-toggle="popover"]').popover();
    
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
    
    // Initialize date pickers
    if (typeof $.fn.datetimepicker !== 'undefined') {
        $('.datepicker').datetimepicker({
            format: 'YYYY-MM-DD',
            icons: {
                time: 'far fa-clock',
                date: 'far fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-check',
                clear: 'far fa-trash-alt',
                close: 'far fa-times-circle'
            }
        });
    }
}

/**
 * Setup Global Event Handlers
 */
function setupGlobalHandlers() {
    // Handle dynamic content loading
    $(document).on('click', '[data-load-content]', function(e) {
        e.preventDefault();
        const url = $(this).data('load-content');
        const target = $(this).data('target');
        loadContent(url, target);
    });
    
    // Handle modal loading
    $(document).on('click', '[data-modal-url]', function(e) {
        e.preventDefault();
        const url = $(this).data('modal-url');
        loadModal(url);
    });
    
    // Auto-save forms
    $(document).on('input change', 'form[data-auto-save]', debounce(function() {
        autoSaveForm(this);
    }, 1000));
}

/**
 * Load content dynamically
 */
async function loadContent(url, target) {
    try {
        const response = await window.App.ajax.request({
            url: url,
            loadingTarget: target
        });
        
        if (response.success && response.html) {
            $(target).html(response.html);
        }
    } catch (error) {
        $(target).html('<div class="alert alert-danger">Failed to load content</div>');
    }
}

/**
 * Load modal content
 */
async function loadModal(url) {
    try {
        const response = await window.App.ajax.request({
            url: url,
            showLoading: false
        });
        
        if (response.success && response.modal) {
            showModal(response.modal);
        }
    } catch (error) {
        // Error handled by AjaxHandler
    }
}

/**
 * Show modal
 */
function showModal(modalData) {
    const modalId = 'dynamic-modal';
    let $modal = $('#' + modalId);
    
    if (!$modal.length) {
        $modal = $(`
            <div class="modal fade" id="${modalId}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body"></div>
                    </div>
                </div>
            </div>
        `);
        $('body').append($modal);
    }
    
    $modal.find('.modal-title').text(modalData.title || '');
    $modal.find('.modal-body').html(modalData.html || '');
    $modal.modal('show');
}

/**
 * Auto-save form
 */
async function autoSaveForm(form) {
    const $form = $(form);
    const url = $form.data('auto-save-url') || $form.attr('action');
    
    if (!url) return;
    
    try {
        await window.App.ajax.request({
            url: url,
            method: 'POST',
            data: new FormData(form),
            processData: false,
            contentType: false,
            showNotifications: false,
            showLoading: false
        });
        
        // Show subtle save indicator
        $form.find('.auto-save-indicator').remove();
        $form.append('<small class="auto-save-indicator text-success"><i class="fas fa-check"></i> Saved</small>');
        setTimeout(() => {
            $form.find('.auto-save-indicator').fadeOut();
        }, 2000);
    } catch (error) {
        // Silently fail for auto-save
    }
}

/**
 * Debounce utility function
 */
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction() {
        const context = this;
        const args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

// Export for global access
window.loadContent = loadContent;
window.loadModal = loadModal;
window.showModal = showModal;
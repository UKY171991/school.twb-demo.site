/* ============================================
   GLOBAL HELPER FUNCTIONS
   ============================================ */

/**
 * Shows a success toast notification.
 * @param {string} message - The message to display.
 */
function showSuccessToast(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Success!',
            text: message,
            icon: 'success', // Use 'type' for SweetAlert2 v8
            toast: true,
            position: 'top-end',
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        alert(message);
    }
}

/**
 * Shows an error toast notification.
 * @param {string} message - The message to display.
 */
function showErrorToast(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Error!',
            text: message,
            icon: 'error', // Use 'type' for SweetAlert2 v8
            toast: true,
            position: 'top-end',
            showConfirmButton: false, // Keep it consistent with success, or set to true if actions are needed
            timer: 5000 // Longer timer for errors
        });
    } else {
        alert(message);
    }
}


/* ============================================
   SCHOOL MANAGEMENT SYSTEM - CUSTOM SCRIPTS
   ============================================ */

$(document).ready(function () {

    /* ============================================
       TEACHERS MODULE
       ============================================ */

    // Generic Photo Modal (Teachers & Students)
    if ($('.teacher-photo, .student-photo').length > 0) {
        // Add image modal functionality
        if ($('#imageModal').length === 0) {
            $('body').append(`
                <div id="imageModal" class="image-modal">
                    <span class="image-modal-close">&times;</span>
                    <div class="image-modal-content">
                        <img id="modalImage" src="" alt="">
                        <p id="modalName" class="mt-3 mb-0 text-white font-weight-bold" style="text-shadow: 1px 1px 2px black;"></p>
                    </div>
                </div>
            `);
        }

        // Handle photo click
        $(document).on('click', '.teacher-photo, .student-photo, .school-logo', function () {
            const imageSrc = $(this).attr('src');
            const name = $(this).attr('alt');

            $('#modalImage').attr('src', imageSrc).attr('alt', name);
            $('#modalName').text(name);
            $('#imageModal').fadeIn(300);
        });

        // Close modal when clicking the close button or outside the image
        $(document).on('click', '.image-modal-close, #imageModal', function (e) {
            if (e.target === this || $(e.target).hasClass('image-modal-close')) {
                $('#imageModal').fadeOut(300);
            }
        });

        // Close modal with Escape key
        $(document).on('keydown', function (e) {
            if (e.key === 'Escape') {
                $('#imageModal').fadeOut(300);
            }
        });
    }

    // Initialize DataTable for Teachers
    if ($.fn.DataTable && $('#teachersTable').length > 0) {
        $('#teachersTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'desc']],
            language: {
                search: "Search teachers:",
                lengthMenu: "Show _MENU_ teachers per page",
                info: "Showing _START_ to _END_ of _TOTAL_ teachers",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    }

    // Teacher Create/Edit Form
    const teacherForm = $('#teacherForm');
    if (teacherForm.length > 0) {
        // File upload functionality
        const uploadArea = $('#uploadArea');
        const fileInput = $('#image');
        const uploadContent = $('.upload-content');
        const previewArea = $('#previewArea');
        const imagePreview = $('#imagePreview');
        const fileName = $('#fileName');
        const removeImageBtn = $('#removeImage');
        const submitBtn = $('#submitBtn');

        // Click to upload
        uploadArea.on('click', function (e) {
            if (e.target !== removeImageBtn[0] && !removeImageBtn.has(e.target).length) {
                fileInput.click();
            }
        });

        // Drag and drop functionality
        uploadArea.on('dragover', function (e) {
            e.preventDefault();
            uploadArea.addClass('dragover');
        });

        uploadArea.on('dragleave', function (e) {
            e.preventDefault();
            uploadArea.removeClass('dragover');
        });

        uploadArea.on('drop', function (e) {
            e.preventDefault();
            uploadArea.removeClass('dragover');

            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                handleFileSelect(files[0]);
            }
        });

        // File input change
        fileInput.on('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                handleFileSelect(file);
            }
        });

        // Remove image
        removeImageBtn.on('click', function (e) {
            e.stopPropagation();
            resetImageUpload();
        });

        // Handle file selection
        function handleFileSelect(file) {
            // Validate file type
            if (!file.type.match('image.*')) {
                alert('Please select an image file (JPG, PNG, GIF)');
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                return;
            }

            // Read and display preview
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.attr('src', e.target.result);
                fileName.text(file.name);
                uploadContent.hide();
                previewArea.show();
            };
            reader.readAsDataURL(file);
        }

        // Reset image upload
        function resetImageUpload() {
            fileInput.val('');
            uploadContent.show();
            previewArea.hide();
            imagePreview.attr('src', '');
            fileName.text('');
        }

        // Form validation
        teacherForm.on('submit', function (e) {
            e.preventDefault();

            // Remove previous error states
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            // Basic validation
            let isValid = true;
            const requiredFields = ['school_id', 'name', 'email', 'gender'];

            requiredFields.forEach(fieldName => {
                const field = $(`#${fieldName}`);
                const value = field.val().trim();

                if (!value) {
                    field.addClass('is-invalid');
                    field.after('<span class="invalid-feedback">This field is required.</span>');
                    isValid = false;
                }
            });

            // Email validation
            const emailField = $('#email');
            const email = emailField.val().trim();
            if (email && !isValidEmail(email)) {
                emailField.addClass('is-invalid');
                emailField.after('<span class="invalid-feedback">Please enter a valid email address.</span>');
                isValid = false;
            }

            if (isValid) {
                // Show loading state
                submitBtn.addClass('loading').prop('disabled', true);
                submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...');

                // Submit form
                this.submit();
            }
        });

        // Email validation helper
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Real-time validation
        $('.form-control').on('blur', function () {
            const field = $(this);
            const value = field.val().trim();

            // Remove previous error state
            field.removeClass('is-invalid');
            field.next('.invalid-feedback').remove();

            // Check if required field is empty
            if (field.prop('required') && !value) {
                field.addClass('is-invalid');
                field.after('<span class="invalid-feedback">This field is required.</span>');
            }

            // Email validation
            if (field.attr('type') === 'email' && value && !isValidEmail(value)) {
                field.addClass('is-invalid');
                field.after('<span class="invalid-feedback">Please enter a valid email address.</span>');
            }
        });

        // Phone number formatting
        $('#phone').on('input', function () {
            let value = $(this).val().replace(/\D/g, '');
            if (value.length > 0) {
                if (value.length <= 3) {
                    value = value;
                } else if (value.length <= 6) {
                    value = value.slice(0, 3) + '-' + value.slice(3);
                } else {
                    value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 10);
                }
            }
            $(this).val(value);
        });

        // Auto-resize address textarea
        $('#address').on('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }

});

/* ============================================
   IMAGE UPLOAD HANDLING
   ============================================ */
// Image Upload Enhancement
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initImageUpload);
} else {
    initImageUpload();
}

function initImageUpload() {
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');

    imageInputs.forEach(function (input) {
        input.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    e.target.value = '';
                    return;
                }

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPG, PNG, GIF)');
                    e.target.value = '';
                    return;
                }

                // Show preview if possible
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Find or create preview element
                    let preview = input.parentElement.querySelector('.image-preview-new');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.className = 'image-preview-new';
                        preview.style.maxWidth = '150px';
                        preview.style.maxHeight = '150px';
                        preview.style.marginTop = '10px';
                        preview.style.border = '2px solid #dee2e6';
                        preview.style.borderRadius = '5px';
                        input.parentElement.appendChild(preview);
                    }
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Drag and drop functionality
    const uploadAreas = document.querySelectorAll('.image-upload-input');

    uploadAreas.forEach(function (area) {
        area.addEventListener('dragover', function (e) {
            e.preventDefault();
            area.classList.add('dragover');
        });

        area.addEventListener('dragleave', function (e) {
            e.preventDefault();
            area.classList.remove('dragover');
        });

        area.addEventListener('drop', function (e) {
            e.preventDefault();
            area.classList.remove('dragover');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const input = area.querySelector('input[type="file"]');
                input.files = files;
                input.dispatchEvent(new Event('change'));
            }
        });
    });
}

/* ============================================
   GRADES MODULE SCRIPTS
   ============================================ */
// Grades Management JavaScript
class GradesManager {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.initDataTable();
        this.initAnimations();
        this.initTooltips();
        this.initModals();
        this.initColorSelector();
    }

    bindEvents() {
        // Delete confirmation
        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();
            const btn = $(e.currentTarget);
            const gradeName = btn.data('grade-name') || 'this grade';
            const form = btn.siblings('form');

            if (confirm(`Are you sure you want to delete "${gradeName}"? This action cannot be undone.`)) {
                this.showLoading();
                form.submit();
            }
        }.bind(this));

        // View grade details
        $(document).on('click', '.btn-view', (e) => {
            e.preventDefault();
            const btn = $(e.currentTarget);
            const gradeId = btn.data('grade-id');
            this.showGradeDetails(gradeId);
        });

        // Edit grade
        $(document).on('click', '.btn-edit', (e) => {
            e.preventDefault();
            const btn = $(e.currentTarget);
            const gradeId = btn.data('grade-id');
            this.editGrade(gradeId);
        });

        // Filter and search
        $('#gradeSearch, #sectionFilter, #gradeFilter').on('input change', () => {
            this.filterGrades();
        });

        // Export functionality
        $('#exportBtn').on('click', () => {
            this.exportGrades();
        });

        // Bulk actions
        $('#selectAll').on('change', (e) => {
            $('.grade-checkbox').prop('checked', $(e.target).is(':checked'));
            this.updateBulkActions();
        });

        $(document).on('change', '.grade-checkbox', () => {
            this.updateBulkActions();
        });

        $('#bulkDeleteBtn').on('click', () => {
            this.bulkDelete();
        });
    }

    initDataTable() {
        if ($.fn.DataTable && $('#gradesTable').length) {
            $('#gradesTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[0, 'asc']],
                language: {
                    search: "Search grades:",
                    lengthMenu: "Show _MENU_ grades per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ grades",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    },
                    emptyTable: "No grades available"
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-info btn-sm'
                    }
                ]
            });
        }
    }

    initAnimations() {
        // Animate cards on scroll
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.grade-card, .stat-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.6s ease-out';
                observer.observe(card);
            });
        }

        // Hover effects
        $('.grade-badge').on('mouseenter', function () {
            $(this).addClass('animate__animated animate__pulse');
        }).on('mouseleave', function () {
            $(this).removeClass('animate__animated animate__pulse');
        });
    }

    initTooltips() {
        if ($.fn.tooltip) {
            $('[title]').tooltip({
                placement: 'top',
                trigger: 'hover'
            });
        }
    }

    initModals() {
        // Grade details modal
        if (!$('#gradeModal').length) {
            $('body').append(`
                <div class="modal fade" id="gradeModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Grade Details</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="gradeModalBody">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="editGradeBtn">Edit Grade</button>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            // Initialize handler for Modal Edit Button
            $('#editGradeBtn').on('click', function () {
                const gradeId = $(this).data('grade-id');
                if (gradeId) {
                    window.location.href = `/admin/classes/${gradeId}/edit`;
                }
            });
        }

    }

    initColorSelector() {
        // Color selector for grade theme
        $('.color-option').on('click', function () {
            $('.color-option').removeClass('selected');
            $(this).addClass('selected');
            $('#gradeTheme').val($(this).data('grade'));
        });

        // Set initial selection
        const initialTheme = $('#gradeTheme').val() || '1';
        $(`.color-option[data-grade="${initialTheme}"]`).addClass('selected');
    }

    showGradeDetails(gradeId) {
        $('#gradeModal').modal('show');
        const modalBody = $('#gradeModalBody');
        modalBody.html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        `);

        $('#editGradeBtn').data('grade-id', gradeId);

        // Fetch data from API
        $.ajax({
            url: `/admin/classes/${gradeId}`,
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            },
            success: (gradeData) => {
                this.renderGradeDetails(gradeData);
            },
            error: (xhr) => {
                modalBody.html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> Error loading grade details. Please try again.
                    </div>
                `);
            }
        });
    }

    renderGradeDetails(gradeData) {
        // Parse ID for badge color (fallback to 1 if not numeric or out of range)
        const badgeId = parseInt(gradeData.id) <= 12 ? gradeData.id : 1;

        const html = `
            <div class="row">
                <div class="col-md-6">
                    <div class="text-center mb-3">
                        <div class="grade-badge grade-${badgeId}">${gradeData.name}</div>
                    </div>
                    <h4 class="text-center">${gradeData.name}</h4>
                    <p class="text-muted text-center">Grade Information</p>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Grade Name:</strong></td>
                            <td>${gradeData.name}</td>
                        </tr>
                        <tr>
                            <td><strong>Section:</strong></td>
                            <td>${gradeData.section || 'N/A'}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Students:</strong></td>
                            <td><span class="badge badge-success">${gradeData.students_count}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td>${gradeData.created_at}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td><span class="badge badge-primary">${gradeData.status}</span></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <h6>Recent Activity</h6>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <small class="text-muted">${gradeData.last_activity}</small>
                                <p>Last updated</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#gradeModalBody').html(html);
    }

    // Removed getGradeData as we now fetch from API

    editGrade(gradeId) {
        // Redirect to edit page
        window.location.href = `/admin/classes/${gradeId}/edit`;
    }

    filterGrades() {
        const searchTerm = $('#gradeSearch').val().toLowerCase();
        const sectionFilter = $('#sectionFilter').val().toLowerCase();
        const gradeFilter = $('#gradeFilter').val();

        $('#gradesTable tbody tr').each(function () {
            const row = $(this);

            // Skip if this is the empty state row
            if (row.find('.empty-state').length > 0) {
                return;
            }

            // Get text from the correct columns (accounting for checkbox column)
            const gradeName = row.find('td:eq(1)').text().toLowerCase().trim();
            const section = row.find('td:eq(2)').text().toLowerCase().trim();

            // Check if search term matches either class name or section
            const matchesSearch = !searchTerm ||
                gradeName.includes(searchTerm) ||
                section.includes(searchTerm);

            // Check if section filter matches
            const matchesSection = !sectionFilter || section.includes(sectionFilter);

            // Check if grade filter matches (search in the class name)
            const matchesGrade = !gradeFilter || gradeName.includes(gradeFilter.toLowerCase());

            if (matchesSearch && matchesSection && matchesGrade) {
                row.show();
            } else {
                row.hide();
            }
        });

        // Show/hide empty state message
        const visibleRows = $('#gradesTable tbody tr:visible').length;
        if (visibleRows === 0) {
            if ($('#gradesTable tbody .no-results-row').length === 0) {
                $('#gradesTable tbody').append(`
                    <tr class="no-results-row">
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No classes found matching your search criteria.</p>
                        </td>
                    </tr>
                `);
            }
        } else {
            $('#gradesTable tbody .no-results-row').remove();
        }
    }

    exportGrades() {
        this.showLoading();

        // Simulate export process
        setTimeout(() => {
            this.hideLoading();
            alert('Grades exported successfully!');
        }, 2000);
    }

    updateBulkActions() {
        const checkedCount = $('.grade-checkbox:checked').length;
        const bulkActions = $('#bulkActions');

        if (checkedCount > 0) {
            bulkActions.show();
            $('#bulkDeleteBtn').text(`Delete (${checkedCount})`);
        } else {
            bulkActions.hide();
        }
    }

    bulkDelete() {
        const checkedCount = $('.grade-checkbox:checked').length;

        if (confirm(`Are you sure you want to delete ${checkedCount} grade(s)? This action cannot be undone.`)) {
            this.showLoading();

            // Simulate bulk delete
            setTimeout(() => {
                this.hideLoading();
                $('.grade-checkbox:checked').closest('tr').fadeOut(300, function () {
                    $(this).remove();
                });
                this.updateBulkActions();
                this.showNotification('Grades deleted successfully!', 'success');
            }, 2000);
        }
    }

    showLoading() {
        if (!$('.loading-overlay').length) {
            $('body').append(`
                <div class="loading-overlay">
                    <div class="loading-spinner"></div>
                </div>
            `);
        }
    }

    hideLoading() {
        $('.loading-overlay').fadeOut(300, function () {
            $(this).remove();
        });
    }

    showNotification(message, type = 'info') {
        const alertClass = `alert-${type}`;
        const notification = $(`
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <strong>${type.charAt(0).toUpperCase() + type.slice(1)}!</strong> ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `);

        $('body').append(notification);

        setTimeout(() => {
            notification.fadeOut(300, function () {
                $(this).remove();
            });
        }, 5000);
    }

    // Statistics update
    updateStatistics() {
        const totalGrades = $('#gradesTable tbody tr').length;
        const totalStudents = Array.from($('#gradesTable tbody tr')).reduce((sum, row) => {
            return sum + parseInt($(row).find('td:eq(3)').text()) || 0;
        }, 0);

        const sections = new Set();
        $('#gradesTable tbody tr').each(function () {
            const section = $(this).find('td:eq(2)').text();
            if (section && section !== 'N/A') {
                sections.add(section);
            }
        });

        $('#totalGrades').text(totalGrades);
        $('#totalStudents').text(totalStudents);
        $('#totalSections').text(sections.size);
        $('#avgStudents').text(Math.round(totalStudents / totalGrades));
    }
}

// Initialize when DOM is ready
$(document).ready(function () {
    window.gradesManager = new GradesManager();

    // Update statistics on page load
    setTimeout(() => {
        window.gradesManager.updateStatistics();
    }, 500);
});

// Handle form submissions
// Handle form submissions - validation only
$(document).on('submit', '#gradeForm', function (e) {
    // Only basic client-side validation, no prevention of submit unless invalid
    const gradeName = $('#name').val().trim();
    if (!gradeName) {
        e.preventDefault();
        if (window.gradesManager) window.gradesManager.showNotification('Grade name is required!', 'danger');
        else alert('Grade name is required!');
    }
    // If valid, let it submit naturally to the server (PHP)
});

// Real-time validation
$(document).on('input', '#name', function () {
    const value = $(this).val().trim();
    const feedback = $(this).siblings('.invalid-feedback');

    if (value.length < 2) {
        $(this).addClass('is-invalid');
        if (!feedback.length) {
            $(this).after('<span class="invalid-feedback">Grade name must be at least 2 characters.</span>');
        }
    } else {
        $(this).removeClass('is-invalid');
        feedback.remove();
    }
});

// Keyboard shortcuts
$(document).on('keydown', function (e) {
    // Ctrl/Cmd + N for new grade
    if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
        e.preventDefault();
        window.location.href = '/admin/classes/create';
    }

    // Escape to close modals
    if (e.key === 'Escape') {
        $('.modal').modal('hide');
    }
});

/* ============================================
   EXAM TIMETABLES MODULE SCRIPTS
   ============================================ */
// Bulk operations functionality
// Wrapped in a function or checked for existence to prevent errors on other pages
if (document.querySelector('.timetable-grid') || document.querySelector('#bulkActionForm')) {
    $(document).ready(function () {
        // Select/Deselect all functionality
        $('#selectAll').on('change', function () {
            $('.row-checkbox').prop('checked', this.checked);
            updateBulkButtons();
            updateSelectedCount();
        });

        // Update bulk buttons when individual checkboxes change
        $('.row-checkbox').on('change', function () {
            updateSelectAllCheckbox();
            updateBulkButtons();
            updateSelectedCount();
        });

        // Bulk edit functionality
        $('#bulkEditBtn').on('click', function () {
            const selectedCombinations = getSelectedCombinations();
            if (selectedCombinations.length === 0) {
                alert('Please select at least one timetable entry to edit.');
                return;
            }

            // Create form dynamically
            const form = $('<form>', {
                method: 'POST',
                action: '/exam-timetables/bulk-edit' // Hardcoded path or use a data attribute on body/btn
            });

            form.append($('<input>', {
                type: 'hidden',
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            }));

            selectedCombinations.forEach(function (combination) {
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'class_combinations[]',
                    value: combination
                }));
            });

            $('body').append(form);
            form.submit();
        });

        // Bulk delete functionality
        $('#bulkDeleteBtn').on('click', function () {
            const selectedCombinations = getSelectedCombinations();
            if (selectedCombinations.length === 0) {
                alert('Please select at least one timetable entry to delete.');
                return;
            }

            if (!confirm(`Are you sure you want to delete ${selectedCombinations.length} selected timetable class groups? This will delete ALL timetable entries for these classes. This action cannot be undone.`)) {
                return;
            }

            const form = $('<form>', {
                method: 'POST',
                action: '/exam-timetables/bulk-delete'
            });

            form.append($('<input>', {
                type: 'hidden',
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: '_method',
                value: 'DELETE'
            }));

            selectedCombinations.forEach(function (combination) {
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'class_combinations[]',
                    value: combination
                }));
            });

            $('body').append(form);
            form.submit();
        });

        function getSelectedCombinations() {
            const selectedCombinations = [];
            $('.row-checkbox:checked').each(function () {
                selectedCombinations.push($(this).val());
            });
            return selectedCombinations;
        }

        function updateBulkButtons() {
            const selectedCount = $('.row-checkbox:checked').length;
            $('#bulkEditBtn, #bulkDeleteBtn').prop('disabled', selectedCount === 0);

            if (selectedCount > 0) {
                $('#bulkEditBtn').html(`<i class="fas fa-edit"></i> Bulk Edit Selected (${selectedCount})`);
                $('#bulkDeleteBtn').html(`<i class="fas fa-trash"></i> Bulk Delete Selected (${selectedCount})`);
            } else {
                $('#bulkEditBtn').html('<i class="fas fa-edit"></i> Bulk Edit Selected');
                $('#bulkDeleteBtn').html('<i class="fas fa-trash"></i> Bulk Delete Selected');
            }
        }

        function updateSelectedCount() {
            const selectedCount = $('.row-checkbox:checked').length;
            $('#selectedCount').text(`${selectedCount} selected`);
        }

        function updateSelectAllCheckbox() {
            const totalCheckboxes = $('.row-checkbox').length;
            const checkedCheckboxes = $('.row-checkbox:checked').length;

            if (checkedCheckboxes === 0) {
                $('#selectAll').prop('indeterminate', false).prop('checked', false);
            } else if (checkedCheckboxes === totalCheckboxes) {
                $('#selectAll').prop('indeterminate', false).prop('checked', true);
            } else {
                $('#selectAll').prop('indeterminate', true);
            }
        }

        // Individual action buttons
        $('.edit-class-btn').on('click', function () {
            const examType = $(this).data('exam-type');
            const className = $(this).data('class');
            const section = $(this).data('section') || '';
            const academicYear = $(this).data('academic-year');

            // Redirect to edit group page
            const url = `/exam-timetables/edit-group?exam_type_id=${examType}&class=${className}&section=${section}&academic_year=${academicYear}`;
            window.location.href = url;
        });

        $('.add-subjects-btn').on('click', function () {
            const examType = $(this).data('exam-type');
            const className = $(this).data('class');
            const section = $(this).data('section');
            const academicYear = $(this).data('academic-year');

            // Redirect to bulk create with pre-filled data
            const url = `/exam-timetables/bulk-create?exam_type_id=${examType}&class=${className}&section=${section}&academic_year=${academicYear}`;
            window.location.href = url;
        });

        $('.delete-class-btn').on('click', function () {
            const examType = $(this).data('exam-type');
            const className = $(this).data('class');
            const section = $(this).data('section') || '';
            const academicYear = $(this).data('academic-year');

            const sectionText = section ? section : 'All';

            if (!confirm(`Are you sure you want to delete all timetable entries for Class ${className} Section ${sectionText}? This action cannot be undone.`)) {
                return;
            }

            const combination = `${examType}|${className}|${section}|${academicYear}`;

            const form = $('<form>', {
                method: 'POST',
                action: '/exam-timetables/bulk-delete'
            });

            form.append($('<input>', {
                type: 'hidden',
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: '_method',
                value: 'DELETE'
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: 'class_combinations[]',
                value: combination
            }));

            $('body').append(form);
            form.submit();
        });

        $('.print-class-btn').on('click', function () {
            const examType = $(this).data('exam-type');
            const className = $(this).data('class');
            const section = $(this).data('section') || '';
            const academicYear = $(this).data('academic-year');

            // Build the print URL with parameters
            const printUrl = `/exam-timetables/print?exam_type_id=${examType}&class=${className}&section=${section}&academic_year=${academicYear}`;

            // Open in new window for printing
            window.open(printUrl, '_blank');
        });

        // Initialize selected count
        updateSelectedCount();
    });
}

/* ============================================
   ADMIT CARDS MODULE SCRIPTS
   ============================================ */
function generateAdmitCard(studentId, studentName) {
    if (document.getElementById('modal_student_id')) {
        document.getElementById('modal_student_id').value = studentId;
        document.getElementById('modal_student_name').value = studentName;
    }
}

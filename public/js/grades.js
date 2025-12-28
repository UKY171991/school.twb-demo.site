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
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const gradeName = $(this).data('grade-name') || 'this grade';
            const form = $(this).siblings('form');
            
            if (confirm(`Are you sure you want to delete "${gradeName}"? This action cannot be undone.`)) {
                this.showLoading();
                form.submit();
            }
        }.bind(this));

        // View grade details
        $(document).on('click', '.btn-view', function(e) {
            e.preventDefault();
            const gradeId = $(this).data('grade-id');
            this.showGradeDetails(gradeId);
        }.bind(this));

        // Edit grade
        $(document).on('click', '.btn-edit', function(e) {
            e.preventDefault();
            const gradeId = $(this).data('grade-id');
            this.editGrade(gradeId);
        }.bind(this));

        // Filter and search
        $('#gradeSearch, #sectionFilter, #gradeFilter').on('input change', function() {
            this.filterGrades();
        }.bind(this));

        // Export functionality
        $('#exportBtn').on('click', function() {
            this.exportGrades();
        }.bind(this));

        // Bulk actions
        $('#selectAll').on('change', function() {
            $('.grade-checkbox').prop('checked', $(this).is(':checked'));
            this.updateBulkActions();
        }.bind(this));

        $(document).on('change', '.grade-checkbox', function() {
            this.updateBulkActions();
        }.bind(this));

        $('#bulkDeleteBtn').on('click', function() {
            this.bulkDelete();
        }.bind(this));
    }

    initDataTable() {
        if ($.fn.DataTable) {
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

        // Hover effects
        $('.grade-badge').on('mouseenter', function() {
            $(this).addClass('animate__animated animate__pulse');
        }).on('mouseleave', function() {
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
                                <!-- Content will be loaded dynamically -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="editGradeBtn">Edit Grade</button>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        }
    }

    initColorSelector() {
        // Color selector for grade theme
        $('.color-option').on('click', function() {
            $('.color-option').removeClass('selected');
            $(this).addClass('selected');
            $('#gradeTheme').val($(this).data('grade'));
        });

        // Set initial selection
        const initialTheme = $('#gradeTheme').val() || '1';
        $(`.color-option[data-grade="${initialTheme}"]`).addClass('selected');
    }

    showGradeDetails(gradeId) {
        // Simulate loading grade details
        const gradeData = this.getGradeData(gradeId);
        const modalBody = $('#gradeModalBody');
        
        modalBody.html(`
            <div class="row">
                <div class="col-md-6">
                    <div class="text-center mb-3">
                        <div class="grade-badge grade-${gradeData.gradeNumber}">${gradeData.name}</div>
                    </div>
                    <h4>${gradeData.name}</h4>
                    <p class="text-muted">Grade Information</p>
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
                            <td><span class="badge badge-success">${gradeData.studentsCount}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td>${gradeData.createdAt}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td><span class="badge badge-primary">Active</span></td>
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
                                <small class="text-muted">${gradeData.lastActivity}</small>
                                <p>Grade created and ${gradeData.studentsCount} students enrolled</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);

        $('#gradeModal').modal('show');
        $('#editGradeBtn').data('grade-id', gradeId);
    }

    getGradeData(gradeId) {
        // Simulate grade data - in real app, this would be an API call
        return {
            id: gradeId,
            name: `Grade ${gradeId}`,
            gradeNumber: gradeId,
            section: String.fromCharCode(65 + (gradeId % 3)),
            studentsCount: Math.floor(Math.random() * 50) + 10,
            createdAt: new Date().toLocaleDateString(),
            lastActivity: new Date().toLocaleDateString()
        };
    }

    editGrade(gradeId) {
        // Redirect to edit page or show edit modal
        window.location.href = `/admin/grades/${gradeId}/edit`;
    }

    filterGrades() {
        const searchTerm = $('#gradeSearch').val().toLowerCase();
        const sectionFilter = $('#sectionFilter').val();
        const gradeFilter = $('#gradeFilter').val();

        $('#gradesTable tbody tr').each(function() {
            const row = $(this);
            const gradeName = row.find('td:eq(1)').text().toLowerCase();
            const section = row.find('td:eq(2)').text();
            const gradeNumber = row.find('td:eq(0)').text();

            const matchesSearch = gradeName.includes(searchTerm);
            const matchesSection = !sectionFilter || section === sectionFilter;
            const matchesGrade = !gradeFilter || gradeNumber === gradeFilter;

            if (matchesSearch && matchesSection && matchesGrade) {
                row.show();
            } else {
                row.hide();
            }
        });
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
                $('.grade-checkbox:checked').closest('tr').fadeOut(300, function() {
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
        $('.loading-overlay').fadeOut(300, function() {
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
            notification.fadeOut(300, function() {
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
        $('#gradesTable tbody tr').each(function() {
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
$(document).ready(function() {
    window.gradesManager = new GradesManager();
    
    // Update statistics on page load
    setTimeout(() => {
        window.gradesManager.updateStatistics();
    }, 500);
});

// Handle form submissions
$(document).on('submit', '#gradeForm', function(e) {
    e.preventDefault();
    
    const form = $(this);
    const submitBtn = form.find('button[type="submit"]');
    
    // Basic validation
    const gradeName = $('#name').val().trim();
    if (!gradeName) {
        window.gradesManager.showNotification('Grade name is required!', 'danger');
        return;
    }
    
    // Show loading state
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
    
    // Simulate form submission
    setTimeout(() => {
        submitBtn.prop('disabled', false).html('Create Grade');
        window.gradesManager.showNotification('Grade saved successfully!', 'success');
        
        // Redirect to index page after a delay
        setTimeout(() => {
            window.location.href = '/admin/grades';
        }, 1500);
    }, 2000);
});

// Real-time validation
$(document).on('input', '#name', function() {
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
$(document).on('keydown', function(e) {
    // Ctrl/Cmd + N for new grade
    if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
        e.preventDefault();
        window.location.href = '/admin/grades/create';
    }
    
    // Escape to close modals
    if (e.key === 'Escape') {
        $('.modal').modal('hide');
    }
});

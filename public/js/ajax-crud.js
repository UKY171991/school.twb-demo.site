/**
 * Generic AJAX CRUD Helper
 * Handles fetching forms in modals, submitting them via AJAX, and deleting items.
 */

// Add CSRF token to all AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Generic Modal Container (should be present in layout or included in index)
const modalTemplate = `
<div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajaxModalTitle">Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="ajaxModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>`;

// Ensure modal exists in DOM
$(document).ready(function () {
    if ($('#ajaxModal').length === 0) {
        $('body').append(modalTemplate);
    }
});

/**
 * Open a generic modal with content from a URL
 * @param {string} url - The URL to fetch (create/edit form)
 * @param {string} title - Optional title for the modal
 */
function openAjaxModal(url, title = 'Manage Item') {
    $('#ajaxModalTitle').text(title);
    $('#ajaxModalBody').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    `);
    $('#ajaxModal').modal('show');

    // Fetch form
    $.get(url, function (response) {
        $('#ajaxModalBody').html(response);

        // Remove standard card headers if they exist in the returned partial (but keep footer for submit buttons)
        $('#ajaxModalBody').find('.card-header, .content-header').remove();
        $('#ajaxModalBody').find('.card').removeClass('card').css('box-shadow', 'none');
        $('#ajaxModalBody').find('.card-body').removeClass('card-body').addClass('p-0');

        // Re-bind automatic form submission - DEPRECATED
        // Form submission is now handled by event delegation in ajax-crud.js
        /*
        const form = $('#ajaxModalBody').find('form');
        if (form.length > 0) {
            setupAjaxForm(form);
        }
        */
    }).fail(function (xhr) {
        $('#ajaxModalBody').html(`
            <div class="alert alert-danger">
                Error loading content: ${xhr.statusText}
            </div>
        `);
    });
}

// Event delegation for AJAX form submission
$(document).on('submit', '#ajaxModal form', function (e) {
    e.preventDefault();
    const form = $(this);

    // Clear previous errors
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();

    // Get submit button to show loading state
    const submitBtn = form.find('[type="submit"]');
    const originalBtnText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

    // Create FormData (supports file uploads)
    const formData = new FormData(this);

    $.ajax({
        url: form.attr('action'),
        method: form.attr('method') || 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.success) {
                $('#ajaxModal').modal('hide');
                // Show success message (using AdminLTE Toast if available or standard alert)
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    alert(response.message);
                    location.reload();
                }
            } else if (response.message) {
                // Handle false success with message
                submitBtn.prop('disabled', false).html(originalBtnText);
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error', response.message, 'error');
                } else {
                    alert(response.message);
                }
            }
        },
        error: function (xhr) {
            submitBtn.prop('disabled', false).html(originalBtnText);

            if (xhr.status === 422) {
                // Validation errors
                const errors = xhr.responseJSON.errors;
                $.each(errors, function (field, messages) {
                    const input = form.find(`[name="${field}"]`);
                    input.addClass('is-invalid');
                    // Check if invalid-feedback already exists to avoid duplicates
                    if (input.closest('.form-group').find('.invalid-feedback').length === 0) {
                        input.closest('.form-group').append(`<span class="invalid-feedback">${messages[0]}</span>`);
                    } else {
                        // Update existing feedback
                        input.closest('.form-group').find('.invalid-feedback').text(messages[0]);
                    }
                });
            } else {
                // General error
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
                } else {
                    alert(xhr.responseJSON?.message || 'Something went wrong');
                }
            }
        }
    });
});
/**
 * Handle AJAX form submission - DEPRECATED (Delegation used)
 * @param {jQuery} form - The form element
 */
function setupAjaxForm(form) {
    // No-op: Submission handled via delegation
}

/**
 * Delete an item via AJAX
 * @param {string} url - The delete URL
 */
function deleteAjaxItem(url, itemName = 'this item') {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${itemName}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                performDelete(url);
            }
        });
    } else {
        if (confirm(`Are you sure you want to delete ${itemName}?`)) {
            performDelete(url);
        }
    }
}

function performDelete(url) {
    $.ajax({
        url: url,
        method: 'POST',
        data: { _method: 'DELETE' },
        success: function (response) {
            if (response.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Deleted!', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    alert(response.message);
                    location.reload();
                }
            }
        },
        error: function (xhr) {
            const msg = xhr.responseJSON?.message || 'Delete failed';
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', msg, 'error');
            } else {
                alert(msg);
            }
        }
    });
}

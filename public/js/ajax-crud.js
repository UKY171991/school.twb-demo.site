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
    <div class="modal-dialog modal-xl" role="document">
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

    const formData = new FormData(this);
    if (form.attr('method').toUpperCase() === 'POST' && form.find('input[name="_method"]').length > 0) {
        formData.append('_method', form.find('input[name="_method"]').val());
    }

    $.ajax({
        url: form.attr('action'),
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.success) {
                $('#ajaxModal').modal('hide');
                Swal.fire({
                    title: 'Success',
                    text: response.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });

                if (response.teacher) {
                    upsertTeacherRow(response.teacher);
                } else {
                    location.reload(); // Fallback if no teacher data is returned
                }

            } else if (response.message) {
                submitBtn.prop('disabled', false).html(originalBtnText);
                Swal.fire({ title: 'Error', text: response.message, icon: 'error' });
            }
        },
        error: function (xhr) {
            submitBtn.prop('disabled', false).html(originalBtnText);
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                $.each(errors, function (field, messages) {
                    const input = form.find(`[name="${field}"]`);
                    input.addClass('is-invalid');
                    if (input.closest('.form-group').find('.invalid-feedback').length === 0) {
                        input.closest('.form-group').append(`<span class="invalid-feedback">${messages[0]}</span>`);
                    } else {
                        input.closest('.form-group').find('.invalid-feedback').text(messages[0]);
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Something went wrong',
                    icon: 'error'
                });
            }
        }
    });
});

/**
 * Add or update a row in the teachers DataTable.
 * @param {object} teacher - The teacher object from the server.
 */
function upsertTeacherRow(teacher) {
    const table = $('#teachersTable').DataTable();
    const rowData = formatTeacherRow(teacher);
    
    // Try to find the row by ID. We add an ID to the TR element for this.
    const rowNode = table.row('#teacher-row-' + teacher.id).node();

    if (rowNode) {
        // Update existing row
        table.row(rowNode).data(rowData).draw();
    } else {
        // Add new row
        table.row.add(rowData).node().id = 'teacher-row-' + teacher.id;
        table.draw();
    }
}

/**
 * Format teacher data for a DataTable row.
 * @param {object} teacher - The teacher object.
 * @returns {array} - Array of strings/HTML for the row.
 */
function formatTeacherRow(teacher) {
    const defaultAvatar = `<div class="teacher-photo-placeholder" style="width: 45px; height: 45px; background-color: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;"><i class="fas fa-user text-muted"></i></div>`;
    const photo = teacher.image_url 
        ? `<img src="${teacher.image_url}" alt="${teacher.name}" class="teacher-photo img-thumbnail" style="width: 45px; height: 45px; object-fit: cover; border-radius: 50%; cursor: pointer;">` 
        : defaultAvatar;
    
    const joinedDate = teacher.date_of_joining 
        ? `<br><small class="text-muted">Joined: ${new Date(teacher.date_of_joining).toLocaleString('default', { month: 'short', year: 'numeric' })}</small>`
        : '';

    const genderBadge = {
        male: 'bg-primary',
        female: 'bg-pink',
        other: 'bg-secondary'
    };
    const genderIcon = {
        male: 'fa-mars',
        female: 'fa-venus',
        other: 'fa-genderless'
    };

    const teacherName = `<strong>${teacher.name}</strong>${joinedDate}`;
    const teacherEmail = `<i class="fas fa-envelope text-info"></i> ${teacher.email}`;
    const teacherPhone = teacher.phone ? `<i class="fas fa-phone text-info"></i> ${teacher.phone}` : `<span class="text-muted">N/A</span>`;
    const teacherGender = `<span class="badge ${genderBadge[teacher.gender]}"><i class="fas ${genderIcon[teacher.gender]}"></i> ${teacher.gender.charAt(0).toUpperCase() + teacher.gender.slice(1)}</span>`;
    const status = `<span class="badge badge-success"><i class="fas fa-check-circle"></i> Active</span>`;
    const school = teacher.school ? `<small class="text-muted">${teacher.school.name}</small>` : `<span class="text-muted">N/A</span>`;

    const showUrl = '/admin/teachers/' + teacher.id;
    const editUrl = '/admin/teachers/' + teacher.id + '/edit';
    const deleteUrl = '/admin/teachers/' + teacher.id;

    const actions = `
        <div class="btn-group btn-group-sm">
            <button onclick="openAjaxModal('${showUrl}', 'View ${teacher.name.replace(/'/g, "\'")}')" class="btn btn-success" title="View">
                <i class="fas fa-eye"></i>
            </button>
            <button onclick="openAjaxModal('${editUrl}', 'Edit ${teacher.name.replace(/'/g, "\'")}')" class="btn btn-info" title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            <button onclick="deleteAjaxItem('${deleteUrl}', '${teacher.name.replace(/'/g, "\'")}')" class="btn btn-danger" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>`;

    return [
        `<span class="badge badge-secondary">${teacher.id}</span>`,
        photo,
        teacherName,
        teacherEmail,
        teacherPhone,
        teacherGender,
        status,
        school,
        actions
    ];
}


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
    console.log('Attempting to delete:', url);
    $.ajax({
        url: url,
        method: 'DELETE',
        beforeSend: function() {
            console.log('Sending delete request to:', url);
        },
        success: function (response) {
            console.log('Delete response:', response);
            if (response.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Deleted!',
                        text: response.message,
                        icon: 'success'
                    }).then(() => {
                        // Try to remove the row from DataTable, fallback to page reload
                        var tableId = 'teachersTable'; // Default table ID
                        var table = $('#' + tableId).DataTable();
                        
                        if (table && table.rows().count() > 0) {
                            // Extract the ID from URL to find the correct row
                            var id = url.split('/').pop();
                            var row = $('button[onclick*="' + id + '"]').closest('tr');
                            if (row.length > 0) {
                                table.row(row).remove().draw();
                            } else {
                                location.reload();
                            }
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    alert(response.message);
                    location.reload();
                }
            }
        },
        error: function (xhr, status, error) {
            console.log('Delete error:', {xhr: xhr, status: status, error: error});
            console.log('Response text:', xhr.responseText);
            const msg = xhr.responseJSON?.message || 'Delete failed';
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Error',
                    text: msg,
                    icon: 'error'
                });
            } else {
                alert(msg);
            }
        }
    });
}

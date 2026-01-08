(function($) {
    "use strict";

    let table;

    // CSRF Token Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // DataTable Initialization
    function initializeDataTable() {
        table = $('#teachersTable').DataTable({
            processing: true,
            serverSide: false, // Data is loaded client-side via Ajax
            ajax: {
                url: "/admin/teachers", // The URL to fetch data from
                type: "GET",
                dataSrc: "data" // Key in the JSON response that contains the data
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: null, name: 'image', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'gender', name: 'gender' },
                { data: null, name: 'status', orderable: false, searchable: false },
                { data: 'school.name', name: 'school' },
                { data: null, name: 'actions', orderable: false, searchable: false }
            ],
            columnDefs: [
                {
                    targets: 1, // Photo column
                    render: function(data, type, row) {
                        const defaultAvatar = `<div class="teacher-photo-placeholder" style="width: 45px; height: 45px; background-color: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;"><i class="fas fa-user text-muted"></i></div>`;
                        return row.image_url 
                            ? `<img src="${row.image_url}" alt="${row.name}" class="teacher-photo img-thumbnail" style="width: 45px; height: 45px; object-fit: cover; border-radius: 50%; cursor: pointer;">` 
                            : defaultAvatar;
                    }
                },
                {
                    targets: 2, // Name column
                    render: function(data, type, row) {
                        const joinedDate = row.date_of_joining 
                            ? `<br><small class="text-muted">Joined: ${new Date(row.date_of_joining).toLocaleString('default', { month: 'short', year: 'numeric' })}</small>`
                            : '';
                        return `<strong>${data}</strong>${joinedDate}`;
                    }
                },
                {
                    targets: 6, // Status column
                    render: function(data, type, row) {
                        return `<span class="badge badge-success"><i class="fas fa-check-circle"></i> Active</span>`;
                    }
                },
                {
                    targets: 8, // Actions column
                    render: function(data, type, row) {
                        const showUrl = `/admin/teachers/${row.id}`;
                        const editUrl = `/admin/teachers/${row.id}/edit`;
                        return `
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-success btn-show" data-url="${showUrl}" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-info btn-edit" data-url="${editUrl}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-delete" data-id="${row.id}" data-name="${row.name}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>`;
                    }
                }
            ],
            rowId: 'id', // Use 'id' as the row ID
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[0, 'asc']],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search teachers...",
            }
        });
    }

    // Modal Management
    function openModal(url, title) {
        $('#ajaxModalTitle').text(title);
        $('#ajaxModalBody').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');
        $('#ajaxModal').modal('show');

        $.get(url, function(response) {
            $('#ajaxModalBody').html(response);
        }).fail(function() {
            $('#ajaxModalBody').html('<div class="alert alert-danger">Error loading content.</div>');
        });
    }

    // Form Submission
    $(document).on('submit', '#ajaxModal form', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const method = form.find('input[name="_method"]').val() || 'POST';
        const formData = new FormData(this);
        
        const submitBtn = form.find('[type="submit"]');
        const originalBtnText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: url,
            method: 'POST', // Always POST, use _method for spoofing
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#ajaxModal').modal('hide');
                    showSuccessToast(response.message);
                    table.ajax.reload(); // Reload table data
                } else {
                    showErrorToast(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    // Clear previous errors
                    form.find('.is-invalid').removeClass('is-invalid');
                    form.find('.invalid-feedback').remove();
                    // Display new errors
                    $.each(errors, function(field, messages) {
                        const input = form.find(`[name="${field}"]`);
                        input.addClass('is-invalid');
                        input.closest('.form-group').append(`<span class="invalid-feedback">${messages[0]}</span>`);
                    });
                    showErrorToast('Please check the form for errors.');
                } else {
                    showErrorToast('Something went wrong.');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalBtnText);
            }
        });
    });

    // Delete Action
    $(document).on('click', '.btn-delete', function() {
        const teacherId = $(this).data('id');
        const teacherName = $(this).data('name');
        const url = `/admin/teachers/${teacherId}`;

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${teacherName}.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            showSuccessToast(response.message);
                            table.row(`#${teacherId}`).remove().draw();
                        } else {
                            showErrorToast(response.message);
                        }
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON?.message || 'Something went wrong.';
                        showErrorToast(msg);
                    }
                });
            }
        });
    });

    // Modal Triggers
    $('#add-teacher-btn').on('click', function() {
        const url = $(this).data('url');
        openModal(url, 'Add New Teacher');
    });

    $(document).on('click', '.btn-edit', function() {
        const url = $(this).data('url');
        openModal(url, 'Edit Teacher');
    });

    $(document).on('click', '.btn-show', function() {
        const url = $(this).data('url');
        openModal(url, 'View Teacher');
    });

    // Initialize the DataTable on page load
    $(document).ready(function() {
        // Add modal placeholder if not exists
        if ($('#ajaxModal').length === 0) {
            $('body').append(`
                <div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ajaxModalTitle"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="ajaxModalBody"></div>
                        </div>
                    </div>
                </div>
            `);
        }
        
        initializeDataTable();
    });

})(jQuery);

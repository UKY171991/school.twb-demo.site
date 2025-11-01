// Admin JavaScript for AJAX functionality and notifications

$(document).ready(function() {
    // CSRF token setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Toast notification function
    function showToast(type, message, title = '') {
        toastr[type](message, title, { 
            progressBar: true,
            positionClass: 'toast-top-right'
        });
    }

    // Schools
    var schools_table = $('#schools-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/admin/schools",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'address', name: 'address' },
            { data: 'phone', name: 'phone' },
            { data: 'email', name: 'email' },
            { data: 'principal_name', name: 'principal_name' },
            { data: 'teachers_count', name: 'teachers_count', searchable: false },
            { data: 'students_count', name: 'students_count', searchable: false },
            { data: 'status', name: 'status', searchable: false, orderable: false },
            { data: 'actions', name: 'actions', searchable: false, orderable: false }
        ]
    });

    $('#add-school-btn').on('click', function() {
        $('#school-form')[0].reset();
        $('#school-modal-label').text('Add New School');
        $('#school-form-method').val('POST');
        $('#school-form').attr('action', "/admin/schools");
        $('#school-id').val('');
        $('#school-modal').modal('show');
    });

    $('#schools-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.get("/admin/schools/" + id + '/edit', function(data) {
            $('#school-modal-label').text('Edit School');
            $('#school-form-method').val('PUT');
            $('#school-form').attr('action', "/admin/schools/" + id);
            $('#school-id').val(data.id);
            $('#name').val(data.name);
            $('#address').val(data.address);
            $('#phone').val(data.phone);
            $('#email').val(data.email);
            $('#principal_name').val(data.principal_name);
            $('#established_year').val(data.established_year);
            $('#description').val(data.description);
            $('#is_active').prop('checked', data.is_active);
            $('#school-modal').modal('show');
        });
    });

    $('#save-school-btn').on('click', function() {
        var form = $('#school-form');
        var url = form.attr('action');
        var method = $('#school-form-method').val();
        var data = form.serialize();

        $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#school-modal').modal('hide');
                    schools_table.ajax.reload();
                    showToast('success', response.message);
                } else {
                    var errors = response.errors;
                    var errorMessages = '';
                    $.each(errors, function(key, value) {
                        errorMessages += value[0] + '<br>';
                    });
                    showToast('error', errorMessages);
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                var message = response.message || 'An error occurred.';
                showToast('error', message);
            }
        });
    });

    // Teachers
    var teachers_table = $('#teachers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/admin/teachers",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user.name', name: 'user.name' },
            { data: 'user.email', name: 'user.email' },
            { data: 'school.name', name: 'school.name' },
            { data: 'phone', name: 'phone' },
            { data: 'status', name: 'status', searchable: false, orderable: false },
            { data: 'actions', name: 'actions', searchable: false, orderable: false }
        ]
    });

    $('#add-teacher-btn').on('click', function() {
        $('#teacher-modal-label').text('Add New Teacher');
        $('#teacher-form-method').val('POST');
        $('#teacher-form').attr('action', "/admin/teachers");
        $('#teacher-id').val('');
        $.get("/admin/teachers/create", function(data) {
            $('#teacher-form-content').html(data);
            $('.select2').select2();
            $('#teacher-modal').modal('show');
        });
    });

    $('#teachers-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $('#teacher-modal-label').text('Edit Teacher');
        $('#teacher-form-method').val('PUT');
        $('#teacher-form').attr('action', "/admin/teachers/" + id);
        $('#teacher-id').val(id);
        $.get("/admin/teachers/" + id + '/edit', function(data) {
            $('#teacher-form-content').html(data);
            $('.select2').select2();
            $('#teacher-modal').modal('show');
        });
    });

    $('#save-teacher-btn').on('click', function() {
        var form = $('#teacher-form');
        var url = form.attr('action');
        var method = $('#teacher-form-method').val();
        var data = form.serialize();

        $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#teacher-modal').modal('hide');
                    teachers_table.ajax.reload();
                    showToast('success', response.message);
                } else {
                    var errors = response.errors;
                    var errorMessages = '';
                    $.each(errors, function(key, value) {
                        errorMessages += value[0] + '<br>';
                    });
                    showToast('error', errorMessages);
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                var message = response.message || 'An error occurred.';
                showToast('error', message);
            }
        });
    });

    // Students
    var students_table = $('#students-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/admin/students",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user.name', name: 'user.name' },
            { data: 'user.email', name: 'user.email' },
            { data: 'school.name', name: 'school.name' },
            { data: 'class_model.name', name: 'classModel.name' },
            { data: 'status', name: 'status', searchable: false, orderable: false },
            { data: 'actions', name: 'actions', searchable: false, orderable: false }
        ]
    });

    $('#add-student-btn').on('click', function() {
        $('#student-modal-label').text('Add New Student');
        $('#student-form-method').val('POST');
        $('#student-form').attr('action', "/admin/students");
        $('#student-id').val('');
        $.get("/admin/students/create", function(data) {
            $('#student-form-content').html(data);
            $('.select2').select2();
            $('#student-modal').modal('show');
        });
    });

    $('#students-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $('#student-modal-label').text('Edit Student');
        $('#student-form-method').val('PUT');
        $('#student-form').attr('action', "/admin/students/" + id);
        $('#student-id').val(id);
        $.get("/admin/students/" + id + '/edit', function(data) {
            $('#student-form-content').html(data);
            $('.select2').select2();
            $('#student-modal').modal('show');
        });
    });

    $('#save-student-btn').on('click', function() {
        var form = $('#student-form');
        var url = form.attr('action');
        var method = $('#student-form-method').val();
        var data = form.serialize();

        $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#student-modal').modal('hide');
                    students_table.ajax.reload();
                    showToast('success', response.message);
                } else {
                    var errors = response.errors;
                    var errorMessages = '';
                    $.each(errors, function(key, value) {
                        errorMessages += value[0] + '<br>';
                    });
                    showToast('error', errorMessages);
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                var message = response.message || 'An error occurred.';
                showToast('error', message);
            }
        });
    });

    // Classes
    var classes_table = $('#classes-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/admin/classes",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'school.name', name: 'school.name' },
            { data: 'teacher.user.name', name: 'teacher.user.name' },
            { data: 'students_count', name: 'students_count', searchable: false },
            { data: 'status', name: 'status', searchable: false, orderable: false },
            { data: 'actions', name: 'actions', searchable: false, orderable: false }
        ]
    });

    $('#add-class-btn').on('click', function() {
        $('#class-modal-label').text('Add New Class');
        $('#class-form-method').val('POST');
        $('#class-form').attr('action', "/admin/classes");
        $('#class-id').val('');
        $.get("/admin/classes/create", function(data) {
            $('#class-form-content').html(data);
            $('.select2').select2();
            $('#class-modal').modal('show');
        });
    });

    $('#classes-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $('#class-modal-label').text('Edit Class');
        $('#class-form-method').val('PUT');
        $('#class-form').attr('action', "/admin/classes/" + id);
        $('#class-id').val(id);
        $.get("/admin/classes/" + id + '/edit', function(data) {
            $('#class-form-content').html(data);
            $('.select2').select2();
            $('#class-modal').modal('show');
        });
    });

    $('#save-class-btn').on('click', function() {
        var form = $('#class-form');
        var url = form.attr('action');
        var method = $('#class-form-method').val();
        var data = form.serialize();

        $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#class-modal').modal('hide');
                    classes_table.ajax.reload();
                    showToast('success', response.message);
                } else {
                    var errors = response.errors;
                    var errorMessages = '';
                    $.each(errors, function(key, value) {
                        errorMessages += value[0] + '<br>';
                    });
                    showToast('error', errorMessages);
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                var message = response.message || 'An error occurred.';
                showToast('error', message);
            }
        });
    });

    // Subjects
    var subjects_table = $('#subjects-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/admin/subjects",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'code', name: 'code' },
            { data: 'school.name', name: 'school.name' },
            { data: 'teacher.user.name', name: 'teacher.user.name' },
            { data: 'status', name: 'status', searchable: false, orderable: false },
            { data: 'actions', name: 'actions', searchable: false, orderable: false }
        ]
    });

    $('#add-subject-btn').on('click', function() {
        $('#subject-modal-label').text('Add New Subject');
        $('#subject-form-method').val('POST');
        $('#subject-form').attr('action', "/admin/subjects");
        $('#subject-id').val('');
        $.get("/admin/subjects/create", function(data) {
            $('#subject-form-content').html(data);
            $('.select2').select2();
            $('#subject-modal').modal('show');
        });
    });

    $('#subjects-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $('#subject-modal-label').text('Edit Subject');
        $('#subject-form-method').val('PUT');
        $('#subject-form').attr('action', "/admin/subjects/" + id);
        $('#subject-id').val(id);
        $.get("/admin/subjects/" + id + '/edit', function(data) {
            $('#subject-form-content').html(data);
            $('.select2').select2();
            $('#subject-modal').modal('show');
        });
    });

    $('#save-subject-btn').on('click', function() {
        var form = $('#subject-form');
        var url = form.attr('action');
        var method = $('#subject-form-method').val();
        var data = form.serialize();

        $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#subject-modal').modal('hide');
                    subjects_table.ajax.reload();
                    showToast('success', response.message);
                } else {
                    var errors = response.errors;
                    var errorMessages = '';
                    $.each(errors, function(key, value) {
                        errorMessages += value[0] + '<br>';
                    });
                    showToast('error', errorMessages);
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                var message = response.message || 'An error occurred.';
                showToast('error', message);
            }
        });
    });

    // Parents
    var parents_table = $('#parents-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/admin/parents",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user.name', name: 'user.name' },
            { data: 'user.email', name: 'user.email' },
            { data: 'phone', name: 'phone' },
            { data: 'students_count', name: 'students_count', searchable: false },
            { data: 'status', name: 'status', searchable: false, orderable: false },
            { data: 'actions', name: 'actions', searchable: false, orderable: false }
        ]
    });

    $('#add-parent-btn').on('click', function() {
        $('#parent-modal-label').text('Add New Parent');
        $('#parent-form-method').val('POST');
        $('#parent-form').attr('action', "/admin/parents");
        $('#parent-id').val('');
        $.get("/admin/parents/create", function(data) {
            $('#parent-form-content').html(data);
            $('.select2').select2();
            $('#parent-modal').modal('show');
        });
    });

    $('#parents-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $('#parent-modal-label').text('Edit Parent');
        $('#parent-form-method').val('PUT');
        $('#parent-form').attr('action', "/admin/parents/" + id);
        $('#parent-id').val(id);
        $.get("/admin/parents/" + id + '/edit', function(data) {
            $('#parent-form-content').html(data);
            $('.select2').select2();
            $('#parent-modal').modal('show');
        });
    });

    $('#save-parent-btn').on('click', function() {
        var form = $('#parent-form');
        var url = form.attr('action');
        var method = $('#parent-form-method').val();
        var data = form.serialize();

        $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#parent-modal').modal('hide');
                    parents_table.ajax.reload();
                    showToast('success', response.message);
                } else {
                    var errors = response.errors;
                    var errorMessages = '';
                    $.each(errors, function(key, value) {
                        errorMessages += value[0] + '<br>';
                    });
                    showToast('error', errorMessages);
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                var message = response.message || 'An error occurred.';
                showToast('error', message);
            }
        });
    });

    // Attendance
    var attendance_table = $('#attendance-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/admin/attendance",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'student.user.name', name: 'student.user.name' },
            { data: 'class_model.name', name: 'classModel.name' },
            { data: 'date', name: 'date' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions', searchable: false, orderable: false }
        ]
    });

    $('#attendance-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $('#attendance-modal-label').text('Edit Attendance');
        $('#attendance-form-method').val('PUT');
        $('#attendance-form').attr('action', "/admin/attendance/" + id);
        $('#attendance-id').val(id);
        $.get("/admin/attendance/" + id + '/edit', function(data) {
            $('#attendance-form-content').html(data);
            $('#attendance-modal').modal('show');
        });
    });

    $('#save-attendance-btn').on('click', function() {
        var form = $('#attendance-form');
        var url = form.attr('action');
        var method = $('#attendance-form-method').val();
        var data = form.serialize();

        $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#attendance-modal').modal('hide');
                    attendance_table.ajax.reload();
                    showToast('success', response.message);
                } else {
                    showToast('error', response.message || 'An error occurred.');
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                var message = response.message || 'An error occurred.';
                showToast('error', message);
            }
        });
    });

    // Grades
    var grades_table = $('#grades-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/admin/grades",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'student.user.name', name: 'student.user.name' },
            { data: 'subject.name', name: 'subject.name' },
            { data: 'class_model.name', name: 'classModel.name' },
            { data: 'marks', name: 'marks', searchable: false, orderable: false },
            { data: 'grade', name: 'grade' },
            { data: 'exam_date', name: 'exam_date' },
            { data: 'actions', name: 'actions', searchable: false, orderable: false }
        ]
    });

    $('#add-grade-btn').on('click', function() {
        $('#grade-modal-label').text('Add New Grade');
        $('#grade-form-method').val('POST');
        $('#grade-form').attr('action', "/admin/grades");
        $('#grade-id').val('');
        $.get("/admin/grades/create", function(data) {
            $('#grade-form-content').html(data);
            $('.select2').select2();
            $('#grade-modal').modal('show');
        });
    });

    $('#grades-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $('#grade-modal-label').text('Edit Grade');
        $('#grade-form-method').val('PUT');
        $('#grade-form').attr('action', "/admin/grades/" + id);
        $('#grade-id').val(id);
        $.get("/admin/grades/" + id + '/edit', function(data) {
            $('#grade-form-content').html(data);
            $('.select2').select2();
            $('#grade-modal').modal('show');
        });
    });

    $('#save-grade-btn').on('click', function() {
        var form = $('#grade-form');
        var url = form.attr('action');
        var method = $('#grade-form-method').val();
        var data = form.serialize();

        $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#grade-modal').modal('hide');
                    grades_table.ajax.reload();
                    showToast('success', response.message);
                } else {
                    var errors = response.errors;
                    var errorMessages = '';
                    $.each(errors, function(key, value) {
                        errorMessages += value[0] + '<br>';
                    });
                    showToast('error', errorMessages);
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                var message = response.message || 'An error occurred.';
                showToast('error', message);
            }
        });
    });

    // Common Delete and Toggle Status
    var deleteUrl;
    $(document).on('click', '.delete-btn', function() {
        deleteUrl = $(this).data('url');
        $('#delete-modal').modal('show');
    });

    $('#confirm-delete-btn').on('click', function() {
        $.ajax({
            url: deleteUrl,
            type: 'DELETE',
            success: function(response) {
                if (response.success) {
                    $('#delete-modal').modal('hide');
                    showToast('success', response.message);
                    if (typeof schools_table !== 'undefined') { schools_table.ajax.reload(); }
                    if (typeof teachers_table !== 'undefined') { teachers_table.ajax.reload(); }
                    if (typeof students_table !== 'undefined') { students_table.ajax.reload(); }
                    if (typeof classes_table !== 'undefined') { classes_table.ajax.reload(); }
                    if (typeof subjects_table !== 'undefined') { subjects_table.ajax.reload(); }
                    if (typeof parents_table !== 'undefined') { parents_table.ajax.reload(); }
                    if (typeof attendance_table !== 'undefined') { attendance_table.ajax.reload(); }
                    if (typeof grades_table !== 'undefined') { grades_table.ajax.reload(); }
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                var message = response.message || 'An error occurred.';
                showToast('error', message);
            }
        });
    });

    $(document).on('click', '.toggle-status-btn', function() {
        var url = $(this).data('url');
        $.ajax({
            url: url,
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    if (typeof schools_table !== 'undefined') { schools_table.ajax.reload(); }
                    if (typeof teachers_table !== 'undefined') { teachers_table.ajax.reload(); }
                    if (typeof students_table !== 'undefined') { students_table.ajax.reload(); }
                    if (typeof classes_table !== 'undefined') { classes_table.ajax.reload(); }
                    if (typeof subjects_table !== 'undefined') { subjects_table.ajax.reload(); }
                    if (typeof parents_table !== 'undefined') { parents_table.ajax.reload(); }
                }
            }
        });
    });
});
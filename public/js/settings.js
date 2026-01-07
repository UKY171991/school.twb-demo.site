$(document).ready(function() {
    $('#markingSettingsForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#markingSettingsModal').modal('hide');
                if (response.success) {
                    toastr.success(response.success);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.each(errors, function(key, value) {
                    var input = $('[name="' + key + '"]');
                    input.addClass('is-invalid');
                    input.after('<span class="invalid-feedback">' + value[0] + '</span>');
                });
            }
        });
    });

    $('#gradingSettingsForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#gradingSettingsModal').modal('hide');
                if (response.success) {
                    toastr.success(response.success);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.each(errors, function(key, value) {
                    var input = $('[name="' + key + '"]');
                    input.addClass('is-invalid');
                    input.after('<span class="invalid-feedback">' + value[0] + '</span>');
                });
            }
        });
    });

    $('#schoolSettingsForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#schoolSettingsModal').modal('hide');
                if (response.success) {
                    // Show success message using a toast or alert
                    toastr.success(response.success);
                    // Optionally, update parts of the page without reloading
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                // Clear previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.each(errors, function(key, value) {
                    var fieldName = key.replace('settings.', '');
                    var input = $('[name="settings[' + fieldName + ']"]');
                    input.addClass('is-invalid');
                    input.after('<span class="invalid-feedback">' + value[0] + '</span>');
                });
            }
        });
    });
});

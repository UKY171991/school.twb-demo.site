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
        
        // Log form data for debugging
        console.log('Submitting school settings form...');
        for (var pair of formData.entries()) {
            console.log(pair[0] + ':', pair[1]);
        }

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                // Add progress tracking
                this.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        var percent = Math.round((e.loaded / e.total) * 100);
                        console.log('Upload progress: ' + percent + '%');
                    }
                });
            },
            success: function(response) {
                console.log('Upload success:', response);
                $('#schoolSettingsModal').modal('hide');
                if (response.success) {
                    // Show success message using a toast or alert
                    toastr.success(response.success);
                    // Reload page after short delay to show updated logo/favicon
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr, status, error) {
                console.log('Upload error:', xhr.responseText);
                console.log('Status:', status);
                console.log('Error:', error);
                var errors = xhr.responseJSON ? xhr.responseJSON.errors : {};
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

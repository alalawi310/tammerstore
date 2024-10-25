$(document).ready(function() {
    var loader = $('#loader');

    // Show loader when form is submitted
    $('form').submit(function() {
        loader.fadeIn();
    });

    $(document).on('click', 'a[href^="tel:"], a[href^="mail:"], a[href^="mailto:"]  ', function(event) {
        event.preventDefault();
        loader.fadeOut();
    });

    // Show loader on submit button click with validation
    $(document).on('click', 'input[type="submit"]', function(event) {
        var isValid = true;
        var form = $(this).closest('form');

        // Find and validate required fields
        form.find(':input[required], select[required]').each(function() {
            if ($(this).val().trim() === '') {
                isValid = false;
                $(this).addClass('is-invalid');
                // Append span if it doesn't exist
                if ($(this).next('.invalid-feedback').length === 0) {
                    $(this).after('<span class="invalid-feedback" style="color: red;">This field is required.</span>');
                }
                $(this).next('.invalid-feedback').show();
            } else {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').hide();
            }
        });

        // Validate fields with min/max attributes
        form.find(':input').each(function() {
            var min = $(this).attr('min');
            var max = $(this).attr('max');
            var value = $(this).val().trim();

            if (min !== undefined && parseFloat(value) < parseFloat(min)) {
                isValid = false;
                $(this).addClass('is-invalid');
                // Append span if it doesn't exist
                if ($(this).next('.invalid-feedback').length === 0) {
                    $(this).after('<span class="invalid-feedback" style="color: red;"></span>');
                }
                $(this).next('.invalid-feedback').text('Value must be greater than or equal to ' + min + '.').show();
            } else if (max !== undefined && parseFloat(value) > parseFloat(max)) {
                isValid = false;
                $(this).addClass('is-invalid');
                // Append span if it doesn't exist
                if ($(this).next('.invalid-feedback').length === 0) {
                    $(this).after('<span class="invalid-feedback" style="color: red;"></span>');
                }
                $(this).next('.invalid-feedback').text('Value must be less than or equal to ' + max + '.').show();
            }
        });

        if (!isValid) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            loader.fadeIn();
        }
    });

    // Hide the validation message when user starts typing
    $(document).on('input', ':input[required], select[required]', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').hide();
    });

    // Hide loader for links with data-ajax-popup attribute
    $(document).on('click', 'a[data-ajax-popup="true"]', function() {
        loader.fadeOut();
    });

    // Handle export button click
    $(document).on('click', '.export-btn', function(event) {
        event.preventDefault();
        loader.fadeIn();

        // Fetch file download
        fetch($(this).attr('href'))
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                const title = this.getAttribute('filename') || 'Export';
                a.download = title + '.xlsx';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                loader.fadeOut();
            })
            .catch(() => {
                loader.fadeOut();
                alert('File download failed.');
            });
    });

    // Handle page events
    $(window).on('beforeunload', function() {
        loader.fadeIn(); // Show loader when leaving the page
    });

    $(window).on('load', function() {
        loader.fadeOut(); // Hide loader when page is fully loaded
    });

    $(window).on('pageshow', function(event) {
        if (event.originalEvent.persisted) {
            loader.fadeOut(); // Hide loader when page is shown from the cache
        }
    });

});

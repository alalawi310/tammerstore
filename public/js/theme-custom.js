// Set csrf token in header
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Key change text JS
$(document).ready(function() {
    $(document).on('keyup', 'input[name^="array"], textarea[name^="array"], select[name^="array"]', function() {
        var id = $(this).attr('id');
        var previewId = id + '_preview';
        var previewValue = $(this).val();

        $('#publish-theme-btn').prop('disabled', true);
        $('#save-theme-btn').prop('disabled', false);
        // Check if the value contains HTML tags
        var containsHtml = /<[a-z][\s\S]*>/i.test(previewValue);
        var iframe = $('#theme_preview_section iframe').contents();
        if (containsHtml) {
            iframe.find('#' + previewId).html(previewValue);
        } else {
            iframe.find('#' + previewId).text(previewValue);
        }
    });

    $(document).on('change', 'input[name^="array"], textarea[name^="array"], select[name^="array"]', function() {
        var id = $(this).attr('id');
        var previewId = id + '_preview';
        var previewValue = $(this).val();
        var containsHtml = /<[a-z][\s\S]*>/i.test(previewValue);
        var iframe = $('#theme_preview_section iframe').contents();
        if (containsHtml) {
            iframe.find('#' + previewId).html(previewValue);
        } else {
            iframe.find('#' + previewId).text(previewValue);
        }
        if ($(this).attr('type') === 'file') {
            previewImage(iframe, previewId);
        }
        $('#publish-theme-btn').prop('disabled', true);
        $('#save-theme-btn').prop('disabled', false);
    });


    // Check if the iframe has loaded its content
    $('#theme_preview_section iframe').on('load', function () {
        var iframe = $(this).contents();
        var headerHeight = iframe.find('header').outerHeight();
        iframe.find('header').css('position', 'relative');
        iframe.find('section').first().css('margin-top', '0px');
        
        defaultShowEditSection(iframe);

        // Bind iframe section controls
        iframe.on('click', '#up-section-btn', function (e) {
            e.preventDefault();
            moveSection($(this), 'up');
        });

        iframe.on('click', '#down-section-btn', function (e) {
            e.preventDefault();
            moveSection($(this), 'down');
        });

        iframe.on('click', '#hide-section-btn', function (e) {
            e.preventDefault();
            toggleSectionVisibility($(this), 'hide');
        });

        iframe.on('click', '#show-section-btn', function (e) {
            e.preventDefault();
            toggleSectionVisibility($(this), 'show');
        });

        iframe.on('click', 'header,section,footer', function(e) {
            e.preventDefault();
            selectSection($(this));
        });

        function selectSection(section) {
            if (section.hasClass('highlighted')) return;

            iframe.find('header, section, footer').removeClass('highlighted').css('pointer-events', 'auto');
            iframe.find('.custome_tool_bar').empty();
            
            updateToolbarVisibility(section);
            section.addClass('highlighted').css('pointer-events', 'none');
            section.find('.custome_tool_bar').html($('#default_tool_bar').html());
            section.find('.custome_tool_bar').css('pointer-events', 'auto');
            showEditSection(section);
        }

        function updateToolbarVisibility(section) {
            var is_hide = parseInt(section.attr('data-hide'));
            if (is_hide === 0) {
                $('#default_tool_bar').find('#show-section-btn').hide();
                $('#default_tool_bar').find('#hide-section-btn').show();
            } else {
                $('#default_tool_bar').find('#hide-section-btn').hide();
                $('#default_tool_bar').find('#show-section-btn').show();
            }
        }

        function moveSection(button, direction) {
            var section = button.closest('header, section, footer');
            var order = parseInt(section.attr('data-index'));
            if (isNaN(order)) return;
            if (optionOrderValidation(direction, order)) {
                changePosition(order, direction === 'up' ? order - 1 : order + 1, direction);
                $('#save-theme-btn').prop('disabled', false);
            }
        }

        function toggleSectionVisibility(button, action) {
            var section = button.closest('header, section, footer');
            hideShowSection(section, action);
            $('#save-theme-btn').prop('disabled', false);
        }

        function defaultShowEditSection(iframe) {
            // Use iframe context to find the header
            var headerSection = iframe.find('header');
            
            if (headerSection.length === 0) {
                return false;
            }
            
            if (headerSection.hasClass('highlighted')) {
                // Section is already highlighted, do nothing
                return false;
            }
        
            // Highlight the header section and update the toolbar
            headerSection.addClass('highlighted');
            headerSection.find('.custome_tool_bar').html('');
        
            var is_hide = parseInt(headerSection.attr('data-hide'));
        
            if (is_hide === 0) {
                $('#default_tool_bar').find('#show-section-btn').css('display', 'none');
                $('#default_tool_bar').find('#hide-section-btn').css('display', 'inline-flex');
            } else {
                $('#default_tool_bar').find('#hide-section-btn').css('display', 'none');
                $('#default_tool_bar').find('#show-section-btn').css('display', 'inline-flex');
            }
        
            headerSection.addClass('highlighted');
            headerSection.find('.custome_tool_bar').html($('#default_tool_bar').html());

            // Disable pointer events for the highlighted section to stop clicks
            headerSection.css('pointer-events', 'none');
            headerSection.find('.custome_tool_bar').css('pointer-events', 'auto');
            showEditSection(headerSection);
        }
        // Validation
        function optionOrderValidation(type, order) {
            var maxIndex = 0;
            // Select the elements and iterate over them
            iframe.find('header, section, footer').each(function() {
                // Get the data-index attribute value of the current element
                var dataIndex = parseInt($(this).attr('data-index'));

                // Check if the data-index value is valid (not NaN) and greater than the current maximum
                if (!isNaN(dataIndex) && dataIndex > maxIndex) {
                    // Update the maximum data-index value
                    maxIndex = dataIndex;
                }
            });

            if (type == 'up' && order === 0) {
                return false;
            } else if (type == 'down' && order === maxIndex) {
                return false;
            }

            return true;
        }

        // Change section position JS
        function changePosition(currentIndex, newPosition, type) {
            const elements = iframe.find('[data-index]');
            const targetElement = elements.filter(`[data-index="${currentIndex}"]`);

            if (targetElement.length > 0) {
                if (type === 'up') {
                    // Insert the target element at the new position
                    elements.eq(newPosition).before(targetElement);
                } else {
                    // Insert the target element at the new position
                    elements.eq(newPosition).after(targetElement);
                }
                // Update data-index attributes for all elements
                iframe.find('[data-index]').each(function (index) {
                    $(this).attr('data-index', index);
                });
            } else {
                console.error('Element with data-index ' + currentIndex + ' not found.');
            }
        }
    });  

    // Save Theme script
    $(document).on('click', '#save-theme-btn', function(e) {
        e.preventDefault();
        var array = [];
        $('#loader').fadeIn();
    
        // Ensure we are referencing elements within the iframe
        var iframeContents = $('#theme_preview_section iframe').contents();
    
        iframeContents.find('[data-index]').each(function (index) {
            var obj = {};
            obj['order'] = index;
            obj['section'] = $(this).attr('data-section');
            obj['theme'] = $(this).attr('data-theme');
            obj['store'] = $(this).attr('data-store');
            obj['id'] = $(this).attr('data-value');
            obj['is_hide'] = $(this).attr('data-hide');
    
            array.push(obj);
        });
    
        $.ajax({
            url: saveThemeRoute,
            data: {
                array: array,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            success: function(data) {
                //$('#loader').fadeOut();
                if (data.error) {
                    show_toastr('Error', data.error, 'error');
                } else {
                    saveThemePageChanges($('.sidebar_form form'), true);
                    $('#save-theme-btn').prop('disabled', true);
                    if (data.is_publish == 1) {
                        $('#publish-theme-btn').prop('disabled', true);
                    } else {
                        $('#publish-theme-btn').prop('disabled', false);
                    }
                }
            },
            error: function(data) {
                data = data.responseJSON;
                show_toastr('Error', data.error, 'error');
            },
            complete: function() {
                $('#loader').fadeIn();
                location.reload();
            }
        });
    });
    

    // Publish Theme script
    $(document).on('click', '#publish-theme-btn', function(e) {
        e.preventDefault();
        $('#loader').fadeIn();
        
        var themeUrl = $('#publish_theme_url').val();
        var themeId = $('#publish_theme_id').val();
        var storeId = $('#publish_store_id').val();
        var isPublish = $('#publish_is_publish').val();
    
        $.ajax({
            url: themeUrl,
            data: {
                theme_id: themeId,
                store_id: storeId,
                is_publish: isPublish,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            success: function(data) {
                $('#loader').fadeOut();
                if (data.error) {
                    show_toastr('Error', data.error, 'error');
                } else {
                    if (data.data.content) {
                        var iframe = $('#theme_preview_section iframe');
                        iframe.contents().find('body').html(data.data.content);
    
                        // Initialize Theme Sliders
                        //initializeThemeSliders();
                    }
    
                    $('#save-theme-btn').prop('disabled', true);
                    if (data.is_publish == 1) {
                        $('#publish-theme-btn').prop('disabled', true);
                    } else {
                        $('#publish-theme-btn').prop('disabled', false);
                    }
                    show_toastr('Success', data.msg, 'success');
                }
                location.reload();
            },
            error: function(data) {
                $('#loader').fadeOut();
                data = data.responseJSON;
                show_toastr('Error', data.error, 'error');
            }
        });
    });

    // Save theme changes in database
    $('button[type="submit"]').on('click', function (e) {
        e.preventDefault(); // Prevent the default form submission
        saveThemePageChanges($(this), true);
    });

    // Add Slider Row
    $(document).on('click', '.add-new-slider-btn', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#publish-theme-btn').prop('disabled', true);
        $('#save-theme-btn').prop('disabled', false);
    
        var iframeContents = $('#theme_preview_section iframe').contents();
        var firstRow = $('.slider-body-rows .row.slider_0:first');
        var firstSliderRow = iframeContents.find('.slick-track .home-banner-content:first');
        var firstRowClone = firstRow.clone();
        var firstSliderRowClone = firstSliderRow.clone();
        
        // Reset input field array index
        var newIndex = $('.slider-body-rows .row').length;
    
        // Modify the cloned elements
        firstSliderRowClone.attr('data-slick-index', newIndex);
        firstSliderRowClone.attr('tabindex', -1);
        firstSliderRowClone.attr('aria-hidden', false);
        firstSliderRowClone.removeClass('slick-current');
        firstSliderRowClone.removeClass('slick-active');
    
        // Update the IDs of specific elements within the cloned element
        firstSliderRowClone.find('#slider_title_0_preview').attr('id', 'slider_title_' + newIndex + '_preview');
        firstSliderRowClone.find('#slider_sub_title_0_preview').attr('id', 'slider_sub_title_' + newIndex + '_preview');
        firstSliderRowClone.find('#slider_description_0_preview').attr('id', 'slider_description_' + newIndex + '_preview');
        firstSliderRowClone.find('#slider_first_button_0_preview').attr('id', 'slider_first_button_' + newIndex + '_preview');
        firstSliderRowClone.find('#slider_second_button_0_preview').attr('id', 'slider_second_button_' + newIndex + '_preview');
    
        // Check if the cloned element contains the elements you want to update
        firstRowClone.find('#slider_title_0').attr('id', 'slider_title_' + newIndex);
        firstRowClone.find('#slider_sub_title_0').attr('id', 'slider_sub_title_' + newIndex);
        firstRowClone.find('#slider_description_0').attr('id', 'slider_description_' + newIndex);
        firstRowClone.find('#slider_first_button_0').attr('id', 'slider_first_button_' + newIndex);
        firstRowClone.find('#slider_second_button_0').attr('id', 'slider_second_button_' + newIndex);
    
        $('.slider-body-rows').append(firstRowClone);
    
        firstRowClone.find('input[name="array[section][title][text][0]"]').attr('name', 'array[section][title][text][' + newIndex + ']');
        firstRowClone.find('input[name="array[section][sub_title][text][0]"]').attr('name', 'array[section][sub_title][text][' + newIndex + ']');
        firstRowClone.find('textarea[name="array[section][description][text][0]"]').attr('name', 'array[section][description][text][' + newIndex + ']');
        firstRowClone.find('input[name="array[section][button_first][text][0]"]').attr('name', 'array[section][button_first][text][' + newIndex + ']');
        firstRowClone.find('input[name="array[section][button_second][text][0]"]').attr('name', 'array[section][button_second][text][' + newIndex + ']');
    
        firstRowClone.find('.slider-collspan').attr('data-bs-target', '#slider_' + newIndex);
        firstRowClone.find('.slider-collspan').attr('aria-controls', 'slider_' + newIndex);
        firstRowClone.find('.slider-collspan-body').attr('id', 'slider_' + newIndex);
        firstRowClone.find('.accordion-header').attr('id', 'flush-headingOne' + newIndex);
        firstRowClone.find('.slider-collspan-body').attr('aria-labelledby', 'flush-headingOne' + newIndex);
    
        var loop = parseInt($('#slider-loop-number').val()) + 1;
        $('#slider-loop-number').val(loop);
        $('.delete-slider-btn').prop('disabled', loop < 4);
    
        var bannerSlider = iframeContents.find('.banner-slider');
        if (bannerSlider.length > 0) {
            if (typeof bannerSlider.slick === 'function') {
                if (!bannerSlider.hasClass('slick-initialized')) {
                    bannerSlider.slick({
                        // Your Slick options here
                    });
                }
                bannerSlider.slick('slickAdd', firstSliderRowClone);
                bannerSlider.slick('refresh');
                var slickIndex = bannerSlider.find('.slick-slide').length - 1;
                bannerSlider.slick('slickGoTo', slickIndex);
            } else {
                console.error('Slick is not a function on the bannerSlider object.');
            }
        } else {
            console.error('bannerSlider element not found.');
        }
    });
    
    // Delete Slider Row
    $(document).on('click', '.delete-slider-btn', function(e) {
        e.preventDefault(); // Prevent the default form submission
    
        $('#publish-theme-btn').prop('disabled', true);
        $('#save-theme-btn').prop('disabled', false);
    
        var loop = parseInt($('#slider-loop-number').val());
        if (loop < 4) {
            $('.delete-slider-btn').prop('disabled', true);
            return false;
        }
    
        $('.slider-body-rows .row:last').remove();
    
        var totalLoop = loop - 1;
        $('#slider-loop-number').val(totalLoop);
    
        $('.delete-slider-btn').prop('disabled', totalLoop < 4);
    
        var iframeContents = $('#theme_preview_section iframe').contents();
        var slickIndex = parseInt(iframeContents.find('.banner-content-slider .banner-content-item:last').attr('data-slick-index'));
    
        // Remove the slide
        iframeContents.find('.banner-content-slider').slick('slickRemove', slickIndex);
        // Refresh the Slick slider to reflect the changes
        iframeContents.find('.banner-content-slider').slick();
    });
    

    // Delete Slider Row
    $(document).on('change', 'select#product_type', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var productType = $(this).val();
        showHideCustomProductDropDown(productType);
    });
});

var header_hright = $('.preview-header-main').outerHeight();
$('.preview-header-main').next('.wrapper').css('margin-top', header_hright + 'px');

// Hide show section JS
function hideShowSection(element, type) {
    //var closestSection = element.closest('header,section,footer');
    if (type === 'show') {
        // Add CSS to the closest section
        element.css('opacity', 1);
        var newHideValue = 0;
        element.find('#show-section-btn').css('display', 'none');
        element.find('#hide-section-btn').css('display', 'inline-flex');
    } else {
        // Add CSS to the closest section
        element.css('opacity', 0.5);
        var newHideValue = 1;
        element.find('#hide-section-btn').css('display', 'none');
        element.find('#show-section-btn').css('display', 'inline-flex');
    }
    element.attr('data-hide', newHideValue);
}

// Hide show sidebar section JS
function showEditSection(element) {
    var sectionName = element.data('section') ?? 'header';
    var themeId = $('#publish_theme_id').val();
    var storeId = $('#publish_store_id').val();
    // Serialize the data object
    var requestData = { section_name: sectionName, store_id: storeId, theme_id: themeId };
    var serializedData = JSON.stringify(requestData);
    $.ajax({
        url: sidebarThemeRoute, // Use the form's action attribute as the URL
        type: 'POST', // Use the form's method attribute as the HTTP method
        contentType: 'application/json',
        data: serializedData, // Serialize form data
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('.sidebar_form').html('');
            $('.sidebar_form').html(data.data.content);
            $('.product_ids').hide();
            $('.category_ids').hide();

            var customProductSection = $('select#product_type');
            if (customProductSection && customProductSection.length > 0) {
                var productType = customProductSection.val();
                showHideCustomProductDropDown(productType);
            }
            if (sectionName === 'product_category') {
                $('.category_ids').show();
            }
        },
        error: function (error) {
            if (toast) {
                // Handle the error response
                // show_toastr('Error', error.error, 'error')
            }
        }
    });
    // Hide all sections
    $('div[data-section]').hide();
    // sidebarThemeRoute
    // Show the corresponding section based on the data-section attribute
    if (sectionName) {
        $('div[data-section="' + sectionName + '"]').show();
    } else {
        // Show a default section if no matching data-section attribute is found
        $('div[data-section="header"]').show();
    }
}

// Theme Changes Save function
function saveThemePageChanges(element, toast) {
    // Find the closest form element
    var closestForm = element.closest('form');
    // Create a FormData object to handle file uploads
    var formData = new FormData(closestForm[0]);
    // Perform AJAX request
    $.ajax({
        url: closestForm.attr('action'), // Use the form's action attribute as the URL
        type: closestForm.attr('method'), // Use the form's method attribute as the HTTP method
        data: formData, // Serialize form data
        contentType: false, // Important! Indicates that the request will be sent as multipart/form-data
        processData: false, // Important! Prevents jQuery from automatically processing the data
        success: function (data) {
            if (data.error) {
                show_toastr('Error', data.error, 'error')
            }
            else {
                if (data.data.content) {
                    $('#theme_preview_section').html('');
                    $('#theme_preview_section').html(data.data.content);
                    // Remove unwanted div element
                    //$('.nice-select.product_ids').remove();
                    // Initialize Theme Sliders
                    initializeThemeSliders();
                }

                if (toast) {
                    // Handle the success response
                    show_toastr('Success', data.msg, 'success')
                }
            }

            if (data.is_publish == 1) {
                $('#publish-theme-btn').prop('disabled', true);
            } else {
                $('#publish-theme-btn').prop('disabled', false);
            }
        },
        error: function (error) {
            if (toast) {
                // Handle the error response
                show_toastr('Error', error.error, 'error')
            }
        }
    });
}

function showHideCustomProductDropDown(productType) {
   if (productType == 'custom_product') {
    $('.category_ids').hide();
    $('.product_ids').show();
   } else if (productType == 'category_wise_product') {
    $('.product_ids').hide();
    $('.category_ids').show();
   }  else {
    $('.product_ids').hide();
    $('.category_ids').hide();
   }
}

// Function to display image preview
function previewImage(element, previewId) {
    var file = element.files[0] ?? null;

    if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            // Display the image preview
            $('.'+previewId).attr('src', e.target.result);
            $('#'+previewId).attr('src', e.target.result);
        };

        reader.readAsDataURL(file);
    }
}

var headerHeight = $('ifram header').outerHeight(); // Get the height of the header
    $('ifram header').nextAll('ifram section').first().css('margin-top', headerHeight + 'px');
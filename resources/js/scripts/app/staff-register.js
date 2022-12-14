$(function () {
    ('use strict');

    var assetsPath = '../../../app-assets/',
    registerMultiStepsWizard = document.querySelector('.register-multi-steps-wizard'),
    select = $('.select2'),
    mobileNumberMask = $('.mobile-number-mask');

    if ($('body').attr('data-framework') === 'laravel') {
        assetsPath = $('body').attr('data-asset-path');
    }

    $("#join_date").flatpickr({
        dateFormat: "d-m-Y"
    });

    $("#dob").flatpickr({
        dateFormat: "d-m-Y",
        maxDate: "today"
    });

    fetchWorkLocation($("#work_location_type").val());
    
    $("#work_location_type").change(function () {
        fetchWorkLocation($("#work_location_type").val());
    });
    

    // multi-steps registration
    // --------------------------------------------------------------------

    // Horizontal Wizard
    if (typeof registerMultiStepsWizard !== undefined && registerMultiStepsWizard !== null) {
        var numberedStepper = new Stepper(registerMultiStepsWizard),
        $form = $(registerMultiStepsWizard).find('form');
        $form.each(function () {
            var $this = $(this);
            $this.validate({
                rules: {
                    username: {
                        required: true,
                    },
                    email: {
                        // required: true,
                        email:true
                    },
                    password: {
                        required: true,
                        minlength: 8
                    },
                    'confirm-password': {
                        required: true,
                        minlength: 8,
                        equalTo: '#password'
                    },
                    name: {
                        required: true
                    },
                    gender: {
                        required: true
                    },
                    qualification: {
                        required: true
                    },
                    dob: {
                        required: true
                    },
                    aadhar_no: {
                        required: true,
                        minlength: 14,
                        maxlength: 14,
                    },
                    nationality: {
                        required: true
                    },
                    employee_no: {
                        required:true   
                    },
                    job_type: {
                        required: true
                    },
                    mobile: {
                        required: true,
                        minlength: 10,
                        maxlength:15
                    },
                    whatsapp: {
                        minlength: 10,
                        maxlength:15
                    },
                    address_line_1: {
                        required: true
                    },
                    city: {
                        required: true
                    },
                    district: {
                        required: true
                    },
                    state: {
                        required: true
                    },
                    pincode: {
                        required: true,
                        minlength: 6,
                        maxlength:6
                    },
                },
                messages: {
                    username: {
                        required: 'Please enter username.',
                    },
                    email: {
                        required: 'Please enter email address.',
                    },
                    name: {
                        required: 'Please enter full name.',
                    },                    
                    gender: {
                        required: 'Please select gender.',
                    },                    
                    qualification: {
                        required: 'Please select qualification.',
                    },                  
                    dob: {
                        required: 'Please select date of birth.',
                    },                  
                    aadhar_no: {
                        required: 'Please enter aadhaar number.',
                        minlength: 'Enter a valid aadhaar number.',
                    },                  
                    nationality: {
                        required: 'Please enter nationality.',
                    },                  
                    password: {
                        required: 'Enter new password.',
                        minlength: 'Enter at least 8 characters.'
                    },
                    'confirm-password': {
                        required: 'Please confirm new password.',
                        minlength: 'Enter at least 8 characters.',
                        equalTo: 'The password and its confirm are not the same.'
                    },
                    employee_no: {
                        required: 'Please enter employee number.'    
                    },
                    job_type: {
                        required: 'Please select job type.'
                    },
                    mobile: {
                        required: 'Please enter mobile number.'
                    },
                    address_line_1: {
                        required: 'Please enter address line 1.'
                    },
                    city: {
                        required: 'Please enter city.'
                    },
                    district: {
                        required: 'Please enter district.'
                    },
                    state: {
                        required: 'Please enter state.'
                    },
                    pincode: {
                        required: 'Please enter pincode.'
                    }
                }
            });
        });

        $(registerMultiStepsWizard)
        .find('.btn-next')
        .each(function () {
            $(this).on('click', function (e) {
                if($('form input[type="date"][step="any"]').length)
                    $('form input[type="date"]').removeAttr('step');
                
                var isValid = $(this).parent().siblings('form').length ? $(this).parent().siblings('form').valid() : true;
                if (isValid) {
                    numberedStepper.next();
                } else {
                    e.preventDefault();
                }
            });
        });

        $(registerMultiStepsWizard)
        .find('.btn-prev')
        .on('click', function () {
            numberedStepper.previous();
        });

        $(registerMultiStepsWizard)
        .find('.btn-submit')
        .on('click', function () {
            var isValid = true;//$(this).parent().siblings('form').valid();
            if (isValid) {
                var formData = $('form').serialize();
                console.log(formData);
                $.ajax({                    
                    url: $('#slug').length ? "/admin/staff/"+$('#slug').val() : "/staff",
                    type: $('#slug').length ? "PUT" : "POST",
                    data: formData,
                    success: function(response) {
                        if (response && response.success) {
                            toastr['success'](
                                response.message,
                                'Success!', {
                                    closeButton: true,
                                    tapToDismiss: false,
                                }
                            );
                            
                            window.location.href = $("#redirect-to").val();
                        } else {
                            toastr['error'](
                                response.message,
                                'Failed!', {
                                    closeButton: true,
                                    tapToDismiss: false,
                                }
                            );
                            
                        }
                    },
                    error: function(response) {
                        toastr['error'](
                            response.responseJSON.error,
                            'Error!', {
                                closeButton: true,
                                tapToDismiss: false,
                            }
                        );
                    }
                });
            }
        });
    }

    // select2
    var select = $(".select2");
    select.each(function () {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>');
        $this.select2({
        // the following code is used to disable x-scrollbar when click in select input and
        // take 100% width in responsive also
        dropdownAutoWidth: true,
        width: '100%',
        dropdownParent: $this.parent()
        });        
        
    });

    // Accepted Only Files
    var files = $('#files-form');
    files.dropzone({
        paramName: 'file', // The name that will be used to transfer the file
        maxFiles: 5,
        addRemoveLinks: true,
        dictRemoveFile: 'Remove',
        success: function (file, response) {
            if (response.success) {
                var el = document.createElement("div");   // Create a <button> element
                el.innerHTML = '<input type="hidden" name="files[]" value="' + response.path + '"/>';
                file.previewTemplate.appendChild(el);
            }
        }
    });

    // Accepted Only Files
    var photo = $('#profile-image-form');
    photo.dropzone({
        paramName: 'profile', // The name that will be used to transfer the file
        maxFiles: 1,
        addRemoveLinks: true,
        dictRemoveFile: 'Remove',
        acceptedFiles: 'image/*',
        success: function (profile, response) {
            if (response.success) {
                var el = document.createElement("div");   // Create a <button> element
                el.innerHTML = '<input type="hidden" name="profile_image" value="' + response.path + '"/>';
                profile.previewTemplate.appendChild(el);
            }
        }
    });


  // multi-steps registration
  // --------------------------------------------------------------------
});

function fetchWorkLocation(type,selected='') {
        var work_location = $("#work_location_slug");
        $.ajax({
            url: "/staff/work-locations/"+type,
            type: "GET",
            async: false,            
            success: function(response) {
                work_location.html('<option value="">Select Work Location</option>');
                $.each(response, function (i, data) {
                    if(selected == data.slug)
                        work_location.append('<option value="' + data.slug + '" selected="selected">' + data.name + '</option>');
                    else
                        work_location.append('<option value="' + data.slug + '">' + data.name + '</option>');
                })
            }
        });
    }

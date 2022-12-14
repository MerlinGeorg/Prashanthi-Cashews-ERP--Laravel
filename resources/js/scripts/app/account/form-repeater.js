/*=========================================================================================
    File Name: form-repeater.js
    Description: form repeater page specific js
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy HTML Admin Template
    Version: 1.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(function () {
  'use strict';
    var deleteElement;
    // form repeater jquery
    $('.invoice-repeater, .repeater-default').repeater({
        isFirstItemUndeletable: true,
        show: function () {
            $(this).slideDown();
            // Feather Icons
            if (feather) {
                feather.replace({ width: 14, height: 14 });
            }

            $('.select2-container').remove();
            //$(document).find('select').addClass('select2');
            $('select').select2();            
            $('.select2-container').css('width','100%');
        },
        hide: function (deleteElement) {
            var $this = $(this);
            Swal.fire({
                title: 'Are you sure to delete?',
                text: "You won't be able to revert!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete!',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-danger ms-1'
                },
                buttonsStyling: false
            }).then(function (result) {
                if (result.isConfirmed) {
                    $this.slideUp(deleteElement);                    
                }
            });
        }
    });
});

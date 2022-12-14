$(function () {
    'use strict';

    // form repeater jquery
    $('.invoice-repeater, .repeater-default').repeater({
        isFirstItemUndeletable: true,
        show: function () {
            $(this).slideDown();
            resetRepeaterSelectbox();
            // Feather Icons
            if (feather) {
                feather.replace({ width: 14, height: 14 });
            }
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
                    console.log(deleteElement)
                }
            });
        }
    });

    // $('.processor_type_checkbox').on('change',function () {
    //     if ($(this).is(":checked")) {
    //         $('.processing_capacity_'+$(this).val().replace(' ','')).removeAttr('disabled');
    //     } else {
    //         $('.processing_capacity_'+$(this).val().replace(' ','')).attr('disabled', true);
    //         $('.processing_capacity_'+$(this).val().replace(' ','')).val('');
    //     }
    // });

    $('#factory_slug').on('change', function () {
        fetchLotNumber();
        $('[data-repeater-item]').slice(2).remove();
        resetRepeaterSelectbox();
        factorySizeringStock();
    });

    $('#stockyard_rcn_stock_slug').on('change', function () {
        $('[data-repeater-item]').slice(2).remove();
        resetRepeaterSelectbox();
        factorySizeringStock();
    });

    $("#repeater-button").on('click', function () {
        factorySizeringStock();
    });

});

function fetchLotNumber() {
    var htmlval = '<option value="">Select</option>';

    let factory_slug = $('#factory_slug').val();
    var selectedStock = $("#stockyard_rcn_stock_slug").data('selected');

    if (factory_slug) {
        $.ajax({
            url: '/factory/stock/sizering/stock-by-factory/' + factory_slug,
            type: "GET",
            async: false,
            success: function (response) {
                if (response && response.success) {
                    if (response.data) {
                        response.data.forEach(stock => {
                            if (stock.slug == selectedStock) {
                                htmlval += '<option selected value="' + stock.slug + '">' + stock.lot_number + '</option>';
                            } else {
                                htmlval += '<option value="' + stock.slug + '">' + stock.lot_number + '</option>';
                            }
                        });
                    }
                }
            },
            error: function (response) {
            }
        });
    }

    $("#stockyard_rcn_stock_slug").html(htmlval);
}

function factorySizeringStock(updateAllSizeringStock = false) {

    let factory_slug = $('#factory_slug').val();
    let stockyard_rcn_stock_slug = $('#stockyard_rcn_stock_slug').val();
    var htmlval = '<option> Select </option>';

    if (factory_slug && stockyard_rcn_stock_slug) {
        $.ajax({
            url: '/factory/stock/sizering/list-by-factory/' + factory_slug + '/' + stockyard_rcn_stock_slug,
            type: "GET",
            async: false,
            success: function (response) {
                if (response.data) {
                    response.data.forEach(sizering => {
                        htmlval += '<option value="' + sizering.slug + '">' + sizering.sizering_number + '</option>';
                    });
                }
            },
            error: function (response) {
            }
        });
    }

    if (updateAllSizeringStock) {
        $('.sizering_stock').html(htmlval);
        $('.sizering_stock').each(function () {
            $(this).val($(this).data('selected'));
        });
    } else {
        $('.sizering_stock:last').html(htmlval);
    }
}

function resetRepeaterSelectbox() {
    $('[data-repeater-item] .select2-container').remove();
    $('[data-repeater-item] .select2').select2();
    $('[data-repeater-item] .select2-container').css('width', '100%');
}

// function getfactoryofchange(){
//     let factory_of = $('#factory_of').val();
//     if (factory_of == 'Prashanthi') {
//         $('.processing_type_section').show();
//         $('.processor_type_checkbox').removeAttr('disabled');
//         $('.processing_type_div').show();
//     } else {
//         $('.processing_type_section').hide();
//         $('.processor_type_checkbox').attr('disabled', true);
//         $('.processing_type_div').hide();
//     }
// }
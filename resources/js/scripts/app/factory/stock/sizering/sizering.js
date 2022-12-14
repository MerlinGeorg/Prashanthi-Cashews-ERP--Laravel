
$(function () {
  'use strict';

  // form repeater jquery
  $('.processingtype-repeater').repeater({
    isFirstItemUndeletable: true,
    show: function () {
      $(this).slideDown();
      // Feather Icons
      if (feather) {
        feather.replace({ width: 14, height: 14 });
      }
    },
    hide: function (deleteElement) {
      if (confirm('Are you sure you want to delete this element?')) {
        $(this).slideUp(deleteElement);
      }
    }
  });

  $('.processor_type_checkbox').on('change',function () {
    if ($(this).is(":checked")) {
      $('.processing_capacity_'+$(this).val().replace(' ','')).removeAttr('disabled');
    } else {
      $('.processing_capacity_'+$(this).val().replace(' ','')).attr('disabled', true);
      $('.processing_capacity_'+$(this).val().replace(' ','')).val('');
    }
  });
  $('#factory_slug').on('change',function () {
    $("#factory_stock_slug option").remove();
    let htmlval = '<option value="">Select</option>';
    $("#factory_stock_slug").append(htmlval);
    let factory_slug = $('#factory_slug').val();
    if (typeof(factory_slug) != 'undefined' && factory_slug != ''){
      let sizering_options = $.ajax({                    
          url: '/factory/stock/sizering/stock-by-factory/'+factory_slug,
          type: "GET",
          async:false,
          success: function(response) {
              if (response && response.success) {
                let htmlval = '<option value="">Select</option>';
                if (response.data) {
                  response.data.forEach(stock => { 
                    if (stock.slug == selectedStock) {
                      htmlval+= '<option selected value="'+stock.slug+'">'+stock.lot_number+'</option>';
                    } else {
                      htmlval+= '<option value="'+stock.slug+'">'+stock.lot_number+'</option>';
                    }
                    
                  });
                }
                $("#factory_stock_slug option").remove();
                  $("#factory_stock_slug").append(htmlval);
            }
          },
          error: function(response) {
          }
      });
  }
  });
  $('#factory_slug').trigger("change");
});


function getfactoryofchange(){
  let factory_of = $('#factory_of').val();
  if (factory_of == 'Prashanthi') {
      $('.processing_type_section').show();
      $('.processor_type_checkbox').removeAttr('disabled');
      $('.processing_type_div').show();
  } else {
    $('.processing_type_section').hide();
    $('.processor_type_checkbox').attr('disabled', true);
    $('.processing_type_div').hide();
  }
}
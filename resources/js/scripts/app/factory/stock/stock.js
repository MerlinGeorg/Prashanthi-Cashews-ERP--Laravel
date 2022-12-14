
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

  $(".number").on("input", function(evt) {
    var self = $(this);
    self.val(self.val().replace(/[^0-9\.]/g, ''));
    if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
    {
      evt.preventDefault();
    }
  });
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
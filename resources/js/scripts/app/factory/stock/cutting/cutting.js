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
                  }
              });
          }
      });

      $('#factory_slug').on('change', function () {       
          fetchLotNumber();
          $('[data-repeater-item]').slice(2).remove();
          resetRepeaterSelectbox();
          factoryBoilingStock();
      });
              
      $('#stockyard_rcn_stock_slug').on('change', function () {     
          $('[data-repeater-item]').slice(2).remove();
          resetRepeaterSelectbox();
          factoryBoilingStock();
      });
        
      $("#repeater-button").on('click', function(){
        factoryBoilingStock();
      });
  
  });
  
  function fetchLotNumber() {
    
      var htmlval = '<option value="">Select</option>';
  
      let factory_slug = $('#factory_slug').val();
      var selectedStock = $("#stockyard_rcn_stock_slug").data('selected');
  
      if (factory_slug) {
          $.ajax({
              url: '/factory/stock/cutting/stock-by-factory/' + factory_slug,
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
  
  function factoryBoilingStock(updateAllBoilingStock = false) {
      
      let factory_slug = $('#factory_slug').val();
      var selectedStock = $("#boiling_slug").data('selected');
      let stockyard_rcn_stock_slug = $('#stockyard_rcn_stock_slug').val();
      var htmlval = '<option value=" "> Select </option>';

      if (factory_slug  && stockyard_rcn_stock_slug ){
          $.ajax({                    
              url: '/factory/stock/boiling/list-by-factory/'+factory_slug+'/'+stockyard_rcn_stock_slug,
              type: "GET",
              async:false,
              success: function (response) { 
                  if (response.data) {
                    
                      response.data.forEach(boiling => {
                        if (boiling.slug == selectedStock) {
                            htmlval += '<option selected value="' + boiling.slug + '">' + boiling.boiling_number + '</option>';
                        } else {
                            htmlval += '<option value="' + boiling.slug + '">' + boiling.boiling_number + '</option>';
                        }     
                   
                        });
                  }
              },
              error: function(response) {
              }
          });        
      }
  
      if (updateAllBoilingStock) {
          $('.boiling_stock').html(htmlval);
          $('.boiling_stock').each(function () {
              $(this).val($(this).data('selected'));
          });
      } else {
          $('.boiling_stock:last').html(htmlval);
      }
  }
  
  function resetRepeaterSelectbox() {    
      $('[data-repeater-item] .select2-container').remove();
      $('[data-repeater-item] .select2').select2();            
      $('[data-repeater-item] .select2-container').css('width', '100%');
  }
  $('#cutting_type').on('change',function () {
    $('[data-repeater-item]').slice(2).remove();
    if ($(this).val() == 'machinery') {
        $('.machinery_sec').show();
        $('.traditional_sec').hide();
        factoryBoilingStock();
    } else if ($(this).val() == 'traditional') {
        $('.traditional_sec').show();
        $('.machinery_sec').hide();
        factoryBoilingStock();
    } 
    else {
      $('.machinery_sec').hide();
      $('.traditional_sec').hide();
    }
    
  });
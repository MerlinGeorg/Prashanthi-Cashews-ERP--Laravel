$(function () {
  'use strict';
  
      $('.invoice-repeater, .repeater-default').repeater({
          isFirstItemUndeletable: true,
          show: function () {
              $(this).slideDown();
              resetRepeaterSelectbox();
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




      $("#repeater-button").on('click', function(){
        resetRepeaterSelectbox();
  var data =2;
        fetchLotNumberRcnMark();
      });
  
  });


$('#rcn_mark').on('change', function () {

    var data = 2;
    fetchLotNumberRcnMark(data);
    resetRepeaterSelectbox();

});


$('#stockyardList').on('change', function () {
    $('[data-repeater-item]').slice(2).remove();
    resetRepeaterSelectbox();
    var data = 2;
    fetchLotNumberRcnMark(data);
});




  function fetchLotNumberRcnMark(updateAllBoilingStock=false) {
    
    var htmlval = '<option value="">Select</option>';

    let factory_slug = $('#stockyardList').val();
    var rcn_mark = $('#rcn_mark').val();
    if (rcn_mark!=null) {
        $.ajax({
            url: '/stockyard/stockyard_rcn_stock/list-by-rcnmark/' + factory_slug+'/'+rcn_mark,
            type: "GET",
            async: false,
            success: function (response) {
                if (response && response.success) {                       
                    if (response.data) {
                        response.data.forEach(stock  => {
                   
                                htmlval += '<option value="' + stock .lot_number + '">' + stock .lot_number + '</option>';
                                                
                        });
                    }
                }
            },
            error: function (response) {
            }
        });


        if (updateAllBoilingStock) {

            $('.boiling_stock').html(htmlval);
            $('.boiling_stock').each(function () {
                $(this).val($(this).data('selected'));
            });

        } else {
        
            $('.boiling_stock:last').html(htmlval);
        }
             
    }
    
}



  function fetchLotNumber() {
    
      var htmlval = '<option value="">Select</option>';
  
      let factory_slug = $('#stockyardList').val();
      var selectedStock = $("#stockyard_rcn_stock_slug").data('selected');
  
      if (factory_slug) {
          $.ajax({
              url: '/factory/stock/boiling/stock-by-factory/' + factory_slug,
              type: "GET",
              async: false,
              success: function (response) {
                  if (response && response.success) {                       
                      if (response.data) {
                          response.data.forEach(stock => {
                     
                                  htmlval += '<option value="' + stock.lot_number + '">' + stock.lot_number + '</option>';
                                                   
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
  
  

  function factoryBoilingStockplus(updateAllBoilingStock = false) {
      
    let factory_slug = $('#stockyardList').val();
    var selectedStock = $("#stockyard_rcn_stock_slug").data('selected');
    let stockyard_rcn_stock_slug = $('#stockyard_rcn_stock_slug').val();
    let stockyard_rcn_stock_slugZ = 1;
    var htmlval = '<option> Select </option>';
    
    if (factory_slug ){
        $.ajax({   
                           
            url: '/stockyard/list-by-stockyardMixComp/'+factory_slug+'/'+stockyard_rcn_stock_slugZ,
            type: "GET",
            async:false,
            success: function (response) { 
          
          
                if (response.data) {
                 
                    response.data.forEach(lot_number => {
                  
           
                          htmlval += '<option value="' + lot_number.lot_number + '">' + lot_number.lot_number + '</option>';
                  
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
        $('.boiling_stock').html(htmlval);
          $('.boiling_stock').each(function () {
              $(this).val($(this).data('selected'));
          });
     
        $('.boiling_stock:last').html(htmlval);
    }
}

  function factoryBoilingStock(updateAllBoilingStock = false) {
      
      let factory_slug = $('#stockyardList').val();
      var selectedStock = $("#stockyard_rcn_stock_slug").data('selected');
      let stockyard_rcn_stock_slug = $('#stockyard_rcn_stock_slug').val();
      let stockyard_rcn_stock_slugZ = 1;
      var htmlval = '<option> Select </option>';
      
      if (factory_slug ){
          $.ajax({   
                             
              url: '/stockyard/list-by-stockyardMixComp/'+factory_slug+'/'+stockyard_rcn_stock_slugZ,
              type: "GET",
              async:false,
              success: function (response) { 
            
            
                  if (response.data) {
                   
                      response.data.forEach(lot_number => {
                    
                      if (lot_number.lot_number == selectedStock) {
                             htmlval += '<option selected value="' + lot_number.lot_number + '">' + lot_number.lot_number + '</option>';

                            } else {
                            htmlval += '<option value="' + lot_number.lot_number + '">' + lot_number.lot_number + '</option>';
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
      
        $('.boiling_stock').html(htmlval);
     
          $('.boiling_stock').each(function () {
              $(this).val($(this).data('selected'));
         });
       
      }
  }
  
  function resetRepeaterSelectbox() {    
      $('[data-repeater-item] .select2-container').remove();
      $('[data-repeater-item] .select2').select2();            
      $('[data-repeater-item] .select2-container').css('width', '100%');
  }

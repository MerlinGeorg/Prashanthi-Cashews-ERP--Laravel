$(function () {
  'use strict';
   
    var dtInvoiceTable = $('.list-table'),
        assetPath = '../../../app-assets/';

  if ($('body').attr('data-framework') === 'laravel') {
    assetPath = $('body').attr('data-asset-path');
  }

  // datatable
  if (dtInvoiceTable.length) {
    var dtInvoice = dtInvoiceTable.DataTable({
      ajax: 'factory/list',
      "processing": true,
      "serverSide": true,
      autoWidth: false,
      columns: [
        // columns according to JSON
        { data: 'factory_name', name: 'factory_name' },
        { data: 'factory_short_name', name: 'factory_short_name' },
        { data: 'factory_reg_number', name: 'factory_reg_number' },
        { data: 'factory_state', name: 'factory_state' },
        { data: 'account_name', name: 'account_name' },
        { data: 'office_name', name: 'office_name' },
        { data: '' }
      ],
      columnDefs: [ 
        {
          // Actions
          targets: -1,
          title: 'Actions',
          width: '80px',
          orderable: false,
              render: function (data, type, full, meta) {
                  var action = '<div class="d-flex align-items-center col-actions">';
                  
                if (full['action']['view']) {
                    action += '<a class="me-1" href="' +
                        assetPath + 'admin/factory/' + full['slug']+
                    '" title="View Factory">' +
                    feather.icons['eye'].toSvg({ class: 'font-medium-2 text-body' }) +
                    '</a>' ;
                }   
                if (full['action']['edit']) {
                    action += '<a class="" href="'+assetPath + 'admin/factory/'+full['slug']+'/edit'+'" title="Edit Factory">' +
                    feather.icons['edit'].toSvg({ class: 'font-medium-2 text-body' }) +
                    '</a>';
                }           
                if (full['action']['delete']) {
                    action +='<a class="ms-1 delete-factory" data-slug="' + full['slug']+
                        '" title="Delete Factory">' +
                        feather.icons['trash'].toSvg({ class: 'font-medium-2 text-body' }) +
                        '</a>' ;
                  }
                  action += '</div>' +
                            '</div>';
                        
                        return action;
                
          }
        }
      ],
      order: [[0, 'desc']],
      dom:
        '<"row d-flex justify-content-between align-items-center m-1"' +
        '<"col-lg-6 d-flex align-items-center"l<"dt-action-buttons text-xl-end text-lg-start text-lg-end text-start "B>>' +
        '<"col-lg-6 d-flex align-items-center justify-content-lg-end flex-lg-nowrap flex-wrap pe-lg-1 p-0"f<"invoice_status ms-sm-2">>' +
        '>t' +
        '<"d-flex justify-content-between mx-2 row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      // Buttons with Dropdown
      buttons: [
      ],
      // For responsive popup
      initComplete: function () {
      },
      drawCallback: function () {
        $(document).find('[data-bs-toggle="tooltip"]').tooltip();

        const deleteStockyard = document.querySelector('.delete-factory');

  // Suspend User javascript
        if (deleteStockyard) {
          $('.delete-factory').on('click', function () {
            let slug = $(this).data('slug');
            Swal.fire({
              title: 'Are you sure to delete factory?',
              text: "You won't be able to revert!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Yes, delete factory!',
              customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ms-1'
              },
              buttonsStyling: false
            }).then(function (result) {
              if (result.value) {
                if (result.isConfirmed) {
                    $.ajax({
                      url: "factory/"+slug,
                      type: "DELETE",
                      beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'))
                      },
                      success: function () {
                        Swal.fire({
                          icon: 'success',
                          title: 'Deleted!',
                          text: 'Factory has been deleted.',
                          customClass: {
                            confirmButton: 'btn btn-success'
                          }
                        });
                        dtInvoice.draw();
                      },
                      error: function (xhr, ajaxOptions, thrownError) {
                          Swal.fire({
                            title: 'Error',
                            text: 'Error in Deletion :)',
                            icon: 'error',
                            customClass: {
                              confirmButton: 'btn btn-success'
                            }
                          });
                      }
                  });
                }
              } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                  title: 'Cancelled',
                  text: 'Cancelled Deletion :)',
                  icon: 'error',
                  customClass: {
                    confirmButton: 'btn btn-success'
                  }
                });
              }
            });
          });
        }
      }
    });
  }

  
});
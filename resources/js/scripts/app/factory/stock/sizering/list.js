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
      ajax: 'sizering/list',
      "processing": true,
      "serverSide": true,
      autoWidth: false,
      columns: [
        // columns according to JSON
        { data: 'factory_name', name: 'factory_name' },
        { data: 'sizering_number', name: 'sizering_number' },
        { data: 'sizering_datetime', name: 'sizering_date_time' },
        { data: 'total_sizering_rcn_stock', name: 'total_sizering_rcn_stock' },
        { data: 'balance_sizering_rcn_stock', name: 'balance_sizering_rcn_stock' },
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
                if (full['boilingcount'] > 0 && full['action']['view']){
                    return (
                        '<div class="d-flex align-items-center col-actions">' +
                            '<a class="me-25" href="' +
                                assetPath + 'factory/stock/sizering/' + full['slug']+
                            '" title="View Sizering Stock">' +
                            feather.icons['eye'].toSvg({ class: 'font-medium-2 text-body' }) +
                            '</a>' +
                        '</div>'
                    );
                } else {
                    var $html = '<div class="d-flex align-items-center col-actions">';
                    if (full['action']['edit']) {
                        $html += '<a class="me-1" href="' + assetPath + 'factory/stock/sizering/' + full['slug'] + '/edit' + '" title="Edit Sizering Stock">' +
                            feather.icons['edit'].toSvg({ class: 'font-medium-2 text-body' }) +
                            '</a>';
                    }
                    if (full['action']['view']) {
                        $html += '<a class="me-25" href="' +
                            assetPath + 'factory/stock/sizering/' + full['slug'] +
                            '" title="View Sizering Stock">' +
                            feather.icons['eye'].toSvg({ class: 'font-medium-2 text-body' }) +
                            '</a>';
                    }
                    if (full['action']['delete']) {
                        $html += '<a class="ms-1 delete-factory" data-slug="' + full['slug'] +
                            '" title="Delete Sizering Stock">' +
                            feather.icons['trash'].toSvg({ class: 'font-medium-2 text-body' }) +
                            '</a>';
                    }
                    $html += '</div>';

                    return $html;                     
                }
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
              title: 'Are you sure to delete Sizering Stock?',
              text: "You won't be able to revert!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Yes, delete Sizering Stock!',
              customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ms-1'
              },
              buttonsStyling: false
            }).then(function (result) {
              if (result.value) {
                if (result.isConfirmed) {
                    $.ajax({
                      url: "sizering/"+slug,
                      type: "DELETE",
                      beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'))
                      },
                      success: function (data) {
                        if (data.status == 'Error') {
                          Swal.fire({
                            title: 'Error',
                            text: 'Error in Deletion :) '+ data.msg,
                            icon: 'error',
                            customClass: {
                              confirmButton: 'btn btn-success'
                            }
                          });
                        } else {
                          Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Sizering Stock has been deleted.',
                            customClass: {
                              confirmButton: 'btn btn-success'
                            }
                          });
                        }
                        console.log(data);
                        
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
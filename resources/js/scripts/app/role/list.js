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
      ajax: 'role/list',
      "processing": true,
        "serverSide": true,
      autoWidth: false,
      columns: [
        // columns according to JSON
        { data: 'name', name: 'name' },
        { data: 'slug', name: 'slug' },
        { data: 'work_location_type', name: 'work_location_type' },
        { data: '' }
      ],
        columnDefs: [
          {
            targets: 0,
                render: function (data, type, full, meta) {
                    return  '<a href="/admin/role/permissions/'+full['slug']+'">' + data + '</a>';
            
                }
            },
            {
            // Actions
            targets: -1,
            title: 'Actions',
            width: '80px',
            orderable: false,
                render: function (data, type, full, meta) {
                var action = '<div class="d-flex align-items-center col-actions">';
               
                if (full['action']['edit']) {
                    action += '<a class="me-1" href="'+assetPath + 'admin/role/'+full['slug']+'/edit" title="Edit role">' +
                    feather.icons['edit'].toSvg({ class: 'font-medium-2 text-body' }) +
                    '</a>'  ;
                }              
                if (full['action']['delete']) {
                    action +='<a class=" delete-role" data-id="' + full['slug']+
                '" title="Delete role">' +
                feather.icons['trash'].toSvg({ class: 'font-medium-2 text-body' }) +
                '</a>'  ;
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

        const deleteStockyard = document.querySelector('.delete-role');

  // Suspend User javascript
        if (deleteStockyard) {
          $('.delete-role').on('click', function () {
            let id = $(this).data('id');
            Swal.fire({
              title: 'Are you sure to delete role?',
              text: "You won't be able to revert!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Yes, delete role!',
              customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ms-1'
              },
              buttonsStyling: false
            }).then(function (result) {
              if (result.value) {
                if (result.isConfirmed) {
                    $.ajax({
                      url: "role/"+id,
                      type: "DELETE",
                      beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'))
                      },
                      success: function () {
                        Swal.fire({
                          icon: 'success',
                          title: 'Deleted!',
                          text: 'role has been deleted.',
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

        $("#work_location_type").on('change', function () {
            var typeVal = $("#work_location_type").val();
            dtInvoice.search(typeVal).draw();
            
        });
  }

  
});
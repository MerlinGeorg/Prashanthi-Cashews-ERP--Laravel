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
            ajax: '/factory/employee/list',
            "processing": true,
            "serverSide": true,
            autoWidth: false,
            columns: [
                // columns according to JSON
                { data: 'employee_no' },
                { data: 'name' },
                { data: 'mobile' },
                { data: 'join_date' },
                { data: 'job_category' },
                { data: 'status' },
                { data: '' }
            ],
            columnDefs: [
                 {
                    targets:5 ,
                        render: function (data, type, full, meta) {
                            return '<span class="badge '+full['statusClass'][data]+'">' + full['status'] + '</span>';
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
                        if (full['action']['view']) {
                            action += '<a class="me-1" href="' +
                                        assetPath + 'factory/employee/' + full['slug'] +
                                        '" title="View Employee">' +
                                        feather.icons['eye'].toSvg({ class: 'font-medium-2 text-body' }) +
                                        '</a> ';
                        }
                        if (full['action']['edit']) {
                            action += '<a class="" href="' + assetPath + 'factory/employee/' + full['slug'] + '/edit' + '" title="Edit Employee">' +
                                        feather.icons['edit'].toSvg({ class: 'font-medium-2 text-body' }) +
                                        '</a> ';
                        }              
                        if (full['action']['delete']) {
                            action +='<a class="ms-1 delete-employee" data-slug="' + full['slug'] +
                                        '" title="Delete Employee">' +
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
            language: {
                sLengthMenu: 'Show _MENU_',
                search: 'Search',
                searchPlaceholder: 'Search here',
                paginate: {
                    // remove previous & next text from pagination
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            // Buttons with Dropdown
            buttons: [
            
            ],
            // For responsive popup
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return 'Details of ' + data['name'];
                        }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.columnIndex !== 2 // ? Do not show row in modal popup if title is blank (for check box)
                                ? '<tr data-dt-row="' +
                                col.rowIdx +
                                '" data-dt-column="' +
                                col.columnIndex +
                                '">' +
                                '<td>' +
                                col.title +
                                ':' +
                                '</td> ' +
                                '<td>' +
                                col.data +
                                '</td>' +
                                '</tr>'
                                : '';
                        }).join('');
                        return data ? $('<table class="table"/>').append('<tbody>' + data + '</tbody>') : false;
                    }
                }
            },
            initComplete: function () {
                $(document).find('[data-bs-toggle="tooltip"]').tooltip();

                const deleteEmployee = document.querySelector('.delete-employee');

                // Suspend User javascript
                if (deleteEmployee) {
                    $('.delete-employee').on('click', function () {
                        let slug = $(this).data('slug');
                        Swal.fire({
                            title: 'Are you sure to delete employee?',
                            text: "You won't be able to revert!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete employee!',
                            customClass: {
                                confirmButton: 'btn btn-primary',
                                cancelButton: 'btn btn-outline-danger ms-1'
                            },
                            buttonsStyling: false
                        }).then(function (result) {
                            if (result.value) {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: "/factory/employee/" + slug,
                                        type: "DELETE",
                                        beforeSend: function (xhr) {
                                            xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'))
                                        },
                                        success: function () {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Deleted!',
                                                text: 'Employee has been deleted.',
                                                customClass: {
                                                    confirmButton: 'btn btn-success'
                                                }
                                            });
                                            dtInvoice.draw();
                                            location.reload();
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

@extends('layouts/contentLayoutMaster')

@section('title', 'Outward RCN List')

@section('content')
    <section id="stockyard-page">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    @if (\Helper::userAccess('stockyard-outward-rcn-add'))
                        <div class="card-header border-bottom p-1">
                            <div class="head-label">
                                <h6 class="mb-0"></h6>
                            </div>
                            <div class="dt-action-buttons text-end">
                                <div class="dt-buttons d-inline-flex">
                                    <a href="{{ url('stockyard/outward-rcn/create') }}" class="dt-button btn btn-primary">
                                        <i data-feather="plus"></i> Add
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <table class="table outward-rcn-datatable">
                        <thead>
                            <tr>
                                {{-- <th></th> --}}
                                <th>Truck Reg No.</th>
                                <th>Lot No.</th>
                                <th>Factory</th>
                                <th>DC No.</th>
                                <th>EWB No.</th>
                                <th>RCN Weight/Bag </th>
                                <th> STATUS </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
@endsection

@section('page-script')
    <script type="text/javascript">
        jQuery(function($) {
            var groupColumn = 2;
            var table = $('.outward-rcn-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('stockyard/outward-rcn-list') }}",
                columns: [{
                        data: 'truck_reg_number',
                        name: 'truck_reg_number'
                    },
                    {
                        data: 'stockyard_rcn_stock_details.lot_number',
                        name: 'stockyard_rcn_stock_details.lot_number',
                        searchable: false
                    },
                    {
                        data: 'factory.factory_name',
                        name: 'factory.factory_name',
                    
                    },
                    {
                        data: 'dc_number',
                        name: 'dc_number'
                    },
                    {
                        data: 'ewb_number',
                        name: 'ewb_number'
                    },
                    {
                        data: 'rcn_net_weight',
                        name: 'rcn_net_weight'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                dom: '<"row d-flex justify-content-between align-items-center m-1"' +
                    '<"col-lg-6 d-flex align-items-center"l<"dt-action-buttons text-xl-end text-lg-start text-lg-end text-start "B>>' +
                    '<"col-lg-6 d-flex align-items-center justify-content-lg-end flex-lg-nowrap flex-wrap pe-lg-1 p-0"f<"invoice_status ms-sm-2">>' +
                    '>t' +
                    '<"d-flex justify-content-between mx-2 row"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',
                buttons: [],
                "order": [
                    [4, "asc"], //status 
                ],
                columnDefs: [{
                        "visible": false,
                        "targets": groupColumn
                    },
                    {
                        "render": function(data, type, row) {
                            return data + 'Kg / ' + row['rcn_bags'];
                        },
                        "targets": -3
                    },
                    {
                        // Actions
                        targets: -1,
                        title: 'Actions',
                        width: '80px',
                        orderable: false,
                        render: function(data, type, full, meta) {
                            $actions = '<div class="d-flex align-items-center col-actions">';
                            if (full['action']['view']) {
                                $actions += '<a class="me-1" href="/stockyard/outward-rcn/' +
                                    full['slug'] +
                                    '" title="View Inward RCN">' +
                                    feather.icons['eye'].toSvg({
                                        class: 'font-medium-2 text-body'
                                    }) +
                                    '</a> ';
                            }

                            if (full['action']['edit'] && !full['dispatched_date_time']) {
                                $actions += '<a class="me-1" href="/stockyard/outward-rcn/' +
                                    full['slug'] +
                                    '/edit' +
                                    '" title="Edit Outward RCN">' +
                                    feather.icons['edit'].toSvg({
                                        class: 'font-medium-2 text-body'
                                    }) +
                                    '</a> '
                            }

                            if (full['action']['delete'] && !full['dispatched_date_time']) {
                                $actions +=
                                    '<a class=" deleteOutwardRcn" data-id="' +
                                    full['id'] +
                                    '" title="Delete Outward RCN">' +
                                    feather.icons['trash'].toSvg({
                                        class: 'font-medium-2 text-body'
                                    }) +
                                    '</a>';
                            }
                            $actions += '</div></div>';
                            return $actions;
                        }
                    }
                ],
                initComplete: function() {
            var i = 0;
            this.api().columns().every(function() {
                if (i == 3) {
                    var column = this;
                    
                    // var input = `<input type='checkbox' id='selectAll' onclick/>`;
                    // $(input).appendTo($(column.footer()).empty()).on('change', function() {
                    //     if (this.checked) {
                    //         $('.inv_ids').each(function() { 
                    //             $(this).prop('checked', true); 
                    //         });
                    //     } else {
                    //         $('.inv_ids').each(function() { 
                    //             $(this).prop('checked', false); 
                    //         });
                    //     }
                    // });
                } 
               
                i++;


            });
        },
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;

                    api.column(groupColumn, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                '<tr class="group"><td colspan="7">' + group + '</td></tr>'
                            );

                            last = group;
                        }
                    });
                }

            });

            //Delete Stockyard RCN Stock
            $("body").on("click", ".deleteOutwardRcn", function(e) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Once deleted, you will not be able to recover this !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var id = $(this).data("id");
                        $.ajax({
                            url: "/stockyard/outward-rcn/" + id,
                            type: "DELETE",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "id": id
                            },
                            success: function(response) {
                                if (response.status == "success") {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Outward RCN deleted successfully',
                                        icon: "success",
                                        confirmButtonText: `Ok`,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        'Error deleting Inwrad RCN!',
                                        'error'
                                    )
                                }
                            }
                        })
                    }
                })
            });
        });
    </script>
@endsection

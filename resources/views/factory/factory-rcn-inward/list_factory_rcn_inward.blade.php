@extends('layouts/contentLayoutMaster')

@section('title', 'Factory RCN Inwards')

@section('content')
    <section id="stockyard-page">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <table class="table factory-inwards-datatable">
                        <thead>
                            <tr>
                                <th>Truck Reg. No</th>
                                <th>Factory</th>
                                <th>Lot No.</th>
                                <th>DC No.</th>
                                <th>EWB No.</th>
                                <th>Status </th>
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
            var table = $('.factory-inwards-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('factory/factory-rcn-inward-list') }}",
                columns: [{
                        data: 'truck_reg_number',
                        name: 'truck_reg_number'
                    },
                    {
                        data: 'factory.factory_name',
                        name: 'factory_name'
                    },
                    {
                        data:'outward_rcn_details.stockyard_rcn_stock_details.lot_number',
                        name:'lot_number'
                    },
                    {
                        data: 'dc_number',
                        name: 'dc_number'
                    },
                    {
                        data: 'ewb_number',
                        name: 'ewb_number'
                    },
                    // {
                    //     data: 'rcn_bags',
                    //     name: 'rcn_bags'
                    // },
                    // {
                    //     data: 'rcn_net_weight',
                    //     name: 'rcn_net_weight'
                    // },
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
                    // Actions
                    targets: -1,
                    title: 'Actions',
                    width: '80px',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        $action =
                            '<div class="d-flex align-items-center col-actions">';
                        if (full['action']['view'] ) {
                            $action += '<a class="me-1" href="/factory/factory-rcn-inward/' +
                                full['slug'] +
                                '" title="View Inward RCN">' +
                                feather.icons['eye'].toSvg({
                                    class: 'font-medium-2 text-body'
                                }) +
                                '</a> ';
                        }
                        if (full['action']['edit'] && !full['outward_rcn_details']['received_date_time'] ) {
                            $action += '<a class="me-25" href="/factory/factory-rcn-inward/' +
                                full['slug'] +
                                '/edit' +
                                '" title="Edit Inward RCN">' +
                                feather.icons['edit'].toSvg({
                                    class: 'font-medium-2 text-body'
                                }) +
                                '</a> ';
                        }

                        $action += '</div></div>';
                        return $action;
                    }
                }]
            });
        });
    </script>
@endsection

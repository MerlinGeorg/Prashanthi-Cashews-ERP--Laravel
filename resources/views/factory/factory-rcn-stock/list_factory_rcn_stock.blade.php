@extends('layouts/contentLayoutMaster')

@section('title', 'Factory RCN Stock List')

@section('content')
    <section id="stockyard-page">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <table class="table factory-rcn-stock-datatable">
                        <thead>
                            <tr>
                                <th>Stockyard Lot No.</th>
                                <th>Factory Name</th>
                                <th>Total Stock</th>
                                <th>Total Bag</th>
                                <th>Balance Stock</th>
                                <th>Balance Bag</th>
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
            var table = $('.factory-rcn-stock-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('factory/factory-rcn-stock-list') }}",
                columns: [{
                        data: 'stockyard_lot_number',
                        name: 'stockyard_lot_number'
                    },
                    {
                        data: 'factory_name',
                        name: 'factory_name'
                    },
                    {
                        data: 'total_rcn_factory_stock',
                        name: 'total_rcn_factory_stock'
                    },
                    {
                        data: 'total_rcn_bag',
                        name: 'total_rcn_bag'
                    },
                    {
                        data: 'balance_rcn_factory_stock',
                        name: 'balance_rcn_factory_stock'
                    },
                    {
                        data: 'balance_rcn_bag',
                        name: 'balance_rcn_bag'
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
                columnDefs: [{
                    // Actions
                    targets: -1,
                    title: 'Actions',
                    width: '80px',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        $action =
                            '<div class="d-flex align-items-center col-actions">';

                        if (full['action']['view']) {
                            $action += '<a class="me-1" href="/factory/rcn-stock/' + full[
                                    'stockyard_rcn_stock_slug'] +
                                '" title="View RCN Stock">' +
                                feather.icons['eye'].toSvg({
                                    class: 'font-medium-2 text-body'
                                }) + '</a>';
                        }

                        $action += '</div></div>';
                        return $action;
                    }
                }]
            });
        });
    </script>
@endsection

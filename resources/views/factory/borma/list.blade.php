@extends('layouts/contentLayoutMaster')

@section('title', 'Borma Stock List')

@section('content')
    <section id="stockyard-page">
        <div class="row">
            <div class="col-12">
                <div class="card invoice-list-wrapper">
                    <div class="card-datatable table-responsive">
                        @if (\Helper::userAccess('factory-borma-add'))
                            <div class="card-header border-bottom p-1">
                                <div class="head-label">
                                    <h6 class="mb-0"></h6>
                                </div>
                                <div class="dt-action-buttons text-end">
                                    <div class="dt-buttons d-inline-flex">
                                        <a href="{{ route('factory.borma.create') }}" class="dt-button btn btn-primary">
                                            <i data-feather="plus"></i> Add
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <table class="table borma-stock-datatable">
                            <thead>
                                <tr>
                                    <th>Borma Work No.</th>
                                    <th>Factory Slug</th>
                                    <th>Wholes</th>
                                    <th>Brokens</th>
                                    <th>Piruwal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
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
            var table = $('.borma-stock-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('factory/stock/borma-list') }}",
                columns: [{
                        data: 'borma_work_number',
                        name: 'borma_work_number'
                    },
                    {
                        data: 'factory_slug',
                        name: 'factory_slug'
                    },
                    {
                        data: 'wholes',
                        name: 'wholes'
                    },
                    {
                        data: 'brokens',
                        name: 'brokens'
                    },
                    {
                        data: 'piruwal',
                        name: 'piruwal'
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
                        $action = '<div class="d-flex align-items-center col-actions">';
                        if (full['action']['view']) {
                            $action += '<a class="me-1" href="/factory/stock/borma/' + full[
                                    'slug'] +
                                '" data-bs-toggle="tooltip" data-bs-placement="top" title="View RCN Stock">' +
                                feather.icons['eye'].toSvg({
                                    class: 'font-medium-2 text-body'
                                }) +
                                '</a> ';
                        }

                        if (full['action']['edit']) {
                            $action += '<a class="me-25" href="/factory/stock/borma/' + full[
                                    'slug'] +
                                '/edit' +
                                '" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Borma Stock">' +
                                feather.icons['edit'].toSvg({
                                    class: 'font-medium-2 text-body'
                                }) +
                                '</a> ';
                        }

                        if (full['action']['delete']) {
                            $action +=
                                '<a class="ms-1 deleteBormaStock" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' +
                                full['id'] +
                                '" title="Delete Borma Stock">' +
                                feather.icons['trash'].toSvg({
                                    class: 'font-medium-2 text-body'
                                }) +
                                '</a>';
                        }

                        $action += '</div></div>';

                        return $action;
                    }
                }]
            });


            //Delete Borma Stock
            $("body").on("click", ".deleteBormaStock", function(e) {
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
                            url: "/factory/borma-stock/" + id,
                            type: "DELETE",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "id": id
                            },
                            success: function(response) {
                                if (response.status == "success") {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Borma Stock deleted successfully',
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
                                        'Error deleting Borma Stock!',
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

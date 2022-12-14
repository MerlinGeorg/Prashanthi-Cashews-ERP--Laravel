@extends('layouts/contentLayoutMaster')

@section('title', 'RCN Stock List')
@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">

@endsection

@section('content')

    <section id="stockyard-page">
        <div class="demo-inline-spacing">

            <div class="" >
                <!-- Button trigger modal -->
                <button type="hidden" id="modalOption" hidden="hidden" data-bs-toggle="modal" data-bs-target="#backdrop">
                </button>
                <!-- Modal -->
                <div style=""
 class="modal fade text-start" id="backdrop" tabindex="-1" aria-labelledby="myModalLabel4"
                    data-bs-backdrop="false" aria-hidden="true">
                    <div  class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel4">Please Choose</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-1 " data-select2-id="16">
                                    <label class="form-label" for="stockyard">Stock Type<label class="text-danger px-sm-25">
                                            *</label></label>
                                    <div class="position-relative" data-select2-id="15"><select
                                            class="form-select select2 select2-hidden-accessible" name="stockyType"
                                            id="stockyType" data-select2-id="stockyardList" tabindex="-1"
                                            aria-hidden="true">
                                            <option value="0" selected="" data-select2-id="2">-Select-</option>
                                            <option value="normal" data-short-name="normal" data-select2-id="0">
                                                Normal</option>
                                            <option value="split" data-short-name="split">
                                                Split</option>
                                                <option value="mix" data-short-name="mix">
                                                    Mix</option>
                                                       <option value="combine" data-short-name="mix">
                                                    Combine</option>

                                            {{-- <option value="mix" data-short-name="mix" >
                                    Mix</option>
                                    <option value="compine" data-short-name="compine" >
                                        Compine</option> --}}

                                        </select>
                                        <span class="" dir="ltr" data-select2-id="1" style="width: 100%;">
                                            {{-- <span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-stockyardList-container">
                                                                    <span class="select2-selection__rendered" id="select2-stockyardList-container" role="textbox" aria-readonly="true" title="-Select-">-Select-</span>
                                                                    <span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span> --}}
                                        </span>

                                    </div>
                                    <span style="color: red;display:none;" id="modal_val" class="">please select any
                                        option to proceed</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="typeSubmit"
                                    class="btn btn-primary waves-effect waves-float waves-light"
                                    data-bs-dismiss="modal">Proceed</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12">

            </div>
            <div class="card invoice-list-wrapper">

                <div class="card-datatable table-responsive">
                    @if (\Helper::userAccess('stockyard-rcn-add'))
                        <div class="card-header border-bottom p-1">
                            <div class="head-label">
                                <h6 class="mb-0"></h6>
                            </div>
                            <div class="dt-action-buttons text-end">
                                <div class="dt-buttons d-inline-flex">
                                    <a type="hidden" id="add_button_via_js"
                                        href="{{ url('stockyard/rcn-stock/create') }}"> </a>
                                    <a type="hidden" id="add_button_split_via_js"
                                        href="{{ route('stockyard.rcn-stock.create_splitz') }}"> </a>

                                        <a type="hidden" id="add_button_mix_via_js"
                                        href="{{ route('stockyard.rcn-stock.create_mix') }}"> </a>

                                            <a type="hidden" id="add_button_combine_via_js"
                                        href="{{ route('stockyard.rcn-stock.create_compine') }}"> </a>

                                    <a onclick="addOptions()" class="dt-button btn btn-primary">
                                        <i data-feather="plus"></i> Add
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <table class="table stockyard-rcn-datatable">
                        <thead>
                            <tr>
                                {{-- <th>Sl.No</th> --}}
                                <th>Stockyard</th>
                                <th>Lot No.</th>
                                <th>Account</th>
                                <th>Acc. Lot No.</th>
                                <th>BL Number</th>
                                <th>RCN Mark</th>
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

    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>

@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            $("#stockyType").select2();
        });
    </script>
    <script type="text/javascript">
        jQuery(function($) {
            var table = $('.stockyard-rcn-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('stockyard/rcn-stock-list') }}",
                columns: [{
                        data: 'stockyard_name',
                        name: 'stockyard_name'
                    },
                    {
                        data: 'lot_number',
                        name: 'lot_number'
                    },
                    {
                        data: 'account_name',
                        name: 'account_name'
                    },
                    {
                        data: 'account_lot_number',
                        name: 'account_lot_number'
                    },
                    {
                        data: 'bl_number',
                        name: 'bl_number'
                    },
                    {
                        data: 'rcn_mark',
                        name: 'rcn_mark'
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
                        if (full['type'] == "split") {

                            if (full['action']['view']) {
                                $action += '<a class="me-1" href="/stockyard/rcn-stockz/' +
                                    full[
                                        'slug'] +
                                    '" title="View RCN Split Stock">' +
                                    feather.icons['eye'].toSvg({
                                        class: 'font-medium-2 text-body'
                                    }) +
                                    '</a> ';
                            }

                            if (full['action']['edit']) {
                                $action += '<a class="me-25" href="/stockyard/rcn-stocks/' +
                                    full[
                                        'slug'] +
                                    '/split_edit' +
                                    '" title="Edit RCN Split Stock">' +
                                    feather.icons['edit'].toSvg({
                                        class: 'font-medium-2 text-body'
                                    }) +
                                    '</a> ';
                            }

                            if (full['action']['delete']) {
                                $action +=
                                    '<a class="ms-1 deleteStockyardRcnStock" data-id="' +
                                    full['id'] +
                                    '" title="Delete RCN Stock">' +
                                    feather.icons['trash'].toSvg({
                                        class: 'font-medium-2 text-body'
                                    }) +
                                    '</a>';
                            }

                        }else if(full['type'] == "mix"){

                            if (full['action']['view']) {
                                $action += '<a class="me-1" href="/stockyard/rcn-stockz-mix/' +
                                    full[
                                        'slug'] +
                                    '" title="View RCN Mix Stock">' +
                                    feather.icons['eye'].toSvg({
                                        class: 'font-medium-2 text-body'
                                    }) +
                                    '</a> ';
                            }

                            if (full['action']['edit']) {
                                $action += '<a class="me-25" href="/stockyard/rcn-stocks/' +
                                    full[
                                        'slug'] +
                                    '/mix_edit' +
                                    '" title="Edit RCN Mix Stock">' +
                                    feather.icons['edit'].toSvg({
                                        class: 'font-medium-2 text-body'
                                    }) +
                                    '</a> ';
                            }

                            if (full['action']['delete']) {
                                $action +=
                                    '<a class="ms-1 deleteStockyardRcnStock" data-id="' +
                                    full['id'] +
                                    '" title="Delete RCN Stock">' +
                                    feather.icons['trash'].toSvg({
                                        class: 'font-medium-2 text-body'
                                    }) +
                                    '</a>';
                            }


                       


                        }else if(full['type'] == "combine"){

                            if (full['action']['view']) {
                                $action += '<a class="me-1" href="/stockyard/rcn-stockz-compine/' +
                                    full[
                                        'slug'] +
                                    '" title="View RCN Compine Stock">' +
                                    feather.icons['eye'].toSvg({
                                        class: 'font-medium-2 text-body'
                                    }) +
                                    '</a> ';
                            }

                            if (full['action']['edit']) {
                                $action += '<a class="me-25" href="/stockyard/rcn-stocks/' +
                                    full[
                                        'slug'] +
                                    '/compine_edit' +
                                    '" title="Edit RCN Compine Stock">' +
                                    feather.icons['edit'].toSvg({
                                        class: 'font-medium-2 text-body'
                                    }) +
                                    '</a> ';
                            }

                            if (full['action']['delete']) {
                                $action +=
                                    '<a class="ms-1 deleteStockyardRcnStock" data-id="' +
                                    full['id'] +
                                    '" title="Delete RCN Stock">' +
                                    feather.icons['trash'].toSvg({
                                        class: 'font-medium-2 text-body'
                                    }) +
                                    '</a>';
                            }


                        }
                                else {

                            if (full['action']['view']) {
                                $action += '<a class="me-1" href="/stockyard/rcn-stock/' + full[
                                        'slug'] +
                                    '" title="View RCN Stock">' +
                                    feather.icons['eye'].toSvg({
                                        class: 'font-medium-2 text-body'
                                    }) +
                                    '</a> ';
                            }

                            if (full['action']['edit']) {
                                $action += '<a class="me-25" href="/stockyard/rcn-stock/' +
                                    full[
                                        'slug'] +
                                    '/edit' +
                                    '" title="Edit RCN Stock">' +
                                    feather.icons['edit'].toSvg({
                                        class: 'font-medium-2 text-body'
                                    }) +
                                    '</a> ';
                            }

                            if (full['action']['delete']) {
                                $action +=
                                    '<a class="ms-1 deleteStockyardRcnStock" data-id="' +
                                    full['id'] +
                                    '" title="Delete RCN Stock">' +
                                    feather.icons['trash'].toSvg({
                                        class: 'font-medium-2 text-body'
                                    }) +
                                    '</a>';
                            }


                        }


                        $action += '</div></div>';

                        return $action;
                    }
                }, ]
            });


            //Delete Stockyard RCN Stock
            $("body").on("click", ".deleteStockyardRcnStock", function(e) {
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
                            url: "/stockyard/rcn-stock/" + id,
                            type: "DELETE",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "id": id
                            },
                            success: function(response) {
                                if (response.status == "success") {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Stockyard RCN Stock deleted successfully',
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
                                        'Error deleting Stockyard RCN Stock!',
                                        'error'
                                    )
                                }
                            }
                        })
                    }
                })
            });
        });

        function addOptions() {
            $('#modalOption').click();

        }

        $(document).ready(function() {

            var stockType = $('#stockyType').val();
            if (stockType == 0) {
                document.getElementById("modal_val").style.display = 'block';

            }

        });

        $("#stockyType").on('change', function() {

            var stockType = $('#stockyType').val();
            if (stockType != 0) {

                document.getElementById("modal_val").style.display = 'none';
            } else {
                document.getElementById("modal_val").style.display = 'block';
            }
        });

        $("#typeSubmit").click(function(event) {



            var stockType = $('#stockyType').val();



            if (stockType == "normal") {
                document.getElementById("add_button_via_js").click();

            } else if (stockType == "split") {
                document.getElementById("add_button_split_via_js").click();


            }else if (stockType == "mix") {
                document.getElementById("add_button_mix_via_js").click();


            }else if (stockType == "combine") {
                document.getElementById("add_button_combine_via_js").click();


            }
             else if (stockType == 0) {

                document.getElementById("modal_val").style.display = 'block';
                $('#backdrop').modal('show');


            } else {

            }

        });
    </script>
@endsection

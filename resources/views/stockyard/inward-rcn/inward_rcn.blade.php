@extends('layouts/contentLayoutMaster')

@section('title', 'Inward RCN List')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form class="p-2">
                        @csrf
                        <div class="row">
                            <div class="mb-1 col-md-6">
                                <label class="form-label" for="stockyard">Stockyard<label class="text-danger px-sm-25">
                                        *</label></label>
                                <select class="form-select select2" name="stockyard_id" id="stockyardList">
                                    <option value=""  selected>-Select-</option>
                                    @foreach ($stockyards as $value)
                                        <option value="{{ $value->slug }}"
                                            data-short-name="{{ $value->stockyard_short_name }}" 
                                            {{ session('data.stockyard_slug') == $value->slug ? 'selected' : '' }} >
                                            {{ $value->stockyard_name }}</option>
                                    @endforeach
                                </select>
                                @error('stockyard_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-1 col-md-6">
                                <label class="form-label" for="stockyard_rcn_stock">Account Lot No. / BL Number <label
                                        class="text-danger px-sm-25"> *</label></label>
                                <select class="form-select select2" name="stockyard_stock_id" id="stockyardRcnStockList"
                                    value="{{ old('account') }}">
                                    <option value="">-Select-</option>
                                    @if(session::has('data.rcn_stock_slug'))
                                        @foreach($stockyard_rcn_stocks as $value)
                                        <option value="{{$value->slug}}"
                                        {{ session('data.rcn_stock_slug') == $value->slug ? 'selected' : '' }}>{{$value->account_lot_number}}</option>
                                        @endforeach
                                    @endif
                                    
                                </select>
                                @error('account_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <!--  <a href="{{ route('admin.stockyard') }}"><button class="dt-button buttons-collection btn btn-outline-secondary me-2" tabindex="0" aria-controls="DataTables_Table_0" type="button" aria-haspopup="true"><span>Back</span></button></a> -->
                                <button type="button" id="btnInwardRcn" class="btn btn-primary">Show</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section id="inwardRcnList">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    @if (\Helper::userAccess('stockyard-inward-rcn-add'))
                        <div class="card-header border-bottom p-1">
                            <div class="head-label">
                                <h6 class="mb-0"></h6>
                            </div>
                            <div class="dt-action-buttons text-end">
                                <div class="dt-buttons d-inline-flex">
                                    <a href="#" id="btnAddContainer" class="dt-button btn btn-primary">
                                        <i data-feather="plus"></i> Add
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <table class="table inward-rcn-datatable">
                        <thead>
                            <tr>
                                {{-- <th>Sl.No</th> --}}
                                <th>Truck Reg No.</th>
                                <th>Container No.</th>
                                <th>Seal No.</th>
                                <th>Status</th>
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
    <script>
        jQuery(function($) {
            
            $('#inwardRcnList').hide();
            
           
            $("#btnInwardRcn").click(function() {
                var stockyard = $('#stockyardList :selected').val();
                var stockyardRcn = $('#stockyardRcnStockList :selected').val();

                if (stockyard == "" || stockyardRcn == "") {
                    Swal.fire(
                        'Error!',
                        'Please choose stockyard & stockyard RCN!',
                        'error'
                    )
                } else {
                    $('#inwardRcnList').show();
                    var table = $('.inward-rcn-datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        stateSave: true,
                        "bDestroy": true,
                        ajax: "/stockyard/inward-rcn/list/" + $.trim(stockyard) + '/' + $.trim(
                            stockyardRcn),
                        columns: [{
                                data: 'truck_reg_number',
                                name: 'truck_reg_number'
                            },
                            {
                                data: 'container_number',
                                name: 'container_number'
                            },
                            {
                                data: 'seal_number',
                                name: 'seal_number'
                            },
                            {
                                data: 'status',
                                name: 'status'
                            },
                            // {
                            //     data: 'dispatched_date_time',
                            //     name: 'dispatched_date_time'
                            // },
                            // {
                            //     data: 'received_date_time',
                            //     name: 'received_date_time'
                            // },
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
                                if (full['action']['view'] ) {
                                    $action +=
                                        '<a class="me-1" href="/stockyard/inward-rcn/' +
                                        full['slug'] +
                                        '/view-rcn" title="View RCN Stock">' +
                                        feather.icons['eye'].toSvg({
                                            class: 'font-medium-2 text-body'
                                        }) +
                                        '</a> ';
                                }
                                if (full['action']['edit'] && !full['received_date_time']) {
                                    $action +=
                                        '<a class="me-25" href="/stockyard/inward-rcn/' +
                                        full['slug'] +
                                        '/edit-rcn' +
                                        '" title="Edit RCN Stock">' +
                                        feather.icons['edit'].toSvg({
                                            class: 'font-medium-2 text-body'
                                        }) +
                                        '</a> ';
                                }
                                if (full['action']['delete'] && !full['received_date_time']) {
                                    $action +=
                                        '<a class="ms-1 deleteInwardRcn" data-id="' +
                                        full['id'] +
                                        '" title="Delete RCN Stock">' +
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
                }
            });
            if($('#stockyardList').val()!=''){
                $("#btnInwardRcn").click(); 
            }
        });


        $('#stockyardList').on('change', function(e) {
            var stockyard_slug = e.target.value;
            if(stockyard_slug!='')
            {
                $.ajax({
                    url: "{{ url('stockyard/inward-rcn/stockyard-rcn-stock-list') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "stockyard_slug": stockyard_slug
                    },
                    success: function(response) {
                        $('select[name="stockyard_stock_id"]').empty();
                        $('select[name="stockyard_stock_id"]').append('<option value="">-Select-</option>')
                        if (response.stockyard_rcn_stocks.length != 0) {
                            $.each(response.stockyard_rcn_stocks, function(key, value) {
                                $('select[name="stockyard_stock_id"]').append('<option value=" ' +
                                    value.slug + '">' + value.account_lot_number + ' / ' +
                                    value.bl_number +
                                    '</option>');
                            })
                        }
                    }
                })
            }
        });


        $("#btnAddContainer").click(function(e) {
            e.preventDefault();
            var stockyard = $('#stockyardList :selected').val();
            var stockyardRcn = $('#stockyardRcnStockList :selected').val();

            if (stockyard == "" || stockyardRcn == "") {
                Swal.fire(
                    'Error!',
                    'Please choose stockyard & stockyard RCN!',
                    'error'
                )
            } else {
                window.location.href = '/stockyard/inward-rcn/add/' + $.trim(stockyard) + '/' + $.trim(
                    stockyardRcn);
            }
        });

        //Delete Stockyard RCN Stock
        $("body").on("click", ".deleteInwardRcn", function(e) {
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
                        url: "/stockyard/inward-rcn/" + id + "/delete-rcn",
                        type: "DELETE",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Inward RCN deleted successfully',
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
    </script>
@endsection

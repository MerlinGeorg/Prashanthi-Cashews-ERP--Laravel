@extends('layouts/contentLayoutMaster')

@section('title', 'Add RCN Stock - Split')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('stockyard.rcn-stock.store_splitz') }}" method="POST">
                            @csrf

                            <input type="hidden" name="type" id="type" value="split"  />

                            <div class="row">
                             
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="stockyard">Stockyard<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="stockyard_slug" id="stockyardList" role="textbox"
                                        value="{{ old('stockyard_slug') }}">
                                        <option value=''>Select</option>
                                        @foreach ($stockyards as $value)
                                                         <option value="{{ $value->slug }}"
                                                            data-short-name="{{ $value->stockyard_short_name }}"
                                                            {{ old('stockyard_slug') == $value->slug ? 'selected' : '' }}>
                                            {{ $value->stockyard_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('stockyard_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="stockyard_rcn_stock_slug">Lot No.<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="stockyard_rcn_stock_slug" id="stockyardRcnStockList"
                                        value="{{ old('stockyard_rcn_stock_slug') }}">
                                        <option value="" selected>-Select-</option>
                                        
                                        @foreach($stockyard_rcn_stocks as $value)
                                        <option value="{{ $value->slug }}"
                                            {{ old('stockyard_rcn_stock_slug') == $value->slug ? 'selected' : '' }}
                                            data-short-name="{{ $value->stockyard_short_name }}">
                                            {{ $value->lot_number }}</option>
                                        @endforeach
                                
                                    
                                    
                                    </select>
                                    @error('stockyard_rcn_stock_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                   
                                <h5 class="my-2">
                                    <i data-feather="database" class="font-medium-4 mr-25"></i>
                                    <span class="align-middle">New Stock Details </span>
                                </h5>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="rcn_kg">RCN (kg)<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="rcn_kg" id="rcn_kg"
                                        class="form-control number-mask" maxlength="10" value ="{{ old('rcn_kg') }}" />
                                    @error('rcn_kg')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="rcn_bags">Bags<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="rcn_bags" id="rcn_bags"
                                        class="form-control number-mask" maxlength="10" value ="{{ old('rcn_bags') }}" />
                                    @error('rcn_bags')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                 <div class="mb-1 col-md-6">
                                    <label class="form-label" for="account_id">Account<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="account_id" id="accountList"
                                        value="{{ old('account') }}">
                                          <option value=''>Select</option>
                                        @foreach ($accounts as $key => $value)
                                            <option value="{{ $value->slug }}"
                                                {{ old('account_id') == $value->slug ? 'selected' : '' }}>
                                                {{ $value->account_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('account_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div> 
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="sub_account_id">Sub Account<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="sub_account_id" id="subAccountList"
                                        data-selected="{{ old('sub_account_id') }}">
                                        <!-- <option value="" disabled selected>-Select-</option> -->
                                    </select>
                                    @error('sub_account_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-xs-12 mt-3 col-sm-12 col-md-12 text-center">
                                    <a href="{{ route('stockyard.rcn-stock') }}"><button
                                            class="dt-button buttons-collection btn btn-outline-secondary me-2" tabindex="0"
                                            aria-controls="DataTables_Table_0" type="button"
                                            aria-haspopup="true"><span>Back</span></button></a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                            <input type="hidden" name="slug" id="slug">
                        </form>
                    </div>
    </section>
@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>

    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/addons/cleave-phone.in.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset(mix('js/scripts/forms/form-input-mask.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/app/state-account.js')) }}"></script>

    <script>

   $(document).ready(function() {

    var  stockyard_slug =  $('#stockyardList').val();
    var stockyardRcn = $('#stockyardRcnStockList').val();

            if(stockyard_slug!='')
            {   
                $.ajax({
                    url: "{{ url('stockyard/inward-rcn/stockyard-rcn-stock-list/split') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "stockyard_slug": stockyard_slug
                    },
                    success: function(response) {
                        

                        $('select[name="stockyard_rcn_stock_slug"]').empty();
                        $('select[name="stockyard_rcn_stock_slug"]').append('<option value="">-Select-</option>')
                          

                            
                        if (response.stockyard_rcn_stocks.length != 0) {
                            $.each(response.stockyard_rcn_stocks, function(key, value) {
                               
                                $('select[name="stockyard_rcn_stock_slug"]').append('<option value=" ' +
                                    value.slug + '" '+ (stockyardRcn == value.slug ?
                                    'selected="selected"' : "") + '>' + value.lot_number + 
                                    '</option>');
                            })
                        }
                    }


                })

            
                
            }

          });
        $(document).ready(function() {

            fetchSubAccount();
            fetchWarehouses();
            $('#accountList').on('change', function(e) {
                fetchSubAccount();
            });

            function fetchSubAccount() {
                var acc_slug = $('#accountList').val();
                $.ajax({
                    url: "{{ url('stockyard/rcn-stock/sub-account-list') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "acc_slug": acc_slug
                    },
                    success: function(response) {
                        $('select[name="sub_account_id"]').html('<option value=""> -Select- </option>');
                        $.each(response.sub_accounts, function(key, value) {
                            var selected = $('select[name="sub_account_id"]').data(
                                'selected');
                            $('select[name="sub_account_id"]').append(
                                '<option value=" ' + value
                                .id + '"' + (selected == value.id ?
                                    'selected="selected"' : "") + '>' + value
                                .account_state + '</option>');
                        })
                    }
                })
          
            }

            // $('#stockyardList').on('change', function(e) {
            //     fetchWarehouses();
            // });

            function fetchWarehouses() {
                var stockyard_slug = $('#stockyardList').val();
               if(stockyard_slug!='')
                $.ajax({
                    url: "{{ url('admin/stockyard/warehouses') }}/" + stockyard_slug,
                    type: "GET",
                    success: function(response) {
                        let $warehouse = $('#warehouse');
                        let selected = $warehouse.data('selected');
                        $warehouse.html('<option value=""> -Select- </option>');
                        //console.log(response);
                        $.each(response, function(slug, name) {
                            $warehouse.append('<option value="' + slug + '" >' +
                                name + '</option>');
                        });

                        $warehouse.val(selected).select2();
                    }

                })
            }
        });

        
        $('#stockyardList').on('change', function(e) {


            var stockyard_slug = e.target.value;
            if(stockyard_slug!='')
            {   
                $.ajax({
                    url: "{{ url('stockyard/inward-rcn/stockyard-rcn-stock-list/split') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "stockyard_slug": stockyard_slug
                    },
                    success: function(response) {
                        $('select[name="stockyard_rcn_stock_slug"]').empty();
                        $('select[name="stockyard_rcn_stock_slug"]').append('<option value="">-Select-</option>')
                        if (response.stockyard_rcn_stocks.length != 0) {
                            $.each(response.stockyard_rcn_stocks, function(key, value) {
                                $('select[name="stockyard_rcn_stock_slug"]').append('<option value=" ' +
                                    value.slug + '">' + value.lot_number + 
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


    </script>
@endsection

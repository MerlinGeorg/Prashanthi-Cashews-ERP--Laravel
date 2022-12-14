@extends('layouts/contentLayoutMaster')

@section('title', 'Add RCN Stock - Mix')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/plugins/forms/pickers/form-flat-pickr.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/plugins/forms/pickers/form-pickadate.css')) }}">
@endsection

@section('content')
<section id="multiple-column-form">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('stockyard.rcn-stock.store_mix') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" id="type" value="mix"  />
                        <div class="row col-12">
                            <h5 class="my-2">
                                <i data-feather="server" class="font-medium-4 mr-25"></i>
                                <span class="align-middle">Factory Stock</span>
                            </h5>
                        </div>
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
                          
                       
                            <?php
                            $state_val_count = old('lot_numbers') ? sizeof(old('lot_numbers')) : 1;
                                // dd( old('lot_numbers') );               
                                       
                                         ?>
                            {{-- <div class="machinery_sec"> --}}
                              <div data-repeater-list="sizering_stocks" class="">
                            <div class="row col-12 machinery_sec">
                             
                            </div>
                            <div  class="machinery_sec">
                                <div class="align-items-end">
                                    <div class="row d-flex align-items-end">
                                        <div class="invoice-repeater">
                                            <div data-repeater-list="lot_numbers" class="">
                                                @for ($i = 0; $i < $state_val_count; $i++) 
                                                    <div  data-repeater-item="" class="d-block align-items-end p-2 mx-25 border mb-1">
                                                        <div class="row align-items-end">

                                                                       
                                                            <div class="col-md-3 col-12">
                                                                <div class="mb-1">
                                                                
                                                                    <label class="form-label" for="boiling_slug">Lot Number</label>
                                                                    <select class="form-select select2 boiling_stock" name="stockyard_rcn_stock_slug" id="stockyard_rcn_stock_slug" data-selected="{{ old('lot_numbers.' . $i . '.stockyard_rcn_stock_slug') }}">
                                                                     {{-- @foreach ( [old('lot_numbers.' . $i . '.stockyard_rcn_stock_slug')] as $lot )
                                                                              
                                                                              <option value="{{ old('lot_numbers.' . $i . '.stockyard_rcn_stock_slug') }}">{{ old('lot_numbers.' .$i . '.stockyard_rcn_stock_slug') }}</option>
                                                                        @endforeach --}}

                                                                    </select>
                                                                </div>
                                                            </div>

                                                            @foreach (Config::get('constants.mix') as $name => $grade)
                                                                    <div class="col-md-4 col-12">
                                                                        <div class="mb-1">
                                                                            <label class="form-label"
                                                                                for="experiences">{{ $name }} <label
                                            class="text-danger px-sm-25"> *</label></label>
                                                                            <input type="text"
                                                                                maxlength="10" name="{{ $grade }}"
                                                                                id="{{ $grade }}"
                                                                                value="{{ old('lot_numbers.' . $i . '.' . $grade) }}"
                                                                                class="form-control number-mask p-50" />
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                        
                                                           
                                                            
                                                            <div class="col-md-1 col-12 text-end">
                                                                <div class="mb-1">
                                                                    <button class="btn btn-outline-danger text-nowrap px-1 waves-effect" data-repeater-delete="" type="button">
                                                                        <i data-feather="x" class="font-medium-2"></i>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                        @error('lot_numbers')
                                                        <div class="alert alert-danger">{{ $message }}
                                                        </div>
                                                    @enderror

                                                    
                                                    @error('lot_numbers.' . $i . '.stockyard_rcn_stock_slug')
                                                        <div class="alert alert-danger">{{ $message }}
                                                        </div>
                                                    @enderror
                                                               @foreach (Config::get('constants.mix') as $name => $grade)
                                                        @error('lot_numbers.' . $i . '.' . $grade)
                                                            <div class="alert alert-danger">{{ $message }}
                                                            </div>
                                                        @enderror

                                                        
                                                    @endforeach
                                                @endfor
                                     
                                            </div>
                                            

                                            <div class="col-md-12 col-12 text-center px-5 py-1">
                                                <button class="btn btn-icon btn-outline-success waves-effect waves-float waves-light" id="repeater-button" type="button" data-repeater-create="">
                                                    <i data-feather="plus" class="font-medium-2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    </div>
                                <div class="row">
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
                            </div>
                            </div>
                            <div class="mb-1 col-md-6">
                                <label class="form-label" for="out_turn">Out Turn<label
                                        class="text-danger px-sm-25"> *</label></label>
                                <input type="text" name="out_turn" id="out_turn" value="{{ old('out_turn') }}"
                                    class="form-control number-mask" maxlength="10" />
                                @error('out_turn')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-1 col-md-6">
                                <label class="form-label" for="nut_count">Nut Count<label
                                        class="text-danger px-sm-25"> </label></label>
                                <input type="number" name="nut_count" id="nut_count" value="{{ old('nut_count') }}"
                                    class="form-control number-mask" maxlength="10" />
                                @error('nut_count')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-1 col-md-6">
                                <label class="form-label" for="rejection">Rejection<label
                                        class="text-danger px-sm-25"> </label></label>
                                <input type="text" name="rejection" id="rejection" value="{{ old('rejection') }}"
                                    class="form-control number-mask" maxlength="10" />
                                @error('rejection')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                   
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <a href="{{ route('stockyard.rcn-stock') }}"><button class="dt-button buttons-collection btn btn-outline-secondary me-2" tabindex="0" aria-controls="DataTables_Table_0" type="button" aria-haspopup="true"><span>Back</span></button></a>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                </div>
            </div>
        </div>

        </form>
    </div>
    </div>
    </div>
    </div>
</section>

@endsection

@section('vendor-script')
<script src="{{asset(mix('vendors/js/forms/wizard/bs-stepper.min.js'))}}"></script>
<script src="{{asset(mix('vendors/js/forms/select/select2.full.min.js'))}}"></script>
<script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
<script src="{{asset(mix('vendors/js/forms/cleave/cleave.min.js'))}}"></script>
<script src="{{asset(mix('vendors/js/forms/cleave/addons/cleave-phone.in.js'))}}"></script>
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection

@section('page-script')
<script src="{{ asset(mix('js/scripts/forms/form-input-mask.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/scripts/forms/pickers/form-pickers.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/app/state-account.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/forms/form-input-mask.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/forms/repeater/jquery.repeater.min.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/app/factory/stock/stock.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/app/stockyard/mix/mix.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/app/stockyard/mix/form-repeater.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
<?php
$sizeringArray = json_encode(old('boiling_stocks'));
?>
<script>
    let cuttingtype = "{{ old('cutting_type') }}";

    $(document).ready(function(){    
      //    $('[data-repeater-item]').slice(2).remove();
       //   resetRepeaterSelectbox();
          factoryBoilingStock();
      });

    $(document).ready(function() {

fetchSubAccount();
fetchWarehouses();
});

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

                         $('select[name="stockyard_rcn_stock_slug"]').empty();
                         $('select[name="stockyard_rcn_stock_slug"]').append('<option value="">-Select-</option>')
                         if (response.stockyard_rcn_stocks.length != 0) {
                             $.each(response.stockyard_rcn_stocks, function(key, value) {
                                 $('select[name="stockyard_rcn_stock_slug"]').append('<option value=" ' +
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

</script>
@endsection
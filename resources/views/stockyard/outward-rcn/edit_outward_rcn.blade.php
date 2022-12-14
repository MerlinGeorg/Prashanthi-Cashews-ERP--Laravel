@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Outward RCN')

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
                        <form action="{{ route('outward-rcn.update', $outward_rcn_data->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="stockyard_rcn_stock_slug">Lot No.<label
                                            class="text-danger px-sm-25"> *</label></label>
                                            <span
                                                class="form-control">{{$outward_rcn_data->stockyardRcnStockDetails->lot_number}}
                                            </span>
                                    
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="factory_slug">Factory<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="factory_slug" id="factoryList"
                                        value="{{ old('factory_slug') }}">
                                        <option value="" disabled selected>-Select-</option>
                                        @foreach ($factories as $value)
                                            <option value="{{ $value->slug }}"
                                                data-short-name="{{ $value->factory_short_name }}"
                                                {{ $outward_rcn_data->factory_slug == $value->slug ? 'selected' : '' }}>
                                                {{ $value->factory_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('factory_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="truck_reg_number">Truck Reg No.<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="truck_reg_number" id="truck_reg_number"
                                        class="form-control text-uppercase" placeholder="KL-02-AA-0123" maxlength="15"
                                        value="{{ $outward_rcn_data->truck_reg_number }}" />
                                    @error('truck_reg_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="contact_number">Contact Number<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="contact_number" id="contact_number"
                                        value="{{ $outward_rcn_data->contact_number }}" class="form-control number-mask"
                                        maxlength="10" />
                                    @error('contact_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="dc_number">DC Number<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="dc_number" id="dc_number"
                                        value="{{ $outward_rcn_data->dc_number }}" class="form-control alphanumeric"
                                        maxlength="32" />
                                    @error('dc_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="ewb_number">EWB Number<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="ewb_number" id="ewb_number"
                                        value="{{ $outward_rcn_data->ewb_number }}" class="form-control alphanumeric"
                                        maxlength="32" />
                                    @error('ewb_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="stock_from">Stock from<label
                                            class="text-danger px-sm-25"> *</label></label>
                                            <select  class="form-control alphanumeric"  name="stock_from" id="stock_fromE">

                                                <option  value="same">Same</option>
                                                <option  value="borrowed">Borrowed</option>
                                            </select>
                                 
                                    @error('stock_from')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4" id="borrowed_lot_numbersE">
                                    <label class="form-label" for="ewb_number">Lot Number(if borrowed)<label
                                            class="text-danger px-sm-25"> *</label></label>
                       
                                            <select class="form-select select2" name="borrowed_lot_number" id="borrowed_lot_numberE"
                                            value="{{ old('borrowed_lot_number') }}">
                                            <option value="{{ $outward_rcn_data->borrowed_lot_number }}"  selected>{{ $outward_rcn_data->borrowed_lot_number }}</option>
                                            @foreach ($stockyard_rcn_stocks as $value)
                                                <option value="{{ $value->slug }}"
                                                    {{ old('stockyard_rcn_stock_slug') == $value->slug ? 'selected' : '' }}
                                                    data-short-name="{{ $value->stockyard_short_name }}">
                                                    {{ $value->lot_number }}</option>
                                            @endforeach
                                        </select>
                       
                                    
                                    @error('borrowed_lot_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <h5 class="my-2">
                                        <i data-feather="package" class="font-medium-4 mr-25"></i>
                                        <span class="align-middle">Details</span>
                                    </h5> 
                                 </div>
                                 <div class="mb-1 col-md-4">
                                    <label class="form-label" for="rcn_net_weight">RCN Net Weight (Kg)<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="rcn_net_weight" id="rcn_net_weight"
                                        value="{{ $outward_rcn_data->rcn_net_weight }}" class="form-control number-mask"
                                        maxlength="10" placeholder="(Kilogram)" />
                                    @error('rcn_net_weight')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="rcn_bags">RCN Bags (No.s)<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="number" name="rcn_bags" id="rcn_bags"
                                        value="{{ $outward_rcn_data->rcn_bags }}" class="form-control number-mask"
                                        maxlength="10" placeholder="(No.s)" />
                                    @error('rcn_bags')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="tare_weight">Tare Weight (Kg)<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="tare_weight" id="tare_weight"
                                        value="{{ $outward_rcn_data->tare_weight }}" class="form-control number-mask"
                                        maxlength="10" placeholder="(Kilogram)" />
                                    @error('tare_weight')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                

                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="out_turn">Out Turn (Lbs)</label>
                                    <input type="text" name="out_turn" id="out_turn"
                                        value="{{ $outward_rcn_data->out_turn }}" class="form-control number-mask"
                                        maxlength="10" placeholder="(Lbs)" />
                                    @error('out_turn')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="nut_count">Nut Count (No.s)</label>
                                    <input type="number" name="nut_count" id="nut_count"
                                        value="{{ $outward_rcn_data->nut_count }}" class="form-control number-mask"
                                        maxlength="10" placeholder="(No.s)" />
                                    @error('nut_count')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="rejection">Rejection (%)</label>
                                    <input type="number" name="rejection" id="rejection"
                                        value="{{ $outward_rcn_data->rejection }}" class="form-control number-mask"
                                        max="100" step=".01" placeholder="(Percentage)" />
                                    @error('rejection')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="moisture_level">Moisture Level<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="moisture_level" id="moisture_level"
                                        value="{{ old('moisture_level') }}">
                                        <option value="1" @if ($outward_rcn_data->moisture_level == '1') selected='selected' @endif>Dry</option>
                                        <option value="2" @if ($outward_rcn_data->moisture_level == '2') selected='selected' @endif>Semi</option>
                                        <option value="3" @if ($outward_rcn_data->moisture_level == '3') selected='selected' @endif>Un Dry</option>
                                    </select>
                                    @error('moisture_level')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                
                                {{-- <div class="mb-1 col-md-4">
                                    <label class="form-label" for="rejection">Document</label>
                                    <input type="file" name="document" id="document" class="form-control" />
                                    @error('document')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div> --}}
                                <div class="col-12">
                            
                                    <h5 class="my-2">
                                        <i data-feather="truck" class="font-medium-4 mr-25"></i>
                                        <span class="align-middle">Status</span>
                                    </h5>
                                
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="status">Status<label class="text-danger px-sm-25">
                                            *</label></label>
                                    <select class="form-select select2" name="status" id="status"
                                        value="{{ old('status') }}">
                                        @foreach (\Helper::inWardOutWardConditionalStatus($outward_rcn_data->status) as $index => $status)
                                            <option value="{{ $index }}" @if (old('status', $outward_rcn_data->status) == $index) selected='selected' @endif>
                                                {{ $status }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4 @if (old('status', $outward_rcn_data->status) == 0) d-none @endif" id="dispatch">
                                    <label class="form-label" for="dispatched_date_time">Dispatched Date Time<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" id="fp-default" name="dispatched_date_time" id="dispatched_date_time"
                                        value="{{ old('dispatched_date_time', $outward_rcn_data->dispatched_date_time ? $outward_rcn_data->dispatched_date_time->format('d-m-Y h:is') : '') }}"
                                        class="form-control date-time-picker" />
                                    @error('dispatched_date_time')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4 @if (old('status', $outward_rcn_data->status) != 2) d-none @endif" id="receive">
                                    <label class="form-label" for="received_date_time">Received Date Time<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="received_date_time" id="received_date_time"
                                        value="{{ old('received_date_time', $outward_rcn_data->received_date_time ? $outward_rcn_data->received_date_time->format('d-m-Y h:i:s') : '') }}"
                                        class="form-control date-time-picker" />
                                    @error('received_date_time')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <a href="{{ url('stockyard/outward-rcn') }}"><button
                                            class="dt-button buttons-collection btn btn-outline-secondary me-2" tabindex="0"
                                            aria-controls="DataTables_Table_0" type="button"
                                            aria-haspopup="true"><span>Back</span></button></a>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                            <input type="hidden" name="slug" id="slug" value="{{ $outward_rcn_data->slug }}">
                        </form>
                    </div>
                </div>
            </div>
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
    <script src="{{ asset(mix('js/scripts/forms/form-input-mask.js')) }}">
    </script>
    <script>
     $(document).ready(function() {
var statuss = $("#stock_fromE").val();

var value = $("#borrowed_lot_numberE").val();

if (value!=""){
    document.getElementById("stock_fromE").value = "borrowed";
    document.getElementById("borrowed_lot_numbersE").style.display = "block";
}else{
    document.getElementById("stock_fromE").value = "same";
    document.getElementById("borrowed_lot_numbersE").style.display = "none";
}

     
      
        });
    </script>
       <script>
   
        $("#stock_fromE").on('change', function() {
           var status = $("#stock_fromE").val();
           if (status == "borrowed") {
               document.getElementById("borrowed_lot_numbersE").style.display = "block";

           }else if(status =="same"){

           document.getElementById("borrowed_lot_numbersE").style.display = "none";
           document.getElementById("borrowed_lot_numberE").value = "";
       
           }else {
             
             
           }
       });
</script>

    <script>
        $(document).ready(function() {


    
            $(".date-time-picker").flatpickr({
                dateFormat: "d-m-Y H:i:s",
                enableTime: true,
            });

            //Slug Generation
            var stockyardRcnLotNumber, stockyardRcnSlug, slug, factoryShortName, factorySlug;
            $('#stockyardRcnList, #factoryList').on('change', function(e) {
                stockyardRcnLotNumber = $('#stockyardRcnList :selected').text();
                stockyardRcnSlug = $('#stockyardRcnList :selected').val();
                factoryShortName = $('#factoryList :selected').data('short-name');
                factorySlug = $('#factoryList :selected').val();

                if (stockyardRcnSlug && factorySlug) {
                    slug = stockyardRcnLotNumber + '-' + factoryShortName;
                    $('#slug').val(slug);
                }
            });

            $("#status").on('change', function() {
                var status = $("#status").val();
                if (status == 0) {
                    $("#dispatch, #receive").addClass('d-none');
                } else if (status == 1) {
                    $("#receive").addClass('d-none');
                    $("#dispatch").removeClass('d-none');
                } else {
                    $("#dispatch, #receive").removeClass('d-none');
                }
            });
        });
    </script>
@endsection

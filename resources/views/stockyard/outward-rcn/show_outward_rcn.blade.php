@extends('layouts/contentLayoutMaster ')
@section('title', 'Outward RCN Details')
@section('content')
    <div class="">
        <div class="card">
            <div class="card-header p-1 border-bottom">
                <div class="head-label">
                    <h4 class="card-title">Outward RCN - {{ $outward_rcn_data->ewb_number }}<strong></strong></h4>
                </div>
                <div class="dt-action-buttons text-end">
                    <div class="dt-buttons d-inline-flex">
                        <a href="{{ url('stockyard/outward-rcn') }}"
                            class="dt-button buttons-collection btn btn-outline-secondary me-2 waves-effect">Back</a>
                        @if (\Helper::userAccess('stockyard-outward-rcn-edit') && !$outward_rcn_data->status)
                            <a href="{{ url('/stockyard/outward-rcn/' . $outward_rcn_data->slug . '/edit') }}"
                                class="btn btn-primary">
                                <i data-feather="edit"></i> Edit
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Lot No</label>
                            <span
                                class="form-control">{{ $outward_rcn_data->stockyardRcnStockDetails->lot_number }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Factory</label>
                            <span class="form-control">{{ $outward_rcn_data->factory->factory_name }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Truck Reg No.</label>
                            <span class="form-control">{{ $outward_rcn_data->truck_reg_number }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Contact Number</label>
                            <span class="form-control">{{ $outward_rcn_data->contact_number }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">DC Number</label>
                            <span class="form-control">{{ $outward_rcn_data->dc_number }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">EWB Number</label>
                            <span class="form-control">{{ $outward_rcn_data->ewb_number }} </span>
                        </div>
                    </div>
                    <div class="mb-1 col-md-4">
                        <label class="form-label" for="ewb_number">Stock from<label
                                class="text-danger px-sm-25"> *</label></label>
                                <span class="form-control" id="stock_fromEs"> </span>
                                {{-- <select  class="form-control " disabled name="stock_from" id="stock_fromE">

                                    <option value="same">Same</option>
                                    <option value="borrowed">Borrowed</option>
                                </select> --}}
                     
                        @error('stock_from')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-1 col-md-4" id="borrowed_lot_numbersE">
                        <label class="form-label " for="ewb_number">Lot Number(if borrowed)<label
                                class="text-danger px-sm-25"> *</label></label>
                        {{-- <input type="text" name="borrowed_lot_number" id="borrowed_lot_number" value="{{ old('borrowed_lot_number') }}"
                            class="form-control alphanumeric" maxlength="32" /> --}}
                            <span class="form-control" id="borrowed_lot_numberEs"> </span>
                             <input hidden type="text" class="form-control  " name="borrowed_lot_number" id="borrowed_lot_numberE" value="{{$outward_rcn_data->borrowed_lot_number}}"
                            > 
                           
           
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">RCN Net Weight</label>
                            <span class="form-control">{{ $outward_rcn_data->rcn_net_weight }} Kg </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">RCN Bags</label>
                            <span class="form-control">{{ $outward_rcn_data->rcn_bags }} </span>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Tare Weight</label>
                            <span class="form-control">{{ $outward_rcn_data->tare_weight }} Kg </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Out Turn</label>
                            <span class="form-control">{{ $outward_rcn_data->out_turn }}
                                {{ $outward_rcn_data->out_turn ? 'Lbs' : '' }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Nut Count</label>
                            <span class="form-control">{{ $outward_rcn_data->nut_count }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Rejection</label>
                            <span class="form-control">{{ $outward_rcn_data->rejection }}
                                {{ $outward_rcn_data->out_turn ? '%' : '' }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Moisture Level</label>
                            @if ($outward_rcn_data->moisture_level == 1)
                                <span class="form-control">Dry</span>
                            @elseif($outward_rcn_data->moisture_level == 2)
                                <span class="form-control">Semi</span>
                            @elseif($outward_rcn_data->moisture_level == 3)
                                <span class="form-control">Un Dry</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-12">
                  
                        <h5 class="my-2">
                            <i data-feather="truck" class="font-medium-4 mr-25"></i>
                            <span class="align-middle">Status</span>
                        </h5>
                       
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Dispatched Date Time</label>
                            <span
                                class="form-control">{{ $outward_rcn_data->dispatched_date_time ? $outward_rcn_data->dispatched_date_time->format('d-m-Y h:i:00 a') : 'Not dispatched yet' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Received Date Time</label>
                            <span
                                class="form-control">{{ $outward_rcn_data->received_date_time ? $outward_rcn_data->received_date_time->format('d-m-Y h:i:00 a') : 'Not received yet' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            @if ($outward_rcn_data->status == 0)
                                <span class="form-control">Schedule</span>
                            @elseif($outward_rcn_data->status == 1)
                                <span class="form-control">Dispatch</span>
                            @elseif($outward_rcn_data->status == 2)
                                <span class="form-control">Received</span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
   var statuss = $("#stock_fromEs").val();
   
   var value = $("#borrowed_lot_numberE").val();
   
   if (value!=""){
       document.getElementById("stock_fromEs").value = "borrowed";
       document.getElementById("borrowed_lot_numbersE").style.display = "block";
       $("#stock_fromEs").html("Borrowed");
       $("#borrowed_lot_numberEs").html(value);
       
     
   }else{
       document.getElementById("stock_fromEs").value = "same";
       document.getElementById("borrowed_lot_numbersE").style.display = "none";
       $("#stock_fromEs").html("Same");
   }
   
         
           });
       </script>
@endsection

@extends('layouts/contentLayoutMaster')

@section('title', 'Edit RCN Stock - Split')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('stockyard.rcn-stocks.updateSplit', $stockyard_rcn_data->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                   
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="stockyard_slug">Stockyard</label>
                                    <span class="form-control" >{{ $stockyard_rcn_data->stockyardDetails->stockyard_name }}</span>
                                    <!-- <select class="form-select select2" name="stockyard_slug" id="stockyardList"
                                        value="{{ old('stockyard_slug') }}">
                                        <option value=""  selected>-Select-</option>
                                        @foreach ($stockyards as $value)
                                            <option value="{{ $value->slug }}"
                                                {{ $stockyard_rcn_data->stockyard_slug == $value->slug ? 'selected' : '' }}>
                                                {{ $value->stockyard_name }}</option>
                                        @endforeach
                                    </select>
                                   -->
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="lot_number">Lot No.</label>
                                    <span class="form-control" >{{ $stockyard_rcn_data->lot_number }}</span>

                                    <input type="hidden" name="lot_number" id="lot_number" class="form-control"
                                        value="{{ $stockyard_rcn_data->lot_number }}" readonly />
                                 
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="lot_number">Account Lot No.</label>
                                    <span class="form-control" >{{ $stockyard_rcn_data->account_lot_number }}</span>
                                </div>
                           
                                <h5 class="my-2">
                                    <i data-feather="database" class="font-medium-4 mr-25"></i>
                                    <span class="align-middle">Stock Details</span> 
                                </h5>
                                <div class="mb-1 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">RCN Weight(Kg) </label>
                                        <span class="form-control" >{{$stockyard_rcn_data->bl_despatched_rcn_weight }}</span>
                                        <input type="hidden"  name="rcn_kg" id="rcn_kg" value="{{ old('rcn_kg',$stockyard_rcn_data->bl_despatched_rcn_weight) }}"
                                        class="form-control number-mask"/>
                                     
                                    </div>
                                </div>
                                <div class="mb-1 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Bags</label>
                                        <span class="form-control" >{{$stockyard_rcn_data->bl_despatched_rcn_bags }}</span>
                                        <input type="hidden" name="rcn_bags" id="rcn_bags" value="{{ old('rcn_bags',(int)$stockyard_rcn_data->bl_despatched_rcn_bags) }}"
                                        class="form-control number-mask"/>
                                        
                                    </div>
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="account_id">Account</label>
                                    <span class="form-control" >{{$stockyard_rcn_data->account['account_name']  }}</span>
                              
                                  
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="sub_account_id">Sub Account</label>
                                    <span class="form-control" >{{$stockyard_rcn_data->subaccount['account_state']  }}</span>
                                   
                               
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="sub_account_id">Parent Lot Number</label>
                                    <span class="form-control" >{{$parent_lot_number  }}</span>
                                   
                                 
                                </div>
                                
                                @if (\Helper::userAccess('stockyard-rcn-adjust-rcn-current-stock'))
                                <div class="col-12">
                            
                                    <h5 class="my-2">
                                        <i data-feather="sliders" class="font-medium-4 mr-25"></i>
                                        <span class="align-middle ">Adjust - Current Stock</span>
                                    </h5>
                                    <span class="" style="color: red">Note:-</span>    <small class="" style="color: red">This Stock Details Will Not Effect Parent Lot Number</small>

                                </div>
                                <div class="mb-1 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">RCN Weight(Kg) </label>
                                        <input type="text" maxlength="10" name="balance_rcn_stock" id="balance_rcn_stock" value="{{ old('balance_rcn_stock',$stockyard_rcn_data->balance_rcn_stock) }}"
                                        class="form-control number-mask"/>
                                        @error('balance_rcn_stock')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-1 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Bags</label>
                                        <input type="text"  maxlength="6" name="balance_rcn_bag" id="balance_rcn_bag" value="{{ old('balance_rcn_bag',(int)$stockyard_rcn_data->balance_rcn_bag) }}"
                                        class="form-control number-mask"/>
                                        @error('balance_rcn_bag')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                @endif
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <a href="{{ route('stockyard.rcn-stock') }}"><button
                                            class="dt-button buttons-collection btn btn-outline-secondary me-2" tabindex="0"
                                            aria-controls="DataTables_Table_0" type="button"
                                            aria-haspopup="true"><span>Back</span></button></a>
                                    <button type="submit" class="btn btn-primary" >Update</button>
                                </div>
                            </div>
                            
                            <input type="hidden" name="slug" id="slug" value="{{ $stockyard_rcn_data->slug }}">
                            <input type="hidden" name="account_lot_number" id="account_lot_number" value="{{$stockyard_rcn_data->account_lot_number}}">
                            <input type='hidden' name='disable_edit' id ='disable_edit' value ="{{$disable_edit}}">
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
    <script src="{{ asset(mix('js/scripts/forms/form-input-mask.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/app/state-account.js')) }}"></script>
    <script>
        $(document).ready(function() {
            fetchWarehouses();

            $('#accountList').on('change', function(e) {
                var acc_slug = e.target.value;
                $.ajax({
                    url: "{{ url('stockyard/rcn-stock/sub-account-list') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "acc_slug": acc_slug
                    },
                    success: function(response) {
                        $('select[name="sub_account_id"]').empty();
                        $.each(response.sub_accounts, function(key, value) {
                            $('select[name="sub_account_id"]').append(
                                '<option value=" ' + value
                                .id + '">' + value.account_state + '</option>');
                        })
                    }
                })
            });

            // $('#stockyardList').on('change', function(e) {
            //     fetchWarehouses();
            // });

            function fetchWarehouses() {
                var stockyard_slug =  "{{$stockyard_rcn_data->stockyard_slug}}";
                $.ajax({
                    url: "{{ url('admin/stockyard/warehouses') }}/" + stockyard_slug,
                    type: "GET",
                    success: function(response) {
                        let $warehouse = $('#warehouse');
                        let selected = $warehouse.data('selected');
                        $warehouse.html('<option value=""> -Select- </option>');
                        $.each(response, function(slug, name) {
                            $warehouse.append('<option value="' + slug + '" >' +
                                name + '</option>');
                        });
                        $warehouse.val(selected).select2();
                    }

                })
            }
        })
    </script>
@endsection

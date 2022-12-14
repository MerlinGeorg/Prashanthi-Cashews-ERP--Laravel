@extends('layouts/contentLayoutMaster')

@section('title', 'Edit RCN Stock')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('stockyard.rcn-stock.update', $stockyard_rcn_data->id) }}" method="POST">
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
                                    @error('stockyard_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror -->
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="stockyard">Warehouse<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="warehouse_slug" id="warehouse"
                                        data-selected="{{ old('warehouse_slug', $stockyard_rcn_data->warehouse_slug) }}">
                                        <option value=""> -Select- </option>
                                    </select>
                                    @error('warehouse_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="account_id">Account</label>
                                    <select class="form-select select2" name="account_id" id="accountList"
                                        value="{{ old('account') }}">
                                        <option value="" disabled selected>-Select-</option>
                                        @foreach ($accounts as $key => $value)
                                            <option value="{{ $value->slug }}"
                                                {{ $sub_account->account->id == $value->id ? 'selected' : '' }}>
                                                {{ $value->account_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('account_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="sub_account_id">Sub Account</label>
                                    <select class="form-select select2" name="sub_account_id" id="subAccountList"
                                        value="{{ old('sub_account_id') }}">
                                        <option value="{{ $sub_account->id }}" selected>
                                            {{ $sub_account->account_state }}</option>
                                    </select>
                                    @error('sub_account_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="lot_number">Lot Number</label>
                                    <input type="text" name="lot_number" id="lot_number" class="form-control"
                                        value="{{ $stockyard_rcn_data->lot_number }}" readonly />
                                    @error('lot_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" maxlength="32" for="rcn_mark">RCN Mark</label>
                                    <select class="form-select select2" name="rcn_mark" id="rcn_mark"
                                        value="{{ old('rcn_marks') }}">
                                        <option value="" disabled selected>-Select-</option>
                                        @foreach ($rcn_marks as $slug => $value)
                                            <option value="{{ $slug }}"
                                                {{ $stockyard_rcn_data->rcn_mark == $slug ? 'selected' : '' }}>
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('rcn_mark')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" maxlength="32" for="shipper_company_slug">Shipper
                                        Company</label>
                                    <select class="form-select select2" name="shipper_company_slug"
                                        id="shipper_company_slug" value="{{ old('shipper_company_slug') }}">
                                        <option value="" disabled selected>Select</option>
                                        @foreach ($shipper_details as $value)
                                            <option value="{{ $value->slug }}"
                                                {{ $stockyard_rcn_data->shipper_company_slug == $value->slug ? 'selected' : '' }}>
                                                {{ $value->shipper_company_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('shipper_company_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="be_number">BE Number</label>
                                    <input type="text" name="be_number" id="be_number"
                                        value="{{ $stockyard_rcn_data->be_number }}" class="form-control alphanumeric"
                                        maxlength="32" />
                                    @error('be_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="bl_number">BL Number</label>
                                    <input type="text" name="bl_number" id="bl_number"
                                        value="{{ $stockyard_rcn_data->bl_number }}" class="form-control alphanumeric"
                                        maxlength="32" />
                                    @error('bl_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" maxlength="128" for="invoice_number">Invoice
                                        Number</label>
                                    <input type="text" name="invoice_number" id="invoice_number"
                                        value="{{ $stockyard_rcn_data->invoice_number }}" class="form-control"
                                        maxlength="32" />
                                    @error('invoice_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="bl_despatched_rcn_weight">BL Despatched RCN Weight(Kg)</label>
                                    @if($disable_edit)
                                        <span class="form-control">{{ $stockyard_rcn_data->bl_despatched_rcn_weight }}</span>
                                    @else
                                    <input type="text" maxlength="10" name="bl_despatched_rcn_weight"  id="bl_despatched_rcn_weight"
                                            value="{{ $stockyard_rcn_data->bl_despatched_rcn_weight }}"
                                            class="form-control number-mask" />
                                        @error('bl_despatched_rcn_weight')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    @endif
                                        

                                </div>
                                <div class="mb-1 col-md-6">
                                
                                    <label class="form-label" for="bl_despatched_rcn_bags">BL Despatched RCN
                                        Bags</label>
                                @if($disable_edit)  
                                    <span class="form-control">{{ (int) $stockyard_rcn_data->bl_despatched_rcn_bags }}</span>
                                @else
                                    <input type="number" maxlength="10" name="bl_despatched_rcn_bags"
                                        id="bl_despatched_rcn_bags"
                                        value="{{ (int) $stockyard_rcn_data->bl_despatched_rcn_bags }}"
                                        class="form-control number-mask" />
                                    @error('bl_despatched_rcn_bags')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    @endif
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="out_turn">Out Turn</label>
                                    <input type="text" maxlength="10" name="out_turn" id="out_turn"
                                        value="{{ $stockyard_rcn_data->out_turn }}" class="form-control number-mask" />
                                    @error('out_turn')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="nut_count">Nut Count</label>
                                    <input type="number" maxlength="10" name="nut_count" id="nut_count"
                                        value="{{  (int) $stockyard_rcn_data->nut_count }}" class="form-control number-mask " />
                                    @error('nut_count')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="rejection">Rejection</label>
                                    <input type="text" maxlength="10" name="rejection" id="rejection"
                                        value="{{ $stockyard_rcn_data->rejection }}" class="form-control number-mask" />
                                    @error('rejection')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="total_containers">No. of Containers<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    @if($disable_edit)
                                        <span class="form-control">{{ $stockyard_rcn_data->total_containers }}</span>
                                    @else
                                    <input type="number" name="total_containers" id="total_containers" value="{{ old('total_containers',$stockyard_rcn_data->total_containers) }}"
                                        class="form-control number-mask" maxlength="3" />
                                    @error('total_containers')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    @endif
                                </div>
                                @if (\Helper::userAccess('stockyard-rcn-adjust-rcn-current-stock'))
                                <div class="col-12">
                            
                                    <h5 class="my-2">
                                        <i data-feather="sliders" class="font-medium-4 mr-25"></i>
                                        <span class="align-middle">Adjust - Current Stock</span>
                                    </h5>
                                
                                </div>
                                <div class="mb-1 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">RCN Weight(Kg) </label>
                                        <input type="text" name="balance_rcn_stock" id="balance_rcn_stock" value="{{ old('balance_rcn_stock',$stockyard_rcn_data->balance_rcn_stock) }}"
                                        class="form-control number-mask"/>
                                        @error('balance_rcn_stock')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-1 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Bags</label>
                                        <input type="number" name="balance_rcn_bag" id="balance_rcn_bag" value="{{ old('balance_rcn_bag',(int)$stockyard_rcn_data->balance_rcn_bag) }}"
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

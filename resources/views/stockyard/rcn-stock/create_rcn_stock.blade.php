@extends('layouts/contentLayoutMaster')

@section('title', 'Add RCN Stock')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('stockyard.rcn-stock.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="stockyard">Stockyard<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="stockyard_slug" id="stockyardList"
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
                                    <label class="form-label" for="stockyard">Warehouse<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="warehouse_slug" id="warehouse"
                                        data-selected="{{ old('warehouse_slug') }}">
                                        <option value=""> -Select- </option>
                                    </select>
                                    @error('warehouse_slug')
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
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" maxlength="32" for="rcn_mark">RCN Mark<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="rcn_mark" id="rcn_mark"
                                        value="{{ old('rcn_marks') }}">
                                          <option value=''>Select</option>
                                        <!-- <option value="" disabled selected>-Select-</option> -->
                                        @foreach ($rcn_marks as $slug => $value)
                                            <option value="{{ $slug }}" @if (old('rcn_mark') == $slug) selected @endif>
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('rcn_mark')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" maxlength="32" for="shipper_company">Shipper Company<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="shipper_company_slug"
                                        id="shipper_company_slug" value="{{ old('shipper_company_slug') }}">
                                        <option value=''>Select</option>
                                        <!-- <option value="" disabled selected>-Select-</option> -->
                                        @foreach ($shipper_details as $value)
                                            <option value="{{ $value->slug }}" @if (old('shipper_company_slug') == $value->slug) selected @endif>
                                                {{ $value->shipper_company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shipper_company_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="be_number">BE Number<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="be_number" id="be_number" value="{{ old('be_number') }}"
                                        class="form-control " maxlength="32" />
                                    @error('be_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="bl_number">BL Number<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="bl_number" id="bl_number" value="{{ old('bl_number') }}"
                                        class="form-control" maxlength="32" />
                                    @error('bl_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" maxlength="128" for="invoice_number">Invoice Number<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="invoice_number" id="invoice_number"
                                        value="{{ old('invoice_number') }}" class="form-control" maxlength="32" />
                                    @error('invoice_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="bl_despatched_rcn_weight">BL Despatched RCN Weight
                                        (Kg)<label class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="bl_despatched_rcn_weight" id="bl_despatched_rcn_weight"
                                        value="{{ old('bl_despatched_rcn_weight') }}" class="form-control number-mask"
                                        maxlength="10" placeholder="(Kilogram)" />
                                    @error('bl_despatched_rcn_weight')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="bl_despatched_rcn_bags">BL Despatched RCN Bags<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="number" name="bl_despatched_rcn_bags" id="bl_despatched_rcn_bags"
                                        value="{{ old('bl_despatched_rcn_bags') }}" class="form-control number-mask"
                                        maxlength="10" />
                                    @error('bl_despatched_rcn_bags')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
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
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="number" name="nut_count" id="nut_count" value="{{ old('nut_count') }}"
                                        class="form-control number-mask" maxlength="10" />
                                    @error('nut_count')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="rejection">Rejection<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="rejection" id="rejection" value="{{ old('rejection') }}"
                                        class="form-control number-mask" maxlength="10" />
                                    @error('rejection')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="total_containers">No. of Containers<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="number" name="total_containers" id="total_containers" value="{{ old('total_containers') }}"
                                        class="form-control number-mask" maxlength="3" />
                                    @error('total_containers')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
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

            $('#stockyardList').on('change', function(e) {
                fetchWarehouses();
            });

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
                        console.log(response);
                        $.each(response, function(slug, name) {
                            $warehouse.append('<option value="' + slug + '" >' +
                                name + '</option>');
                        });

                        $warehouse.val(selected).select2();
                    }

                })
            }
        });
    </script>
@endsection

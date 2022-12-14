@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Stockyard')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('stockyard.update', $stockyard->slug) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="stockyard_name">Name<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="32" name="stockyard_name" id="stockyard_name"
                                        value="{{ old('stockyard_name', $stockyard->stockyard_name) }}"
                                        class="form-control" />
                                    @error('stockyard_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="stockyard_short_name">Short Name<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="32" name="stockyard_short_name" id="stockyard_short_name"
                                        value="{{ old('stockyard_short_name', $stockyard->stockyard_short_name) }}"
                                        class="form-control" />
                                    @error('stockyard_short_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="stockyard_reg_number">Registration / Door No <label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="32" name="stockyard_reg_number" id="stockyard_reg_number"
                                        value="{{ old('stockyard_reg_number', $stockyard->stockyard_reg_number) }}"
                                        class="form-control" />
                                    @error('stockyard_reg_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="office_slug">Office<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="office_slug" id="office_slug"
                                        value="{{ $stockyard->office->slug }}">
                                        <option value="">Select</option>
                                        @if ($offices)
                                            @foreach ($offices as $office)
                                                @if ($stockyard->office->slug == $office->slug)
                                                    <option value="{{ $office->slug }}" selected>
                                                        {{ $office->office_name }}</option>
                                                @else
                                                    <option value="{{ $office->slug }}">{{ $office->office_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('office_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="stockyard_state">State<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2 state_account" onchange="getstateaccounts()"
                                        name="stockyard_state" id="stockyard_state"
                                        value="{{ old('stockyard_state', $stockyard->stockyard_state) }}">
                                        <option value="">Select</option>
                                        @if (config('constants.states'))
                                            @foreach (config('constants.states') as $state)
                                                @if ($state == old('stockyard_state', $stockyard->stockyard_state))
                                                    <option value="{{ $state }}" selected>{{ $state }}
                                                    </option>
                                                @else
                                                    <option value="{{ $state }}">{{ $state }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('office_state')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="sub_account_slug">Account<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2 state_account_list" name="sub_account_slug"
                                        id="sub_account_slug"
                                        data-selected="{{ old('sub_account_slug', $stockyard->subaccount->slug) }}"
                                        value="{{ old('sub_account_slug', $stockyard->subaccount->slug) }}">
                                        <option value="">Select</option>
                                    </select>
                                    @error('sub_account_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="contact_address_1">Address Line 1<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="128" name="contact_address_1" id="contact_address_1"
                                        value="{{ old('contact_address_1', $stockyard->contact_address_1) }}"
                                        class="form-control" />
                                    @error('contact_address_1')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" maxlength="128" for="contact_address_2">Address Line
                                        2</label>
                                    <input type="text" name="contact_address_2" id="contact_address_2"
                                        value="{{ old('contact_address_1', $stockyard->contact_address_2) }}"
                                        class="form-control" />
                                    @error('contact_address_2')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="stockyard_pincode">Pincode<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="6" name="stockyard_pincode" id="stockyard_pincode"
                                        value="{{ old('stockyard_pincode', $stockyard->stockyard_pincode) }}"
                                        class="form-control pincode-mask" />
                                    @error('stockyard_pincode')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="align-items-center my-1">
                                    <i data-feather="lock"> </i>
                                    <strong> Warehouse Informations</strong>
                                </div>
                                <?php
                                $warehouse_val_count = max(sizeof(old('warehouse', $stockyard->warehouses)), 1);
                                
                                ?>
                                <div data-repeater-list="warehouse" class="">
                                    <div data-repeater-item="" class="align-items-end">
                                        <div class="row d-flex align-items-end">
                                            <!-- <div class="row"> -->
                                            <div class="invoice-repeater">
                                                <div data-repeater-list="warehouse" class="">
                                                    @for ($i = 0; $i < $warehouse_val_count; $i++)
                                                        <div data-repeater-list="warehouse" class="">
                                                            <div data-repeater-item=""
                                                                class="d-block align-items-end p-2 mx-25 border mb-1">
                                                                <div class="row align-items-end">
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="mb-1">
                                                                            <label class="form-label"
                                                                                for="warehouse_name">Warehouse Name<label
                                                                                    class="text-danger px-sm-25">
                                                                                    *</label></label>
                                                                            <input type="text" maxlength="128"
                                                                                name="warehouse_name" id="warehouse_name"
                                                                                value="{{ old('warehouse.' . $i . '.warehouse_name', $stockyard->warehouses[$i]['warehouse_name'] ?? '') }}"
                                                                                class="form-control" maxlength="32" />
                                                                            @error('warehouse.' . $i . '.warehouse_name')
                                                                                <div class="alert alert-danger">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-5 col-12">
                                                                        <div class="mb-1">
                                                                            <label class="form-label"
                                                                                for="warehouse_account_slug">Account<label
                                                                                    class="text-danger px-sm-25">
                                                                                    *</label></label>
                                                                            <select
                                                                                class="form-select select2 state_account_list"
                                                                                name="warehouse_account_slug"
                                                                                id="warehouse_account_slug"
                                                                                data-selected="{{ old('warehouse.' . $i . '.warehouse_account_slug', $stockyard->warehouses[$i]['warehouse_account_slug'] ?? '') }}">
                                                                                <option value="">Select</option>

                                                                            </select>
                                                                            @error('warehouse.' . $i .
                                                                                '.warehouse_account_slug')
                                                                                <div class="alert alert-danger">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>

                                                                        <input type="hidden" name="warehouse_slug"
                                                                            value="{{ $stockyard->warehouses[$i]['slug'] ?? '' }}">
                                                                    </div>

                                                                    <div class="col-md-1 col-12 text-end">
                                                                        <div class="mb-1">
                                                                            <button
                                                                                class="btn btn-outline-danger text-nowrap px-1 waves-effect"
                                                                                data-repeater-delete="" type="button">
                                                                                <i data-feather="x"
                                                                                    class="font-medium-2"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                </div>

                                                <div class="col-md-12 col-12 text-center px-5 py-1">
                                                    <button id="add-repeater"
                                                        class="btn btn-icon btn-outline-success waves-effect waves-float waves-light"
                                                        type="button" data-repeater-create="">
                                                        <i data-feather="plus" class="font-medium-2"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <a href="{{ route('admin.stockyard') }}"><button
                                            class="dt-button buttons-collection btn btn-outline-secondary me-2" tabindex="0"
                                            aria-controls="DataTables_Table_0" type="button"
                                            aria-haspopup="true"><span>Back</span></button></a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
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
    <script src="{{ asset(mix('js/scripts/forms/repeater/jquery.repeater.min.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/app/account/form-repeater.js')) }}"></script>
    <script>
        $(document).ready(function() {
            getstateaccounts();

            $("#add-repeater").on('click', function() {
                getstateaccounts();
            });
        })
    </script>
@endsection

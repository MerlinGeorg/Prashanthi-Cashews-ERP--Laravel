@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Factory')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('factory.update', $factory->slug) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="factory_of">Factory of<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" onchange="getfactoryofchange()" name="factory_of"
                                        id="factory_of" value="{{ old('factory_of', $factory->factory_of) }}">
                                        <option value="">Select</option>
                                        @if (config('constants.factory_of'))
                                            @foreach (config('constants.factory_of') as $key => $ind_factory_of)
                                                @if ($ind_factory_of == old('factory_of', $factory->factory_of))
                                                    <option value="{{ $key }}" selected>{{ $ind_factory_of }}
                                                    </option>
                                                @else
                                                    <option value="{{ $key }}">{{ $ind_factory_of }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('factory_of')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="factory_name">Name<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="32" name="factory_name" id="factory_name"
                                        value="{{ old('factory_name', $factory->factory_name) }}"
                                        class="form-control" />
                                    @error('factory_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="factory_short_name">Short Name<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="32" name="factory_short_name" id="factory_short_name"
                                        value="{{ old('factory_short_name', $factory->factory_short_name) }}"
                                        class="form-control" />
                                    @error('factory_short_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="factory_reg_number">Registration / Door No <label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="32" name="factory_reg_number" id="factory_reg_number"
                                        value="{{ old('factory_reg_number', $factory->factory_reg_number) }}"
                                        class="form-control" />
                                    @error('factory_reg_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="factory_location">Location<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="32" name="factory_location" id="factory_location"
                                        value="{{ old('factory_location', $factory->factory_location) }}"
                                        class="form-control" />
                                    @error('factory_location')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="factory_office_slug">Office<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="factory_office_slug" id="factory_office_slug"
                                        value="{{ old('factory_office_slug', $factory->factory_office_slug) }}">
                                        <option value="">Select</option>
                                        @if ($offices)
                                            @foreach ($offices as $office)
                                                @if ($office->slug == old('factory_office_slug', $factory->factory_office_slug))
                                                    <option value="{{ $office->slug }}" selected>
                                                        {{ $office->office_name }}</option>
                                                @else
                                                    <option value="{{ $office->slug }}">{{ $office->office_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('factory_office_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="factory_state">State<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2 state_account"
                                        onchange="getstateaccounts('{{ old('factory_sub_account_slug', $factory->factory_sub_account_slug) }}')"
                                        name="factory_state" id="factory_state"
                                        value="{{ old('factory_state', $factory->factory_state) }}">
                                        <option value="">Select</option>
                                        @if (config('constants.states'))
                                            @foreach (config('constants.states') as $state)
                                                @if ($state == old('factory_state', $factory->factory_state))
                                                    <option value="{{ $state }}" selected>{{ $state }}
                                                    </option>
                                                @else
                                                    <option value="{{ $state }}">{{ $state }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('factory_state')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="factory_sub_account_slug">Account<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2 state_account_list" name="factory_sub_account_slug"
                                        id="factory_sub_account_slug"
                                        value="{{ old('factory_sub_account_slug', $factory->factory_sub_account_slug) }}">
                                        <option value="">Select</option>
                                        <!-- @if ($accounts)
                                                                                                                        @foreach ($accounts as $account)
                                                                                                                            <option>{{ $account->account_name }}</option>
                                                                                                                        @endforeach
                                                                                                                    @endif -->
                                    </select>
                                    @error('factory_sub_account_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" maxlength="128" for="factory_power_allocation">Power
                                        allocation <small>(K V)</small><label class="text-danger px-sm-25">
                                            *</label></label>
                                    <input type="text" name="factory_power_allocation" id="factory_power_allocation"
                                        value="{{ old('factory_power_allocation', $factory->factory_power_allocation) }}"
                                        class="form-control number-mask" placeholder="K V" maxlength="6" />
                                    @error('factory_power_allocation')
                                        <div class=" alert alert-danger">{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" maxlength="128" for="factory_contact_address_1">Address
                                        Line 1<label class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="factory_contact_address_1" id="factory_contact_address_1"
                                        value="{{ old('factory_contact_address_1', $factory->factory_contact_address_1) }}"
                                        class="form-control" />
                                    @error('factory_contact_address_1')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" maxlength="128" for="factory_contact_address_2">Address
                                        Line 2</label>
                                    <input type="text" name="factory_contact_address_2" id="factory_contact_address_2"
                                        value="{{ old('factory_contact_address_2', $factory->factory_contact_address_2) }}"
                                        class="form-control" />
                                    @error('factory_contact_address_2')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="factory_pincode">Pincode<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="6" name="factory_pincode" id="factory_pincode"
                                        value="{{ old('factory_pincode', $factory->factory_pincode) }}"
                                        class="form-control pincode-mask" placeholder="K V" />
                                    @error('factory_pincode')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- </div> -->
                                <?php
                                $already_processor_types = $factory->factoryProcessing()->pluck('factory_processing_capacity', 'factory_processing_types');
                                ?>
                                <div class="row processing_type_div">
                                    <label class="form-label">Processing Types</label>
                                    @foreach (config('constants.processing_types') as $key => $processing_type)
                                        <?php
                                        $old_val = old('processor_types_list.' . $key . '.processing_type');
                                        $checked_val = isset($old_val) ? 'checked' : (isset($already_processor_types[$key]) ? 'checked' : '');
                                        $disabled_checkbox = old('factory_of', $factory->factory_of) == 'Prashanthi' ? '' : 'disabled';
                                        $disabled_textbox = $disabled_checkbox != 'disabled' && $checked_val == 'checked' ? '' : 'disabled';
                                        ?>
                                        <div class="col-md-4">
                                            <div class="mb-1">
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <div class="form-check">
                                                            <input {{ $disabled_checkbox }} {{ $checked_val }}
                                                                class="form-check-input processor_type_checkbox"
                                                                name="processor_types_list[{{ $key }}][processing_type]"
                                                                value="{{ $processing_type }}" type="checkbox"
                                                                id="inputCheckbox">{{ $processing_type }}
                                                        </div>
                                                    </div>
                                                    <input type="text"
                                                        class="form-control number-mask processing_capacity_{{ str_replace(' ', '', $processing_type) }}"
                                                        name="processor_types_list[{{ $key }}][processing_capacity]"
                                                        value="{{ old('processor_types_list.' . $key . '.processing_capacity', isset($already_processor_types[$key]) ? $already_processor_types[$key] : '') }}"
                                                        placeholder="Processing Capacity (Kg)" {{ $disabled_textbox }}
                                                        maxlength="6">

                                                </div>
                                                @error('processor_types_list.' . $key . '.processing_type')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                @error('processor_types_list.' . $key . '.processing_capacity')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <a href="{{ route('admin.factory') }}"><button
                                        class="dt-button buttons-collection btn btn-outline-secondary me-2" tabindex="0"
                                        aria-controls="DataTables_Table_0" type="button"
                                        aria-haspopup="true"><span>Back</span></button></a>
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
    <script src="{{ asset(mix('js/scripts/forms/form-input-mask.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/repeater/jquery.repeater.min.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/app/factory/factory.js')) }}"></script>
    <script>
        let account_slug = "{{ old('factory_sub_account_slug', $factory->factory_sub_account_slug) }}";
        getstateaccounts(account_slug);
        getfactoryofchange();
    </script>
@endsection

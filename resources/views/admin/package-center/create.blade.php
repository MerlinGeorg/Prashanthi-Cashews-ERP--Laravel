@extends('layouts/contentLayoutMaster')

@section('title', 'Add Package Center')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.package-center.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="package_center_name">Name<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="32" name="package_center_name" id="package_center_name"
                                        value="{{ old('package_center_name') }}" class="form-control" />
                                    @error('package_center_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="package_center_short_name">Short Name<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="32" name="package_center_short_name"
                                        id="package_center_short_name" value="{{ old('package_center_short_name') }}"
                                        class="form-control" />
                                    @error('package_center_short_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="package_center_office_slug">Office<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="package_center_office_slug"
                                        id="package_center_office_slug" value="{{ old('package_center_office_slug') }}">
                                        <option value="">Select</option>
                                        @if ($offices)
                                            @foreach ($offices as $office)
                                                @if ($office->slug == old('package_center_office_slug'))
                                                    <option value="{{ $office->slug }}" selected>
                                                        {{ $office->office_name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $office->slug }}">{{ $office->office_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('package_center_office_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="package_center_reg_number">Registration / Door No
                                        <label class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="package_center_reg_number" maxlength="32"
                                        id="package_center_reg_number" value="{{ old('package_center_reg_number') }}"
                                        class="form-control" />
                                    @error('package_center_reg_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="package_center_state">State<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2 state_account"
                                        onchange="getstateaccounts('{{ old('package_center_sub_account_slug') }}')"
                                        name="package_center_state" id="package_center_state"
                                        value="{{ old('package_center_state') }}">
                                        <option value="">Select</option>
                                        @if (config('constants.states'))
                                            @foreach (config('constants.states') as $state)
                                                @if ($state == old('package_center_state'))
                                                    <option value="{{ $state }}" selected>{{ $state }}
                                                    </option>
                                                @else
                                                    <option value="{{ $state }}">{{ $state }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('package_center_state')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="account_slug">Account<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2 state_account_list"
                                        name="package_center_sub_account_slug" id="package_center_sub_account_slug"
                                        value="{{ old('package_center_sub_account_slug') }}">
                                        <option value="">Select</option>
                                        <!-- @if ($accounts)
                                                                    @foreach ($accounts as $account)
                                                                        <option>{{ $account->account_name }}</option>
                                                                    @endforeach
                                                                @endif -->
                                    </select>
                                    @error('package_center_sub_account_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" maxlength="128"
                                        for="package_center_power_allocation">Power Allocation <small>(K v)</small><label
                                            class="text-danger px-sm-25 "> *</label></label>
                                    <input type="text" name="package_center_power_allocation"
                                        id="package_center_power_allocation" placeholder="K V"
                                        value="{{ old('package_center_power_allocation') }}"
                                        class="form-control number-mask" maxlength="6" />
                                    @error('package_center_power_allocation')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="package_center_location">Location<label
                                            class="text-danger px-sm-25">
                                            *</label></label>
                                    <input type="text" name="package_center_location" maxlength="128"
                                        id="package_center_location" value="{{ old('package_center_location') }}"
                                        class="form-control" />
                                    @error('package_center_location')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="package_center_contact_address_1">Address Line
                                        1<label class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="128" name="package_center_contact_address_1"
                                        id="package_center_contact_address_1"
                                        value="{{ old('package_center_contact_address_1') }}" class="form-control" />
                                    @error('package_center_contact_address_1')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="package_center_contact_address_2">Address Line
                                        2</label>
                                    <input type="text" name="package_center_contact_address_2" maxlength="128"
                                        id="package_center_contact_address_2"
                                        value="{{ old('package_center_contact_address_2') }}" class="form-control" />
                                    @error('package_center_contact_address_2')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="package_center_pincode">Pincode<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="6" name="package_center_pincode"
                                        id="package_center_pincode" value="{{ old('package_center_pincode') }}"
                                        class="form-control pincode-mask" />
                                    @error('package_center_pincode')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <a href="{{ route('admin.package-center') }}"><button
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
    <script>
        let account_slug = "{{ old('package_center_sub_account_slug') }}";
        getstateaccounts(account_slug);
    </script>
@endsection

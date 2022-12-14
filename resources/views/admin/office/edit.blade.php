@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Office')

@section('content')

    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('office.update', $office->slug) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="qualification">Name<label class="text-danger px-sm-25">
                                            *</label></label>
                                    <input type="text" maxlength="32" name="office_name" id="office_name"
                                        value="{{ old('office_name', $office->office_name) }}" class="form-control" />
                                    @error('office_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="experiences">Short Name<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="32" name="office_short_name" id="office_short_name"
                                        value="{{ old('office_short_name', $office->office_short_name) }}"
                                        class="form-control" />
                                    @error('office_short_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="qualification">Registration Number<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="32" name="office_reg_number" id="office_reg_number"
                                        value="{{ old('office_reg_number', $office->office_reg_number) }}"
                                        class="form-control" />
                                    @error('office_reg_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="experiences">Location<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="32" name="office_location" id="office_location"
                                        value="{{ old('office_location', $office->office_location) }}"
                                        class="form-control" />
                                    @error('office_location')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="experiences">Address Line 1<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="128" name="office_address_1" id="office_address_1"
                                        value="{{ old('office_address_1', $office->office_address_1) }}"
                                        class="form-control" />
                                    @error('office_address_1')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="qualification">Address Line 2</label>
                                    <input type="text" maxlength="128" name="office_address_2" id="office_address_2"
                                        value="{{ old('office_address_2', $office->office_address_2) }}"
                                        class="form-control" />
                                    @error('office_address_2')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="office_state">State<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="office_state" id="office_state"
                                        value="{{ old('office_state', $office->office_state) }}">
                                        <option value="">Select</option>
                                        @if (config('constants.states'))
                                            @foreach (config('constants.states') as $state)
                                                @if ($state == old('office_state', $office->office_state))
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
                                    <label class="form-label" for="qualification">Pincode<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="6" name="office_pincode" id="office_pincode"
                                        value="{{ old('office_pincode', $office->office_pincode) }}"
                                        class="form-control pincode-mask" />
                                    @error('office_pincode')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="experiences">Phone Number<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">IN (+91)</span>
                                        <input type="text" maxlength="15" name="office_phone_number"
                                            id="office_phone_number"
                                            value="{{ old('office_phone_number', $office->office_phone_number) }}"
                                            class="form-control number-mask" />
                                    </div>
                                    @error('office_phone_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <a href="{{ route('admin.office') }}"><button
                                            class="dt-button buttons-collection btn btn-outline-secondary me-2" tabindex="0"
                                            aria-controls="DataTables_Table_0" type="button"
                                            aria-haspopup="true"><span>Back</span></button></a>
                                    <button type="submit" class="btn btn-primary">Update</button>
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
    <script src="{{ asset(mix('js/scripts/forms/form-input-mask.js')) }}">
    </script>
@endsection

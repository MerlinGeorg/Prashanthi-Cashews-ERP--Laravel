@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Shipper Details')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('shipper-details.update', $shipper_details->slug) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="shipper_company_name">Name<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="32" name="shipper_company_name" id="shipper_company_name"
                                        value="{{ old('shipper_company_name', $shipper_details->shipper_company_name) }}"
                                        class="form-control" />
                                    @error('shipper_company_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="shipper_location">Location<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="32" name="shipper_location" id="shipper_location"
                                        value="{{ old('shipper_location', $shipper_details->shipper_location) }}"
                                        class="form-control" />
                                    @error('shipper_location')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="shipper_contact_address_1">Address
                                        Line 1<label class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="128" name="shipper_contact_address_1"
                                        id="shipper_contact_address_1"
                                        value="{{ old('shipper_contact_address_1', $shipper_details->shipper_contact_address_1) }}"
                                        class="form-control" />
                                    @error('shipper_contact_address_1')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="shipper_contact_address_2">Address
                                        Line 2</label>
                                    <input type="text" maxlength="128" name="shipper_contact_address_2"
                                        id="shipper_contact_address_2"
                                        value="{{ old('shipper_contact_address_2', $shipper_details->shipper_contact_address_2) }}"
                                        class="form-control" />
                                    @error('shipper_contact_address_2')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <a href="{{ route('admin.shipper-details') }}"><button
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
        let account_slug = "{{ old('sub_account_slug') }}";
        getstateaccounts(account_slug);
    </script>
@endsection

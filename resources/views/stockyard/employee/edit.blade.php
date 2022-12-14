@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Employee')

@section('vendor-style')
    {{-- Vendor Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/wizard/bs-stepper.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/file-uploaders/dropzone.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-wizard.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-file-uploader.css')) }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Employee Details</h4>
                </div>
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

                    <div class="w-100">
                        <div class="bs-stepper register-multi-steps-wizard shadow-none">
                            <div class="bs-stepper-header px-0" role="tablist">
                                <div class="step" data-target="#personal-info" role="tab"
                                    id="personal-info-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="user" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Personal</span>
                                            <span class="bs-stepper-subtitle">Personal informations</span>
                                        </span>
                                    </button>
                                </div>

                                <div class="line">
                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                </div>
                                <div class="step" data-target="#company-info" role="tab" id="company-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="server" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Company</span>
                                            <span class="bs-stepper-subtitle">Company informations</span>
                                        </span>
                                    </button>
                                </div>
                                <div class="line">
                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                </div>
                                <div class="step" data-target="#contact" role="tab" id="contact-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="phone" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Contact</span>
                                            <span class="bs-stepper-subtitle">Contact details</span>
                                        </span>
                                    </button>
                                </div>
                                <div class="line">
                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                </div>
                                <div class="step" data-target="#upload" role="tab" id="upload-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">
                                            <i data-feather="upload" class="font-medium-3"></i>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Identification</span>
                                            <span class="bs-stepper-subtitle">Upload your files</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="bs-stepper-content px-0 mt-0 pt-0">

                                <div id="personal-info" class="content" role="tabpanel"
                                    aria-labelledby="personal-info-trigger">
                                    <div class="content-header mb-2">
                                        <h2 class="fw-bolder mb-75">Personal Information</h2>
                                        <span>Enter your personal information</span>
                                    </div>
                                    <form>
                                        <input type="hidden" name="slug" id="slug" value="{{ $employee->slug }}">
                                        <div class="row">
                                            <div class="mb-1 col-md-6">
                                                <label class="form-label" for="name">Full Name</label>
                                                <span class="text-danger px-sm-25"> *</span>
                                                <input type="text" name="name" id="name" class="form-control"
                                                    maxlength="30" value="{{ $employee->name }}" />
                                            </div>

                                            <div class="col-lg-6 col-md-6 form-group">

                                                <label class="d-block mb-1">Gender
                                                    <span class="text-danger px-sm-25"> *</span></label>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" id="male" name="gender" class="form-check-input"
                                                        value="male" @if ($employee->gender == 'male') checked @endif />
                                                    <label class="custom-control-label" for="male">Male</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" id="female" name="gender" class="form-check-input"
                                                        value="female" @if ($employee->gender == 'female') checked @endif />
                                                    <label class="custom-control-label" for="female">Female</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-1 col-md-6">
                                                <label class="form-label" for="religion">Religion </label>
                                                <option value="">Select Religion</option>
                                                <select class="form-control select2" name="religion" id="religion">
                                                    <option value="">Select Religion</option>
                                                    @foreach ($religions as $slug => $religion)
                                                        <option @if ($slug == $employee->religion) selected @endif value="{{ $slug }}">
                                                            {{ $religion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-1 col-md-6">
                                                <label class="form-label" for="dob">DOB</label>
                                                <span class="text-danger px-sm-25"> *</span>
                                                <input type="text" name="dob" id="dob" class="form-control"
                                                    placeholder="DD-MM-YYYY"
                                                    value="{{ $employee->dob ? $employee->dob->format('d-m-Y') : '' }}" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-1 col-md-6">
                                                <label class="form-label" for="aadhar_no">Aadhaar Number</label>
                                                <span class="text-danger px-sm-25"> *</span>
                                                <input type="text" name="aadhar_no" id="aadhar_no"
                                                    class="form-control aadhar-mask" maxlength="14" minlength="14"
                                                    value="{{ $employee->aadhar_no }}" />
                                            </div>

                                            <div class="mb-1 col-md-6">
                                                <label class="form-label" for="nationality">Nationality</label>
                                                <span class="text-danger px-sm-25"> *</span>
                                                <select class="form-control select2" name="nationality" id="nationality">
                                                    <option value="">Select Nationality</option>
                                                    @foreach ($nationalities as $slug => $nationality)
                                                        <option @if ($slug == $employee->nationality) selected @endif value="{{ $slug }}">
                                                            {{ $nationality }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-1 col-md-6">
                                                <label class="form-label" for="status">Status<span
                                                        class="text-danger px-sm-25">
                                                        *</span></label>
                                                <select class="form-control select2" name="status" id="status">
                                                    @foreach ($statuses as $slug => $status)
                                                        <option @if ($slug == $employee->status) selected @endif value="{{ $slug }}">
                                                            {{ $status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </form>
                                    <div class="d-flex justify-content-between mt-2">
                                        <button class="btn btn-primary btn-prev">
                                            <i data-feather="chevron-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-primary btn-next">
                                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                                            <i data-feather="chevron-right" class="align-middle ms-sm-25 ms-0"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="company-info" class="content" role="tabpanel"
                                    aria-labelledby="personal-info-trigger">
                                    <div class="content-header mb-2">
                                        <h2 class="fw-bolder mb-75">Company Information</h2>
                                        <span>Enter your c informations</span>
                                    </div>
                                    <form>
                                        <div class="row">
                                            <div class="mb-1 col-md-6">
                                                <label class="form-label" for="employee_no">Employee Number</label>
                                                <span class="text-danger px-sm-25"> *</span>
                                                <input type="text" name="employee_no" id="employee_no"
                                                    class="form-control" maxlength="30"
                                                    value="{{ $employee->employee_no }}" />
                                            </div>
                                            <div class="col-lg-6 col-md-6 form-group">
                                                <label class="d-block mb-1">Job Type
                                                    <span class="text-danger px-sm-25"> *</span></label>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" id="permanent" name="job_type"
                                                        class="form-check-input" value="permanent"
                                                        @if ($employee->job_type == 'permanent') checked @endif />
                                                    <label class="custom-control-label" for="male">Permanent</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" id="temporary" name="job_type"
                                                        class="form-check-input" value="temporary"
                                                        @if ($employee->job_type == 'temporary') checked @endif />
                                                    <label class="custom-control-label" for="temporary">Temporary</label>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-1 col-md-6">
                                                <label class="form-label" for="work_location_type">Work Location
                                                    Type</label>
                                                <select class="form-control select2" name="work_location_type"
                                                    id="work_location_type">
                                                    <option value="">Select Work Location
                                                        Type</option>
                                                    @foreach ($work_location_types as $key => $name)
                                                        <option @if ($employee->work_location_type == $key) selected @endif value="{{ $key }}">
                                                            {{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-1 col-md-6">
                                                <label class="form-label" for="work_location_slug">Work
                                                    Location</label>
                                                <select class="form-control select2" name="work_location_slug"
                                                    id="work_location_slug">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-1 col-md-6">
                                                <label class="form-label" for="job_category">Job Category</label>
                                                <span class="text-danger px-sm-25"> *</span>
                                                <select class="form-control select2" name="job_category" id="job_category">
                                                    <option value="">Select Job Category</option>
                                                    @foreach ($job_categories as $key => $name)
                                                        <option @if ($employee->job_category == $key) selected @endif value="{{ $key }}">
                                                            {{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-1 col-md-6">
                                                <label class="form-label" for="join_date">Join Date</label>
                                                <input type="text" name="join_date" id="join_date" class="form-control"
                                                    placeholder="DD-MM-YYYY"
                                                    value="{{ $employee->join_date ? $employee->join_date->format('d-m-Y') : '' }}" />
                                            </div>
                                        </div>
                                    </form>

                                    <div class="d-flex justify-content-between mt-2">
                                        <button class="btn btn-primary btn-prev">
                                            <i data-feather="chevron-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-primary btn-next">
                                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                                            <i data-feather="chevron-right" class="align-middle ms-sm-25 ms-0"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="contact" class="content" role="tabpanel" aria-labelledby="billing-trigger">

                                    <form>
                                        {{-- @csrf --}}
                                        <div class="content-header my-2 py-1">
                                            <h2 class="fw-bolder mb-75">Contact Information</h2>
                                            <span>Enter your contact information</span>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label" for="mobile">Mobile Number</label>
                                                <span class="text-danger px-sm-25"> *</span>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text">IN (+91)</span><input type="text"
                                                        name="mobile" id="mobile" class="form-control number-mask"
                                                        minlength="10" maxlength="10" value="{{ $employee->mobile }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label" for="whatsapp">Whatsapp Number</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text">IN (+91)</span><input type="text"
                                                        name="whatsapp" id="whatsapp" class="form-control number-mask"
                                                        minlength="10" maxlength="10"
                                                        value="{{ $employee->whatsapp }}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-md-6 mb-1">
                                                <label class="form-label" for="email">Email</label>
                                                <input type="email" name="email" id="email" class="form-control"
                                                    placeholder="Email Address" value="{{ $employee->email }}" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label" for="address_line_1">Address Line 1</label>
                                                <span class="text-danger px-sm-25"> *</span>
                                                <input type="text" name="address_line_1" id="address_line_1"
                                                    class="form-control" maxlength="100"
                                                    value="{{ $employee->address_line_1 }}" />
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label" for="address_line_2">Address Line 2</label>
                                                <input type="text" name="address_line_2" id="address_line_2"
                                                    class="form-control" maxlength="100"
                                                    value="{{ $employee->address_line_2 }}" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label" for="city">City</label>
                                                <span class="text-danger px-sm-25"> *</span>
                                                <input type="text" name="city" id="city" class="form-control"
                                                    maxlength="30" value="{{ $employee->city }}" />
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label" for="district">District</label>
                                                <span class="text-danger px-sm-25"> *</span>
                                                <input type="text" name="district" id="district" class="form-control"
                                                    maxlength="30" value="{{ $employee->district }}" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label" for="state">State</label>
                                                <span class="text-danger px-sm-25"> *</span>
                                                <select class="form-control select2" name="state" id="state">
                                                    <option value="">Select State</option>
                                                    @foreach ($states as $slug => $state)
                                                        <option @if ($slug == $employee->state) selected @endif value="{{ $slug }}">
                                                            {{ $state }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label" for="pincode">Pincode</label>
                                                <span class="text-danger px-sm-25"> *</span>
                                                <input type="text" name="pincode" id="pincode"
                                                    class="form-control pincode-mask" maxlength="6"
                                                    value="{{ $employee->pincode }}" />
                                            </div>

                                        </div>

                                    </form>

                                    <div class="d-flex justify-content-between mt-1">
                                        <button class="btn btn-primary btn-prev">
                                            <i data-feather="chevron-left" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-primary btn-next">
                                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                                            <i data-feather="chevron-right" class="align-middle ms-sm-25 ms-0"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="upload" class="content" role="tabpanel" aria-labelledby="upload-trigger">
                                    <div class="content-header my-2 py-1">
                                        <h2 class="fw-bolder mb-75">Identification files</h2>
                                        <span>Upload your Identification files</span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form action="{{ url('/admin/employee/upload') }}"
                                                class="dropzone dropzone-area" id="files-form">
                                                @csrf
                                                <div class="dz-message">Drop files here or click to upload.</div>
                                                <div id="file-error" class="error hidden">Please upload your
                                                    identifiaction files.
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button class="btn btn-secondary btn-prev" data-step-id="5">
                                            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none ">Previous</span>
                                        </button>

                                        <button class="btn btn-success btn-submit">
                                            <i data-feather="check" class="align-middle me-sm-25 me-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Submit</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/file-uploaders/dropzone.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/addons/cleave-phone.in.js')) }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/forms/form-file-uploader.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-validation.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/app/stockyard/employee/create.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-input-mask.js')) }}"></script>
    <script>
        $(document).ready(function() {
            fetchWorkLocation($("#work_location_type").val(), '{{ $employee->work_location_slug }}');
        });
    </script>
@endsection

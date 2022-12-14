@extends('layouts/contentLayoutMaster')

@section('title', 'Staff Edit')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/wizard/bs-stepper.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/file-uploaders/dropzone.min.css')) }}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-wizard.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-file-uploader.css')) }}">
    <style>
        .auth-wrapper.auth-cover .auth-inner {
            height: 100%;
        }

    </style>
@endsection

@section('content')

    <!-- Register-->
    <div class="row">
        <div class="card">
            <div class="card-body bs-stepper register-multi-steps-wizard shadow-none">
                <div class="bs-stepper-header px-2" role="tablist">
                    <div class="step" data-target="#personal-info" role="tab" id="personal-info-trigger">
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
                    <div class="step" data-target="#profile" role="tab" id="profile-trigger">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-box">
                                <i data-feather="image" class="font-medium-3"></i>
                            </span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-title">Profile Image</span>
                                <span class="bs-stepper-subtitle">Upload your profile image</span>
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

                    <div id="personal-info" class="content" role="tabpanel" aria-labelledby="personal-info-trigger">
                        <div class="content-header mb-2">
                            <h2 class="fw-bolder mb-75">Personal Information</h2>
                            <span>Enter your personal information</span>
                        </div>
                        <form>
                            <input type="hidden" name="slug" id="slug" value="{{ $staff->slug }}">
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="name">Full Name</label>
                                    <span class="text-danger px-sm-25"> *</span>
                                    <input type="text" name="name" id="name" class="form-control" maxlength="30"
                                        value="{{ $staff->name }}" />
                                </div>

                                <div class="col-lg-6 col-md-6 form-group">

                                    <label class="d-block mb-1">Gender <span class="text-danger px-sm-25">
                                            *</span></label>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="male" name="gender" class="form-check-input" value="male"
                                            @if ($staff->gender == 'male') checked @endif />
                                        <label class="custom-control-label" for="male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="female" name="gender" class="form-check-input"
                                            value="female" @if ($staff->gender == 'female') checked @endif />
                                        <label class="custom-control-label" for="female">Female</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="qualification">Qualification<span
                                            class="text-danger px-sm-25"> *</span></label>
                                    <input type="text" name="qualification" id="qualification" class="form-control"
                                        maxlength="50" value="{{ $staff->qualification }}" />
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="religion">Religion </label>
                                    <option value="">Select Religion</option>
                                    <select class="form-control select2" name="religion" id="religion">
                                        <option value="">Select Religion</option>
                                        @foreach ($religions as $slug => $religion)
                                            <option @if ($slug == $staff->religion) selected @endif value="{{ $slug }}">
                                                {{ $religion }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="dob">DOB<span class="text-danger px-sm-25">
                                            *</span></label>
                                    <input type="text" name="dob" id="dob" class="form-control" placeholder="DD-MM-YYYY"
                                        value="{{ $staff->dob ? $staff->dob->format('d-m-Y') : '' }}" />
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="aadhar_no">Aadhaar Number<span
                                            class="text-danger px-sm-25"> *</span></label>
                                    <input type="text" name="aadhar_no" id="aadhar_no" class="form-control aadhar-mask"
                                        maxlength="14" minlength="14" value="{{ $staff->aadhar_no }}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="nationality">Nationality<span
                                            class="text-danger px-sm-25"> *</span></label>
                                    <select class="form-control select2" name="nationality" id="nationality">
                                        <option value="">Select Nationality</option>
                                        @foreach ($nationalities as $slug => $nationality)
                                            <option @if ($slug == $staff->nationality) selected @endif value="{{ $slug }}">
                                                {{ $nationality }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label class="form-label" for="username">Username<span class="text-danger px-sm-25">
                                            *</span></label>
                                    <input type="text" name="username" id="username" class="form-control"
                                        placeholder="Username" value="{{ $staff->username }}" />
                                </div>

                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="status">Status<span class="text-danger px-sm-25">
                                            *</span></label>
                                    <select class="form-control select2" name="status" id="status">
                                        @foreach ($statuses as $slug => $status)
                                            <option @if ($slug == $staff->status) selected @endif value="{{ $slug }}">
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
                    <div id="company-info" class="content" role="tabpanel" aria-labelledby="personal-info-trigger">
                        <div class="content-header mb-2">
                            <h2 class="fw-bolder mb-75">Company Information</h2>
                            <span>Enter your c informations</span>
                        </div>
                        <form>
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="employee_no">Employee Number<span
                                            class="text-danger px-sm-25"> *</span></label>
                                    <input type="text" name="employee_no" id="employee_no" class="form-control"
                                        maxlength="30" value="{{ $staff->employee_no }}" />
                                </div>
                                <div class="col-lg-6 col-md-6 form-group">
                                    <label class="d-block mb-1">Job Type<span class="text-danger px-sm-25">
                                            *</span></label>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="permanent" name="job_type" class="form-check-input"
                                            value="permanent" @if ($staff->job_type == 'permanent') checked @endif />
                                        <label class="custom-control-label" for="male">Permanent</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="temporary" name="job_type" class="form-check-input"
                                            value="temporary" @if ($staff->job_type == 'temporary') checked @endif />
                                        <label class="custom-control-label" for="temporary">Temporary</label>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="work_location_type">Work Location Type</label>
                                    <select class="form-control select2" name="work_location_type" id="work_location_type">
                                        <option value="">Select Work Location Type</option>
                                        @foreach ($work_location_types as $key => $name)
                                            <option @if ($staff->work_location_type == $key) selected @endif value="{{ $key }}">
                                                {{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="work_location_slug">Work Location</label>
                                    <select class="form-control select2" name="work_location_slug" id="work_location_slug">

                                    </select>
                                </div>
                            </div>
                            <div class="row">


                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="join_date">Join Date</label>
                                    <input type="text" name="join_date" id="join_date" class="form-control"
                                        placeholder="DD-MM-YYYY"
                                        value="{{ $staff->join_date ? $staff->join_date->format('d-m-Y') : '' }}" />
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="user_group">User Group</label>
                                    <select class="form-control select2" name="user_group" id="user_group">
                                        <option value="">Select User Group</option>
                                        @foreach ($user_groups as $key => $name)
                                            <option @if ($key == $staff->user_group) selected @endif value="{{ $key }}">
                                                {{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="roles">Roles</label>
                                    <select class="form-control select2" multiple name="roles[]" id="roles">
                                        @foreach ($roles as $role)
                                            <option @if (in_array($role->id, $user_roles)) selected @endif value="{{ $role->id }}">
                                                {{ $role->name }}</option>
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
                    <div id="contact" class="content" role="tabpanel" aria-labelledby="billing-trigger">

                        <form>
                            {{-- @csrf --}}
                            <div class="content-header my-2 py-1">
                                <h2 class="fw-bolder mb-75">Contact Information</h2>
                                <span>Enter your contact information</span>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <label class="form-label" for="mobile">Mobile Number<span
                                            class="text-danger px-sm-25"> *</span></label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">IN (+91)</span><input type="text" name="mobile"
                                            id="mobile" class="form-control number-mask" minlength="10" maxlength="10"
                                            value="{{ $staff->mobile }}" />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label class="form-label" for="whatsapp">Whatsapp Number</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">IN (+91)</span><input type="text" name="whatsapp"
                                            id="whatsapp" class="form-control number-mask" minlength="10" maxlength="10"
                                            value="{{ $staff->whatsapp }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6 mb-1">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="Email Address" value="{{ $staff->email }}" maxlength="80" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <label class="form-label" for="address_line_1">Address Line 1<span
                                            class="text-danger px-sm-25"> *</span></label>
                                    <input type="text" name="address_line_1" id="address_line_1" class="form-control"
                                        maxlength="100" value="{{ $staff->address_line_1 }}" />
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label class="form-label" for="address_line_2">Address Line 2</label>
                                    <input type="text" name="address_line_2" id="address_line_2" class="form-control"
                                        maxlength="100" value="{{ $staff->address_line_2 }}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <label class="form-label" for="city">City<span class="text-danger px-sm-25">
                                            *</span></label>
                                    <input type="text" name="city" id="city" class="form-control" maxlength="30"
                                        value="{{ $staff->city }}" />
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label class="form-label" for="district">District<span
                                            class="text-danger px-sm-25"> *</span></label>
                                    <input type="text" name="district" id="district" class="form-control" maxlength="30"
                                        value="{{ $staff->district }}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <label class="form-label" for="state">State<span class="text-danger px-sm-25">
                                            *</span></label>
                                    <select class="form-control select2" name="state" id="state">
                                        <option value="">Select State</option>
                                        @foreach ($states as $slug => $state)
                                            <option @if ($slug == $staff->state) selected @endif value="{{ $slug }}">
                                                {{ $state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label class="form-label" for="pincode">Pincode<span class="text-danger px-sm-25">
                                            *</span></label>
                                    <input type="text" name="pincode" id="pincode" class="form-control pincode-mask"
                                        maxlength="6" value="{{ $staff->pincode }}" />
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
                    <div id="profile" class="content" role="tabpanel" aria-labelledby="upload-trigger">
                        <div class="content-header my-2 py-1">
                            <h2 class="fw-bolder mb-75">Profile Image</h2>
                            <span>Upload your profile image</span>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="card-text">
                                    Accepted image format are <code>png, jpeg, jpg</code>
                                </p>
                                <form action="{{ url('/staff/upload-profile-image') }}" class="dropzone dropzone-area"
                                    id="profile-image-form">
                                    @csrf
                                    <div class="dz-message">Drop files here or click to upload.</div>
                                    <div id="file-error" class="error hidden">Please upload your profile image.
                                    </div>
                                </form>
                            </div>
                        </div>
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
                                <form action="{{ url('/staff/upload') }}" class="dropzone dropzone-area"
                                    id="files-form">
                                    @csrf
                                    <div class="dz-message">Drop files here or click to upload.</div>
                                    <div id="file-error" class="error hidden">Please upload your identifiaction files.
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

    <input type="hidden" id="redirect-to" value="{{ route('admin.staff') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/addons/cleave-phone.in.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/file-uploaders/dropzone.min.js')) }}"></script>
@endsection

@section('page-script')

    <script src="{{ asset(mix('js/scripts/forms/form-file-uploader.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-input-mask.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/app/staff-register.js')) }}"></script>
    <script>
        $(document).ready(function() {
            fetchWorkLocation($("#work_location_type").val(), '{{ $staff->work_location_slug }}');

            $("#work_location_type").on('change', function() {
                var roles = $("#roles");
                $.ajax({
                    url: "/staff/roles/" + $("#work_location_type").val(),
                    type: "GET",
                    async: false,
                    success: function(response) {
                        roles.html('<option value="">Select</option>');
                        $.each(response, function(i, data) {
                            roles.append('<option value="' + data.slug +
                                '">' + data.name + '</option>');
                        })

                        roles.select2();

                    }
                });
            });
        });
    </script>
@endsection

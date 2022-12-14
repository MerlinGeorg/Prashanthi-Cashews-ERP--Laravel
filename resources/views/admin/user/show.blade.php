@extends('layouts/contentLayoutMaster')

@section('title', 'Staff')

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

@section('content')
    <div class="">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom mb-1">
                    <div class="head-label">
                        <h4 class="card-title">Staff - <strong>{{ $staff->name }}</strong></h4>
                    </div>

                    <div class="dt-action-buttons text-end">
                        <div class="dt-action-buttons text-end">
                            <div class="dt-buttons d-inline-flex">
                                <a href="{{ route('admin.staff') }}"
                                    class="dt-button buttons-collection btn btn-outline-secondary me-2">
                                    <span>Back</span>
                                </a>
                                @if (\Helper::userAccess('office-staff-edit'))
                                    <a href="{{ route('admin.staff.edit', $staff->slug) }}"
                                        class="dt-button create-new btn btn-primary">
                                        <span><i data-feather="edit"></i> Edit</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12">
                            <img width="100" height="100" class=" my-2"
                                src="{{ Storage::exists($staff->profile_image) ? Storage::url($staff->profile_image) : asset('images/avatars/profile-placeholder.png') }}">
                        </div>
                    </div>
                    <div class="row col-12">
                        <h5 class="mb-1">
                            <i data-feather="user" class="font-medium-4 mr-25"></i>
                            <span class="align-middle">Personal Details</span>
                        </h5>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="form-label" for="name">Full Name</label>
                            <span class="form-control">{{ $staff->name }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="middle_name">Gender</label>
                            <span class="form-control">{{ \Str::title($staff->gender) }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="last_name">Qualification</label>
                            <span class="form-control">{{ $staff->qualification }} </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="dob">Religion</label>
                                <span class="form-control">{{ $religions[$staff->religion] ?? '' }} </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">DOB</label>
                                <span class="form-control">{{ $staff->dob ? $staff->dob->format('d-m-Y') : '' }}
                                </span>
                            </div>
                        </div>

                        <div class=" col-md-4">
                            <div class="form-group">
                                <label class="form-label">Aadhaar Number</label>
                                <span class="form-control">{{ $staff->aadhar_no }} </span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="father_name">Nationality</label>
                                <span class="form-control">{{ $nationalities[$staff->nationality] ?? '' }} </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="father_name">Identification</label>
                                <span class="form-control">
                                    @php
                                        $files = json_decode($staff->identification_file);
                                    @endphp
                                    @if ($files)
                                        @foreach ($files as $file)
                                            <p>
                                                <a target="_blank" href="{{ \Storage::url($file) }}"
                                                    class="d-inline-block">
                                                    View document </a>
                                            </p>
                                        @endforeach
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="row col-12">
                        <h5 class="my-2">
                            <i data-feather="server" class="font-medium-4 mr-25"></i>
                            <span class="align-middle">Company Information</span>
                        </h5>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="form-label" for="employee_number">Employee Number</label>
                            <span class="form-control">{{ $staff->employee_no }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="club_name">User Group</label>
                            <span class="form-control">{{ $staff->userGroup->name ?? '' }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="club_name">Date of Join</label>
                            <span
                                class="form-control">{{ $staff->join_date ? $staff->join_date->format('d-m-Y') : '' }}
                            </span>
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group col-md-4">
                            <label class="form-label" for="club_name">Job Type</label>
                            <span class="form-control">{{ Str::title($staff->job_type) }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="club_name">Work Location Type</label>
                            <span class="form-control">{{ $work_location_types[$staff->work_location_type] ?? '' }}
                            </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="employee_number">Work Location</label>
                            <span
                                class="form-control">{{ isset($staff->workLocation) ? $staff->workLocation->name : '' }}
                            </span>
                        </div>

                    </div>

                    <div class="row col-12">
                        <h5 class="my-2">
                            <i data-feather="phone" class="font-medium-4 mr-25"></i>
                            <span class="align-middle">Contact Information</span>
                        </h5>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="form-label" for="mobile">Mobile</label>
                            <span class="form-control">{{ $staff->mobile }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="father_mobile">Whatsapp</label>
                            <span class="form-control">{{ $staff->whatsapp }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="email">Email</label>
                            <span class="form-control">{{ $staff->email }} </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="form-label" for="address_line_1">Address Line 1</label>
                            <span class="form-control">{{ $staff->address_line_1 }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="address_line_2">Address Line 2</label>
                            <span class="form-control">{{ $staff->address_line_2 }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="city">City</label>
                            <span class="form-control">{{ $staff->city }} </span>
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group col-md-4">
                            <label class="form-label" for="district">District</label>
                            <span class="form-control">{{ $staff->district }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="district">State</label>
                            <span class="form-control">{{ $states[$staff->state] ?? '' }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="pincode">Pincode</label>
                            <span class="form-control">{{ $staff->pincode }} </span>
                        </div>
                    </div>


                    <div class="row col-12">
                        <h5 class="my-2">
                            <i data-feather="lock" class="font-medium-4 mr-25"></i>
                            <span class="align-middle">Account </span>
                        </h5>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="form-label" for="status">Username</label>
                            <span class="form-control">{{ $staff->username }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="status">Status</label>
                            <span class="form-control">{{ \Str::title($staff->status) }} </span>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    </section>

@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection

@extends('layouts/contentLayoutMaster')

@section('title', 'Employee')

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom p-1">
                    <div class="head-label">
                        <h4 class="card-title">Employee - <strong>{{ $employee->name }}</strong></h4>
                    </div>
                    <div class="dt-action-buttons text-end">
                        <div class="dt-buttons d-inline-flex">
                            <a href="{{ route('stockyard.employee') }}"
                                class="dt-button buttons-collection btn btn-outline-secondary me-2 waves-effect">Back</a>
                            @if (\Helper::userAccess('stockyard-employee-edit'))
                                <a href="{{ route('stockyard.employee.edit', $employee->slug) }}" class="btn btn-primary">
                                    <i data-feather="edit"></i> Edit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row col-12">
                        <h5 class="my-1">
                            <i data-feather="user" class="font-medium-4 mr-25"></i>
                            <span class="align-middle">Personal Details</span>
                        </h5>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="form-label" for="name">Full Name</label>
                            <span class="form-control">{{ $employee->name }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="middle_name">Gender</label>
                            <span class="form-control">{{ \Str::title($employee->gender) }} </span>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">DOB</label>
                                <span class="form-control">{{ $employee->dob ? $employee->dob->format('d-m-Y') : '' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class=" col-md-4">
                            <div class="form-group">
                                <label class="form-label">Aadhaar Number</label>
                                <span class="form-control">{{ $employee->aadhar_no }} </span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="father_name">Nationality</label>
                                <span class="form-control">{{ $nationalities[$employee->nationality] ?? '' }} </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="dob">Religion</label>
                                <span class="form-control">{{ $religions[$employee->religion] ?? '' }} </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="father_name">Identification</label>
                                <span class="form-control">
                                    @php
                                        $files = json_decode($employee->identification_file);
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
                            <span class="form-control">{{ $employee->employee_no }} </span>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="form-label" for="club_name">Job Type</label>
                            <span class="form-control">{{ Str::title($employee->job_type) }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="club_name">Job Category</label>
                            <span class="form-control">{{ $employee->jobCategory->name ?? '' }} </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="form-label" for="club_name">Date of Join</label>
                            <span
                                class="form-control">{{ $employee->join_date ? $employee->join_date->format('d-m-Y') : '' }}
                            </span>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="form-label" for="club_name">Work Location Type</label>
                            <span class="form-control">{{ $work_location_types[$employee->work_location_type] }}
                            </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="employee_number">Work Location</label>
                            <span class="form-control">{{ $employee->workLocation->name ?? '' }} </span>
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
                            <span class="form-control">{{ $employee->mobile }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="father_mobile">Whatsapp</label>
                            <span class="form-control">{{ $employee->whatsapp }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="email">Email</label>
                            <span class="form-control">{{ $employee->email }} </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="form-label" for="address_line_1">Address Line 1</label>
                            <span class="form-control">{{ $employee->address_line_1 }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="address_line_2">Address Line 2</label>
                            <span class="form-control">{{ $employee->address_line_2 }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="city">City</label>
                            <span class="form-control">{{ $employee->city }} </span>
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group col-md-4">
                            <label class="form-label" for="district">District</label>
                            <span class="form-control">{{ $employee->district }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="district">State</label>
                            <span class="form-control">{{ $states[$employee->state] ?? '' }} </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="pincode">Pincode</label>
                            <span class="form-control">{{ $employee->pincode }} </span>
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
                            <label class="form-label" for="status">Status</label>
                            <span class="form-control">{{ \Str::title($employee->status) }} </span>
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

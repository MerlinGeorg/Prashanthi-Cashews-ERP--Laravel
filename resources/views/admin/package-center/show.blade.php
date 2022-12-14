@extends('layouts/contentLayoutMaster ')
@section('title', 'Packaging Center Details')
@section('content')
    <div class="">
        <div class="card">
            <div class="card-header p-1 border-bottom mb-1">
                <div class="head-label">
                    <h4 class="card-title">Packaging Center Details -
                        <strong>{{ $package_center->package_center_name }}</strong>
                    </h4>
                </div>
                <div class="dt-action-buttons text-end">
                    <div class="dt-buttons d-inline-flex">
                        <a href="{{ route('admin.package-center') }}"
                            class="dt-button buttons-collection btn btn-outline-secondary me-2">
                            <span>Back</span>
                        </a>
                        @if (\Helper::userAccess('office-package-center-edit'))
                            <a href="{{ route('admin.package-center.edit', $package_center->slug) }}"
                                class="dt-button create-new btn btn-primary">
                                <span><i data-feather="edit"></i> Edit</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <span class="form-control">{{ $package_center->package_center_name }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Short Name</label>
                            <span class="form-control">{{ $package_center->package_center_short_name }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Registration / Door No </label>
                            <span class="form-control">{{ $package_center->package_center_reg_number }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Office</label>
                            <span class="form-control">{{ $package_center->office->office_name }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">State</label>
                            <span class="form-control">{{ $package_center->package_center_state }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Account</label>
                            <span class="form-control">{{ $package_center->subaccount->account->account_name }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Power Allocation</label>
                            <span class="form-control">{{ $package_center->package_center_power_allocation }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Location</label>
                            <span class="form-control">{{ $package_center->package_center_location }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Address Line 1</label>
                            <span class="form-control">{{ $package_center->package_center_contact_address_1 }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Address Line 2</label>
                            <span class="form-control">{{ $package_center->package_center_contact_address_2 }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Pincode</label>
                            <span class="form-control">{{ $package_center->package_center_pincode }} </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

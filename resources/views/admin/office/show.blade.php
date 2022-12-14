@extends('layouts/contentLayoutMaster ')
@section('title', 'Office Details')
@section('content')
    <div class="">
        <div class="card">
            <div class="card-header p-1 border-bottom mb-1">
                <div class="head-label">
                    <h4 class="card-title">Office Details - <strong>{{ $office->office_name }}</strong></h4>
                </div>
                <div class="dt-action-buttons text-end">
                    <div class="dt-buttons d-inline-flex">
                        <a href="{{ route('admin.office') }}"
                            class="dt-button buttons-collection btn btn-outline-secondary me-2">
                            <span>Back</span>
                        </a>
                        @if (\Helper::userAccess('office-office-edit'))
                            <a href="{{ route('admin.office.edit', $office->slug) }}"
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
                            <span class="form-control">{{ $office->office_name }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Short Name</label>
                            <span class="form-control">{{ $office->office_short_name }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Registration Number</label>
                            <span class="form-control">{{ $office->office_reg_number }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Location</label>
                            <span class="form-control">{{ $office->office_location }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Address Line 1</label>
                            <span class="form-control">{{ $office->office_address_1 }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Address Line 2</label>
                            <span class="form-control">{{ $office->office_address_2 }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">State</label>
                            <span class="form-control">{{ $office->office_state }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Pincode</label>
                            <span class="form-control">{{ $office->office_pincode }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <span class="form-control">{{ $office->office_phone_number }} </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

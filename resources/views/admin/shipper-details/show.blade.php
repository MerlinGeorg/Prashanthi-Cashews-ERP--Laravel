@extends('layouts/contentLayoutMaster ')
@section('title', 'Shipper Details')
@section('content')
    <div class="">
        <div class="card">
            <div class="card-header p-1 border-bottom mb-1">
                <div class="head-label">
                    <h4 class="card-title">Shipper Details -
                        <strong>{{ $shipper_details->shipper_company_name }}</strong>
                    </h4>
                </div>
                <div class="dt-action-buttons text-end">
                    <div class="dt-buttons d-inline-flex">
                        <a href="{{ route('admin.shipper-details') }}"
                            class="dt-button buttons-collection btn btn-outline-secondary me-2">
                            <span>Back</span>
                        </a>
                        @if (\Helper::userAccess('office-shipper-details-edit'))
                            <a href="{{ route('admin.shipper-details.edit', $shipper_details->slug) }}"
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
                            <span class="form-control">{{ $shipper_details->shipper_company_name }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Location</label>
                            <span class="form-control">{{ $shipper_details->shipper_location }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Address Line 1</label>
                            <span class="form-control">{{ $shipper_details->shipper_contact_address_1 }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Address Line 2</label>
                            <span class="form-control">{{ $shipper_details->shipper_contact_address_2 }} </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

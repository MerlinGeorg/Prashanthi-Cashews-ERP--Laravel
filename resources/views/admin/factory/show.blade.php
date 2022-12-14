@extends('layouts/contentLayoutMaster ')
@section('title', 'Factory Details')
@section('content')
    <div class="">
        <div class="card">
            <div class="card-header p-1 border-bottom mb-1">
                <div class="head-label">
                    <h4 class="card-title">Factory Details - <strong>{{ $factory->factory_name }}</strong></h4>
                </div>
                <div class="dt-action-buttons text-end">
                    <div class="dt-buttons d-inline-flex">
                        <a href="{{ route('admin.factory') }}"
                            class="dt-button buttons-collection btn btn-outline-secondary me-2">
                            <span>Back</span>
                        </a>
                        @if (\Helper::userAccess('office-factory-edit'))
                            <a href="{{ route('admin.factory.edit', $factory->slug) }}"
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
                            <span class="form-control">{{ $factory->factory_name }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Short Name</label>
                            <span class="form-control">{{ $factory->factory_short_name }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Registration / Door No </label>
                            <span class="form-control">{{ $factory->factory_reg_number }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Factory of</label>
                            <span class="form-control">{{ $factory->factory_of }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Location</label>
                            <span class="form-control">{{ $factory->factory_location }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Office</label>
                            <span class="form-control">{{ $factory->office->office_name }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">State</label>
                            <span class="form-control">{{ $factory->factory_state }} </span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Account</label>
                            <span class="form-control">{{ $factory->subaccount->account->account_name }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Power Allocation</label>
                            <span class="form-control">{{ $factory->factory_power_allocation }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Address Line 1</label>
                            <span class="form-control">{{ $factory->factory_contact_address_1 }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Address Line 2</label>
                            <span class="form-control">{{ $factory->factory_contact_address_2 }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Pincode</label>
                            <span class="form-control">{{ $factory->factory_pincode }} </span>
                        </div>
                    </div>
                </div>
                @if ($factory->factory_of == 'Prashanthi' && sizeof($factory->factoryProcessing) > 0)
                    <div class="row col-12">
                        <h5 class="my-2">
                            <i data-feather="server" class="font-medium-4 mr-25"></i>
                            <span class="align-middle">Processing Types</span>
                        </h5>
                    </div>
                    @foreach ($factory->factoryProcessing as $processingtype)
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="form-label">Processing type</label>
                                <span class="form-control">{{ $processingtype->factory_processing_types }} </span>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-label">Processing Capacity</label>
                                <span class="form-control">{{ $processingtype->factory_processing_capacity }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </div>

@endsection

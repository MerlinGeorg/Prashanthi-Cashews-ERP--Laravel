@extends('layouts/contentLayoutMaster ')
@section('title', 'Inward RCN Details')
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header p-1 border-bottom ">
                <div class="head-label">
                    <h4 class="card-title">Inward RCN - {{ $inward_rcn_data->ewb_number }}<strong></strong></h4>
                </div>
                <div class="dt-action-buttons text-end">
                    <div class="dt-buttons d-inline-flex">
                        <a href="{{ url('stockyard/inward-rcn') }}"
                            class="dt-button buttons-collection btn btn-outline-secondary me-2 waves-effect">Back</a>
                        @if (\Helper::userAccess('stockyard-inward-rcn-edit') && $inward_rcn_data->status != 2)
                            <a href="{{ url('/stockyard/inward-rcn/' . $inward_rcn_data->slug . '/edit-rcn') }}"
                                class="btn btn-primary">
                                <i data-feather="edit"></i> Edit
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Truck Reg No.</label>
                            <span class="form-control">{{ $inward_rcn_data->truck_reg_number }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Container No.</label>
                            <span class="form-control">{{ $inward_rcn_data->container_number }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Seal No.</label>
                            <span class="form-control">{{ $inward_rcn_data->seal_number }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Contact No.</label>
                            <span class="form-control">{{ $inward_rcn_data->contact_number }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">DC No.</label>
                            <span class="form-control">{{ $inward_rcn_data->dc_number }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">EWB No.</label>
                            <span class="form-control">{{ $inward_rcn_data->ewb_number }} </span>
                        </div>
                    </div>
                    
                    
                    <h5 class="my-2">
                        <i data-feather="package" class="font-medium-4 mr-25"></i>
                        <span class="align-middle">Details</span>
                    </h5>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">RCN Net Weight</label>
                            <span class="form-control">{{ $inward_rcn_data->rcn_net_weight }} Kg </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">RCN Bags</label>
                            <span class="form-control">{{ $inward_rcn_data->rcn_bags }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Tare Weight</label>
                            <span class="form-control">{{ $inward_rcn_data->tare_weight }} Kg</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Out Turn</label>
                            <span class="form-control">{{ $inward_rcn_data->out_turn }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Nut Count</label>
                            <span class="form-control">{{ $inward_rcn_data->nut_count }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Rejection</label>
                            <span class="form-control">{{ $inward_rcn_data->rejection }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Moisture Level</label>
                            @if ($inward_rcn_data->moisture_level == 0)
                                <span class="form-control">Dry</span>
                            @elseif($inward_rcn_data->moisture_level == 1)
                                <span class="form-control">Semi</span>
                            @elseif($inward_rcn_data->moisture_level == 2)
                                <span class="form-control">Un Dry</span>
                            @endif
                        </div>
                    </div>
                    <h5 class="my-2">
                                        <i data-feather="truck" class="font-medium-4 mr-25"></i>
                                        <span class="align-middle">Status</span>
                    </h5>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            @if ($inward_rcn_data->status == 0)
                                <span class="form-control">Schedule</span>
                            @elseif($inward_rcn_data->status == 1)
                                <span class="form-control">Dispatch</span>
                            @elseif($inward_rcn_data->status == 2)
                                <span class="form-control">Received</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Dispatched Date Time</label>
                            <span
                                class="form-control">{{ date('d-m-Y H:i:s', strtotime($inward_rcn_data->dispatched_date_time)) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Received Date Time</label>
                            <span
                                class="form-control">{{ date('d-m-Y h:i:s', strtotime($inward_rcn_data->received_date_time)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@extends('layouts/contentLayoutMaster ')
@section('title', 'Factory RCN Inward')
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header border-bottom p-1">
                <div class="head-label">
                    <h4 class="card-title">Factory RCN Inward -
                        {{ $factory_rcn_inward_data->outwardRcnDetails->ewb_number }} <strong></strong></h4>
                </div>
                <div class="dt-action-buttons text-end">
                    <div class="dt-buttons d-inline-flex">
                        <a href="{{ url('factory/factory-rcn-inward') }}"
                            class="dt-button buttons-collection btn btn-outline-secondary me-2 waves-effect">Back</a>
                        @if (\Helper::userAccess('factory-inward-rcn-edit') && !$factory_rcn_inward_data->outwardRcnDetails->received_date_time)
                            <a href="{{ route('factory.factory-rcn-inward.edit', $factory_rcn_inward_data->slug) }}"
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Truck Reg No.</label>
                            <span
                                class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->truck_reg_number }}
                            </span>
                        </div>

                    </div>
                <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Lot No.</label>
                            <span
                                class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->stockyardRcnStockDetails->lot_number }}
                            </span>
                        </div>

                </div>
                <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Factory</label>
                            <span
                                class="form-control">{{ $factory_rcn_inward_data->factory->factory_name }}
                            </span>
                        </div>

                </div>
                 <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Contact No.</label>
                            <span class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->contact_number }}
                            </span>
                        </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">DC No.</label>
                        <span class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->dc_number }}
                        </span>
                    </div>
                </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">EWB No.</label>
                            <span class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->ewb_number }}
                            </span>
                        </div>
                    </div>
                    <div class="col-12">
                        <h5 class="my-2">
                            <i data-feather="package" class="font-medium-4 mr-25"></i>
                            <span class="align-middle">GD-Details</span>
                        </h5> 
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">RCN Bags (No.s)</label>
                            <span class="form-control">{{(int)$factory_rcn_inward_data->outwardRcnDetails->rcn_bags }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">RCN Net Weight</label>
                            <span
                                class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->rcn_net_weight }} Kg
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Tare Weight</label>
                            <span class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->tare_weight }} Kg
                            </span>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Out Turn </label>
                            <span class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->out_turn }} Lbs
                            </span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Nut Count(No.s)</label>
                            <span class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->nut_count }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Rejection</label>
                            <span class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->rejection }} %
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Moisture Level</label>
                            <span class="form-control">
                                {{ $factory_rcn_inward_data->outwardRcnDetails->moisture_level == 0 ? 'Dry' : ($factory_rcn_inward_data->outwardRcnDetails->moisture_level == 1 ? 'Semi' : 'Un Dry') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <h5 class="my-2">
                            <i data-feather="package" class="font-medium-4 mr-25"></i>
                            <span class="align-middle">GR-Details</span>
                        </h5> 
                    </div>
                    @if ($factory_rcn_inward_data->outwardRcnDetails->status == 2)
                        
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Received RCN Bags</label>
                            <span class="form-control">{{ (int)$factory_rcn_inward_data->rcn_bags }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Received RCN Net Weight</label>
                            <span class="form-control">{{ $factory_rcn_inward_data->rcn_net_weight }}
                            </span>
                        </div>
                    </div>

                    @else
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Received RCN Bags</label>
                            <span class="form-control">
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Received RCN Net Weight</label>
                            <span class="form-control">
                            </span>
                        </div>
                    </div>
                    @endif
                 
                    <div class="col-12">
                        <h5 class="my-2">
                            <i data-feather="truck" class="font-medium-4 mr-25"></i>
                            <span class="align-middle">Status</span>
                        </h5>   
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="dispatched_date_time">Dispatched Date Time</label>
                        <span class="form-control">
                            {{ $factory_rcn_inward_data->outwardRcnDetails->dispatched_date_time ? $factory_rcn_inward_data->outwardRcnDetails->dispatched_date_time->format('d-m-Y h:i:s') : 'Not dispatched yet' }}
                        </span>

                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="received_date_time">Received Date Time</label>
                        <span class="form-control">
                            {{ $factory_rcn_inward_data->outwardRcnDetails->received_date_time ? $factory_rcn_inward_data->outwardRcnDetails->received_date_time->format('d-m-Y h:i:s') : 'Not received yet' }}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="dispatched_date_time">Status</label>
                        <span class="form-control">
                            {{ $factory_rcn_inward_data->outwardRcnDetails->status == 1 ? 'Dispatched' : ($factory_rcn_inward_data->outwardRcnDetails->status == 2 ? 'Received' : 'Scheduled') }}
                        </span>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@extends('layouts/contentLayoutMaster ')
@section('title', 'RCN Stock Details')
@section('content')
    <div class="">
        <div class="card">
            <div class="card-header p-1 border-bottom">
                <div class="head-label">
                    <h4 class="card-title">RCN Stock - <strong>{{ $stockyard_rcn_data->lot_number }}</strong>
                    </h4>
                </div>
                <div class="dt-action-buttons text-end">
                    <div class="dt-buttons d-inline-flex">
                        <a href="{{ route('stockyard.rcn-stock') }}"
                            class="dt-button buttons-collection btn btn-outline-secondary me-2 waves-effect">Back</a>
                        @if (\Helper::userAccess('stockyard-rcn-edit'))
                            <a href="{{ route('stockyard.rcn-stock.edit', $stockyard_rcn_data->slug) }}"
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
                            <label class="form-label">Stockyard</label>
                            <span class="form-control">{{ $stockyard_rcn_data->stockyardDetails->stockyard_name }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Lot No.</label>
                            <span class="form-control">{{ $stockyard_rcn_data->lot_number }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Warehouse</label>
                            <span class="form-control">{{ $stockyard_rcn_data->warehouse->warehouse_name ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Account</label>
                            <span class="form-control">{{ $sub_account->account->account_name }} - {{ $sub_account->account_state }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Account Lot No.</label>
                            <span class="form-control">{{ $stockyard_rcn_data->account_lot_number}} </span>
                        </div>
                    </div>
                        
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Invoice Number</label>
                            <span class="form-control">{{ $stockyard_rcn_data->invoice_number }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">BE Number</label>
                            <span class="form-control">{{ $stockyard_rcn_data->be_number }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">BL Number</label>
                            <span class="form-control">{{ $stockyard_rcn_data->bl_number }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">RCN Mark</label>
                            <span class="form-control">{{Config::get('constants.rcn_marks')[$stockyard_rcn_data->rcn_mark]??'' }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Shipper Company</label>
                            <span class="form-control">{{ $stockyard_rcn_data->shipperCompany->shipper_company_name ?? null}}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Out Turn</label>
                            <span class="form-control">{{ $stockyard_rcn_data->out_turn }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Nut Count</label>
                            <span class="form-control">{{(int) $stockyard_rcn_data->nut_count }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Rejection</label>
                            <span class="form-control">{{ $stockyard_rcn_data->rejection }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">BL Despatched RCN Weight</label>
                            <span class="form-control">{{ $stockyard_rcn_data->bl_despatched_rcn_weight }} Kg</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">BL Despatched RCN Bags</label>
                            <span class="form-control"> {{ (int)$stockyard_rcn_data->bl_despatched_rcn_bags }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">No. of Containers</label>
                            <span class="form-control">{{(int) $stockyard_rcn_data->total_containers }} </span>
                        </div>
                    </div>
                    
                </div>
            <div class="row">
                    <div class="col-12">
                  
                        <h5 class="my-2">
                            <i data-feather="database" class="font-medium-4 mr-25"></i>
                            <span class="align-middle">Current Stock</span>
                        </h5>
                       
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">RCN  </label>
                            <span class="form-control">{{ $stockyard_rcn_data->balance_rcn_stock }} Kg</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Bags</label>
                            <span class="form-control">{{(int) $stockyard_rcn_data->balance_rcn_bag }} </span>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

@endsection

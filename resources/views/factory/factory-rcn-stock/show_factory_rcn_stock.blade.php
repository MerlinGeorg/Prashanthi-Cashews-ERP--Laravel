@extends('layouts/contentLayoutMaster ')
@section('title', 'Factory RCN Inward Details')
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header border-bottom p-1">
                <div class="head-label">
                    <h4 class="card-title">Factory RCN Stock -
                        {{ $factory_rcn_stock_data->factory_slug }}<strong></strong></h4>
                </div>
                <div class="dt-action-buttons text-end">
                    <div class="dt-buttons d-inline-flex">
                        <a href="{{ url('factory/rcn-stock') }}"
                            class="dt-button buttons-collection btn btn-outline-secondary me-2 waves-effect">Back</a>
                    </div>
                </div>
            </div>
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Stockyard Lot Number</label>
                            <span class="form-control">{{ $factory_rcn_stock_data->stockyardRcnDetails->lot_number }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Factory Name</label>
                            <span class="form-control">{{ $factory_rcn_stock_data->factoryDetails->factory_name }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Total RCN Factory Stock</label>
                            <span class="form-control">{{ $factory_rcn_stock_data->total_rcn_factory_stock }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Total RCN Bag</label>
                            <span class="form-control">{{ $factory_rcn_stock_data->total_rcn_bag }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Balance RCN Factory Stock</label>
                            <span class="form-control">{{ $factory_rcn_stock_data->balance_rcn_factory_stock }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Balance RCN Bag</label>
                            <span class="form-control">{{ $factory_rcn_stock_data->balance_rcn_bag }} </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
